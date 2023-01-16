<?php

use App\Invirtu\InvirtuClient;
use App\Models\Competition;
use App\Models\Event;
use App\Models\EventTicketType;
use App\Models\Recording;
use App\Models\EventTicketTypeSection;
use Tests\TestCase;

class EventTicketTypeSectionTest extends TestCase {

    public function testCreation() {

        $faker = \Faker\Factory::create();

        $type = EventTicketType::factory()->create();

        $data = array(
            'title' => $faker->name(),
            'ticket_type_id' => $type->id,
        );

        $section = new EventTicketTypeSection();

        $tmp_field = $data['title'];
        unset($data['title']);
        $this->assertFalse($section->validate($data));
        $data['title'] = $tmp_field;


        $tmp_field = $data['ticket_type_id'];
        unset($data['ticket_type_id']);
        $this->assertFalse($section->validate($data));
        $data['ticket_type_id'] = $tmp_field;


        $this->assertTrue($section->validate($data));

        $section = EventTicketTypeSection::create($data);

        $this->assertNotNull($section->id);

        //Reload with data from the observer
        $section->refresh();

    }

}