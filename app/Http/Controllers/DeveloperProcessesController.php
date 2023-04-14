<?php

namespace App\Http\Controllers;

use App\Models\ActiveProject;
use App\Models\Activity;
use App\Models\AdvancePaymentRequest;
use App\Models\RequestApproval;
use App\Models\RequestSupervisorChange;
use App\Models\RequestRejection;
use App\Models\RequestReturn;
use App\Models\GlAccount;
use App\Models\JsonTimeSheetLine;
use App\Models\Leave;
use App\Models\LeaveApproval;
use App\Models\LeaveChangedSupervisor;
use App\Models\LeaveEntitlement;
use App\Models\LeaveEntitlementCarry;
use App\Models\LeaveEntitlementExtension;
use App\Models\LeaveEntitlementLine;
use App\Models\LeaveModification;
use App\Models\LeavePayment;
use App\Models\LeavePlan;
use App\Models\LeavePlanApproval;
use App\Models\LeavePlanChangedSupervisor;
use App\Models\LeavePlanLine;
use App\Models\LeavePlanReject;
use App\Models\LeavePlanReturn;
use App\Models\LeaveReject;
use App\Models\PerformanceObjective;
use App\Models\PerformanceObjectiveApproval;
use App\Models\PerformanceObjectiveChangedSupervisor;
use App\Models\PerformanceObjectiveLine;
use App\Models\PerformanceObjectiveMark;
use App\Models\PerformanceObjectiveReject;
use App\Models\PerformanceObjectiveReturn;
use App\Models\Project;
use App\Models\StaffBiographicalDataSheet;
use App\Models\StaffDependent;
use App\Models\StaffEmergencyContact;
use App\Models\StaffPerformance;
use App\Models\TimeSheet;
use App\Models\TimeSheetApproval;
use App\Models\TimeSheetChangedSupervisor;
use App\Models\TimeSheetClosing;
use App\Models\TimeSheetLateSubmission;
use App\Models\TimeSheetLine;
use App\Models\TimeSheetReject;
use App\Models\TimeSheetReturn;
use App\Models\TravelRequest;
use App\Models\TravelRequestApproval;
use App\Models\TravelRequestChangedSupervisor;
use App\Models\TravelRequestLine;
use App\Models\TravelRequestReject;
use App\Models\TravelRequestReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class DeveloperProcessesController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function cleanApplicationTables()
    {
        if (Gate::denies('access',['clean_application_tables','edit'])){
            abort(403, 'Access Denied');
        }

        ini_set('max_execution_time', '6000');


        //clean all application tables
        DB::statement("SET foreign_key_checks=0");
/**/
        //leave & leave plans tables
        LeaveEntitlementCarry::truncate();
        LeaveEntitlementExtension::truncate();
        LeaveEntitlementLine::truncate();
        LeaveEntitlement::truncate();

        LeaveApproval::truncate();
        LeaveChangedSupervisor::truncate();
        LeaveModification::truncate();
        LeavePayment::truncate();
        LeaveReject::truncate();
        Leave::truncate();

        LeavePlanApproval::truncate();
        LeavePlanChangedSupervisor::truncate();
        LeavePlanLine::truncate();
        LeavePlanReject::truncate();
        LeavePlanReturn::truncate();
        LeavePlan::truncate();

        //time sheets
        JsonTimeSheetLine::truncate();
        TimeSheetApproval::truncate();
        TimeSheetChangedSupervisor::truncate();
        TimeSheetLine::truncate();
        TimeSheetReject::truncate();
        TimeSheetReturn::truncate();
        TimeSheet::truncate();
        TimeSheetLateSubmission::truncate();
        TimeSheetClosing::truncate();

        //performance objectives
        PerformanceObjectiveApproval::truncate();
        //PerformanceObjectiveChangedSupervisor::truncate();
        PerformanceObjectiveLine::truncate();
        PerformanceObjectiveReject::truncate();
        PerformanceObjectiveReturn::truncate();
        PerformanceObjective::truncate();


        //travel requests
        TravelRequestApproval::truncate();
        //TravelRequestChangedSupervisor::truncate();
        TravelRequestReject::truncate();
        TravelRequestReturn::truncate();
        TravelRequest::truncate();


        //travel requests
        RequestApproval::truncate();
        RequestSupervisorChange::truncate();
        RequestRejection::truncate();
        RequestReturn::truncate();
        AdvancePaymentRequest::truncate();


        //staff related data
        StaffPerformance::truncate();
        StaffBiographicalDataSheet::truncate();
        StaffDependent::truncate();
        StaffEmergencyContact::truncate();

        GlAccount::truncate();
        Activity::truncate();
        ActiveProject::truncate();
        Project::truncate();

/**/

        DB::statement("SET foreign_key_checks=1");
        return redirect()->back();
    }


}
