<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequisitionRequestChangedSupervisor extends Model
{
    protected $guarded = [];

    public function travel_request(){
        return $this->belongsTo(RequisitionRequest::class);
    }
}
