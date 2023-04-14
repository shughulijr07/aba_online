<?php

namespace App\Http\Controllers;

use App\Models\GeneralSetting;
use App\Models\PerformanceObjective;
use App\Models\Staff;
use App\Models\StaffPerformance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class StaffPerformancesController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        if (Gate::denies('access',['staff_performances','view'])){
            abort(403, 'Access Denied');
        }

        $staff = auth()->user()->staff;
        $staff_id = $staff->id;
        
        //this method create performance entry for staff if was not created before
        StaffPerformance::create_performance_entry_for_one_staff($staff_id);

        $staff_performances = $staff->staff_performances;

        $model_name = 'staff_performance';
        $controller_name = 'staff_performances';
        $view_type = 'index';

        return view('staff.performances.index',
            compact('staff','staff_performances','model_name', 'controller_name', 'view_type'));
        
    }


    public function indexAdmin($year)
    {
        if (Gate::denies('access',['staff_performances','view'])){
            abort(403, 'Access Denied');
        }

        //this method create performance entry for staff if was not created before
        StaffPerformance::create_performance_entries_for_all_staff();
        $all_staff_performances = [];
        $staff_performances = [];
        if($year == 'all'){
            $all_staff_performances = StaffPerformance::all();
        }
        else{
            $all_staff_performances = StaffPerformance::where('year','=',date('Y'))->get();
        }


        if( in_array(auth()->user()->role_id,[1,2,3]) ){ // show all staff performances for these roles
            $staff_performances = $all_staff_performances;
        }else{ // only performances of staff responsible to this supervisor

            $responsible_spv = auth()->user()->staff->id;
            foreach ($all_staff_performances as $one_staff_performance) {
                if($one_staff_performance->staff->supervisor_id == $responsible_spv){
                    $staff_performances[] = $one_staff_performance;
                }
            }

        }

        $model_name = 'staff_performance';
        $controller_name = 'staff_performances';
        $view_type = 'index';

        return view('staff.performances.index_admin',
            compact('staff_performances','year','model_name', 'controller_name', 'view_type'));


    }


    public function show($performance_id)
    {
        if (Gate::denies('access',['staff_performances','view'])){
            abort(403, 'Access Denied');
        }

        //
        $staff_performance = StaffPerformance::find($performance_id);
        $staff = $staff_performance->staff;
        $staff_performance_statuses = StaffPerformance::$staff_performance_statuses;



        /************ staff objectives codes begin here  ******/
        //get system settings
        $system_settings = GeneralSetting::find(1);
        $supervisors_mode = $system_settings->supervisors_mode;


        $current_logged_staff = auth()->user()->staff;
        $user_role = auth()->user()->role_id;

        //dd($performance_objective_lines);
        $performance_objective = PerformanceObjective::where('staff_id','=',$staff->id)
            ->where('year','=',$staff_performance->year)
            ->where('status','=','50')->first();

        $lines = [];
        $supervisors = [];
        $performance_objective_statuses = PerformanceObjective::$performance_objective_statuses;
        $responsible_spv = '';
        $spv_name = '';
        $employee_name = ucwords($staff->first_name.' '.$staff->last_name);
        $department_name = $staff->department->name;
        $year = $staff_performance->year;


        if( isset($performance_objective->id) ){

            if(  count($performance_objective->lines)>0 ){
                $lines = ($performance_objective->lines->last())->data;
                $lines = json_decode($lines,true);
            }

            $supervisor_id = $performance_objective->responsible_spv;
            //override supervisor settings if no supervisor have been assigned to this staff
            if($supervisor_id == null || $supervisor_id == ''){ $supervisors_mode = '2'; } //'2' => 'Selection From List'

            $supervisors =  Staff::get_supervisors('2');
            $responsible_spv = $performance_objective->responsible_spv;
            $supervisor = Staff::find($responsible_spv);
            $spv_name = ucwords($supervisor->first_name.' '.$supervisor->last_name);

        }

        /**** staff objectives code ends here ***/


        $model_name = 'staff_performance';
        $controller_name = 'staff_performances';
        $view_type = 'show';
        $view_type2 = 'show_admin';

        $rejection_reason = '';
        $supervisor_change_reason = '';
        $comments = '';
        //dd($performance_objective);

        return view('staff.performances.show',
            compact('staff_performance', 'staff_performance_statuses',
                'performance_objective','supervisors', 'responsible_spv', 'spv_name', 'employee_name',
                'lines','performance_objective_statuses', 'supervisors_mode','department_name','year',
                'rejection_reason','travel_request_modification_reason','comments','user_role','supervisor_change_reason',
                'model_name', 'controller_name', 'view_type','view_type2'));

    }


    public function edit(StaffPerformance $staff_performance)
    {
        if (Gate::denies('access',['staff_performances','edit'])){
            abort(403, 'Access Denied');
        }

        $model_name = 'staff_performance';
        $controller_name = 'staff_performances';
        $view_type = 'edit';

        return view('staff.performances.edit',
            compact( 'staff_performance','model_name', 'controller_name', 'view_type'));

    }



    public function firstQuoterAssessment(){

        if (Gate::denies('access',['staff_performances','edit'])){
            abort(403, 'Access Denied');
        }

        $data = $this->validateFirstQuoterAssessment();
        $first_quoter_assessing_spv = auth()->user()->staff->id;
        $staff_performance_id = $data['staff_performance_id'];
        $staff_performance = StaffPerformance::find($staff_performance_id);

        //dd($staff_performance);

        if( isset($staff_performance->id) &&  ($staff_performance->staff->supervisor_id == $first_quoter_assessing_spv)
            && ( in_array($staff_performance->first_quoter_spv_marks,[null,'']))
        ){

            $staff_performance->first_quoter_assessing_spv  = $first_quoter_assessing_spv;
            $staff_performance->first_quoter_spv_marks = $data['first_quoter_spv_marks'];
            $staff_performance->first_quoter_spv_comments = $data['first_quoter_spv_comments'];
            $staff_performance->status = '20';
            $staff_performance->save();

            $message = 'Assessment Submission was completed successfully';
            return redirect('/staff_performances/'.$staff_performance_id)->with('message',$message);

        }else{
            return redirect('/staff_performances/'.$staff_performance_id)->with('message','You are not authorized to do assessment for this employee');
        }


        //return redirect('staff_performances/'.$staff_performance->id);
    }




    public function secondQuoterAssessment(){

        if (Gate::denies('access',['staff_performances','edit'])){
            abort(403, 'Access Denied');
        }

        $data = $this->validateSecondQuoterAssessment();
        $second_quoter_assessing_spv = auth()->user()->staff->id;
        $staff_performance_id = $data['staff_performance_id'];
        $staff_performance = StaffPerformance::find($staff_performance_id);

        //dd($staff_performance);

        if( isset($staff_performance->id) &&  ($staff_performance->staff->supervisor_id == $second_quoter_assessing_spv)
            && ( in_array($staff_performance->second_quoter_spv_marks,[null,'']))
        ){

            $staff_performance->second_quoter_assessing_spv  = $second_quoter_assessing_spv;
            $staff_performance->second_quoter_spv_marks = $data['second_quoter_spv_marks'];
            $staff_performance->second_quoter_spv_comments = $data['second_quoter_spv_comments'];
            $staff_performance->status = '30';
            $staff_performance->save();

            $message = 'Assessment Submission was completed successfully';
            return redirect('/staff_performances/'.$staff_performance_id)->with('message',$message);

        }else{
            return redirect('/staff_performances/'.$staff_performance_id)->with('message','You are not authorized to do assessment for this employee');
        }


        //return redirect('staff_performances/'.$staff_performance->id);
    }




    public function thirdQuoterAssessment(){

        if (Gate::denies('access',['staff_performances','edit'])){
            abort(403, 'Access Denied');
        }

        $data = $this->validateThirdQuoterAssessment();
        $third_quoter_assessing_spv = auth()->user()->staff->id;
        $staff_performance_id = $data['staff_performance_id'];
        $staff_performance = StaffPerformance::find($staff_performance_id);

        //dd($staff_performance);

        if( isset($staff_performance->id) &&  ($staff_performance->staff->supervisor_id == $third_quoter_assessing_spv)
            && ( in_array($staff_performance->third_quoter_spv_marks,[null,'']))
        ){

            $staff_performance->third_quoter_assessing_spv  = $third_quoter_assessing_spv;
            $staff_performance->third_quoter_spv_marks = $data['third_quoter_spv_marks'];
            $staff_performance->third_quoter_spv_comments = $data['third_quoter_spv_comments'];
            $staff_performance->status = '40';
            $staff_performance->save();

            $message = 'Assessment Submission was completed successfully';
            return redirect('/staff_performances/'.$staff_performance_id)->with('message',$message);

        }else{
            return redirect('/staff_performances/'.$staff_performance_id)->with('message','You are not authorized to do assessment for this employee');
        }


        //return redirect('staff_performances/'.$staff_performance->id);
    }




    public function fourthQuoterAssessment(){

        if (Gate::denies('access',['staff_performances','edit'])){
            abort(403, 'Access Denied');
        }

        $data = $this->validateFourthQuoterAssessment();
        $fourth_quoter_assessing_spv = auth()->user()->staff->id;
        $staff_performance_id = $data['staff_performance_id'];
        $staff_performance = StaffPerformance::find($staff_performance_id);

        //dd($staff_performance);

        if( isset($staff_performance->id) &&  ($staff_performance->staff->supervisor_id == $fourth_quoter_assessing_spv)
            && ( in_array($staff_performance->fourth_quoter_spv_marks,[null,'']))
        ){

            $staff_performance->fourth_quoter_assessing_spv  = $fourth_quoter_assessing_spv;
            $staff_performance->fourth_quoter_spv_marks = $data['fourth_quoter_spv_marks'];
            $staff_performance->fourth_quoter_spv_comments = $data['fourth_quoter_spv_comments'];
            $staff_performance->status = '50';
            $staff_performance->save();

            $message = 'Assessment Submission was completed successfully';
            return redirect('/staff_performances/'.$staff_performance_id)->with('message',$message);

        }else{
            return redirect('/staff_performances/'.$staff_performance_id)->with('message','You are not authorized to do assessment for this employee');
        }


        //return redirect('staff_performances/'.$staff_performance->id);
    }



    private function validateFirstQuoterAssessment(){

        return request()->validate([
            'staff_performance_id' => 'required',
            'first_quoter_spv_marks' => 'required|numeric|min:0|max:100',
            'first_quoter_spv_comments' => 'required',
        ]);

    }


    private function validateSecondQuoterAssessment(){

        return request()->validate([
            'staff_performance_id' => 'required',
            'second_quoter_spv_marks' => 'required|numeric|min:0|max:100',
            'second_quoter_spv_comments' => 'required',
        ]);

    }


    private function validateThirdQuoterAssessment(){

        return request()->validate([
            'staff_performance_id' => 'required',
            'third_quoter_spv_marks' => 'required|numeric|min:0|max:100',
            'third_quoter_spv_comments' => 'required',
        ]);

    }


    private function validateFourthQuoterAssessment(){

        return request()->validate([
            'staff_performance_id' => 'required',
            'fourth_quoter_spv_marks' => 'required|numeric|min:0|max:100',
            'fourth_quoter_spv_comments' => 'required',
        ]);

    }
}
