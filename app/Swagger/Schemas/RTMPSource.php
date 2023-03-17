<?php
namespace App\Swagger\Schemas
;
/**
 *
 * @OA\Schema(
 *     schema="RTMPSource",
 *     title="The RTMP Source for adding and removing.",
 *     description="The RTMP Source for adding and removing.",
 *     @OA\Property(
 *          property="id",
 *          description="The id of the RTMP",
 *          type="string",
 *          format="uuid",
 *          readOnly=true,
 *     ),
 *     @OA\Property(
 *          property="stream_url",
 *          description="The RTMP endpoint to send the stream too.",
 *          type="string"
 *     ),
 *      @OA\Property(
 *          property="label",
 *          description="An optional label or name used to identify the stream.",
 *          type="string"
 *     ),
 *      @OA\Property(
 *          property="fps",
 *          description="The Frames Per Second(FPS) to play the stream. The maximum value is 120.",
 *          type="integer"
 *     ),
 *     @OA\Property(
 *          property="bitrate",
 *          description="The bitrate for the stream.",
 *          type="integer"
 *     ),
 *     @OA\Property(
 *          property="max_bitrate",
 *          description="The max bitrate for the stream. The maximum value is 13000.",
 *          type="integer"
 *     ),
 *      @OA\Property(
 *          property="gop",
 *          description="The Group of Pictures (gop) for the stream. The maximum value is 260.",
 *          type="integer"
 *     ),
 *      @OA\Property(
 *          property="date_added",
 *          description="The date/time that the RTMP endpoint was added.",
 *          type="datetime",
 *          readOnly=true,
 *     ),
 * 
 * )
 * 
 * 
 *
 * 
 */
class RTMPSource
{
}
