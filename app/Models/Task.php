<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'timesheet_client_id',
        'user_id',
        'day_date',
        'start_time',
        'end_time',
        'hour',
        'task_name',
        'task_day',
        'status',
        'description',        
    ];

    public static function fetch_task_progress(){
        $data = DB::table('task_progress')
                ->join('tasks','task_progress.task_id','=','tasks.id');
    }
}
