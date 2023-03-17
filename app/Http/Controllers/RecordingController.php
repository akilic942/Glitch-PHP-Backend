<?php

namespace App\Http\Controllers;

use App\Facades\PermissionsFacade;
use App\Facades\RecordingsFacade;
use App\Http\Resources\EventFullResource;
use App\Models\Event;
use App\Models\Recording;
use Illuminate\Http\Request;

class RecordingController extends Controller
{


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Recording  $recording
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Put(
     *     path="events/{uuid}/recording/{subid}",
     *     summary="Update a recording related to the event.",
     *     description="Update a recording related to the event.",
     *     operationId="updateEventRecording",
     *     tags={"Event Route"},
     *     security={{"bearer": {}}},
     *      @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the event",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *      @OA\Parameter(
     *         name="subid",
     *         in="path",
     *         description="UUID of the recording",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent( ref="#/components/schemas/Recordings")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *          @OA\JsonContent( ref="#/components/schemas/EventFull")
     *     ),
     *     @OA\Response(
     *      response=403,
     *      description="Access denied to event.",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", type="Access denined to the event resource.")
     *       )
     *     ) 
     * )
     *     
     */
    public function update(Request $request, $id, $subid)
    {
        $event = Event::where('id', $id)->first();

        // check if currently authenticated user is the owner of the book
        if(!PermissionsFacade::eventCanUpdate($event, $request->user())){
           return response()->json(['error' => 'Permission Denied.'], 403);
        }

        $input = $request->all();

        if(!$subid) {
            return response()->json(['error' => 'The ID of the recording you want to update is required.'], 403);
        }

        RecordingsFacade::updateRecording($subid, $input);

        return new EventFullResource($event);
    }

}
