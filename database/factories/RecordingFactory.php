<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Recording>
 */
class RecordingFactory extends Factory
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
            'title' => $faker->name(),
            'description' => $faker->text(),
            'event_id' => $event->id,
        ];
    }
}
