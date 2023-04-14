<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerformanceObjectiveApproval extends Model
{
    protected $guarded = [];

    public function performance_objective(){
        return $this->belongsTo(PerformanceObjective::class);
    }
}
