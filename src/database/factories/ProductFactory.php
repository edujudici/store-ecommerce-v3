<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'pro_id' => $this->faker->randomNumber(9),
            'cat_id' => $this->faker->randomNumber(9),
            'pro_price' => $this->faker->randomFloat(2, 1, 100),
            'pro_oldprice' => $this->faker->randomFloat(2, 1, 100),
            'pro_title' => $this->faker->sentence,
            'pro_description' => $this->faker->sentence,
            'pro_description_long' => $this->faker->sentence,
            // 'pro_image' => $this->faker->url,
            'pro_sku' => $this->faker->randomNumber(9),
            'pro_seller_id' => $this->faker->randomNumber(9),
            'pro_category_id' => $this->faker->randomNumber(5),
            'pro_condition' => 'new',
            'pro_permalink' => $this->faker->url,
            'pro_thumbnail' => $this->faker->url,
            'pro_secure_thumbnail' => 'http://via.placeholder.com/220x240',
            'pro_accepts_merc_pago' => '1',
            'pro_load_date' => $this->faker->date(),
            'pro_sold_quantity' => $this->faker->randomNumber(9),
            'pro_external' => true,
            'pro_inventory' => $this->faker->randomNumber(9),
        ];
    }
}
