<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Committee extends Model
{
    protected $fillable = [
        'committeeName',
        'parentId'
    ];

    public function setCommitteeNameAttribute($value)
    {
        $this->attributes['committeeName'] = mb_strtoupper($value, 'UTF-8');
    }

    public function parent(){
        return $this->belongsTo(Committee::class, 'parentId');
    }

    public function children(){
        return $this->hasMany(Committee::class, 'parentId');
    }
}