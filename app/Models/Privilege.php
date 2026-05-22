<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Privilege extends Model
{
    protected $fillable = [
        'privilegeName',
        'route',
        'status'
    ];

    public function setPrivilegeNameAttribute($value)
    {
        $this->attributes['privilegeName'] = mb_strtoupper($value, 'UTF-8');
    }
}