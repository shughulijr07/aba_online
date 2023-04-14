@component('mail::message')



@if($recipient_type == 'staff')

# Travel Request Submitted Successfully

<p>
    Hello {{$recipient->first_name}}, your Travel Request with departure date of
    {{date("d-m-Y", strtotime($travel_request->departure_date))}}
    and returning date of  {{date("d-m-Y", strtotime($travel_request->returning_date))}}
    have been received, currently it is waiting for Supervisor's Approval<br>
    - Time of Submission | {{date("d-m-Y H:i:s", strtotime($travel_request->updated_at))}}<br>
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent


@endif



@if($recipient_type == 'spv')
# New Travel Request Submitted By Staff

<p>
    Hello {{ucwords($recipient->first_name)}}, there is new Travel Request submitted by
    {{ucwords($travel_request->staff->first_name.' '.$travel_request->staff->last_name)}}.
    The departure date is {{date("d-m-Y", strtotime($travel_request->departure_date))}}
    and returning date is  {{date("d-m-Y", strtotime($travel_request->returning_date))}}, please
     login into Portal to approve it.<br>
    - Time of Submission | {{date("d-m-Y H:i:s", strtotime($travel_request->updated_at))}}<br>
    - Travel Request No. is {{$travel_request->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif




@if($recipient_type == 'hrm')
# New Travel Request Submitted By Staff

<p>
    Hello {{ucwords($recipient->first_name)}}, there is new Travel Request submitted by
    {{ucwords($travel_request->staff->first_name.' '.$travel_request->staff->last_name)}}.
    The departure date is {{date("d-m-Y", strtotime($travel_request->departure_date))}}
    and returning date is  {{date("d-m-Y", strtotime($travel_request->returning_date))}},
    currently it is waiting for Supervisor's Approval.<br>
    - Time of Submission | {{date("d-m-Y H:i:s", strtotime($travel_request->updated_at))}}<br>
    - Travel Request No. is {{$travel_request->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif




@if($recipient_type == 'acc')
# New Travel Request Submitted By Staff

<p>
    Hello {{ucwords($recipient->first_name)}}, there is new Travel Request submitted by
    {{ucwords($travel_request->staff->first_name.' '.$travel_request->staff->last_name)}}.
    The departure date is {{date("d-m-Y", strtotime($travel_request->departure_date))}}
    and returning date is  {{date("d-m-Y", strtotime($travel_request->returning_date))}},
    currently it is waiting for Supervisor's Approval.<br>
    - Time of Submission | {{date("d-m-Y H:i:s", strtotime($travel_request->updated_at))}}<br>
    - Travel Request No. is {{$travel_request->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif




@if($recipient_type == 'fd')
# New Travel Request Submitted By Staff

<p>
    Hello {{ucwords($recipient->first_name)}}, there is new Travel Request submitted by
    {{ucwords($travel_request->staff->first_name.' '.$travel_request->staff->last_name)}}.
    The departure date is {{date("d-m-Y", strtotime($travel_request->departure_date))}}
    and returning date is  {{date("d-m-Y", strtotime($travel_request->returning_date))}},
    currently it is waiting for Supervisor's Approval.<br>
    - Time of Submission | {{date("d-m-Y H:i:s", strtotime($travel_request->updated_at))}}<br>
    - Travel Request No. is {{$travel_request->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif



@endcomponent
