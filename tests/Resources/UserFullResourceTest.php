<?php
namespace Tests\Resources;

use App\Http\Resources\UserFullResource;
use App\Models\User;
use Tests\TestCase;

class UserFullResourceTest extends TestCase {

    public function testResource() {

        $user = User::factory()->create();

        $resource = new UserFullResource($user);

        //Test Basic Info
        $this->assertEquals($user->id, $resource->id);
        $this->assertEquals($user->first_name, $resource->first_name);
        $this->assertEquals($user->last_name, $resource->last_name);
        $this->assertEquals($user->display_name, $resource->display_name);
        $this->assertEquals($user->bio, $resource->bio);
        
        //Test Dates
        $this->assertEquals((string) $user->created_at, $resource->created_at);
        $this->assertEquals((string) $user->updated_at, $resource->updated_at);

        //Test Socials Pages
        $this->assertEquals($user->twitter_page, $resource->twitter_page);
        $this->assertEquals($user->facebook_page, $resource->facebook_page);
        $this->assertEquals($user->twitch_page, $resource->twitch_page);
        $this->assertEquals($user->instagram_page, $resource->instagram_page);
        $this->assertEquals($user->snapchat_page, $resource->snapchat_page);
        $this->assertEquals($user->youtube_page, $resource->youtube_page);
        $this->assertEquals($user->paetron_page, $resource->paetron_page);

        //Test Socials Pages
        $this->assertEquals($user->twitter_handle, $resource->twitter_handle);
        $this->assertEquals($user->facebook_handle, $resource->facebook_handle);
        $this->assertEquals($user->twitch_handle, $resource->twitch_handle);
        $this->assertEquals($user->instagram_handle, $resource->instagram_handle);
        $this->assertEquals($user->snapchat_handle, $resource->snapchat_handle);
        $this->assertEquals($user->youtube_handle, $resource->youtube_handle);
        $this->assertEquals($user->paetron_handle, $resource->paetron_handle);

        //User Not Auth, Should Be Empty
        //$this->assertNull($resource->email);
        //$this->assertNull($resource->phone_number);
        //$this->assertNull($resource->phone_number_country_code);
        //$this->assertNull($resource->date_of_birth);
        

        
    }

}