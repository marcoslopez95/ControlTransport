<?php

namespace Database\Factories;

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
            'percent'       =>$this->faker->optional()->randomFloat(2),
            'type'          =>$this->faker->randomElement(['Descuento','Retencion']),
        ];
    }
}
