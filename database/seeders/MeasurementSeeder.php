<?php

namespace Database\Seeders;

use App\Models\MeasurementUnit;
use Illuminate\Database\Seeder;

class MeasurementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $units = [
            'Piezas',
            'Litros',
            'Kilogramos',
            'Milimetros',
            'Centimetros',
            'Metros',
            'Juegos',
        ];

        foreach ( $units as $unit ) {
            MeasurementUnit::create([
                'name' => $unit
            ]);
        }
    }
}
