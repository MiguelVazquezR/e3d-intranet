<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class BankDataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'beneficiary_name' => 
            $this->faker->firstName($this->faker->randomElement(['male','female'])) . ' ' . 
            $this->faker->lastName(),
            'account' => '1234567891113150',
            'CLABE' => '234567891111315170',
            'bank' => $this->faker->randomElement(['BBVA', 'Santander', 'Banorte', 'Ban Bajío']),
            'card_type' => $this->faker->randomElement(['Débito', 'Crédito']),
            'supplier_id' => Supplier::all()->random()->id,
        ];
    }
}
