<?php

namespace Database\Factories;

use App\Models\MeasurementUnit;
use App\Models\ProductFamily;
use App\Models\ProductMaterial;
use App\Models\ProductStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'image' => 'public/products/' . $this->faker->image('public/storage/products', 640, 480, null, false),
            'product_status_id' => ProductStatus::all()->random()->id,
            'min_stock' => rand(100, 6000),
            'product_family_id' => ProductFamily::all()->random()->id,
            'product_material_id' => ProductMaterial::all()->random()->id,
            'measurement_unit_id' => MeasurementUnit::all()->random()->id,
        ];
    }
}
