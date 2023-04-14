<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TimesheetClient extends Model
{
    use HasFactory;
    protected $fillable = [
        'time_sheet_id',
        'project_id',
    ];

    public static function findClient($id)
    {
        $data =  DB::table('timesheet_clients')
            ->join('projects', 'timesheet_clients.project_id', '=', 'projects.id')
            ->select('timesheet_clients.*', 'projects.name', 'projects.number')
            ->where('timesheet_clients.id', $id)
            ->first();

        return $data;
    }

    public static function find_client_timesheet($id)
    {
        $data =  DB::table('timesheet_clients')
            ->join('projects', 'timesheet_clients.project_id', '=', 'projects.id')
            ->select('timesheet_clients.*', 'projects.name', 'projects.number')
            ->where('time_sheet_id', $id)
            ->get();

        return $data;
    }
}
