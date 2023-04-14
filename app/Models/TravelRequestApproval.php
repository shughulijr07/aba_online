<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelRequestApproval extends Model
{
    protected $guarded = [];

    public function travel_request(){
        return $this->belongsTo(TravelRequest::class);
    }

}
