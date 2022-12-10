<?php

namespace Database\Factories;

use App\Models\Competition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompetitionRound>
 */
class CompetitionRoundFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        $competition = Competition::factory()->create();

        return [
            'competition_id' => $competition->id,
            'round' => rand(1,10000)
        ];
    }
}
