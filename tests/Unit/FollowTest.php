<?php

namespace Tests\Unit;

use App\Models\Follow;
use App\Models\User;
use Tests\TestCase;

class FollowTest extends TestCase {

    public function testCreation() {

        $user1 = User::factory()->create();

        $user2 = User::factory()->create();

        $data = array(
            'following_id' => $user1->id,
            'follower_id' => $user2->id
        );

        $follow = new Follow();

        $tmp_field = $data['following_id'];
        unset($data['following_id']);
        $this->assertFalse($follow->validate($data));
        $data['following_id'] = $tmp_field;

        $tmp_field = $data['follower_id'];
        unset($data['follower_id']);
        $this->assertFalse($follow->validate($data));
        $data['follower_id'] = $tmp_field;


        $this->assertTrue($follow->validate($data));

        $follow = Follow::create($data);

        $this->assertNotNull($follow->created_at);
    }

    public function testFollowing() {

        $user1 = User::factory()->create();

        $user2 = User::factory()->create();


        $follow = Follow::create(array(
            'following_id' => $user1->id,
            'follower_id' => $user2->id
        ));

        $this->assertEquals($follow->following->id, $user1->id);
        $this->assertEquals($follow->follower->id, $user2->id);

    }



}