<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StaffDependent extends Model
{
    protected $guarded = [];

    public static $relationships = ['Spouse','Child'];

    public function staff(){
        return $this->belongsTo(Staff::class);
    }

    /************************* my custom functions starts here *******************/


}
