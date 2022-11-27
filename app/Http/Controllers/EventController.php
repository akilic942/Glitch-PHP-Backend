<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCodes;
use App\Enums\Modes;
use App\Facades\EventsFacade;
use App\Facades\PermissionsFacade;
use App\Facades\RolesFacade;
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
    public function index()
    {
        $events = Event::query();

        $data = $events->orderBy('events.created_at', 'desc')->paginate(25);

        return EventResource::collection($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreEventRequest  $request
     * @return \Illuminate\Http\Response
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
    public function show($id)
    {
        $event = Event::where('id', $id)->first();

        return new EventFullResource($event);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEventRequest  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEventRequest $request, $id)
    {
        $event = Event::where('id', $id)->first();

        if(!$event){
            return response()->json(['error' => 'The stream does not exist.'], HttpStatusCodes::HTTP_FOUND);
        }

        // check if currently authenticated user is the owner of the book
        //if ($request->user()->id !== $event->user_id) {
        //    return response()->json(['error' => 'You can only edit your own Forum.'], 403);
        //}

        $input = $request->all();

        $data = $input + $event->toArray();

        $valid = $event->validate($data);

        if (!$valid) {
            return response()->json($event->getValidationErrors(), 422);
        }

        $event->update($data);

        return new EventResource($event);
    }

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
}
