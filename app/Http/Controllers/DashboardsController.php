<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\LeavePlan;
use App\Models\PerformanceObjective;
use App\Models\TimeSheet;
use App\Models\TravelRequest;
use App\Models\AdvancePaymentRequest;
use App\Models\RequisitionRequest;
use App\Models\RetirementRequest;
use App\Models\Staff;
use App\Models\Supervisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

class DashboardsController extends Controller
{

    public function showSuperAdminDashboard(){
        $leavePlans = LeavePlan::countLeavePlans();
        $leaveRequests = Leave::countLeaveRequests();
        $timeSheets = TimeSheet::countTimeSheets();
        $travelRequests = TravelRequest::countTravelRequests();
        $performanceObjectives = PerformanceObjective::countPerformanceObjectives();
        $paymentRequests= AdvancePaymentRequest::countRequests();
        $requisitionRequests = RequisitionRequest::countTravelRequests();
        $retirementRequests = RetirementRequest::countTravelRequests();

        $user_id = Auth::user()->id;
        $staff_data = Staff::where('user_id',$user_id)->first();
        $isSupervisor = DB::table('supervisors')->where('staff_id', $staff_data->id)->first();

        $model_name = "";
        $view_type = "";
        $controller_name = "";

        return view('admin.super_admin_dashboard',
            compact('leavePlans','leaveRequests','timeSheets','travelRequests','performanceObjectives','isSupervisor',
                'retirementRequests','requisitionRequests','paymentRequests','controller_name','model_name','view_type')
            );
    }

    public function showSystemAdministratorDashboard(){

        $leavePlans = LeavePlan::countLeavePlans();
        $leaveRequests = Leave::countLeaveRequests();
        $timeSheets = TimeSheet::countTimeSheets();
        $travelRequests = TravelRequest::countTravelRequests();
        $performanceObjectives = PerformanceObjective::countPerformanceObjectives();
        $paymentRequests= AdvancePaymentRequest::countRequests();
        $requisitionRequests = RequisitionRequest::countTravelRequests();
        $retirementRequests = RetirementRequest::countTravelRequests();

        $model_name = "";
        $view_type = "";
        $controller_name = "";

        $user_id = Auth::user()->id;
        $staff_data = Staff::where('user_id',$user_id)->first();
        $isSupervisor = DB::table('supervisors')->where('staff_id', $staff_data->id)->first();

        return view('admin.system_admin_dashboard',
            compact('leavePlans','leaveRequests','timeSheets','travelRequests','performanceObjectives','isSupervisor',
                'retirementRequests','requisitionRequests','paymentRequests','controller_name','model_name','view_type'));
    }

    public function showFDDashboard(){

        $leavePlans = LeavePlan::countLeavePlans();
        $leaveRequests = Leave::countLeaveRequests();
        $timeSheets = TimeSheet::countTimeSheets();
        $travelRequests = TravelRequest::countTravelRequests();
        $performanceObjectives = PerformanceObjective::countPerformanceObjectives();
        $paymentRequests= AdvancePaymentRequest::countRequests();
        $requisitionRequests = RequisitionRequest::countTravelRequests();
        $retirementRequests = RetirementRequest::countTravelRequests();

        $model_name = "";
        $view_type = "";
        $controller_name = "";

        $user_id = Auth::user()->id;
        $staff_data = Staff::where('user_id',$user_id)->first();
        $isSupervisor = DB::table('supervisors')->where('staff_id', $staff_data->id)->first();


        return view('admin.fd_dashboard',
            compact('leavePlans','leaveRequests','timeSheets','travelRequests','performanceObjectives','isSupervisor',
                'paymentRequests','requisitionRequests','retirementRequests','controller_name','model_name','view_type'));
    }

    public function showMDDashboard(){

        $leavePlans = LeavePlan::countLeavePlans();
        $leaveRequests = Leave::countLeaveRequests();
        $timeSheets = TimeSheet::countTimeSheets();
        $travelRequests = TravelRequest::countTravelRequests();
        $performanceObjectives = PerformanceObjective::countPerformanceObjectives();
        $paymentRequests= AdvancePaymentRequest::countRequests();
        $requisitionRequests = RequisitionRequest::countTravelRequests();
        $retirementRequests = RetirementRequest::countTravelRequests();

        $model_name = "";
        $view_type = "";
        $controller_name = "";

        $user_id = Auth::user()->id;
        $staff_data = Staff::where('user_id',$user_id)->first();
        $isSupervisor = DB::table('supervisors')->where('staff_id', $staff_data->id)->first();


        return view('admin.md_dashboard',
            compact('leavePlans','leaveRequests','timeSheets','travelRequests','performanceObjectives','isSupervisor',
                'paymentRequests','requisitionRequests','retirementRequests','controller_name','model_name','view_type'));
    }

    public function showHRMDashboard(){

        $leavePlans = LeavePlan::countLeavePlans();
        $leaveRequests = Leave::countLeaveRequests();
        $timeSheets = TimeSheet::countTimeSheets();
        $travelRequests = TravelRequest::countTravelRequests();
        $performanceObjectives = PerformanceObjective::countPerformanceObjectives();
        $paymentRequests= AdvancePaymentRequest::countRequests();
        $requisitionRequests = RequisitionRequest::countTravelRequests();
        $retirementRequests = RetirementRequest::countTravelRequests();

        $model_name = "";
        $view_type = "";
        $controller_name = "";

        $user_id = Auth::user()->id;
        $staff_data = Staff::where('user_id',$user_id)->first();
        $isSupervisor = DB::table('supervisors')->where('staff_id', $staff_data->id)->first();


        return view('admin.hrm_dashboard',
            compact('leavePlans','leaveRequests','timeSheets','travelRequests','performanceObjectives','isSupervisor',
                'retirementRequests','requisitionRequests','paymentRequests','controller_name','model_name','view_type'));
    }

    public function showAccountantDashboard(){

        $leavePlans = LeavePlan::countLeavePlans();
        $leaveRequests = Leave::countLeaveRequests();
        $timeSheets = TimeSheet::countTimeSheets();
        $travelRequests = TravelRequest::countTravelRequests();
        $paymentRequests= AdvancePaymentRequest::countRequests();
        $requisitionRequests = RequisitionRequest::countTravelRequests();
        $retirementRequests = RetirementRequest::countTravelRequests();
        $performanceObjectives = PerformanceObjective::countPerformanceObjectives();

        $model_name = "";
        $view_type = "";
        $controller_name = "";

        $user_id = Auth::user()->id;
        $staff_data = Staff::where('user_id',$user_id)->first();
        $isSupervisor = DB::table('supervisors')->where('staff_id', $staff_data->id)->first();


        return view('admin.accountant_dashboard',
            compact('leavePlans','leaveRequests','timeSheets','travelRequests','performanceObjectives','isSupervisor',
                'requisitionRequests','retirementRequests','paymentRequests','controller_name','model_name','view_type'));
    }

    public function showSupervisorDashboard(){

        $leavePlans = LeavePlan::countLeavePlans();
        $leaveRequests = Leave::countLeaveRequests();
        $timeSheets = TimeSheet::countTimeSheets();
        $travelRequests = TravelRequest::countTravelRequests();
        $performanceObjectives = PerformanceObjective::countPerformanceObjectives();
        $paymentRequests= AdvancePaymentRequest::countRequests();
        $requisitionRequests = RequisitionRequest::countTravelRequests();
        $retirementRequests = RetirementRequest::countTravelRequests();

        $model_name = "";
        $view_type = "";
        $controller_name = "";

        $user_id = Auth::user()->id;
        $staff_data = Staff::where('user_id',$user_id)->first();
        $isSupervisor = DB::table('supervisors')->where('staff_id', $staff_data->id)->first();

        // For new Dashboard
        $employee_id = auth()->user()->staff->id;
        $supv = Supervisor::where('staff_id', $employee_id)->first();
            if (Auth::user()->role_id == 1) {
            $time_sheets = DB::table('time_sheets')
                       ->join('staff', 'time_sheets.staff_id', '=', 'staff.id')
                       ->select('time_sheets.*', 'staff.first_name','staff.middle_name','staff.last_name')
                       ->get();
             } else {
                 $time_sheets = DB::table('time_sheets')
                       ->join('staff', 'time_sheets.staff_id', '=', 'staff.id')
                       ->select('time_sheets.*', 'staff.first_name','staff.middle_name','staff.last_name')
                        ->where('responsible_spv', $supv->staff_id)
                       ->get();
        }
        // End for new Dashboard

        return view('admin.supervisor_dashboard',
            compact('leavePlans','leaveRequests','timeSheets','travelRequests','performanceObjectives','isSupervisor','time_sheets',
                'retirementRequests','requisitionRequests','paymentRequests','controller_name','model_name','view_type'));
    }

    public function showEmployeeDashboard(){

        $leavePlans = LeavePlan::countLeavePlans();
        $leaveRequests = Leave::countLeaveRequests();
        $timeSheets = TimeSheet::countTimeSheets();
        $travelRequests = TravelRequest::countTravelRequests();
        $performanceObjectives = PerformanceObjective::countPerformanceObjectives();
        $paymentRequests= AdvancePaymentRequest::countRequests();
        $requisitionRequests = RequisitionRequest::countTravelRequests();
        $retirementRequests = RetirementRequest::countTravelRequests();

        $model_name = "";
        $view_type = "";
        $controller_name = "";

        $user_id = Auth::user()->id;
        $staff_data = Staff::where('user_id',$user_id)->first();
        $isSupervisor = DB::table('supervisors')->where('staff_id', $staff_data->id)->first();


        return view('admin.employee_dashboard',
            compact('leavePlans','leaveRequests','timeSheets','travelRequests','performanceObjectives','isSupervisor',
                'paymentRequests','requisitionRequests','retirementRequests','controller_name','model_name','view_type'));
    }


}
