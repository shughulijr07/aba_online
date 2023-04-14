<?php

namespace App\Http\Controllers;


use App\Events\PerformanceObjectivesApprovedByHRMEvent;
use App\Events\PerformanceObjectivesApprovedByMDEvent;
use App\Events\PerformanceObjectivesApprovedBySupervisorEvent;
use App\Events\PerformanceObjectivesRejectedEvent;
use App\Events\PerformanceObjectivesReturnedEvent;
use App\Events\PerformanceObjectivesSubmittedEvent;
use App\Models\GeneralSetting;
use App\Models\PerformanceObjective;
use App\Models\PerformanceObjectiveLine;
use App\Models\Staff;
use App\Models\PerformanceObjectiveApproval;
use App\Models\PerformanceObjectiveChangedSupervisor;
use App\Models\PerformanceObjectiveReject;
use App\Models\PerformanceObjectiveReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PerformanceObjectivesController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }



    public function index($status)
    {
        if (Gate::denies('access',['performance_objectives','view'])){
            abort(403, 'Access Denied');
        }

        $employee_id = auth()->user()->staff->id; 

        //for my performance objectives
        $performance_objectives = PerformanceObjective::where('status', '=', $status)
            ->where('staff_id','=',$employee_id)->where('year','=',date('Y'))->get();

        $model_name = 'performance_objective';
        $controller_name = 'performance_objectives';
        $view_type = 'index';


        $performance_objective_statuses = PerformanceObjective::$performance_objective_statuses;
        $performance_objective_status   = $status;

        return view('performance_objectives.index',
            compact('performance_objectives', 'performance_objective_statuses', 'performance_objective_status',
                'model_name', 'controller_name', 'view_type'));
    }


    public function adminIndex( $status )
    {
        if (Gate::denies('access',['performance_objectives','view'])){
            abort(403, 'Access Denied');
        }

        $employee_id = auth()->user()->staff->id;
        $current_user_role = auth()->user()->role_id;

        $performance_objectives = [];

        if( in_array($current_user_role, [1,2,3])){ //for Super Administrator or HRM or MD
            $performance_objectives = PerformanceObjective::where('status', '=', $status)->get();
        }

        if( in_array($current_user_role,[5,9])){ // for SPV
            $performance_objectives = PerformanceObjective::where('status', '=', $status)
                ->where('responsible_spv','=',$employee_id)->where('year','=',date('Y'))->get();
        }

        $model_name = 'performance_objective';
        $controller_name = 'performance_objectives';
        $view_type = 'index';


        $performance_objective_statuses = PerformanceObjective::$performance_objective_statuses;
        $performance_objective_status = $status;

        return view('performance_objectives.admin_index',
            compact('performance_objectives', 'performance_objective_statuses','performance_objective_status',
                'model_name', 'controller_name', 'view_type'));

    }


    public function myPerformanceObjectivesIndex()
    {

        $year = date('Y');
        $initial_year = 2019;

        $model_name = 'performance_objective';
        $controller_name = 'performance_objectives';
        $view_type = 'index';

        return view('my_performance_objectives.index',
            compact(   'year', 'initial_year',
                'model_name', 'controller_name', 'view_type'));

    }


    public function myPerformanceObjectivesList(Request $request)
    {
        $data = $request->all();
        $year = $data['year'];
        $staff = auth()->user()->staff;
        $staff_id = $staff->id;
        $months = PerformanceObjective::$months;
        $performance_objective_statuses = PerformanceObjective::$performance_objective_statuses;


        $query = PerformanceObjective::query();
        $query = $query->where('staff_id', '=', $staff_id);
        $query = $query->where('status', '=', '50');//approved
        $query = $query->where('year', '=', $year);

        $performance_objectives = $query->get();

        $model_name = 'performance_objective';
        $controller_name = 'performance_objectives';
        $view_type = 'index';

        return view('my_performance_objectives.list',
            compact(  'performance_objectives',  'year','months','performance_objective_statuses',
                'model_name', 'controller_name', 'view_type'));

    }


    public function create()
    {
        if (Gate::denies('access',['performance_objectives','store'])){
            abort(403, 'Access Denied');
        }


        $current_logged_staff = auth()->user()->staff;
        $performance_objective = new PerformanceObjective();

        //get system settings
        $system_settings = GeneralSetting::find(1);
        $supervisors_mode = $system_settings->supervisors_mode;
        $supervisor_id = $current_logged_staff->supervisor_id;
        //override supervisor settings if no supervisor have been assigned to this staff
        if($supervisor_id == null || $supervisor_id == ''){ $supervisors_mode = '2'; } //'2' => 'Selection From List'


        $supervisors = Staff::get_supervisors($supervisors_mode);
        $responsible_spv = '';
        $year = date('Y');


        $employee_name = ucwords($current_logged_staff->first_name.' '.$current_logged_staff->last_name);
        $department_name = $current_logged_staff->department->name;



        $model_name = 'performance_objective';
        $controller_name = 'performance_objectives';
        $view_type = 'create';

        return view('performance_objectives.create',
            compact( 'performance_objective','supervisors', 'responsible_spv', 'employee_name', 'department_name',
                'supervisors_mode','year',
                'model_name', 'controller_name', 'view_type'));

    }


    public function store(Request $request)
    {
        if (Gate::denies('access',['performance_objectives','store'])){
            abort(403, 'Access Denied');
        }

        $data = $request->all();
        $action = $data['action'];

        $header_data = $this->validateSubmittedObjective();
        $responsible_spv = $header_data['responsible_spv'];

        //get objectives header data
        $staff_id = auth()->user()->staff->id;
        $year = date('Y');


        //check if there are objective submitted by user for this year
        $objectives = PerformanceObjective::get_staff_objectives_by_year($staff_id, $year);


        if($objectives == null){

            //save objectives header
            $performance_objective  = new PerformanceObjective();
            $performance_objective->staff_id = $staff_id;
            $performance_objective->year = $year;
            $performance_objective->responsible_spv = $responsible_spv;
            $performance_objective->status = '10'; //add to drafts
            $performance_objective->transferred_to_nav = 'no';
            $performance_objective->save();


            //save objectives lines
            //get objectives lines
            $lines = array_slice($data, 5);
            $required_lines = [];

            foreach ($lines as $key=>$data){
                $key_elements = explode('_',$key);
                if( in_array('line',$key_elements)){ $required_lines[$key] =$data; }
            }

            $lines = $required_lines;

            //save travel request lines

            $performance_objective_line = new PerformanceObjectiveLine();
            $performance_objective_line->performance_objective_id = $performance_objective->id;
            $performance_objective_line->data = json_encode($lines);
            $performance_objective_line->save();

            if( $action == 'Submit Objectives' ){

                $performance_objective->status = '20'; //'20' => 'Waiting For Supervisor Approval'
                $performance_objective->save();

                //send notifications
                if( isset($performance_objective->id) ){
                    //dump('inatuma email');
                    event( new PerformanceObjectivesSubmittedEvent($performance_objective));
                }

            }else{

                $performance_objective->status = '10'; //'10' => 'Draft'
                $performance_objective->save();
            }

            return redirect('performance_objective/'.$performance_objective->id);

        }else{

            $message = 'You have already Submitted Objectives for this year';

             redirect('set_objectives')->with('message', $message);

        }



    }


    public function show($id)
    {
        if (Gate::denies('access',['performance_objectives','view'])){
            abort(403, 'Access Denied');
        }

        //get system settings
        $system_settings = GeneralSetting::find(1);
        $supervisors_mode = $system_settings->supervisors_mode;


        $current_logged_staff = auth()->user()->staff;

        //dd($performance_objective_lines);
        $performance_objective = PerformanceObjective::find($id); 

        $lines = [];

        if( count($performance_objective->lines)>0 ){
            $lines = ($performance_objective->lines->last())->data;
            $lines = json_decode($lines,true);
        }

        $supervisor_id = $performance_objective->responsible_spv;
        //override supervisor settings if no supervisor have been assigned to this staff
        if($supervisor_id == null || $supervisor_id == ''){ $supervisors_mode = '2'; } //'2' => 'Selection From List'

        $supervisors =  Staff::get_supervisors($supervisors_mode);
        $performance_objective_statuses = PerformanceObjective::$performance_objective_statuses;
        $responsible_spv = $performance_objective->responsible_spv;
        $supervisor = Staff::find($responsible_spv);
        $spv_name = ucwords($supervisor->first_name.' '.$supervisor->last_name);


        $employee_name = ucwords($performance_objective->staff->first_name.' '.$performance_objective->staff->last_name);
        $department_name = $performance_objective->staff->department->name;
        $year = $performance_objective->year;


        $model_name = 'performance_objective';
        $controller_name = 'performance_objectives';
        $view_type = 'show';
        $view_type2 = 'show';

        $rejection_reason = '';
        $comments = '';
        //dd($performance_objective);

        return view('performance_objectives.show',
            compact( 'performance_objective','supervisors', 'responsible_spv', 'spv_name', 'employee_name',
                'lines','performance_objective_statuses', 'supervisors_mode','department_name','year',
                'rejection_reason','travel_request_modification_reason','comments',
                'model_name', 'controller_name', 'view_type','view_type2'));

    }


    public function showAdmin($id)
    {
        if (Gate::denies('access',['performance_objectives','view'])){
            abort(403, 'Access Denied');
        }

        //get system settings
        $system_settings = GeneralSetting::find(1);
        $supervisors_mode = $system_settings->supervisors_mode;


        $current_logged_staff = auth()->user()->staff;
        $user_role = auth()->user()->role_id;

        //dd($performance_objective_lines);
        $performance_objective = PerformanceObjective::find($id);

        $lines = [];

        if( count($performance_objective->lines)>0 ){
            $lines = ($performance_objective->lines->last())->data;
            $lines = json_decode($lines,true);
        }

        $supervisor_id = $performance_objective->responsible_spv;
        //override supervisor settings if no supervisor have been assigned to this staff
        if($supervisor_id == null || $supervisor_id == ''){ $supervisors_mode = '2'; } //'2' => 'Selection From List'

        $supervisors =  Staff::get_supervisors($supervisors_mode);
        $performance_objective_statuses = PerformanceObjective::$performance_objective_statuses;
        $responsible_spv = $performance_objective->responsible_spv;
        $supervisor = Staff::find($responsible_spv);
        $spv_name = ucwords($supervisor->first_name.' '.$supervisor->last_name);


        $employee_name = ucwords($performance_objective->staff->first_name.' '.$performance_objective->staff->last_name);
        $department_name = $performance_objective->staff->department->name;
        $year = $performance_objective->year;


        $model_name = 'performance_objective';
        $controller_name = 'performance_objectives';
        $view_type = 'show';
        $view_type2 = 'show_admin';

        $rejection_reason = '';
        $supervisor_change_reason = '';
        $comments = '';
        //dd($performance_objective);

        return view('performance_objectives.show_admin',
            compact( 'performance_objective','supervisors', 'responsible_spv', 'spv_name', 'employee_name',
                'lines','performance_objective_statuses', 'supervisors_mode','department_name','year',
                'rejection_reason','travel_request_modification_reason','comments','user_role','supervisor_change_reason',
                'model_name', 'controller_name', 'view_type','view_type2'));
    }


    public function update($performance_objective_id)
    {
        if (Gate::denies('access',['performance_objectives','edit'])){
            abort(403, 'Access Denied');
        }

        $data = request()->all();
        $action = $data['action'];


        $header_data = $this->validateSubmittedObjective();
        $responsible_spv = $header_data['responsible_spv'];

        //get objectives header data
        $staff_id = auth()->user()->staff->id;
        $year = date('Y');

        //save objectives header
        $performance_objective  = PerformanceObjective::find($performance_objective_id);
        $performance_objective->staff_id = $staff_id;
        $performance_objective->year = $year;
        $performance_objective->responsible_spv = $responsible_spv;
        $performance_objective->status = '20'; //waiting for SPV
        $performance_objective->transferred_to_nav = 'no';
        $performance_objective->save();


        //save objectives lines
        //get objectives lines
        $lines = array_slice($data, 6);
        array_pop($lines);

        $required_lines = [];

        foreach ($lines as $key=>$data){
            $key_elements = explode('_',$key);
            if( in_array('line',$key_elements)){ $required_lines[$key] =$data; }
        }

        $lines = $required_lines;

        //save travel request lines
        $performance_objective_line = $performance_objective->lines->last();
        $performance_objective_line->data = json_encode($lines);
        $performance_objective_line->save();

        if( $action == 'Submit Objectives' ){

            $performance_objective->status = '20'; //'20' => 'Waiting For Supervisor Approval'
            $performance_objective->save();

            //send notifications  -- the event have errors
            if( isset($performance_objective->id) ){
                //dump('inatuma email');
                event( new PerformanceObjectivesSubmittedEvent($performance_objective));
            }

        }else{

            $performance_objective->status = '10'; //'10' => 'Draft'
            $performance_objective->save();
        }

        return redirect('performance_objective/'.$performance_objective->id);


    }


    public function destroy($id)
    {
        //
    }


    /************************ OBJECTIVES APPROVING *******************/


    public function approvePerformanceObjectives(Request $request){

        if (Gate::denies('access',['approve_performance_objectives','edit'])){
            abort(403, 'You Are Not Allowed To Approve These Objectives');
        }

        $data = $this->validateApprovalRequest();
        $performance_objective_id= $data['performance_objective_id'];
        $comments = $data['comments'];

        return $this->approveSubmittedObjectives($performance_objective_id,$comments);

    }


    public function approveSubmittedObjectives($performance_objective_id,$comments = ''){

        if (Gate::denies('access',['approve_performance_objectives','edit'])){
            abort(403, 'You Are Not Allowed To Approve These Objectives');
        }

        $current_user_role = auth()->user()->role_id;
        $current_user_staff_id = auth()->user()->staff->id;

        $performance_objective = PerformanceObjective::find($performance_objective_id);
        $performance_objective_status = $performance_objective->status;
        $performance_objective_spv = $performance_objective->responsible_spv;



        if( in_array($current_user_role, [2,3,4,5,9]) && $performance_objective_status == 20 ){ // for MD, HRM, ACC, FD
            //Waiting For Supervisor Approval, accountant, hrm, FD and MD can be supervisors thus they can return performance objectives
            // from staff they are supervising

                $new_performance_objective_status = 30;//'Waiting For HRM Approval'
                $approval_level = 'spv';

                if($performance_objective_spv == $current_user_staff_id){ //approve only if the current user is the supervisor assigned to approve the request

                    //update performance_objective status
                    $performance_objective->status = $new_performance_objective_status;
                    $performance_objective->save();

                    //record performance_objective approval
                    $approval = new PerformanceObjectiveApproval();
                    $approval->performance_objective_id = $performance_objective_id;
                    $approval->level = $approval_level;
                    $approval->done_by = $current_user_staff_id;
                    $approval->comments = $comments;
                    $approval->save();

                    //send email notifications
                    event( new PerformanceObjectivesApprovedBySupervisorEvent($performance_objective));

                    $message = 'Objectives Approved successfully.';

                    return redirect('performance_objective_admin/'.$performance_objective_id)->with('message',$message);
                }
                else{

                    $message = 'You are not authorised to approve this performance objective.';

                    return redirect('performance_objective_admin/'.$performance_objective_id)->with('message',$message);

                }

        }

        if($current_user_role == 3 && $performance_objective_status == 30){ //for HRM


                $approval_level = 'hrm';
                $new_performance_objective_status = 50;// '50' => 'Approved' //skip MD Approval

                //update performance_objective status
                $performance_objective->status = $new_performance_objective_status;
                $performance_objective->save();

                //record performance_objective approval
                $approval = new PerformanceObjectiveApproval();
                $approval->performance_objective_id = $performance_objective_id;
                $approval->level = $approval_level;
                $approval->done_by = $current_user_staff_id;
                $approval->comments = $comments;
                $approval->save();

                //send email notifications
                event( new PerformanceObjectivesApprovedByHRMEvent($performance_objective));
            

                $message = 'Objectives Approved successfully.';

                return redirect('performance_objective_admin/'.$performance_objective_id)->with('message',$message);

        }

        if($current_user_role == 2 && $performance_objective_status == 40){ //for MD

                $approval_level = 'md';
                $new_performance_objective_status = 50;// '50' => 'Approved',

                //update performance_objective status
                $performance_objective->status = $new_performance_objective_status;
                $performance_objective->save();

                //record performance_objective approval
                $approval = new PerformanceObjectiveApproval();
                $approval->performance_objective_id = $performance_objective_id;
                $approval->level = $approval_level;
                $approval->done_by = $current_user_staff_id;
                $approval->comments = $comments;
                $approval->save();

                //send email notifications
                event( new PerformanceObjectivesApprovedByMDEvent($performance_objective));

                $message = 'Objectives Approved successfully.';

                return redirect('performance_objective_admin/'.$performance_objective_id)->with('message',$message);



        }

        if($current_user_role == 1){ //for Super Administrator

            

            if( in_array($performance_objective_status, [20,30,40]) ){ //Waiting For Supervisor,HRM or MD Approval

                $new_performance_objective_status = 30;//default
                $approval_level = 'spv';//default

                if($performance_objective_status == 20){
                    $new_performance_objective_status = 30;//'Waiting For HRM Approval'
                    $approval_level = 'spv';
                }

                if($performance_objective_status == 30){
                    $new_performance_objective_status = 50;//'Approved' skip 'Waiting For MD Approval'
                    $approval_level = 'hrm';
                }

                if($performance_objective_status == 40){
                    $new_performance_objective_status = 50;//Approved
                    $approval_level = 'md';
                }

                //update performance_objective status
                $performance_objective->status = $new_performance_objective_status;
                $performance_objective->save();

                //record performance_objective approval
                $approval = new PerformanceObjectiveApproval();
                $approval->performance_objective_id = $performance_objective_id;
                $approval->level = $approval_level;
                $approval->done_by = $current_user_staff_id;
                $approval->comments = $comments;
                $approval->save();

                //send email notification

                $message = 'Objectives Approved successfully.';

            }


            elseif($performance_objective_status == 50){ //Approved

                $message = 'Objectives Have Already Been Approved.';

            }

            else{

                $message = 'You are not allowed to approve these Objectives.';
            }

            return redirect('performance_objective_admin/'.$performance_objective_id)->with('message',$message);
        }

        else{

            $message = 'You are not Authorized to approve these Objectives.';

            return redirect('performance_objective_admin/'.$performance_objective_id)->with('message',$message);

        }

    }


    public function returnPerformanceObjectives(Request $request){

        if (Gate::denies('access',['return_performance_objectives','edit'])){
            abort(403, 'You Are Not Allowed To Return These Objectives');
        }


        $data = $this->validateReturnRequest();
        $performance_objective_id= $data['performance_objective_id'];
        $comments = $data['comments'];
        $current_user_role = auth()->user()->role_id;
        $current_user_staff_id = auth()->user()->staff->id;

        $performance_objective = PerformanceObjective::find($performance_objective_id);
        $performance_objective_status = $performance_objective->status;
        $performance_objective_spv = $performance_objective->responsible_spv;


        if( in_array($current_user_role, [2,3,4,5,9]) && $performance_objective_status == 20 ){ // for MD, HRM, ACC, FD
            //Waiting For Supervisor Approval, accountant, hrm and MD can be supervisors thus they can return performance objectives
            // from staff they are supervising

            if($performance_objective_spv == $current_user_staff_id){ //approve only if the current user is the supervisor assigned to approve the request

                $new_performance_objective_status = 0;//'Returned For Correction'
                $return_level = 'spv';

                //update performance_objective status
                $performance_objective->status = $new_performance_objective_status;
                $performance_objective->save();

                //record performance_objective return
                $return = new PerformanceObjectiveReturn();
                $return->performance_objective_id = $performance_objective_id;
                $return->level = $return_level;
                $return->done_by = $current_user_staff_id;
                $return->comments = $comments;
                $return->save();

                //send email notifications
                event( new PerformanceObjectivesReturnedEvent($return_level,$performance_objective));

                $message = 'Objectives Returned Successfully.';

                return redirect('performance_objective_admin/'.$performance_objective_id)->with('message',$message);
            }
            else{

                $message = 'You are not authorised to Return these Objectives.';

                return redirect('performance_objective_admin/'.$performance_objective_id)->with('message',$message);

            }

        }

        if($current_user_role == 3 && $performance_objective_status == 30){ //for HRM

            $return_level = 'hrm';
            $new_performance_objective_status = 0;//'Returned For Correction'

            //update performance_objective status
            $performance_objective->status = $new_performance_objective_status;
            $performance_objective->save();

            //record performance_objective approval
            $return = new PerformanceObjectiveReturn();
            $return->performance_objective_id = $performance_objective_id;
            $return->level = $return_level;
            $return->done_by = $current_user_staff_id;
            $return->comments = $comments;
            $return->save();

            //send email notifications
            event( new PerformanceObjectivesReturnedEvent($return_level,$performance_objective));

            $message = 'Objectives Returned Successfully.';

            return redirect('performance_objective_admin/'.$performance_objective_id)->with('message',$message);


        }


        if($current_user_role == 2 && $performance_objective_status == 40){ //for MD

            $return_level = 'md';
            $new_performance_objective_status = 0;//'Returned For Correction'

            //update performance_objective status
            $performance_objective->status = $new_performance_objective_status;
            $performance_objective->save();

            //record performance_objective approval
            $return = new PerformanceObjectiveReturn();
            $return->performance_objective_id = $performance_objective_id;
            $return->level = $return_level;
            $return->done_by = $current_user_staff_id;
            $return->comments = $comments;
            $return->save();

            //send email notifications
            event( new PerformanceObjectivesReturnedEvent($return_level,$performance_objective));

            $message = 'Objectives Returned Successfully.';

            return redirect('performance_objective_admin/'.$performance_objective_id)->with('message',$message);



        }


        if($current_user_role == 1){ //for Super Administrator


            if( in_array($performance_objective_status, [20,30,40]) ){ //Waiting For Supervisor,HRM or MD Approval

                $new_performance_objective_status = 0;
                $return_level = 'spv';

                //update performance_objective status
                $performance_objective->status = $new_performance_objective_status;
                $performance_objective->save();

                //record performance_objective approval
                $return = new PerformanceObjectiveReturn();
                $return->performance_objective_id = $performance_objective_id;
                $return->level = $return_level;
                $return->done_by = $current_user_staff_id;
                $return->comments = $comments;
                $return->save();

                //send email notification to  new supervisor

                $message = 'Objectives Returned successfully.';

            }

            else{

                $message = 'You are not allowed to Return these Objectives.';
            }

            return redirect('performance_objective_admin/'.$performance_objective_id)->with('message',$message);
        }

        else{

            $message = 'You are not Authorized to Return these Objectives.';
            return redirect('performance_objective_admin/'.$performance_objective_id)->with('message',$message);

        }

    }


    public function changeSupervisor(Request $request){

        if (Gate::denies('access',['change_supervisor','edit'])){
            abort(403, 'You Are Not Allowed To Change Supervisor For These Objectives');
        }

        $data = $this->validateChangeSupervisorRequest();
        $performance_objective_id = $data['performance_objective_id'];
        $new_spv = $data['responsible_spv'];
        $reason_for_change = $data['reason'];

        //get old supervisor
        $performance_objective = PerformanceObjective::find($performance_objective_id);
        $old_spv = $performance_objective->responsible_spv;

        //change supervisor, but when you change supervisor also reverse status to previous stage
        try{
            $performance_objective->responsible_spv = $new_spv;
            $performance_objective->status = '20';
            $performance_objective->save();

            $changed_by = auth()->user()->staff->id;

            //record this change
            $spv_change = new PerformanceObjectiveChangedSupervisor();
            $spv_change->performance_objective_id = $performance_objective_id;
            $spv_change->old_spv_id = $old_spv;
            $spv_change->new_spv_id = $new_spv;
            $spv_change->changed_by = $changed_by;
            $spv_change->reason = $reason_for_change;
            $spv_change->save();

            //send email notification to  new supervisor

            $message = 'Supervisor Have been Changed Successfully';

            return redirect('performance_objective_admin/'.$performance_objective_id)->with('message',$message);

        }catch(\Exception $e){
            echo $e->getMessage();
        };

    }


    public function rejectPerformanceObjectives(Request $request){

        if (Gate::denies('access',['reject_performance_objectives','edit'])){
            abort(403, 'You Are Not Allowed To Reject These Objectives');
        }


        $data = $this->validateRejectRequest();
        $performance_objective_id= $data['performance_objective_id'];
        $rejection_reason = $data['rejection_reason'];
        $current_user_role = auth()->user()->role_id;
        $current_user_staff_id = auth()->user()->staff->id;

        $performance_objective = PerformanceObjective::find($performance_objective_id);
        $performance_objective_status = $performance_objective->status;
        $performance_objective_spv = $performance_objective->responsible_spv;


        if( in_array($current_user_role, [2,3,4,5,9]) && $performance_objective_status == 20 ){ // for SPV, HRM and MD
            //Waiting For Supervisor Approval, accountant, hrm and MD can be supervisors thus they can reject performance objectives
            // from staff they are supervising

            if($performance_objective_spv == $current_user_staff_id){ //reject only if the current user is the supervisor assigned to approve the request

                $new_performance_objective_status = 99;//'Rejected'
                $reject_level = 'spv';

                //update performance_objective status
                $performance_objective->status = $new_performance_objective_status;
                $performance_objective->save();

                //record performance_objective approval
                $reject = new PerformanceObjectiveReject();
                $reject->performance_objective_id = $performance_objective_id;
                $reject->level = $reject_level;
                $reject->done_by = $current_user_staff_id;
                $reject->reason = $rejection_reason;
                $reject->save();

                //send email notifications
                event( new PerformanceObjectivesRejectedEvent($reject_level,$performance_objective));

                $message = 'Objectives Rejected Successfully.';

                return redirect('performance_objective_admin/'.$performance_objective_id)->with('message',$message);
            }
            else{

                $message = 'You are not authorised to Reject these Objectives.';

                return redirect('performance_objective_admin/'.$performance_objective_id)->with('message',$message);

            }


        }

        if($current_user_role == 3 && $performance_objective_status == 30){ //for HRM

            $reject_level = 'hrm';
            $new_performance_objective_status = 99;//'Rejected For Correction'

            //update performance_objective status
            $performance_objective->status = $new_performance_objective_status;
            $performance_objective->save();

            //record performance_objective approval
            $reject = new PerformanceObjectiveReject();
            $reject->performance_objective_id = $performance_objective_id;
            $reject->level = $reject_level;
            $reject->done_by = $current_user_staff_id;
            $reject->reason = $rejection_reason;
            $reject->save();

            //send email notifications
            event( new PerformanceObjectivesRejectedEvent($reject_level,$performance_objective));

            $message = 'Objectives Rejected Successfully.';

            return redirect('performance_objective_admin/'.$performance_objective_id)->with('message',$message);



        }

        if($current_user_role == 2 && $performance_objective_status == 40 ){ //for MD

            $reject_level = 'md';
            $new_performance_objective_status = 99;//'Rejected For Correction'

            //update performance_objective status
            $performance_objective->status = $new_performance_objective_status;
            $performance_objective->save();

            //record performance_objective rjection
            $reject = new PerformanceObjectiveReject();
            $reject->performance_objective_id = $performance_objective_id;
            $reject->level = $reject_level;
            $reject->done_by = $current_user_staff_id;
            $reject->reason = $rejection_reason;
            $reject->save();

            //send email notifications
            event( new PerformanceObjectivesRejectedEvent($reject_level,$performance_objective));

            $message = 'Objectives Rejected Successfully.';

            return redirect('performance_objective_admin/'.$performance_objective_id)->with('message',$message);


        }

        if($current_user_role == 1){ //for Super Administrator


            if( in_array($performance_objective_status, [20,30,40,50]) ){ //can reject submitted performance objective at any status

                $new_performance_objective_status = 99;
                $reject_level = 'adm';

                //update performance_objective status
                $performance_objective->status = $new_performance_objective_status;
                $performance_objective->save();

                //record performance_objective approval
                $reject = new PerformanceObjectiveReject();
                $reject->performance_objective_id = $performance_objective_id;
                $reject->level = $reject_level;
                $reject->done_by = $current_user_staff_id;
                $reject->reason = $rejection_reason;
                $reject->save();

                //send email notification
                event( new PerformanceObjectivesRejectedEvent($reject_level,$performance_objective));

                $message = 'Objectives Rejected successfully.';

            }

            else{

                $message = 'You are not allowed to reject these Objectives.';
            }

            return redirect('performance_objective_admin/'.$performance_objective_id)->with('message',$message);
        }

        else{

            $message = 'You are not Authorized to reject these Objectives.';

            return redirect('performance_objective_admin/'.$performance_objective_id)->with('message',$message);

        }

    }




    /********************** VALIDATION SECTION ****************************/

    public function validateSubmittedObjective(){

        return  request()->validate([
            'responsible_spv' =>  'required',
            'year' =>  'required',
        ]);

    }

    public function validateApprovalRequest(){

        return  request()->validate([
            'performance_objective_id' => 'required',
            'comments' =>  'nullable',
        ]);

    }

    public function validateReturnRequest(){

        return  request()->validate([
            'performance_objective_id' => 'required',
            'comments' =>  'required',
        ]);

    }


    public function validateChangeSupervisorRequest(){

        return  request()->validate([
            'responsible_spv' =>  'required',
            'reason' =>  'required',
            'performance_objective_id' => 'required',
        ]);

    }

    public function validateRejectRequest(){

        return  request()->validate([
            'performance_objective_id' => 'required',
            'rejection_reason' =>  'required',
        ]);

    }


}
