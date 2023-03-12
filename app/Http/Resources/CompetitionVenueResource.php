<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CompetitionVenueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    /**
     * @OA\Schema(
     *     schema="CompetitionVenue",
     *     title="Competition Venue Model",
     *     description="Setup competitions to take place in venues.",
     *     required={"venue_name", "is_virtual_hybrid_remote", "competition_id"},
     *     @OA\Property(
     *          property="id",
     *          description="The id of the venue",
     *          type="string",
     *          format="uuid"
     *     ),
     *      @OA\Property(
     *          property="venue_name",
     *          description="The name of the venue.",
     *          type="string",
     *          maxLength=255
     *     ),
     *      @OA\Property(
     *          property="address_line_1",
     *          description="The first address line of the venue.",
     *          type="string",
     *          maxLength=255
     *     ),
     *      @OA\Property(
     *          property="address_line_2",
     *          description="The second address line of the venue.",
     *          type="string",
     *          maxLength=255
     *     ),
     *      @OA\Property(
     *          property="postal_code",
     *          description="The postol/zipcode of the venue.",
     *          type="string",
     *          maxLength=255
     *     ),
     *      @OA\Property(
     *          property="locality",
     *          description="The locality of venue (ie: city).",
     *          type="string",
     *          maxLength=255
     *     ),
     *       @OA\Property(
     *          property="province",
     *          description="The province of venue (ie: state).",
     *          type="string",
     *          maxLength=255
     *     ),
     *      @OA\Property(
     *          property="country",
     *          description="The country of the venue.",
     *          type="string",
     *          maxLength=255
     *     ),
     *      @OA\Property(
     *          property="is_virtual_hybrid_remote",
     *          description="Select if the venue is online, physical or hybrid - both.",
     *          type="integer",
     *          enum={"1", "2", "3"}
     *     ),
     *      @OA\Property(
     *          property="competition_id",
     *          description="The id of the competition the venue is associated with.",
     *          type="string",
     *          format="uuid"
     *     ),
     *      @OA\Property(
     *          property="venue_direction_instructions",
     *          description="Set instructions on how to reach the venue.",
     *          type="string",
     *     ),
     *      @OA\Property(
     *          property="venue_access_instructions",
     *          description="Set instructions on how to access the venue.",
     *          type="string",
     *     ),
     *       @OA\Property(
     *          property="additional_notes",
     *          description="Add any additional information about the venue.",
     *          type="string",
     *     ),
     *    
     *      @OA\Property(
     *          property="created_at",
     *          description="The time the event was created at.",
     *          type="timestamp"
     *     ),
     *      @OA\Property(
     *          property="updated_at",
     *          description="The timestamp the event was last updated.",
     *          type="string"
     *     ),
     * 
     * )
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);

        $data['venue_image'] = !empty($this->venue_image) ? Storage::disk('s3')->url($this->venue_image) : null;

        return $data;
    }
}
