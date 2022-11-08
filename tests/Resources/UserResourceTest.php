<?php
namespace Tests\Resources;

use App\Http\Resources\DiscussionResource;
use App\Http\Resources\UserResource;
use App\Models\Discussion;
use App\Models\DiscussionComment;
use App\Models\DiscussionInteraction;
use App\Models\User;
use Tests\TestCase;

class UserResourceTest extends TestCase {

    public function testResource() {

        $user = User::factory()->create();

        $resource = UserResource::make($user);

        $this->assertEquals($user->id, $resource->id);
        $this->assertEquals($user->first_name, $resource->first_name);
        $this->assertEquals($user->last_name, $resource->last_name);
        $this->assertEquals((string) $user->created_at, $resource->created_at);
        $this->assertEquals($user->email, $resource->email);
        $this->assertEquals($user->username, $resource->username);

        
    }

}