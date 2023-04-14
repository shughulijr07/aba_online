@component('mail::message')


@if($recipient_type == 'staff')
# {{$request_name}} Request {{'#'.$staff_request->no}} Rejected

<p>
    Hello {{$recipient->first_name}}, your {{$request_name}} Request {{'#'.$staff_request->no}}
    was Rejected by {{$action_done_by_title}}.<br> To see more information please login into the portal
    <br>
    - Time of Rejection | {{date("d-m-Y H:i:s", strtotime($staff_request->updated_at))}}<br>
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent


@endif



@if(in_array($recipient_type, ['spv', 'hrm', 'acc', 'fd', 'md']) && !$is_next_level_notification)
# {{$request_name}} Request  {{'#'.$staff_request->no}} Rejected Successfully

<p>
    Hello {{ucwords($recipient->first_name)}}, you have Rejected successfully {{$request_name}} Request  {{'#'.$staff_request->no}}
    submitted by {{ucwords($staff_request->staff->first_name.' '.$staff_request->staff->last_name)}}.<br>
    - Time of Rejection | {{date("d-m-Y H:i:s", strtotime($staff_request->updated_at))}}<br>
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif




@if(in_array($recipient_type, ['spv', 'hrm', 'acc', 'fd', 'md']) && $is_next_level_notification)
# {{$request_name}} Request {{'#'.$staff_request->no}} Rejected

<p>
    Hello {{ucwords($recipient->first_name)}}, the {{$request_name}} Request {{'#'.$staff_request->no}}
    submitted by {{ucwords($staff_request->staff->first_name.' '.$staff_request->staff->last_name)}}
    have been Rejected by {{$action_done_by_title}}.<br>  To see more information please login into the portal<br>
    - Time of Rejection | {{date("d-m-Y H:i:s", strtotime($staff_request->updated_at))}}<br>
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif





@endcomponent
