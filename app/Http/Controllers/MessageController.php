<?php

namespace App\Http\Controllers;

use App\Facades\MessageFacade;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Http\Resources\MessageResource;
use App\Http\Resources\MessageThreadResource;
use App\Models\Message;
use App\Models\MessageThread;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return MessageResource::collection(Message::where('message_thread_particapants.user_id', '=', $request->user()->id)->join('message_thread_particapants.thread_id', '=', 'messages.thread_id')->orderBy('created_at', 'desc')->paginate(25));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMessageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMessageRequest $request)
    {
        $input = $request->all();

        $input['user_id'] = $request->user()->id;

        $message = new Message();

        $valid = $message->validate($input);

        if (!$valid) {
            return response()->json($message->getValidationErrors(), 422);
        }

        $thread_id = $request->thread_id;

        $thread = MessageThread::where('id', $thread_id)->first();

        $message = MessageFacade::sendMessage($request->user(),$thread, $request->message);

        return new MessageResource($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMessageRequest  $request
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMessageRequest $request, $id)
    {
        $message = Message::where('id', $id)->first();

        // check if currently authenticated user is the owner of the book
        if ($request->user()->id !== $message->user_id) {
            return response()->json(['error' => 'You can only edit your own message.'], 403);
        }

        $input = $request->all();

        $data = $input + $message->toArray() ;

        $valid = $message->validate($data);

        if (!$valid) {
            return response()->json($message->getValidationErrors(), 422);
        }

        $message->update($data);

        return new MessageResource($message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $message= Message::where('id', $id)->first();

        // check if currently authenticated user is the owner of the book
        if ($request->user()->id !== $message->user_id) {
            return response()->json(['error' => 'You can only delete your own discussion.'], 403);
        }

        $message->delete();

        return response()->json(null, 204);
    }

    public function getConversations(Request $request) {

        return MessageThreadResource::collection(MessageThread::where('user_id', '=', $request->user()->id)->join('message_thread_particapants','message_thread_particapants.thread_id', '=', 'message_threads.id')->orderBy('message_threads.created_at', 'desc')->paginate(25));

    }

    public function conversations(Request $request) {

        $thread = MessageFacade::createOrRetrieveThread($request->users);

        return new MessageThreadResource($thread);


    }
}
