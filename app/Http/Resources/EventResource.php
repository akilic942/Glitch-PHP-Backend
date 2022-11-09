<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,

            //Timestamp Info
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,

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
