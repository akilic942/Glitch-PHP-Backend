<?php
namespace App\Facades;

use App\Models\Follow;
use App\Models\User;

class FollowFacade {

    public static function toggleFollowing(User $following, User $follower) {

        $follow = Follow::where('following_id', $following->id)
        ->where('follower_id', $follower->id)
        ->first();

        if($follow){
            Follow::where('following_id', $following->id)
            ->where('follower_id', $follower->id)
            ->forceDelete();

            return false;
        } else {

            $data = ['following_id'=>$following->id, 'follower_id' => $follower->id];
            
            $follow = Follow::create($data);

            return $follow;
        }

    }


}