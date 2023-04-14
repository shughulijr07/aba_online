<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\TimeSheet;
use App\Models\TimeSheetLateSubmission;
use Facade\FlareClient\Time\Time;
use Illuminate\Http\Request;

class TimeSheetLateSubmissionsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $time_sheet_late_submissions = TimeSheetLateSubmission::where('status','=','locked')->latest()->get();
        $months = TimeSheet::$months;

        return view('time_sheet_late_submissions.index',
            compact('time_sheet_late_submissions','months'));
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show(TimeSheetLateSubmission $timeSheetLateSubmission)
    {

        $time_sheet_late_submission = $timeSheetLateSubmission;
        $staff = Staff::find($time_sheet_late_submission->staff_id);
        $months = TimeSheet::$months;

        return view('time_sheet_late_submissions.show',
            compact('time_sheet_late_submission','months','staff'));

    }

    public function edit(TimeSheetLateSubmission $timeSheetLateSubmission)
    {
        //dd($timeSheetLateSubmission);
        $time_sheet_late_submission = $timeSheetLateSubmission;

        if($time_sheet_late_submission->status == 'locked' && !in_array($timeSheetLateSubmission->reason, [null,''])){
            return redirect('time_sheet_late_submissions/'.$timeSheetLateSubmission->id);
        }

        $months = TimeSheet::$months;

        return view('time_sheet_late_submissions.edit',
            compact('time_sheet_late_submission','months'));

    }

    public function update(Request $request, TimeSheetLateSubmission $timeSheetLateSubmission)
    {
        $data = $this->validateRequest();

        $timeSheetLateSubmission->reason = $data['reason'];
        $timeSheetLateSubmission->save();

        return redirect('time_sheet_late_submissions/'.$timeSheetLateSubmission->id);
    }


    public function unlockTimeSheetSubmission($id){

        $timeSheetLateSubmission = TimeSheetLateSubmission::find($id);

        if( in_array(auth()->user()->role_id,[1,3]) ){
            $timeSheetLateSubmission->status = 'unlocked';
            $timeSheetLateSubmission->unlocked_by = auth()->user()->staff->id;
            $timeSheetLateSubmission->save();
            return redirect('time_sheet_late_submissions/'.$timeSheetLateSubmission->id);

        }else{
            return redirect('time_sheet_late_submissions/'.$timeSheetLateSubmission->id);
        }

    }

    public function destroy(TimeSheetLateSubmission $timeSheetLateSubmission)
    {
        //
    }

    public function validateRequest(){

        return $data = request()->validate([
            'reason'=> 'required'
        ]);
    }
}
