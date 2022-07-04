<?php

namespace Database\Seeders;

use App\Models\ProductStatus;
use Illuminate\Database\Seeder;

class ProductStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            'Obsoleto',
            'Activo',
            'Render (no se tiene en físico)'
        ];

        foreach($statuses as $status) {
            ProductStatus::create([
                'name' => $status
            ]);
        }
    }
}
