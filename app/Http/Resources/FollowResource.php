<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FollowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    /**
     * Class Event.
     *
     * @OA\Schema(
     *     schema="Follow",
     *     title="Followers Relationship Model",
     *     description="The data that descibes the relationing of follower/following. Think of it as subscibing to another user.",
     *     @OA\Property(
     *          property="follower",
     *          description="The user who is the follower.",
     *          ref="#/components/schemas/User",
     *          type="object"
     *     ),
     *     @OA\Property(
     *          property="following",
     *          description="The user who is doing the following/subscribing.",
     *          ref="#/components/schemas/User",
     *          type="object"
     *     ),
     *    
     *      @OA\Property(
     *          property="created_at",
     *          description="The time the event was created at.",
     *          type="timestamp"
     *     ),
     *      @OA\Property(
     *          property="updated_at",
     *          description="The timestamp the event was last updated.",
     *          type="string"
     *     ),
     * 
     * )
     * 
     * 
     *
     * 
     */
    public function toArray($request)
    {
        return [
            'follower' => !empty($this->follower_id) ? $this->follower : null,
            'following' => !empty($this->following_id) ? $this->following : null,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
        ];
    }
}
