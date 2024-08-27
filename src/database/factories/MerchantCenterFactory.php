<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MerchantCenter>
 */
class MerchantCenterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'mec_token_type' => 'Bearer',
            'mec_expires_in' => $this->faker->randomNumber(9),
            'mec_access_token' => $this->faker->word,
            'mec_refresh_token' => $this->faker->word,
            'mec_authorize_code' => $this->faker->word,
        ];
    }
}
