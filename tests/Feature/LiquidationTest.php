<?php

namespace Tests\Feature;

use App\Models\Additional;
use App\Models\Coin;
use App\Models\Liquidation;
use App\Models\Office;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LiquidationTest extends TestCase
{
    use DatabaseTransactions;

    private function createToken()
    {
        $user = User::create([
            'first_name'    => 'Admin',
            'last_name'     => 'Admin',
            'email'         => 'admin@controltransport.com',
            'password'      => 'admin123admin',
            'role_id'       => 1
        ]);

        $token = $user->createToken('login')->plainTextToken;
        $pos = strpos($token, '|');
        $token = substr($token, $pos + 1);
        return $token;
    }

    private function CreateBolivar(): void
    {
        Coin::create([
            'name'  => 'Bolivar Digital',
            'symbol' => 'BsD'
        ]);
    }

    private function createIGTF(): void
    {
        $coin = Coin::firstOrCreate([
            'name'  => 'Bolivar Digital',
            'symbol' => 'BsD'
        ]);

        Additional::create([
            'description'   =>  'Impuesto a Grandes Transacciones Financieras',
            'percent'       =>  3,
            'quantity'      =>  null,
            'coin_id'       =>  $coin->id,
            'type'          =>  'Descuento',
        ]);
    }

    public function test_crear_liquidacion_exitoso()
    {
        //$this->withoutExceptionHandling();
        self::CreateBolivar();
        self::createIGTF();
        $vehicle    = Vehicle::factory(1)->create();
        $coin       = Coin::factory(2)->create();
        $offices    = Office::factory(2)->create();
        $additional = Additional::create([
            'description'   =>  'Descuentro',
            'percent'       =>  12,
            'quantity'      =>  null,
            'coin_id'       =>  $coin[0]->id,
            'type'          =>  'Descuento',
        ]);
        $additional2 = Additional::create([
            'description'   =>  'Descuentro',
            'percent'       =>  null,
            'quantity'      =>  5,
            'coin_id'       =>  $coin[0]->id,
            'type'          =>  'Descuento',
        ]);

        $date = Carbon::now()->format('Y-m-d');

        $data = [
            'type_travel'   => "Salida",
            'vehicle_id'    => $vehicle[0]->id,
            'precio_pasaje' => 12,
            "fecha_express" => "2022-08-16",
            "number_express"=> "001",
            'coin_id'       => $coin[0]->id,
            'date'          => $date,
            'pasajeros'     => 3,
            'falta'         => 0,
            'office_origin' => $offices[0]->id,
            'office_destiny' => $offices[1]->id,
            'additionals'   => [
                $additional->id,
                $additional2->id
            ],
            'ammounts'      => [
                [

                    'coin_id'    => $coin[0]->id,
                    'quantity'   => 50.4,
                    'neto'       => 50,
                    'received'   => 50,
                ],
                [

                    'coin_id'    => $coin[1]->id,
                    'quantity'   => 5,
                    'neto'       => 5,
                    'received'   => 5,
                ],
            ]
        ];
        $response = $this->postJson('api/liquidations',$data , [
            'Authorization' => 'Bearer ' . self::createToken()
        ]);

        $response
            ->assertCreated()
            ->assertExactJson([
                'success' => true,
                'message' => 'Creado con éxito',
                'data'    => [
                    'id'            => 1,
                    'total'         => 26.68,
                    "type_travel"   => "Salida",
                    "fecha_express" => "2022-08-16",
                    "number_express"=> "001",
                    'vehicle_id'    => $vehicle[0]->id,
                    'precio_pasaje' => 12,
                    'coin_id'       => $coin[0]->id,
                    'date'          => $date,
                    'pasajeros'     => 3,
                    'falta'         => 0,
                    'office_origin' => $offices[0]->id,
                    'office_destiny' => $offices[1]->id,
                    'additionals'   => [
                        $additional->id,
                        $additional2->id
                    ],
                    'ammounts'      => [
                        [
                            'coin_id'    => $coin[0]->id,
                            'quantity'   => 50.4,
                            'neto'       => 50,
                            'received'   => 50,
                        ],
                        [

                            'coin_id'    => $coin[1]->id,
                            'quantity'   => 5,
                            'neto'       => 5,
                            'received'   => 5,
                        ],
                    ]
                ],
                'count'   => 15
            ]);
        $liquidation = Liquidation::first();

        ####### Verificando datos en la tabla liquidacion ########
        $this->assertEquals(26.68, $liquidation->total);
        $this->assertEquals($vehicle[0]->id, $liquidation->vehicle_id);
        $this->assertEquals(12, $liquidation->precio_pasaje);
        $this->assertEquals($coin[0]->id, $liquidation->coin_id);
        $this->assertEquals($date, $liquidation->date);
        $this->assertEquals(3, $liquidation->pasajeros);
        $this->assertEquals(0, $liquidation->falta);
        $this->assertEquals($offices[0]->id, $liquidation->office_origin);
        $this->assertEquals($offices[1]->id, $liquidation->office_destiny);
        $this->assertEquals("14-08-2022",$this->liquidation->fecha_express);
        $this->assertEquals("001",$this->liquidation->number_express);
        ##############################

        # Verificando Relaciones
        // $rel_additional = DB::table('additional_liquidation')
        //     ->whereIn('additional_id', [$additional->id, $additional2->id])
        //     ->get();
        $this->assertCount(2, $liquidation->additionals);

        // $rel_ammount = $liquidation->ammounts;
        $this->assertCount(2, $liquidation->ammounts);
    }

    public function test_mostrar_liquidacion_exitoso()
    {
        $this->withoutExceptionHandling();
        self::CreateBolivar();
        self::createIGTF();
        $vehicle    = Vehicle::factory(1)->create();
        $coin       = Coin::factory(2)->create();
        $offices    = Office::factory(2)->create();
        $additional = Additional::create([
            'description'   =>  'Descuentro',
            'percent'       =>  12,
            'quantity'      =>  null,
            'coin_id'       =>  $coin[0]->id,
            'type'          =>  'Descuento',
        ]);
        $additional2 = Additional::create([
            'description'   =>  'Descuentro',
            'percent'       =>  null,
            'quantity'      =>  5,
            'coin_id'       =>  $coin[0]->id,
            'type'          =>  'Descuento',
        ]);
        $date = Carbon::now()->format('Y-m-d');
        $liquidation = Liquidation::create([
            'vehicle_id'    => $vehicle[0]->id,
            'precio_pasaje' => 12,
            'coin_id'       => $coin[0]->id,
            'date'          => $date,
            'total'         => 26.68,
            'falta'         =>0,
            'pasajeros'     => 3,
            'falta'         => 0,
            'office_origin' => $offices[0]->id,
            'office_destiny' => $offices[1]->id,
        ]);
        $liquidation->additionals()->attach([
            $additional->id,
            $additional2->id
        ]);
        $liquidation->ammounts()->createMany([
            [

                'coin_id'    => $coin[0]->id,
                'quantity'   => 50.4,
                'neto'       => 50,
                'received'   => 50,
            ],
            [

                'coin_id'    => $coin[1]->id,
                'quantity'   => 5,
                'neto'       => 5,
                'received'   => 5,
            ]
        ]);

        $response = $this->getJson('api/liquidations/' . $liquidation->id, [
            'Authorization' => 'Bearer ' . self::createToken()
        ]);
        $liquidation = Liquidation::first();

        $response
            ->assertOk()
            ->assertExactJson([
                'success' => true,
                'message' => 'Show con éxito',
                'data'    => [
                    'id'            => $liquidation->id,
                    'total'         => 26.68,
                    'vehicle_id'    => $vehicle[0]->id,
                    'precio_pasaje' => 12,
                    'coin_id'       => $coin[0]->id,
                    'date'          => $date,
                    'pasajeros'     => 3,
                    'falta'         => 0,
                    'office_origin' => $offices[0]->id,
                    'office_destiny' => $offices[1]->id,
                    'name_office_origin' => $offices[0]->name,
                    'name_office_destiny' => $offices[1]->name,
                    'additionals'   => [
                        [
                            'id'            =>  $liquidation->additionals[0]->id,
                            'description'   =>  $liquidation->additionals[0]->description,
                            'percent'       =>  $liquidation->additionals[0]->percent,
                            'quantity'      =>  $liquidation->additionals[0]->quantity,
                            'coin_id'       =>  $liquidation->additionals[0]->coin_id,
                            'type'          =>  $liquidation->additionals[0]->type,
                        ],
                        [
                            'id'            => $liquidation->additionals[1]->id,
                            'description'   => $liquidation->additionals[1]->description,
                            'percent'       => $liquidation->additionals[1]->percent,
                            'quantity'      => $liquidation->additionals[1]->quantity,
                            'coin_id'       => $liquidation->additionals[1]->coin_id,
                            'type'          => $liquidation->additionals[1]->type,
                        ],
                    ],
                    'ammounts'      => [
                        [
                            'id'              => $liquidation->ammounts[0]->id,
                            'amountable_id'   => $liquidation->ammounts[0]->amountable_id,
                            'amountable_type' => $liquidation->ammounts[0]->amountable_type,
                            'coin_id'         => $liquidation->ammounts[0]->coin_id,
                            'quantity'        => $liquidation->ammounts[0]->quantity,
                            'neto'            => $liquidation->ammounts[0]->neto,
                            'received'        => $liquidation->ammounts[0]->received,
                        ],
                        [
                            'id'              => $liquidation->ammounts[1]->id,
                            'amountable_id'   => $liquidation->ammounts[1]->amountable_id,
                            'amountable_type' => $liquidation->ammounts[1]->amountable_type,
                            'coin_id'         => $liquidation->ammounts[1]->coin_id,
                            'quantity'        => $liquidation->ammounts[1]->quantity,
                            'neto'            => $liquidation->ammounts[1]->neto,
                            'received'        => $liquidation->ammounts[1]->received,
                        ],


                    ],
                    'coin' => $liquidation->coin,
                    'vehicle' => [
                        'id'          => $liquidation->vehicle->id,
                        'plate'       => $liquidation->vehicle->plate,
                        'num_control' => $liquidation->vehicle->num_control,
                        'description' => $liquidation->vehicle->description,
                        'status'      => $liquidation->vehicle->status,
                        'partner_id'  => $liquidation->vehicle->partner_id,
                    ]
                ],
                'count'   => 1
            ]);
    }

    public function test_editar_liquidacion_exitoso()
    {
        $this->withoutExceptionHandling();
        self::CreateBolivar();
        self::createIGTF();
        $vehicle    = Vehicle::factory(1)->create();
        $coin       = Coin::factory(2)->create();
        $offices    = Office::factory(2)->create();
        $additional = Additional::create([
            'description'   =>  'Descuentro',
            'percent'       =>  12,
            'quantity'      =>  null,
            'coin_id'       =>  $coin[0]->id,
            'type'          =>  'Descuento',
        ]);
        $additional2 = Additional::create([
            'description'   =>  'Descuentro',
            'percent'       =>  null,
            'quantity'      =>  5,
            'coin_id'       =>  $coin[0]->id,
            'type'          =>  'Descuento',
        ]);
        $date = Carbon::now()->format('Y-m-d');
        $liquidation = Liquidation::create([
            'vehicle_id'    => $vehicle[0]->id,
            'precio_pasaje' => 12,
            'coin_id'       => $coin[0]->id,
            'date'          => $date,
            'total'         => 26.68,
            'falta'         =>0,
            'pasajeros'     => 3,
            'falta'         => 0,
            'office_origin' => $offices[0]->id,
            'office_destiny' => $offices[1]->id,
        ]);
        $liquidation->additionals()->attach([
            $additional->id,
            $additional2->id
        ]);
        $liquidation->ammounts()->createMany([
            [

                'coin_id'    => $coin[0]->id,
                'quantity'   => 50.4,
                'neto'       => 50,
                'received'   => 50,
            ],
            [

                'coin_id'    => $coin[1]->id,
                'quantity'   => 5,
                'neto'       => 5,
                'received'   => 5,
            ]
        ]);

        $response = $this->putJson('api/liquidations/' . $liquidation->id,
            [
                'vehicle_id'    => $vehicle[0]->id,
                'precio_pasaje' => 12,
                'coin_id'       => $coin[0]->id,
                'date'          => $date,
                'pasajeros'     => 3,
                'falta'         => 0,
                'office_origin' => $offices[1]->id,
                'office_destiny' => $offices[0]->id,
                'additionals'   => [
                    $additional->id,
                ],
                'ammounts'      => [
                    [

                        'coin_id'    => $coin[1]->id,
                        'quantity'   => 5,
                        'neto'       => 5,
                        'received'   => 5,
                    ],
                ]
            ]
        , [
            'Authorization' => 'Bearer ' . self::createToken()
        ]);

        $liquidation = Liquidation::first();
        $liquidation->load(['ammounts','additionals']);
        $response
            ->assertStatus(205)
            ->assertExactJson([
                'success' => true,
                'message' => 'Editado con éxito',
                'data'    => [
                    'id'            => $liquidation->id,
                    'total'         => 31.68,
                    'vehicle_id'    => $vehicle[0]->id,
                    'precio_pasaje' => 12,
                    'coin_id'       => $coin[0]->id,
                    'date'          => $date,
                    'pasajeros'     => 3,
                    'falta'         => 0,
                    'office_origin' => $offices[1]->id,
                    'office_destiny' => $offices[0]->id,
                    'name_office_origin' => $offices[1]->name,
                    'name_office_destiny' => $offices[0]->name,
                    'additionals'   => [
                        [
                            'id' => $additional->id,
                            'description'   =>  'Descuentro',
                            'percent'       =>  12,
                            'quantity'      =>  null,
                            'coin_id'       =>  $coin[0]->id,
                            'type'          =>  'Descuento',
                        ]
                    ],
                    'ammounts'      => [
                        [
                            'id'              => $liquidation->ammounts[0]->id,
                            'amountable_id'   => $liquidation->ammounts[0]->amountable_id,
                            'amountable_type' => $liquidation->ammounts[0]->amountable_type,
                            'coin_id'         => $liquidation->ammounts[0]->coin_id,
                            'quantity'        => $liquidation->ammounts[0]->quantity,
                            'neto'            => $liquidation->ammounts[0]->neto,
                            'received'        => $liquidation->ammounts[0]->received,
                        ],
                    ],
                    'coin' => $liquidation->coin,
                    'vehicle' => [
                        'id'          => $liquidation->vehicle->id,
                        'plate'       => $liquidation->vehicle->plate,
                        'num_control' => $liquidation->vehicle->num_control,
                        'description' => $liquidation->vehicle->description,
                        'status'      => $liquidation->vehicle->status,
                        'partner_id'  => $liquidation->vehicle->partner_id,
                    ]

                ],
                'count'   => 1
            ]);



        ####### Verificando datos en la tabla liquidacion ########
        $this->assertEquals(31.68, $liquidation->total);
        $this->assertEquals($vehicle[0]->id, $liquidation->vehicle_id);
        $this->assertEquals(12, $liquidation->precio_pasaje);
        $this->assertEquals($coin[0]->id, $liquidation->coin_id);
        $this->assertEquals($date, $liquidation->date);
        $this->assertEquals(3, $liquidation->pasajeros);
        $this->assertEquals(0, $liquidation->falta);
        $this->assertEquals($offices[1]->id, $liquidation->office_origin);
        $this->assertEquals($offices[0]->id, $liquidation->office_destiny);
        ##############################

        # Verificando Relaciones
        $this->assertCount(1, $liquidation->additionals);
        $this->assertCount(1, $liquidation->ammounts);
    }

    public function test_eliminar_liquidacion_exitoso(){
        $this->withoutExceptionHandling();
        self::CreateBolivar();
        self::createIGTF();
        $vehicle    = Vehicle::factory(1)->create();
        $coin       = Coin::factory(2)->create();
        $offices    = Office::factory(2)->create();
        $additional = Additional::create([
            'description'   =>  'Descuentro',
            'percent'       =>  12,
            'quantity'      =>  null,
            'coin_id'       =>  $coin[0]->id,
            'type'          =>  'Descuento',
        ]);
        $additional2 = Additional::create([
            'description'   =>  'Descuentro',
            'percent'       =>  null,
            'quantity'      =>  5,
            'coin_id'       =>  $coin[0]->id,
            'type'          =>  'Descuento',
        ]);
        $date = Carbon::now()->format('Y-m-d');
        $liquidation = Liquidation::create([
            'vehicle_id'    => $vehicle[0]->id,
            'precio_pasaje' => 12,
            'coin_id'       => $coin[0]->id,
            'date'          => $date,
            'total'         => 26.68,
            'falta'         =>0,
            'pasajeros'     => 3,
            'falta'         => 0,
            'office_origin' => $offices[0]->id,
            'office_destiny' => $offices[1]->id,
        ]);
        $liquidation->additionals()->attach([
            $additional->id,
            $additional2->id
        ]);
        $liquidation->ammounts()->createMany([
            [

                'coin_id'    => $coin[0]->id,
                'quantity'   => 50.4,
                'neto'       => 50,
                'received'   => 50,
            ],
            [

                'coin_id'    => $coin[1]->id,
                'quantity'   => 5,
                'neto'       => 5,
                'received'   => 5,
            ]
        ]);

        $response = $this->deleteJson('api/liquidations/'.$liquidation->id,[],[
            'Authorization' => 'Bearer ' . self::createToken()
        ]);

        $response
            ->assertStatus(202);

        $bool = Liquidation::all();

        $this->assertCount(0,$bool);
    }
}
