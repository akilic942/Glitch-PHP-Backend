<?php

namespace Database\Factories;

use App\Models\MessageThread;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $faker = \Faker\Factory::create();

        $user = User::factory()->create();

        $thread = MessageThread::factory()->create();

        $message = $faker->paragraphs(8, true);

        return [
            'user_id' => $user->id,
            'thread_id' => $thread->id,
            'message' => $message
        ];
    }
}
