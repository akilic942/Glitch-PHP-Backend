<?php
namespace Tests\Facades;

use App\Facades\EventInvitesFacade;
use App\Facades\PermissionsFacade;
use App\Models\Event;
use App\Models\EventInvite;
use App\Models\User;
use Tests\TestCase;

class EventInviteFacadeTest extends TestCase {

    public function testAcceptInvite() {

        $user = User::factory()->create();

        $event = Event::factory()->create();

        $invite = EventInvite::factory()->create(['event_id' => $event->id]);

        $this->assertNull($invite->user_id);
        $this->assertFalse(PermissionsFacade::eventCanJoinVideoSession($event, $user));

        EventInvitesFacade::acceptInvite($invite, $user);

        $invite->refresh();

        $this->assertEquals($invite->user_id, $user->id);
        $this->assertTrue(PermissionsFacade::eventCanJoinVideoSession($event, $user));
    }

}