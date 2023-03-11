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
     *     schema="Competition",
     *     title="Competition Model",
     *     description="The model that describes a competition",
     *     @OA\Property(
     *          property="id",
     *          description="The id of the competition.",
     *          type="uuid"
     *     ),
     *     @OA\Property(
     *          property="name",
     *          description="The name of the competiton.",
     *          type="string"
     *     ),
     *    @OA\Property(
     *          property="start_date", 
     *          type="string", 
     *          format="date-time",
     *          description="The start date of the competition.",
     *      ),
     *     @OA\Property(
     *           property="end_date", 
     *           type="string", 
     *           format="date-time",
     *           description="The end date of the competition.",
     *       ),
     *     @OA\Property(
     *            property="status", 
     *            type="string", 
     *            enum={"open", "closed"},
     *            description="If the competition.",
     *      ),
     *     @OA\Property(
     *            property="image_url", 
     *            type="string",
     *            description="Read only file of the main image of the competition.",
     *      ),
     *    
     *     
     *      @OA\Property(
     *          property="allow_team_signup", 
     *          type="boolean",
     *          description="Will allow teams to sign-up to the competition.",

     *     ),
     *      @OA\Property(
     *          property="allow_individual_signup", 
     *          type="boolean",
     *          description="Will allow individuals to sign-up to the competition.",
     *     ),
     *      @OA\Property(
     *          property="require_attendee_rsvp", 
     *          type="boolean",
     *          description="Attendees will be required to RSVP to access competition and its events.",
     *     ),
     *      @OA\Property(
     *          property="is_private", 
     *          type="boolean",
     *          description="If the event is private and will not show on lists.",
     *     ),
     * 
     *      @OA\Property(
     *          property="competitors_per_match", 
     *          type="interger",
     *          description="The number of competitors to have in a single match. This value is used when matches are auto generated.",
     *     ),
     *      @OA\Property(
     *          property="winners_per_match", 
     *          type="interger",
     *          description="The number winners to allow per match when there is more than 2 competitors.",
     *     ),
     * 
     *      @OA\Property(
     *          property="max_registration_for_teams", 
     *          type="interger",
     *          description="The maximum number of teams that will be allowed to sign-up for the compeititon. Optional field, leave null or 0 for infinite.",
     *     ),
     *      @OA\Property(
     *          property="max_registration_for_users", 
     *          type="interger",
     *          description="The maximum number of individual users that will be allowed to sign-up for the compeititon. Optional field, leave null or 0 for infinite.",
     *     ),
     *       @OA\Property(
     *          property="minimum_team_size", 
     *          type="interger",
     *          description="The minimum amount of people required to be on a team to allow sign-up.",
     *      ),
     * 
     *      @OA\Property(
     *          property="checkin_enabled", 
     *          type="boolean",
     *          description="Enable check-in that will have users check-in to the compeitition.",
     *      ),
     *      @OA\Property(
     *          property="checkin_mintues_prior", 
     *          type="integer",
     *          description="The number of minutes prior to the competition that the user must check-in.",
     *      ),
     * 
     *      @OA\Property(
     *          property="team_signup_requires_approval", 
     *          type="boolean",
     *          description="Once a team has signed up, this will require an administrator to approve them before they can be considered registered.",
     *      ),
     *      @OA\Property(
     *          property="individual_signup_requires_approval", 
     *          type="boolean",
     *          description="Once an individual has signed up, this will require an administrator to approve them before they can be considered registered.",
     *      ),
     * 
     *      @OA\Property(
     *          property="team_registration_price", 
     *          type="float",
     *          description="The cost associated when registering a team.",
     *      ),
     *      @OA\Property(
     *          property="individual_registration_price", 
     *          type="float",
     *          description="The cost associated when registering a individual user.",
     *      ),
     *      @OA\Property(
     *          property="grand_prize_total", 
     *          type="float",
     *          description="The total amount the grand prize is worth.",
     *      ),
     *       @OA\Property(
     *          property="currency", 
     *          type="string",
     *          description="The currecny for the tournmanet.",
     *      ),
     * 
     *      @OA\Property(
     *          property="require_contestant_waiver", 
     *          type="boolean",
     *          description="Will require constentants to sign a waiver before registering.",
     *      ),
     *      @OA\Property(
     *          property="require_attendee_waiver", 
     *          type="boolean",
     *          description="Will require attendees to sign a waiver before registering.",
     *      ),
     * 
     *       @OA\Property(
     *          property="rules", 
     *          type="string",
     *          description="Enter all the rules for competition.",
     *      ),
     *       @OA\Property(
     *          property="agreement", 
     *          type="string",
     *          description="Enter the agreement a user must agree to for entering the competition.",
     *      ),
     *      @OA\Property(
     *          property="schedule", 
     *          type="string",
     *          description="Enter the schedule for the competition.",
     *      ),
     *      @OA\Property(
     *          property="disqualifiers", 
     *          type="string",
     *          description="Set any disqualifers for the competition.",
     *      ),
     *       @OA\Property(
     *          property="refund_policy", 
     *          type="string",
     *          description="Set the refund policy for the competition.",
     *      ),
     *      @OA\Property(
     *          property="harassment_policy", 
     *          type="string",
     *          description="Set an harassment policy for the competition.",
     *      ),
     *      @OA\Property(
     *          property="saftey_policy", 
     *          type="string",
     *          description="Set a safety policy for the competition.",
     *      ),
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
            'paetron_page' => $this->paetron_page,
            'github_page' => $this->github_page,

            //Social Handles
            'twitter_handle' => $this->twitter_handle,
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
