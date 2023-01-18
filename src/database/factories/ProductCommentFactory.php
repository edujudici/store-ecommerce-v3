<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductComment>
 */
class ProductCommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'prc_id' => $this->faker->randomNumber(9),
            'pro_sku' => $this->faker->randomNumber(9),
            'prc_name' => $this->faker->firstName,
            'prc_question' => $this->faker->sentence,
            'prc_answer' => $this->faker->sentence,
            'prc_answer_date' => $this->faker->date(),
        ];
    }
}
