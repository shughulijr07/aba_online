<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequisitionRequestLine extends Model
{
    protected $guarded = [];

    public function travel_request(){
        return $this->belongsTo(RequisitionRequest::class);
    }
}
