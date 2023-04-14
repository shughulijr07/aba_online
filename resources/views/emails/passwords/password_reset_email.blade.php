@component('mail::message')



@if($recipient_type == 'staff')

# Password Reset

<p>
    Hello {{$recipient->first_name}}, your password have been reset successfully to default password.
    Once you login remember to change the default password to password of your preference.
    <br>
</p>

@component('mail::button', ['url' => url('/')])
    Click Here To Login To the Portal
@endcomponent

@endif




@endcomponent
