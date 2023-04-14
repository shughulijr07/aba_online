<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerformanceObjectiveLine extends Model
{
    protected $guarded = [];

    public function performance_objective(){
        return $this->belongsTo(PerformanceObjective::class);
    }
}
