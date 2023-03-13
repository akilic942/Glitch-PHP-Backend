<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompetitionTeamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

     /**
     * Class CompetitionteamResource for CompetitionTeamController.
     *
     * @OA\Schema(
     *     schema="CompetitionTeam",
     *     title="Competition Team Model",
     *     description="Controls how a team is associated with a competition.",
     *     @OA\Property(
     *          property="competition_id",
     *          description="The id of the competition the team belongs too.",
     *          type="string",
     *          format="uuid",
     *          readOnly=true,
     *     ),
     *      @OA\Property(
     *          property="team_id",
     *          description="The id of the team associated with the relationship.",
     *          type="string",
     *          format="uuid"
     *     ),
     *      @OA\Property(
     *          property="checked_in",
     *          description="If the team has checked-in.",
     *          type="boolean"
     *      ),
     *       @OA\Property(
     *          property="waiver_signed",
     *          description="If the waiver has been signed for the team.",
     *          type="boolean"
     *      ),
     *       @OA\Property(
     *          property="entry_fee_paid",
     *          description="If the waiver has been signed for the team.",
     *          type="boolean"
     *      ),
     *       @OA\Property(
     *          property="status",
     *          description="The current statues of the team in the competition.",
     *          type="integer",
     *          enum={"1", "2", "3", "4", "5", "6", "7", "8", "9"}
     *      ),
     * )
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
