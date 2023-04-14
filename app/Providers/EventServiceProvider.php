<?php

namespace App\Providers;

use App\Events\LeavePaymentConfirmedEvent;
use App\Events\LeaveRequestRejectedEvent;
use App\Events\LeaveRequestApprovedByHRMEvent;
use App\Events\LeaveRequestApprovedBySupervisorEvent;
use App\Events\LeaveRequestApprovedByMDEvent;
use App\Events\LeaveRequestReceivedEvent;
use App\Events\PerformanceObjectivesApprovedByHRMEvent;
use App\Events\PerformanceObjectivesApprovedByMDEvent;
use App\Events\PerformanceObjectivesApprovedBySupervisorEvent;
use App\Events\PerformanceObjectivesRejectedEvent;
use App\Events\PerformanceObjectivesReturnedEvent;
use App\Events\PerformanceObjectivesSubmittedEvent;
use App\Events\RequestActivityEvent;
use App\Events\ResetPasswordEvent;
use App\Events\SendTimeSheetToBC130;
use App\Events\SendTravelRequestToBC130;
use App\Events\TimeSheetApprovedByHRMEvent;
use App\Events\TimeSheetApprovedByMDEvent;
use App\Events\TimeSheetApprovedBySupervisorEvent;
use App\Events\TimeSheetRejectedEvent;
use App\Events\TimeSheetReturnedEvent;
use App\Events\TimeSheetSubmittedEvent;
use App\Events\LeavePlanApprovedByHRMEvent;
use App\Events\LeavePlanApprovedByMDEvent;
use App\Events\LeavePlanApprovedBySupervisorEvent;
use App\Events\LeavePlanRejectedEvent;
use App\Events\LeavePlanReturnedEvent;
use App\Events\LeavePlanSubmittedEvent;
use App\Events\TravelRequestApprovedByAccountantEvent;
use App\Events\TravelRequestApprovedByFDEvent;
use App\Events\TravelRequestApprovedByMDEvent;
use App\Events\TravelRequestApprovedBySupervisorEvent;
use App\Events\TravelRequestRejectedEvent;
use App\Events\TravelRequestReturnedEvent;
use App\Events\TravelRequestSubmittedEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        RequestActivityEvent::class =>[
            \App\Listners\Requests\RequestActivityListner::class,
        ],
        LeaveRequestReceivedEvent::class =>[
            \App\Listners\LeaveRequests\LeaveRequestReceivedListner::class,
        ],
        LeaveRequestApprovedBySupervisorEvent::class =>[
            \App\Listners\LeaveRequests\LeaveRequestApprovedBySupervisorListner::class,
        ],
        LeaveRequestApprovedByHRMEvent::class =>[
            \App\Listners\LeaveRequests\LeaveRequestApprovedByHRMListner::class,
        ],
        LeaveRequestApprovedByMDEvent::class =>[
            \App\Listners\LeaveRequests\LeaveRequestApprovedByMDListner::class,
        ],
        LeavePaymentConfirmedEvent::class =>[
            \App\Listners\LeaveRequests\LeavePaymentConfirmationListner::class,
        ],
        LeaveRequestRejectedEvent::class =>[
            \App\Listners\LeaveRequests\LeaveRequestRejectedListner::class,
        ],
        TimeSheetSubmittedEvent::class =>[
            \App\Listners\TimeSheets\TimeSheetSubmittedListner::class,
        ],
        TimeSheetApprovedBySupervisorEvent::class =>[
            \App\Listners\TimeSheets\TimeSheetApprovedBySupervisorListner::class,
        ],
        TimeSheetApprovedByHRMEvent::class =>[
            \App\Listners\TimeSheets\TimeSheetApprovedByHRMListner::class,
        ],
        TimeSheetApprovedByMDEvent::class =>[
            \App\Listners\TimeSheets\TimeSheetApprovedByMDListner::class,
        ],
        TimeSheetReturnedEvent::class =>[
            \App\Listners\TimeSheets\TimeSheetReturnedListner::class,
        ],
        TimeSheetRejectedEvent::class =>[
            \App\Listners\TimeSheets\TimeSheetRejectedListner::class,
        ],
        LeavePlanSubmittedEvent::class =>[
            \App\Listners\LeavePlans\LeavePlanSubmittedListner::class,
        ],
        LeavePlanApprovedBySupervisorEvent::class =>[
            \App\Listners\LeavePlans\LeavePlanApprovedBySupervisorListner::class,
        ],
        LeavePlanApprovedByHRMEvent::class =>[
            \App\Listners\LeavePlans\LeavePlanApprovedByHRMListner::class,
        ],
        LeavePlanApprovedByMDEvent::class =>[
            \App\Listners\LeavePlans\LeavePlanApprovedByMDListner::class,
        ],
        LeavePlanReturnedEvent::class =>[
            \App\Listners\LeavePlans\LeavePlanReturnedListner::class,
        ],
        LeavePlanRejectedEvent::class =>[
            \App\Listners\LeavePlans\LeavePlanRejectedListner::class,
        ],
        PerformanceObjectivesSubmittedEvent::class =>[
            \App\Listners\PerformanceObjectives\PerformanceObjectivesSubmittedListner::class,
        ],
        PerformanceObjectivesApprovedBySupervisorEvent::class =>[
            \App\Listners\PerformanceObjectives\PerformanceObjectivesApprovedBySupervisorListner::class,
        ],
        PerformanceObjectivesApprovedByHRMEvent::class =>[
            \App\Listners\PerformanceObjectives\PerformanceObjectivesApprovedByHRMListner::class,
        ],
        PerformanceObjectivesApprovedByMDEvent::class =>[
            \App\Listners\PerformanceObjectives\PerformanceObjectivesApprovedByMDListner::class,
        ],
        PerformanceObjectivesReturnedEvent::class =>[
            \App\Listners\PerformanceObjectives\PerformanceObjectivesReturnedListner::class,
        ],
        PerformanceObjectivesRejectedEvent::class =>[
            \App\Listners\PerformanceObjectives\PerformanceObjectivesRejectedListner::class,
        ],
        TravelRequestSubmittedEvent::class =>[
            \App\Listners\TravelRequests\TravelRequestSubmittedListner::class,
        ],
        TravelRequestApprovedBySupervisorEvent::class =>[
            \App\Listners\TravelRequests\TravelRequestApprovedBySupervisorListner::class,
        ],
        TravelRequestApprovedByFDEvent::class =>[
            \App\Listners\TravelRequests\TravelRequestApprovedByFDListner::class,
        ],
        TravelRequestApprovedByMDEvent::class =>[
            \App\Listners\TravelRequests\TravelRequestApprovedByMDListner::class,
        ],
        ResetPasswordEvent::class =>[
            \App\Listners\Password\EmailNotifyAboutPasswordReset::class,
        ],
        SendTimeSheetToBC130::class =>[
            \App\Listners\BC130\SendTimeSheetDataToBC130::class,
        ],
        SendTravelRequestToBC130::class =>[
            \App\Listners\BC130\SendTravelRequestDataToBC130::class,
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
