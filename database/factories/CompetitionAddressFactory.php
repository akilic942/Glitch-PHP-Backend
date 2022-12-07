<?php

namespace Database\Factories;

use App\Models\Competition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompetitionAddress>
 */
class CompetitionAddressFactory extends Factory
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

        return [
            'venue_name' => $faker->name(),
            'competition_id' => $competition->id,
            'is_virtual_hybrid_remote' => rand(1,3),
        ];
    }
}
