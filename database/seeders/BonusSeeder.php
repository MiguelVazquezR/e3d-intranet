<?php

namespace Database\Seeders;

use App\Models\Bonus;
use Illuminate\Database\Seeder;

class BonusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bonuses = [
            [
                'full_time' => 175,
                'half_time' => 117,
                'name' => 'Asistencia',
            ],
            [
                'full_time' => 175,
                'half_time' => 117,
                'name' => 'Puntualidad',
            ],
            [
                'full_time' => 100,
                'half_time' => 117,
                'name' => 'Productividad',
            ],
            [
                'full_time' => 52.76,
                'half_time' => 52.76,
                'name' => 'Prima dominical',
            ],
            [
                'full_time' => 50,
                'half_time' => 50,
                'name' => 'Puntualidad jefe producción',
            ],
        ];

        foreach ( $bonuses as $bonus ) {
            Bonus::create($bonus);
        }
    }
}
