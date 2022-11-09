<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserFullResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $data = [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'username' => $this->username,
            'bio' => $this->bio,

            //Social Pages
            'twitter_page' => $this->twitter_page,
            'facebook_page' => $this->facebook_page,
            'instagram_page' => $this->instagram_page,
            'snapchat_page' => $this->snapchat_page,
            'tiktok_page' => $this->tiktok_page,
            'twitch_page' => $this->twitch_page,
            'youtube_page' => $this->youtube_page,
            'paetron_page' => $this->paetron_page,

            //Social Handles
            'twitter_handle' => $this->twitter_handle,
            'facebook_handle' => $this->facebook_handle,
            'instagram_handle' => $this->instagram_handle,
            'snapchat_handle' => $this->snapchat_handle,
            'tiktok_handle' => $this->tiktok_handle,
            'twitch_handle' => $this->twitch_handle,
            'youtube_handle' => $this->youtube_handle,
            'paetron_handle' => $this->paetron_handle,

            //Timestamp Info
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,

            'followers' => $this->followers,
            'following' => $this->following,
            'events' => $this->events
        ];

        $loggedin_user = Auth::user();

        //If its the current user, we can return confidential information
        if($loggedin_user && $loggedin_user->id == $this->id) {

            $confidential = [
                'email' => $this->email,
                'phone_number' => $this->phone_number,
                'phone_number_country_code' => $this->phone_number_country_code,
                'date_of_birth' => $this->date_of_birth,

                'token' => ($this->token) ?: null,
                'invirtu_user_id' => $this->email,
                'invirtu_user_jwt_token' => $this->invirtu_user_jwt_token,

                //Facebook OAuth
                'facebook_auth_token' => $this->facebook_auth_token,
                'facebook_id' => $this->facebook_id,

                //Twitch OAuth
                'twitch_auth_token' => $this->twitch_auth_token,
                'twitch_id' => $this->twitch_id,

                //Youtube OAuth
                'youtube_auth_token' => $this->youtube_auth_token,
                'youtube_id' => $this->youtube_id
            ];

            $data += $confidential;

        }


        return $data;
    }
}
