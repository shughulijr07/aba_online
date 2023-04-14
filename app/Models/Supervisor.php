<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    protected $guarded = [];


    function staff(){
        return $this->belongsTo(Staff::class);
    }
}
