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
     *     description="Keep track of users when that have been invited to a competition.",
     *     required={"name", "email", "competition_id"},
     *     @OA\Property(
     *          property="id",
     *          description="The uuid of invite.",
     *          type="string",
     *          format="uuid",
     *          readOnly=true,
     *     ),
     *     @OA\Property(
     *          property="email",
     *          description="The email address of the person that is invited.",
     *          type="string",
     *          maxLength=255,
     *     ),
     *      @OA\Property(
     *          property="name",
     *          description="The name of the person that is invited.",
     *          type="string",
     *          maxLength=255,
     *     ),
     *       @OA\Property(
     *          property="competition_id",
     *          description="The id of the competition that the invite belongs too.",
     *          type="string",
     *          format="uuid",
     *          readOnly=true,
     *     ),
     *       @OA\Property(
     *          property="invited_as_participant",
     *          description="Sets if the user is to be invited as an individual participant.",
     *          type="boolean"
     *     ),
     *      @OA\Property(
     *          property="invited_as_team_member",
     *          description="Sets if the user is to be invited as part of a team.",
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
     *          maxLength=255,
     *          readOnly=true,
     *     ),
     *      @OA\Property(
     *          property="role",
     *          description="The role the user is being invited as.",
     *          type="integer",
     *          enum={"0", "1", "2"},
     *     ),
     * )
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
