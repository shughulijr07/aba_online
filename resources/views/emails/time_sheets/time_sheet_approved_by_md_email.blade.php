@component('mail::message')


@if($recipient_type == 'staff')

# Time Sheet Approved By Managing Director

<p>
    Hello {{$recipient->first_name}}, your Time Sheet for the month of
    {{$months[$time_sheet->month].', '.$time_sheet->year}}
    have been Approved by Managing Director.<br>
    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($time_sheet->updated_at))}}<br>
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent


@endif



@if($recipient_type == 'spv')
# Time Sheet Approved By Managing Director

<p>
    Hello {{ucwords($recipient->first_name)}}, the Time Sheet submitted by
    {{ucwords($time_sheet->staff->first_name.' '.$time_sheet->staff->last_name)}}
    for the month of {{$months[$time_sheet->month].', '.$time_sheet->year}}
    have been Approved by Managing Director.<br>
    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($time_sheet->updated_at))}}<br>
    - Time Sheet No. is {{$time_sheet->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif




@if($recipient_type == 'hrm')
#  Time Sheet Approved By Managing Director

<p>
    Hello {{ucwords($recipient->first_name)}}, the Time Sheet submitted by
    {{ucwords($time_sheet->staff->first_name.' '.$time_sheet->staff->last_name)}}
    for the month of {{$months[$time_sheet->month].', '.$time_sheet->year}}
    have been Approved by Managing Director.<br>
    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($time_sheet->updated_at))}}<br>
    - Time Sheet No. is {{$time_sheet->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif




@if($recipient_type == 'md')
# Time Sheet Approved Successfully

<p>
    Hello {{ucwords($recipient->first_name)}}, you have Approved successfully Time Sheet submitted by
    {{ucwords($time_sheet->staff->first_name.' '.$time_sheet->staff->last_name)}}
    for the month of {{$months[$time_sheet->month].', '.$time_sheet->year}}.<br>
    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($time_sheet->updated_at))}}<br>
    - Time Sheet No. is {{$time_sheet->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif



@endcomponent
