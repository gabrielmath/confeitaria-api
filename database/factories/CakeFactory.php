<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cake>
 */
class CakeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name'               => $this->faker->name,
            'weight'             => $this->faker->numberBetween(500, 2000),
            'value'              => $this->faker->randomFloat(2, 50, 200),
            'available_quantity' => $this->faker->numberBetween(3, 15),
        ];
    }
}
