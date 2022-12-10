<?php

namespace App\Models;

use App\Traits\HasCompositePrimaryKeyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionUser extends BaseModel
{

    use HasFactory, HasCompositePrimaryKeyTrait;

    protected $primaryKey = ['competition_id','user_id'];

    public $incrementing = false;

    protected $keyType =  'string';

    protected $casts = [
        'user_id' => 'string',
        'competition_id' => 'string',
    ];

    protected $rules = array(
        'competition_id' => 'required',
        'user_id'  => 'required',
        'user_role' => 'integer|nullable',
        'checked_in' => 'boolean|nullable',
    );

    protected $fillable = [
        'competition_id',
        'user_id',
        'user_role',
        'checked_in'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }
}
