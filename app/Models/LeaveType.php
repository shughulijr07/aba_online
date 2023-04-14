<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{


    protected $guarded = [];

    public static function get_active_leave_types(){

        $leave_types = LeaveType::where('status','=','Active')->get();
        $leave_types_array = [];

        foreach ($leave_types as $leave_type){

            $leave_types_array[$leave_type->key] = [
                "name" => $leave_type->name,
                "days" => $leave_type->days,
                "period" => $leave_type->period,
            ];
        }

        return [
            'objects' => $leave_types,
            'arrays' => $leave_types_array,
        ];

    }




}
