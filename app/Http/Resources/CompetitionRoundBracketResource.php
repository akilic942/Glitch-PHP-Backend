<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompetitionRoundBracketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    /**
     * @OA\Schema(
     *     schema="CompetitionRoundBracket",
     *     title="Competition Round Bracket Model",
     *     description="The mode of how a bracket is defined that is inside a round.",
     *     @OA\Property(
     *          property="id",
     *          description="The id of the bracket for the round.",
     *          type="string",
     *          format="uuid"
     *     ),
     *      @OA\Property(
     *          property="competition_id",
     *          description="The id of the competition the bracket belongs too.",
     *          type="string",
     *          format="uuid",
     *      ),
     *      @OA\Property(
     *          property="round",
     *          description="The round the bracket is associate with.",
     *          type="integer",
     *      ),
     *      @OA\Property(
     *          property="bracket",
     *          description="The current bracket.",
     *          type="integer",
     *      ),
     *      @OA\Property(
     *          property="user_id",
     *          description="The id of the user for this bracket/round.",
     *          type="string",
     *          format="uuid",
     *      ),
     *      @OA\Property(
     *          property="team_id",
     *          description="The id of the team for this bracket/round.",
     *          type="string",
     *          format="uuid",
     *      ),
     *      @OA\Property(
     *          property="event_id",
     *          description="The id of the event the event is associated with.",
     *          type="string",
     *          format="uuid",
     *      ),
     *      @OA\Property(
     *          property="is_winner",
     *          description="Sets true if the current user is the winner.",
     *          type="boolean",
     *      ),
     *      @OA\Property(
     *          property="is_finished",
     *          description="Sets true if the match is finished.",
     *          type="boolean",
     *      ),
     *       @OA\Property(
     *          property="checked_in",
     *          description="Sets true if the user has checked in.",
     *          type="boolean",
     *      ),
     *       @OA\Property(
     *          property="bracket_start_date",
     *          description="The start date for the bracket to begin.",
     *          type="date-time",
     *      ),
     *       @OA\Property(
     *          property="bracket_end_date",
     *          description="The end date for the bracket to end.",
     *          type="date-time",
     *      ),
     *       @OA\Property(
     *          property="cash_awarded",
     *          description="The amount of cash awarded to the winner.",
     *          type="float",
     *      ),
     *      @OA\Property(
     *          property="points_awarded",
     *          description="The amount of points awarded to the winner.",
     *          type="float",
     *      ),
     *      @OA\Property(
     *              property="user",
     *              description="The user associated with this bracket/round.",
     *              ref="#/components/schemas/User",
     *              type="object"
     *       ),
     *       @OA\Property(
     *              property="team",
     *              description="The team associated with this bracket/round.",
     *              ref="#/components/schemas/User",
     *              type="object"
     *       ),
     *        @OA\Property(
     *          property="created_at",
     *          description="The date the bracket/round  was created.",
     *          type="string"
     *     ),
     *     @OA\Property(
     *          property="updated_at",
     *          description="The date the bracket/round was last updated.",
     *          type="string"
     *     ),
     * )
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'competition_id' => $this->competition_id,
            'round' => $this->round,
            'bracket' => $this->bracket,
            'user_id' => $this->user_id,
            'team_id' => $this->team_id,
            'event_id' => $this->event_id,
            'is_winner' => $this->is_winner,
            'is_finished' => $this->is_finished,
            'bracket_start_date' => $this->bracket_start_date,
            'bracket_end_date' => $this->bracket_end_date,
            'checked_in' => $this->checked_in,
            'cash_awarded' => $this->cash_awarded,
            'points_awarded' => $this->points_awarded,

            'user' => UserResource::make($this->user),
            'team' => TeamResource::make($this->team),

            //Timestamp Info
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];

        return $data;
    }
}
