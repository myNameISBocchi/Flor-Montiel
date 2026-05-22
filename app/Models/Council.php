<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Council extends Model
{
    protected $fillable = [
        'councilName',
        'comunityId',
        'cityId',
        'googleMaps',
    ];

    public function setCouncilNameAttribute($value)
    {
        $this->attributes['councilName'] = mb_strtoupper($value, 'UTF-8');
    }
}