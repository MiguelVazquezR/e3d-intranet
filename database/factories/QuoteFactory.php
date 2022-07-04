<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::all()->random()->id,
            'authorized_user_id' => User::all()->random()->id,
            'currency_id' => Currency::all()->random()->id,
            'customer_id' => rand(1, 5),
            'receiver' => $this->faker->sentence(3),
            'department' => $this->faker->sentence(2),
            'first_production_days' => $this->faker->sentence(2),
            'tooling_cost' => rand(5, 364) . $this->faker->randomElement(['$MXN', '$USD']),
            'freight_cost' => rand(5, 364) . $this->faker->randomElement(['$MXN', '$USD']),
        ];
    }
}
