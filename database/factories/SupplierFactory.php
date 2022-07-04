<?php

namespace Database\Factories;

use App\Models\BankData;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence(2),
            'address' => $this->faker->address(),
            'post_code' => $this->faker->postcode(),
            'company_id' => Company::all()->random()->id
        ];
    }
}
