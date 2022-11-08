<?php

namespace Tests\Facades;

use App\Facades\MessageFacade;
use App\Models\User;
use Tests\TestCase;

class MessageFacadeTest extends TestCase {

    public function testCreateOrRetrieveThread() {

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();
        $user4 = User::factory()->create();

        $group1 = [
            $user1->id,
            $user2->id,
            $user3->id,
        ];

        $group2 = [
            $user3->id,
            $user4->id,
        ];

        $thread_group_1 = MessageFacade::createOrRetrieveThread($group1);

        $thread_group_2 = MessageFacade::createOrRetrieveThread($group2);

        $this->assertNotEquals($thread_group_1->id, $thread_group_2->id);

        $thread_group_1_2nd_call = MessageFacade::createOrRetrieveThread($group1);

        $thread_group_2_2nd_call = MessageFacade::createOrRetrieveThread($group2);

        $this->assertEquals($thread_group_1->id, $thread_group_1_2nd_call->id);

        $this->assertEquals($thread_group_2->id, $thread_group_2_2nd_call->id);

    }


}