@component('mail::message')


@if($recipient_type == 'staff')

# Leave Plan Approved By Managing Director

<p>
    Hello {{$recipient->first_name}}, your Leave Plan for the year
    {{$leave_plan->year}} have been Approved by Managing Director.<br>
    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($leave_plan->updated_at))}}<br>
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif



@if($recipient_type == 'spv')
# Leave Plan Approved By Managing Director

<p>
    Hello {{ucwords($recipient->first_name)}}, the Leave Plan submitted by
    {{ucwords($leave_plan->staff->first_name.' '.$leave_plan->staff->last_name)}}
    for the year {{$leave_plan->year}} have been Approved by Managing Director.<br>
    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($leave_plan->updated_at))}}<br>
    - Leave Plan No. is {{$leave_plan->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif




@if($recipient_type == 'hrm')
#  Leave Plan Approved By Managing Director

<p>
    Hello {{ucwords($recipient->first_name)}}, the Leave Plan submitted by
    {{ucwords($leave_plan->staff->first_name.' '.$leave_plan->staff->last_name)}}
    for the year {{$leave_plan->year}} have been Approved by Managing Director.<br>
    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($leave_plan->updated_at))}}<br>
    - Leave Plan No. is {{$leave_plan->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif




@if($recipient_type == 'md')
# Leave Plan Approved Successfully

<p>
    Hello {{ucwords($recipient->first_name)}}, you have Approved successfully Leave Plan submitted by
    {{ucwords($leave_plan->staff->first_name.' '.$leave_plan->staff->last_name)}}
    for the year {{$leave_plan->year}}.<br>
    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($leave_plan->updated_at))}}<br>
    - Leave Plan No. is {{$leave_plan->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif



@endcomponent
