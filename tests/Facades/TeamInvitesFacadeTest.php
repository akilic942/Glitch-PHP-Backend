<?php
namespace Tests\Facades;

use App\Facades\TeamInvitesFacade;
use App\Facades\PermissionsFacade;
use App\Models\Team;
use App\Models\TeamInvite;
use App\Models\User;
use Tests\TestCase;

class TeamInviteFacadeTest extends TestCase {

    public function testAcceptInvite() {

        $user = User::factory()->create();

        $team = Team::factory()->create();

        $invite = TeamInvite::factory()->create(['team_id' => $team->id]);

        $this->assertNull($invite->user_id);

        TeamInvitesFacade::acceptInvite($invite, $user);

        $invite->refresh();

        $this->assertEquals($invite->user_id, $user->id);
    }

}