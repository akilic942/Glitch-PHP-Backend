<?php

namespace Database\Factories;

use App\Models\Competition;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompetitionInvite>
 */
class CompetitionInviteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        $faker = \Faker\Factory::create();

        $competition = Competition::factory()->create();

        $user = User::factory()->create();

        return [
            'competition_id' => $competition->id,
            'name' => $faker->name(),
            'token' => Str::random(20),
            'email' => $faker->unique()->safeEmail(),
        ];
    }
}
