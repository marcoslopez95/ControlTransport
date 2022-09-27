<?php

namespace Database\Factories;

use App\Models\Coin;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Additional>
 */
class AdditionalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'description'   =>$this->faker->word(),
            'coin_id'       => Coin::factory(),
            'percent'       =>$this->faker->optional()->randomFloat(2),
            'type'          =>$this->faker->randomElement(['Descuento','Retencion']),
        ];
    }
}
