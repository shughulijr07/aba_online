@component('mail::message')


@if($recipient_type == 'staff')

# Performance Objectives Approved By Managing Director

<p>
    Hello {{$recipient->first_name}}, your Performance Objectives for the year {{$performance_objectives->year}}
    have been Approved by Managing Director.<br>
    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($performance_objectives->updated_at))}}<br>
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent


@endif



@if($recipient_type == 'spv')
# Performance Objectives Approved By Managing Director

<p>
    Hello {{ucwords($recipient->first_name)}}, Performance Objectives submitted by
    {{ucwords($performance_objectives->staff->first_name.' '.$performance_objectives->staff->last_name)}}
    for the year {{$performance_objectives->year}} have been Approved by Managing Director.<br>
    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($performance_objectives->updated_at))}}<br>
    - Performance Objectives No. is {{$performance_objectives->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif




@if($recipient_type == 'hrm')
#  Performance Objectives Approved By Managing Director

<p>
    Hello {{ucwords($recipient->first_name)}}, the Performance Objectives submitted by
    {{ucwords($performance_objectives->staff->first_name.' '.$performance_objectives->staff->last_name)}}
    for the year {{$performance_objectives->year}} have been Approved by Managing Director.<br>
    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($performance_objectives->updated_at))}}<br>
    - Performance Objectives No. is {{$performance_objectives->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif




@if($recipient_type == 'md')
# Performance Objectives Approved Successfully

<p>
    Hello {{ucwords($recipient->first_name)}}, you have Approved successfully Performance Objectives submitted by
    {{ucwords($performance_objectives->staff->first_name.' '.$performance_objectives->staff->last_name)}}
    for the year {{$performance_objectives->year}}.<br>
    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($performance_objectives->updated_at))}}<br>
    - Performance Objectives No. is {{$performance_objectives->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif



@endcomponent
