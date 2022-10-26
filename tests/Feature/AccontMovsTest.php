<?php

namespace Tests\Feature;

use App\Models\Additional;
use App\Models\Coin;
use App\Models\Liquidation;
use App\Models\Office;
use App\Models\Travel;
use App\Models\User;
use App\Models\Vehicle;
use App\Traits\TravelTrait;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccontMovsTest extends TestCase
{
    use DatabaseTransactions,
        WithFaker,
        TravelTrait;

    /**
     * @test
     */
    public function show_account_movs_of_a_vehicle()
    {
        $vehicle = Vehicle::find(2);
        $user = User::firstWhere('email', 'admin@controltransport.com');
        $response = $this
            ->actingAs($user)
            ->getJson(route('api.v1.show-account-movs', [
                'vehicle' => $vehicle->id
            ]));


        $response
            ->assertOk()
            ->assertExactJson([
                'success' => true,
                'message' => 'Caja Chica del vehiculo',
                'data'    => [
                    'vehicle_id'    => $vehicle->id,
                    'vehicle'       => $vehicle,
                    'count_travels' => $vehicle->travels->count(),
                    'caja-chica'  => [
                        'coin_id' => 1,
                        'coin_name' => 'dolar',
                        'sum'   => 990
                    ]
                ],
                'count'   => 1
            ]);
    }

    /**
     * @test
     */
    public function create_an_account_mov_successfully()
    {
    }
}
