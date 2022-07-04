<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'model_id' => rand(1, 10),
            'model_name' => $this->faker->randomElement([Customer::class, Supplier::class]),
            'name' =>
            $this->faker->firstName($this->faker->randomElement(['male', 'female'])) . ' ' .
                $this->faker->lastName(),
            'email' => $this->faker->email(),
            'phone' => $this->faker->phoneNumber(),
        ];
    }
}
