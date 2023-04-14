<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveChangedSupervisor extends Model
{
    protected $guarded = [];

    public function leave(){
        return $this->belongsTo(Leave::class);
    }
}
