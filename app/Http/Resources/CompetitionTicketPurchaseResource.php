<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompetitionTicketPurchaseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    /**
     * 
     *
     * @OA\Schema(
     *     schema="CompetitionTicketPurchase",
     *     title="Competition Ticket Purchase Model",
     *     description="Every time a ticket is purchased or rsvped to an event, it is recorded in this model.",
     *     @OA\Property(
     *          property="id",
     *          description="The id of the ticket purchase.",
     *          type="string",
     *          format="uuid"
     *     ),
     *      @OA\Property(
     *          property="cteam_id",
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
