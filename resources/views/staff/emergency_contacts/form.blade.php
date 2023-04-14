@csrf
<fieldset>
    <legend>Photo & Basic Information</legend>
    <div class="row" style="margin-bottom: 10px;">        
        <div class="col-md-3">
            <div class="form-row">
                <div class="col-md-12">
                    <div class="position-relative form-group">
                        <label>Staff Dependent Image</label>
                        <div class="staff-image-container">
                            <img src="@if($staff_emergency_contact->image != '') /storage/{{$staff_emergency_contact->image}} @else /images/staff-image.png @endif" alt="Staff Image" style="width: 100%; height: auto; " id="staff-image">
                        </div>
                        <div class="text-center" style="margin-bottom: 10px;">
                            <div style="padding: 5px; ">
                                <span id="file-name"></span>
                            </div>
                            <label for="image" class="upload-label" type="button">
                                <i class="pe-7s-upload"></i>
                                <span style="padding-left: 7px; padding-right: 5px;">
                                    Upload Contact Image
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
                <div class="col-md-6">
                    <div class="position-relative form-group">
                        <label  class="">Staff Name</label>
                        <input  type="text" class="form-control" value="{{ ucwords($staff->first_name.' '.$staff->last_name) }}" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="position-relative form-group">
                        <label  class="">Staff No.</label>
                        <input  type="text" class="form-control" value="{{ $staff->no }}" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="position-relative form-group">
                        <label for="full_name" class="">Contact Full Name</label>
                        <input name="full_name" id="full_name" type="text" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') ?? $staff_emergency_contact->full_name}}">

                        @error('full_name')
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
                <div class="col-md-3">
                    <div class="position-relative form-group">
                        <label for="relationship" class="">
                            <span>Relationship</span>
                            <span class="text-danger">*</span>
                        </label>
                        <input name="relationship" id="relationship" type="text" class="form-control @error('relationship') is-invalid @enderror" value="{{ old('relationship') ?? $staff_emergency_contact->relationship}}">

                        @error('relationship')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="physical_address" class="">
                            <span>Physical Address</span>
                            <span class="text-danger">*</span>
                        </label>
                        <input name="physical_address" id="physical_address" type="text" class="form-control @error('physical_address') is-invalid @enderror" value="{{ old('physical_address') ?? $staff_emergency_contact->physical_address}}">

                        @error('physical_address')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="city" class="">
                            <span>City/Town</span>
                            <span class="text-danger">*</span>
                        </label>
                        <input name="city" id="city" type="text" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') ?? $staff_emergency_contact->city}}">

                        @error('city')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="email" class="">
                            <span>Email</span>
                            <span class="text-danger">*</span>
                        </label>
                        <input name="email" id="email" type="text" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') ?? $staff_emergency_contact->email}}">

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="cell_phone" class="">
                            <span>Cell Phone</span>
                            <span class="text-danger">*</span>
                        </label>
                        <input name="cell_phone" id="cell_phone" type="text" class="form-control @error('cell_phone') is-invalid @enderror" value="{{ old('cell_phone') ?? $staff_emergency_contact->cell_phone}}">

                        @error('cell_phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="home_phone" class="">
                            <span>Home Phone</span>
                        </label>
                        <input name="home_phone" id="home_phone" type="text" class="form-control @error('home_phone') is-invalid @enderror" value="{{ old('home_phone') ?? $staff_emergency_contact->home_phone}}">

                        @error('home_phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="business_phone" class="">
                            <span>Business Phone</span>
                        </label>
                        <input name="business_phone" id="business_phone" type="text" class="form-control @error('business_phone') is-invalid @enderror" value="{{ old('business_phone') ?? $staff_emergency_contact->business_phone}}">

                        @error('business_phone')
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


<!--scripts -->
<script type="text/javascript">

    $('#certificate').on('change', function(){
        var file_name = $(this).val().split('\\').pop();

        if(file_name.length >0){
            $('#file-name2').text(file_name);
            $('#file-name-div').show();
        }
    })

</script>
