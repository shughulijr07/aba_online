<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSheetApproval extends Model
{
    protected $guarded = [];

    public function time_sheet(){
        return $this->belongsTo(TimeSheet::class);
    }
}
