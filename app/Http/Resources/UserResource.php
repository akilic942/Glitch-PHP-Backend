<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $data = [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'username' => $this->username,
            'display_name' => $this->display_name,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'bio' => $this->bio,
            'token' => ($this->token) ?: null,
        ];

        return $data;
    }
}
