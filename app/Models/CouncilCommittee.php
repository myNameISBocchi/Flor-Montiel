<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouncilCommittee extends Model
{
    protected $fillable = [
        'councilId',
        'committeeId'
    ];
}
