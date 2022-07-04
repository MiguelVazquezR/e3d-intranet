<?php

namespace Database\Factories;

use App\Models\DesignOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class DesignResultFactory extends Factory
{

    public function definition()
    {
        return [
            'design_order_id' => DesignOrder::all()->random()->id,
            'image' => 'public/design-results/' . $this->faker->image('public/storage/design-results', 640, 480, null, false),
            'notes' => $this->faker->text(70),
        ];
    }
}
