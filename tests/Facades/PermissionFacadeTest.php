<?php
namespace Tests\Resources;

use App\Enums\Roles;
use App\Facades\PermissionsFacade;
use App\Facades\RolesFacade;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Models\User;
use Tests\TestCase;

class PermissionFacadeTest extends TestCase {

    public function testEventCanUpdate() {

        $event = Event::factory()->create();

        $super_admin = User::factory()->create();
        RolesFacade::eventMakeSuperAdmin($event, $super_admin);

        $admin = User::factory()->create();
        RolesFacade::eventMakeAdmin($event, $admin);

        $speaker = User::factory()->create();
        RolesFacade::eventMakeSpeaker($event, $speaker);

        $moderator = User::factory()->create();
        RolesFacade::eventMakeModerator($event, $moderator);

        $subscriber = User::factory()->create();
        RolesFacade::eventMakeSubscriber($event, $subscriber);

        $blocked = User::factory()->create();
        RolesFacade::eventMakeBlocked($event, $blocked);

        $none = User::factory()->create();
        RolesFacade::eventMakeNone($event, $none);

        $non_affiliated_user = User::factory()->create();
        
        $this->assertTrue(PermissionsFacade::eventCanUpdate($event, $super_admin));
        $this->assertTrue(PermissionsFacade::eventCanUpdate($event, $admin));
        $this->assertFalse(PermissionsFacade::eventCanUpdate($event, $speaker));
        $this->assertFalse(PermissionsFacade::eventCanUpdate($event, $moderator));
        $this->assertFalse(PermissionsFacade::eventCanUpdate($event, $subscriber));
        $this->assertFalse(PermissionsFacade::eventCanUpdate($event, $blocked));
        $this->assertFalse(PermissionsFacade::eventCanUpdate($event, $none));
        $this->assertFalse(PermissionsFacade::eventCanUpdate($event, $non_affiliated_user));
    }

    public function testEventCanEngage() {

        $event = Event::factory()->create();

        $super_admin = User::factory()->create();
        RolesFacade::eventMakeSuperAdmin($event, $super_admin);

        $admin = User::factory()->create();
        RolesFacade::eventMakeAdmin($event, $admin);

        $speaker = User::factory()->create();
        RolesFacade::eventMakeSpeaker($event, $speaker);

        $moderator = User::factory()->create();
        RolesFacade::eventMakeModerator($event, $moderator);

        $subscriber = User::factory()->create();
        RolesFacade::eventMakeSubscriber($event, $subscriber);

        $blocked = User::factory()->create();
        RolesFacade::eventMakeBlocked($event, $blocked);

        $none = User::factory()->create();
        RolesFacade::eventMakeNone($event, $none);

        $non_affiliated_user = User::factory()->create();
        
        $this->assertTrue(PermissionsFacade::eventCanEngage($event, $super_admin));
        $this->assertTrue(PermissionsFacade::eventCanEngage($event, $admin));
        $this->assertTrue(PermissionsFacade::eventCanEngage($event, $speaker));
        $this->assertTrue(PermissionsFacade::eventCanEngage($event, $moderator));
        $this->assertTrue(PermissionsFacade::eventCanEngage($event, $subscriber));
        $this->assertTrue(PermissionsFacade::eventCanEngage($event, $none));
        $this->assertTrue(PermissionsFacade::eventCanEngage($event, $non_affiliated_user));
        $this->assertFalse(PermissionsFacade::eventCanEngage($event, $blocked));
    }


}