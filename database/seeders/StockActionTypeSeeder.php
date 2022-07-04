<?php

namespace Database\Seeders;

use App\Models\StockActionType;
use Illuminate\Database\Seeder;

class StockActionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $action_types = [
            'Devolución de mercancía' => 1,
            'Devolución de muestras' => 1,
            'Fabricación de stock' => 1,
            'Ajuste de Inventario' => 1,
            'Venta' => 0,
            'Reposición a cliente' => 0,
            'Para muestra' => 0,
            'Ajuste de inventario' => 0,
        ];

        foreach($action_types as $name => $type) {
            StockActionType::create([
                'name' => $name,
                'movement' => $type,
            ]);
        }
    }
}
