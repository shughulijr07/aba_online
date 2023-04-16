{{-- Check if the logged in user is supervisor/superadmin --}}
@php
        $user_id = Auth::user()->id;
        $staff_data = \App\Models\Staff::where('user_id',$user_id)->first();
        $isSupervisor = DB::table('supervisors')->where('staff_id', $staff_data->id)->first();
        $role_id = Auth::user()->role_id;
@endphp
{{-- End of Check if the logged in user is supervisor or not --}}


<ul class="vertical-nav-menu">

    <!-- Dashboard Menu -->
    @can('view-menu','dashboard_menu')
    <li class="app-sidebar__heading menu-title invisible">Dashboard</li>
    <li class="mm-active mt-3">
        <a @switch ( auth()->user()->role_id )
            @case (1) href="/super-administrator" @break
            @case (2) href="/managing-director" @break
            @case (3) href="/human-resource-manager" @break
            @case (4) href="/accountant" @break
            @case (5) href="/supervisor" @break
            @case (6) href="/employee" @break
            @case (7) href="/system-administrator" @break
            @case (8) href="/system-administrator" @break
            @case (9) href="/finance-director" @break
            @default href="#" @break
            @endswitch
            >
            <i class="metismenu-icon pe-7s-home">
            </i>Home
        </a>
    </li>
    @endcan
    <!-- End Of Dashboard Menu -->


    <!-- Leave Management Menu-->
    @can('view-menu','leave_menu')
    <li class="app-sidebar__heading menu-title" id="leave-menu-title" style="cursor:pointer">Leave Management</li>
    <div class="menu-content" id="leave-menu-content">
        @can('view-menu','my_leave_requests_menu_item')
        <li>
            <a href="#">
                <i class="metismenu-icon  pe-7s-date"></i>
                Leave Planner
                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
            </a>
            <ul>
                <li>
                    <a href="/leave_plans/create">
                        <i class="metismenu-icon">
                        </i>Create Plan <span class="text-danger"></span>
                    </a>
                </li>
                <li>
                    <a href="/leave_plans">
                        <i class="metismenu-icon">
                        </i>My Plans
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="{{url('/request_leave')}}">
                <i class="metismenu-icon  pe-7s-note2">
                </i>Request Leave
            </a>
        </li>
        <li>
            <a href="#">
                <i class="metismenu-icon pe-7s-photo-gallery"></i>
                My Requests
                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
            </a>
            <ul>
                <li>
                    <a href="/leaves/10">
                        <i class="metismenu-icon">
                        </i>Waiting SPV Approval <span class="text-danger">({{ $leaveRequests['waitingForSPVApproval'] }})</span>
                    </a>
                </li>
                <li>
                    <a href="/leaves/20">
                        <i class="metismenu-icon">
                        </i>Waiting HRM Approval <span class="text-danger">({{ $leaveRequests['waitingForHRMApproval'] }})</span>
                    </a>
                </li>
                <li>
                    <a href="/leaves/30">
                        <i class="metismenu-icon">
                        </i>Waiting MD Approval <span class="text-danger">({{ $leaveRequests['waitingForMDApproval'] }})</span>
                    </a>
                </li>
                <li style="display: none;">
                    <a href="/leaves/40">
                        <i class="metismenu-icon">
                        </i>Waiting Payment <span class="text-danger">({{ $leaveRequests['approvedWaitingPayment'] }})</span>
                    </a>
                </li>
                <li>
                    <a href="/leaves/50">
                        <i class="metismenu-icon">
                        </i>Approved <span class="text-danger">({{ $leaveRequests['approved'] }})</span>
                    </a>
                </li>
                <li>
                    <a href="/leaves/99">
                        <i class="metismenu-icon">
                        </i>Rejected <span class="text-danger">({{ $leaveRequests['rejected'] }})</span>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="{{url('/my_leaves')}}">
                <i class="metismenu-icon  pe-7s-folder">
                </i>My Leaves
            </a>
        </li>
        @endcan
        @can('view-menu','leave_requests_for_approving_menu_item')
        <li>
            <a href="#">
                <i class="metismenu-icon pe-7s-pen"></i>
                Leave Approving
                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
            </a>
            <ul>
                <li>
                    <a href="/admin_leaves/10">
                        <i class="metismenu-icon">
                        </i>Waiting SPV Approval <span class="text-danger">({{ $leaveRequests['waitingForSPVApproval2'] }})</span>
                    </a>
                </li>
                <li>
                    <a href="/admin_leaves/20">
                        <i class="metismenu-icon">
                        </i>Waiting HRM Approval <span class="text-danger">({{ $leaveRequests['waitingForHRMApproval2'] }})</span>
                    </a>
                </li>
                <li>
                    <a href="/admin_leaves/30">
                        <i class="metismenu-icon">
                        </i>Waiting MD Approval <span class="text-danger">({{ $leaveRequests['waitingForMDApproval2'] }})</span>
                    </a>
                </li>
                <li style="display: none;">
                    <a href="/admin_leaves/40">
                        <i class="metismenu-icon">
                        </i>Waiting Payment <span class="text-danger">({{ $leaveRequests['approvedWaitingPayment2'] }})</span>
                    </a>
                </li>
                <li>
                    <a href="/admin_leaves/50">
                        <i class="metismenu-icon">
                        </i>Approved <span class="text-danger">({{ $leaveRequests['approved2'] }})</span>
                    </a>
                </li>
                <li>
                    <a href="/admin_leaves/99">
                        <i class="metismenu-icon">
                        </i>Rejected <span class="text-danger">({{ $leaveRequests['rejected2'] }})</span>
                    </a>
                </li>
            </ul>
        </li>
        @endcan
        @can('view-menu','leave_plans_approving_menu_item')
        <li>
            <a href="#">
                <i class="metismenu-icon pe-7s-pen"></i>
                Leave Plan Approving
                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
            </a>
            <ul>
                <li>
                    <a href="/admin_leave_plans/10">
                        <i class="metismenu-icon">
                        </i>Drafts <span class="text-danger">({{ $leavePlans['drafts2'] }})</span>
                    </a>
                </li>
                <li>
                    <a href="/admin_leave_plans/0">
                        <i class="metismenu-icon">
                        </i>Returned For Correction<span class="text-danger">({{ $leavePlans['returnedForCorrection2'] }})</span>
                    </a>
                </li>
                <li>
                    <a href="/admin_leave_plans/20">
                        <i class="metismenu-icon">
                        </i>Waiting SPV Approval <span class="text-danger">({{ $leavePlans['waitingForSPVApproval2'] }})</span>
                    </a>
                </li>
                <li>
                    <a href="/admin_leave_plans/30">
                        <i class="metismenu-icon">
                        </i>Waiting HRM Approval <span class="text-danger">({{ $leavePlans['waitingForHRMApproval2'] }})</span>
                    </a>
                </li>
                <li style="display: none;">
                    <a href="/admin_leave_plans/40">
                        <i class="metismenu-icon">
                        </i>Waiting MD Approval <span class="text-danger">({{ $leavePlans['waitingForMDApproval2'] }})</span>
                    </a>
                </li>
                <li>
                    <a href="/admin_leave_plans/50">
                        <i class="metismenu-icon">
                        </i>Approved <span class="text-danger">({{ $leavePlans['approved2'] }})</span>
                    </a>
                </li>
                <li>
                    <a href="/admin_leave_plans/99">
                        <i class="metismenu-icon">
                        </i>Rejected <span class="text-danger">({{ $leavePlans['rejected2'] }})</span>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="/leave_plan_summary/admin">
                <i class="metismenu-icon  pe-7s-date"></i>
                Leave Plans Summary
            </a>
        </li>
        @endcan
        @can('view-menu','leave_entitlement')
        <li>
            <a href="#">
                <i class="metismenu-icon pe-7s-settings"></i>
                Leave Entitlement
                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
            </a>
            <ul>
                <li>
                    <a href="/leave_entitlements/create">
                        <i class="metismenu-icon">
                        </i>New Entitlement
                    </a>
                </li>
                <li>
                    <a href="/leave_entitlements">
                        <i class="metismenu-icon">
                        </i>Entitlements List
                    </a>
                </li>
            </ul>
        </li>
        @endcan
        @can('view-menu','leave_reports')
        <li>
            <a href="{{url('/leave_reports')}}">
                <i class="metismenu-icon pe-7s-news-paper"></i>
                Reports
            </a>
        </li>
        @endcan
    </div>

    @endcan
    <!-- Leave Management Menu -->


    <!-- Time-sheet Management Menu-->
    @can('view-menu','time_sheet_menu')
    <li class="app-sidebar__heading menu-title" id="timesheet-menu-title" style="cursor:pointer">Time sheets</li>
    <div class="menu-content" id="timesheet-menu-content">
        @can('view-menu','my_time_sheets_menu_item')
       @if ($isSupervisor || $role_id == 1)
            <li>
            <a href="{{url('/time-sheets')}}">
                <i class="metismenu-icon  pe-7s-date">
                </i>Time Sheets
            </a>
        </li>
       @endif
        {{-- <li>
                    <a href="{{url('/new_time_sheet')}}">
        <i class="metismenu-icon  pe-7s-date">
        </i>New Time Sheet
        </a>
        </li> --}}
        <li>
            <a href="/time_sheets/10">
                <i class="metismenu-icon pe-7s-folder">
                </i>Drafts <span class="text-danger">({{ $timeSheets['drafts'] }})</span>
            </a>
        </li>
        <li>
            <a href="#">
                <i class="metismenu-icon pe-7s-albums"></i>
                Submitted Time Sheets
                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
            </a>
            <ul>
                <li>
                    <a href="/time_sheets/0">
                        <i class="metismenu-icon">
                        </i>Returned For Correction <span class="text-danger">({{ $timeSheets['returnedForCorrection'] }})</span>
                    </a>
                </li>
                <li>
                    <a href="/time_sheets/20">
                        <i class="metismenu-icon">
                        </i>Waiting SPV Approval <span class="text-danger">({{ $timeSheets['waitingForSPVApproval'] }})</span>
                    </a>
                </li>

                {{-- <li>
                    <a href="/time_sheets/30">
                        <i class="metismenu-icon">
                        </i>Waiting HRM Approval <span class="text-danger">({{ $timeSheets['waitingForHRMApproval'] }})</span>
                    </a>
                </li> --}}
                
                <li>
                    <a href="/time_sheets/40" style="display: none;">
                        <i class="metismenu-icon">
                        </i>Waiting MD Approval <span class="text-danger">({{ $timeSheets['waitingForMDApproval'] }})</span>
                    </a>
                </li>
                <li>
                    <a href="/time_sheets/50">
                        <i class="metismenu-icon">
                        </i>Approved <span class="text-danger">({{ $timeSheets['approved'] }})</span>
                    </a>
                </li>
                <li>
                    <a href="/time_sheets/99">
                        <i class="metismenu-icon">
                        </i>Rejected <span class="text-danger">({{ $timeSheets['rejected'] }})</span>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="{{url('/my_time_sheets')}}">
                <i class="metismenu-icon  pe-7s-folder">
                </i>My Time Sheets
            </a>
        </li>
        @endcan
        @if ($isSupervisor || $role_id == 1)
            @can('view-menu','time_sheet_approving_menu_item')
            <li>
                <a href="#">
                    <i class="metismenu-icon pe-7s-pen"></i>
                    Time Sheet Approving
                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                </a>
                <ul>
                    <li>
                        <a href="/admin_time_sheets/10">
                            <i class="metismenu-icon">
                            </i>Drafts <span class="text-danger">({{ $timeSheets['drafts2'] }})</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin_time_sheets/0">
                            <i class="metismenu-icon">
                            </i>Returned For Correction<span class="text-danger">({{ $timeSheets['returnedForCorrection2'] }})</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin_time_sheets/20">
                            <i class="metismenu-icon">
                            </i>Waiting SPV Approval <span class="text-danger">({{ $timeSheets['waitingForSPVApproval2'] }})</span>
                        </a>
                    </li>
                    {{-- <li>
                        <a href="/admin_time_sheets/30">
                            <i class="metismenu-icon">
                            </i>Waiting HRM Approval <span class="text-danger">({{ $timeSheets['waitingForHRMApproval2'] }})</span>
                        </a>
                    </li> --}}
                    <li>
                        <a href="/admin_time_sheets/40" style="display: none;">
                            <i class="metismenu-icon">
                            </i>Waiting MD Approval <span class="text-danger">({{ $timeSheets['waitingForMDApproval2'] }})</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin_time_sheets/50">
                            <i class="metismenu-icon">
                            </i>Approved <span class="text-danger">({{ $timeSheets['approved2'] }})</span>
                        </a>
                    </li>
                    <li>
                        <a href="/admin_time_sheets/99">
                            <i class="metismenu-icon">
                            </i>Rejected <span class="text-danger">({{ $timeSheets['rejected2'] }})</span>
                        </a>
                    </li>
                    @if( in_array( auth()->user()->role_id,[1,3]))
                    <li>
                        <a href="/time_sheet_late_submissions">
                            <i class="metismenu-icon">
                            </i>Delayed Time Sheets
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endcan
        @endif
        @can('view-menu','time_sheet_reports_menu_item')
        <li>
            <a href="{{url('/time_sheet_reports')}}">
                <i class="metismenu-icon  pe-7s-news-paper">
                </i>Time Sheet Reports
            </a>
        </li>
        @endcan
       @if ($isSupervisor || $role_id == 1)
            @can('view-menu','create_timesheet_for_another_staff_menu')
                <li>
                    <a href="{{url('/create_timesheet_for_another_staff')}}">
                        <i class="metismenu-icon  pe-7s-news-paper">
                        </i>Time Sheet For Another Staff
                    </a>
                </li>
            @endcan
       @endif
    </div>
    @endcan
    <!-- Time-sheet Management Menu -->


    <!-- Travel Management Menu -->
    {{-- @can('view-menu','travel_menu')
        <li class="app-sidebar__heading menu-title" id="travel-menu-title">Travel Authorization</li>
        <div class="menu-content" id="travel-menu-content">
            @can('view-menu','my_travel_requests_menu_item')
                <li>
                    <a href="{{url('/new_travel_request')}}">
    <i class="metismenu-icon  pe-7s-note2">
    </i>New Travel Request
    </a>
    </li>
    <li>
        <a href="#">
            <i class="metismenu-icon pe-7s-photo-gallery"></i>
            Submitted Requests
            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
        </a>
        <ul>
            <li>
                <a href="/travel_requests/0">
                    <i class="metismenu-icon">
                    </i>Returned For Correction <span class="text-danger">({{ $travelRequests['returnedForCorrection'] }})</span>
                </a>
            </li>
            <li>
                <a href="/travel_requests/10">
                    <i class="metismenu-icon">
                    </i>Waiting SPV Approval <span class="text-danger">({{ $travelRequests['waitingForSPVApproval'] }})</span>
                </a>
            </li>
            <li>
                <a href="/travel_requests/20">
                    <i class="metismenu-icon">
                    </i>Waiting ACC Approval <span class="text-danger">({{ $travelRequests['waitingForACCApproval'] }})</span>
                </a>
            </li>
            <li>
                <a href="/travel_requests/30">
                    <i class="metismenu-icon">
                    </i>Waiting FD Approval <span class="text-danger">({{ $travelRequests['waitingForFDApproval'] }})</span>
                </a>
            </li>
            <li>
                <a href="/travel_requests/40">
                    <i class="metismenu-icon">
                    </i>Waiting MD Approval <span class="text-danger">({{ $travelRequests['waitingForMDApproval'] }})</span>
                </a>
            </li>
            <li>
                <a href="/travel_requests/50">
                    <i class="metismenu-icon">
                    </i>Approved <span class="text-danger">({{ $travelRequests['approved'] }})</span>
                </a>
            </li>
            <li>
                <a href="/travel_requests/99">
                    <i class="metismenu-icon">
                    </i>Rejected <span class="text-danger">({{ $travelRequests['rejected'] }})</span>
                </a>
            </li>
        </ul>
    </li>
    <li>
        <a href="{{url('/my_travel_records')}}">
            <i class="metismenu-icon  pe-7s-folder">
            </i>Travel Records
        </a>
    </li>
    @endcan
    @can('view-menu','travel_requests_for_approving_menu_item')
    <li>
        <a href="#">
            <i class="metismenu-icon pe-7s-pen"></i>
            Travel Request Approving
            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
        </a>
        <ul>
            <li>
                <a href="/admin_travel_requests/0">
                    <i class="metismenu-icon">
                    </i>Returned For Correction <span class="text-danger">({{ $travelRequests['returnedForCorrection2'] }})</span>
                </a>
            </li>
            <li>
                <a href="/admin_travel_requests/10">
                    <i class="metismenu-icon">
                    </i>Waiting SPV Approval <span class="text-danger">({{ $travelRequests['waitingForSPVApproval2'] }})</span>
                </a>
            </li>
            <li>
                <a href="/admin_travel_requests/20">
                    <i class="metismenu-icon">
                    </i>Waiting ACC Approval <span class="text-danger">({{ $travelRequests['waitingForACCApproval2'] }})</span>
                </a>
            </li>
            <li>
                <a href="/admin_travel_requests/30">
                    <i class="metismenu-icon">
                    </i>Waiting FD Approval <span class="text-danger">({{ $travelRequests['waitingForFDApproval2'] }})</span>
                </a>
            </li>
            <li>
                <a href="/admin_travel_requests/40">
                    <i class="metismenu-icon">
                    </i>Waiting MD Approval <span class="text-danger">({{ $travelRequests['waitingForMDApproval2'] }})</span>
                </a>
            </li>
            <li>
                <a href="/admin_travel_requests/50">
                    <i class="metismenu-icon">
                    </i>Approved <span class="text-danger">({{ $travelRequests['approved2'] }})</span>
                </a>
            </li>
            <li>
                <a href="/admin_travel_requests/99">
                    <i class="metismenu-icon">
                    </i>Rejected <span class="text-danger">({{ $travelRequests['rejected2'] }})</span>
                </a>
            </li>
        </ul>
    </li>
    <li>
        <a href="{{url('/staff_travel_records')}}">
            <i class="metismenu-icon  pe-7s-folder">
            </i>Staff Travel Records
        </a>
    </li>
    @endcan
    @can('view-menu','travel_reports')
    <li>
        <a href="{{url('/travel_reports')}}" style="display: none;">
            <i class="metismenu-icon pe-7s-news-paper"></i>
            Reports
        </a>
    </li>
    @endcan
    </div>

    @endcan --}}
    <!-- Travel Menu -->

    <!-- Requisition Menu -->
    @can('view-menu','travel_menu')
    <li class="app-sidebar__heading menu-title" id="requisition-menu-title" style="cursor:pointer">Requisition</li>
    <div class="menu-content" id="requisition-menu-content">
        @can('view-menu','my_travel_requests_menu_item')
        <li>
            <a href="{{url('/new_requisition_request')}}">
                <i class="metismenu-icon  pe-7s-note2">
                </i>New Requisition Request
            </a>
        </li>
        <li>
            <a href="#">
                <i class="metismenu-icon pe-7s-photo-gallery"></i>
                Submitted Requests
                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
            </a>
            <ul>
                <li>
                    <a href="/requisition_requests/0">
                        <i class="metismenu-icon">
                        </i>Returned For Correction <span class="text-danger">({{ $travelRequests['returnedForCorrection'] }})</span>
                    </a>
                </li>
                <li>
                    <a href="/requisition_requests/10">
                        <i class="metismenu-icon">
                        </i>Waiting SPV Approval <span class="text-danger">({{ $travelRequests['waitingForSPVApproval'] }})</span>
                    </a>
                </li>
                <li>
                    <a href="/requisition_requests/20">
                        <i class="metismenu-icon">
                        </i>Waiting ACC Approval <span class="text-danger">({{ $travelRequests['waitingForACCApproval'] }})</span>
                    </a>
                </li>
                <li>
                    <a href="/requisition_requests/30">
                        <i class="metismenu-icon">
                        </i>Waiting FD Approval <span class="text-danger">({{ $travelRequests['waitingForFDApproval'] }})</span>
                    </a>
                </li>
                <li>
                    <a href="/requisition_requests/40">
                        <i class="metismenu-icon">
                        </i>Waiting MD Approval <span class="text-danger">({{ $travelRequests['waitingForMDApproval'] }})</span>
                    </a>
                </li>
                <li>
                    <a href="/requisition_requests/50">
                        <i class="metismenu-icon">
                        </i>Approved <span class="text-danger">({{ $travelRequests['approved'] }})</span>
                    </a>
                </li>
                <li>
                    <a href="/requisition_requests/99">
                        <i class="metismenu-icon">
                        </i>Rejected <span class="text-danger">({{ $travelRequests['rejected'] }})</span>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="{{url('/my_requisition_records')}}">
                <i class="metismenu-icon  pe-7s-folder">
                </i>Requisition Records
            </a>
        </li>
        @endcan
        @can('view-menu','travel_requests_for_approving_menu_item')
        <li>
            <a href="#">
                <i class="metismenu-icon pe-7s-pen"></i>
                Requisition Request Approving
                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
            </a>
            <ul>
                <li>
                    <a href="/admin_requisition_requests/0">
                        <i class="metismenu-icon">
                        </i>Returned For Correction <span class="text-danger">({{ $travelRequests['returnedForCorrection2'] }})</span>
                    </a>
                </li>
                <li>
                    <a href="/admin_requisition_requests/10">
                        <i class="metismenu-icon">
                        </i>Waiting SPV Approval <span class="text-danger">({{ $travelRequests['waitingForSPVApproval2'] }})</span>
                    </a>
                </li>
                <li>
                    <a href="/admin_requisition_requests/20">
                        <i class="metismenu-icon">
                        </i>Waiting ACC Approval <span class="text-danger">({{ $travelRequests['waitingForACCApproval2'] }})</span>
                    </a>
                </li>
                <li>
                    <a href="/admin_requisition_requests/30">
                        <i class="metismenu-icon">
                        </i>Waiting FD Approval <span class="text-danger">({{ $travelRequests['waitingForFDApproval2'] }})</span>
                    </a>
                </li>
                <li>
                    <a href="/admin_requisition_requests/40">
                        <i class="metismenu-icon">
                        </i>Waiting MD Approval <span class="text-danger">({{ $travelRequests['waitingForMDApproval2'] }})</span>
                    </a>
                </li>
                <li>
                    <a href="/admin_requisition_requests/50">
                        <i class="metismenu-icon">
                        </i>Approved <span class="text-danger">({{ $travelRequests['approved2'] }})</span>
                    </a>
                </li>
                <li>
                    <a href="/admin_requisition_requests/99">
                        <i class="metismenu-icon">
                        </i>Rejected <span class="text-danger">({{ $travelRequests['rejected2'] }})</span>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="{{url('/staff_requisition_records')}}">
                <i class="metismenu-icon  pe-7s-folder">
                </i>Staff Requisition Records
            </a>
        </li>
        @endcan
        @can('view-menu','travel_reports')
        <li>
            <a href="{{url('/requisition_reports')}}" style="display: none;">
                <i class="metismenu-icon pe-7s-news-paper"></i>
                Reports
            </a>
        </li>
        @endcan
    </div>

    @endcan
    <!-- Requisition Menu -->

    <!-- Advance Payment Menu -->
    {{-- @can('view-menu','advance_payment_requests_menu')
        <li class="app-sidebar__heading menu-title" id="payment-menu-title">Advance Payment</li>
        <div class="menu-content" id="payment-menu-content">
            @can('view-menu','my_advance_payment_requests_menu_item')
                <li>
                    <a href="{{url('/new_advance_payment_request')}}">
    <i class="metismenu-icon  pe-7s-note2">
    </i>Request Advance Payment
    </a>
    </li>
    <li>
        <a href="#">
            <i class="metismenu-icon pe-7s-photo-gallery"></i>
            Submitted Requests
            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
        </a>
        <ul>
            <li>
                <a href="/advance_payment_requests/0">
                    <i class="metismenu-icon">
                    </i>Returned For Correction <span class="text-danger">({{ $advancePaymentRequest['returnedForCorrection'] }})</span>
                </a>
            </li>
            <li>
                <a href="/advance_payment_requests/10">
                    <i class="metismenu-icon">
                    </i>Waiting SPV Approval <span class="text-danger">({{ $advancePaymentRequest['waitingForSPVApproval'] }})</span>
                </a>
            </li>
            <li>
                <a href="/advance_payment_requests/20">
                    <i class="metismenu-icon">
                    </i>Waiting ACC Approval <span class="text-danger">({{ $advancePaymentRequest['waitingForACCApproval'] }})</span>
                </a>
            </li>
            <li>
                <a href="/advance_payment_requests/30">
                    <i class="metismenu-icon">
                    </i>Waiting FD Approval <span class="text-danger">({{ $advancePaymentRequest['waitingForFDApproval'] }})</span>
                </a>
            </li>
            <li>
                <a href="/advance_payment_requests/40">
                    <i class="metismenu-icon">
                    </i>Waiting MD Approval <span class="text-danger">({{ $advancePaymentRequest['waitingForMDApproval'] }})</span>
                </a>
            </li>
            <li>
                <a href="/advance_payment_requests/50">
                    <i class="metismenu-icon">
                    </i>Approved <span class="text-danger">({{ $advancePaymentRequest['approved'] }})</span>
                </a>
            </li>
            <li>
                <a href="/advance_payment_requests/99">
                    <i class="metismenu-icon">
                    </i>Rejected <span class="text-danger">({{ $advancePaymentRequest['rejected'] }})</span>
                </a>
            </li>
        </ul>
    </li>
    <li>
        <a href="{{url('/my_advance_payment_records')}}">
            <i class="metismenu-icon  pe-7s-folder">
            </i>Advance Payment Records
        </a>
    </li>
    @endcan
    @can('view-menu','advance_payment_requests_for_approving_menu_item')
    <li>
        <a href="#">
            <i class="metismenu-icon pe-7s-pen"></i>
            Adv. Payment Approving
            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
        </a>
        <ul>
            <li>
                <a href="/admin_advance_payment_requests/0">
                    <i class="metismenu-icon">
                    </i>Returned For Correction <span class="text-danger">({{ $advancePaymentRequest['returnedForCorrection2'] }})</span>
                </a>
            </li>
            <li>
                <a href="/admin_advance_payment_requests/10">
                    <i class="metismenu-icon">
                    </i>Waiting SPV Approval <span class="text-danger">({{ $advancePaymentRequest['waitingForSPVApproval2'] }})</span>
                </a>
            </li>
            <li>
                <a href="/admin_advance_payment_requests/20">
                    <i class="metismenu-icon">
                    </i>Waiting ACC Approval <span class="text-danger">({{ $advancePaymentRequest['waitingForACCApproval2'] }})</span>
                </a>
            </li>
            <li>
                <a href="/admin_advance_payment_requests/30">
                    <i class="metismenu-icon">
                    </i>Waiting FD Approval <span class="text-danger">({{ $advancePaymentRequest['waitingForFDApproval2'] }})</span>
                </a>
            </li>
            <li>
                <a href="/admin_advance_payment_requests/40">
                    <i class="metismenu-icon">
                    </i>Waiting MD Approval <span class="text-danger">({{ $advancePaymentRequest['waitingForMDApproval2'] }})</span>
                </a>
            </li>
            <li>
                <a href="/admin_advance_payment_requests/50">
                    <i class="metismenu-icon">
                    </i>Approved <span class="text-danger">({{ $advancePaymentRequest['approved2'] }})</span>
                </a>
            </li>
            <li>
                <a href="/admin_advance_payment_requests/99">
                    <i class="metismenu-icon">
                    </i>Rejected <span class="text-danger">({{ $advancePaymentRequest['rejected2'] }})</span>
                </a>
            </li>
        </ul>
    </li>
    <li>
        <a href="{{url('/staff_advance_payment_records')}}">
            <i class="metismenu-icon  pe-7s-folder">
            </i>Staff Adv. Payment Records
        </a>
    </li>
    @endcan
    @can('view-menu','advance_payment_requests_reports')
    <li>
        <a href="{{url('/advance_payment_reports')}}" style="display: none;">
            <i class="metismenu-icon pe-7s-news-paper"></i>
            Reports
        </a>
    </li>
    @endcan
    </div>

    @endcan --}}
    <!-- Requisition Menu -->

    {{-- <!-- Requisition Menu -->
@can('view-menu','travel_menu')
        <li class="app-sidebar__heading menu-title " id="retirement-menu-title">Retirement</li>
        <div class="menu-content" id="retirement-menu-content">
            @can('view-menu','my_travel_requests_menu_item')
                <li>
                    <a href="{{url('admin_retirement_requests/50')}}">
    <i class="metismenu-icon  pe-7s-note2">
    </i>New Retirement Request
    </a>
    </li>
    <li>
        <a href="#">
            <i class="metismenu-icon pe-7s-photo-gallery"></i>
            Submitted Requests
            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
        </a>
        <ul>
            <li>
                <a href="/retirement_requests/0">
                    <i class="metismenu-icon">
                    </i>Returned For Correction <span class="text-danger">({{ $travelRequests['returnedForCorrection'] }})</span>
                </a>
            </li>
            <li>
                <a href="/retirement_requests/10">
                    <i class="metismenu-icon">
                    </i>Waiting SPV Approval <span class="text-danger">({{ $travelRequests['waitingForSPVApproval'] }})</span>
                </a>
            </li>
            <li>
                <a href="/retirement_requests/20">
                    <i class="metismenu-icon">
                    </i>Waiting ACC Approval <span class="text-danger">({{ $travelRequests['waitingForACCApproval'] }})</span>
                </a>
            </li>
            <li>
                <a href="/retirement_requests/30">
                    <i class="metismenu-icon">
                    </i>Waiting FD Approval <span class="text-danger">({{ $travelRequests['waitingForFDApproval'] }})</span>
                </a>
            </li>
            <li>
                <a href="/retirement_requests/40">
                    <i class="metismenu-icon">
                    </i>Waiting MD Approval <span class="text-danger">({{ $travelRequests['waitingForMDApproval'] }})</span>
                </a>
            </li>
            <li>
                <a href="/retirement_requests/50">
                    <i class="metismenu-icon">
                    </i>Approved <span class="text-danger">({{ $travelRequests['approved'] }})</span>
                </a>
            </li>
            <li>
                <a href="/retirement_requests/99">
                    <i class="metismenu-icon">
                    </i>Rejected <span class="text-danger">({{ $travelRequests['rejected'] }})</span>
                </a>
            </li>
        </ul>
    </li>
    <li>
        <a href="{{url('/my_retirement_records')}}">
            <i class="metismenu-icon  pe-7s-folder">
            </i>Retirement Records
        </a>
    </li>
    @endcan
    @can('view-menu','travel_requests_for_approving_menu_item')
    <li>
        <a href="#">
            <i class="metismenu-icon pe-7s-pen"></i>
            Retirement Request Approving
            <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
        </a>
        <ul>
            <li>
                <a href="/admin_retirement_requests/0">
                    <i class="metismenu-icon">
                    </i>Returned For Correction <span class="text-danger">({{ $travelRequests['returnedForCorrection2'] }})</span>
                </a>
            </li>
            <li>
                <a href="/admin_retirement_requests/10">
                    <i class="metismenu-icon">
                    </i>Waiting SPV Approval <span class="text-danger">({{ $travelRequests['waitingForSPVApproval2'] }})</span>
                </a>
            </li>
            <li>
                <a href="/admin_retirement_requests/20">
                    <i class="metismenu-icon">
                    </i>Waiting ACC Approval <span class="text-danger">({{ $travelRequests['waitingForACCApproval2'] }})</span>
                </a>
            </li>
            <li>
                <a href="/admin_retirement_requests/30">
                    <i class="metismenu-icon">
                    </i>Waiting FD Approval <span class="text-danger">({{ $travelRequests['waitingForFDApproval2'] }})</span>
                </a>
            </li>
            <li>
                <a href="/admin_retirement_requests/40">
                    <i class="metismenu-icon">
                    </i>Waiting MD Approval <span class="text-danger">({{ $travelRequests['waitingForMDApproval2'] }})</span>
                </a>
            </li>
            <li>
                <a href="/admin_retirement_requests/50">
                    <i class="metismenu-icon">
                    </i>Approved <span class="text-danger">({{ $travelRequests['approved2'] }})</span>
                </a>
            </li>
            <li>
                <a href="/admin_retirement_requests/99">
                    <i class="metismenu-icon">
                    </i>Rejected <span class="text-danger">({{ $travelRequests['rejected2'] }})</span>
                </a>
            </li>
        </ul>
    </li>
    <li>
        <a href="{{url('/staff_retirement_records')}}">
            <i class="metismenu-icon  pe-7s-folder">
            </i>Staff Retirement Records
        </a>
    </li>
    @endcan
    @can('view-menu','travel_reports')
    <li>
        <a href="{{url('/retirement_reports')}}" style="display: none;">
            <i class="metismenu-icon pe-7s-news-paper"></i>
            Reports
        </a>
    </li>
    @endcan
    </div>

    @endcan
    <!-- Requisition Menu --> --}}



    <!-- Staffs Menu -->
    @can('view-menu','staff_menu')
    <li class="app-sidebar__heading menu-title" id="staff-menu-title" style="cursor:pointer">Staffs</li>
    <div class="menu-content" id="staff-menu-content">
        <li>
            <a href="{{url('/staff')}}">
                <i class="metismenu-icon  pe-7s-users">
                </i>List Of Staffs
            </a>
        </li>
        <li>
            <a href="{{url('/staff_supervisors')}}">
                <i class="metismenu-icon  pe-7s-users">
                </i>Staff Supervisors'
            </a>
        </li>
        <li>
            <a href="{{url('/supervisors')}}">
                <i class="metismenu-icon  pe-7s-users">
                </i>Supervisors'
            </a>
        </li>
    </div>
    @endcan
    <!-- End Of Staffs Menu -->


    <!-- My Account Menu -->
    @can('view-menu','my_account')
    <li class="app-sidebar__heading menu-title" id="myaccount-menu-title" style="cursor:pointer">My Account</li>
    <div class="menu-content" id="myaccount-menu-content">
        <li style="display: none;">
            <a href="{{url('/account_information')}}">
                <i class="metismenu-icon  pe-7s-door-lock">
                </i>Account Information
            </a>
        </li>
        <li>
            <a href="{{url('/change_password')}}">
                <i class="metismenu-icon  pe-7s-door-lock">
                </i>Change Password
            </a>
        </li>
        @can('view-menu','staff_biographical_data_menu')
        @if( !isset(auth()->user()->staff->staff_biographical_data_sheet) )
        <li>
            <a href="{{url('/staff_biographical_data_sheets/create')}}">
                <i class="metismenu-icon  pe-7s-note">
                </i>Fill Bio Data Form
            </a>
        </li>
        @else
        <li>
            <a href="{{url('/staff_biographical_data_sheets/'.auth()->user()->staff->staff_biographical_data_sheet->id)}}">
                <i class="metismenu-icon  pe-7s-note2">
                </i>View Bio Data
            </a>
        </li>
        @endif
        <li>
            <a href="{{url('/staff_dependents')}}">
                <i class="metismenu-icon  pe-7s-users">
                </i>Dependents
            </a>
        </li>
        <li>
            <a href="{{url('/staff_emergency_contacts')}}">
                <i class="metismenu-icon  pe-7s-users">
                </i>Emergency Contacts
            </a>
        </li>
        @endcan
    </div>
    @endcan
    <!-- End Of My Account Menu -->




    <!-- Settings Menu -->
    @can('view-menu','settings_menu')
    <li class="app-sidebar__heading menu-title" id="settings-menu-title" style="cursor:pointer">Settings</li>
    <div class="menu-content" id="settings-menu-content">
        @can('view-menu','company_information_settings')
        <li>
            <a href="{{url('/company_information')}}">
                <i class="metismenu-icon pe-7s-culture"></i> Company Information
            </a>
        </li>
        @endcan
        @can('view-menu','general_settings_menu')
        <li>
            <a href="{{url('/general_settings')}}">
                <i class="metismenu-icon pe-7s-config"></i> General Settings
            </a>
        </li>
        @endcan
        @can('view-menu','holidays_setting_menu')
        <li>
            <a href="#">
                <i class="metismenu-icon  pe-7s-date"></i> Holidays
                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
            </a>
            <ul>
                <li>
                    <a href="{{url('/holidays_list/'.date('Y'))}}">
                        <i class="metismenu-icon">
                        </i>Holidays <span class="text-danger">({{date('Y')}})</span>
                    </a>
                </li>
                <li>
                    <a href="{{url('/holidays')}}">
                        <i class="metismenu-icon">
                        </i>Previous Years Holidays'
                    </a>
                </li>
            </ul>
        </li>
        @endcan
        @can('view-menu','projects_menu')
        <li>
            <a href="#">
                <i class="metismenu-icon  pe-7s-albums"></i> Clients
                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
            </a>
            <ul>
                <li>
                    <a href="{{url('/active_projects')}}">
                        <i class="metismenu-icon">
                        </i>Active Clients</span>
                    </a>
                </li>
                <li>
                    <a href="{{url('/projects')}}">
                        <i class="metismenu-icon">
                        </i>All Clients
                    </a>
                </li>
                <li>
                    <a href="{{url('/activities')}}">
                        <i class="metismenu-icon">
                        </i>Activities
                    </a>
                </li>
            </ul>
        </li>
        @endcan
        @can('view-menu','gl_accounts_menu')
        <li>
            <a href="{{url('/gl_accounts')}}">
                <i class="metismenu-icon pe-7s-config"></i> GL Accounts
            </a>
        </li>
        @endcan
        @can('view-menu','leave_types_settings')
        <li>
            <a href="#">
                <i class="metismenu-icon  pe-7s-help2"></i> Leaves
                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
            </a>
            <ul>
                <li>
                    <a href="{{url('/leave_types')}}">
                        <i class="metismenu-icon">
                        </i>Type Of Leaves</span>
                    </a>
                </li>
            </ul>
        </li>
        @endcan
        @can('view-menu','system_users')
        <li>
            <a href="{{url('/system_users')}}">
                <i class="metismenu-icon pe-7s-users"></i> Sytem Users
            </a>
        </li>
        @endcan
        @can('view-menu','system_roles_settings')
        <li>
            <a href="{{url('/system_roles')}}">
                <i class="metismenu-icon pe-7s-users"></i> System Roles
            </a>
        </li>
        <li>
            <a href="{{url('/user_activities')}}">
                <i class="metismenu-icon pe-7s-timer">
                </i>User Activities
            </a>
        </li>
        @endcan
        @can('view-menu','permissions_settings')
        <li>
            <a href="{{url('/permissions')}}">
                <i class="metismenu-icon pe-7s-users"></i> Permissions
            </a>
        </li>
        @endcan
        @can('view-menu','staff_settings')
        <li>
            <a href="#">
                <i class="metismenu-icon fas fa-user-cog"></i>Staffs
                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
            </a>
            <ul>
                <li>
                    <a href="{{url('/departments')}}">
                        <i class="metismenu-icon">
                        </i>Departments
                    </a>
                </li>
                <li>
                    <a href="{{url('/staff_job_titles')}}">
                        <i class="metismenu-icon">
                        </i>Job Titles
                    </a>
                </li>
            </ul>
        </li>
        @endcan
        @can('view-menu','location_settings')
        <li>
            <a href="#">
                <i class="metismenu-icon fas fa-globe-africa"></i>
                Locations
                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
            </a>
            <ul>
                <li>
                    <a href="{{url('/countries')}}">
                        <i class="metismenu-icon"> </i>Countries
                    </a>
                </li>
                <li>
                    <a href="{{url('/regions')}}">
                        <i class="metismenu-icon"></i>Regions
                    </a>
                </li>
                <li>
                    <a href="{{url('/districts')}}">
                        <i class="metismenu-icon"></i>Districts
                    </a>
                </li>
                <li>
                    <a href="{{url('/wards')}}">
                        <i class="metismenu-icon"></i>Wards
                    </a>
                </li>
            </ul>
        </li>
        @endcan

        <!---------- DEVELOPER MENU ------->
        @can('view-menu','developer_menu')
        <li>
            <a href="#">
                <i class="metismenu-icon pe-7s-ribbon"></i>Numbering
                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
            </a>
            <ul>
                <li>
                    <a href="{{ url('/numbered_items') }}">
                        <i class="metismenu-icon"></i>Numbered Items
                    </a>
                </li>
            </ul>
            <ul>
                <li>
                    <a href="{{ url('/number_series') }}">
                        <i class="metismenu-icon"></i>Number Series
                    </a>
                </li>
            </ul>
        </li>
        @endcan


        @can('view-menu','developer_menu')
        <li>
            <a href="#">
                <i class="metismenu-icon fas fa-users"></i>Developer Processes
                <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
            </a>
            <ul>
                <li>
                    <a href="{{ url('/generate_system_roles_permissions') }}">
                        <i class="metismenu-icon"></i>Generate System Roles Permissions
                    </a>
                </li>
            </ul>
            <ul>
                <li>
                    <a href="{{ url('/clean_application_tables') }}">
                        <i class="metismenu-icon"></i>Clean Tables
                    </a>
                </li>
            </ul>
        </li>
        @endcan
    </div>

    @endcan
    <!-- End Of Settings Menu -->

</ul>

<script type="text/javascript">
    $(document).ready(function() {

        $('.menu-content').hide();
        //$('#leave-menu-content').show('slow');

    });

    $('.app-sidebar__heading').on('click', function() {
        var section_title_id = $(this).attr('id');
        var id_contents = section_title_id.split('-');
        var section_name = id_contents[0];
        var section_content_id = '#' + section_name + '-' + 'menu-content';

        // $('.menu-content').hide();
        $(section_content_id).toggle('slow');
    });
</script>