<?php
namespace Tests\Resources;

use App\Enums\Roles;
use App\Facades\RolesFacade;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Models\User;
use Tests\TestCase;

class RolesFacadeTest extends TestCase {

    public function testEventMakeSuperAdmin() {

        $event = Event::factory()->create();

        $user = User::factory()->create();

        $userEvent = RolesFacade::eventMakeSuperAdmin($event, $user);

        $this->assertEquals($userEvent->user_role, Roles::SuperAdministrator);
        
    }

    public function testEventMakeAdmin() {

        $event = Event::factory()->create();

        $user = User::factory()->create();

        $userEvent = RolesFacade::eventMakeAdmin($event, $user);

        $this->assertEquals($userEvent->user_role, Roles::Administrator);
        
    }

    public function testEventMakeSpeaker() {

        $event = Event::factory()->create();

        $user = User::factory()->create();

        $userEvent = RolesFacade::eventMakeSpeaker($event, $user);

        $this->assertEquals($userEvent->user_role, Roles::Speaker);
        
    }

    public function testEventMakeModerator() {

        $event = Event::factory()->create();

        $user = User::factory()->create();

        $userEvent = RolesFacade::eventMakeModerator($event, $user);

        $this->assertEquals($userEvent->user_role, Roles::Moderator);
        
    }


    public function testEventMakeSubscriber() {

        $event = Event::factory()->create();

        $user = User::factory()->create();

        $userEvent = RolesFacade::eventMakeSubscriber($event, $user);

        $this->assertEquals($userEvent->user_role, Roles::Subscriber);
        
    }

    public function testEventMakeBlocked() {

        $event = Event::factory()->create();

        $user = User::factory()->create();

        $userEvent = RolesFacade::eventMakeBlocked($event, $user);

        $this->assertEquals($userEvent->user_role, Roles::Blocked);
        
    }

    public function testEventMakeNone() {

        $event = Event::factory()->create();

        $user = User::factory()->create();

        $userEvent = RolesFacade::eventMakeNone($event, $user);

        $this->assertEquals($userEvent->user_role, Roles::NONE);
        
    }


}