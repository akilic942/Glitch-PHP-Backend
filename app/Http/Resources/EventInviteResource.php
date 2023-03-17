<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventInviteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

     /**
     * @OA\Schema(
     *     schema="EventInvite",
     *     title="Event Invite Model",
     *     description="Manages how a user is invited to an event.",
     *     @OA\Property(
     *          property="id",
     *          description="The uuid of invite.",
     *          type="string",
     *          format="uuid",
     *          readOnly=true
     *     ),
     *     @OA\Property(
     *          property="email",
     *          description="The email address of the person that is invited.",
     *          type="string"
     *     ),
     *      @OA\Property(
     *          property="name",
     *          description="The name of the person that is invited.",
     *          type="string"
     *     ),
     *       @OA\Property(
     *          property="event_id",
     *          description="The id of the team that the invite belongs too.",
     *          type="string",
     *          format="uuid",
     *          readOnly=true
     *     ),
     *      @OA\Property(
     *          property="invited_as_cohost",
     *          description="Sets if the user is to be invited as co-host to the event where they can join chat with the host.",
     *          type="boolean"
     *     ),
     *      @OA\Property(
     *          property="accepted_invite",
     *          description="Is true of the user has accepted the invite.",
     *          type="boolean"
     *     ),
     *       @OA\Property(
     *          property="token",
     *          description="The auto-generated token that is sent along with the invite.",
     *          type="string",
     *          readOnly=true
     *     ),
     * )
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
