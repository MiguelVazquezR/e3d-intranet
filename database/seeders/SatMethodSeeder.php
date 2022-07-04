<?php

namespace Database\Seeders;

use App\Models\SatMethod;
use Illuminate\Database\Seeder;

class SatMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $methods = [
            'PUE' => 'Pago en una sola exhibición',
            'PPD' => 'Pago en parcialidades o diferido',
        ];

        foreach ($methods as $key => $method) {
            SatMethod::create([
                'key' => $key,
                'description' => $method
            ]);
        }
    }
}
