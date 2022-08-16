<?php

namespace Tests\Feature;

use App\Events\NewLiquidationRegisteredEvent;
use App\Models\Additional;
use App\Models\Coin;
use App\Models\Liquidation;
use App\Models\Office;
use App\Models\Travel;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TravelTest extends TestCase
{
    use RefreshDatabase;

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

    private function createLiquidation($type = 'Salida', $nLiquid = 1){
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
        for($i = 0; $i < $nLiquid; $i++){

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

            if($i == $nLiquid-1){
                $type = 'Llegada';
            }
            event(new NewLiquidationRegisteredEvent($liquidation,$type));
        }


        return $vehicle[0];
    }

    public function test_mostrar_todos_viajes(){
        $vehicle = self::createLiquidation();

        $response = $this->getJson('api/travel/',[
            'Authorization' => 'Bearer '.self::createToken()
        ]);

        $travel = Travel::first();
        $response
            ->assertOk()
            ->assertExactJson([
                'success' => true,
                'message' => 'index',
                'data'    => [
                    [
                        'id'         => $travel->id,
                        'status'     => $travel->status,
                        'date_start' => $travel->date_start,
                        'date_end'   => $travel->date_end,
                        'observation'=> $travel->observation,
                        'vehicle_id' => $travel->vehicle_id,
                        'vehicle'    => [
                            'id'          =>$vehicle->id,
                            'plate'       =>$vehicle->plate,
                            'num_control' =>$vehicle->num_control,
                            'description' =>$vehicle->description,
                            'status'      =>$vehicle->status,
                            'partner_id'  =>$vehicle->partner_id,
                        ]
                    ]
                ],
                'count'   => 1
            ]);
    }

    public function test_mostrar_viajes_de_un_vehiculo(){
        $vehicle = self::createLiquidation();
        $vehicle2 = self::createLiquidation();


        $response = $this->getJson('api/travel?vehicle_id='.$vehicle->id,[
            'Authorization' => 'Bearer '.self::createToken()
        ]);

        $travel = Travel::first();
        $response
            ->assertOk()
            ->assertExactJson([
                'success' => true,
                'message' => 'index',
                'data'    => [
                    [
                        'id'         => $travel->id,
                        'status'     => $travel->status,
                        'date_start' => $travel->date_start,
                        'date_end'   => $travel->date_end,
                        'observation'=> $travel->observation,
                        'vehicle_id' => $travel->vehicle_id,
                        'vehicle'    => [
                            'id'          =>$vehicle->id,
                            'plate'       =>$vehicle->plate,
                            'num_control' =>$vehicle->num_control,
                            'description' =>$vehicle->description,
                            'status'      =>$vehicle->status,
                            'partner_id'  =>$vehicle->partner_id,
                        ]
                    ]
                ],
                'count'   => 1
            ]);
    }

    public function test_mostrar_todas_las_liquidaciones_de_un_viajes(){
        $vehicle = self::createLiquidation('Salida',3);


        $travel = Travel::first();
        $response = $this->getJson('api/travel/'.$travel->id,[
            'Authorization' => 'Bearer '.self::createToken()
        ]);

        $response
            ->assertOk()
            ->assertExactJson([
                'success' => true,
                'message' => 'Show con éxito',
                'data'    => [
                        'id'         => $travel->id,
                        'status'     => 'Finalizado',
                        'date_start' => $travel->date_start,
                        'date_end'   => $travel->date_end,
                        'observation'=> $travel->observation,
                        'vehicle_id' => $travel->vehicle_id,
                        'vehicle'    => [
                            'id'          =>$vehicle->id,
                            'plate'       =>$vehicle->plate,
                            'num_control' =>$vehicle->num_control,
                            'description' =>$vehicle->description,
                            'status'      =>$vehicle->status,
                            'partner_id'  =>$vehicle->partner_id,
                        ],
                        'liquidations' => [
                            [
                                'id'            => $travel->liquidations[0]->id,
                                'vehicle_id'    => $travel->liquidations[0]->vehicle_id,
                                'precio_pasaje' => $travel->liquidations[0]->precio_pasaje,
                                'coin_id'       => $travel->liquidations[0]->coin_id,
                                'date'          => $travel->liquidations[0]->date,
                                'total'         => $travel->liquidations[0]->total,
                                'falta'         => $travel->liquidations[0]->falta,
                                'pasajeros'     => $travel->liquidations[0]->pasajeros,
                                'office_origin' => $travel->liquidations[0]->office_origin,
                                'office_destiny'=> $travel->liquidations[0]->office_destiny,
                                'travel_id'     => $travel->liquidations[0]->travel_id,
                            ],
                            [
                                'id'            => $travel->liquidations[1]->id,
                                'vehicle_id'    => $travel->liquidations[1]->vehicle_id,
                                'precio_pasaje' => $travel->liquidations[1]->precio_pasaje,
                                'coin_id'       => $travel->liquidations[1]->coin_id,
                                'date'          => $travel->liquidations[1]->date,
                                'total'         => $travel->liquidations[1]->total,
                                'falta'         => $travel->liquidations[1]->falta,
                                'pasajeros'     => $travel->liquidations[1]->pasajeros,
                                'office_origin' => $travel->liquidations[1]->office_origin,
                                'office_destiny'=> $travel->liquidations[1]->office_destiny,
                                'travel_id'     => $travel->liquidations[1]->travel_id,
                            ],
                            [
                                'id'            => $travel->liquidations[2]->id,
                                'vehicle_id'    => $travel->liquidations[2]->vehicle_id,
                                'precio_pasaje' => $travel->liquidations[2]->precio_pasaje,
                                'coin_id'       => $travel->liquidations[2]->coin_id,
                                'date'          => $travel->liquidations[2]->date,
                                'total'         => $travel->liquidations[2]->total,
                                'falta'         => $travel->liquidations[2]->falta,
                                'pasajeros'     => $travel->liquidations[2]->pasajeros,
                                'office_origin' => $travel->liquidations[2]->office_origin,
                                'office_destiny'=> $travel->liquidations[2]->office_destiny,
                                'travel_id'     => $travel->liquidations[2]->travel_id,
                            ],
                        ]
                ],
                'count'   => 1
        ]);
    }

    public function test_editar_observacion_de_un_viaje(){
        $vehicle = self::createLiquidation();


        $travel = Travel::first();
        $response = $this->putJson('api/travel/'.$travel->id,[
            'observation' => 'nueva obsevacion'
        ],[
            'Authorization' => 'Bearer '.self::createToken()
        ]);

        $response
            ->assertStatus(205)
            ->assertExactJson([
                'success' => true,
                'message' => 'Editado con éxito',
                'data'    => [
                        'id'         => $travel->id,
                        'status'     => $travel->status,
                        'date_start' => $travel->date_start,
                        'date_end'   => $travel->date_end,
                        'observation'=> 'nueva obsevacion',
                        'vehicle_id' => $travel->vehicle_id,
                ],
                'count'   => 1
        ]);

        $travel->refresh();

        $this->assertEquals('nueva obsevacion',$travel->observation);
    }
}
