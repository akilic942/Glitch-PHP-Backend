<?php
namespace Tests\Facades;

use App\Facades\CompetitionInvitesFacade;
use App\Facades\PermissionsFacade;
use App\Models\Competition;
use App\Models\CompetitionInvite;
use App\Models\User;
use Tests\TestCase;

class CompetitionInviteFacadeTest extends TestCase {

    public function testAcceptInvite() {

        $user = User::factory()->create();

        $competition = Competition::factory()->create();

        $invite = CompetitionInvite::factory()->create(['competition_id' => $competition->id]);

        $this->assertNull($invite->user_id);

        CompetitionInvitesFacade::acceptInvite($invite, $user);

        $invite->refresh();

        $this->assertEquals($invite->user_id, $user->id);
    }

}