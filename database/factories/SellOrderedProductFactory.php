<?php

namespace Database\Factories;

use App\Models\CompositProduct;
use App\Models\SellOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class SellOrderedProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'quantity' => rand(100,300),
            'notes' => $this->faker->sentence(),
            'composit_product_id' => CompositProduct::all()->random()->id,
            'sell_order_id' => SellOrder::all()->random()->id,
        ];
    }
}
