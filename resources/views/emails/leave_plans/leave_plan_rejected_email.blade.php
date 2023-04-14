@component('mail::message')



@if($recipient_type == 'staff')

# Leave Plan Rejected

<p>
    Hello {{$recipient->first_name}}, your Leave Plan for the year
    {{$leave_plan->year}} have been Rejected by
    @if($reject_level == 'spv') Supervisor
    @elseif($reject_level == 'hrm') Human Resource Manager
    @elseif($reject_level == 'md') Managing Director
    @endif
    <br>
    - Time of Rejection | {{date("d-m-Y H:i:s", strtotime($leave_plan->updated_at))}}<br>
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif



@if($recipient_type == $reject_level)
# Leave Plan Rejected Successfully

<p>
    Hello {{ucwords($recipient->first_name)}}, you have Rejected successfully Leave Plan submitted by
    {{ucwords($leave_plan->staff->first_name.' '.$leave_plan->staff->last_name)}}
    for the year {{$leave_plan->year}}.<br>
    - Time of Rejection | {{date("d-m-Y H:i:s", strtotime($leave_plan->updated_at))}}<br>
    - Leave Plan No. is {{$leave_plan->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif




@if( ($recipient_type != $reject_level) && ($recipient_type != 'staff') )
# Leave Plan Rejected

<p>
    Hello {{ucwords($recipient->first_name)}}, the Leave Plan submitted by
    {{ucwords($leave_plan->staff->first_name.' '.$leave_plan->staff->last_name)}}
    for the year {{$leave_plan->year}}  have been Rejected by
    @if($reject_level == 'spv') Supervisor
    @elseif($reject_level == 'hrm') Human Resource Manager
    @elseif($reject_level == 'md') Managing Director
    @endif
    .<br>
    - Time of Rejection | {{date("d-m-Y H:i:s", strtotime($leave_plan->updated_at))}}<br>
    - Leave Plan No. is {{$leave_plan->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif





@endcomponent
