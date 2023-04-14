<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequisitionRequestReturn extends Model
{
    protected $guarded = [];

    public function travel_request(){
        return $this->belongsTo(RequisitionRequest::class);
    }
}
