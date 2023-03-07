<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

     /**
     * Class User.
     *
     * @OA\Schema(
     *     schema="UserModel",
     *     title="User model",
     *     description="User model",
     *     @OA\Property(
     *          property="first_name",
     *          description="The users first name",
     *          title="first_name",
     *          type="string"
     *     ),
     *     @OA\Property(
     *          property="last_name",
     *          description="The users last name",
     *          title="last_name",
     *          type="string"
     *     ),
     *      @OA\Property(
     *          property="username",
     *          description="The username associated with the user",
     *          title="username",
     *          type="string"
     *     )
     * )
     * 
     * 
     *
     * 
     */
    public function toArray($request)
    {

        $data = [
            'id' => $this->id,
            
            //User Info
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'username' => $this->username,
            'display_name' => $this->display_name,
            'bio' => $this->bio,

            //Media
            'avatar' => !empty($this->avatar) ? Storage::disk('s3')->url($this->avatar) : null,
            'banner_image' => !empty($this->banner_image) ? Storage::disk('s3')->url($this->banner_image) : null,

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
        ];

        return $data;
    }
}
