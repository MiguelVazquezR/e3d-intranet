<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencies = [
            '$MXN',
            '$USD',
        ];

        foreach($currencies as $currency) {
            Currency::create([
                'name' => $currency
            ]);
        }
    }
}
