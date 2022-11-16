<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventInvite>
 */
class EventInviteFactory extends Factory
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
            'email' => $faker->unique()->safeEmail(),
            'name' => $faker->name(),
            'token' => Str::random(20),
            'event_id' => $event->id
        ];
    }
}
