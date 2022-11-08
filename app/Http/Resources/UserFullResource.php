<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserFullResource extends JsonResource
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
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'email' => $this->email,
            'username' => $this->username,
            'bio' => $this->bio,
            'is_super_admin' => $this->is_super_admin,
            'token' => ($this->token) ?: null,
            'invirtu_user_id' => $this->email,
            'invirtu_user_jwt_token' => $this->invirtu_user_jwt_token,

        ];


        return $data;
    }
}
