<?php

namespace Database\Seeders;

use App\Models\ProductFamily;
use Illuminate\Database\Seeder;

class ProductFamilySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product_families = [
            'Emblemas',
            'Porta placas ABS',
            'Porta placas metálicos',
            'Llaveros',
            'Porta documentos',
            'Termos',
            'Placas de estireno',
            'Parasoles',
            'Tapetes',
            'Medallones',
        ];

        foreach ( $product_families as $product_family ) {
            ProductFamily::create([
                'name' => $product_family
            ]);
        }
    }
}
