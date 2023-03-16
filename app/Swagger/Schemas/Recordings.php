<?php
namespace App\Swagger\Schemas
;
/**
 *
 * @OA\Schema(
 *     schema="Recordings",
 *     title="Recordings of the live stream.",
 *     description="Recordings of the live stream.",
 *     @OA\Property(
 *          property="id",
 *          description="The id of the video content.",
 *          type="string",
 *          format="uuid",
 *          readOnly=true,
 *     ),
 *     @OA\Property(
 *          property="title",
 *          description="The the title of the content.",
 *          type="string"
 *     ),
 *      @OA\Property(
 *          property="description",
 *          description="The description of the content.",
 *          type="string"
 *     ),
 * 
 * )
 * 
 * 
 *
 * 
 */
class Recordings
{
}
