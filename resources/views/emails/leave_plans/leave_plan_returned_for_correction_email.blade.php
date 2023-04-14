@component('mail::message')



@if($recipient_type == 'staff')

# Leave Plan Returned For Correction

<p>
    Hello {{$recipient->first_name}}, your Leave Plan for the year
    {{$leave_plan->year}} have been Returned For Correction by
    @if($return_level == 'spv') Supervisor
    @elseif($return_level == 'hrm') Human Resource Manager
    @elseif($return_level == 'md') Managing Director
    @endif
    <br>
    - Time of Returning | {{date("d-m-Y H:i:s", strtotime($leave_plan->updated_at))}}<br>
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif



@if($recipient_type == $return_level)
# Leave Plan Returned For Correction Successfully

<p>
    Hello {{ucwords($recipient->first_name)}}, you have Returned For Correction successfully Leave Plan submitted by
    {{ucwords($leave_plan->staff->first_name.' '.$leave_plan->staff->last_name)}}
    for the year {{$leave_plan->year}}.<br>
    - Time of Returning | {{date("d-m-Y H:i:s", strtotime($leave_plan->updated_at))}}<br>
    - Leave Plan No. is {{$leave_plan->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif




@if( ($recipient_type != $return_level) && ($recipient_type != 'staff') )
# Leave Plan Returned For Correction

<p>
    Hello {{ucwords($recipient->first_name)}}, the Leave Plan submitted by
    {{ucwords($leave_plan->staff->first_name.' '.$leave_plan->staff->last_name)}}
    for the year {{$leave_plan->year}}
    have been Returned For Correction by
    @if($return_level == 'spv') Supervisor
    @elseif($return_level == 'hrm') Human Resource Manager
    @elseif($return_level == 'md') Managing Director
    @endif
    .<br>
    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($leave_plan->updated_at))}}<br>
    - Leave Plan No. is {{$leave_plan->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif





@endcomponent
