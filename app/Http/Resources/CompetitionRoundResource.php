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
     * Class CompetitionRoundResource for CompetitionRoundController.
     *
     * @OA\Schema(
     *     schema="CompetitionRoundController",
     *     title="Competition Round Controller Model",
     *     description="Competition round controller model",
     *     @OA\Property(
     *          property="id",
     *          title="id",
     *          type="string"
     *     ),
     *      @OA\Property(
     *          property="subid",
     *          title="subid",
     *          type="string"
     *      )
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
