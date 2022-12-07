<?php
namespace Tests\Resources;

use App\Enums\Roles;
use App\Facades\RolesFacade;
use App\Http\Resources\EventResource;
use App\Models\Competition;
use App\Models\Event;
use App\Models\Team;
use App\Models\User;
use Tests\TestCase;

class RolesFacadeTest extends TestCase {

    //Event Test
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

    //Competition Test

    public function testCompetitionMakeSuperAdmin() {

        $competition = Competition::factory()->create();

        $user = User::factory()->create();

        $userEvent = RolesFacade::competitionMakeSuperAdmin($competition, $user);

        $this->assertEquals($userEvent->user_role, Roles::SuperAdministrator);
        
    }

    public function testCompetitionMakeAdmin() {

        $competition = Competition::factory()->create();

        $user = User::factory()->create();

        $userEvent = RolesFacade::competitionMakeAdmin($competition, $user);

        $this->assertEquals($userEvent->user_role, Roles::Administrator);
        
    }

    public function testCompetitionMakeSpeaker() {

        $competition = Competition::factory()->create();

        $user = User::factory()->create();

        $userEvent = RolesFacade::competitionMakeSpeaker($competition, $user);

        $this->assertEquals($userEvent->user_role, Roles::Speaker);
        
    }

    public function testCompetitionMakeModerator() {

        $competition = Competition::factory()->create();

        $user = User::factory()->create();

        $userEvent = RolesFacade::competitionMakeModerator($competition, $user);

        $this->assertEquals($userEvent->user_role, Roles::Moderator);
        
    }


    public function testCompetitionMakeSubscriber() {

        $competition = Competition::factory()->create();

        $user = User::factory()->create();

        $userEvent = RolesFacade::competitionMakeSubscriber($competition, $user);

        $this->assertEquals($userEvent->user_role, Roles::Subscriber);
        
    }

    public function testCompetitionMakeBlocked() {

        $competition = Competition::factory()->create();

        $user = User::factory()->create();

        $userEvent = RolesFacade::competitionMakeBlocked($competition, $user);

        $this->assertEquals($userEvent->user_role, Roles::Blocked);
        
    }

    public function testCompetitionMakeNone() {

        $competition = Competition::factory()->create();

        $user = User::factory()->create();

        $userEvent = RolesFacade::competitionMakeNone($competition, $user);

        $this->assertEquals($userEvent->user_role, Roles::NONE);
        
    }

    //Teams Test

    public function testTeamMakeSuperAdmin() {

        $team = Team::factory()->create();

        $user = User::factory()->create();

        $userEvent = RolesFacade::teamMakeSuperAdmin($team, $user);

        $this->assertEquals($userEvent->user_role, Roles::SuperAdministrator);
        
    }

    public function testTeamMakeAdmin() {

        $team = Team::factory()->create();

        $user = User::factory()->create();

        $userEvent = RolesFacade::teamMakeAdmin($team, $user);

        $this->assertEquals($userEvent->user_role, Roles::Administrator);
        
    }

    public function testTeamMakeSpeaker() {

        $team = Team::factory()->create();

        $user = User::factory()->create();

        $userEvent = RolesFacade::teamMakeSpeaker($team, $user);

        $this->assertEquals($userEvent->user_role, Roles::Speaker);
        
    }

    public function testTeamMakeModerator() {

        $team = Team::factory()->create();

        $user = User::factory()->create();

        $userEvent = RolesFacade::teamMakeModerator($team, $user);

        $this->assertEquals($userEvent->user_role, Roles::Moderator);
        
    }


    public function testTeamMakeSubscriber() {

        $team = Team::factory()->create();

        $user = User::factory()->create();

        $userEvent = RolesFacade::teamMakeSubscriber($team, $user);

        $this->assertEquals($userEvent->user_role, Roles::Subscriber);
        
    }

    public function testTeamMakeBlocked() {

        $team = Team::factory()->create();

        $user = User::factory()->create();

        $userEvent = RolesFacade::teamMakeBlocked($team, $user);

        $this->assertEquals($userEvent->user_role, Roles::Blocked);
        
    }

    public function testTeamMakeNone() {

        $team = Team::factory()->create();

        $user = User::factory()->create();

        $userEvent = RolesFacade::teamMakeNone($team, $user);

        $this->assertEquals($userEvent->user_role, Roles::NONE);
    }
        


}