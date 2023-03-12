<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompetitionRoundResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

     /**
     * @OA\Schema(
     *     schema="CompetitionRound",
     *     title="Competition Round Model",
     *     description="The model that relates to how a round is associated with competition.",
     *     @OA\Property(
     *          property="competition_id",
     *          description="The id of the competition the round belongs too.",
     *          type="string",
     *          format="uuid"
     *     ),
     *      @OA\Property(
     *          property="round",
     *          description="The round in the current competition",
     *          type="integer"
     *      ),
     *       @OA\Property(
     *          property="title",
     *          description="The title given to then round.",
     *          type="string"
     *      ),
     *       @OA\Property(
     *          property="overview",
     *          description="The overview description of the round.",
     *          type="string"
     *      ),
     *       @OA\Property(
     *          property="round_start_date",
     *          description="The start date of the round.",
     *          type="date-time"
     *      ),
     *       @OA\Property(
     *          property="round_end_date",
     *          description="The end date of the round.",
     *          type="date-time"
     *      ),
     *       @OA\Property(
     *          property="checkin_enabled",
     *          description="Set if check-in is enabled for this round.",
     *          type="boolean"
     *      ),
     *       @OA\Property(
     *          property="checkin_mintues_prior",
     *          description="The number of minutes check-in is required before the round begins.",
     *          type="integer"
     *      ),
     *       @OA\Property(
     *          property="elimination_type",
     *          description="The elimination type for this round.",
     *          type="integer",
     *          enum={"1", "2", "3", "4", "5", "6", "7", "8", "9"}
     *      ),
     *       @OA\Property(
     *          property="venue_id",
     *          description="The id of the venue the round is associated with.",
     *          type="string",
     *          format="uuid"
     *      ),
     *      @OA\Property(
     *          property="brackets",
     *          description="The brackets associated with the round.",
     *          @OA\Items(ref="#/components/schemas/CompetitionRoundBracket"),
     *          type="array"
     *       ),
     * )
     */
    public function toArray($request)
    {

        $data = [
            'competition_id' => $this->competition_id,
            'round' => $this->round,
            'title' => $this->title,
            'overview' => $this->overview,
            'round_start_date' => $this->round_start_date,
            'round_end_date' => $this->round_end_date,
            'checkin_enabled' => $this->checkin_enabled,
            'checkin_mintues_prior' => $this->checkin_mintues_prior,
            'elimination_type' => $this->elimination_type,
            'venue_id' => $this->venue_id,

            'brackets' => CompetitionRoundBracketResource::collection($this->brackets),

            //Timestamp Info
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];

        return $data;
    }
}
