<?php

namespace Database\Seeders;

use App\Models\ProductMaterial;
use Illuminate\Database\Seeder;

class ProductMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product_materials = [
            'Metal',
            'Metal colores',
            'Metal cromado',
            'Cuero con emblema',
            'Cuero normal',
            'Cuero hotstamping',
            'Cuero extrafino',
            'Cromo',
            'PU',
            'Aluminio mate',
            'Aluminio pulido',
            'Aluminio brush (cepillado)',
            '3D Solid chrome mate',
            '3D Solid chrome pulido',
            '3D Solid chrome cepillado',
            'Encapsulado base vinil',
            'Encapsulado base chrome mylar pulido',
            'Encapsulado base chrome mylar mate',
            'Encapsulado base chrome mylar brush (cepillado)',
            'Encapsulado base aluminio',
            'Flex chrome',
            'Micrometal',
        ];

        foreach ($product_materials as $product_material) {
            ProductMaterial::create([
                'name' => $product_material
            ]);
        }
    }
}
