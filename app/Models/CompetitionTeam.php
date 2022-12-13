<?php

namespace App\Models;

use App\Traits\HasCompositePrimaryKeyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionTeam extends BaseModel
{
    use HasFactory, HasCompositePrimaryKeyTrait;

    protected $primaryKey = ['competition_id','team_id'];
    
    public $incrementing = false;

    protected $keyType =  'string';

    protected $casts = [
        'team_id' => 'string',
        'competition_id' => 'string',
    ];

    protected $rules = array(
        'competition_id' => 'required|uuid',
        'team_id'  => 'required|uuid',
        'checked_in' => 'boolean|nullable',
        'waiver_signed' => 'boolean|nullable',
        'entry_fee_paid' => 'boolean|nullable',
        'status' => 'nullable|numeric|min:0|max:7'
    );

    protected $fillable = [
        'team_id',
        'competition_id',
        'status',
        'checked_in',
        'status',
        'checked_in_time',
        'entry_fee_paid',
        'waiver_signed'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }
}
