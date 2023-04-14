<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RetirementRequestReturn extends Model
{
    protected $guarded = [];

    public function travel_request(){
        return $this->belongsTo(RetirementRequest::class);
    }
}
