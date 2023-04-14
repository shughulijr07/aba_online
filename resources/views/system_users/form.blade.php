@csrf
<fieldset>
    <legend>Photo & Basic Information</legend>
    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-3">
            <div class="form-row">
                <div class="col-md-12">
                    <div class="position-relative form-group">
                        <label>User Image <span class="text-primary">(Optional)</span></label>
                        <div class="user-image-container">
                            <img src="@if($system_user->image != '') /storage/{{$system_user->image}} @else /images/staff-image.png @endif" alt="User Image" style="width: 100%; height: auto; " id="system_user-image">
                        </div>
                        <div class="text-center" style="margin-bottom: 10px;">
                            <div style="padding: 5px; ">
                                <span id="file-name"></span>
                            </div>
                            <label for="image" class="upload-label" type="button">
                                <i class="pe-7s-upload"></i>
                                <span style="padding-left: 7px; padding-right: 5px;">
                                    Upload User Image
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
                                    $('#system_user-image').attr('src', e.target.result);
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
                        <label for="first_name" class="">First Name <span class="text-danger">(Mandatory)*</span></label>
                        <input name="first_name" id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') ?? $system_user->first_name}}">

                        @error('first_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="middle_name" class="">Middle Name <span class="text-danger">(Mandatory)*</span></label>
                        <input name="middle_name" id="middle_name" type="text" class="form-control @error('middle_name') is-invalid @enderror" value="{{ old('middle_name') ?? $system_user->middle_name}}">

                        @error('middle_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="last_name" class="">Last Name <span class="text-danger">(Mandatory)*</span></label>
                        <input name="last_name" id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') ?? $system_user->last_name}}">

                        @error('last_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="gender" class="">
                            <span>Gender</span>
                            <span class="text-danger">(Mandatory)*</span>
                        </label>
                        <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror">
                            <option value="">Select Gender</option>
                            <option value="Female" @if( old('gender')  == 'Female' || $system_user->gender == 'Female') selected @endif>
                                Female
                            </option>
                            <option value="Male" @if( old('gender') == 'Male' || $system_user->gender == 'Male') selected @endif>
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
                        <label for="phone_no" class="">Phone No. <span class="text-danger">(Mandatory)*</span></label>
                        <input name="phone_no" id="phone_no" type="text" class="form-control @error('phone_no') is-invalid @enderror" value="{{ old('phone_no') ?? $system_user->phone_no}}">

                        @error('phone_no')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="email" class="">Email <span class="text-danger">(Mandatory)*</span></label>
                        <input name="email" id="email" type="text" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') ?? $system_user->email}}">

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="position-relative form-group">
                        <label for="company" class="">Company <span class="text-primary">(Optional)</span></label>
                        <input name="company" id="company" type="text" class="form-control @error('company') is-invalid @enderror" value="{{ old('company') ?? $system_user->company}}">

                        @error('company')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="position-relative form-group">
                        <label for="description" class="">User Description <span class="text-primary">(Optional)</span></label>
                        <textarea name="description" id="description" type="text" class="form-control @error('description') is-invalid @enderror" value="{{ old('description') ?? $system_user->description}}"></textarea>

                        @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="status" class="">
                            <span>Status</span>
                            <span class="text-danger">(Mandatory)*</span>
                        </label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                            <option value="">Select Status</option>
                            <option value="Active" @if( old('status')  == 'Active' || $system_user->status == 'Active') selected @endif>
                                Active
                            </option>
                            <option value="Inactive" @if( old('status')  == 'Inactive' || $system_user->status == 'Inactive') selected @endif>
                                Inactive
                            </option>
                        </select>

                        @error('status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4" id="role-selection-div">
                    <div class="position-relative form-group">
                        <label for="system_role_id" class="">
                            <span>Role in System</span>
                            <span class="text-danger">(Mandatory)*</span>
                        </label>
                        <select name="system_role_id" id="system_role_id" class="form-control @error('system_role_id') is-invalid @enderror">
                            <option value="">Select a Role</option>
                            @foreach($system_roles as $system_role)
                                @if($system_role->id != 1)
                                    <option value="{{$system_role->id}}" @if(($system_role->id == old('system_role_id')) || ($system_role->id == $system_user->system_role_id)) selected @endif>
                                        {{ucwords(str_replace('-',' ',$system_role->role_name))}}
                                    </option>
                                @endif
                            @endforeach
                        </select>

                        @error('system_role_id')
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




