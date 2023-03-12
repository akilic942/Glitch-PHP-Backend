<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class EventOverlayResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    /**
     * @OA\Schema(
     *     schema="EventOverlay",
     *     title="Event Overlay Model",
     *     description="Manage the overlays that appear over screens at events.",
     *     @OA\Property(
     *          property="id",
     *          description="The id of overlay.",
     *          type="string",
     *          format="uuid"
     *     ),
     *      @OA\Property(
     *          property="event_id",
     *          description="The event id the overlay is associated with.",
     *          type="string",
     *          format="uuid"
     *     ),
     *      @OA\Property(
     *          property="image_url",
     *          description="The url of where the overlay resides",
     *          type="string",
     *     ),
     *      @OA\Property(
     *          property="is_active",
     *          description="If the overlay is active or not.",
     *          type="boolean"
     *      ),
     * )
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'event_id' => $this->event_id,
            'label' => $this->label,
            'image_url' => !empty($this->image_url) ? Storage::disk('s3')->url($this->image_url) : null,
            'is_active' => $this->is_active
        ];
    }
}
