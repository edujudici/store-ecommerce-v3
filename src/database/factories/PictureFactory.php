<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Picture>
 */
class PictureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'pic_id' => $this->faker->randomNumber(9),
            'pro_sku' => $this->faker->randomNumber(9),
            'pic_id_secondary' => $this->faker->randomNumber(9),
            'pic_image' => 'https://via.placeholder.com/220x240',
            'pic_url' => $this->faker->url,
            'pic_secure_url' => 'https://via.placeholder.com/220x240',
            'pic_size' => '500x476',
            'pic_max_size' => '750x715',
            'pic_quality' => '',
        ];
    }
}
