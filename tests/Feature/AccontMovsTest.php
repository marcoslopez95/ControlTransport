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
use Tests\TestCase;

class AccontMovsTest extends TestCase
{
    use DatabaseTransactions,
        WithFaker;



    private function createToken()
    {
        $user = User::create([
            'first_name'    => 'Admin',
            'last_name'     => 'Admin',
            'email'         => 'admin@controltransport.com',
            'password'      => 'admin123admin',
            'role_id'       => 1
        ]);
        return $user;
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

    /**
     * @test
     */
    public function show_account_movs_of_a_vehicle()
    {
        $liquidation = Liquidation::factory()->create();

        dd($liquidation);
        $data = [
            'vehicle' => 'asd'
        ];
        $response = $this->getJson(route('api.v1.show-account-movs'));
    }

    private function createLiquidation()
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
            "number_express" => "001",
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
        return $data;
    }
}
