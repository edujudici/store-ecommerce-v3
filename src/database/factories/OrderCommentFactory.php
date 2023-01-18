<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderComment>
 */
class OrderCommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'orc_id' => $this->faker->randomNumber(9),
            'ord_id' => $this->faker->randomNumber(9),
            'orc_name' => $this->faker->firstName,
            'orc_question' => $this->faker->sentence,
            'orc_answer' => $this->faker->sentence,
            'orc_answer_date' => $this->faker->date(),
            'orc_image' => 'http://via.placeholder.com/500x500',
        ];
    }
}
