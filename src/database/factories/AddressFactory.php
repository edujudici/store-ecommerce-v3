<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'adr_id' => $this->faker->randomNumber(9),
            'user_id' => $this->faker->randomNumber(9),
            'adr_name' => $this->faker->firstName,
            'adr_surname' => $this->faker->lastName,
            'adr_phone' => $this->faker->phoneNumber,
            'adr_zipcode' => $this->faker->postcode,
            'adr_address' => $this->faker->address,
            'adr_number' => $this->faker->buildingNumber,
            'adr_district' => $this->faker->streetAddress,
            'adr_city' => $this->faker->city,
            'adr_complement' => '',
            'adr_type' => 'shipping',
            'adr_uf' => 'SP',
        ];
    }
}
