<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelRequestReject extends Model
{
    protected $guarded = [];

    public function travel_request(){
        return $this->belongsTo(TravelRequest::class);
    }
}
