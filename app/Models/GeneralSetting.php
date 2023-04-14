<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{

    protected $guarded = [];


    public static $supervisors_modes = [
        '1' => 'Dedicated Supervisors',
        '2' => 'Selection From List',
    ];


    public static $carry_over_modes = [
        '1' => 'Automatic',
        '2' => 'Manual',
    ];


    public static $leave_timesheet_link_modes = [
        '1' => "Don't Link",
        '2' => "Link",
    ];

    public static $include_holidays_in_leave = [
        '1' => "Yes",
        '2' => "No",
    ];

    public static $include_weekends_in_leave = [
        '1' => "Yes",
        '2' => "No",
    ];

    public static $time_sheet_data_formats = [
        '1' => "Normal Database Entries",
        '2' => "JSON",
    ];

    public static $user_activities_recording_modes = [
        '1' => "Record All User Activities",
        '2' => "Record Major User Activities Only",
        '3' => "Don't Record Any User Activity",
    ];




}
