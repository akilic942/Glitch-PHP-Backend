<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TeamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */


    /**
     * Class User.
     *
     * @OA\Schema(
     *     schema="Team",
     *     title="Team Model",
     *     description="The model that describes the attributes for a team.",
     *     allOf={
     *         
     *         @OA\Schema(
     *            @OA\Property(
     *              property="followers",
     *              description="A list of users who are following the current user.",
     *              @OA\Items(ref="#/components/schemas/User"),
     *              type="array"
     *          ),
        *          @OA\Property(
        *              property="name",
        *              description="The name of the team.",
        *              type="string"
        *          ),
        *          @OA\Property(
        *              property="description",
        *              description="A description of the team",
        *              type="string"
        *          ),
        *          @OA\Property(
        *              property="main_image",
        *              description="The main image for the team. Is a read-only field.",
        *              type="string"
        *          ),
        *          @OA\Property(
        *              property="banner_image",
        *              description="The banner image for the team. Is a read-only field.",
        *              type="string"
        *          ),
        *          @OA\Property(
        *              property="logo",
        *              description="The logo image for the team. Is a read-only field.",
        *              type="string"
        *          ),
        *          @OA\Property(
        *              property="contact_name",
        *              description="The name of the contact person to contact about the team.",
        *              type="string"
        *          ),
        *          @OA\Property(
        *              property="contact_email",
        *              description="The contact email to send inquires too about the team.",
        *              type="string"
        *          ),
        *          @OA\Property(
        *              property="contact_phone_number",
        *              description="The phone number to call for inquires about the team.",
        *              type="string"
        *          ),
        *          @OA\Property(
        *              property="website",
        *              description="The phone number to call for inquires about the team.",
        *              type="string"
        *          ),
        *          @OA\Property(
        *              property="join_process",
        *              description="The process for another user joing a team.",
        *              type="string"
        *          ),
     *             
     *        ),
     *         @OA\Schema(ref="#/components/schemas/BaseSocialFields"),
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
        $data = parent::toArray($request);

        $data['main_image'] = !empty($this->main_image) ? Storage::disk('s3')->url($this->main_image) : null;
        $data['banner_image'] = !empty($this->banner_image) ? Storage::disk('s3')->url($this->banner_image) : null;

        return $data;
    }
}
