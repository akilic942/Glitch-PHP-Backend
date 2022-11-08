<?php

namespace Tests\Facades;

use App\Facades\FollowFacade;

use App\Models\Follow;
use App\Models\User;
use Tests\TestCase;

class FollowFacadeTest extends TestCase {

    public function testToggleFollow() {

        $user = User::factory()->create();

        $followers = Follow::where('following_id', $user->id)->get();

        $this->assertCount(0, $followers);

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();
        $user4 = User::factory()->create();

        FollowFacade::toggleFollowing($user, $user1);
        FollowFacade::toggleFollowing($user, $user2);
        FollowFacade::toggleFollowing($user, $user3);
        FollowFacade::toggleFollowing($user, $user4);

        //all interactions
        $followers = Follow::where('following_id', $user->id)->get();
        $this->assertCount(4, $followers);

        FollowFacade::toggleFollowing($user, $user1);
        FollowFacade::toggleFollowing($user, $user3);

        //all interactions
        $followers = Follow::where('following_id', $user->id)->get();
        $this->assertCount(2, $followers);

    }
}