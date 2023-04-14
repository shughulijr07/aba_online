@component('mail::message')


@if($recipient_type == 'staff')

# Leave Request Received

<p>
    Hello {{$recipient->first_name}}, Your request for LEAVE from {{date("d-m-Y",strtotime($leave->starting_date))}} to
    {{date("d-m-Y",strtotime($leave->ending_date))}} have been received, please wait for Approval.<br>
    - Time of receiving | {{date("d-m-Y H:i:s", strtotime($leave->created_at))}}<br>
    - Your Leave Request No. is {{$leave->id}}. For assistance please contact System Administrator or Human Resource Manager
</p>


@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif





@if($recipient_type == 'spv')

#New Leave Request

<p>
    Hello {{ucwords($recipient->first_name)}}, there is new Leave Request from {{ucwords($leave->staff->first_name.' '.$leave->staff->last_name)}}
    starting from {{date("d-m-Y",strtotime($leave->starting_date))}} to {{date("d-m-Y",strtotime($leave->ending_date))}}, please
    please login into Portal to approve it.<br>
    - Time of receiving | {{date("d-m-Y H:i:s", strtotime($leave->created_at))}}<br>
    - Request No. is {{$leave->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login Into The Portal
@endcomponent

@endif






@if($recipient_type == 'hrm')

# New Leave Request

<p>
    Hello {{ucwords($recipient->first_name)}}, there is new Leave Request from {{ucwords($leave->staff->first_name.' '.$leave->staff->last_name)}}
    starting from {{date("d-m-Y",strtotime($leave->starting_date))}} to {{date("d-m-Y",strtotime($leave->ending_date))}}, currently
    it is waiting for supervisor's approval, to see more information about the request please login into Portal.<br>
    - Time of receiving | {{date("d-m-Y H:i:s", strtotime($leave->created_at))}}<br>
    - Request No. is {{$leave->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login Into The Portal
@endcomponent

@endif





@if($recipient_type == 'md')

# New Leave Request

<p>
    Hello {{ucwords($recipient->first_name)}}, there is new Leave Request from {{ucwords($leave->staff->first_name.' '.$leave->staff->last_name)}}
    starting from {{date("d-m-Y",strtotime($leave->starting_date))}} to {{date("d-m-Y",strtotime($leave->ending_date))}}, currently
    it is waiting for supervisor's approval, to see more information about the request please login into Portal.<br>
    - Time of receiving | {{date("d-m-Y H:i:s", strtotime($leave->created_at))}}<br>
    - Request No. is {{$leave->id}}.
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login Into The Portal
@endcomponent

@endif





@endcomponent
