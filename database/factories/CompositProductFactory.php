<?php

namespace Database\Factories;

use App\Models\Company;
use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompositProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $fecha = new DateTime();

        return [
            'image' => 'public/composit-products/' . $this->faker->image('public/storage/composit-products', 640, 480, null, false),
            'alias' => $this->faker->word(),
            'old_price' => rand(31, 86),
            'new_price' => rand(31, 86),
            'new_price_currency' => $this->faker->randomElement(['$MXN', '$USD']),
            'company_id' => Company::all()->random()->id,
        ];
    }
}
