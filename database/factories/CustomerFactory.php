<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\SatMethod;
use App\Models\SatMethods;
use App\Models\SatType;
use App\Models\SatWay;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
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
            'sat_method_id' => SatMethod::all()->random()->id,
            'sat_type_id' => SatType::all()->random()->id,
            'sat_way_id' => SatWay::all()->random()->id,
            'company_id' => Company::all()->random()->id,
        ];
    }
}
