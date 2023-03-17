<?php
namespace App\Swagger\Schemas;

/**
 *
 * @OA\Schema(
 *     schema="InvirtuEvent",
 *     title="Live event event information that is associated with the invirtu API.",
 *     description="Live event event information that is associated with the invirtu API.",
 *     @OA\Property(
 *          property="id",
 *          description="The id of the live event",
 *          type="string",
 *          format="uuid",
 *          readOnly=true,
 *     ),
 *     @OA\Property(
 *          property="title",
 *          description="The title of the live event.",
 *          type="string"
 *     ),
 *      @OA\Property(
 *          property="slug",
 *          description="A unique slug for this live event.",
 *          type="string",
 *          readOnly=true
 *     ),
 *      @OA\Property(
 *          property="date",
 *          description="The date & time of the live event.",
 *          type="datetime"
 *     ),
 *      @OA\Property(
 *          property="timezone",
 *          description="The timezone of the live event.",
 *          type="string"
 *     ),
 *      @OA\Property(
 *          property="description",
 *          description="A description of the live event.",
 *          type="string"
 *     ),
 *      @OA\Property(
 *          property="event_started",
 *          description="Records if the live event has started. When the video chat has actived or a video stream is started after the live event date, the live event wil count as started.",
 *          type="boolean"
 *     ),
 *     @OA\Property(
 *          property="price",
 *          description="The ticket price of the live event.",
 *          type="float"
 *     ),
 * 
 * )
 * 
 * 
 *
 * 
 */
class InvirtuEvent
{
}
