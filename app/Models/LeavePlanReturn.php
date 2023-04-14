<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeavePlanReturn extends Model
{
    protected $guarded = [];

    public function leave_plan(){
        return $this->belongsTo(LeavePlan::class);
    }

}
