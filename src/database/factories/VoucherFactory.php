<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Voucher>
 */
class VoucherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'vou_id' => $this->faker->randomNumber(9),
            'vou_id_base' => $this->faker->randomNumber(9),
            'user_uuid' => $this->faker->uuid,
            'vou_code' => $this->faker->sentence,
            'vou_value' => $this->faker->randomFloat(2, 1, 100),
            'vou_expiration_date' => $this->faker->date(),
            'vou_applied_date' => $this->faker->date(),
            'vou_description' => $this->faker->sentence(),
            'vou_status' => 'active',
        ];
    }
}
