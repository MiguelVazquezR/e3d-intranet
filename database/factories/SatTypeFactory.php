<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SatTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'key' => $this->faker->word(),
            'description' => $this->faker->sentence(2),
        ];
    }
}
