<?php
namespace Tests\Facades;

use App\Facades\CompetitionInvitesFacade;
use App\Facades\CompetitionTicketFacade;
use App\Facades\PermissionsFacade;
use App\Models\Competition;
use App\Models\CompetitionInvite;
use App\Models\CompetitionTicketPurchase;
use App\Models\CompetitionTicketType;
use App\Models\CompetitionTicketTypeField;
use App\Models\User;
use Tests\TestCase;

class CompetitionTicketFacadeTest extends TestCase {


    public function testValidateFields() {

        $type1 = CompetitionTicketType::factory()->create();

        $type2 = CompetitionTicketType::factory()->create();

        $type1_field1_required = CompetitionTicketTypeField::factory()->create(['ticket_type_id' => $type1, 'is_required' => 1]);

        $type1_field2_required = CompetitionTicketTypeField::factory()->create(['ticket_type_id' => $type1, 'is_required' => 1]);

        $type1_field3_not_required = CompetitionTicketTypeField::factory()->create(['ticket_type_id' => $type1]);

        $type2_field1_not_required = CompetitionTicketTypeField::factory()->create(['ticket_type_id' => $type2]);

        $type2_field2_not_required = CompetitionTicketTypeField::factory()->create(['ticket_type_id' => $type2]);

        $result = CompetitionTicketFacade::validateFields($type1, []);

        $this->assertFalse($result['status']);

        $this->assertCount(2, $result['errors']);

        $data = [];
        $data[$type1_field1_required->name] = 'sfsdfsdsds';
        $data[$type1_field2_required->name] = 'klcmvsdd';

        $result = CompetitionTicketFacade::validateFields($type1, $data);

        $this->assertTrue($result['status']);

        $result = CompetitionTicketFacade::validateFields($type2, []);

        $this->assertTrue($result['status']);
    }

    public function testIsUnderMaxAvailable() {

        $type_full = CompetitionTicketType::factory()->create(['max_available' => rand(5,10)]);

        $type_not_full = CompetitionTicketType::factory()->create(['max_available' => rand(100,200)]);

        $type_no_cap = CompetitionTicketType::factory()->create();

        CompetitionTicketPurchase::factory(rand(20,30))->create(['ticket_type_id' => $type_full->id, 'fully_paid' => 1]);

        CompetitionTicketPurchase::factory(rand(20,30))->create(['ticket_type_id' => $type_not_full->id, 'fully_paid' => 1]);

        CompetitionTicketPurchase::factory(rand(20,30))->create(['ticket_type_id' => $type_no_cap->id, 'fully_paid' => 1]);

        $result = CompetitionTicketFacade::isUnderMaxAvailable($type_full, rand(1,10));

        $this->assertFalse($result);

        $result = CompetitionTicketFacade::isUnderMaxAvailable($type_not_full, rand(1,10));

        $this->assertTrue($result);

        $result = CompetitionTicketFacade::isUnderMaxAvailable($type_not_full, rand(400,500));

        $this->assertFalse($result);

        $result = CompetitionTicketFacade::isUnderMaxAvailable($type_no_cap, rand(400,500));

        $this->assertTrue($result);
    }

    public function testiIsUnderMaxPurchasable() {

        $type_with_max = CompetitionTicketType::factory()->create(['max_purchasable' => rand(4,6)]);

        $type_with_nomax = CompetitionTicketType::factory()->create();

        $result = CompetitionTicketFacade::isUnderMaxPurchasable($type_with_max, rand(1,3));

        $this->assertTrue($result);

        $result = CompetitionTicketFacade::isUnderMaxPurchasable($type_with_max, rand(8,10));

        $this->assertFalse($result);

        $result = CompetitionTicketFacade::isUnderMaxPurchasable($type_with_nomax , rand(200,300));

        $this->assertTrue($result);

    }

    public function testIsOverMinPurchasable() {

        $type_with_min = CompetitionTicketType::factory()->create(['min_purchasable' => rand(4,6)]);

        $type_with_nomin = CompetitionTicketType::factory()->create();

        $result = CompetitionTicketFacade::isOverMinPurchasable($type_with_min , rand(1,3));

        $this->assertFalse($result);

        $result = CompetitionTicketFacade::isOverMinPurchasable($type_with_min, rand(8,10));

        $this->assertTrue($result);

        $result = CompetitionTicketFacade::isOverMinPurchasable($type_with_nomin , rand(200,300));

        $this->assertTrue($result);

    }

    public function testSaveInput() {

        $type = CompetitionTicketType::factory()->create();

        $field1 = CompetitionTicketTypeField::factory()->create(['ticket_type_id' => $type, 'is_required' => 1]);

        $field2 = CompetitionTicketTypeField::factory()->create(['ticket_type_id' => $type, 'is_required' => 1]);

        $field3 = CompetitionTicketTypeField::factory()->create(['ticket_type_id' => $type]);

        $faker = \Faker\Factory::create();

        $input = [];
        $input[$field1->name] = $faker->name();
        $input[$field2->name] = $faker->name();
        $input[$field3->name] = $faker->name();
        $input[$faker->name()] = $faker->name();
        $input[$faker->name()] = $faker->name();
        $input[$faker->name()] = $faker->name();

        $purchase = CompetitionTicketPurchase::factory()->create(['ticket_type_id' => $type->id]);


        $result = CompetitionTicketFacade::saveInput($type, $purchase, $input);

        $fields = $result->fields;

        $this->assertCount(3, $fields);

        $this->assertEquals($input[$field1->name], $fields[$field1->name]);
        $this->assertEquals($input[$field2->name], $fields[$field2->name]);
        $this->assertEquals($input[$field3->name], $fields[$field3->name]);

    }
}