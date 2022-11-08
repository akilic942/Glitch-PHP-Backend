<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class MessageThreadParticapant extends BaseModel
{
    use HasFactory;

    public $incrementing = false;

    protected $primaryKey = ['thread_id','user_id'];

    protected $casts = [
        'user_id' => 'string',
        'thread_id' => 'string',
    ];

    protected $fillable = [
        'thread_id',
        'user_id',
    ];

    protected $rules = [
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
