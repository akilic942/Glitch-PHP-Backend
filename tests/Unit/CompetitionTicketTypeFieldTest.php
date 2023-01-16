<?php

use App\Invirtu\InvirtuClient;
use App\Models\Competition;
use App\Models\Event;
use App\Models\CompetitionTicketType;
use App\Models\Recording;
use App\Models\CompetitionTicketTypeField;
use Tests\TestCase;

class CompetitionTicketTypeFieldTest extends TestCase {

    public function testCreation() {

        $faker = \Faker\Factory::create();

        $type = CompetitionTicketType::factory()->create();

        $data = array(
            'label' => $faker->name(),
            'name' => $faker->name(),
            'ticket_type_id' => $type->id,
            'field_type' => rand(1,4)
        );

        $field = new CompetitionTicketTypeField();

        $tmp_field = $data['label'];
        unset($data['label']);
        $this->assertFalse($field->validate($data));
        $data['label'] = $tmp_field;

        $tmp_field = $data['name'];
        unset($data['name']);
        $this->assertFalse($field->validate($data));
        $data['name'] = $tmp_field;


        $tmp_field = $data['ticket_type_id'];
        unset($data['ticket_type_id']);
        $this->assertFalse($field->validate($data));
        $data['ticket_type_id'] = $tmp_field;

        $tmp_field = $data['field_type'];
        unset($data['field_type']);
        $this->assertFalse($field->validate($data));
        $data['field_type'] = $tmp_field;


        $this->assertTrue($field->validate($data));

        $field = CompetitionTicketTypeField::create($data);

        $this->assertNotNull($field->id);

        //Reload with data from the observer
        $field->refresh();

    }

}