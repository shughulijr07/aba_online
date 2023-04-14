@csrf
<div class="form-row">
    <div class="col-md-4">
        <div class="position-relative form-group">
            <label for="old_password" class="">
                <span>Old Password</span>
                <span class="text-danger">*</span>
            </label>
            <input name="old_password" id="old_password" type="password" class="form-control @error('old_password') is-invalid @enderror" value="{{ old('old_password')}}">

            @error('old_password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="position-relative form-group">
            <label for="new_password1" class="">
                <span>New Password</span>
                <span class="text-danger">*</span>
            </label>
            <input name="new_password1" id="new_password1" type="password" class="form-control @error('new_password1') is-invalid @enderror" value="{{ old('new_password1')}}">

            @error('new_password1')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="position-relative form-group">
            <label for="new_password2" class="">
                <span>Confirm New Password</span>
                <span class="text-danger">*</span>
            </label>
            <input name="new_password2" id="new_password2" type="password" class="form-control @error('new_password2') is-invalid @enderror" value="{{ old('new_password2')}}">

            @error('new_password2')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>

    <div id="email" style="display: none;">{{auth()->user()->email}}</div>


    <div class="col-md-4">
        <div class="position-relative form-group">
            <button class="mt-2 btn btn-primary" id="change-password-btn">Change Password</button>
        </div>
    </div>

</div>

{{ csrf_field() }}


<script>

    //when change password button is clicked
    $("#change-password-btn").on("click", function(event){

        event.preventDefault();

        var email = $("#email").text();
        var old_password = $("#old_password").val();
        var new_password1 = $("#new_password1").val();
        var new_password2 = $("#new_password2").val();
        var _token = $('input[name="_token"]').val();

        //verify these these passwords
        if ( old_password == '' || new_password1 == '' || new_password2 == '') {

            var message = 'Please enter correct password';
            sweet_alert_error(message);
        }
        else{ //if they are not empty then continue
            if (new_password2 == new_password1){
                change_password(email,old_password,new_password1,_token);
            }
            else{

                var message = "New Password Don't Match";
                sweet_alert_error(message);
            }
        }
    });




    /******************* functions goes here ************************/

    function  change_password(email,old_password,new_password1,_token){
        Swal.fire({
            title: 'Are you sure? Yo want to change your password ? ',
            text: "Your old password will no longer be usable!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#213368',
            cancelButtonColor: '#F15A24',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.value) {

            //ajax request
            $.ajax({
                url:"{{ route('user_account.ajax_change_password') }}",
                method:"POST",
                data:{email:email,old_password:old_password,new_password:new_password1,_token:_token},
                success:function(data)
                {
                    //add the list to selection option
                    //console.log(data);
                    data = JSON.parse(data);

                    if (data.length > 0) {
                        change_password_response(data);
                    }else{

                    }
                }
            });

        }
    })
    }

    function change_password_response(data){


        if (data =='success') {

            $(document).off(".firstCall");//stop ajaxStart call

            sweet_alert_success('Password Changed Successfully');

            //reset fields
            $("#old_password").val('');
            $("#new_password1").val('');
            $("#new_password2").val('');

            //reload the page
            //setTimeout(function(){ window.location.reload(); }, 1000);


        }

        else{
            sweet_alert_error('Failed To Change Password, Please Enter Correct Password & Try Again');
        }

    }


    /******************* sweet alerts *******************************/

    function sweet_alert_success(success_message){
        Swal.fire({
            type: "success",
            text: success_message,
            confirmButtonColor: '#213368',
        })
    }


    function sweet_alert_error(error_message){
        Swal.fire({
            type: "error",
            text: error_message,
            confirmButtonColor: '#213368',
        })
    }

    function sweet_alert_warning(warning_message){
        Swal.fire({
            type: "warning",
            text: warning_message,
            confirmButtonColor: '#213368',
        })
    }

</script>