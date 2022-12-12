<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TeamInvite>
 */
class TeamInviteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        $team = Team::factory()->create();

        $user = User::factory()->create();

        $faker = \Faker\Factory::create();

        return [
            'team_id' => $team->id,
            'name' => $faker->name(),
            'token' => Str::random(20),
            'email' => $faker->unique()->safeEmail(),
        ];
    }
}
