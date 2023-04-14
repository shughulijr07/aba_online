@component('mail::message')


@if($recipient_type == 'staff')

# Leave Request Approved By Supervisor

<p>
    Hello {{$recipient->first_name}}, Your request for LEAVE from {{date("d-m-Y",strtotime($leave->starting_date))}} to
    {{date("d-m-Y",strtotime($leave->ending_date))}} have been approved by Supervisor, please wait for
    Human Resource Manager Approval.<br>
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
    Hello {{ucwords($recipient->first_name)}}, you have Approved Leave Request made by {{ucwords($leave->staff->first_name.' '.$leave->staff->last_name)}}
    starting from {{date("d-m-Y",strtotime($leave->starting_date))}} to {{date("d-m-Y",strtotime($leave->ending_date))}}.<br>
    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($leave->updated_at))}}<br>
    - Leave Request No. is {{$leave->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login Into The Portal
@endcomponent

@endif






@if($recipient_type == 'hrm')

# Leave Request Waiting For HRM Approval

<p>
    Hello {{ucwords($recipient->first_name)}}, leave Request from {{ucwords($leave->staff->first_name.' '.$leave->staff->last_name)}}
    starting from {{date("d-m-Y",strtotime($leave->starting_date))}} to {{date("d-m-Y",strtotime($leave->ending_date))}},
    have been approved by supervisor and currently it is waiting for your approval, to approve the request please login into the Portal.<br>
    - Time of Approval By Supervisor | {{date("d-m-Y H:i:s", strtotime($leave->updated_at))}}<br>
    - Request No. is {{$leave->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login Into The Portal
@endcomponent

@endif





@if($recipient_type == 'md')

# Leave Request Waiting For HRM Approval

<p>
    Hello {{ucwords($recipient->first_name)}}, leave Request from {{ucwords($leave->staff->first_name.' '.$leave->staff->last_name)}}
    starting from {{date("d-m-Y",strtotime($leave->starting_date))}} to {{date("d-m-Y",strtotime($leave->ending_date))}},
    have been approved by supervisor and currently it is waiting for Human Resource Manager Approval<br>
    - Time of Approval By Supervisor | {{date("d-m-Y H:i:s", strtotime($leave->updated_at))}}<br>
    - Request No. is {{$leave->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login Into The Portal
@endcomponent

@endif





@endcomponent
