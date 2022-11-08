<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Follow>
 */
class FollowFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user1 = User::factory()->create();

        $user2 = User::factory()->create();

        return [
            'following_id' => $user1->id,
            'follower_id' => $user2->id
        ];
    }
}
