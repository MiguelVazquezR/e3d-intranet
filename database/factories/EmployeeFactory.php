<?php

namespace Database\Factories;

use App\Models\Bonus;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'salary' => rand(19.8, 45.85),
            'hours_per_week' => $this->faker->randomElement([36, 48]),
            'discounts' => rand(0, 50),
            'bonuses' => $this->faker->randomElement( [Bonus::all()->random()->id,null] ),
            'days_off' => '2|3',
            'check_in_times' => $this->faker->randomElement( ['07:00','07:00|07:00|08:00|09:00|08:30|07:00'] ),
            'job_position' => $this->faker->word(),
            'department_id' => Department::all()->random()->id,
            'user_id' => $this->faker->unique()->randomDigitNotZero(),
            'birth_date' => '2000-09-10',
        ];
    }
}
