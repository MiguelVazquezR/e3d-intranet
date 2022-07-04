<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\StockActionType;
use App\Models\StockProduct;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockMovementFactory extends Factory
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
            'stock_product_id' => StockProduct::all()->random()->id,
            'stock_action_type_id' => StockActionType::all()->random()->id,
            'quantity' => rand(1, 5000),
        ];
    }
}
