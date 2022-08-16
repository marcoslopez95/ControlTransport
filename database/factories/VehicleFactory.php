<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'plate' => $this->faker->regexify('\w{5}'),
            'num_control' => $this->faker->regexify('\d{1,100}'),
            'description' => $this->faker->text(50),
            'status' => $this->faker->randomElement(['Operativo','En Reparación','Averiado']),
        ];
    }
}
