@component('mail::message')



@if($recipient_type == 'staff')

# Time Sheet Submitted Successfully

<p>
    Hello {{$recipient->first_name}}, your Time Sheet for the month of
    {{$months[$time_sheet->month].', '.$time_sheet->year}}
    have been received, currently it is waiting for Supervisor's Approval<br>
    - Time of Submission | {{date("d-m-Y H:i:s", strtotime($time_sheet->updated_at))}}<br>
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent


@endif



@if($recipient_type == 'spv')
# New Time Sheet Submitted By Staff

<p>
    Hello {{ucwords($recipient->first_name)}}, there is new Time Sheet submitted by
    {{ucwords($time_sheet->staff->first_name.' '.$time_sheet->staff->last_name)}}
    for the month of {{$months[$time_sheet->month].', '.$time_sheet->year}}, please
     login into Portal to approve it.<br>
    - Time of Submission | {{date("d-m-Y H:i:s", strtotime($time_sheet->updated_at))}}<br>
    - Time Sheet No. is {{$time_sheet->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif




@if($recipient_type == 'hrm')
# New Time Sheet Submitted By Staff

<p>
    Hello {{ucwords($recipient->first_name)}}, there is new Time Sheet submitted by
    {{ucwords($time_sheet->staff->first_name.' '.$time_sheet->staff->last_name)}}
    for the month of {{$months[$time_sheet->month].', '.$time_sheet->year}},
    currently it is waiting for Supervisor's Approval.<br>
    - Time of Submission | {{date("d-m-Y H:i:s", strtotime($time_sheet->updated_at))}}<br>
    - Time Sheet No. is {{$time_sheet->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif




@if($recipient_type == 'md')
# New Time Sheet Submitted By Staff

<p>
    Hello {{ucwords($recipient->first_name)}}, there is new Time Sheet submitted by
    {{ucwords($time_sheet->staff->first_name.' '.$time_sheet->staff->last_name)}}
    for the month of {{$months[$time_sheet->month].', '.$time_sheet->year}},
    currently it is waiting for Supervisor's Approval.<br>
    - Time of Submission | {{date("d-m-Y H:i:s", strtotime($time_sheet->updated_at))}}<br>
    - Time Sheet No. is {{$time_sheet->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif



@endcomponent
