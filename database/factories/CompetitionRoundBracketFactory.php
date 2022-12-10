<?php

namespace Database\Factories;

use App\Models\Competition;
use App\Models\CompetitionRound;
use App\Models\CompetitionRoundBracket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompetitionRoundBracket>
 */
class CompetitionRoundBracketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        $competition = Competition::factory()->create();

        $round = CompetitionRound::factory()->create(['competition_id' => $competition->id]);

        return [
            'competition_id' => $competition->id,
            'round' => $round->round,
            'bracket' => rand(1, 100)
        ];

    }
}
