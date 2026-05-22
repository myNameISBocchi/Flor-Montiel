<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comunity extends Model
{
    protected $fillable = [
        'comunityName',
        'googleMaps',
        'photoComunity',
    ];

    public function setComunityNameAttribute($value)
    {
        $this->attributes['comunityName'] = mb_strtoupper($value, 'UTF-8');
    }
}