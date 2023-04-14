@component('mail::message')


@if($recipient_type == 'staff')


# Leave Request Approved By HRM

<p>
    Hello {{$recipient->first_name}}, Your request for LEAVE from {{date("d-m-Y",strtotime($leave->starting_date))}} to
    {{date("d-m-Y",strtotime($leave->ending_date))}} have been approved by Human Resource Manager, please wait for
    Managing Director's Approval.<br>
    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($leave->updated_at))}}<br>
    - Your Leave Request No. is {{$leave->id}}. For assistance please contact System Administrator or Human Resource Manager
</p>


@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif





@if($recipient_type == 'spv')

#Leave Request Approval

<p>
    Hello {{ucwords($recipient->first_name)}}, the Leave Request made by {{ucwords($leave->staff->first_name.' '.$leave->staff->last_name)}}
    starting from {{date("d-m-Y",strtotime($leave->starting_date))}} to {{date("d-m-Y",strtotime($leave->ending_date))}}
    have been approved by Human Resource Manager.<br>
    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($leave->updated_at))}}<br>
    - Leave Request No. is {{$leave->id}}.
</p>


@component('mail::button', ['url' => url('/')])
    Click Here To Login Into The Portal
@endcomponent

@endif






@if($recipient_type == 'hrm')

# Leave Approval

<p>
    Hello {{ucwords($recipient->first_name)}}, leave Request from {{ucwords($leave->staff->first_name.' '.$leave->staff->last_name)}}
    starting from {{date("d-m-Y",strtotime($leave->starting_date))}} to {{date("d-m-Y",strtotime($leave->ending_date))}},
    have been approved successfully.<br>
    - Time of Approval By HRM | {{date("d-m-Y H:i:s", strtotime($leave->updated_at))}}<br>
    - Request No. is {{$leave->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login Into The Portal
@endcomponent

@endif






@if($recipient_type == 'accountant')

# Leave Payment Issuing Confirmed

<p>
    Hello {{ucwords($recipient->first_name)}}, {{ucwords($leave->staff->first_name.' '.$leave->staff->last_name)}} has requested
    {{strtoupper( str_replace('_',' ',$leave->type) )}} which will start from {{date("d-m-Y",strtotime($leave->starting_date))}} and ends on
    {{date("d-m-Y",strtotime($leave->ending_date))}}, the request have been approved by HRM please issue payment for this leave.
    to see more information about the Leave Request please log into the portal<br>
    - Time of Approval By HRM | {{date("d-m-Y H:i:s", strtotime($leave->updated_at))}}<br>
    - Request No. is {{$leave->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login Into The Portal
@endcomponent

@endif






@if($recipient_type == 'md')

# Leave Request Waiting For Managing Director's Approval

<p>
    Hello {{ucwords($recipient->first_name)}}, leave Request from {{ucwords($leave->staff->first_name.' '.$leave->staff->last_name)}}
    starting from {{date("d-m-Y",strtotime($leave->starting_date))}} to {{date("d-m-Y",strtotime($leave->ending_date))}},
    have been approved by Human Resource Manager and currently it is waiting for your approval, to approve the request please login into the Portal.<br>
    - Time of Approval By HRM | {{date("d-m-Y H:i:s", strtotime($leave->updated_at))}}<br>
    - Request No. is {{$leave->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login Into The Portal
@endcomponent

@endif





@endcomponent
