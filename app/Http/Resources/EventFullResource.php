<?php

namespace App\Http\Resources;

use App\Facades\PermissionsFacade;
use App\Invirtu\InvirtuClient;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'is_live' => $this->is_live,
            'live_last_checkin' => $this->live_last_checkin,
            'mode' => $this->mode,

            'admins' => UserResource::collection($this->admins),
            'moderators' => UserResource::collection($this->moderators),
            'speakers' => UserResource::collection($this->cohosts),

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

        $loggedin_user = Auth::user();

        //if($loggedin_user && PermissionsFacade::eventCanUpdate($this, $loggedin_user)) {
            //Should only show for admins of the event
            $data['invites'] = $this->invites;
        //}

        $organizer_token = env('INVIRTU_ORGANIZER_TOKEN', '');

        //This should be set run async
        if($organizer_token &&  $this->invirtu_id){
            $client = new InvirtuClient($organizer_token);

            //Get full event info
            $result = $client->events->get($this->invirtu_id);

            $data['invirtu_event'] = (array) $result->data;

            $data['recordings'] = (array)$result->data->pre_recorded_contents;

            //Get Restreams
            $result = $client->events->getRestreams($this->invirtu_id);

            $data['restreams'] = (array) $result->data;
           
        }


        return $data;


    }
}
