<?php
namespace App\Facades;

use App\Models\Message;
use App\Models\MessageThread;
use App\Models\MessageThreadParticapant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Facade;

class MessageFacade extends Facade{

    protected static function getFacadeAccessor()
    {
        return 'chat';
    }

    public static function createOrRetrieveThread(array $particapantsIDs = array()) {

        $threadParticapants = DB::table('message_thread_particapants as participants')
           ->select(DB::raw('participants.thread_id, COUNT(DISTINCT participants.user_id) as participants'))
           ->whereIn('participants.user_id', $particapantsIDs)
           ->groupBy('participants.thread_id')
           ->having(DB::raw('COUNT(DISTINCT participants.user_id)'), '=', DB::raw(count($particapantsIDs)))
           ->first();

        $thread = null;

        if(!$threadParticapants) {

            $thread = MessageThread::create();

            foreach($particapantsIDs as $id){
                $mp = MessageThreadParticapant::create(['thread_id' => $thread->id, 'user_id' => $id]);
            }

        } else {

            $thread = MessageThread::where('id', $threadParticapants->thread_id)->first();

        }

        return $thread;
        
    }

    public static function sendMessage(User $sender, MessageThread $thread, string $message){

        $data = array(
            'message' => $message,
            'user_id' => $sender->id,
            'thread_id' => $thread->id
        );

        $message = Message::create($data);

        return $message;

    }

    

}