<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Support\Facades\Route;
use App\Events\TimeSheetSubmittedEvent;
use App\Http\Controllers\ActivitiesController;
use App\Http\Controllers\GlAccountsController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\DashboardsController;
use App\Http\Controllers\SystemUsersController;
use App\Http\Controllers\LeavesController;
use App\Http\Controllers\LeaveEntitlementsController;
use App\Http\Controllers\LeaveReportsController;
use App\Http\Controllers\LeaveTypesController;
use App\Http\Controllers\LeavePlansController;
use App\Http\Controllers\TimeSheetsController;
use App\Http\Controllers\TimeSheetLateSubmissionsController;
use App\Http\Controllers\TimeSheetReportsController;
use App\Http\Controllers\TravelRequestsController;
use App\Http\Controllers\HolidaysController;
use App\Http\Controllers\ActiveProjectsController;
use App\Http\Controllers\RequisitionRequestsController;
use App\Http\Controllers\AdvancePaymentRequestsController;
use App\Http\Controllers\SupervisorsController;
use App\Http\Controllers\PerformanceObjectivesController;
use App\Http\Controllers\StaffPerformancesController;
use App\Http\Controllers\RetirementRequestsController;
use App\Http\Controllers\CompanyInformationController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\UserAccountController;
use App\Http\Controllers\GeneralSettingsController;
use App\Http\Controllers\StaffDependentsController;
use App\Http\Controllers\StaffBiographicalDataSheetsController;
use App\Http\Controllers\CountriesController;
use App\Http\Controllers\DepartmentsController;
use App\Http\Controllers\DistrictsController;
use App\Http\Controllers\RegionsController;
use App\Http\Controllers\SystemRolePermissionsController;
use App\Http\Controllers\SystemRolesController;
use App\Http\Controllers\StaffsJobTitlesController;
use App\Http\Controllers\WardsController;
use App\Http\Controllers\NumberedItemsController;
use App\Http\Controllers\NumberSeriesController;
use App\Http\Controllers\UserActivitiesController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\DeveloperProcessesController;
use App\Http\Controllers\StaffEmergencyContactsController;
use App\Mail\PasswordReset;
use App\Models\LeaveEntitlement;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

Auth::routes();

Route::get('/home', function(){ 
    return redirect('employee');});
Route::view('/','auth.login');

Route::get('/send_time_sheet',function(){
    return event( new \App\Events\SendTimeSheetToBC130());
});

Route::get('/send_travel_request',function(){
    return event( new \App\Events\SendTravelRequestToBC130());
});


Route::get('/email', function(){
    $staff = \App\Models\Staff::find(14);

    //send email to employee
    $employee_official_email = $staff->official_email;

    $recipient_type = 'staff';
    $recipient = $staff;
    Mail::to('abdumalikiandrew@gmail.com')->send(new PasswordReset($recipient_type,$recipient));

});


/******************************** BASIC ROUTES *********************************/
Route::get('super-administrator',[DashboardsController::class,"showSuperAdminDashboard"]);
Route::get('system-administrator',[DashboardsController::class,"showSystemAdministratorDashboard"]);
Route::get('finance-director',[DashboardsController::class, 'showFDDashboard']);
Route::get('managing-director',[DashboardsController::class, 'showMDDashboard']);
Route::get('human-resource-manager',[DashboardsController::class, 'showHRMDashboard']);
Route::get('accountant',[DashboardsController::class, 'showAccountantDashboard']);
Route::get('supervisor',[DashboardsController::class, 'showSupervisorDashboard']);
Route::get('employee',[DashboardsController::class, 'showEmployeeDashboard']);




/************ LEAVE MANAGEMENT ROUTES *************************/

    Route::get('/request_leave',[LeavesController::class, 'create']);
    Route::post('/request_leave',[LeavesController::class,'store']);
    Route::get('/leaves/{status}',[LeavesController::class,'index']);
    Route::get('/admin_leaves/{status}',[LeavesController::class,'adminIndex']);
    Route::get('/leave/{id}',[LeavesController::class,'show']);
    Route::get('/leave_admin/{id}',[LeavesController::class,'showAdmin']);
    Route::get('/time_sheet_admin/leave_admin/{id}',[LeavesController::class,'showAdmin']);
    Route::post('/approve_leave',[LeavesController::class,'approveLeave']);
    Route::post('/confirm_leave_payment',[LeavesController::class,'confirmPayment']);
    Route::post('/modify_leave',[LeavesController::class,'modifyApproveLeave']);
    Route::post('/modify_leave',[LeavesController::class,'modifyApproveLeave']);
    Route::post('/change_supervisor',[LeavesController::class,'changeSupervisor']);
    Route::post('/reject_leave',[LeavesController::class,'rejectLeave']);
    Route::get('/leave_statement/{id}',[LeavesController::class,'showLeaveStatement']);
    Route::get('/my_leaves',[LeavesController::class,'myLeavesIndex']);
    Route::post('/my_leaves',[LeavesController::class,'myLeavesList']);
    Route::get('/overlapping_leaves/{id}',[LeavesController::class,'overlappingLeaves']);

//LeavesEntitlement Routes
Route::get('/leave_entitlements/create',[LeaveEntitlementsController::class,'create']);
Route::post('/leave_entitlements',[LeaveEntitlementsController::class,'store']);
Route::get('/leave_entitlements',[LeaveEntitlementsController::class,'index']);
Route::get('/leave_entitlements/{id}',[LeaveEntitlementsController::class,'show']);
Route::get('/leave_entitlements/{id}/edit',[LeaveEntitlementsController::class,'edit']);
Route::patch('/leave_entitlements/{id}',[LeaveEntitlementsController::class,'update']);
Route::get('/perform_carry_over/{staff_id}',[LeaveEntitlementsController::class,'performCarryOver']);

Route::get('/leave_reports',[LeaveReportsController::class, 'index']);
Route::post('/generate_leave_report',[LeaveReportsController::class, 'generateReport']);
Route::resource('leave_types', 'LeaveTypesController');

//LeavesPlan Routes
Route::resource('leave_plans', 'LeavePlansController');
Route::get('/leave_plan_remove_line/{line_id}',[LeavePlansController::class,'removeLine']);
Route::get('/leave_plan_submit/{leave_plan_id}',[LeavePlansController::class,'submitLeavePlan']);
Route::get('/leave_plan_admin/{id}',[LeavePlansController::class,'showAdmin']);
Route::get('/leave/leave_plans/{id}',[LeavePlansController::class,'showById']);
Route::get('/leave_admin/leave_plans/{id}',[LeavePlansController::class,'showAdmin']);
Route::get('/admin_leave_plans/{status}',[LeavePlansController::class,'adminIndex']);
Route::get('/leave_plan_summary/{mode}',[LeavePlansController::class,'leavePlansSummary']);

Route::post('/approve_leave_plan',[LeavePlansController::class,'approveLeavePlan']);
Route::post('/return_leave_plan',[LeavePlansController::class,'returnLeavePlan']);
Route::post('/change_leave_plan_spv',[LeavePlansController::class,'changeSupervisor']);
Route::post('/reject_leave_plan',[LeavePlansController::class,'rejectLeavePlan']);


/************ TIME SHEETS MANAGEMENT ROUTES *************************/
Route::post('/fill_day_task',[TimeSheetsController::class,'fill_day_task'])->name('fill_day_task');
Route::get('/fill-timesheet',[TimeSheetsController::class,'fill_timesheet'])->name('fill-timesheet');
Route::get('/view-sheet',[TimeSheetsController::class,'preview_timesheet'])->name('view-sheet');
Route::get('/new_time_sheet',[TimeSheetsController::class,'create']);
Route::post('/new_time_sheet',[TimeSheetsController::class,'store']);
Route::get('/create_timesheet_for_another_staff',[TimeSheetsController::class,'new_createForAnotherStaff']);
Route::post('/create_timesheet_for_another_staff',[TimeSheetsController::class,'storeForAnotherStaff']);
Route::get('/create_time_sheet_entries/{id}',[TimeSheetsController::class,'createTimeSheetLines']);
Route::post('/new_create_timesheet_for_another_staff',[TimeSheetsController::class,'new_storeForAnotherStaff']);
Route::get('/time-sheets',[TimeSheetsController::class,'time_sheets']);

Route::get('/assign_client_task/{id}',[TimeSheetsController::class,'assign_client_task']);
Route::get('/delete_draft_timesheets/{id}',[TimeSheetsController::class,'delete_draft_timesheets']);
Route::get('/delete_task/{id}', [TimeSheetsController::class, 'delete_task']);
Route::post('/create_time_sheet_entries',[TimeSheetsController::class,'storeTimesheetData']);
Route::get('/create_time_sheet_entries_admin/{id}',[TimeSheetsController::class,'createTimeSheetLinesAdmin']);
Route::post('/timesheet-add-client',[TimeSheetsController::class,'timesheet_add_client'])->name('timesheet-add-client');
Route::post('/create_time_sheet_entries_admin',[TimeSheetsController::class,'storeTimesheetDataAdmin']);
Route::get('/time_sheets/{status}',[TimeSheetsController::class,'index']);
Route::get('/admin_time_sheets/{status}',[TimeSheetsController::class,'adminIndex']);
Route::get('/time_sheet/{id}',[TimeSheetsController::class,'show']);
Route::get('/time_sheet_admin/{id}',[TimeSheetsController::class,'showAdmin']);
Route::get('/time_sheet_edit/{id}',[TimeSheetsController::class,'editTimeSheetData']);
Route::get('/time_sheet_edit_admin/{id}',[TimeSheetsController::class,'adminEditTimeSheetData']);
Route::post('/time_sheet_edit',[TimeSheetsController::class,'update']);
Route::get('/my_time_sheets',[TimeSheetsController::class,'myTimeSheetsIndex']);
Route::post('/my_time_sheets',[TimeSheetsController::class,'myTimeSheetsList']);
Route::post('/approve_timesheet',[TimeSheetsController::class,'approveTimeSheet']);
Route::post('/return_timesheet',[TimeSheetsController::class,'returnTimeSheet']);
Route::post('/change_timesheet_spv',[TimeSheetsController::class,'changeSupervisor']);
Route::post('/reject_timesheet',[TimeSheetsController::class,'rejectTimeSheet']);
Route::get('/time_sheet_statement/{id}',[TimeSheetsController::class,'showTimeSheetStatement']);
Route::resource('time_sheet_late_submissions','TimeSheetLateSubmissionsController');
Route::get('/unlock_time_sheet_submission/{id} ',[TimeSheetLateSubmissionsController::class, 'unlockTimeSheetSubmission']);
Route::get('/time_sheet_reports',[TimeSheetReportsController::class,'index']);
Route::post('/generate_time_sheet_report',[TimeSheetReportsController::class,'generateReport']);
Route::get('/holidays_list/{year}',[HolidaysController::class, 'index2']);
Route::get('/supervisors/delete/{supervisor_id}',[SupervisorsController::class, 'delete']);
Route::resource('supervisors','SupervisorsController');


/************ PROJECTS ROUTES *************************/
Route::resource('holidays','HolidaysController');
Route::get('/projects/ajaxGetList/', [ProjectsController::class, 'ajaxGetList'])->name('projects.ajaxGetList');
Route::post('/projects/ajaxDelete/', [ProjectsController::class, 'ajaxDelete'])->name('projects.ajaxDelete');
Route::get('/activities/getList/', [ActivitiesController::class, 'getList'])->name('activities.getList');
Route::post('/activities/ajaxDelete/', [ActivitiesController::class, 'ajaxDelete'])->name('activities.ajaxDelete');
Route::post('/activities/ajaxGetByProject/', [ActivitiesController::class, 'ajaxGetActivitiesByProject'])->name('activities.ajaxGetByProject');
Route::get('/gl_accounts/ajaxGetList/', [GlAccountsController::class, 'ajaxGetList'])->name('gl_accounts.ajaxGetList');
Route::post('/gl_accounts/ajaxDelete/', [GlAccountsController::class, 'ajaxDelete'])->name('gl_accounts.ajaxDelete');

Route::get('/activities/import_from_excel', [ActivitiesController::class, 'importFromExcel']);
Route::get('/gl_accounts/import_from_excel', [GlAccountsController::class, 'importFromExcel']);

Route::resource('projects','ProjectsController');
Route::resource('activities','ActivitiesController');
Route::resource('active_projects','ActiveProjectsController');
Route::resource('gl_accounts','GlAccountsController');


/************ TRAVEL MANAGEMENT ROUTES *************************/
Route::get('/new_travel_request',[TravelRequestsController::class,'create']);
Route::post('/travel_request',[TravelRequestsController::class,'store']);
Route::get('/travel_request/{id}',[TravelRequestsController::class,'show']);
Route::patch('/travel_request_update/{id}',[TravelRequestsController::class,'update']);
Route::get('/travel_requests/{status}',[TravelRequestsController::class,'index']);
Route::get('/admin_travel_requests/{status}',[TravelRequestsController::class,'adminIndex']);
Route::get('/travel_requests_admin/{id}',[TravelRequestsController::class,'showAdmin']);
Route::get('/travelFilePreview/{is}',[TravelRequestsController::class,'filePreview']);
Route::get('/travel_request/activities',[TravelRequestsController::class,'activities']);
Route::post('/approve_travel_request',[TravelRequestsController::class,'approveTravelRequest']);
Route::post('/return_travel_request',[TravelRequestsController::class,'returnTravelRequest']);
Route::post('/change_travel_request_spv',[TravelRequestsController::class,'changeSupervisor']);
Route::post('/reject_travel_request',[TravelRequestsController::class,'rejectTravelRequest']);
Route::get('/my_travel_records',[TravelRequestsController::class,'myTravelRequestsIndex']);
Route::post('/my_travel_records',[TravelRequestsController::class,'myTravelRequestsList']);
Route::get('/staff_travel_records',[TravelRequestsController::class,'staffTravelRequestsIndex']);
Route::post('/staff_travel_records',[TravelRequestsController::class,'staffTravelRequestsList']);
Route::get('/travel_request_statement/{id}',[TravelRequestsController::class,'showTravellingStatement']);

/************ REQUISITION MANAGEMENT ROUTES *************************/
Route::get('/new_requisition_request',[RequisitionRequestsController::class,'create']);
Route::post('/requisition_request',[RequisitionRequestsController::class,'store']);
Route::get('/requisition_request/{id}',[RequisitionRequestsController::class,'show']);
Route::patch('/requisition_request_update/{id}',[RequisitionRequestsController::class,'update']);
Route::get('/requisition_requests/{status}',[RequisitionRequestsController::class,'index']);
Route::get('/admin_requisition_requests/{status}',[RequisitionRequestsController::class,'adminIndex']);
Route::get('/requisition_requests_admin/{id}',[RequisitionRequestsController::class,'showAdmin']);
Route::get('/requisitionFilePreview/{is}',[RequisitionRequestsController::class,'filePreview']);
Route::get('/requisition_activities',[RequisitionRequestsController::class,'activities']);
Route::post('/approve_requisition_request',[RequisitionRequestsController::class,'approveTravelRequest']);
Route::post('/return_requisition_request',[RequisitionRequestsController::class,'returnTravelRequest']);
Route::post('/change_requisition_request_spv',[RequisitionRequestsController::class,'changeSupervisor']);
Route::post('/reject_requisition_request',[RequisitionRequestsController::class,'rejectTravelRequest']);
Route::get('/my_requisition_records',[RequisitionRequestsController::class,'myTravelRequestsIndex']);
Route::post('/my_requisition_records',[RequisitionRequestsController::class,'myTravelRequestsList']);
Route::get('/staff_requisition_records',[RequisitionRequestsController::class,'staffTravelRequestsIndex']);
Route::post('/staff_requisition_records',[RequisitionRequestsController::class,'staffTravelRequestsList']);
Route::get('/requisition_request_statement/{id}',[RequisitionRequestsController::class,'showTravellingStatement']);

/************ ADVANCE PAYMENT MANAGEMENT ROUTES *************************/
Route::get('/new_advance_payment_request',[AdvancePaymentRequestsController::class,'create']);
Route::post('/advance_payment_request',[AdvancePaymentRequestsController::class,'store']);
Route::get('/advance_payment_request/{id}',[AdvancePaymentRequestsController::class,'show']);
Route::get('/advance_payment_request/{id}/{responseType}',[AdvancePaymentRequestsController::class,'show']);
Route::get('/advance_payment_request_edit/{id}',[AdvancePaymentRequestsController::class,'edit']);
Route::get('/advance_payment_request_edit/{id}/{responseType}',[AdvancePaymentRequestsController::class,'edit']);
Route::patch('/advance_payment_request_update/{id}',[AdvancePaymentRequestsController::class,'update']);
Route::get('/advance_payment_requests/{status}',[AdvancePaymentRequestsController::class,'index']);
Route::get('/admin_advance_payment_requests/{status}',[AdvancePaymentRequestsController::class,'adminIndex']);
Route::get('/advance_payment_requests_admin/{id}',[AdvancePaymentRequestsController::class,'showAdmin']);
Route::get('/advance_payment_requests_admin/{id}/{responseType}',[AdvancePaymentRequestsController::class,'showAdmin']);
Route::post('/approve_advance_payment_request',[AdvancePaymentRequestsController::class,'approveRequest']);
Route::post('/return_advance_payment_request',[AdvancePaymentRequestsController::class,'returnRequest']);
Route::post('/change_advance_payment_request_spv',[AdvancePaymentRequestsController::class,'changeSupervisor']);
Route::post('/reject_advance_payment_request',[AdvancePaymentRequestsController::class,'rejectRequest']);
Route::get('/my_advance_payment_records',[AdvancePaymentRequestsController::class,'myRequestsIndex']);
Route::post('/my_advance_payment_records',[AdvancePaymentRequestsController::class,'myRequestsList']);
Route::get('/staff_advance_payment_records',[AdvancePaymentRequestsController::class,'staffRequestsIndex']);
Route::post('/staff_advance_payment_records',[AdvancePaymentRequestsController::class,'staffRequestsList']);
Route::get('/advance_payment_request_statement/{id}',[AdvancePaymentRequestsController::class,'showStatement']);
Route::post('/advance_payment_requests.ajaxDeleteMultiple',[AdvancePaymentRequestsController::class,'deleteMultiple'])->name('advance_payment_requests.ajaxDeleteMultiple');
Route::post('/advance_payment_requests.ajaxApproveMultiple',[AdvancePaymentRequestsController::class,'approveMultipleRequests'])->name('advance_payment_requests.ajaxApproveMultiple');
Route::post('/advance_payment_requests.ajaxApprove',[AdvancePaymentRequestsController::class,'approveRequest'])->name('advance_payment_requests.ajaxApprove');
Route::post('/advance_payment_requests.ajaxReturnForCorrection',[AdvancePaymentRequestsController::class,'returnRequest'])->name('advance_payment_requests.ajaxReturnForCorrection');
Route::post('/advance_payment_requests.ajaxChangeSupervisor',[AdvancePaymentRequestsController::class,'changeSupervisor'])->name('advance_payment_requests.ajaxChangeSupervisor');
Route::post('/advance_payment_requests.ajaxReject',[AdvancePaymentRequestsController::class,'rejectRequest'])->name('advance_payment_requests.ajaxReject');

/************ REQUISITION MANAGEMENT ROUTES *************************/
Route::get('/new_requisition_request',[RequisitionRequestsController::class,'create']);
Route::post('/requisition_request',[RequisitionRequestsController::class,'store']);
Route::get('/requisition_request/{id}',[RequisitionRequestsController::class,'show']);
Route::patch('/requisition_request_update/{id}',[RequisitionRequestsController::class,'update']);
Route::get('/requisition_requests/{status}',[RequisitionRequestsController::class,'index']);
Route::get('/admin_requisition_requests/{status}',[RequisitionRequestsController::class,'adminIndex']);
Route::get('/requisition_requests_admin/{id}',[RequisitionRequestsController::class,'showAdmin']);
Route::get('/requisitionFilePreview/{is}',[RequisitionRequestsController::class,'filePreview']);
Route::get('/requisition_activities',[RequisitionRequestsController::class,'activities']);
Route::post('/approve_requisition_request',[RequisitionRequestsController::class,'approveTravelRequest']);
Route::post('/return_requisition_request',[RequisitionRequestsController::class,'returnTravelRequest']);
Route::post('/change_requisition_request_spv',[RequisitionRequestsController::class,'changeSupervisor']);
Route::post('/reject_requisition_request',[RequisitionRequestsController::class,'rejectTravelRequest']);
Route::get('/my_requisition_records',[RequisitionRequestsController::class,'myTravelRequestsIndex']);
Route::post('/my_requisition_records',[RequisitionRequestsController::class,'myTravelRequestsList']);
Route::get('/staff_requisition_records',[RequisitionRequestsController::class,'staffTravelRequestsIndex']);
Route::post('/staff_requisition_records',[RequisitionRequestsController::class,'staffTravelRequestsList']);
Route::get('/requisition_request_statement/{id}',[RequisitionRequestsController::class,'showTravellingStatement']);

/************ RETIREMENT MANAGEMENT ROUTES *************************/
Route::get('/new_retirement_request/{id}',[RetirementRequestsController::class,'create']);
Route::post('/retirement_request',[RetirementRequestsController::class,'store']);
Route::get('/retirement_request/{id}',[RetirementRequestsController::class,'show']);
Route::patch('/retirement_request_update/{id}',[RetirementRequestsController::class,'update']);
Route::get('/retirement_requests/{status}',[RetirementRequestsController::class,'index']);
Route::get('/admin_retirement_requests/{status}',[RetirementRequestsController::class,'adminIndex']);
Route::get('/retirement_requests_admin/{id}',[RetirementRequestsController::class,'showAdmin']);
Route::get('/retirementFilePreview/{is}',[RetirementRequestsController::class,'filePreview']);
Route::get('/retirement_activities',[RetirementRequestsController::class,'activities']);
Route::get('/admin_retirementadvance_payment_requests/{status}',[RetirementRequestsController::class,'adminIndexPayment']);
Route::post('/approve_retirement_request',[RetirementRequestsController::class,'approveTravelRequest']);
Route::post('/return_retirement_request',[RetirementRequestsController::class,'returnTravelRequest']);
Route::post('/change_retirement_request_spv',[RetirementRequestsController::class,'changeSupervisor']);
Route::post('/reject_retirement_request',[RetirementRequestsController::class,'rejectTravelRequest']);
Route::get('/my_retirement_records',[RetirementRequestsController::class,'myTravelRequestsIndex']);
Route::post('/my_retirement_records',[RetirementRequestsController::class,'myTravelRequestsList']);
Route::get('/staff_retirement_records',[RetirementRequestsController::class,'staffTravelRequestsIndex']);
Route::post('/staff_retirement_records',[RetirementRequestsController::class,'staffTravelRequestsList']);
Route::get('/retirement_request_statement/{id}',[RetirementRequestsController::class,'showTravellingStatement']);

/************************* CONTACT ROUTES ***************************/
Route::get('/contact',[ContactController::class, 'index']);

/************ PERFORMANCE MANAGEMENT ROUTES *************************/
Route::get('/set_objectives',[PerformanceObjectivesController::class,'create']);
Route::post('/submit_objectives',[PerformanceObjectivesController::class,'store']);
Route::patch('/update_objectives/{id}',[PerformanceObjectivesController::class,'update']);
Route::get('/performance_objective/{id}',[PerformanceObjectivesController::class,'show']);
Route::get('/performance_objective_admin/{id}',[PerformanceObjectivesController::class,'showAdmin']);
Route::get('/performance_objectives/{status}',[PerformanceObjectivesController::class,'index']);
Route::get('/admin_performance_objectives/{status}',[PerformanceObjectivesController::class,'adminIndex']);

Route::post('/approve_objectives',[PerformanceObjectivesController::class,'approvePerformanceObjectives']);
Route::post('/return_objectives',[PerformanceObjectivesController::class,'returnPerformanceObjectives']);
Route::post('/change_objectives_spv',[PerformanceObjectivesController::class,'changeSupervisor']);
Route::post('/reject_objectives',[PerformanceObjectivesController::class,'rejectPerformanceObjectives']);




Route::get('/staff_performances',[StaffPerformancesController::class,'index']);
Route::get('/staff_performances_admin/{year}',[StaffPerformancesController::class,'indexAdmin']);
Route::get('/staff_performances/{performance_id}',[StaffPerformancesController::class,'show']);
Route::post('/first_quoter_staff_performance_assessment',[StaffPerformancesController::class,'firstQuoterAssessment']);
Route::post('/second_quoter_staff_performance_assessment',[StaffPerformancesController::class,'secondQuoterAssessment']);
Route::post('/third_quoter_staff_performance_assessment',[StaffPerformancesController::class,'thirdQuoterAssessment']);
Route::post('/fourth_quoter_staff_performance_assessment',[StaffPerformancesController::class,'fourthQuoterAssessment']);



/***************************** MIXED ROUTES'*****************************/

Route::get('/staff_supervisors',[StaffController::class,'supervisorsIndex']);
Route::post('/staff_supervisor_update',[StaffController::class,'supervisorsUpdate']);
Route::get('/create_staff_biographical_data_sheets/{staff_id}',[StaffBiographicalDataSheetsController::class, 'createForStaff']);
Route::get('/reset_staff_password/{id}',[UserAccountController::class, 'resetPassword']);
Route::get('/company_information',[CompanyInformationController::class, 'show']);
Route::post('/company_information',[CompanyInformationController::class, 'update']);
Route::get('/general_settings',[GeneralSettingsController::class, 'show']);
Route::post('/general_settings',[GeneralSettingsController::class, 'update']);




Route::get('update_dependants_list',[StaffDependentsController::class, 'edit']);
Route::post('update_dependants_list',[StaffDependentsController::class, 'update']);

Route::get('staff_emergency_contacts',[ StaffEmergencyContactsController::class, 'index']);
Route::get('update_emergency_contacts_list',[ StaffEmergencyContactsController::class, 'edit']);
Route::post('update_emergency_contacts_list',[ StaffEmergencyContactsController::class, 'update']);
Route::get('/staff/import_from_excel', [StaffController::class, 'importFromExcel']);



/************************** RESOURCE ROUTES ****************************/
Route::resource('departments','DepartmentsController');
Route::resource('countries','CountriesController');
Route::resource('districts','DistrictsController');
Route::resource('regions','RegionsController');
Route::resource('staff','StaffController');
Route::resource('staff_dependents','StaffDependentsController');
Route::resource('staff_emergency_contacts','StaffEmergencyContactsController');
Route::resource('staff_biographical_data_sheets','StaffBiographicalDataSheetsController');
Route::resource('staff_job_titles','StaffsJobTitlesController');
Route::resource('system_roles','SystemRolesController');
Route::resource('permissions','PermissionsController');
Route::resource('wards','WardsController');
Route::resource('system_users', 'SystemUsersController');
Route::resource('numbered_items', 'NumberedItemsController');
Route::resource('number_series', 'NumberSeriesController');



/***************************** USER ACCOUNTS ROUTES ********************************/
Route::get('/change_password',[UserAccountController::class, 'changePassword']);
Route::post('update_password',[UserAccountController::class, 'updatePassword']);


/********************************* USER ACTIVITIES *********************************/
Route::get('/user_activities',[UserActivitiesController::class, 'index']);


/******************** AJAX REQUESTS ******************/
Route::post('wards/ajax_get', [WardsController::class, 'ajaxGetWards'])->name('wards.ajax_get');
Route::post('districts/ajax_get', [DistrictsController::class, 'ajaxGetDistricts'])->name('districts.ajax_get');
Route::post('regions/ajax_get', [RegionsController::class, 'ajaxGetRegions'])->name('regions.ajax_get');
Route::post('system_role_permissions/ajax_update_multiple', [SystemRolePermissionsController::class, 'ajaxUpdateMultiple'])->name('system_role_permissions.ajax_update_multiple');
Route::post('user_account/ajax_change_password', [UserAccountController::class, 'ajaxChangePassword'])->name('user_account.ajax_change_password');
Route::post('leaves/ajax_check_date', [LeavesController::class, 'ajaxCheckDate'])->name('leaves.ajax_check_date');




/************************ ROUTE FOR FILE PREVIEWING AND DOWNLOADS *************/
Route::get('/leave_supporting_documents/{filename}',[LeavesController::class, 'viewDocument'])
    ->where('filename', '[A-Za-z0-9\-\_\.]+');

Route::get('/staff_dependents_certificates/{filename}',[StaffDependentsController::class, 'viewDocument'])
    ->where('filename', '[A-Za-z0-9\-\_\.]+');

Route::get('/staff_status_attachments/{filename}',[StaffController::class, 'viewDocument'])
    ->where('filename', '[A-Za-z0-9\-\_\.]+');

Route::get('/advance_payment_requests/attachments/{filename}',[AdvancePaymentRequestsController::class, 'viewDocument'])
    ->where('filename', '[A-Za-z0-9\-\_\.]+');

//Route::get('/leave_supporting_documents/{filename}','LeavesController@downloadDocument')
//   ->where('filename', '[A-Za-z0-9\-\_\.]+');




/************************ DEVELOPER ONLY ROUTES *************/
//Route::get('/generate_system_roles_permissions','SystemRolePermissionsController@createAllPermissionsForAllRoles');
Route::get('/clean_application_tables',[DeveloperProcessesController::class, 'cleanApplicationTables']);

Route::get('/relink_storage', function () {
    Artisan::call('storage:link');
});

Route::get('/clear_all', function () {
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
});

Route::get('/nav', function () {
    //event( new \App\Events\SendTimeSheetToBC130());

    $recipient_type = 'staff';
    $recipient = \App\Models\Staff::find('14');
    Mail::to('susumashoma@gmail.com')->send(new PasswordReset($recipient_type,$recipient));
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
