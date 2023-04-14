<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveEntitlementLine extends Model
{
    protected $guarded = [];

    public function leave_entitlement(){
        return $this->belongsTo(LeaveEntitlement::class);
    }

    public function extensions(){
        return $this->hasMany(LeaveEntitlementExtension::class);
    }

    public function carry_over(){
        return $this->hasOne(LeaveEntitlementCarry::class);
    }

}
