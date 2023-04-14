@component('mail::message')


@if($recipient_type == 'staff')

# Leave Request Approved By Managing Director

<p>
    Hello {{$recipient->first_name}}, your {{strtoupper( str_replace('_',' ',$leave->type) )}} request have been Approved by Managing Director,
    your leave will start on {{date("d-m-Y",strtotime($leave->starting_date))}}
    and end on {{date("d-m-Y",strtotime($leave->ending_date))}}, for more information please login into the Portal.<br>
    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($leave->updated_at))}}<br>
    - Your Leave Request No. is {{$leave->id}}. For assistance please contact System Administrator or Human Resource Manager
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif





@if($recipient_type == 'spv')

#Leave Request Approval

<p>
    Hello {{ucwords($recipient->first_name)}}, the Leave Request made by {{ucwords($leave->staff->first_name.' '.$leave->staff->last_name)}}
    starting from {{date("d-m-Y",strtotime($leave->starting_date))}} to {{date("d-m-Y",strtotime($leave->ending_date))}}
    have been approved by Managing Director.<br>
    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($leave->updated_at))}}<br>
    - Leave Request No. is {{$leave->id}}.
</p>

@endif






@if($recipient_type == 'hrm')

# Leave Approval

<p>
    Hello {{ucwords($recipient->first_name)}}, leave Request from {{ucwords($leave->staff->first_name.' '.$leave->staff->last_name)}}
    starting from {{date("d-m-Y",strtotime($leave->starting_date))}} to {{date("d-m-Y",strtotime($leave->ending_date))}},
    have been approved successfully.<br>
    - Time of Approval By MD | {{date("d-m-Y H:i:s", strtotime($leave->updated_at))}}<br>
    - Request No. is {{$leave->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login Into The Portal
@endcomponent

@endif





@if($recipient_type == 'md')

# Leave Approval

<p>
    Hello {{ucwords($recipient->first_name)}}, leave Request from {{ucwords($leave->staff->first_name.' '.$leave->staff->last_name)}}
    starting from {{date("d-m-Y",strtotime($leave->starting_date))}} to {{date("d-m-Y",strtotime($leave->ending_date))}},
    have been approved successfully.<br>
    - Time of Approval By MD | {{date("d-m-Y H:i:s", strtotime($leave->updated_at))}}<br>
    - Request No. is {{$leave->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login Into The Portal
@endcomponent

@endif





@endcomponent
