<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'location' => $this->faker->word(),
            'quantity' => rand(1,5000),
            'product_id' => Product::all()->random()->id,
            'image' => 'public/stock-products/' . $this->faker->image('public/storage/stock-products', 640, 480, null, false),
        ];
    }
}
