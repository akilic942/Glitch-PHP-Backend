<?php

namespace Database\Factories;

use App\Models\CompetitionTicketType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompetitionTicketTypeSection>
 */
class CompetitionTicketTypeSectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        $faker = \Faker\Factory::create();

        $type = CompetitionTicketType::factory()->create();

        return [
            'title' => $faker->name(),
            'ticket_type_id' => $type->id,
        ];
    }
}
