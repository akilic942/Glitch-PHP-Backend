<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CompetitionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

     /**
     * Class CompetitionResource for CompetitionController.
     *
     * @OA\Schema(
     *     schema="CompetitionController",
     *     title="Competition Model",
     *     description="Competition Model",
     *     @OA\Property(
     *          property="id",
     *          title="id",
     *          type="string"
     *     )
     * )
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'rules' => $this->rules,
            'agreement' => $this->agreement,
            'schedule' => $this->schedule,
            'disqualifiers' => $this->disqualifiers,
            'refund_policy' => $this->refund_policy,
            'harassment_policy' => $this->harassment_policy,
            'saftey_policy' => $this->harassment_policy,

            //Social Pages
            'twitter_page' => $this->twitter_page,
            'facebook_page' => $this->facebook_page,
            'instagram_page' => $this->instagram_page,
            'snapchat_page' => $this->snapchat_page,
            'tiktok_page' => $this->tiktok_page,
            'twitch_page' => $this->twitch_page,
            'youtube_page' => $this->youtube_page,
            'paetron_page'=> $this->paetron_page,
            'github_page' => $this->github_page,

            //Social Handles
            'twitter_handle' => $this->itwitter_handle,
            'facebook_handle' => $this->facebook_handle,
            'instagram_handle' => $this->instagram_handle,
            'snapchat_handle' => $this->snapchat_handle,
            'tiktok_handle' => $this->tiktok_handle,
            'twitch_handle' => $this->twitch_handle,
            'youtube_handle' => $this->youtube_handle,
            'paetron_handle' => $this->paetron_handle,
            'github_handle' => $this->github_handle,

            'contact_name' => $this->contact_name,
            'contact_email' => $this->contact_email,
            'contact_phone_number' => $this->contact_phone_number,
            'website' => $this->website,

            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'registration_start_date' => $this->registration_start_date,
            'registration_end_date' => $this->registration_end_date,

            'allow_team_signup' => $this->allow_team_signup,
            'allow_individual_signup' => $this->allow_individual_signup,
            'is_private' => $this->is_private,

            'competitors_per_match' => $this->competitors_per_match,
            'winners_per_match' => $this->winners_per_match,

            'max_registration_for_teams' => $this->max_registration_for_teams,
            'max_registration_for_users' => $this->max_registration_for_users,

            'minimum_team_size' => $this->minimum_team_size,

            'checkin_enabled' => $this->checkin_enabled,
            'checkin_mintues_prior' => $this->checkin_mintues_prior,

            'team_signup_requires_approval' => $this->team_signup_requires_approval,
            'individual_signup_requires_approval' => $this->individual_signup_requires_approval,

            'team_registration_price' => $this->team_registration_price,
            'individual_registration_price' => $this->individual_registration_price,
            
            'require_attendee_rsvp' => $this->require_attendee_rsvp,
            'grand_prize_total' => $this->grand_prize_total,
            
            'require_contestant_waiver' => $this->require_contestant_waiver,
            'require_attendee_waiver' => $this->require_attendee_waiver,

            'currency' => $this->currency,

            //Media
            'main_image' => !empty($this->main_image) ? Storage::disk('s3')->url($this->main_image) : null,
            'banner_image' => !empty($this->banner_image) ? Storage::disk('s3')->url($this->banner_image) : null,

            //Timestamp Info
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];

        return $data;
    }
}
