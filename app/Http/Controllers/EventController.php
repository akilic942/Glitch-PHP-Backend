<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Enums\Modes;
use App\Facades\EventsFacade;
use App\Facades\PermissionsFacade;
use App\Facades\RolesFacade;
use App\Facades\UsersFacade;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\StoreImageRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Resources\EventFullResource;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Models\EventOverlay;
use App\Models\User;
use Carbon\Carbon;

class EventController extends Controller
{

    public function __construct()
    {
      $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     /**
     * @OA\Get(
     *     path="/events",
     *     summary="Displays a listing of events.",
     *     description="Displays a listing of events.",
     *     operationId="resourceEventList",
     *     tags={"Event Route"},
     *     security={ {"bearer": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent( 
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Event")
     *         )
     *     ),
     * )
     *     
     */
    public function index()
    {
        $events = Event::query();

        $data = $events->orderBy('events.created_at', 'desc')->paginate(25);

        return EventResource::collection($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreEventRequest  $request
     * @return \Illuminate\Http\Response
     */

     /**
     * @OA\Post(
     *     path="/events",
     *     summary="Create a new event.",
     *     description="Create a new event.",
     *     operationId="newEventResourceStorage",
     *     tags={"Event Route"},
     *     security={{"bearer": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent( ref="#/components/schemas/Event")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *           @OA\JsonContent(
     *             ref="#/components/schemas/Event"
     *         ),
     *     ),
     *     @OA\Response(
     *      response=422,
     *      description="Validation errors",
     *      ) 
     * )
     *  )
     */
    public function store(StoreEventRequest $request)
    {
        $input = $request->all();

        $event = new Event();

        $valid = $event->validate($input);

        if (!$valid) {
            return response()->json($event->getValidationErrors(), 422);
        }

        $event = Event::create($input);

        if($event) {
            RolesFacade::eventMakeSuperAdmin($event, $request->user());
        }

        return new EventResource($event);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */

     /**
     * @OA\Get(
     *     path="/events/{uuid}",
     *     summary="Retrieve a single event resource.",
     *     description="Retrieve a single event resource.",
     *     operationId="showEventStorage",
     *     tags={"Event Route"},
     *     security={{"bearer": {}}},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the event",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/Event"
     *         ),
     *     @OA\Response(
     *      response=404,
     *      description="Competition does not exist."
     *      ) 
     *     )
     *)
     *     
     */
    public function show($id)
    {
        $event = Event::where('id', $id)->first();

        return new EventFullResource($event);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEventRequest  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */

     /**
     * @OA\Put(
     *     path="/events/{uuid}",
     *     summary="Update a event.",
     *     description="Update a event.",
     *     operationId="updateEventStorage",
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
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent( ref="#/components/schemas/Event")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *          @OA\JsonContent( ref="#/components/schemas/Event")
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
    public function update(UpdateEventRequest $request, $id)
    {
        $event = Event::where('id', $id)->first();

        if(!$event){
            return response()->json(['error' => 'The stream does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::eventCanUpdate($event, $request->user())){
            return response()->json(['error' => 'Access denied to live stream.' , 'message' => 'Access denied to live stream.'], 403);
        }

        $input = $request->all();

        $data = $input + $event->toArray();

        $valid = $event->validate($data);

        if (!$valid) {
            return response()->json($event->getValidationErrors(), 422);
        }

        $event->update($input);

        return new EventFullResource($event);
    }

    /**
     * @OA\Put(
     *     path="/events/{uuid}/invirtu",
     *     summary="Update an Invirtu event.",
     *     description="Update an Invirtu event.",
     *     operationId="updateInvirtuEventStorage",
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
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent( ref="#/components/schemas/Event")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *          @OA\JsonContent( ref="#/components/schemas/Event")
     *     ),
     *     @OA\Response(
     *      response=403,
     *      description="Access denied to Invirtu event.",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", type="Access denined to the Invirtu event resource.")
     *       )
     *     ) 
     * )
     *     
     */
    public function updateInvirtuEvent(UpdateEventRequest $request, $id)
    {
        $event = Event::where('id', $id)->first();

        if(!$event){
            return response()->json(['error' => 'The stream does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!PermissionsFacade::eventCanUpdate($event, $request->user())){
            return response()->json(['error' => 'Access denied to live stream.' , 'message' => 'Access denied to live stream.'], 403);
        }

        // check if currently authenticated user is the owner of the book
        //if ($request->user()->id !== $event->user_id) {
        //    return response()->json(['error' => 'You can only edit your own Forum.'], 403);
        //}

        $input = $request->all();

        $result = EventsFacade::updateInvirtuEvent($event, $input);

        if(!$result){
            return response()->json(['error' => 'There was an error.'], HttpStatusCodes::HTTP_NO_CONTENT);
        }

        if($result->status == 'failure') {
            return response()->json(['errors' => $result->errors], HttpStatusCodes::HTTP_NO_CONTENT);
        }

        return new EventFullResource($event);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */

     /**
     * @OA\Delete(
     *     path="/events/{uuid}",
     *     summary="Delete an event.",
     *     description="Delete an event.",
     *     operationId="destoryEventStorage",
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
     *     @OA\Response(
     *         response=204,
     *         description="Event successfully deleted",
     *     ),
     *     @OA\Response(
     *      response=403,
     *      description="Access denied to event.",
     *      @OA\JsonContent(
     *         @OA\Property(property="message", type="Access denied to event.")
     *        )
     *      ) 
     * )
     *     
     */
    public function destroy(UpdateEventRequest $request, $id)
    {
        $event = Event::where('id', $id)->first();

        if(!PermissionsFacade::eventCanUpdate($event, $request->user())){
            return response()->json(['error' => 'Access denied to live stream.'], 403);
        }
        
        // check if currently authenticated user is the owner of the book
        //if ($event->user()->id !== $event->user_id) {
        //   return response()->json(['error' => 'You can only delete your own Forum.'], 403);
        //}

        $event->delete();

        return response()->json(null, 204);
    }

    public function getUsers() {

        $users = User::query();

        $data = $users->orderBy('events.created_at', 'desc')->paginate(25);

        return EventResource::collection($data);
    }

    /**
     * @OA\Post(
     *     path="events/{uuid}/addRTMPSource",
     *     summary="adds RTMP source to storage.",
     *     description="adds RTMP source to storage.",
     *     operationId="addRTMPSource",
     *     tags={"Competitions Route"},
     *     security={{"bearer": {}}},
     * 
     *      @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the RTMP source",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\RequestBody(
     *      required=true,
     *      @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(
     *              @OA\Property(
     *                  property="rtmp_source",
     *                  type="string",
     *                  format="uuid",
     *                  description="The id of the team to add to the RTMP source."
     *              ),
     *          )
     *      )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent( ref="#/components/schemas/Event")
     *     ),
     *     @OA\Response(
     *      response=403,
     *      description="Cannot add this RTMP source",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="Cannot add this RTMP source.")
     *              )
     *      ) 
     * )
     *     
     */
    public function addRTMPSource(UpdateEventRequest $request, $id) {

        $event = Event::where('id', $id)->first();

        if(!$event){
            return response()->json(['error' => 'The stream does not exist.', 'message' => 'The stream does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        // check if currently authenticated user is the owner of the book
        if(!PermissionsFacade::eventCanUpdate($event, $request->user())){
           return response()->json(['error' => 'Access denied to live stream.' , 'message' => 'Access denied to live stream.'], 403);
        }

        $input = $request->all();

        if(!isset($input['rtmp_source'])) {
            return response()->json(['error' => 'A valid RTMP source is required.', 'message' => 'A valid RTMP source is required.'], 403);
        }

        EventsFacade::addRestream($event, $input['rtmp_source']);

        return new EventFullResource($event);

    }

    /**
     * @OA\Put(
     *     path="/events/{uuid}/updateRTMPSource/{subid}",
     *     summary="Update the RTMP source.",
     *     description="Update the RTMP source.",
     *     operationId="updateRTMPSourceStorage",
     *     tags={"Event Route"},
     *     security={{"bearer": {}}},
     *      @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the RTMP source",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent( ref="#/components/schemas/Event")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *          @OA\JsonContent( ref="#/components/schemas/Event")
     *     ),
     *     @OA\Response(
     *      response=403,
     *      description="Access denied to RTMP source.",
     *      @OA\JsonContent(
     *          @OA\Property(property="message", type="Access denined to RTMP source resource.")
     *       )
     *     ) 
     * )
     *     
     */
    public function updateRTMPSource(UpdateEventRequest $request, $id, $subid) {

        $event = Event::where('id', $id)->first();

        if(!$event){
            return response()->json(['error' => 'The stream does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        // check if currently authenticated user is the owner of the book
        if(!PermissionsFacade::eventCanUpdate($event, $request->user())){
           return response()->json(['error' => 'Access denied to live stream.'], 403);
        }

        $input = $request->all();

        if(!$subid) {
            return response()->json(['error' => 'The ID of the stream you want to remove is required.'], 403);
        }

        EventsFacade::updateRestream($event, $subid, $input);

        return new EventFullResource($event);

    }

    /**
     * @OA\Delete(
     *     path="/events/{uuid}/removeRTMPSource/{subid}",
     *     summary="Delete a RTMP source.",
     *     description="Delete a RTMP source.",
     *     operationId="destoryRTMPSourceStorage",
     *     tags={"Event Route"},
     *     security={{"bearer": {}}},
     *      @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the RTMP source",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="RTMP source successfully deleted",
     *     ),
     *     @OA\Response(
     *      response=403,
     *      description="Access denied to RTMP source.",
     *      @OA\JsonContent(
     *         @OA\Property(property="message", type="Access denied to RTMP source.")
     *        )
     *      ) 
     * )
     *     
     */
    public function removeRTMPSource(UpdateEventRequest $request, $id, $subid) {

        $event = Event::where('id', $id)->first();

        if(!$event){
            return response()->json(['error' => 'The stream does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        // check if currently authenticated user is the owner of the book
        if(!PermissionsFacade::eventCanUpdate($event, $request->user())){
           return response()->json(['error' => 'Access denied to live stream.'], 403);
        }

        $input = $request->all();

        if(!$subid) {
            return response()->json(['error' => 'The ID of the stream you want to remove is required.'], 403);
        }

        EventsFacade::removeRestream($event, $subid);

        return new EventFullResource($event);

    }

    /**
     * @OA\Post(
     *     path="/events/{uuid}/uploadMainImage",
     *     summary="Upload main image for event.",
     *     description="Upload main image for event.",
     *     operationId="uploadMainEventImage",
     *     tags={"Event Route"},
     *     security={{"bearer": {}}},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the event",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          description="Image file to upload",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="image",
     *                      description="Image file to upload",
     *                      type="string",
     *                      format="binary"
     *                  )
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     @OA\JsonContent(
     *             ref="#/components/schemas/Event"
     *         )
     *     ),
     *     @OA\Response(
     *      response=400,
     *      description="Access denied to live stream.",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="Access denied to live stream.")
     *              )
     *      ) 
     * )
     *     
     */
    public function uploadMainImage(StoreImageRequest $request, $id)
    {
        $event = Event::where('id', $id)->first();

        if(!$event){
            return response()->json(['error' => 'The stream does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        // check if currently authenticated user is the owner of the book
        if(!PermissionsFacade::eventCanUpdate($event, $request->user())){
            return response()->json(['error' => 'Access denied to live stream.'], 403);
        }

        $base_location = 'images';

        // Handle File Upload
        if($request->hasFile('image')) {              
            //Using store(), the filename will be hashed. You can use storeAs() to specify a name.
            //To specify the file visibility setting, you can update the config/filesystems.php s3 disk visibility key,
            //or you can specify the visibility of the file in the second parameter of the store() method like:
            //$imagePath = $request->file('document')->store($base_location, ['disk' => 's3', 'visibility' => 'public']);
            
            $imagePath = $request->file('image')->store($base_location, 's3');
          
        } else {
            return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
        }
    
        //We save new path
        $event->forceFill([
            'image_main' => $imagePath
        ]);

        $event->save();
       
        return EventFullResource::make($event);
    }

    /**
     * Upload banner image
     * 
     * @OA\Post(
     *     path="/events/{uuid}/uploadBannerImage",
     *     summary="Upload banner image to storage.",
     *     description="Upload banner image to storage.",
     *     operationId="uploadBannerEventImage",
     *     tags={"Event Route"},
     *     security={{"bearer": {}}},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the event",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          description="Image file to upload",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="image",
     *                      description="Image file to upload",
     *                      type="string",
     *                      format="binary"
     *                  )
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     @OA\JsonContent(
     *             ref="#/components/schemas/Event"
     *         )
     *     ),
     *     @OA\Response(
     *      response=403,
     *      description="Access denied to event.",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="Access denied to event.")
     *              )
     *      ) 
     * )
     *     
     */
    public function uploadBannerImage(StoreImageRequest $request, $id)
    {
        $event = Event::where('id', $id)->first();

        if(!$event){
            return response()->json(['error' => 'The stream does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        // check if currently authenticated user is the owner of the book
        if(!PermissionsFacade::eventCanUpdate($event, $request->user())){
            return response()->json(['error' => 'Access denied to live stream.'], 403);
        }

        $base_location = 'images';

        // Handle File Upload
        if($request->hasFile('image')) {              
            //Using store(), the filename will be hashed. You can use storeAs() to specify a name.
            //To specify the file visibility setting, you can update the config/filesystems.php s3 disk visibility key,
            //or you can specify the visibility of the file in the second parameter of the store() method like:
            //$imagePath = $request->file('document')->store($base_location, ['disk' => 's3', 'visibility' => 'public']);
            
            $imagePath = $request->file('image')->store($base_location, ['disk' => 's3', 'visibility' => 'public']);
          
        } else {
            return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
        }
    
        //We save new path
        $event->forceFill([
            'image_banner' => $imagePath
        ]);

        $event->save();
       
        return EventFullResource::make($event);
    }

    /**
     * Enable broadcast mode
     * 
     * @OA\Post(
     *     path="/events/{uuid}/enableBroadcastMode",
     *     summary="Enable broadcast mode.",
     *     description="Enable broadcast mode.",
     *     operationId="enableBroadcastMode",
     *     tags={"Event Route"},
     *     security={{"bearer": {}}},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the event",
     *         required=true
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          description="Enable broadcast mode",
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     @OA\JsonContent(
     *             ref="#/components/schemas/Event"
     *         )
     *     ),
     *     @OA\Response(
     *      response=403,
     *      description="Access denied to live stream.",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="Access denied to live stream.")
     *              )
     *      ) 
     * )
     *     
     */
    public function enableBroadcastMode(UpdateEventRequest $request, $id) {

        $event = Event::where('id', $id)->first();

        if(!$event){
            return response()->json(['error' => 'The stream does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        // check if currently authenticated user is the owner of the book
        if(!PermissionsFacade::eventCanUpdate($event, $request->user())){
           return response()->json(['error' => 'Access denied to live stream.'], 403);
        }

        $event->forceFill(['mode' => Modes::BROADCAST]);
        $event->save();

        EventsFacade::setToBroadcastMode($event);

        //return response()->json(EventsFacade::setToBroadcastMode($event));

        return new EventFullResource($event);

    }

    /**
     * Enable livestream mode
     * 
     * @OA\Post(
     *     path="/events/{uuid}/enableLivestreamMode",
     *     summary="Enable livestream mode.",
     *     description="Enable livestream mode.",
     *     operationId="enableLivestreamMode",
     *     tags={"Event Route"},
     *     security={{"bearer": {}}},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the event",
     *         required=true
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          description="Enable livestream mode",
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     @OA\JsonContent(
     *             ref="#/components/schemas/Event"
     *         )
     *     ),
     *     @OA\Response(
     *      response=403,
     *      description="Access denied to live stream.",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="Access denied to lives stream.")
     *              )
     *      ) 
     * )
     *     
     */
    public function enableLivestreamMode(UpdateEventRequest $request, $id) {

        $event = Event::where('id', $id)->first();

        if(!$event){
            return response()->json(['error' => 'The stream does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        $input = $request->all();

        // check if currently authenticated user is the owner of the book
        if(!PermissionsFacade::eventCanUpdate($event, $request->user())){
           return response()->json(['error' => 'Access denied to live stream..'], 403);
        }

        if($event->mode == Modes::BROADCAST) {
            EventsFacade::setToLivestreamMode($event);
        }

        if(isset($input['mode']) && $input['mode'] == Modes::OBS) {
            $event->forceFill(['mode' => Modes::OBS]);
            $event->save();
        } else {
            $event->forceFill(['mode' => Modes::RTMP]);
            $event->save();
        }

        //return response()->json( EventsFacade::setToLivestreamMode($event));

        return new EventFullResource($event);

    }

    /**
     * Restream live
     * 
     * @OA\Post(
     *     path="/events/{uuid}/syncAsLive",
     *     summary="Restream live.",
     *     description="Restream live.",
     *     operationId="syncLive",
     *     tags={"Event Route"},
     *     security={{"bearer": {}}},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the event",
     *         required=true
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          description="Sync live",
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     @OA\JsonContent(
     *             ref="#/components/schemas/Event"
     *         )
     *     ),
     *     @OA\Response(
     *      response=403,
     *      description="Cannot add restream to event.",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="Cannot add restream to event.")
     *              )
     *      ) 
     * )
     *     
     */
    public function syncAsLive(UpdateEventRequest $request, $id) {

        $event = Event::where('id', $id)->first();

        if(!$event){
            return response()->json(['error' => 'The stream does not exist.', 'message' => 'The stream does not exist.' ], HttpStatusCodes::HTTP_FOUND);
        }

        // check if currently authenticated user is the owner of the book
        if(!PermissionsFacade::eventCanUpdate($event, $request->user())){
           return response()->json(['error' => 'Cannot add restream to event.', 'message' => 'Cannot add restream to event.'], 403);
        }

        $event->forceFill([
            'live_last_checkin' => Carbon::now()->toDateTimeString(),
            'is_live' => 1
        ]);
        
        $event->save();

        //return response()->json( EventsFacade::setToLivestreamMode($event));

        return new EventFullResource($event);

    }

    /**
     * Send on screen content
     * 
     * @OA\Post(
     *     path="/events/{uuid}/sendOnScreenContent",
     *     summary="Sends on screen content.",
     *     description="Sends on screen content.",
     *     operationId="sendOnScreenContent",
     *     tags={"Event Route"},
     *     security={{"bearer": {}}},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the event",
     *         required=true
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          description="Sync live",
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     @OA\JsonContent(
     *             ref="#/components/schemas/Event"
     *         )
     *     ),
     *     @OA\Response(
     *      response=403,
     *      description="Access denied to live stream.",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="Access denied to live stream.")
     *              )
     *      ) 
     * )
     *     
     */
    public function sendOnScreenContent(UpdateEventRequest $request, $id) {

        $event = Event::where('id', $id)->first();

        if(!$event){
            return response()->json(['error' => 'The stream does not exist.', 'message' => 'The stream does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        // check if currently authenticated user is the owner of the book
        if(!PermissionsFacade::eventCanUpdate($event, $request->user())){
           return response()->json(['error' => 'Access denied to live stream..'], 403);
        }

        $input = $request->all();

        if(!isset($input['type'])){
            return response()->json(['error' => 'The type of content being sent must be present.', 'message' => 'The type of content being sent must be present.'], HttpStatusCodes::HTTP_FOUND);
        }

        if(!isset($input['content']) || (isset($input['content']) && !$input['content'])){
            return response()->json(['error' => 'The contents cannot be empty.', 'message' => 'The contents cannot be empty.'], HttpStatusCodes::HTTP_FOUND);
        }

        if($input['type'] == 'message') {

            EventsFacade::sendOnScreenMessage($event, $input['content'], $input);
        }  else if($input['type'] == 'image') {
            
        }

        //return response()->json( EventsFacade::setToLivestreamMode($event));

        return new EventFullResource($event);

    }

    /**
     * Upload overlay image
     * 
     * @OA\Post(
     *     path="/events/{uuid}/addOverlay",
     *     summary="Upload overlay image to storage.",
     *     description="Upload overlay image to storage.",
     *     operationId="uploadOverlayImage",
     *     tags={"Event Route"},
     *     security={{"bearer": {}}},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the event",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          description="Image file to upload",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="image",
     *                      description="Image file to upload",
     *                      type="string",
     *                      format="binary"
     *                  )
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     @OA\JsonContent(
     *             ref="#/components/schemas/Event"
     *         )
     *     ),
     *     @OA\Response(
     *      response=400,
     *      description="The stream does not exist.",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="The stream does not exist.")
     *              )
     *      ),
     *      @OA\Response(
     *      response=403,
     *      description="Access denied to live stream.",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="Access denied to live stream.")
     *              )
     *      )
     * )
     *     
     */
    public function uploadOverlay(StoreImageRequest $request, $id)
    {
        $event = Event::where('id', $id)->first();

        if(!$event){
            return response()->json(['error' => 'The stream does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        // check if currently authenticated user is the owner of the book
        if(!PermissionsFacade::eventCanUpdate($event, $request->user())){
            return response()->json(['error' => 'Access denied to live stream.'], 403);
        }

        $base_location = 'images';

        // Handle File Upload
        if($request->hasFile('image')) {              
            //Using store(), the filename will be hashed. You can use storeAs() to specify a name.
            //To specify the file visibility setting, you can update the config/filesystems.php s3 disk visibility key,
            //or you can specify the visibility of the file in the second parameter of the store() method like:
            //$imagePath = $request->file('document')->store($base_location, ['disk' => 's3', 'visibility' => 'public']);
            
            $imagePath = $request->file('image')->store($base_location, 's3');
          
        } else {
            return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
        }
    
        $input = $request->all();

        $input['event_id'] = $event->id;

        $overlay = new EventOverlay();

        $valid = $overlay->validate($input);

        if (!$valid) {
            return response()->json($overlay->getValidationErrors(), 422);
        }

        $input['image_url'] = $imagePath;

        $overlay = EventOverlay::create($input);
       
        return EventFullResource::make($event);
    }

    /**
     * @OA\Delete(
     *     path="/events/{uuid}/removeOverlay/{subid}",
     *     summary="Delete a overlay.",
     *     description="Delete a overlay.",
     *     operationId="destoryOverlayStorage",
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
     *     @OA\Response(
     *         response=204,
     *         description="overlay successfully deleted",
     *     ),
     *     @OA\Response(
     *      response=403,
     *      description="Access denied to overlay.",
     *      @OA\JsonContent(
     *         @OA\Property(property="message", type="Access denied to live stream.")
     *        )
     *      ) 
     * )
     *     
     */
    public function removeOverlay(StoreImageRequest $request, $id, $subid)
    {

        $event = Event::where('id', $id)->first();

        if(!$event){
            return response()->json(['error' => 'The stream does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        // check if currently authenticated user is the owner of the book
        if(!PermissionsFacade::eventCanUpdate($event, $request->user())){
            return response()->json(['error' => 'Access denied to live stream.'], 403);
        }

        $overlay = EventOverlay::where('id', $subid)->where('event_id', $subid)->first();

        if(!$overlay){
            return response()->json(['error' => 'Overlay not found.'], HttpStatusCodes::HTTP_NO_CONTENT);
        }

        $overlay->delete();

        return EventFullResource::make($event);

    }

    /**
     * Enables overlay
     * 
     * @OA\Post(
     *     path="/events/{uuid}/enableOverlay/{subid}",
     *     summary="enable overlay image in storage.",
     *     description="enable overlay image in storage.",
     *     operationId="enableOverlayImage",
     *     tags={"Event Route"},
     *     security={{"bearer": {}}},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the event",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             format="uuid"
     *         )
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          description="Image file to upload",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="image",
     *                      description="Image file to upload",
     *                      type="string",
     *                      format="binary"
     *                  )
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     @OA\JsonContent(
     *             ref="#/components/schemas/Event"
     *         )
     *     ),
     *     @OA\Response(
     *      response=400,
     *      description="The stream does not exist.",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="The stream does not exist.")
     *              )
     *      ),
     *      @OA\Response(
     *      response=403,
     *      description="Access denied to live stream.",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="Access denied to live stream.")
     *              )
     *      )
     * )
     *     
     */
    public function enableOverlay(StoreImageRequest $request, $id, $subid)
    {

        $event = Event::where('id', $id)->first();

        if(!$event){
            return response()->json(['error' => 'The stream does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        // check if currently authenticated user is the owner of the book
        if(!PermissionsFacade::eventCanUpdate($event, $request->user())){
            return response()->json(['error' => 'Access denied to live stream.'], 403);
        }

        $overlay = EventOverlay::where('id', $subid)->where('event_id', $id)->first();

        if(!$overlay){
            return response()->json(['error' => 'Overlay not found.'], HttpStatusCodes::HTTP_NO_CONTENT);
        }

        EventsFacade::activateOverlay($event, $overlay);

        return EventFullResource::make($event);
    }

    /**
     * @OA\Post(
     *     path="/events/{uuid}/disableOverlay",
     *     summary="Disable an overlay.",
     *     description="Disable an overlay.",
     *     operationId="disableOverlay",
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
     *     @OA\Response(
     *         response=204,
     *         description="overlay successfully disabled",
     *     ),
     *     @OA\Response(
     *      response=403,
     *      description="Access denied to overlay.",
     *      @OA\JsonContent(
     *         @OA\Property(property="message", type="Access denied to live stream.")
     *        )
     *      ) 
     * )
     *     
     */
    public function disableOverlay(StoreImageRequest $request, $id)
    {

        $event = Event::where('id', $id)->first();

        if(!$event){
            return response()->json(['error' => 'The stream does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        // check if currently authenticated user is the owner of the book
        if(!PermissionsFacade::eventCanUpdate($event, $request->user())){
            return response()->json(['error' => 'Access denied to live stream.'], 403);
        }

        EventsFacade::deactivateOverlay($event);

        return EventFullResource::make($event);
    }

    /**
     * Enable donations
     * 
     * @OA\Post(
     *     path="/events/{uuid}/enableDonations",
     *     summary="Enable donations.",
     *     description="Enable donations.",
     *     operationId="enableDonations",
     *     tags={"Event Route"},
     *     security={{"bearer": {}}},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="UUID of the event",
     *         required=true
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          description="Enable donations",
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     @OA\JsonContent(
     *             ref="#/components/schemas/Event"
     *         )
     *     ),
     *     @OA\Response(
     *      response=403,
     *      description="Access denied to live stream.",
     *      @OA\JsonContent(
     *              @OA\Property(property="message", type="Access denied to lives stream.")
     *              )
     *      ) 
     * )
     *     
     */
    public function enableDonations(StoreImageRequest $request, $id)
    {

        $event = Event::where('id', $id)->first();

        if(!$event){
            return response()->json(['error' => 'The stream does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        // check if currently authenticated user is the owner of the book
        if(!PermissionsFacade::eventCanUpdate($event, $request->user())){
            return response()->json(['error' => 'Access denied to live stream.'], 403);
        }

        $user = $request->user();

        if(!$user->stripe_donation_purhcase_link_url){
            return response()->json(['error' => 'A donation page is required.'], HttpStatusCodes::HTTP_NO_CONTENT);
        }

        UsersFacade::activateDonationForEvent($event, $user);

        return EventFullResource::make($event);
    }

    /**
     * @OA\Post(
     *     path="/events/{uuid}/disableDonations",
     *     summary="Disable donations.",
     *     description="Disable donations.",
     *     operationId="disableDonations",
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
     *     @OA\Response(
     *         response=204,
     *         description="Donations successfully disabled",
     *     ),
     *     @OA\Response(
     *      response=403,
     *      description="Access denied to overlay.",
     *      @OA\JsonContent(
     *         @OA\Property(property="message", type="Access denied to live stream.")
     *        )
     *      ) 
     * )
     *     
     */
    public function disableDonations(StoreImageRequest $request, $id)
    {

        $event = Event::where('id', $id)->first();

        if(!$event){
            return response()->json(['error' => 'The stream does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        // check if currently authenticated user is the owner of the book
        if(!PermissionsFacade::eventCanUpdate($event, $request->user())){
            return response()->json(['error' => 'Access denied to live stream.'], 403);
        }

        UsersFacade::deactivateDonationForEvent($event);

        return EventFullResource::make($event);
    }
}
