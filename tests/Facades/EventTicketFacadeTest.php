<?php
namespace Tests\Facades;

use App\Facades\CompetitionInvitesFacade;
use App\Facades\EventTicketFacade;
use App\Facades\PermissionsFacade;
use App\Models\Competition;
use App\Models\CompetitionInvite;
use App\Models\EventTicketPurchase;
use App\Models\EventTicketType;
use App\Models\EventTicketTypeField;
use App\Models\User;
use Tests\TestCase;

class EventTicketFacadeTest extends TestCase {


    public function testValidateFields() {

        $type1 = EventTicketType::factory()->create();

        $type2 = EventTicketType::factory()->create();

        $type1_field1_required = EventTicketTypeField::factory()->create(['ticket_type_id' => $type1, 'is_required' => 1]);

        $type1_field2_required = EventTicketTypeField::factory()->create(['ticket_type_id' => $type1, 'is_required' => 1]);

        $type1_field3_not_required = EventTicketTypeField::factory()->create(['ticket_type_id' => $type1]);

        $type2_field1_not_required = EventTicketTypeField::factory()->create(['ticket_type_id' => $type2]);

        $type2_field2_not_required = EventTicketTypeField::factory()->create(['ticket_type_id' => $type2]);

        $result = EventTicketFacade::validateFields($type1, []);

        $this->assertFalse($result['status']);

        $this->assertCount(2, $result['errors']);

        $data = [];
        $data[$type1_field1_required->name] = 'sfsdfsdsds';
        $data[$type1_field2_required->name] = 'klcmvsdd';

        $result = EventTicketFacade::validateFields($type1, $data);

        $this->assertTrue($result['status']);

        $result = EventTicketFacade::validateFields($type2, []);

        $this->assertTrue($result['status']);
    }

    public function testIsUnderMaxAvailable() {

        $type_full = EventTicketType::factory()->create(['max_available' => rand(5,10)]);

        $type_not_full = EventTicketType::factory()->create(['max_available' => rand(100,200)]);

        $type_no_cap = EventTicketType::factory()->create();

        EventTicketPurchase::factory(rand(20,30))->create(['ticket_type_id' => $type_full->id, 'fully_paid' => 1]);

        EventTicketPurchase::factory(rand(20,30))->create(['ticket_type_id' => $type_not_full->id, 'fully_paid' => 1]);

        EventTicketPurchase::factory(rand(20,30))->create(['ticket_type_id' => $type_no_cap->id, 'fully_paid' => 1]);

        $result = EventTicketFacade::isUnderMaxAvailable($type_full, rand(1,10));

        $this->assertFalse($result);

        $result = EventTicketFacade::isUnderMaxAvailable($type_not_full, rand(1,10));

        $this->assertTrue($result);

        $result = EventTicketFacade::isUnderMaxAvailable($type_not_full, rand(400,500));

        $this->assertFalse($result);

        $result = EventTicketFacade::isUnderMaxAvailable($type_no_cap, rand(400,500));

        $this->assertTrue($result);
    }

    public function testiIsUnderMaxPurchasable() {

        $type_with_max = EventTicketType::factory()->create(['max_purchasable' => rand(4,6)]);

        $type_with_nomax = EventTicketType::factory()->create();

        $result = EventTicketFacade::isUnderMaxPurchasable($type_with_max, rand(1,3));

        $this->assertTrue($result);

        $result = EventTicketFacade::isUnderMaxPurchasable($type_with_max, rand(8,10));

        $this->assertFalse($result);

        $result = EventTicketFacade::isUnderMaxPurchasable($type_with_nomax , rand(200,300));

        $this->assertTrue($result);

    }

    public function testIsOverMinPurchasable() {

        $type_with_min = EventTicketType::factory()->create(['min_purchasable' => rand(4,6)]);

        $type_with_nomin = EventTicketType::factory()->create();

        $result = EventTicketFacade::isOverMinPurchasable($type_with_min , rand(1,3));

        $this->assertFalse($result);

        $result = EventTicketFacade::isOverMinPurchasable($type_with_min, rand(8,10));

        $this->assertTrue($result);

        $result = EventTicketFacade::isOverMinPurchasable($type_with_nomin , rand(200,300));

        $this->assertTrue($result);

    }

    public function testSaveInput() {

        $type = EventTicketType::factory()->create();

        $field1 = EventTicketTypeField::factory()->create(['ticket_type_id' => $type, 'is_required' => 1]);

        $field2 = EventTicketTypeField::factory()->create(['ticket_type_id' => $type, 'is_required' => 1]);

        $field3 = EventTicketTypeField::factory()->create(['ticket_type_id' => $type]);

        $faker = \Faker\Factory::create();

        $input = [];
        $input[$field1->name] = $faker->name();
        $input[$field2->name] = $faker->name();
        $input[$field3->name] = $faker->name();
        $input[$faker->name()] = $faker->name();
        $input[$faker->name()] = $faker->name();
        $input[$faker->name()] = $faker->name();

        $purchase = EventTicketPurchase::factory()->create(['ticket_type_id' => $type->id]);


        $result = EventTicketFacade::saveInput($type, $purchase, $input);

        $fields = $result->fields;

        $this->assertCount(3, $fields);

        $this->assertEquals($input[$field1->name], $fields[$field1->name]);
        $this->assertEquals($input[$field2->name], $fields[$field2->name]);
        $this->assertEquals($input[$field3->name], $fields[$field3->name]);

    }
}