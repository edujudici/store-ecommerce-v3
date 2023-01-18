<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderPayment>
 */
class OrderPaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'orp_id' => $this->faker->randomNumber(9),
            'ord_id' => $this->faker->randomNumber(9),
            'orp_payment_id' => $this->faker->randomNumber(9),
            'orp_order_id' => $this->faker->randomNumber(9),
            'orp_payer_id' => $this->faker->randomNumber(9),
            'orp_payer_email' => $this->faker->email,
            'orp_payer_first_name' => $this->faker->firstName,
            'orp_payer_last_name' => $this->faker->lastName,
            'orp_payer_phone' => $this->faker->phoneNumber,
            'orp_payment_method_id' => 'master',
            'orp_payment_type_id' => 'credit_card',
            'orp_status' => 'approved',
            'orp_status_detail' => 'accredited',
            'orp_transaction_amount' => $this->faker->randomFloat(2, 1, 100),
            'orp_received_amount' => $this->faker->randomFloat(2, 1, 100),
            'orp_resource_url' => $this->faker->url,
            'orp_total_paid_amount' => $this->faker->randomFloat(2, 1, 100),
            'orp_shipping_amount' => $this->faker->randomFloat(2, 1, 100),
            'orp_date_approved' => $this->faker->dateTime(),
            'orp_date_created' => $this->faker->dateTime(),
            'orp_date_of_expiration' => $this->faker->dateTime(),
            'orp_live_mode' => $this->faker->boolean,
        ];
    }
}
