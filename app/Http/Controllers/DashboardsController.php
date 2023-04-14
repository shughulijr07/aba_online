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
use Illuminate\Http\Request;

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

        $model_name = "";
        $view_type = "";
        $controller_name = "";

        return view('admin.super_admin_dashboard',
            compact('leavePlans','leaveRequests','timeSheets','travelRequests','performanceObjectives',
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

        return view('admin.system_admin_dashboard',
            compact('leavePlans','leaveRequests','timeSheets','travelRequests','performanceObjectives',
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

        return view('admin.fd_dashboard',
            compact('leavePlans','leaveRequests','timeSheets','travelRequests','performanceObjectives',
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

        return view('admin.md_dashboard',
            compact('leavePlans','leaveRequests','timeSheets','travelRequests','performanceObjectives',
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

        return view('admin.hrm_dashboard',
            compact('leavePlans','leaveRequests','timeSheets','travelRequests','performanceObjectives',
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

        return view('admin.accountant_dashboard',
            compact('leavePlans','leaveRequests','timeSheets','travelRequests','performanceObjectives',
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

        return view('admin.supervisor_dashboard',
            compact('leavePlans','leaveRequests','timeSheets','travelRequests','performanceObjectives',
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

        return view('admin.employee_dashboard',
            compact('leavePlans','leaveRequests','timeSheets','travelRequests','performanceObjectives',
                'paymentRequests','requisitionRequests','retirementRequests','controller_name','model_name','view_type'));
    }


}
