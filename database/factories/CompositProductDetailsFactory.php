<?php

namespace Database\Factories;

use App\Models\CompositProduct;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompositProductDetailsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'quantity' => rand(1,2),
            'notes' => $this->faker->word(),
            'product_id' => $this->faker->randomDigitNotZero(),
            'composit_product_id' => CompositProduct::all()->random()->id,
        ];
    }
}
