<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'bussiness_name' => $this->faker->sentence(3),
            'phone' => $this->faker->phoneNumber(),
            'rfc' => 'ascd12345678',
            'post_code' => $this->faker->postcode(),
            'fiscal_address' => $this->faker->address()
        ];
    }
}
