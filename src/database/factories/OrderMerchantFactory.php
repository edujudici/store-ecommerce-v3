<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderMerchant>
 */
class OrderMerchantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'orm_id' => $this->faker->randomNumber(9),
            'ord_id' => $this->faker->randomNumber(9),
            'orm_notification_id' => $this->faker->randomNumber(9),
            'orm_notification_topic' => 'merchant_order',
            'orm_order_status' => 'paid',
            'orm_paid_amount' => $this->faker->randomFloat(2, 1, 100),
        ];
    }
}
