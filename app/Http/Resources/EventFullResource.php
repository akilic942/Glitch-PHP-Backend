<?php

namespace App\Http\Resources;

use App\Invirtu\InvirtuClient;
use Illuminate\Http\Resources\Json\JsonResource;

class EventFullResource extends JsonResource
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

        $organizer_token = env('INVIRTU_ORGANIZER_TOKEN', '');

        //This should be set run async
        if($organizer_token &&  $this->invirtu_id){
            $client = new InvirtuClient($organizer_token);

            //Get full event info
            $result = $client->events->get($this->invirtu_id);

            $data['recordings'] = (array)$result->data->pre_recorded_contents;

            //Get Restreams
            $result = $client->events->getRestreams($this->invirtu_id);

            $data['restreams'] = (array) $result->data;
           
        }


        return $data;


    }
}
