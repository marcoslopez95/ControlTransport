<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Office>
 */
class OfficeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name'          => $this->faker->unique()->word(),
            'description'   => $this->faker->optional(.4)->text(),
            'type'          => $this->faker->randomElement(['Priv.','Pub.'])
        ];
    }
}
