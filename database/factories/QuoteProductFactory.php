<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Quote;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuoteProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
           'quote_id' => Quote::all()->random()->id,
           'product_id' => Product::all()->random()->id,
           'quantity' => rand(5,1250),
           'price' => rand(5,444),
        ];
    }
}
