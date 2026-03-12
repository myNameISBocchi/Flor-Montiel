<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonRole extends Model
{
    protected $table = ['peoples_roles'];
    protected $fillable = [
        'personId',
        'roleId'
    ];
}
