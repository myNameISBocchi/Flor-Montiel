<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonCommittees extends Model
{
    protected $table = 'peoples_committees';
    protected $fillable = [
        'personId',
        'committeeId'
    ];
}
