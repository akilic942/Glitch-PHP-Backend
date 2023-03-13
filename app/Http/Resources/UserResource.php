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
     *     schema="User",
     *     title="User model",
     *     description="The model describes the user.",
     *     @OA\Property(
     *          property="id",
     *          description="The id of the user.",
     *          type="string",
     *          format="uuid",
     *          readOnly=true,
     *     ),
     *     @OA\Property(
     *          property="first_name",
     *          description="The users first name",
     *          type="string"
     *     ),
     *     @OA\Property(
     *          property="last_name",
     *          description="The users last name",
     *          type="string"
     *     ),
     *      @OA\Property(
     *          property="username",
     *          description="The username associated with the user",
     *          type="string"
     *     ),
     *      @OA\Property(
     *          property="display_name",
     *          description="The name to show to others for the users",
     *          type="string"
     *     ),
     *     @OA\Property(
     *          property="bio",
     *          description="Text information about the user.",
     *          type="string"
     *     ),
     *      @OA\Property(
     *          property="avatar",
     *          description="The users avatar image. This property is read-only and the avatar must be updated via image route.",
     *          type="string",
     *          readOnly=true
     *     ),
     *      @OA\Property(
     *          property="banner_image",
     *          description="The users banner image. This property is read-only and must be updated via the image route.",
     *          type="string",
     *          readOnly=true
     *     ),
     * 
     *      @OA\Property(
     *          property="twitter_page",
     *          description="The URL to the users Twitter Page.",
     *          type="string"
     *     ),
     *      @OA\Property(
     *          property="facebook_page",
     *          description="The URL to the users Facebook Page.",
     *          type="string"
     *     ),
     *      @OA\Property(
     *          property="instagram_page",
     *          description="The URL to the users Instagram Page.",
     *          type="string"
     *     ),
     *      @OA\Property(
     *          property="snapchat_page",
     *          description="The URL to the users Snapchat Page.",
     *          type="string"
     *     ),
     *      @OA\Property(
     *          property="tiktok_page",
     *          description="The URL to the users Tiktok Page.",
     *          title="tiktok_page",
     *          type="string"
     *     ),
     *      @OA\Property(
     *          property="twitch_page",
     *          description="The URL to the users Twitch Page.",
     *          title="twitch_page",
     *          type="string"
     *     ),
     *      @OA\Property(
     *          property="youtube_page",
     *          description="The URL to the users Youtube Page.",
     *          title="youtube_page",
     *          type="string"
     *     ),
     *      @OA\Property(
     *          property="paetron_page",
     *          description="The URL to the users Paetron Page.",
     *          title="paetron_page",
     *          type="string"
     *     ),
     * 
     * 
     *      @OA\Property(
     *          property="twitter_handle",
     *          description="The URL to the users Twitter Handle.",
     *          title="twitter_handle",
     *          type="string"
     *     ),
     *      @OA\Property(
     *          property="facebook_handle",
     *          description="The URL to the users Facebook Handle.",
     *          title="facebook_handle",
     *          type="string"
     *     ),
     *      @OA\Property(
     *          property="instagram_handle",
     *          description="The URL to the users Instagram Handle.",
     *          title="instagram_handle",
     *          type="string"
     *     ),
     *      @OA\Property(
     *          property="snapchat_handle",
     *          description="The URL to the users Snapchat Handle.",
     *          title="snapchat_handle",
     *          type="string"
     *     ),
     *      @OA\Property(
     *          property="tiktok_handle",
     *          description="The URL to the users Tiktok Handle.",
     *          type="string"
     *     ),
     *      @OA\Property(
     *          property="twitch_handle",
     *          description="The URL to the users Twitch Handle.",
     *          type="string"
     *     ),
     *      @OA\Property(
     *          property="youtube_handle",
     *          description="The URL to the users Youtube Handle.",
     *          type="string"
     *     ),
     *      @OA\Property(
     *          property="paetron_handle",
     *          description="The URL to the users Paetron Handle.",
     *          type="string"
     *     ),
     * 
     *      @OA\Property(
     *          property="created_at",
     *          description="The date the user account was created.",
     *          type="datetime",
     *          readOnly=true
     *     ),
     *     @OA\Property(
     *          property="updated_at",
     *          description="The date the user account was last updated.",
     *          type="datetime",
     *          readOnly=true
     *     ),
     * 
     * 
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
