<?php

namespace App\Http\Controllers;

use App\Facades\EventsFacade;
use App\Facades\PermissionsFacade;
use App\Facades\RolesFacade;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Resources\EventFullResource;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Models\User;

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

        // check if currently authenticated user is the owner of the book
        //if ($request->user()->id !== $event->user_id) {
        //    return response()->json(['error' => 'You can only edit your own Forum.'], 403);
        //}

        $input = $request->all();

        $data = $input + $event->toArray() ;

        $valid = $event->validate($data);

        if (!$valid) {
            return response()->json($event->getValidationErrors(), 422);
        }

        $event->update($data);

        return new EventResource($event);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        //$event = Event::where('id', $id)->first();

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

        // check if currently authenticated user is the owner of the book
        if(!PermissionsFacade::eventCanUpdate($event, $request->user())){
           return response()->json(['error' => 'Cannot add restream to event.'], 403);
        }

        $input = $request->all();

        if(!isset($input['rtmp_source'])) {
            return response()->json(['error' => 'A valid RTMP source is required.'], 403);
        }

        EventsFacade::addRestream($event, $input['rtmp_source']);

        return new EventFullResource($event);

    }
}
