<?php

namespace Database\Factories;

use App\Models\Additional;
use App\Models\Amount;
use App\Models\Coin;
use App\Models\Office;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Liquidation>
 */
class LiquidationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'type_travel'    => $this->faker->optional(.8,'Entrada')->randomElement(['Salida']),
            'vehicle_id'     => Vehicle::factory(),
            'coin_id'        => Coin::factory(),
            'office_origin'  => Office::factory(),
            'office_destiny' => Office::factory(),
            'date'           => Carbon::now(),
            'precio_pasaje'  => $this->faker->randomNumber(2),
            'pasajeros'      => $this->faker->randomNumber(2),
            'total'          => function(array $attributes){
                return $attributes['precio_pasaje'] * $attributes['total'];
            },
            'falta'          => $this->faker->optional()->randomNumber(2),
            'additionals'    => Additional::factory(3),
            'ammounts'       => Amount::factory(3)
        ];
    }
}
