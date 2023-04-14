<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveEntitlementExtension extends Model
{

    protected $guarded = [];

    public function line(){
        return $this->belongsTo(LeaveEntitlementLine::class);
    }

}
