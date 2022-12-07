<?php

namespace Database\Factories;

use App\Models\Competition;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompetitionTeam>
 */
class CompetitionTeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        $competition = Competition::factory()->create();

        $team = Team::factory()->create();

        return [
            'competition_id' => $competition->id,
            'team_id' => $team->id
        ];
    }
}
