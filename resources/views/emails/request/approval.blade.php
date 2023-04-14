@component('mail::message')


@if($recipient_type == 'staff')
# {{$request_name}} Request {{'#'.$staff_request->no}} Approved By {{$action_done_by_title}}

<p>
    Hello {{$recipient->first_name}}, your {{$request_name}} Request {{'#'.$staff_request->no}}
    has been Approved by {{$action_done_by_title}}.<br>
    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($staff_request->updated_at))}}<br>
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif



@if(in_array($recipient_type, ['spv', 'hrm', 'acc', 'fd', 'md']) && !$is_next_level_notification)
# {{$request_name}} Request {{'#'.$staff_request->no}} Approved Successfully

<p>
    Hello {{ucwords($recipient->first_name)}}, you have Approved successfully {{$request_name}} Request {{'#'.$staff_request->no}} submitted by
    {{ucwords($staff_request->staff->first_name.' '.$staff_request->staff->last_name)}}<br>
    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($staff_request->updated_at))}}<br>
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif



@if(in_array($recipient_type, ['spv', 'hrm', 'acc', 'fd', 'md']) && $is_next_level_notification)
# {{$request_name}} Request {{'#'.$staff_request->no}} Approval

<p>
    Hello {{ucwords($recipient->first_name)}}, {{$request_name}} Request {{'#'.$staff_request->no}} submitted by
    {{ucwords($staff_request->staff->first_name.' '.$staff_request->staff->last_name)}}
    has been Approved by {{$action_done_by_title}}, currently it is waiting for Your Approval.
    Please login into the Portal to Approve it.<br>
    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($staff_request->updated_at))}}<br>
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif



@endcomponent
