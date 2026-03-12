<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonCouncil extends Model
{
    protected $table = ['peoples_councils']; 
    protected $fillable = [
        'personId',
        'councilId'
    ];
}
