@component('mail::message')

@if($recipient_type == 'staff')
# Supervisor Change for {{$request_name}} Request ({{'#'.$staff_request->no}})

<p>
    Hello {{$recipient->first_name}}, Responsible Supervisor for Approving your {{$request_name}} Request {{'#'.$staff_request->no}}
    was changed by  {{$action_done_by_title}}.
    <br>New Responsible Supervisor is {{$new_supervisor_name}}
    <br>To see more information please login into the portal <br>
    - Time of Submission | {{date("d-m-Y H:i:s", strtotime($staff_request->updated_at))}}<br>
</p>

@component('mail::button', ['url' => url('/') ])
    Click Here To Login To the Portal
@endcomponent
@endif



@if(in_array($recipient_type, ['spv', 'hrm', 'acc', 'fd', 'md']))
# New {{$request_name}} Request ({{'#'.$staff_request->no}}) Submitted By Staff

@if($recipient_type == 'spv')
<p>
    Hello {{ucwords($recipient->first_name)}}, there is new {{$request_name}} Request ({{'#'.$staff_request->no}}) submitted by
    {{ucwords($staff_request->staff->first_name.' '.$staff_request->staff->last_name)}}. Please login into Portal to Approve it.<br>
    - Time of Submission | {{date("d-m-Y H:i:s", strtotime($staff_request->updated_at))}}<br>
    - Request No. is {{$staff_request->no}}
</p>
@endif


@if(in_array($recipient_type, ['hrm', 'acc', 'fd', 'md']))
<p>
    Hello {{ucwords($recipient->first_name)}}, there is new {{$request_name}} Request ({{'#'.$staff_request->no}}) submitted by
    {{ucwords($staff_request->staff->first_name.' '.$staff_request->staff->last_name)}}, currently it is waiting for Supervisor's Approval.<br>
    - Time of Submission | {{date("d-m-Y H:i:s", strtotime($staff_request->updated_at))}}<br>
    - Request No. is {{$staff_request->no}}
</p>
@endif


@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent
@endif

@endcomponent
