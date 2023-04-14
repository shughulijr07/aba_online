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
                            <img src="@if($staff_dependent->image != '') /storage/{{$staff_dependent->image}} @else /images/staff-image.png @endif" alt="Staff Image" style="width: 100%; height: auto; " id="staff-image">
                        </div>
                        <div class="text-center" style="margin-bottom: 10px;">
                            <div style="padding: 5px; ">
                                <span id="file-name"></span>
                            </div>
                            <label for="image" class="upload-label" type="button">
                                <i class="pe-7s-upload"></i>
                                <span style="padding-left: 7px; padding-right: 5px;">
                                    Upload Dependent Image
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
                        <label  class="">Dependent To </label>
                        <input  type="text" class="form-control" value="{{ ucwords($staff->first_name.' '.$staff->last_name) }}" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="position-relative form-group">
                        <label  class="">Dependent To No.</label>
                        <input  type="text" class="form-control" value="{{ $staff->no }}" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="position-relative form-group">
                        <label for="full_name" class="">Dependent Full Name</label>
                        <input name="full_name" id="full_name" type="text" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') ?? $staff_dependent->full_name}}">

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
                        <label for="date_of_birth" class="">Date Of Birth</label>
                        <input name="date_of_birth" id="date_of_birth" type="text" class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth') ?? $staff_dependent->date_of_birth}} " data-toggle="datepicker-year">

                        @error('date_of_birth')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="position-relative form-group">
                        <label for="birth_certificate_no" class="">Birth Certificate No.</label>
                        <input name="birth_certificate_no" id="birth_certificate_no" type="text" class="form-control @error('birth_certificate_no') is-invalid @enderror" value="{{ old('birth_certificate_no') ?? $staff_dependent->birth_certificate_no}}">

                        @error('birth_certificate_no')
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
                        <select name="relationship" id="relationship" class="form-control @error('relationship') is-invalid @enderror">
                            <option value="">Select Relationship</option>
                            @foreach($relationships as $relationship)
                                <option value="{{$relationship}}" @if( old('relationship')  == $relationship || $staff_dependent->relationship == $relationship) selected @endif>
                                    {{$relationship}}
                                </option>
                            @endforeach
                        </select>

                        @error('relationship')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="position-relative form-group">
                        <label for="to_be_on_medical" class="">
                            <span>To be on Medical</span>
                            <span class="text-danger">*</span>
                        </label>
                        <select name="to_be_on_medical" id="to_be_on_medical" class="form-control @error('to_be_on_medical') is-invalid @enderror">
                            <option value="No" @if( old('to_be_on_medical')  == 'No' || $staff_dependent->to_be_on_medical == 'No') selected @endif>
                                No
                            </option>
                            <option value="Yes" @if( old('to_be_on_medical') == 'Yes' || $staff_dependent->to_be_on_medical == 'Yes') selected @endif>
                                Yes
                            </option>
                        </select>

                        @error('to_be_on_medical')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 pt-4" id="document_div">
                    <div class="position-relative form-group">
                        <div class="text-center" style="margin-bottom: 10px;">
                            <label for="certificate" class="upload-label col-md-12" type="button">
                                <i class="pe-7s-upload"></i>
                                <span style="padding-left: 7px; padding-right: 5px;">
                                    Upload Birth/Marriage Certificate
                                </span>
                            </label>
                            <input name="certificate" id="certificate" type="file"  accept=".jpeg,.jpg,.png,.pdf,.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" class="form-control-file" style="display:none;" >
                        </div>


                        @error('barcodes_file')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                    </div>
                </div>
                <div class="col-md-6 pt-4" style="@if(!isset($staff_dependent->certificate)) display: none; @endif" id="file-name-div">
                    <div class="position-relative form-group">
                        <div style="padding: 5px;">
                            <i class="pe-7s-news-paper" id="file-icon" ></i>
                            <span id="file-name2">
                                @if( isset($staff_dependent->certificate) )
                                    <a href="{{url($staff_dependent->certificate)}}" target="_blank" class="mr-3">
                                        <i class="pe-7s-exapnd2"></i> View Certificate
                                    </a>
                                @endif
                            </span>
                        </div>
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
