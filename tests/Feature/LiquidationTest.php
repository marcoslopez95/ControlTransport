<?php

namespace Tests\Feature;

use App\Models\Additional;
use App\Models\Coin;
use App\Models\Office;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LiquidationTest extends TestCase
{
    use RefreshDatabase;

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
            'symbol'=> 'BsD'
        ]);
    }

    private function createIGTF(){
        $coin = Coin::firstOrCreate([
            'name'  => 'Bolivar Digital',
            'symbol'=> 'BsD'
        ]);

        Additional::create([
            'description'   =>  'Impuesto a Grandes Transacciones Financieras',
            'percent'       =>  3,
            'quantity'      =>  null,
            'coin_id'       =>  $coin->id,
            'type'          =>  'Descuento',   
        ]);
    }

    public function test_crear_liquidacion_exitoso(){
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
        $response = $this->postJson('api/liquidations',[
            'vehicle_id'    => $vehicle[0]->id,
            'precio_pasaje' => 12,
            'coin_id'       => $coin[0]->id,
            'date'          => $date,
            'pasajeros'     => 3,
            'additionals'   =>[
                $additional->id,
                $additional2->id
            ],
            'office_origin' => $offices[0]->id,
            'office_destiny' => $offices[1]->id,
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
        ], [
            'Authorization' => 'Bearer ' . self::createToken()
        ]);

        $response
            ->assertCreated()
            ->assertExactJson([
                'success' => true,
                'message' => 'Creado con éxito',
                'data'    => [
                    'vehicle_id'    => $vehicle[0]->id,
                    'precio_pasaje' => 12,
                    'coin_id'       => $coin[0]->id,
                    'date'          => $date,
                    'pasajeros'     => 3,
                    'additionals'   =>[
                        $additional->id,
                        $additional2->id
                    ]
                ],
                'count'   => 1
            ]);
    }
}
