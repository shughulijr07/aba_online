@component('mail::message')



@if($recipient_type == 'staff')

# Leave Plan Submitted Successfully

<p>
    Hello {{$recipient->first_name}}, your Leave Plan for the year
    {{$leave_plan->year}}
    have been received, currently it is waiting for Supervisor's Approval<br>
    - Time of Submission | {{date("d-m-Y H:i:s", strtotime($leave_plan->updated_at))}}<br>
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif



@if($recipient_type == 'spv')
# New Leave Plan Submitted By Staff

<p>
    Hello {{ucwords($recipient->first_name)}}, there is new Leave Plan submitted by
    {{ucwords($leave_plan->staff->first_name.' '.$leave_plan->staff->last_name)}}
    for the year {{$leave_plan->year}}, please
     login into Portal to approve it.<br>
    - Time of Submission | {{date("d-m-Y H:i:s", strtotime($leave_plan->updated_at))}}<br>
    - Leave Plan No. is {{$leave_plan->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif




@if($recipient_type == 'hrm')
# New Leave Plan Submitted By Staff

<p>
    Hello {{ucwords($recipient->first_name)}}, there is new Leave Plan submitted by
    {{ucwords($leave_plan->staff->first_name.' '.$leave_plan->staff->last_name)}}
    for the year {{$leave_plan->year}},
    currently it is waiting for Supervisor's Approval.<br>
    - Time of Submission | {{date("d-m-Y H:i:s", strtotime($leave_plan->updated_at))}}<br>
    - Leave Plan No. is {{$leave_plan->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif




@if($recipient_type == 'md')
# New Leave Plan Submitted By Staff

<p>
    Hello {{ucwords($recipient->first_name)}}, there is new Leave Plan submitted by
    {{ucwords($leave_plan->staff->first_name.' '.$leave_plan->staff->last_name)}}
    for the year {{$leave_plan->year}},
    currently it is waiting for Supervisor's Approval.<br>
    - Time of Submission | {{date("d-m-Y H:i:s", strtotime($leave_plan->updated_at))}}<br>
    - Leave Plan No. is {{$leave_plan->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif



@endcomponent
