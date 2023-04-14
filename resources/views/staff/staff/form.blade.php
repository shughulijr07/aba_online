@csrf
<fieldset>
    <legend>Photo & Basic Information</legend>
    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-3">
            <div class="form-row">
                <div class="col-md-12">
                    <div class="position-relative form-group">
                        <label>Staff Image</label>
                        <div class="staff-image-container">
                            <img src="@if($staff->image != '') /storage/{{$staff->image}} @else /images/staff-image.png @endif" alt="Staff Image" style="width: 100%; height: auto; " id="staff-image">
                        </div>
                        <div class="text-center" style="margin-bottom: 10px;">
                            <div style="padding: 5px; ">
                                <span id="file-name"></span>
                            </div>
                            <label for="image" class="upload-label" type="button">
                                <i class="pe-7s-upload"></i>
                                <span style="padding-left: 7px; padding-right: 5px;">
                                    Upload Staff Image
                                </span>
                            </label>
                            <input name="image" id="image" type="file"  accept="image/*" class="form-control-file" style="display:none;" >
                        </div>

                        @error('image')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror

                    </div>
                    <!--script for uploading images -->
                    <script type="text/javascript">

                        $('#image').on('change', function(){

                            if (this.files && this.files[0]) {
                                var reader = new FileReader();

                                reader.onload = function (e) {
                                    $('#staff-image').attr('src', e.target.result);
                                };

                                reader.readAsDataURL(this.files[0]);
                            }
                        });

                    </script>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="form-row">
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="first_name" class="">First Name</label>
                        <input name="first_name" id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') ?? $staff->first_name}}">

                        @error('first_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="middle_name" class="">Middle Name</label>
                        <input name="middle_name" id="middle_name" type="text" class="form-control @error('middle_name') is-invalid @enderror" value="{{ old('middle_name') ?? $staff->middle_name}}">

                        @error('middle_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="last_name" class="">Last Name</label>
                        <input name="last_name" id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') ?? $staff->last_name}}">

                        @error('last_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="position-relative form-group">
                        <label for="gender" class="">
                            <span>Gender</span>
                            <span class="text-danger">*</span>
                        </label>
                        <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror">
                            <option value="">Select Gender</option>
                            <option value="Female" @if( old('gender')  == 'Female' || $gender == 'Female') selected @endif>
                                Female
                            </option>
                            <option value="Male" @if( old('gender') == 'Male' || $gender == 'Male') selected @endif>
                                Male
                            </option>
                        </select>

                        @error('gender')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="date_of_birth" class="">Date Of Birth</label>
                        <input name="date_of_birth" id="date_of_birth" type="text" class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth') ?? $staff->date_of_birth}} " data-toggle="datepicker-year">

                        @error('date_of_birth')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="position-relative form-group">
                        <label for="place_of_birth" class="">Place Of Birth</label>
                        <input name="place_of_birth" id="place_of_birth" type="text" class="form-control @error('place_of_birth') is-invalid @enderror" value="{{ old('place_of_birth') ?? $staff->place_of_birth}}">

                        @error('place_of_birth')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="position-relative form-group">
                        <label for="phone_no" class="">Phone No.</label>
                        <input name="phone_no" id="phone_no" type="text" class="form-control @error('phone_no') is-invalid @enderror" value="{{ old('phone_no') ?? $staff->phone_no}}">

                        @error('phone_no')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="position-relative form-group">
                        <label for="home_address" class="">Home Address</label>
                        <input name="home_address" id="home_address" type="text" class="form-control @error('home_address') is-invalid @enderror" value="{{ old('home_address') ?? $staff->home_address}}">

                        @error('home_address')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="personal_email" class="">Personal Email</label>
                        <input name="personal_email" id="personal_email" type="text" class="form-control @error('personal_email') is-invalid @enderror" value="{{ old('personal_email') ?? $staff->personal_email}}">

                        @error('personal_email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</fieldset>


<fieldset>
    <legend>Official Information</legend>
    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-9">
            <div class="form-row">
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="staff_no" class="">Staff No.</label>
                        <input name="staff_no" id="staff_no" type="text" class="form-control @error('staff_no') is-invalid @enderror" value="{{ old('staff_no') ?? $staff->staff_no}}">

                        @error('staff_no')
                        <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                 </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="official_email" class="">Offical Email</label>
                        <input name="official_email" id="official_email" type="text" class="form-control @error('official_email') is-invalid @enderror" value="{{ old('official_email') ?? $staff->official_email}}">

                        @error('official_email')
                        <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                 </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="date_of_employment" class="">Date Of Employment</label>
                        <input name="date_of_employment" id="date_of_employment" type="text" class="form-control @error('date_of_employment') is-invalid @enderror" value="{{ old('date_of_employment') ?? $staff->date_of_employment}}" data-toggle="datepicker-year">

                        @error('date_of_employment')
                        <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                 </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="department_id" class="">
                            <span>Department</span>
                            <span class="text-danger">*</span>
                        </label>
                        <select name="department_id" id="department_id" class="form-control @error('department_id') is-invalid @enderror">
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{$department->id}}" @if(($department->id == old('department_id')) || ($department->id == $department_id)) selected @endif>
                                    {{$department->name}}
                                </option>
                            @endforeach
                        </select>

                        @error('department_id')
                        <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="job_title_id" class="">
                            <span>Job Title</span>
                            <span class="text-danger">*</span>
                        </label>
                        <select name="job_title_id" id="job_title_id" class="form-control @error('job_title_id') is-invalid @enderror">
                            <option value="">Select Job Title</option>
                            @foreach($job_titles as $job_title)
                                <option value="{{$job_title->id}}" @if(($job_title->id == old('job_title_id')) || ($job_title->id == $job_title_id)) selected @endif>
                                    {{$job_title->title}}
                                </option>
                            @endforeach
                        </select>

                        @error('job_title_id')
                        <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="duty_station" class="">
                            <span>Duty Station</span>
                            <span class="text-danger">*</span>
                        </label>
                        <select name="duty_station" id="duty_station" class="form-control @error('duty_station') is-invalid @enderror">
                            <option value="">Select Region</option>
                            @foreach($regions as $region)
                                <option value="{{$region->name}}" @if(($region->name == old('duty_station')) || ($region->name == $duty_station)) selected @endif>
                                    {{$region->name}}
                                </option>
                            @endforeach
                        </select>

                        @error('duty_station')
                        <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="supervisor_id" class="">
                            <span>Supervisor</span>
                            <span class="text-danger">*</span>
                        </label>
                        <select name="supervisor_id" id="supervisor_id" class="form-control @error('supervisor_id') is-invalid @enderror">
                            <option value="">Select Supervisor</option>
                            @foreach($supervisors as $supervisor)
                                <option value="{{$supervisor->id}}" @if(($supervisor->id == old('supervisor_id')) || ($supervisor->id == $supervisor_id)) selected @endif>
                                    {{ucwords($supervisor->first_name.' '.$supervisor->last_name)}}
                                </option>
                            @endforeach
                        </select>

                        @error('supervisor_id')
                        <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-row">
                <div class="col-md-12">
                    <div class="position-relative form-group">
                        <label>Staff Signature</label>
                        <div class="staff-image-container">
                            <img src="@if($staff->signature != '') /storage/{{$staff->signature}} @else /images/staff-signature.png @endif" alt="Staff Image" style="width: 100%; height: auto; " id="staff-signature">
                        </div>
                        <div class="text-center" style="margin-bottom: 10px;">
                            <div style="padding: 5px; ">
                                <span id="file-name"></span>
                            </div>
                            <label for="signature" class="upload-label" type="button">
                                <i class="pe-7s-upload"></i>
                                <span style="padding-left: 7px; padding-right: 5px;">
                                    Upload Staff Signature
                                </span>
                            </label>
                            <input name="signature" id="signature" type="file"  accept="image/*" class="form-control-file" style="display:none;" >
                        </div>

                        @error('signature')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror

                    </div>
                    <!--script for uploading images -->
                    <script type="text/javascript">

                        $('#signature').on('change', function(){

                            if (this.files && this.files[0]) {
                                var reader = new FileReader();

                                reader.onload = function (e) {
                                    $('#staff-signature').attr('src', e.target.result);
                                };

                                reader.readAsDataURL(this.files[0]);
                            }
                        });

                    </script>
                </div>
            </div>
        </div>
    </div>
</fieldset>


<fieldset>
    <legend>Staff Status</legend>
    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-12">
            <div class="form-row">
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="staff_status" class="">
                            <span>Status</span>
                            <span class="text-danger">*</span>
                        </label>
                        <select name="staff_status" id="staff_status" class="form-control @error('staff_status') is-invalid @enderror">
                            <option value="">Select Status</option>
                            <option value="Active" @if( old('staff_status')  == 'Active' || $staff_status == 'Active') selected @endif>
                                Active
                            </option>
                            <option value="On Contract Renew" @if( old('staff_status')  == 'On Contract Renew' || $staff_status == 'On Contract Renew') selected @endif>
                                On Contract Renew
                            </option>
                            <option value="End Of Contract" @if( old('staff_status')  == 'End Of Contract' || $staff_status == 'End Of Contract') selected @endif>
                                End Of Contract
                            </option>
                            <option value="Resigned" @if( old('staff_status')  == 'Resigned' || $staff_status == 'Resigned') selected @endif>
                                Resigned
                            </option>
                            <option value="Retired" @if( old('staff_status')  == 'Retired' || $staff_status == 'Retired') selected @endif>
                                Retired
                            </option>
                            <option value="Suspended" @if( old('staff_status')  == 'Suspended' || $staff_status == 'Suspended') selected @endif>
                                Suspended
                            </option>
                            <option value="Terminated" @if( old('staff_status') == 'Terminated' || $staff_status == 'Terminated') selected @endif>
                                Terminated
                            </option>
                        </select>

                        @error('staff_status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4" id="date_of_status_change_div">
                    <div class="position-relative form-group">
                        <label for="date_of_status_change" class="" id="date_of_status_change_text">Date Of Termination</label>
                        <input name="date_of_status_change" id="date_of_status_change" type="text" class="form-control @error('date_of_status_change') is-invalid @enderror" value="{{ old('date_of_status_change') ?? $date_of_status_change}}" data-toggle="datepicker-year" >

                        @error('date_of_status_change')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12" id="status_description_div">
                    <div class="position-relative form-group">
                        <label for="status_change_description" class="" id="status_change_description_text">Description</label>
                        <textarea name="status_change_description" id="status_change_description" type="text" class="form-control @error('status_change_description') is-invalid @enderror" >{{ old('status_change_description') ?? $status_change_description}}</textarea>

                        @error('status_change_description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4 " id="status_change_attachments_div">
                    <div class="form-group">

                        <div class="form-group">
                            <label>
                                <span>Attachments</span>
                            </label>
                            <div class="text-left" style="margin-bottom: 5px;">
                                <label for="status_change_attachments" class="upload-label" type="button">
                                    <i class="pe-7s-upload"></i>
                                    <span style="padding-left: 7px; padding-right: 5px;" id="status_change_attachments_text">
                                        Click Here To Upload Attachments
                                    </span>
                                </label>
                                <input name="status_change_attachments[]" id="status_change_attachments" type="file"  accept="image/*,.pdf,.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" class="form-control-file" style="display:none;" multiple >
                            </div>

                            @error('status_change_attachments')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                        </div>

                    </div>
                </div>
                <div class="col-md-8 pt-4" style="display: none; " id="file-name-div">
                    <div class="position-relative form-group">
                        <div class="text-primary" style="padding: 5px; ">
                            <span id="file-count"></span>
                            <span id="file-separator" class="text-danger"></span>
                            <span id="file-name2"></span>
                        </div>
                    </div>
                </div>
            </div>

            <script type="text/javascript">

                $(document).ready(function(){
                    display_staff_status();
                });


                $('#status_change_attachments').on('change', function(){
                    var file_name = $(this).val().split('\\').pop();

                    if(file_name.length >0){
                        $('#file-name2').text(file_name);
                        $('#file-name-div').show();
                    }
                });


                $('#status_change_attachments').on('change', function(){
                    //alert( this.files.length );


                    if (this.files.length > 0) {

                        var file_names = ''; var file_sizes = 0;
                        for (var i = 0; i <= this.files.length - 1; i++) {

                            var file_name = this.files.item(i).name;      // THE NAME OF THE FILE.
                            var file_size = this.files.item(i).size;      // THE SIZE OF THE FILE.

                            file_sizes += Number(file_size);

                            if( i == 0 ){
                                file_names += file_name;
                            }
                            else if( i == this.files.length - 1){
                                file_names += ' ' + file_name;
                            }else{
                                file_names += ', ' + file_name;
                            }

                        }


                        $('#file-name2').text(file_names);
                        $('#file-separator').text(' | ');
                        $('#file-count').text(this.files.length + ' Files');
                        $('#file-name-div').show();

                    }

                });


                $('#staff_status').on('change',function(){
                    display_staff_status();
                });

                function display_staff_status(){

                    var staff_status = $('#staff_status').val();

                    if(staff_status === 'Active'){

                        $('#date_of_status_change_div').hide();
                        $('#status_description_div').hide();
                        $('#status_change_attachments_div').hide();
                    }

                    if(staff_status === 'On Contract Renew'){

                        $('#status_change_description_text').text('Contract Renew Description');

                        $('#date_of_status_change_div').hide();
                        $('#status_description_div').show();
                        $('#status_change_attachments_div').hide();

                    }

                    if(staff_status === 'Resigned'){

                        $('#date_of_status_change_text').text('Date Of Resigning');
                        $('#status_change_description_text').text('Resigning Description');
                        $('#status_change_attachments_text').text('Upload Resigning Documents');


                        $('#date_of_status_change_div').show();
                        $('#status_description_div').show();
                        $('#status_change_attachments_div').show();

                    }
                    if(staff_status === 'Retired'){

                        $('#date_of_status_change_text').text('Date Of Retiring');
                        $('#status_change_description_text').text('Retiring Description');
                        $('#status_change_attachments_text').text('Upload Retiring Documents');

                        $('#date_of_status_change_div').show();
                        $('#status_description_div').show();
                        $('#status_change_attachments_div').show();

                    }
                    if(staff_status === 'Suspended'){

                        $('#date_of_status_change_text').text('Date Of Suspension');
                        $('#status_change_description_text').text('Suspension Description');
                        $('#status_change_attachments_text').text('Upload Suspension Documents');

                        $('#date_of_status_change_div').show();
                        $('#status_description_div').show();
                        $('#status_change_attachments_div').show();

                    }
                    if(staff_status === 'Terminated'){

                        $('#date_of_status_change_text').text('Date Of Termination');
                        $('#status_change_description_text').text('Termination Description');
                        $('#status_change_attachments_text').text('Upload Termination Documents');

                        $('#date_of_status_change_div').show();
                        $('#status_description_div').show();
                        $('#status_change_attachments_div').show();

                    }

                }

            </script>
        </div>
    </div>
</fieldset>


<fieldset class="mt-3">
    <legend>Portal Access</legend>
    <div class="form-row">
        <div class="col-md-3">
            <div class="position-relative form-group">
                <label for="mms_access" class="">
                    <span>Can Use Portal ?</span>
                    <span class="text-danger">*</span>
                </label>
                <select name="mms_access" id="mms_access" class="form-control @error('mms_access') is-invalid @enderror">
                    <option value="no" @if( old('mms_access')  == 'no' || $mms_access == 'no') selected @endif>
                        No
                    </option>
                    <option value="yes" @if( old('mms_access') == 'yes' || $mms_access == 'yes') selected @endif>
                        Yes
                    </option>
                </select>

                @error('mms_access')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="col-md-4" id="role-selection-div" style="display:{{old('mms_access') == 'yes' || $mms_access == 'yes' ? 'block' : 'none'}};">
            <div class="position-relative form-group">
                <label for="role_id" class="">
                    <span>Role in Portal</span>
                    <span class="text-danger">*</span>
                </label>
                <select name="role_id" id="role_id" class="form-control @error('role_id') is-invalid @enderror">
                    <option value="">Select a Role</option>
                    @foreach($system_roles as $system_role)
                        <option value="{{$system_role->id}}" @if(($system_role->id == old('role_id')) || ($system_role->id == $role_id)) selected @endif>
                            {{ucwords(str_replace('-',' ',$system_role->role_name))}}
                        </option>
                    @endforeach
                </select>

                @error('role_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
    </div>


    <script type="text/javascript">

        $('#mms_access').on('change',function(){
            if($(this).val() === 'yes'){
                $('#role-selection-div').show('slow');
            }else{
                $('#role-selection-div').hide();
            }
        });

    </script>

</fieldset>
