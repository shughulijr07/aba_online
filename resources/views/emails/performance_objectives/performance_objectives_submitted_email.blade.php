@component('mail::message')



@if($recipient_type == 'staff')

# Objectives Submitted Successfully

<p>
    Hello {{$recipient->first_name}}, your Performance Objectives for the year {{$performance_objectives->year}}
    have been received, please wait for Supervisor's Approval<br>
    - Time of Submission | {{date("d-m-Y H:i:s", strtotime($performance_objectives->updated_at))}}<br>
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent


@endif



@if($recipient_type == 'spv')
# Performance Objectives Submitted By Staff

<p>
    Hello {{ucwords($recipient->first_name)}},
    {{ucwords($performance_objectives->staff->first_name.' '.$performance_objectives->staff->last_name)}}
    have submitted Performance Objectives for the year {{$performance_objectives->year}}
    , please login into Portal to approve them.<br>
    - Time of Submission | {{date("d-m-Y H:i:s", strtotime($performance_objectives->updated_at))}}<br>
    - Objectives No. is {{$performance_objectives->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif




@if($recipient_type == 'hrm')
# Performance Objectives Submitted By Staff

<p>
    Hello {{ucwords($recipient->first_name)}},
    {{ucwords($performance_objectives->staff->first_name.' '.$performance_objectives->staff->last_name)}}
    have submitted Performance Objectives for the year {{$performance_objectives->year}},
    currently they are waiting for Supervisor's Approval.<br>
    - Time of Submission | {{date("d-m-Y H:i:s", strtotime($performance_objectives->updated_at))}}<br>
    - Objectives No. is {{$performance_objectives->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif




@if($recipient_type == 'md')
# Performance Objectives Submitted By Staff

<p>
    Hello {{ucwords($recipient->first_name)}},
    {{ucwords($performance_objectives->staff->first_name.' '.$performance_objectives->staff->last_name)}}
    have submitted Performance Objectives for the year {{$performance_objectives->year}},
    currently they are waiting for Supervisor's Approval.<br>
    - Time of Submission | {{date("d-m-Y H:i:s", strtotime($performance_objectives->updated_at))}}<br>
    - Objectives No. is {{$performance_objectives->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif



@endcomponent
