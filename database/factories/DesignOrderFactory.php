<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\DesignType;
use App\Models\MeasurementUnit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DesignOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'designer_id' => User::all()->random()->id,
            'customer_id' => Customer::all()->random()->id,
            'contact_name' => $this->faker->firstName($this->faker->randomElement(['male', 'female'])) . ' ' .
            $this->faker->lastName(),
            'user_id' => User::all()->random()->id,
            'design_name' => $this->faker->sentence(2),
            'design_type_id' => DesignType::all()->random()->id,
            'tentative_end' => '2022-03-04 13:59:00',
            'design_data' => $this->faker->text(50),
            'measurement_unit_id' => MeasurementUnit::all()->random()->id,
            'especifications' => $this->faker->text(40),
            'plans_image' => 'public/design-plans/' . $this->faker->image('public/storage/design-plans', 640, 480, null, false),
            'logo_image' => 'public/design-logos/' . $this->faker->image('public/storage/design-logos', 640, 480, null, false),
            'pantones' => $this->faker->word(),
            'authorized_user_id' => $this->faker->randomElement([User::all()->random()->id, null]),
        ];
    }
}
