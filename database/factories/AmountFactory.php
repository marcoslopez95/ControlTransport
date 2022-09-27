<?php

namespace Database\Factories;

use App\Models\Coin;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AmountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'coin_id'   => Coin::factory(),
            'quantity'  => $this->faker->randomNumber(2),
            'neto'      => $this->faker->randomNumber(2),
            'received'  => $this->faker->randomNumber(2),
        ];
    }
}
