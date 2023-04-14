<?php

namespace App\Http\Controllers;

use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class GeneralSettingsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function show()
    {

        if (Gate::denies('access',['general_settings','view'])){
            abort(403, 'Access Denied');
        }

        //check if settings have been initialized previously
        $settings = GeneralSetting::find(1);

        if( isset($settings->id) ){
            //do nothing
        }else{
            //set up new settings
            $settings = new GeneralSetting();
            $settings->supervisors_mode = '1';
            $settings->carry_over_mode  = '1';
            $settings->leave_timesheet_link = '1';
            $settings->include_holidays = '1';
            $settings->include_weekends = '1';
            $settings->time_sheet_data_format = '1';
            $settings->time_sheet_submission_deadline = '4';
            $settings->objectives_submission_deadline = '10';
            $settings->objectives_marking_opening = '1-11';
            $settings->objectives_marking_closing = '30-11';
            $settings->user_activities_recording_mode = '1';
            $settings->save();
        }

        $supervisors_modes = GeneralSetting::$supervisors_modes;
        $carry_over_modes  = GeneralSetting::$carry_over_modes;
        $leave_timesheet_link_modes = GeneralSetting::$leave_timesheet_link_modes;
        $include_holidays_in_leave  = GeneralSetting::$include_holidays_in_leave;
        $include_weekends_in_leave  = GeneralSetting::$include_weekends_in_leave;
        $time_sheet_data_formats = GeneralSetting::$time_sheet_data_formats;
        $user_activities_recording_modes = GeneralSetting::$user_activities_recording_modes ;

        $model_name = 'general_setting';
        $controller_name = 'general_settings';
        $view_type = 'create';

        return view('general_settings.show',
            compact('settings','supervisors_modes','carry_over_modes','leave_timesheet_link_modes',
                'include_holidays_in_leave','include_weekends_in_leave', 'time_sheet_data_formats',
                'user_activities_recording_modes',
                'model_name', 'controller_name', 'view_type'));


    }



    public function update(Request $request)
    {

        $data  = $this->validateRequest();
        //dd($data);
        $settings = GeneralSetting::find(1);
        $settings->update($data);

        return redirect('general_settings');
    }


    private function validateRequest(){ //dd(request());

        return $data = request()->validate([
            'supervisors_mode' => 'required',
            'carry_over_mode' => 'required',
            'leave_timesheet_link' => 'required',
            'include_holidays_in_leave' => 'required',
            'include_weekends_in_leave' => 'required',
            'time_sheet_data_format' => 'required',
            'time_sheet_submission_deadline' => 'required|numeric',
            'objectives_submission_deadline' => 'required|numeric',
            'objectives_marking_opening' => 'required',
            'objectives_marking_closing' => 'required',
            'user_activities_recording_mode' => 'required',
        ]);

    }
}
