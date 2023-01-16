<?php

namespace Tests\Routes;

use App\Facades\CompetitionInvitesFacade;
use App\Facades\RolesFacade;
use App\Models\Competition;
use App\Models\CompetitionTicketType;
use App\Models\CompetitionTicketTypeField;
use App\Models\CompetitionTicketTypeSection;
use App\Models\User;
use Database\Factories\CompetitionInviteFactory;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CompetitionTicketTypeFieldControllerTest extends TestCase
{


    public function testList(){

        $competition = Competition::factory()->create();

        $type = CompetitionTicketType::factory()->create(['competition_id' => $competition->id]);

        $fields = CompetitionTicketTypeField::factory(30)->create(['ticket_type_id' => $type->id]);

        $user = User::factory()->create();

        $url = $this->_getApiRoute() . 'competitions/' . $competition->id . '/tickettypes/' . $type->id . '/fields';

        RolesFacade::competitionMakeAdmin($competition, $user);

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->get($url);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $typesData = $json['data'];

        $this->assertCount(25, $typesData);

    }

    public function testCreation(){

        $competition = Competition::factory()->create();

        $type = CompetitionTicketType::factory()->create(['competition_id' => $competition->id]);

        $user = User::factory()->create();

        RolesFacade::competitionMakeAdmin($competition, $user);

        $url = $this->_getApiRoute() . 'competitions/' . $competition->id . '/tickettypes/' . $type->id . '/fields';

        /*$response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->post($url);

        $this->assertEquals(422, $response->status());*/

        $faker = \Faker\Factory::create();

        $data = [
            'label' => $faker->name(),
            'name' => $faker->name(),
            'field_type' => rand(1,4)
        ];
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken($user),
        ])->post($url, $data);

        $this->assertEquals(201, $response->status());

        $json = $response->json();

        $result = $json['data'];

        $this->assertEquals($result['ticket_type_id'], $type->id);
        $this->assertEquals($result['name'], $data['name']);
        $this->assertEquals($result['label'], $data['label']);
        $this->assertEquals($result['field_type'], $data['field_type']);
        $this->assertNotNull($result['id']);
        //$this->assertEquals($competition['user']['id'], $user->id);

    }

    public function testView() {

        $competition = Competition::factory()->create();

        $type = CompetitionTicketType::factory()->create(['competition_id' => $competition->id]);

        $field = CompetitionTicketTypeField::factory()->create(['ticket_type_id' => $type->id]);

        $url = $this->_getApiRoute() .  'competitions/' . $competition->id . '/tickettypes/' . $type->id . '/fields/' . $field->id;

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken(),
        ])->get($url);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $data = $json['data'];

        $this->assertEquals($field->id, $data['id']);
        $this->assertEquals($type->id, $data['ticket_type_id']);

    }

    public function testUpdate() {

        $user = User::factory()->create();

        $competition = Competition::factory()->create();

        RolesFacade::competitionMakeAdmin($competition, $user);

        $type = CompetitionTicketType::factory()->create(['competition_id' => $competition->id]);

        $field = CompetitionTicketTypeField::factory()->create(['ticket_type_id' => $type->id]);

        $url = $this->_getApiRoute() .  'competitions/' . $competition->id . '/tickettypes/' . $type->id . '/fields/' . $field->id;

        $faker = \Faker\Factory::create();

        $section = CompetitionTicketTypeSection::factory()->create(['ticket_type_id' => $type->id]);

        $data = [
            'section_id' => $section->id,
            'field_type' => rand(1,4),
            
            'label' => $faker->name(),
            'name' => $faker->name(),
            'field_order' => rand(1,100),

            'is_required' => rand(0,1),
            'is_disabled' => rand(0,1),
             
        ];

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->put($url, $data);
        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $jsonData = $json['data'];

        $this->assertEquals($field->id, $jsonData['id']);
        $this->assertEquals($jsonData['section_id'], $data['section_id']);
        $this->assertEquals($jsonData['field_type'], $data['field_type']);
        $this->assertEquals($jsonData['label'], $data['label']);
        $this->assertEquals($jsonData['name'], $data['name']);
        $this->assertEquals($jsonData['field_order'], $data['field_order']);
        $this->assertEquals($jsonData['is_required'], $data['is_required']);
        $this->assertEquals($jsonData['is_disabled'], $data['is_disabled']);

    }

    public function testDelete() {

        $user = User::factory()->create();

        $competition = Competition::factory()->create();

        RolesFacade::competitionMakeAdmin($competition, $user);

        $type = CompetitionTicketType::factory()->create(['competition_id' => $competition->id]);

        $field = CompetitionTicketTypeField::factory()->create(['ticket_type_id' => $type->id]);

        $url = $this->_getApiRoute() .  'competitions/' . $competition->id . '/tickettypes/' . $type->id . '/fields/' . $field->id;;

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->delete($url);

        $this->assertEquals(204, $response->status());

    }


}