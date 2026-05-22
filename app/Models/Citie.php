<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Citie extends Model
{
    protected $fillable = [
        'stateId',
        'cityName'
    ];

    public function setCityNameAttribute($value)
    {
        $this->attributes['cityName'] = mb_strtoupper($value, 'UTF-8');
    }
}