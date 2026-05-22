<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'roleName'
    ];

    public function setRoleNameAttribute($value)
    {
        $this->attributes['roleName'] = mb_strtoupper($value, 'UTF-8');
    }
}