<?php

namespace Database\Factories;

use App\Models\Competition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompetitionTicketType>
 */
class CompetitionTicketTypeFactory extends Factory
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
            'name' => $faker->name(),
            'competition_id' => $competition->id
        ];
    }
}
