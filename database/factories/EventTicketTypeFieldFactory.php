<?php

namespace Database\Factories;

use App\Models\EventTicketType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventTicketTypeField>
 */
class EventTicketTypeFieldFactory extends Factory
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
            'label' => $faker->name(),
            'name' => $faker->name(),
            'ticket_type_id' => $type->id,
            'field_type' => rand(1,4)
        ];
    }
}
