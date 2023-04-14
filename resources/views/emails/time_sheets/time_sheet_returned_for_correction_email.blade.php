@component('mail::message')



@if($recipient_type == 'staff')

# Time Sheet Returned For Correction

<p>
    Hello {{$recipient->first_name}}, your Time Sheet for the month of
    {{$months[$time_sheet->month].', '.$time_sheet->year}}
    have been Returned For Correction by
    @if($return_level == 'spv') Supervisor
    @elseif($return_level == 'hrm') Human Resource Manager
    @elseif($return_level == 'md') Managing Director
    @endif
    <br>
    - Time of Returning | {{date("d-m-Y H:i:s", strtotime($time_sheet->updated_at))}}<br>
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent


@endif



@if($recipient_type == $return_level)
# Time Sheet Returned For Correction Successfully

<p>
    Hello {{ucwords($recipient->first_name)}}, you have Returned For Correction successfully Time Sheet submitted by
    {{ucwords($time_sheet->staff->first_name.' '.$time_sheet->staff->last_name)}}
    for the month of {{$months[$time_sheet->month].', '.$time_sheet->year}}.<br>
    - Time of Returning | {{date("d-m-Y H:i:s", strtotime($time_sheet->updated_at))}}<br>
    - Time Sheet No. is {{$time_sheet->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif




@if( ($recipient_type != $return_level) && ($recipient_type != 'staff') )
# Time Sheet Returned For Correction

<p>
    Hello {{ucwords($recipient->first_name)}}, the Time Sheet submitted by
    {{ucwords($time_sheet->staff->first_name.' '.$time_sheet->staff->last_name)}}
    for the month of {{$months[$time_sheet->month].', '.$time_sheet->year}}
    have been Returned For Correction by
    @if($return_level == 'spv') Supervisor
    @elseif($return_level == 'hrm') Human Resource Manager
    @elseif($return_level == 'md') Managing Director
    @endif
    .<br>
    - Time of Returned | {{date("d-m-Y H:i:s", strtotime($time_sheet->updated_at))}}<br>
    - Time Sheet No. is {{$time_sheet->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif





@endcomponent
