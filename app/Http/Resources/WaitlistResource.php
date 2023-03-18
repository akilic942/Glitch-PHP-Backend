<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WaitlistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    /**
     * @OA\Schema(
     *     schema="Waitlist",
     *     title="Waitlist Model",
     *     description="The waitlist model is for when websites have a website to attract new customers/users.",
     *     required={"email"},
     *     @OA\Property(
     *          property="id",
     *          description="The UUID of the waitlist entry.",
     *          type="string",
     *          format="uuid",
     *          readOnly=true,
     *     ),
     *       @OA\Property(
     *          property="email",
     *          description="The email address of the user on the waitlist.",
     *          type="string",
     *          format="email"
     *     ),
     *      @OA\Property(
     *          property="first_name",
     *          description="The first name of the user signing up to the wait list.",
     *          type="string",
     *     ),
     *      @OA\Property(
     *          property="last_name",
     *          description="The last name of the user signing up to the wait list.",
     *          type="string",
     *     ),
     *      @OA\Property(
     *          property="full_name",
     *          description="The full name of the user signing up to the wait list.",
     *          type="string",
     *     ),
     *      @OA\Property(
     *          property="display_name",
     *          description="The display name of the user signing up to the wait list.",
     *          type="string",
     *     ),
     *      @OA\Property(
     *          property="username",
     *          description="The user name of the user signing up to the wait list.",
     *          type="string",
     *     ),
     *      @OA\Property(
     *          property="phone_number",
     *          description="The phone number associated with the user signing up.",
     *          type="string",
     *     ),
     *      @OA\Property(
     *          property="website",
     *          description="The website associated with the user signing up.",
     *          type="string",
     *     ),
     *      @OA\Property(
     *          property="title",
     *          description="The job title associated with the user signing up.",
     *          type="string",
     *     ),
     *      @OA\Property(
     *          property="company",
     *          description="The company associated with the user signing up.",
     *          type="string",
     *     ),
     *      @OA\Property(
    *         property="meta",
    *         type="object",
    *         description="A JSON object with dynamic fields",
    *         additionalProperties=true,
    *     )
     *     
     * )
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
