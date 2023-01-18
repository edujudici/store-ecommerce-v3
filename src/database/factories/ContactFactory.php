<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'con_id' => $this->faker->randomNumber(9),
            'con_name' => $this->faker->firstName,
            'con_email' => $this->faker->email,
            'con_subject' => $this->faker->sentence,
            'con_message' => $this->faker->sentence,
        ];
    }
}
