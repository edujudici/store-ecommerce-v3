<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Banner>
 */
class BannerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'ban_id' => $this->faker->randomNumber(9),
            'ban_title' => $this->faker->word,
            'ban_description' => $this->faker->sentence,
            'ban_image' => 'http://via.placeholder.com/635x380',
            'ban_url' => $this->faker->url,
        ];
    }
}
