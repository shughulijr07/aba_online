@component('mail::message')



@if($recipient_type == 'staff')

# Leave Request Rejected

<p>
    Hello {{$recipient->first_name}}, your {{strtoupper( str_replace('_',' ',$leave->type) )}} request which was to start from {{date("d-m-Y",
    strtotime($leave->starting_date))}} upto {{date("d-m-Y",strtotime($leave->ending_date))}} have been REJECTED by
    @if($reject_level == 'spv') Supervisor
    @elseif($reject_level == 'hrm') Human Resource Manager
    @elseif($reject_level == 'md') Managing Director
    @endif
    , for more information please login into the Portal.<br>
    - Time of Rejection | {{date("d-m-Y H:i:s", strtotime($leave->updated_at))}}<br>
    - Your Leave Request No. is {{$leave->id}}. For assistance please contact System Administrator or Human Resource Manager

    <br>
    - Time of Rejection | {{date("d-m-Y H:i:s", strtotime($leave->updated_at))}}<br>
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login Into The Portal
@endcomponent

@endif



@if($recipient_type == $reject_level)
# Leave Request Rejected Successfully

<p>
    Hello {{ucwords($recipient->first_name)}}, you have Rejected successfully Leave Request made by
    {{ucwords($leave->staff->first_name.' '.$leave->staff->last_name)}} which was to start on
    {{date("d-m-Y",strtotime($leave->starting_date))}} upto {{date("d-m-Y",strtotime($leave->ending_date))}}.<br>

    - Time of Rejection | {{date("d-m-Y H:i:s", strtotime($leave->updated_at))}}<br>
    - Leave Request No. is {{$leave->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login Into The Portal
@endcomponent

@endif




@if( ($recipient_type != $reject_level) && ($recipient_type != 'staff') )
# Leave Request Rejected

<p>
    Hello {{ucwords($recipient->first_name)}}, the Leave Request made by
    {{ucwords($leave->staff->first_name.' '.$leave->staff->last_name)}}
    which was to start on {{date("d-m-Y",strtotime($leave->starting_date))}} upto
    {{date("d-m-Y",strtotime($leave->ending_date))}} have been rejected by
    @if($reject_level == 'spv') Supervisor
    @elseif($reject_level == 'hrm') Human Resource Manager
    @elseif($reject_level == 'md') Managing Director
    @endif
    .<br>
    - Time of Rejection | {{date("d-m-Y H:i:s", strtotime($leave->updated_at))}}<br>
    - Leave Request No. is {{$leave->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login Into The Portal
@endcomponent

@endif





@endcomponent
