@component('mail::message')



@if($recipient_type == 'staff')

# Objectives Rejected

<p>
    Hello {{$recipient->first_name}}, your Objectives for the year {{$performance_objectives->year}}
    have been Rejected by
    @if($reject_level == 'spv') Supervisor
    @elseif($reject_level == 'hrm') Human Resource Manager
    @elseif($reject_level == 'md') Managing Director
    @endif
    <br>
    - Time of Rejection | {{date("d-m-Y H:i:s", strtotime($performance_objectives->updated_at))}}<br>
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent


@endif



@if($recipient_type == $reject_level)
# Objectives Rejected Successfully

<p>
    Hello {{ucwords($recipient->first_name)}}, you have Rejected successfully Objectives submitted by
    {{ucwords($performance_objectives->staff->first_name.' '.$performance_objectives->staff->last_name)}}
    for the year {{$performance_objectives->year}}.<br>
    - Time of Rejection | {{date("d-m-Y H:i:s", strtotime($performance_objectives->updated_at))}}<br>
    - Objectives No. is {{$performance_objectives->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif




@if( ($recipient_type != $reject_level) && ($recipient_type != 'staff') )
# Objectives Rejected

<p>
    Hello {{ucwords($recipient->first_name)}}, the Objectives submitted by
    {{ucwords($performance_objectives->staff->first_name.' '.$performance_objectives->staff->last_name)}}
    for the year {{$performance_objectives->year}}
    have been Rejected by
    @if($reject_level == 'spv') Supervisor
    @elseif($reject_level == 'hrm') Human Resource Manager
    @elseif($reject_level == 'md') Managing Director
    @endif
    .<br>
    - Time of Rejection | {{date("d-m-Y H:i:s", strtotime($performance_objectives->updated_at))}}<br>
    - Objectives No. is {{$performance_objectives->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif





@endcomponent
