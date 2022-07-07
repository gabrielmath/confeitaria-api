<?php

namespace Database\Factories;

use App\Models\Cake;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WaitingList>
 */
class WaitingListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'cake_id' => Cake::factory(),
            'name'    => $this->faker->name,
            'email'   => $this->faker->unique()->safeEmail()
        ];
    }
}
