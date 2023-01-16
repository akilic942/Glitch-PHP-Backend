<?php

namespace Database\Factories;

use App\Models\EventTicketType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventTicketTypeSection>
 */
class EventTicketTypeSectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $faker = \Faker\Factory::create();

        $type = EventTicketType::factory()->create();

        return [
            'title' => $faker->name(),
            'ticket_type_id' => $type->id,
        ];
    }
}
