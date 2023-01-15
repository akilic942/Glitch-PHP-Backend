<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventTicketType>
 */
class EventTicketTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        $faker = \Faker\Factory::create();

        $event = Event::factory()->create();

        return [
            'name' => $faker->name(),
            'event_id' => $event->id
        ];
    }
}
