<?php
namespace Tests\Resources;

use App\Enums\Roles;
use App\Facades\PermissionsFacade;
use App\Facades\RolesFacade;
use App\Http\Resources\EventResource;
use App\Models\Competition;
use App\Models\CompetitionTicketPurchase;
use App\Models\CompetitionTicketType;
use App\Models\Event;
use App\Models\Team;
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

    public function testCompetitionCanUpdate() {

        $competition = Competition::factory()->create();

        $super_admin = User::factory()->create();
        RolesFacade::competitionMakeSuperAdmin($competition, $super_admin);

        $admin = User::factory()->create();
        RolesFacade::competitionMakeAdmin($competition, $admin);

        $speaker = User::factory()->create();
        RolesFacade::competitionMakeSpeaker($competition, $speaker);

        $moderator = User::factory()->create();
        RolesFacade::competitionMakeModerator($competition, $moderator);

        $subscriber = User::factory()->create();
        RolesFacade::competitionMakeSubscriber($competition, $subscriber);

        $blocked = User::factory()->create();
        RolesFacade::competitionMakeBlocked($competition, $blocked);

        $none = User::factory()->create();
        RolesFacade::competitionMakeNone($competition, $none);

        $non_affiliated_user = User::factory()->create();
        
        $this->assertTrue(PermissionsFacade::competitionCanUpdate($competition, $super_admin));
        $this->assertTrue(PermissionsFacade::competitionCanUpdate($competition, $admin));
        $this->assertFalse(PermissionsFacade::competitionCanUpdate($competition, $speaker));
        $this->assertFalse(PermissionsFacade::competitionCanUpdate($competition, $moderator));
        $this->assertFalse(PermissionsFacade::competitionCanUpdate($competition, $subscriber));
        $this->assertFalse(PermissionsFacade::competitionCanUpdate($competition, $blocked));
        $this->assertFalse(PermissionsFacade::competitionCanUpdate($competition, $none));
        $this->assertFalse(PermissionsFacade::competitionCanUpdate($competition, $non_affiliated_user));
    }

    public function testCompetitionCanEngage() {

        $competition = Competition::factory()->create();

        $super_admin = User::factory()->create();
        RolesFacade::competitionMakeSuperAdmin($competition, $super_admin);

        $admin = User::factory()->create();
        RolesFacade::competitionMakeAdmin($competition, $admin);

        $speaker = User::factory()->create();
        RolesFacade::competitionMakeSpeaker($competition, $speaker);

        $moderator = User::factory()->create();
        RolesFacade::competitionMakeModerator($competition, $moderator);

        $subscriber = User::factory()->create();
        RolesFacade::competitionMakeSubscriber($competition, $subscriber);

        $blocked = User::factory()->create();
        RolesFacade::competitionMakeBlocked($competition, $blocked);

        $none = User::factory()->create();
        RolesFacade::competitionMakeNone($competition, $none);

        $non_affiliated_user = User::factory()->create();
        
        $this->assertTrue(PermissionsFacade::competitionCanEngage($competition, $super_admin));
        $this->assertTrue(PermissionsFacade::competitionCanEngage($competition, $admin));
        $this->assertTrue(PermissionsFacade::competitionCanEngage($competition, $speaker));
        $this->assertTrue(PermissionsFacade::competitionCanEngage($competition, $moderator));
        $this->assertTrue(PermissionsFacade::competitionCanEngage($competition, $subscriber));
        $this->assertTrue(PermissionsFacade::competitionCanEngage($competition, $none));
        $this->assertTrue(PermissionsFacade::competitionCanEngage($competition, $non_affiliated_user));
        $this->assertFalse(PermissionsFacade::competitionCanEngage($competition, $blocked));
    }

    public function testTeamCanUpdate() {

        $team = Team::factory()->create();

        $super_admin = User::factory()->create();
        RolesFacade::teamMakeSuperAdmin($team, $super_admin);

        $admin = User::factory()->create();
        RolesFacade::teamMakeAdmin($team, $admin);

        $speaker = User::factory()->create();
        RolesFacade::teamMakeSpeaker($team, $speaker);

        $moderator = User::factory()->create();
        RolesFacade::teamMakeModerator($team, $moderator);

        $subscriber = User::factory()->create();
        RolesFacade::teamMakeSubscriber($team, $subscriber);

        $blocked = User::factory()->create();
        RolesFacade::teamMakeBlocked($team, $blocked);

        $none = User::factory()->create();
        RolesFacade::teamMakeNone($team, $none);

        $non_affiliated_user = User::factory()->create();
        
        $this->assertTrue(PermissionsFacade::teamCanUpdate($team, $super_admin));
        $this->assertTrue(PermissionsFacade::teamCanUpdate($team, $admin));
        $this->assertFalse(PermissionsFacade::teamCanUpdate($team, $speaker));
        $this->assertFalse(PermissionsFacade::teamCanUpdate($team, $moderator));
        $this->assertFalse(PermissionsFacade::teamCanUpdate($team, $subscriber));
        $this->assertFalse(PermissionsFacade::teamCanUpdate($team, $blocked));
        $this->assertFalse(PermissionsFacade::teamCanUpdate($team, $none));
        $this->assertFalse(PermissionsFacade::teamCanUpdate($team, $non_affiliated_user));
    }

    public function testTeamCanEngage() {

        $team = Team::factory()->create();

        $super_admin = User::factory()->create();
        RolesFacade::teamMakeSuperAdmin($team, $super_admin);

        $admin = User::factory()->create();
        RolesFacade::teamMakeAdmin($team, $admin);

        $speaker = User::factory()->create();
        RolesFacade::teamMakeSpeaker($team, $speaker);

        $moderator = User::factory()->create();
        RolesFacade::teamMakeModerator($team, $moderator);

        $subscriber = User::factory()->create();
        RolesFacade::teamMakeSubscriber($team, $subscriber);

        $blocked = User::factory()->create();
        RolesFacade::teamMakeBlocked($team, $blocked);

        $none = User::factory()->create();
        RolesFacade::teamMakeNone($team, $none);

        $non_affiliated_user = User::factory()->create();
        
        $this->assertTrue(PermissionsFacade::teamCanEngage($team, $super_admin));
        $this->assertTrue(PermissionsFacade::teamCanEngage($team, $admin));
        $this->assertTrue(PermissionsFacade::teamCanEngage($team, $speaker));
        $this->assertTrue(PermissionsFacade::teamCanEngage($team, $moderator));
        $this->assertTrue(PermissionsFacade::teamCanEngage($team, $subscriber));
        $this->assertTrue(PermissionsFacade::teamCanEngage($team, $none));
        $this->assertTrue(PermissionsFacade::teamCanEngage($team, $non_affiliated_user));
        $this->assertFalse(PermissionsFacade::teamCanEngage($team, $blocked));
    }

    public function testCompetitionCanAccessTicketPurchase() {

        $user_admin = User::factory()->create();

        $user_purchaser = User::factory()->create();

        $user_denied = User::factory()->create();

        $competition = Competition::factory()->create();

        RolesFacade::competitionMakeAdmin($competition, $user_admin);

        $type = CompetitionTicketType::factory()->create(['competition_id' => $competition->id]);

        $purchase = CompetitionTicketPurchase::factory()->create(['ticket_type_id' => $type->id, 'user_id' => $user_purchaser->id]);

        $purchase->refresh();

        $result = PermissionsFacade::competitionCanAccessTicketPurchase($purchase, $purchase->access_token);

        $this->assertTrue($result);

        $result = PermissionsFacade::competitionCanAccessTicketPurchase($purchase, $purchase->admin_token);

        $this->assertTrue($result);

        $result = PermissionsFacade::competitionCanAccessTicketPurchase($purchase, null, $user_admin);

        $this->assertTrue($result);

        $result = PermissionsFacade::competitionCanAccessTicketPurchase($purchase, null, $user_purchaser);

        $this->assertTrue($result);

        $result = PermissionsFacade::competitionCanAccessTicketPurchase($purchase, null, $user_denied);

        $this->assertFalse($result);

        $result = PermissionsFacade::competitionCanAccessTicketPurchase($purchase, null, null);

        $this->assertFalse($result);

        
    }

    public function testCompetitionCanAdminTicketPurchase() {

        $user_admin = User::factory()->create();

        $user_purchaser = User::factory()->create();

        $user_denied = User::factory()->create();

        $competition = Competition::factory()->create();

        RolesFacade::competitionMakeAdmin($competition, $user_admin);

        $type = CompetitionTicketType::factory()->create(['competition_id' => $competition->id]);

        $purchase = CompetitionTicketPurchase::factory()->create(['ticket_type_id' => $type->id, 'user_id' => $user_purchaser->id]);

        $purchase->refresh();

        $result = PermissionsFacade::competitionCanAdminTicketPurchase($purchase, $purchase->access_token);

        $this->assertFalse($result);

        $result = PermissionsFacade::competitionCanAdminTicketPurchase($purchase, $purchase->admin_token);

        $this->assertTrue($result);

        $result = PermissionsFacade::competitionCanAdminTicketPurchase($purchase, null, $user_admin);

        $this->assertTrue($result);

        $result = PermissionsFacade::competitionCanAdminTicketPurchase($purchase, null, $user_purchaser);

        $this->assertFalse($result);

        $result = PermissionsFacade::competitionCanAdminTicketPurchase($purchase, null, $user_denied);

        $this->assertFalse($result);

        $result = PermissionsFacade::competitionCanAdminTicketPurchase($purchase, null, null);

        $this->assertFalse($result);

        
    }


}