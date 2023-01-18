<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MercadoLivreComment>
 */
class MercadoLivreCommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'mec_id' => $this->faker->randomNumber(9),
            'mec_date_created' => $this->faker->dateTime(),
            'mec_item_id' => $this->faker->randomNumber(9),
            'mec_seller_id' => $this->faker->randomNumber(9),
            'mec_status' => 'UNANSWERED',
            'mec_text' => $this->faker->word,
            'mec_id_secondary' => $this->faker->randomNumber(9),
            'mec_deleted_from_listing' => 1,
            'mec_hold' => 0,
            'mec_answer_local' => $this->faker->boolean,
            'mec_answer_date' => $this->faker->dateTime(),
            'mec_answer_status' => 'ACTIVE',
            'mec_answer_text' => $this->faker->word,
            'mec_from_id' => $this->faker->randomNumber(9),
        ];
    }
}
