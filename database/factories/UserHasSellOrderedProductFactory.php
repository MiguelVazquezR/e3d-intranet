<?php

namespace Database\Factories;

use App\Models\SellOrderedProduct;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserHasSellOrderedProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'estimated_time' => rand(100,300),
            'time_paused' => rand(100,300),
            'indications' => $this->faker->sentence(),
            'sell_ordered_product_id' => SellOrderedProduct::all()->random()->id,
            'user_id' => User::all()->random()->id,
        ];
    }
}
