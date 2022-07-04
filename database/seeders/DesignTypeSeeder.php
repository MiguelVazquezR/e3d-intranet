<?php

namespace Database\Seeders;

use App\Models\DesignType;
use Illuminate\Database\Seeder;

class DesignTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            'Render',
            'Planos',
            'Positivos',
            '2D',
            'Animación (video) 3D',
            'Recorte de vinil',
            'Corte en láser',
            'Escantillón',
            'Archivo para impresión en cama plana',
            'Archivo para grabado láser',
            'Proyecto especial',
        ];

        foreach($types as $type) {
            DesignType::create([
                'name' => $type
            ]);
        }
    }
}
