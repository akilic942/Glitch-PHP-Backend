<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserOneTimeTokenResource extends JsonResource
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
            'one_time_login_token' => $this->one_time_login_token,
            'one_time_login_token_date' => $this->one_time_login_token_date,
        ];


        return $data;
    }
}
