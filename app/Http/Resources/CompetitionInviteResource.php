<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompetitionInviteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

     /**
     * Class CompetitionInviteResource for CompetitionInviteController.
     *
     * @OA\Schema(
     *     schema="CompetitionInvite",
     *     title="Competition Invite Model",
     *     description="Competition Invite Model",
     *     @OA\Property(
     *          property="id",
     *          title="id",
     *          type="string"
     *     )
     * )
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
