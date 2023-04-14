@component('mail::message')


@if($recipient_type == 'staff')

# Leave Payment Have Been Issued

<p>
    Hello {{$recipient->first_name}}, the PAYMENT for your {{strtoupper(  str_replace('_',' ',$leave->type)  )}} request have been issued by accountant. Your leave
    starts on {{date("d-m-Y",strtotime($leave->starting_date))}}
    and ends on {{date("d-m-Y",strtotime($leave->ending_date))}}, please enjoy your leave.<br>
    - Time of Confirmation | {{date("d-m-Y H:i:s", strtotime($leave->updated_at))}}<br>
    - Your Leave Request No. is {{$leave->id}}. For assistance please contact System Administrator or Human Resource Manager
</p>


@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif





@if($recipient_type == 'spv')

# Leave Payment Have Been Issued

<p>
    Hello {{ucwords($recipient->first_name)}}, payment for {{strtoupper( str_replace('_',' ',$leave->type) )}} Request made by
    {{ucwords($leave->staff->first_name.' '.$leave->staff->last_name)}} starting from
    {{date("d-m-Y",strtotime($leave->starting_date))}} to {{date("d-m-Y",strtotime($leave->ending_date))}},
    have been issued by finance.<br>
    - Time of Confirmation | {{date("d-m-Y H:i:s", strtotime($leave->updated_at))}}<br>
    - Request No. is {{$leave->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login Into The Portal
@endcomponent

@endif






@if($recipient_type == 'hrm')

# Leave Payment Have Been Issued

<p>
    Hello {{ucwords($recipient->first_name)}}, payment for {{strtoupper( str_replace('_',' ',$leave->type) )}} Request made by
    {{ucwords($leave->staff->first_name.' '.$leave->staff->last_name)}} starting from
    {{date("d-m-Y",strtotime($leave->starting_date))}} to {{date("d-m-Y",strtotime($leave->ending_date))}},
    have been issued by finance.<br>
    - Time of Confirmation | {{date("d-m-Y H:i:s", strtotime($leave->updated_at))}}<br>
    - Request No. is {{$leave->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login Into The Portal
@endcomponent

@endif




@if($recipient_type == 'md')

    # Leave Payment Have Been Issued

    <p>
        Hello {{ucwords($recipient->first_name)}}, payment for {{strtoupper( str_replace('_',' ',$leave->type) )}} Request made by
        {{ucwords($leave->staff->first_name.' '.$leave->staff->last_name)}} starting from
        {{date("d-m-Y",strtotime($leave->starting_date))}} to {{date("d-m-Y",strtotime($leave->ending_date))}},
        have been issued by finance.<br>
        - Time of Confirmation | {{date("d-m-Y H:i:s", strtotime($leave->updated_at))}}<br>
        - Request No. is {{$leave->id}}.
    </p>

    @component('mail::button', ['url' => url('/')])
        Click Here To Login Into The Portal
    @endcomponent

@endif






@if($recipient_type == 'accountant')

# Leave Payment Confirmed Successfully

<p>
    Hello {{ucwords($recipient->first_name)}}, payment for {{strtoupper( str_replace('_',' ',$leave->type) )}} requested by
    {{ucwords($leave->staff->first_name.' '.$leave->staff->last_name)}} have been confirmed successfully<br>
    - Time of Confirmation| {{date("d-m-Y H:i:s", strtotime($leave->updated_at))}}<br>
    - Request No. is {{$leave->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login Into The Portal
@endcomponent

@endif





@endcomponent
