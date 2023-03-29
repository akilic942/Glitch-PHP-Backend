<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserFullResource extends JsonResource
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
     *     schema="UserFull",
     *     title="User Extended Model",
     *     description="The user model with complete information",
     *     allOf={
     *         @OA\Schema(ref="#/components/schemas/User"),
     *         @OA\Schema(
     *            @OA\Property(
     *              property="followers",
     *              description="A list of users who are following the current user.",
     *              @OA\Items(ref="#/components/schemas/User"),
     *              type="array"
     *          ),
     *          @OA\Property(
     *              property="following",
     *              description="A list of users who the current user is following.",
     *              @OA\Items(ref="#/components/schemas/User"),
     *              type="array"
     *          ),
     *          @OA\Property(
     *              property="events",
     *              description="A list of events the user is associated with.",
     *              @OA\Items(ref="#/components/schemas/Event"),
     *              type="array"
     *          ),
     *          @OA\Property(
     *              property="teams",
     *              description="A list of teams the user is associated with.",
     *              @OA\Items(ref="#/components/schemas/Team"),
     *              type="array"
     *          ),
     *          @OA\Property(
     *              property="competitions",
     *              description="A list of competitions the user is associated with.",
     *              @OA\Items(ref="#/components/schemas/Competition"),
     *              type="array"
     *          ),
     *          @OA\Property(
     *              property="token",
     *              description="An auth token that will only be returned when a user logins or registers.",
     *              type="string"
     *          ),
     *          @OA\Property(
     *              property="invirtu_user_id",
     *              description="The user id for invirtu. Will only be return of the auth token is the current user.",
     *              type="string"
     *          ),
     *          @OA\Property(
     *              property="invirtu_user_jwt_token",
     *              description="The invirtu auth token for this user. Will only be return of the auth token is the current user.",
     *              type="string"
     *          ),
     *          @OA\Property(
     *              property="facebook_auth_token",
     *              description="The auth token for facebook. Will only be return of the auth token is the current user.",
     *              type="string"
     *          ),
     *          @OA\Property(
     *              property="facebook_id",
     *              description="The user id for facebook. Will only be return of the auth token is the current user.",
     *              type="string"
     *          ),
     *          @OA\Property(
     *              property="twitch_auth_token",
     *              description="The auth token for twitch. Will only be return of the auth token is the current user.",
     *              type="string"
     *          ),
     *          @OA\Property(
     *              property="twitch_id",
     *              description="The user's id for twitch. Will only be return of the auth token is the current user.",
     *              type="string"
     *          ),
     *          @OA\Property(
     *              property="youtube_auth_token",
     *              description="The auth token for youtube. Will only be return of the auth token is the current user.",
     *              type="string"
     *          ),
     *          @OA\Property(
     *              property="youtube_id",
     *              description="The youtube user id. Will only be return of the auth token is the current user.",
     *              type="string"
     *          ),
     *          @OA\Property(
     *              property="stripe_express_account_id",
     *              description="The user's id for their stripe express account. Will only be return of the auth token is the current user.",
     *              type="string"
     *          ),
     *          @OA\Property(
     *              property="stripe_donation_purhcase_link_url",
     *              description="The page to donate to this user using stripe. Will only be return of the auth token is the current user.",
     *              type="string"
     *          ),
     *             
     *        ),
     *     },
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'username' => $this->username,
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

            'followers' => UserResource::collection($this->followers),
            'following' => UserResource::collection($this->following),
            'events' => EventResource::collection($this->events),
            'teams' => EventResource::collection($this->teams),
            'competitions' => EventResource::collection($this->competitions),
        ];

        $loggedin_user = Auth::user();

        if ($loggedin_user && $loggedin_user->id == $this->id) {
            $data['stripe_express_account_id'] = $this->stripe_express_account_id;
            $data['stripe_donation_purhcase_link_url'] = $this->stripe_donation_purhcase_link_url;
        }

        //If its the current user, we can return confidential information
        if ($loggedin_user && $loggedin_user->id == $this->id) {

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
