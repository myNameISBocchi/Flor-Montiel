<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonComunity extends Model
{
    protected $table = ['peoples_comunities'];
    protected $fillable = [
        'personId',
        'comunityId'
    ];
}
