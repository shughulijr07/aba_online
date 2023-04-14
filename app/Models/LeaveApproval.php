<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveApproval extends Model
{
    protected $guarded = [];

    public static $approval_level = [
        'spv' => 'Supervisor',
        'hrm' => 'Human Resource Manager',
    ];

    public function leave(){
        $this->belongsTo(Leave::class);
    }

}
