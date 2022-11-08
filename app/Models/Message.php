<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends BaseModel
{
    use HasFactory;

    protected $primaryKey = "id";

    protected $keyType =  'string';

    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
        'thread_id' => 'string',
    ];

    protected $fillable = [
        'message',
        'thread_id',
        'user_id'
    ];

    protected $rules = [
        'message' => 'required',
        'user_id' => 'required',
        'thread_id' => 'required'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function thread() {
        return $this->belongsTo(MessageThread::class);
    }

}
