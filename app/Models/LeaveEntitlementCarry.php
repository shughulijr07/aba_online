<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveEntitlementCarry extends Model
{
    protected $guarded = [];

    public function line(){
        return $this->belongsTo(LeaveEntitlementLine::class);
    }
}
