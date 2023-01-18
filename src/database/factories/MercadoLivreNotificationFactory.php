<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MercadoLivreNotification>
 */
class MercadoLivreNotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'men_id' => $this->faker->randomNumber(9),
            'men_resource' => $this->faker->word,
            'men_user_id' => $this->faker->randomNumber(9),
            'men_topic' => 'questions',
            'men_application_id' => $this->faker->randomNumber(6),
            'men_attempts' => $this->faker->randomNumber(1),
            'men_sent' => $this->faker->date,
            'men_received' => $this->faker->date,
            'men_send_message' => $this->faker->boolean,
        ];
    }
}
