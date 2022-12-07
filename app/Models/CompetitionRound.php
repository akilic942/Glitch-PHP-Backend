<?php

namespace App\Models;

use App\Traits\HasCompositePrimaryKeyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionRound extends BaseModel
{
    use HasFactory, HasCompositePrimaryKeyTrait;

    protected $primaryKey = ['competition_id','round'];
    
    public $incrementing = false;

    protected $keyType =  'string';

    protected $casts = [
        'competition_id' => 'string',
        'round' => 'integer',
    ];

    protected $rules = array(
        'competition_id' => 'required|uuid',
        'round'  => 'required|integer',
        'title' => 'nullable|string|min:0|max:255'
    );

    protected $fillable = [
        'competition_id',
        'round',
        'title',
        'overview',
        'round_start_date',
        'round_end_date'
    ];

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

}
