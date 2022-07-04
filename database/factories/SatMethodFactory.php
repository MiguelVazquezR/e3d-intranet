<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SatMethodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'key' => $this->faker->randomElement(['PUE', 'PPD']),
            'description' => $this->faker->sentence(2),
        ];
    }
}
