@component('mail::message')



@if($recipient_type == 'staff')

# Travel Request Approved By Managing Director

<p>
    Hello {{$recipient->first_name}}, your Travel Request with departure date of
    {{date("d-m-Y", strtotime($travel_request->departure_date))}}
    and returning date of  {{date("d-m-Y", strtotime($travel_request->returning_date))}}
    have been Approved by Managing Director.<br>
    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($travel_request->updated_at))}}<br>
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif



@if($recipient_type == 'spv')
# Travel Request Approved By Managing Director

<p>
    Hello {{ucwords($recipient->first_name)}}, the Travel Request submitted by
    {{ucwords($travel_request->staff->first_name.' '.$travel_request->staff->last_name)}}
    with departure date of {{date("d-m-Y", strtotime($travel_request->departure_date))}}
    and returning date of  {{date("d-m-Y", strtotime($travel_request->returning_date))}}
    have been Approved by Managing Director.<br>
    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($travel_request->updated_at))}}<br>
    - Travel Request No. is {{$travel_request->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif



@if($recipient_type == 'acc')
    # Travel Request Approved By Managing Director

    <p>
        Hello {{ucwords($recipient->first_name)}}, the Travel Request submitted by
        {{ucwords($travel_request->staff->first_name.' '.$travel_request->staff->last_name)}}
        with departure date of {{date("d-m-Y", strtotime($travel_request->departure_date))}}
        and returning date of  {{date("d-m-Y", strtotime($travel_request->returning_date))}}
        have been Approved by Managing Director.<br>
        - Time of Approval | {{date("d-m-Y H:i:s", strtotime($travel_request->updated_at))}}<br>
        - Travel Request No. is {{$travel_request->id}}.
    </p>

    @component('mail::button', ['url' => url('/')])
        Click Here To Login To the Portal
    @endcomponent

@endif



@if($recipient_type == 'fd')
# Travel Request Approved By Managing Director

<p>
    Hello {{ucwords($recipient->first_name)}}, the Travel Request submitted by
    {{ucwords($travel_request->staff->first_name.' '.$travel_request->staff->last_name)}}
    with departure date of {{date("d-m-Y", strtotime($travel_request->departure_date))}}
    and returning date of  {{date("d-m-Y", strtotime($travel_request->returning_date))}}
    have been Approved by Managing Director.<br>
    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($travel_request->updated_at))}}<br>
    - Travel Request No. is {{$travel_request->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif




@if($recipient_type == 'md')
# Travel Request Approved Successfully

<p>
    Hello {{ucwords($recipient->first_name)}}, you have Approved successfully Travel Request submitted by
    {{ucwords($travel_request->staff->first_name.' '.$travel_request->staff->last_name)}}
    with departure date of {{date("d-m-Y", strtotime($travel_request->departure_date))}}
    and returning date of  {{date("d-m-Y", strtotime($travel_request->returning_date))}}.<br>
    - Time of Approval | {{date("d-m-Y H:i:s", strtotime($travel_request->updated_at))}}<br>
    - Travel Request No. is {{$travel_request->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif




@endcomponent
