<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BonusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'full_time' => 175,
            'half_time' => 117,
            'name' => $this->faker->word(),
        ];
    }
}
