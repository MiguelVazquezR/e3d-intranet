<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SellOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $customer = Customer::all()->random();
        do {

            $contact = $customer->contacts 
            ? $customer->contacts->random()
            : null;
        }
        while(!$contact);

        return [
            'shipping_company' => $this->faker->word(),
            'freight_cost' => rand(200,800) . $this->faker->randomElement(['$MXN','$USD']),
            'status' => 'En proceso',
            'priority' => $this->faker->word(),
            'contact_id' => $contact->id,
            'order_via' => $this->faker->word(),
            'invoice' => $this->faker->word(),
            'tracking_guide' => $this->faker->word(),
            'notes' => $this->faker->sentence(),
            'user_id' => User::all()->random()->id,
            'customer_id' => $customer->id,
        ];
    }
}
