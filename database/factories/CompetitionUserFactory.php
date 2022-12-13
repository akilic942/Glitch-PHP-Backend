<?php

namespace Database\Factories;

use App\Models\Competition;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompetitionUser>
 */
class CompetitionUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {


        $competition = Competition::factory()->create();

        $user = User::factory()->create();

        return [
            'competition_id' => $competition->id,
            'user_id' => $user->id
        ];
    }
}
