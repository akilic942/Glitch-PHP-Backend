<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'tmp' => $this->original_image_url,
            'original_image_url' =>!empty($this->original_image_url) ? Storage::disk('s3')->url($this->original_image_url) : '',
            'large_image' => !empty($this->large_image) ? Storage::disk('s3')->url($this->large_image) : '',
            'large_square_image' => !empty($this->large_square_image) ? Storage::disk('s3')->url($this->large_square_image) : '',
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
          ];
    }
}
