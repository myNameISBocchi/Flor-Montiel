<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $fillable = [
        'countryId',
        'initials',
        'stateName'
    ];

    public function setStateNameAttribute($value)
    {
        $this->attributes['stateName'] = mb_strtoupper($value, 'UTF-8');
    }

    public function setInitialsAttribute($value)
    {
        $this->attributes['initials'] = mb_strtoupper($value, 'UTF-8');
    }
}