<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $table = 'peoples';
    protected $fillable = [
        'firstName',
        'lastName',
        'identification',
        'phone',
        'date',
        'photoPerson',
        'cityId',
        'email',
        'password',
        'status'

    ];
}
