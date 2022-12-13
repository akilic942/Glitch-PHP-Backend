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
