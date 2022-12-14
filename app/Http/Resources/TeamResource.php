<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TeamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);

        $data['main_image'] = !empty($this->main_image) ? Storage::disk('s3')->url($this->main_image) : null;
        $data['banner_image'] = !empty($this->banner_image) ? Storage::disk('s3')->url($this->banner_image) : null;

        return $data;
    }
}
