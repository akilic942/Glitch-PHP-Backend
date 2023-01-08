<?php

namespace Tests\Routes;

use App\Facades\TeamInvitesFacade;
use App\Facades\RolesFacade;
use App\Models\Team;
use App\Models\TeamInvite;
use App\Models\User;
use Database\Factories\TeamInviteFactory;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class WebhookControllerTest extends TestCase
{


    public function testInvirtuBroadcastEnded(){

        $action = 'broadcasting_recording_finished';

        $event_id = '123242';

        $data = ['event_id' => $event_id];

        $url = $this->_getApiRoute() . 'webhooks/invirtu';

        $user = User::factory()->create();

        $response = $this->withHeaders([
            'Authorization Bearer' => $this->getAccessToken($user),
        ])->post($url, ['action' => $action, 'data' => $data]);

        $this->assertEquals(200, $response->status());

        $json = $response->json();

       

    }

   

}