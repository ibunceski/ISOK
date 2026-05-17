<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    protected $model = Message::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'report_id' => Report::factory(),
            'user_id' => null,
            'content' => $this->faker->sentence(),
            'sender_type' => $this->faker->randomElement(['user', 'admin']),
        ];
    }

    /**
     * State for user messages.
     */
    public function userMessage(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'sender_type' => 'user',
                'user_id' => null,
            ];
        });
    }

    /**
     * State for admin messages.
     */
    public function adminMessage(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'sender_type' => 'admin',
                'user_id' => User::factory(),
            ];
        });
    }
}

