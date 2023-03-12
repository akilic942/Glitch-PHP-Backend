<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompetitionUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    /**
     * @OA\Schema(
     *     schema="CompetitionUser",
     *     title="Competition User Model",
     *     description="Manage a user relationship with a competition.",
     *     required={"competition_id", "user_id"},
     *     @OA\Property(
     *          property="competition_id",
     *          description="The id of the competition.",
     *          type="string",
     *          format="uuid"
     *     ),
     *      @OA\Property(
     *          property="user_id",
     *          description="The id of the user associated with the competition.",
     *          type="string",
     *          format="uuid"
     *     ),
     *      @OA\Property(
     *          property="checked_in",
     *          description="If the user has checked-in.",
     *          type="boolean"
     *      ),
     *       @OA\Property(
     *          property="waiver_signed",
     *          description="If the waiver has been signed for the user.",
     *          type="boolean"
     *      ),
     *       @OA\Property(
     *          property="entry_fee_paid",
     *          description="If the waiver has been signed for the user.",
     *          type="boolean"
     *      ),
     *       @OA\Property(
     *          property="status",
     *          description="The current status of the user in the competition.",
     *          type="integer",
     *          enum={"1", "2", "3", "4", "5", "6", "7", "8", "9"}
     *      ),
     * 
     * )
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
