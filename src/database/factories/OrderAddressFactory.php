<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderAddress>
 */
class OrderAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'ora_id' => $this->faker->randomNumber(9),
            'ord_id' => $this->faker->randomNumber(9),
            'ora_name' => $this->faker->firstName,
            'ora_surname' => $this->faker->lastName,
            'ora_phone' => $this->faker->phoneNumber,
            'ora_zipcode' => $this->faker->postcode,
            'ora_address' => $this->faker->address,
            'ora_number' => $this->faker->buildingNumber,
            'ora_district' => $this->faker->streetAddress,
            'ora_city' => $this->faker->city,
            'ora_complement' => '',
            'ora_type' => 'shipping',
            'ora_uf' => 'SP',
        ];
    }
}
