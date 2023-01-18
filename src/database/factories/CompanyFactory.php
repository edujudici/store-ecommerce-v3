<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'com_id' => $this->faker->randomNumber(9),
            'com_title' => $this->faker->word,
            'com_description' => $this->faker->paragraph,
            'com_image' => 'http://via.placeholder.com/380x50',
            'com_address' => $this->faker->address,
            'com_phone' => $this->faker->phoneNumber,
            'com_work_hours' => 'aberto das 8h até as 18h',
            'com_mail' => $this->faker->email,
            'com_iframe' => $this->faker->url,
        ];
    }
}
