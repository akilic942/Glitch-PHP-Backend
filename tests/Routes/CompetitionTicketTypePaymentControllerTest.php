<?php

namespace Tests\Routes;

use App\Enums\TicketTypes;
use App\Facades\CompetitionInvitesFacade;
use App\Facades\RolesFacade;
use App\Models\Competition;
use App\Models\CompetitionTicketPurchase;
use App\Models\CompetitionTicketType;
use App\Models\CompetitionTicketTypeField;
use App\Models\CompetitionTicketTypeSection;
use App\Models\User;
use Database\Factories\CompetitionInviteFactory;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CompetitionTicketTypePaymentControllerTest extends TestCase
{


    public function testListPurchases() {

        $competition = Competition::factory()->create();

        $type = CompetitionTicketType::factory()->create(['competition_id' => $competition->id]);

        $purchase = CompetitionTicketPurchase::factory(30)->create(['ticket_type_id' => $type->id, 'show_entry' => 1]);

        $user = User::factory()->create();

        RolesFacade::competitionMakeAdmin($competition, $user);

        $url = $this->_getApiRoute() . 'competitions/' . $competition->id . '/tickettypes/' . $type->id . '/purchases';

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->get($url);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

        $typesData = $json['data'];

        $this->assertCount(25, $typesData);

    }


    public function testPurchaseFree(){

        $competition = Competition::factory()->create();

        $type = CompetitionTicketType::factory()->create(['competition_id' => $competition->id, 'ticket_type' => TicketTypes::FREE]);

        $user = User::factory()->create();

        RolesFacade::competitionMakeAdmin($competition, $user);

        $url = $this->_getApiRoute() . 'competitions/' . $competition->id . '/tickettypes/' . $type->id . '/purchases';

        /*$response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->post($url);

        $this->assertEquals(422, $response->status());*/

        $faker = \Faker\Factory::create();

        $data = [
            'quantity' => rand(1,10),
        ];
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->getAccessToken($user),
        ])->post($url, $data);

        print_r($response->content());
        $this->assertEquals(201, $response->status());

        $json = $response->json();

        $result = $json['data'];

        print_r($result);

        $this->assertEquals($result['ticket_type_id'], $type->id);
        $this->assertNotNull($result['id']);
        //$this->assertEquals($competition['user']['id'], $user->id);

    }


}