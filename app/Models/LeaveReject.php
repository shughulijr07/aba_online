<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveReject extends Model
{
    protected $guarded = [];

    public static $reject_level = [
        'spv' => 'Supervisor',
        'hrm' => 'Human Resource Manager',
    ];

    public function leave(){
        $this->belongsTo(Leave::class);
    }

}
