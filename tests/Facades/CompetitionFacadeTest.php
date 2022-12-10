<?php
namespace Tests\Facades;

use App\Facades\CompetitionFacade;
use App\Facades\EventsFacade;
use App\Facades\PermissionsFacade;
use App\Facades\RolesFacade;
use App\Models\Competition;
use App\Models\CompetitionRound;
use App\Models\CompetitionRoundBracket;
use App\Models\Event;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CompetitionFacadeTest extends TestCase {

    public function testMakeRound() {

    $this->assertTrue(true);

        $competition = Competition::factory()->create();

        $this->assertEquals(5, CompetitionFacade::makeRounds($competition, 12, 2));

        $this->assertEquals(5, CompetitionRound::where('competition_id', $competition->id)->count());


        $competition = Competition::factory()->create();

        $this->assertEquals(4, CompetitionFacade::makeRounds($competition, 10, 2));

        $this->assertEquals(4, CompetitionRound::where('competition_id', $competition->id)->count());


        $competition = Competition::factory()->create();

        $this->assertEquals(3, CompetitionFacade::makeRounds($competition, 5, 2));

        $this->assertEquals(3, CompetitionRound::where('competition_id', $competition->id)->count());

    }

    public function testCanRegisterTeam() {

        $competition = Competition::factory()->create(['max_registration_for_teams' => 3]);

        $user1 = User::factory()->create();
        $team1 = Team::factory()->create();
        RolesFacade::teamMakeAdmin($team1, $user1);

        $user2 = User::factory()->create();
        $team2 = Team::factory()->create();
        RolesFacade::teamMakeAdmin($team2, $user2);

        $user3 = User::factory()->create();
        $team3 = Team::factory()->create();
        RolesFacade::teamMakeAdmin($team3, $user3);

        $user4 = User::factory()->create();
        $team4 = Team::factory()->create();
        RolesFacade::teamMakeAdmin($team4, $user4);

        $userbanend = User::factory()->create();
        $teambanned = Team::factory()->create();
        RolesFacade::competitionMakeBlocked($competition, $userbanend);

        //Test banned Team
        $result = CompetitionFacade::canRegisterTeam($competition, $teambanned, $userbanend);
        $this->assertFalse($result['status']);

        //Register A Team
        $result = CompetitionFacade::canRegisterTeam($competition, $team1, $user1);
        $this->assertTrue($result['status']);
        CompetitionFacade::registerTeam($competition, $team1);

        //Try to register the team again
        $result = CompetitionFacade::canRegisterTeam($competition, $team1, $user1);
        $this->assertFalse($result['status']);

        //Register A Team
        $result = CompetitionFacade::canRegisterTeam($competition, $team3, $user3);
        $this->assertTrue($result['status']);
        CompetitionFacade::registerTeam($competition, $team3);

        //Register A Team
        $result = CompetitionFacade::canRegisterTeam($competition, $team2, $user2);
        $this->assertTrue($result['status']);
        CompetitionFacade::registerTeam($competition, $team2);


        //Cannot Register Because Full
        $result = CompetitionFacade::canRegisterTeam($competition, $team4, $user4);
        $this->assertFalse($result['status']);

        $competition2 = Competition::factory()->create(['minimum_team_size' => 3]);

        //Team Member Size Test
        $team_member_1 = User::factory()->create();
        $team_member_2 = User::factory()->create();
        $team_member_3 = User::factory()->create();
        
        $team5 = Team::factory()->create();
        RolesFacade::teamMakeAdmin($team5, $team_member_1);

        $result = CompetitionFacade::canRegisterTeam($competition2, $team5, $team_member_1);
        $this->assertFalse($result['status']);

        RolesFacade::teamMakeParticpant($team5, $team_member_2);
        $result = CompetitionFacade::canRegisterTeam($competition2, $team5, $team_member_1);
        $this->assertFalse($result['status']);

        RolesFacade::teamMakeParticpant($team5, $team_member_3);
        $result = CompetitionFacade::canRegisterTeam($competition2, $team5, $team_member_1);
        $this->assertTrue($result['status']);

        
    }

    public function testCanRegisterUser() {

        $competition = Competition::factory()->create(['max_registration_for_users' => 3]);

        $user1 = User::factory()->create();

        $user2 = User::factory()->create();

        $user3 = User::factory()->create();

        $user4 = User::factory()->create();

        $userbanend = User::factory()->create();
        RolesFacade::competitionMakeBlocked($competition, $userbanend);

        //Test banned Team
        $result = CompetitionFacade::canRegisterUser($competition, $userbanend);
        $this->assertFalse($result['status']);

        //Register A User
        $result = CompetitionFacade::canRegisterUser($competition, $user1);
        $this->assertTrue($result['status']);
        CompetitionFacade::registerUser($competition, $user1);

        //Try to register the team again
        $result = CompetitionFacade::canRegisterUser($competition, $user1);
        $this->assertFalse($result['status']);

        //Register A Team
        $result = CompetitionFacade::canRegisterUser($competition,  $user3);
        $this->assertTrue($result['status']);
        CompetitionFacade::registerUser($competition, $user3);

        //Register A Team
        $result = CompetitionFacade::canRegisterUser($competition, $user2);
        $this->assertTrue($result['status']);
        CompetitionFacade::registerUser($competition, $user2);

        //Cannot Register Because Full
        $result = CompetitionFacade::canRegisterUser($competition, $user4);
        $this->assertFalse($result['status']);
        
    }

    public function testUserBracketPlacementFirstRound() {

        $competition = Competition::factory()->create();

        $user_count = rand(1, 50);

        $users = User::factory($user_count)->create();

        foreach($users as $user) {
            CompetitionFacade::registerUser($competition, $user);
        }

        $this->assertEquals(0, CompetitionRoundBracket::where('competition_id', $competition->id)->count());

        $brackets = CompetitionFacade::autoAssignCompetitorsUsers($competition, 1, 2);

        $this->assertEquals($user_count, CompetitionRoundBracket::where('competition_id', $competition->id)->count());


    }

    public function testUserBracketPlacementFirstSubRounds() {

        $competition = Competition::factory()->create();

        $user_count = rand(1, 50);

        $users = User::factory($user_count)->create();

        foreach($users as $user) {
            CompetitionFacade::registerUser($competition, $user);
        }

        $this->assertEquals(0, CompetitionRoundBracket::where('competition_id', $competition->id)->count());

        $brackets = CompetitionFacade::autoAssignCompetitorsUsers($competition, 1, 2);

        $this->assertEquals($user_count, CompetitionRoundBracket::where('competition_id', $competition->id)->count());

        $winners = 0;

        foreach($brackets as $key => $bracket){

            if($key % 2 == 0) {

                $winners += 1;

                $bracket->is_winner = true;
                $bracket->save();
            }
        }

        $sub_brackets = CompetitionFacade::autoAssignCompetitorsUsers($competition, 2, 2);

        $this->assertEquals($winners, count($sub_brackets));


    }

    public function testTeamBracketPlacementFirstRound() {

        $competition = Competition::factory()->create();

        $team_count = rand(1, 50);

        $teams = Team::factory($team_count)->create();

        foreach($teams as $team) {
            CompetitionFacade::registerTeam($competition, $team);
        }

        $this->assertEquals(0, CompetitionRoundBracket::where('competition_id', $competition->id)->count());

        $brackets = CompetitionFacade::autoAssignCompetitorsTeams($competition, 1, 2);

        $this->assertEquals($team_count, CompetitionRoundBracket::where('competition_id', $competition->id)->count());

    }

    public function testTeamBracketPlacementSubRound() {

        $competition = Competition::factory()->create();

        $team_count = rand(1, 50);

        $teams = Team::factory($team_count)->create();

        foreach($teams as $team) {
            CompetitionFacade::registerTeam($competition, $team);
        }

        $this->assertEquals(0, CompetitionRoundBracket::where('competition_id', $competition->id)->count());

        $brackets = CompetitionFacade::autoAssignCompetitorsTeams($competition, 1, 2);

        $this->assertEquals($team_count, CompetitionRoundBracket::where('competition_id', $competition->id)->count());

        $winners = 0;

        foreach($brackets as $key => $bracket){

            if($key % 2 == 0) {

                $winners += 1;

                $bracket->is_winner = true;
                $bracket->save();
            }
        }

        $sub_brackets = CompetitionFacade::autoAssignCompetitorsTeams($competition, 2, 2);

        $this->assertEquals($winners, count($sub_brackets));


    }

}