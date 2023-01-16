<?php

use App\Invirtu\InvirtuClient;
use App\Models\CompetitionTicketPurchase;
use App\Models\CompetitionTicketType;
use App\Models\Event;
use App\Models\Recording;
use Tests\TestCase;

class CompetitionTicketPurchaseTest extends TestCase {

    public function testCreation() {

        $faker = \Faker\Factory::create();

        $type = CompetitionTicketType::factory()->create();

        $data = array(
            'ticket_type_id' => $type->id,
            'quantity' => rand(1, 100),
        );

        $purchase = new CompetitionTicketPurchase();

        $tmp_field = $data['ticket_type_id'];
        unset($data['ticket_type_id']);
        $this->assertFalse($purchase->validate($data));
        $data['ticket_type_id'] = $tmp_field;

        $tmp_field = $data['quantity'];
        unset($data['quantity']);
        $this->assertFalse($purchase->validate($data));
        $data['quantity'] = $tmp_field;


        $this->assertTrue($purchase->validate($data));

        $purchase = new CompetitionTicketPurchase();
        $purchase->forceFill($data);
        $purchase->save();

        $this->assertNotNull($purchase->id);

        //Reload with data from the observer
        $purchase->refresh();

    }

}