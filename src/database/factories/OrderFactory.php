<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'ord_id' => $this->faker->randomNumber(9),
            'user_id' => $this->faker->randomNumber(9),
            'ord_protocol' => 'Vxx-' . randomText(8),
            'ord_preference_id' => $this->faker->randomNumber(9),
            'ord_preference_init_point' => $this->faker->url,
            'ord_external_reference' => randomText(8),
            'ord_subtotal' => $this->faker->randomFloat(2, 1, 100),
            'ord_freight_code' => -1,
            'ord_freight_service' => 'Retirar no local',
            'ord_freight_time' => $this->faker->randomNumber(2),
            'ord_freight_price' => null,
            'ord_total' => $this->faker->randomFloat(2, 1, 100),
            'ord_delivery_date' => null,
            'ord_voucher_code' => $this->faker->sentence,
            'ord_voucher_value' => $this->faker->randomFloat(2, 1, 100),
            'ord_promised_date' => $this->faker->date(),
            'ord_promised_date_recalculated' => $this->faker->date(),
        ];
    }
}
