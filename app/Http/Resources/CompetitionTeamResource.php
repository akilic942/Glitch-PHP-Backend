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
     *     schema="CompetitionTeamController",
     *     title="Competition Team Controller Model",
     *     description="Competition team controller model",
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
        return parent::toArray($request);
    }
}
