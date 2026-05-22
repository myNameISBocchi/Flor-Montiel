<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'countryName'
    ];

    public function setCountryNameAttribute($value)
    {
        $this->attributes['countryName'] = mb_strtoupper($value, 'UTF-8');
    }
}