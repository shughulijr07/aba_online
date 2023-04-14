<?php

namespace App\Http\Controllers;

use App\Events\LeavePlanApprovedByHRMEvent;
use App\Events\LeavePlanApprovedByMDEvent;
use App\Events\LeavePlanApprovedBySupervisorEvent;
use App\Events\LeavePlanRejectedEvent;
use App\Events\LeavePlanReturnedEvent;
use App\Events\LeavePlanSubmittedEvent;
use App\Models\GeneralSetting;
use App\Models\Holiday;
use App\Models\Leave;
use App\Models\LeaveEntitlement;
use App\Models\LeavePlan;
use App\Models\LeavePlanApproval;
use App\Models\LeavePlanChangedSupervisor;
use App\Models\LeavePlanLine;
use App\Models\LeavePlanReject;
use App\Models\LeavePlanReturn;
use App\Models\LeaveType;
use App\Models\MyFunctions;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class LeavePlansController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {

        if (Gate::denies('access',['leave_plans','view'])){
            abort(403, 'Access Denied');
        }

        $current_staff = auth()->user()->staff;
        $leave_plans = LeavePlan::where('staff_id','=',$current_staff->id)
                                ->where('year','=',date('Y'))->latest()->get();

        $leave_plan_statuses = LeavePlan::$leave_plan_statuses;


        $model_name = 'leave_plan';
        $controller_name = 'leave_plans';
        $view_type = 'index';


        return view('leave_plans.index',
            compact('leave_plans', 'leave_plan_statuses',
                    'model_name', 'controller_name', 'view_type'));


    }


    public function adminIndex( $status )
    {
        if (Gate::denies('access',['leave_plans','view'])){
            abort(403, 'Access Denied');
        }

        $employee_id = auth()->user()->staff->id;
        $current_user_role = auth()->user()->role_id;

        $leave_plans = [];

        if($current_user_role == 1 || $current_user_role == 2 || $current_user_role == 3){ //for Super Administrator or HRM or MD
            $leave_plans = LeavePlan::where('status', '=', $status)->get();
        }

        if($current_user_role == 5){ // for SPV
            $leave_plans = LeavePlan::where('status', '=', $status)->where('responsible_spv','=',$employee_id)->get();
        }

        $model_name = 'leave_plan';
        $controller_name = 'leave_plans';
        $view_type = 'index';


        $leave_plan_statuses = LeavePlan::$leave_plan_statuses;
        $leave_plan_status = $status;

        return view('leave_plans.index_admin',
            compact('leave_plans', 'leave_plan_statuses','leave_plan_status',
                'model_name', 'controller_name', 'view_type'));
    }


    public function create()
    {
        if (Gate::denies('access',['leave_plans','store'])){
            abort(403, 'Access Denied');
        }

        $current_staff = auth()->user()->staff;
        $leave_plan = LeavePlan::where('staff_id','=',$current_staff->id)
                                ->where('year','=',date('Y'))->first();

        $leave_plan_lines = [];
        if( isset($leave_plan->id)  && $leave_plan->status != 99){
            if( $leave_plan->status > 10){ // this means it have been submitted already
                return redirect('leave_plans/'.$leave_plan->id);
            }else{
                $leave_plan_lines = $leave_plan->lines;
            }
        }else{
            $leave_plan = new LeavePlan();
        }

        //get system settings
        $system_settings = GeneralSetting::find(1);
        $supervisors_mode = $system_settings->supervisors_mode;
        $include_holidays_in_leave = $system_settings->include_holidays_in_leave;
        $include_weekends_in_leave = $system_settings->include_weekends_in_leave;


        $supervisor_id = $current_staff->supervisor_id;
        //override supervisor settings if no supervisor have been assigned to this staff
        if($supervisor_id == null || $supervisor_id == ''){ $supervisors_mode = '2'; } //'2' => 'Selection From List'

        $staff_id = $current_staff->id;
        $staff_gender = $current_staff->gender;
        $year = date('Y');

        //before anything generate leave entitlement automatically, call the function and it will take care of the rest
        $entitlement = new LeaveEntitlement();
        $entitlement->create_leave_entitlement_for_one_staff($staff_id,$year); // if entitlement exists it will be skipped


        $leave_types = LeaveType::get_active_leave_types()['arrays'];
        $leave_type = '';// default type
        $leaveSupervisors = Staff::get_supervisors($supervisors_mode);
        $responsible_spv = '';
        $include_payment = [];

        $leave_summary = $leave_plan->get_leave_plan_summary_for_staff($staff_id,$year);
        //dd($leave_summary);


        //filter leave types according to gender
        $unfiltered_leave_types = $leave_types;
        if( $staff_gender == 'Male'){ unset($leave_types['maternity_leave']);  unset($leave_types['maternity_leave_2']);}
        elseif( $staff_gender == 'Female'){ unset($leave_types['paternity_leave']); unset($leave_types['maternity_leave_2']);};

        $holidays1 = ( Holiday::get_all_holidays_in_a_year( date('Y')) )['arrays'] ;
        $holidays2 = ( Holiday::get_all_holidays_in_a_year( date('Y')+1) )['arrays'] ;


        $model_name = 'leave_plan';
        $controller_name = 'leave_plans';
        $view_type = 'create';

        return view('leave_plans.create',
            compact( 'leave_plan','leave_plan_lines','leave_types', 'leave_type','leaveSupervisors', 'responsible_spv', 'include_payment',
                'leave_summary','staff_gender','supervisors_mode','unfiltered_leave_types',
                'include_weekends_in_leave','include_holidays_in_leave','holidays1','holidays2',
                'model_name', 'controller_name', 'view_type'));
    }


    public function store(Request $request)
    {
        if (Gate::denies('access',['leave_plans','store'])){
            abort(403, 'Access Denied');
        }

        $data = $this->validateLeaveRequest();
        $staff_id = auth()->user()->staff->id;

        //check if leave plan have been saved before
        $leave_plan = LeavePlan::where('staff_id','=',$staff_id)->where('year','=',date('Y'))->first();
        if( !isset($leave_plan->id)){ // if leave plan does not exist then create it
            $leave_plan = new LeavePlan();
            $leave_plan->staff_id = $staff_id;
            $leave_plan->year = date('Y');
            $leave_plan->status = '10'; // save in drafts
            $leave_plan->responsible_spv = $data['responsible_spv'];
            $leave_plan->save();
        }

        //add line to this leave plan
        $leave_plan_line = new LeavePlanLine();
        $leave_plan_line->leave_plan_id = $leave_plan->id;
        $leave_plan_line->type_of_leave = $data['leave_type'];
        $leave_plan_line->payment       = count($data['include_payment']) == 2 ? 'Include' : 'Do Not Include';
        $leave_plan_line->starting_date = MyFunctions::convert_date_to_mysql_format($data['starting_date']);
        $leave_plan_line->ending_date   = MyFunctions::convert_date_to_mysql_format($data['ending_date']);
        $leave_plan_line->status        = 'Not Taken';


        //for sick_leave supporting document and description is a must
        if( $data['leave_type'] == 'sick_leave' ){
            $data2 = request()->validate([
                'supporting_document'=>'required|mimes:zip,pdf,jpeg,jpg,png|max:3072',
                'description'=>'required'
            ]);


            $supporting_document = $data2['supporting_document'];
            $description = $data2['description'];

            $leave_plan_line->supporting_document = $supporting_document->store('leave_supporting_documents','public');
            $leave_plan_line->description = $description;
        }

        //for other leave types description is a must and supporting document is optional
        elseif( $data['leave_type'] == 'other' ||  $data['leave_type'] == 'compassionate_leave' ){
            $data2 = request()->validate([
                'supporting_document'=>'sometimes|mimes:zip,pdf,jpeg,jpg,png|max:3072',
                'description'=>'required'
            ]);

            if( isset($data2['supporting_document'])){
                $supporting_document = $data2['supporting_document'];
                $leave_plan_line->supporting_document = $supporting_document->store('leave_supporting_documents','public');
            }
            $description = $data2['description'];
            $leave_plan_line->description = $description;
        }
        else{
            $leave_plan_line->description = $data['description'];
        }

        $leave_plan_line->save();

        //return to create page to allow employee to add more lines
        return redirect('/leave_plans/create');

    }


    public function submitLeavePlan($leave_plan_id){

        $leave_plan = LeavePlan::find($leave_plan_id);

        if( isset($leave_plan->id)){
            $leave_plan->status = '20'; //'Waiting For Supervisor Approval'
            $leave_plan->save();

            //send notification
            event( new LeavePlanSubmittedEvent($leave_plan));

            return redirect('leave_plans/'.$leave_plan->id);
        }else{

            return redirect('leave_plans/create');
        }

    }


    public function show(LeavePlan $leavePlan)
    {
        if (Gate::denies('access',['leave_plans','view'])){
            abort(403, 'Access Denied');
        }


        $current_logged_staff = auth()->user()->staff;

        //dd($leave_plan_lines);
        $leave_plan = $leavePlan;
        $leave_plan_lines = $leave_plan->lines;

        //get system settings
        $system_settings = GeneralSetting::find(1);
        $supervisors_mode = $system_settings->supervisors_mode;
        $supervisor_id = $leave_plan->responsible_spv;
        //override supervisor settings if no supervisor have been assigned to this staff
        if($supervisor_id == null || $supervisor_id == ''){ $supervisors_mode = '2'; } //'2' => 'Selection From List'


        $leaveSupervisors =  Staff::get_supervisors($supervisors_mode);
        $leave_plan_statuses = LeavePlan::$leave_plan_statuses;
        $responsible_spv = $leave_plan->responsible_spv;
        $supervisor = Staff::find($responsible_spv);
        $spv_name = ucwords($supervisor->first_name.' '.$supervisor->last_name);


        $months = LeavePlan::$months;
        $leave_types = LeaveType::get_active_leave_types()['arrays'];

        $employee_name = ucwords($leave_plan->staff->first_name.' '.$leave_plan->staff->last_name);

        $model_name = 'leave_plan';
        $controller_name = 'leave_plans';
        $view_type = 'show';

        $rejection_reason = '';//we will write a code which will get rejection reason, incase the request have been rejected
        $leave_plan_modification_reason = '';
        $supervisor_change_reason = '';
        $comments = '';
        //dd($leave_plan);

        return view('leave_plans.show',
            compact( 'leave_plan','leaveSupervisors', 'responsible_spv', 'spv_name', 'employee_name',
                'leave_plan_lines','leave_plan_statuses','leave_types', 'supervisors_mode',
                'rejection_reason','leave_plan_modification_reason', 'supervisor_change_reason','comments',
                'model_name', 'controller_name', 'view_type'));

    }


    public function showById($leave_plan_id)
    {
        if (Gate::denies('access',['leave_plans','view'])){
            abort(403, 'Access Denied');
        }

        $current_logged_staff = auth()->user()->staff;

        //dd($leave_plan_lines);
        $leave_plan = LeavePlan::find($leave_plan_id);
        $leave_plan_lines = $leave_plan->lines;

        //get system settings
        $system_settings = GeneralSetting::find(1);
        $supervisors_mode = $system_settings->supervisors_mode;
        $supervisor_id = $leave_plan->responsible_spv;
        //override supervisor settings if no supervisor have been assigned to this staff
        if($supervisor_id == null || $supervisor_id == ''){ $supervisors_mode = '2'; } //'2' => 'Selection From List'


        $leaveSupervisors =  Staff::get_supervisors($supervisors_mode);
        $leave_plan_statuses = LeavePlan::$leave_plan_statuses;
        $responsible_spv = $leave_plan->responsible_spv;
        $supervisor = Staff::find($responsible_spv);
        $spv_name = ucwords($supervisor->first_name.' '.$supervisor->last_name);


        $months = LeavePlan::$months;
        $leave_types = LeaveType::get_active_leave_types()['arrays'];

        $employee_name = ucwords($leave_plan->staff->first_name.' '.$leave_plan->staff->last_name);

        $model_name = 'leave_plan';
        $controller_name = 'leave_plans';
        $view_type = 'show';

        $rejection_reason = '';//we will write a code which will get rejection reason, incase the request have been rejected
        $leave_plan_modification_reason = '';
        $supervisor_change_reason = '';
        $comments = '';
        //dd($leave_plan);

        return view('leave_plans.show',
            compact( 'leave_plan','leaveSupervisors', 'responsible_spv', 'spv_name', 'employee_name',
                'leave_plan_lines','leave_plan_statuses','leave_types', 'supervisors_mode',
                'rejection_reason','leave_plan_modification_reason', 'supervisor_change_reason','comments',
                'model_name', 'controller_name', 'view_type'));

    }


    public function showAdmin($id){
        if (Gate::denies('access',['leave_plans','view'])){
            abort(403, 'Access Denied');
        }

        $current_logged_user = auth()->user();
        $current_logged_staff = $current_logged_user->staff;
        $user_role = $current_logged_user->role_id;

        //dd($leave_plan_lines);
        $leave_plan = LeavePlan::find($id);
        $leave_plan_lines = $leave_plan->lines;

        //get system settings
        $system_settings = GeneralSetting::find(1);
        $supervisors_mode = $system_settings->supervisors_mode;
        $supervisor_id = $leave_plan->responsible_spv;
        //override supervisor settings if no supervisor have been assigned to this staff
        if($supervisor_id == null || $supervisor_id == ''){ $supervisors_mode = '2'; } //'2' => 'Selection From List'


        $leave_types = LeaveType::get_active_leave_types()['arrays'];

        $leaveSupervisors =  Staff::get_supervisors_list();
        $leave_plan_statuses = LeavePlan::$leave_plan_statuses;
        $responsible_spv = $leave_plan->responsible_spv;
        $supervisor = Staff::find($responsible_spv);
        $spv_name = ucwords($supervisor->first_name.' '.$supervisor->last_name);
        $months = LeavePlan::$months;


        $employee_name = ucwords($leave_plan->staff->first_name.' '.$leave_plan->staff->last_name);

        $leave_plan_obj = new LeavePlan();
        $leave_summary = $leave_plan_obj->get_leave_plan_summary_for_staff($leave_plan->staff->id,$leave_plan->staff->year);

        $model_name = 'leave_plan';
        $controller_name = 'leave_plans';
        $view_type = 'show';

        $rejection_reason = '';//we will write a code which will get rejection reason, incase the request have been rejected
        $leave_plan_modification_reason = '';
        $supervisor_change_reason = '';
        $comments = '';
        //dd($leave_plan);

        return view('leave_plans.show_admin',
            compact( 'leave_plan','leaveSupervisors', 'responsible_spv', 'spv_name', 'employee_name',
                'leave_plan_lines','leave_plan_statuses','user_role','leave_types', 'supervisors_mode',
                'rejection_reason','leave_plan_modification_reason', 'supervisor_change_reason','comments',
                'leave_summary',
                'model_name', 'controller_name', 'view_type'));

    }


    public function edit(LeavePlan $leavePlan)
    {
        //
    }


    public function update(Request $request, LeavePlan $leavePlan)
    {
        //
    }


    public function destroy(LeavePlan $leavePlan)
    {
        //
    }


    public function removeLine($line_id){

        $leave_plan_line = LeavePlanLine::find($line_id);
        $leave_plan      = $leave_plan_line->leave_plan;

        //delete line only if it belongs to the current user and leave status is in draft
        //user is not allowed to delete lines of a submitted plan
        if( ($leave_plan->staff->id == auth()->user()->staff->id) && ( $leave_plan->status == '10' || $leave_plan->status == '10') ){
             $leave_plan_line->delete();
        }

        return redirect('leave_plans/create');

    }


    public function leavePlansSummary($mode){

        $leave_plans = LeavePlan::where('year','=',date('Y'))
                                ->where('status','=','50')->get();

        $months = LeavePlan::$months;
        $leave_types = LeaveType::get_active_leave_types()['arrays'];

        //categorize leave plan lines by month
        $leave_plan_lines_in_all_months = [
            '1'  => [],
            '2'  => [],
            '3'  => [],
            '4'  => [],
            '5'  => [],
            '6'  => [],
            '7'  => [],
            '8'  => [],
            '9'  => [],
            '10' => [],
            '11' => [],
            '12' => [],
        ];

        if( count($leave_plans) > 0 ){

            foreach($leave_plans as $leave_plan){
                $lines = $leave_plan->lines;
                if( count($lines) > 0 ){
                    foreach ($lines as $line){
                        $starting_date = $line->starting_date;
                        $month = date('m', strtotime($starting_date));
                        $month = (int) $month;

                        switch ($month){

                            case 1  : $leave_plan_lines_in_all_months['1'][]  = $line; break;
                            case 2  : $leave_plan_lines_in_all_months['2'][]  = $line; break;
                            case 3  : $leave_plan_lines_in_all_months['3'][]  = $line; break;
                            case 4  : $leave_plan_lines_in_all_months['4'][]  = $line; break;
                            case 5  : $leave_plan_lines_in_all_months['5'][]  = $line; break;
                            case 6  : $leave_plan_lines_in_all_months['6'][]  = $line; break;
                            case 7  : $leave_plan_lines_in_all_months['7'][]  = $line; break;
                            case 8  : $leave_plan_lines_in_all_months['8'][]  = $line; break;
                            case 9  : $leave_plan_lines_in_all_months['9'][]  = $line; break;
                            case 10 : $leave_plan_lines_in_all_months['10'][] = $line; break;
                            case 11 : $leave_plan_lines_in_all_months['11'][] = $line; break;
                            case 12 : $leave_plan_lines_in_all_months['12'][] = $line; break;

                        }

                    }
                }
            }

        }
        //dd($leave_plan_lines_in_all_months);

        return view('leave_plans.summary',
            compact('leave_plan_lines_in_all_months','months', 'leave_types','mode'));
    }
    
    
    
    /****************************** APPROVAL SECTION  *******************************/
    
    public function approveLeavePlan(Request $request){

        if (Gate::denies('access',['approve_leave_plan','edit'])){
            abort(403, 'You Are Not Allowed To Approve This Leave Plan');
        }

        $data = $this->validateApprovalRequest();
        $leave_plan_id= $data['leave_plan_id'];
        $comments = $data['comments'];

        return $this->approveSubmittedLeavePlan($leave_plan_id,$comments);


    }


    public function approveSubmittedLeavePlan($leave_plan_id,$comments = ''){

        if (Gate::denies('access',['approve_leave_plan','edit'])){
            abort(403, 'You Are Not Allowed To Approve This Leave Plan');
        }

        $current_user_role = auth()->user()->role_id;
        $current_user_staff_id = auth()->user()->staff->id;

        $leave_plan = LeavePlan::find($leave_plan_id);
        $leave_plan_status = $leave_plan->status;
        $leave_plan_spv = $leave_plan->responsible_spv;


        if( ($current_user_role == 2 || $current_user_role == 3 || $current_user_role == 4 || $current_user_role == 5) && $leave_plan_status == 20 ){ // for SPV, HRM and MD
            //Waiting For Supervisor Approval, accountant, hrm and MD can be supervisors thus they can approve leave plans
            // from staff they are supervising

            $new_leave_plan_status = 30;//'Waiting For HRM Approval'
            $approval_level = 'spv';

            if($leave_plan_spv == $current_user_staff_id){ //approve only if the current user is the supervisor assigned to approve the request

                //update leave_plan status
                $leave_plan->status = $new_leave_plan_status;
                $leave_plan->save();

                //record leave_plan approval
                $approval = new LeavePlanApproval();
                $approval->leave_plan_id = $leave_plan_id;
                $approval->level = $approval_level;
                $approval->done_by = $current_user_staff_id;
                $approval->comments = $comments;
                $approval->save();

                //send email notifications
                event( new LeavePlanApprovedBySupervisorEvent($leave_plan));

                $message = 'Leave Plan Approved successfully.';

                return redirect('leave_plan_admin/'.$leave_plan_id)->with('message',$message);
            }
            else{

                $message = 'You are not authorised to approve this time sheet.';

                return redirect('leave_plan_admin/'.$leave_plan_id)->with('message',$message);

            }

        }

        if($current_user_role == 3 && $leave_plan_status == 30){ //for HRM

            $approval_level = 'hrm';
            $new_leave_plan_status = 50;// '50' => 'Approved'

            //update leave_plan status
            $leave_plan->status = $new_leave_plan_status;
            $leave_plan->save();

            //record leave_plan approval
            $approval = new LeavePlanApproval();
            $approval->leave_plan_id = $leave_plan_id;
            $approval->level = $approval_level;
            $approval->done_by = $current_user_staff_id;
            $approval->comments = $comments;
            $approval->save();

            //send email notifications
            event( new LeavePlanApprovedByHRMEvent($leave_plan));

            $message = 'Leave Plan Approved successfully.';

            return redirect('leave_plan_admin/'.$leave_plan_id)->with('message',$message);

        }

        if($current_user_role == 2 && $leave_plan_status == 40){ //for MD

            $approval_level = 'md';
            $new_leave_plan_status = 50;// '50' => 'Approved',

            //update leave_plan status
            $leave_plan->status = $new_leave_plan_status;
            $leave_plan->save();

            //record leave_plan approval
            $approval = new LeavePlanApproval();
            $approval->leave_plan_id = $leave_plan_id;
            $approval->level = $approval_level;
            $approval->done_by = $current_user_staff_id;
            $approval->comments = $comments;
            $approval->save();

            //send email notifications
            event( new LeavePlanApprovedByMDEvent($leave_plan));

            $message = 'Leave Plan Approved successfully.';

            return redirect('leave_plan_admin/'.$leave_plan_id)->with('message',$message);

        }

        if($current_user_role == 1){ //for Super Administrator


            if($leave_plan_status == 20 || $leave_plan_status == 30 || $leave_plan_status == 40){ //Waiting For Supervisor,HRM or MD Approval

                $new_leave_plan_status = 30;//default
                $approval_level = 'spv';//default

                if($leave_plan_status == 20){
                    $new_leave_plan_status = 30;//'Waiting For HRM Approval'
                    $approval_level = 'spv';
                }

                if($leave_plan_status == 30){
                    $new_leave_plan_status = 50;//'Approved'
                    $approval_level = 'hrm';
                }

                if($leave_plan_status == 40){
                    $new_leave_plan_status = 50;//Approved
                    $approval_level = 'md';
                }

                //update leave_plan status
                $leave_plan->status = $new_leave_plan_status;
                $leave_plan->save();

                //record leave_plan approval
                $approval = new LeavePlanApproval();
                $approval->leave_plan_id = $leave_plan_id;
                $approval->level = $approval_level;
                $approval->done_by = $current_user_staff_id;
                $approval->comments = $comments;
                $approval->save();

                //send email notification

                $message = 'Leave Plan Approved successfully.';

            }


            elseif($leave_plan_status == 50){ //Approved

                $message = 'Leave Plan Have Already Been Approved.';

            }

            else{

                $message = 'You are not allowed to approve this Leave Plan.';
            }

            return redirect('leave_plan_admin/'.$leave_plan_id)->with('message',$message);
        }

        else{

            $message = 'You are not Authorized to approve this Leave Plan.';

            return redirect('leave_plan_admin/'.$leave_plan_id)->with('message',$message);

        }

    }


    public function returnLeavePlan(Request $request){

        if (Gate::denies('access',['return_leave_plan','edit'])){
            abort(403, 'You Are Not Allowed To Return This Leave Plan');
        }


        $data = $this->validateReturnRequest();
        $leave_plan_id= $data['leave_plan_id'];
        $comments = $data['comments'];
        $current_user_role = auth()->user()->role_id;
        $current_user_staff_id = auth()->user()->staff->id;

        $leave_plan = LeavePlan::find($leave_plan_id);
        $leave_plan_status = $leave_plan->status;
        $leave_plan_spv = $leave_plan->responsible_spv;



        if( ($current_user_role == 2 || $current_user_role == 3 || $current_user_role == 4 || $current_user_role == 5) && $leave_plan_status == 20 ){ // for SPV, HRM and MD
            //Waiting For Supervisor Approval, accountant, hrm and MD can be supervisors thus they can return leave plans
            // from staff they are supervising

            if($leave_plan_spv == $current_user_staff_id){ //approve only if the current user is the supervisor assigned to approve the request

                $new_leave_plan_status = 0;//'Returned For Correction'
                $return_level = 'spv';

                //update leave_plan status
                $leave_plan->status = $new_leave_plan_status;
                $leave_plan->save();

                //record leave_plan return
                $return = new LeavePlanReturn();
                $return->leave_plan_id = $leave_plan_id;
                $return->level = $return_level;
                $return->done_by = $current_user_staff_id;
                $return->comments = $comments;
                $return->save();

                //send email notifications
                event( new LeavePlanReturnedEvent($return_level,$leave_plan));

                $message = 'Leave Plan Returned Successfully.';

                return redirect('leave_plan_admin/'.$leave_plan_id)->with('message',$message);
            }
            else{

                $message = 'You are not authorised to Return this Leave Plan.';

                return redirect('leave_plan_admin/'.$leave_plan_id)->with('message',$message);

            }

        }

        if($current_user_role == 3 && $leave_plan_status == 30){ //for HRM


            $return_level = 'hrm';
            $new_leave_plan_status = 0;//'Returned For Correction'

            //update leave_plan status
            $leave_plan->status = $new_leave_plan_status;
            $leave_plan->save();

            //record leave_plan approval
            $return = new LeavePlanReturn();
            $return->leave_plan_id = $leave_plan_id;
            $return->level = $return_level;
            $return->done_by = $current_user_staff_id;
            $return->comments = $comments;
            $return->save();

            //send email notifications
            event( new LeavePlanReturnedEvent($return_level,$leave_plan));

            $message = 'Leave Plan Returned Successfully.';

            return redirect('leave_plan_admin/'.$leave_plan_id)->with('message',$message);


        }

        if($current_user_role == 2 && $leave_plan_status == 40){ //for MD

            $return_level = 'md';
            $new_leave_plan_status = 0;//'Returned For Correction'

            //update leave_plan status
            $leave_plan->status = $new_leave_plan_status;
            $leave_plan->save();

            //record leave_plan approval
            $return = new LeavePlanReturn();
            $return->leave_plan_id = $leave_plan_id;
            $return->level = $return_level;
            $return->done_by = $current_user_staff_id;
            $return->comments = $comments;
            $return->save();

            //send email notifications
            event( new LeavePlanReturnedEvent($return_level,$leave_plan));

            $message = 'Leave Plan Returned Successfully.';

            return redirect('leave_plan_admin/'.$leave_plan_id)->with('message',$message);


        }

        if($current_user_role == 1){ //for Super Administrator


            if($leave_plan_status == 20 || $leave_plan_status == 30 || $leave_plan_status == 40 || $leave_plan_status == 50){ //can return submitted time sheet at any status

                $new_leave_plan_status = 0;
                $return_level = 'spv';

                //update leave_plan status
                $leave_plan->status = $new_leave_plan_status;
                $leave_plan->save();

                //record leave_plan approval
                $return = new LeavePlanReturn();
                $return->leave_plan_id = $leave_plan_id;
                $return->level = $return_level;
                $return->done_by = $current_user_staff_id;
                $return->comments = $comments;
                $return->save();

                //send email notification to  new supervisor

                $message = 'Leave Plan Returned successfully.';

            }

            else{

                $message = 'You are not allowed to Return this Leave Plan.';
            }

            return redirect('leave_plan_admin/'.$leave_plan_id)->with('message',$message);
        }

        else{

            $message = 'You are not Authorized to Return this Leave Plan.';
            return redirect('leave_plan_admin/'.$leave_plan_id)->with('message',$message);

        }


    }


    public function changeSupervisor(Request $request){

        if (Gate::denies('access',['change_supervisor','edit'])){
            abort(403, 'You Are Not Allowed To Change Supervisor For This Leave Plan');
        }

        $data = $this->validateChangeSupervisorRequest();
        $leave_plan_id = $data['leave_plan_id'];
        $new_spv = $data['responsible_spv'];
        $reason_for_change = $data['reason'];

        //get old supervisor
        $leave_plan = LeavePlan::find($leave_plan_id);
        $old_spv = $leave_plan->responsible_spv;

        //change supervisor, but when you change supervisor also reverse status to previous stage
        try{
            $leave_plan->responsible_spv = $new_spv;
            $leave_plan->status = '20';
            $leave_plan->save();

            $changed_by = auth()->user()->staff->id;

            //record this change
            $spv_change                = new LeavePlanChangedSupervisor();
            $spv_change->leave_plan_id = $leave_plan_id;
            $spv_change->old_spv_id    = $old_spv;
            $spv_change->new_spv_id    = $new_spv;
            $spv_change->changed_by    = $changed_by;
            $spv_change->reason        = $reason_for_change;
            $spv_change->save();

            //send email notification to  new supervisor

            $message = 'Supervisor Have been Changed Successfully';

            return redirect('leave_plan_admin/'.$leave_plan_id)->with('message',$message);

        }catch(\Exception $e){
            echo $e->getMessage();
        };


    }


    public function rejectLeavePlan(Request $request){

        if (Gate::denies('access',['reject_leave_plan','edit'])){
            abort(403, 'You Are Not Allowed To Reject This Leave Plan');
        }


        $data = $this->validateRejectRequest();
        $leave_plan_id= $data['leave_plan_id'];
        $rejection_reason = $data['rejection_reason'];
        $current_user_role = auth()->user()->role_id;
        $current_user_staff_id = auth()->user()->staff->id;

        $leave_plan = LeavePlan::find($leave_plan_id);
        $leave_plan_status = $leave_plan->status;
        $leave_plan_spv = $leave_plan->responsible_spv;



        if( ($current_user_role == 2 || $current_user_role == 3 || $current_user_role == 4 || $current_user_role == 5) && $leave_plan_status == 20 ){ // for SPV, HRM and MD
            //Waiting For Supervisor Approval, accountant, hrm and MD can be supervisors thus they can reject leave plans
            // from staff they are supervising


            if($leave_plan_spv == $current_user_staff_id){ //reject only if the current user is the supervisor assigned to approve the request

                $new_leave_plan_status = 99;//'Rejected'
                $reject_level = 'spv';

                //update leave_plan status
                $leave_plan->status = $new_leave_plan_status;
                $leave_plan->save();

                //record leave_plan approval
                $reject = new LeavePlanReject();
                $reject->leave_plan_id = $leave_plan_id;
                $reject->level = $reject_level;
                $reject->done_by = $current_user_staff_id;
                $reject->reason = $rejection_reason;
                $reject->save();

                //send email notifications
                event( new LeavePlanRejectedEvent($reject_level,$leave_plan));

                $message = 'Leave Plan Rejected Successfully.';

                return redirect('leave_plan_admin/'.$leave_plan_id)->with('message',$message);
            }
            else{

                $message = 'You are not authorised to Reject this Leave Plan.';

                return redirect('leave_plan_admin/'.$leave_plan_id)->with('message',$message);

            }

        }

        if($current_user_role == 3 && $leave_plan_status == 30){ //for HRM


            $reject_level = 'hrm';
            $new_leave_plan_status = 99;//'Rejected For Correction'

            //update leave_plan status
            $leave_plan->status = $new_leave_plan_status;
            $leave_plan->save();

            //record leave_plan approval
            $reject = new LeavePlanReject();
            $reject->leave_plan_id = $leave_plan_id;
            $reject->level = $reject_level;
            $reject->done_by = $current_user_staff_id;
            $reject->reason = $rejection_reason;
            $reject->save();

            //send email notifications
            event( new LeavePlanRejectedEvent($reject_level,$leave_plan));

            $message = 'Leave Plan Rejected Successfully.';

            return redirect('leave_plan_admin/'.$leave_plan_id)->with('message',$message);


        }

        if($current_user_role == 2 && $leave_plan_status == 40){ //for MD

            $reject_level = 'md';
            $new_leave_plan_status = 99;//'Rejected For Correction'

            //update leave_plan status
            $leave_plan->status = $new_leave_plan_status;
            $leave_plan->save();

            //record leave_plan rejection
            $reject = new LeavePlanReject();
            $reject->leave_plan_id = $leave_plan_id;
            $reject->level = $reject_level;
            $reject->done_by = $current_user_staff_id;
            $reject->reason = $rejection_reason;
            $reject->save();

            //send email notifications
            event( new LeavePlanRejectedEvent($reject_level,$leave_plan));

            $message = 'Leave Plan Rejected Successfully.';

            return redirect('leave_plan_admin/'.$leave_plan_id)->with('message',$message);

        }

        if($current_user_role == 1){ //for Super Administrator


            if($leave_plan_status == 20 || $leave_plan_status == 30 || $leave_plan_status == 40 || $leave_plan_status == 50){ //can reject submitted time sheet at any status

                $new_leave_plan_status = 99;
                $reject_level = 'adm';

                //update leave_plan status
                $leave_plan->status = $new_leave_plan_status;
                $leave_plan->save();

                //record leave_plan approval
                $reject = new LeavePlanReject();
                $reject->leave_plan_id = $leave_plan_id;
                $reject->level = $reject_level;
                $reject->done_by = $current_user_staff_id;
                $reject->reason = $rejection_reason;
                $reject->save();

                //send email notification
                event( new LeavePlanRejectedEvent($reject_level,$leave_plan));

                $message = 'Leave Plan Rejected successfully.';

            }

            else{

                $message = 'You are not allowed to reject this Leave Plan.';
            }

            return redirect('leave_plan_admin/'.$leave_plan_id)->with('message',$message);
        }

        else{

            $message = 'You are not Authorized to reject this Leave Plan.';

            return redirect('leave_plan_admin/'.$leave_plan_id)->with('message',$message);

        }


    }
    
    
    


    /****************************** VALIDATION SECTION ******************************/

    public function validateLeaveRequest(){

        return  request()->validate([
            'leave_type' =>  'required',
            'include_payment' =>  'nullable',
            'starting_date' =>  'required',
            'ending_date' =>  'required',
            'responsible_spv' =>  'required',
            'description' =>  'nullable',
        ]);


    }
    

    public function validateApprovalRequest(){

        return  request()->validate([
            'leave_plan_id' => 'required',
            'comments' =>  'nullable',
        ]);

    }


    public function validateReturnRequest(){

        return  request()->validate([
            'leave_plan_id' => 'required',
            'comments' =>  'required',
        ]);

    }


    public function validateChangeSupervisorRequest(){

        return  request()->validate([
            'responsible_spv' =>  'required',
            'reason' =>  'required',
            'leave_plan_id' => 'required',
        ]);

    }


    public function validateRejectRequest(){

        return  request()->validate([
            'leave_plan_id' => 'required',
            'rejection_reason' =>  'required',
        ]);

    }

}
