<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TeamFullResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */


    /**
     * @OA\Schema(
     *     schema="TeamFull",
     *     title="Team Extended Model",
     *     description="The model that describes the attributes for a team with sub attirbutes.",
     *     allOf={
     *         
     *         @OA\Schema(
     *            @OA\Property(
     *              property="members",
     *              description="Users that are members of the team.",
     *              @OA\Items(ref="#/components/schemas/TeamUser"),
     *              type="array"
     *             
     *            ),
     *          @OA\Property(
     *              property="competitions",
     *              description="A list of competitions the team is associated with.",
     *              @OA\Items(ref="#/components/schemas/Competition"),
     *              type="array"
     *             
     *            ),
     *             
     *        ),
     *         @OA\Schema(ref="#/components/schemas/Team"),
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

        $data['members'] = UserResource::collection($this->users);

        $data['competitions'] = CompetitionResource::collection($this->competitions);

        return $data;
    }
}
