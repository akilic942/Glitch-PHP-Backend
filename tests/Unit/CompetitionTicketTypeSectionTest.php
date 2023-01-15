<?php

use App\Invirtu\InvirtuClient;
use App\Models\Competition;
use App\Models\Event;
use App\Models\CompetitionTicketType;
use App\Models\Recording;
use App\Models\CompetitionTicketTypeSection;
use Tests\TestCase;

class CompetitionTicketTypeSectionTest extends TestCase {

    public function testCreation() {

        $faker = \Faker\Factory::create();

        $type = CompetitionTicketType::factory()->create();

        $data = array(
            'title' => $faker->name(),
            'ticket_type_id' => $type->id
            ,
        );

        $section = new CompetitionTicketTypeSection();

        $tmp_field = $data['title'];
        unset($data['title']);
        $this->assertFalse($section->validate($data));
        $data['title'] = $tmp_field;


        $tmp_field = $data['ticket_type_id'];
        unset($data['ticket_type_id']);
        $this->assertFalse($section->validate($data));
        $data['ticket_type_id'] = $tmp_field;


        $this->assertTrue($section->validate($data));

        $section = CompetitionTicketTypeSection::create($data);

        $this->assertNotNull($section->id);

        //Reload with data from the observer
        $section->refresh();

    }

}