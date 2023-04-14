@component('mail::message')



@if($recipient_type == 'staff')

# Travel Request Returned For Correction

<p>
    Hello {{$recipient->first_name}}, your Travel Request with departure date of
    {{date("d-m-Y", strtotime($travel_request->departure_date))}}
    and returning date of  {{date("d-m-Y", strtotime($travel_request->returning_date))}}
    have been Returned For Correction by
    @if($reject_level == 'spv') Supervisor
    @elseif($reject_level == 'acc') Accountant
    @elseif($reject_level == 'fd') Finance Director
    @elseif($reject_level == 'md') Managing Director
    @endif
    <br>
    - Time of Returning | {{date("d-m-Y H:i:s", strtotime($travel_request->updated_at))}}<br>
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent


@endif



@if($recipient_type == $return_level)
# Travel Request Returned For Correction Successfully

<p>
    Hello {{ucwords($recipient->first_name)}}, you have Returned For Correction successfully Travel Request submitted by
    {{ucwords($travel_request->staff->first_name.' '.$travel_request->staff->last_name)}}
    with departure date of {{date("d-m-Y", strtotime($travel_request->departure_date))}}
    and returning date of  {{date("d-m-Y", strtotime($travel_request->returning_date))}}.<br>
    - Time of Returning | {{date("d-m-Y H:i:s", strtotime($travel_request->updated_at))}}<br>
    - Travel Request No. is {{$travel_request->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif




@if( ($recipient_type != $return_level) && ($recipient_type != 'staff') )
# Travel Request Returned For Correction

<p>
    Hello {{ucwords($recipient->first_name)}}, the Travel Request submitted by
    {{ucwords($travel_request->staff->first_name.' '.$travel_request->staff->last_name)}}
    with departure date of {{date("d-m-Y", strtotime($travel_request->departure_date))}}
    and returning date of  {{date("d-m-Y", strtotime($travel_request->returning_date))}}
    have been Returned For Correction by
    @if($reject_level == 'spv') Supervisor
    @elseif($reject_level == 'acc') Accountant
    @elseif($reject_level == 'fd') Finance Director
    @elseif($reject_level == 'md') Managing Director
    @endif
    .<br>
    - Time of Returning | {{date("d-m-Y H:i:s", strtotime($travel_request->updated_at))}}<br>
    - Travel Request No. is {{$travel_request->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif





@endcomponent
