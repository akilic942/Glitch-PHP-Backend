<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    /**
     * @OA\Schema(
     *     schema="TeamUser",
     *     title="Team User Model",
     *     description="Manages how a user relates to a team.",
     *     @OA\Property(
     *          property="user_id",
     *          description="The user id associated with the team.",
     *          type="string",
     *          format="uuid"
     *     ),
     *       @OA\Property(
     *          property="team_id",
     *          description="The team id associated with the user.",
     *          type="string",
     *          format="uuid"
     *     ),
     *      @OA\Property(
     *          property="one_time_login_token",
     *          description="The token that must be passed back when logging in",
     *          type="string",
     *     ),
     *      @OA\Property(
     *          property="user_role",
     *          description="The The role of the current user.",
     *          type="integer"
     *      ),
     *       @OA\Property(
     *          property="status",
     *          description="The status of the user.",
     *          type="integer",
     *          enum={"1", "2", "3", "4", "5", "6", "7", "8", "9"}
     *      ),
     *       @OA\Property(
     *          property="waiver_signed",
     *          description="Indicates if the waiver for the user has been signed.",
     *          type="boolean",
     *      ),
     *      @OA\Property(
     *          property="is_competitor",
     *          description="Not everyone on a team has  to be competitor, (ie: business manager). This boolean will make the user as a competitor.",
     *          type="boolean",
     *      ),
     * )
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
