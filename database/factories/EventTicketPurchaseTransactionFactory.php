<?php

namespace Database\Factories;

use App\Models\EventTicketPurchase;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EventTicketPurchaseTransaction>
 */
class EventTicketPurchaseTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $purchase = EventTicketPurchase::factory()->create();

        return [
            'purchase_id' => $purchase->id,
            'payment_processor' => rand(0,6),
            'payment_or_refund' => rand(1,2),
        ];
    }
}
