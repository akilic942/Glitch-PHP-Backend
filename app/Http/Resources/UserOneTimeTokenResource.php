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

    /**
     * @OA\Schema(
     *     schema="UserOneTimeToken",
     *     title="User One Time Token Model",
     *     description="The one time token is a special token used for logging-in.",
     *     @OA\Property(
     *          property="id",
     *          description="The id of the one time login request.",
     *          type="string",
     *          format="uuid"
     *     ),
     *      @OA\Property(
     *          property="one_time_login_token",
     *          description="The token that must be passed back when logging in",
     *          type="string",
     *     ),
     *      @OA\Property(
     *          property="one_time_login_token_date",
     *          description="The date in which the token was created.",
     *          type="date-time"
     *      ),
     * )
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
