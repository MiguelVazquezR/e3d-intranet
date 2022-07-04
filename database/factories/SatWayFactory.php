<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SatWayFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'key' => rand(1,33),
            'description' => $this->faker->sentence(2),
        ];
    }
}
