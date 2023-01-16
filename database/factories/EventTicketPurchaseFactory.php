<?php

namespace Database\Factories;

use App\Models\EventTicketType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventTicketPurchase>
 */
class EventTicketPurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $type = EventTicketType::factory()->create();

        return [
            'ticket_type_id' => $type->id,
            'quantity' => rand(1, 100),
        ];
    }
}
