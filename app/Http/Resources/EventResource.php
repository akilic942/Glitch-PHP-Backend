<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    /**
     * Class Event.
     *
     * @OA\Schema(
     *     schema="Event",
     *     title="Events Model",
     *     description="The data associated with an event.",
     *     @OA\Property(
     *          property="id",
     *          description="The id of the event",
     *          type="string",
     *          format="uuid",
     *          readOnly=true,
     *     ),
     *     @OA\Property(
     *          property="title",
     *          description="The name of the event",
     *          type="string"
     *     ),
     *      @OA\Property(
     *          property="is_live",
     *          description="A boolean that indicates if the event is currently live.",
     *          type="boolean"
     *     ),
     *      @OA\Property(
     *          property="is_public",
     *          description="A boolean that indicates if the event is public or private.",
     *          type="boolean"
     *     ),
     *     @OA\Property(
     *          property="live_last_checkin",
     *          description="A date/time field that is set when the last is_live check was performed to determine if the event is live.",
     *          type="datetime"
     *     ),
     *      @OA\Property(
     *          property="mode",
     *          description="The mode of sharing the live stream (screenshare, rtmp, etc)",
     *          type="string",
     *          readOnly=true,
     *          enum={"0", "1", "2"}
     *     ),
     *     @OA\Property(
     *          property="admins",
     *          description="A list of users who are administrators for this event.",
     *          @OA\Items(ref="#/components/schemas/User"),
     *          type="array",
     *          readOnly=true,
     *     ),
     * 
     *      @OA\Property(
     *          property="created_at",
     *          description="The time the event was created at.",
     *          type="timestamp",
     *          readOnly=true,
     *     ),
     *      @OA\Property(
     *          property="updated_at",
     *          description="The timestamp the event was last updated.",
     *          type="string",
     *          readOnly=true,
     *     ),
     *      @OA\Property(
     *          property="invirtu_id",
     *          description="The id associated with the invirtu live event, that controls the streaming.",
     *          type="string",
     *          readOnly=true,
     *     ),
     *      @OA\Property(
     *          property="invirtu_webrtc_url",
     *          description="The URL for joining a WebRTC video chat session using for streaming the games.",
     *          type="string",
     *          readOnly=true,
     *     ),
     *      @OA\Property(
     *          property="invirtu_broadcast_url",
     *          description="The URL to send a broadcast too using Invirtu.",
     *          type="string",
     *          readOnly=true,
     *     ),
     *      @OA\Property(
     *          property="invirtu_rtmp_livestream_endpoint",
     *          description="The endpoint to send an RTMP stream too.",
     *          type="string",
     *          readOnly=true,
     *     ),
     *      @OA\Property(
     *          property="invirtu_rtmp_livestream_key",
     *          description="The stream key used for accessing the rtmp endpoint for the livestream.",
     *          type="string",
     *          readOnly=true,
     *     ),
     *      @OA\Property(
     *          property="invirtu_rtmp_broadcast_endpoint",
     *          description="The endpoint to send an RTMP broadcast too.",
     *          type="string",
     *          readOnly=true,
     *     ),
     *      @OA\Property(
     *          property="invirtu_rtmp_broadcast_key",
     *          description="The stream key used for accessing the rtmp endpoint for the broadcast.",
     *          type="string",
     *          readOnly=true,
     *     ),
     * 
     * )
     * 
     * 
     *
     * 
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'is_live' => $this->is_live,
            'is_public' => $this->is_public,
            'live_last_checkin' => $this->live_last_checkin,
            'mode' => $this->mode,

            'admins' => UserResource::collection($this->admins),

            //Timestamp Info
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,

            //Media
            'image_main' => !empty($this->image_main) ? Storage::disk('s3')->url($this->image_main) : null,
            'image_banner' => !empty($this->image_banner) ? Storage::disk('s3')->url($this->image_banner) : null,

            //Invirtu Info
            'invirtu_id' => $this->invirtu_id,
            'invirtu_webrtc_url' => $this->invirtu_webrtc_url,
            'invirtu_livestream_url' => $this->invirtu_livestream_url,
            'invirtu_broadcast_url' => $this->invirtu_broadcast_url,
            'invirtu_rtmp_livestream_endpoint' => $this->invirtu_rtmp_livestream_endpoint,
            'invirtu_rtmp_livestream_key' => $this->invirtu_rtmp_livestream_key,
            'invirtu_rtmp_broadcast_endpoint' => $this->invirtu_rtmp_broadcast_endpoint,
            'invirtu_rtmp_broadcast_key' => $this->invirtu_rtmp_broadcast_key,
        ];
    }
}
