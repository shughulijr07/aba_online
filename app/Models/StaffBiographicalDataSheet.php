<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StaffBiographicalDataSheet extends Model
{
    protected $guarded = [];

    public function staff(){
        return $this->belongsTo(Staff::class);
    }

    /************************* my custom functions starts here *******************/


}
