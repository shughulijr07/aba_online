@component('mail::message')



@if($recipient_type == 'staff')

# Objectives Returned For Correction

<p>
    Hello {{$recipient->first_name}}, your Objectives for the year {{$performance_objectives->year}}
    have been Returned For Correction by
    @if($return_level == 'spv') Supervisor
    @elseif($return_level == 'hrm') Human Resource Manager
    @elseif($return_level == 'md') Managing Director
    @endif
    <br>
    - Time of Returning | {{date("d-m-Y H:i:s", strtotime($performance_objectives->updated_at))}}<br>
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent


@endif



@if($recipient_type == $return_level)
# Objectives Returned For Correction Successfully

<p>
    Hello {{ucwords($recipient->first_name)}}, you have Returned For Correction successfully Objectives submitted by
    {{ucwords($performance_objectives->staff->first_name.' '.$performance_objectives->staff->last_name)}}
    for the year {{$performance_objectives->year}}.<br>
    - Time of Returning | {{date("d-m-Y H:i:s", strtotime($performance_objectives->updated_at))}}<br>
    - Objectives No. is {{$performance_objectives->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif




@if( ($recipient_type != $return_level) && ($recipient_type != 'staff') )
# Objectives Returned For Correction

<p>
    Hello {{ucwords($recipient->first_name)}}, the Objectives submitted by
    {{ucwords($performance_objectives->staff->first_name.' '.$performance_objectives->staff->last_name)}}
    for the year {{$performance_objectives->year}}
    have been Returned For Correction by
    @if($return_level == 'spv') Supervisor
    @elseif($return_level == 'hrm') Human Resource Manager
    @elseif($return_level == 'md') Managing Director
    @endif
    .<br>
    - Time of Returning | {{date("d-m-Y H:i:s", strtotime($performance_objectives->updated_at))}}<br>
    - Objectives No. is {{$performance_objectives->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif





@endcomponent
