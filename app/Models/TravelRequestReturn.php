<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelRequestReturn extends Model
{
    protected $guarded = [];

    public function travel_request(){
        return $this->belongsTo(TimeSheet::class);
    }
}
