@component('mail::message')



@if($recipient_type == 'staff')

# Time Sheet Rejected

<p>
    Hello {{$recipient->first_name}}, your Time Sheet for the month of
    {{$months[$time_sheet->month].', '.$time_sheet->year}}
    have been Rejected by
    @if($reject_level == 'spv') Supervisor
    @elseif($reject_level == 'hrm') Human Resource Manager
    @elseif($reject_level == 'md') Managing Director
    @endif
    <br>
    - Time of Rejection | {{date("d-m-Y H:i:s", strtotime($time_sheet->updated_at))}}<br>
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent


@endif



@if($recipient_type == $reject_level)
# Time Sheet Rejected Successfully

<p>
    Hello {{ucwords($recipient->first_name)}}, you have Rejected successfully Time Sheet submitted by
    {{ucwords($time_sheet->staff->first_name.' '.$time_sheet->staff->last_name)}}
    for the month of {{$months[$time_sheet->month].', '.$time_sheet->year}}.<br>
    - Time of Rejection | {{date("d-m-Y H:i:s", strtotime($time_sheet->updated_at))}}<br>
    - Time Sheet No. is {{$time_sheet->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif




@if( ($recipient_type != $reject_level) && ($recipient_type != 'staff') )
# Time Sheet Rejected

<p>
    Hello {{ucwords($recipient->first_name)}}, the Time Sheet submitted by
    {{ucwords($time_sheet->staff->first_name.' '.$time_sheet->staff->last_name)}}
    for the month of {{$months[$time_sheet->month].', '.$time_sheet->year}}
    have been Rejected by
    @if($reject_level == 'spv') Supervisor
    @elseif($reject_level == 'hrm') Human Resource Manager
    @elseif($reject_level == 'md') Managing Director
    @endif
    .<br>
    - Time of Rejection | {{date("d-m-Y H:i:s", strtotime($time_sheet->updated_at))}}<br>
    - Time Sheet No. is {{$time_sheet->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif





@endcomponent
