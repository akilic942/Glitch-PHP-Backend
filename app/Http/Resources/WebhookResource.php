<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WebhookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    /**
     * @OA\Schema(
     *     schema="Webhook",
     *     title="Webhook Model Model",
     *     description="The model for processing webhooks.",
     *     required={"incoming_outgoing"},
     *     @OA\Property(
     *          property="id",
     *          description="The UUID of the webhook.",
     *          type="string",
     *          format="uuid",
     *          readOnly=true,
     *     ),
     *       @OA\Property(
     *          property="incoming_outgoing",
     *          description="Whether the webhook is listening to an incoming request or sending an outgoing request.",
     *          type="integer",
     *     ),
     *      @OA\Property(
     *          property="action",
     *          description="The action for the webhook.",
     *          type="string",
     *     ),
     *      @OA\Property(
     *          property="url",
     *          description="The url the webhook will send information too.",
     *          type="string",
     *          format="uri",
     *     ),
     *      @OA\Property(
     *          property="request_method",
     *          description="GET, POST, PUT, DELETE method that webhook will use to send requests.",
     *          type="string",
     *          enum = {"GET", "POST", "PUT", "DELETE"}
     *     ),
     *      @OA\Property(
     *          property="signature_key",
     *          description="A key for securely prcessing the request.",
     *          type="string",
     *     ),
     *      @OA\Property(
     *          property="signature_value",
     *          description="A value that is associated witht he signature key to securely processing the request.",
     *          type="string",
     *     ),
     *      @OA\Property(
     *          property="processed",
     *          description="A boolean that indicates if the webhook has been sent or recieved.",
     *          type="boolean",
     *     ),
     *      @OA\Property(
    *         property="data",
    *         type="object",
    *         description="The information assoicated with the request",
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
