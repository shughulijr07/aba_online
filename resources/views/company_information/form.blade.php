@csrf
{{ csrf_field() }}

<fieldset>
    <div class="row">
        <div class="col-md-3"> 
            <div class="form-row">
                <div class="col-md-12">
                    <div class="position-relative form-group">                
                        <label>Company Logo</label>
                        <div class="staff-image-container">
                            <img src="@if($company_info->logo != '') /storage/{{$company_info->logo}} @else /images/company-logo.png @endif" alt="Member's Logo" style="width: 100%; height: auto; " id="company-logo">
                        </div>
                        <div class="text-center" style="margin-bottom: 10px;">
                            <div style="padding: 5px; ">
                                <span id="file-name"></span>
                            </div>
                            <label for="logo" class="upload-label" type="button">
                                <i class="pe-7s-upload"></i>
                                <span style="padding-left: 7px; padding-right: 5px;">
                                    Upload Company Logo
                                </span>
                            </label>   
                            <input name="logo" id="logo" type="file"  accept="image/*" class="form-control-file" style="display:none;" >
                        </div> 

                        @error('logo')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror 
                      
                    </div>  
                </div>    
            </div>        
        </div>
        <div class="col-md-9"> 
            <div class="form-row">

                <div class="col-md-7">
                    <div class="position-relative form-group">
                        <label for="company_name" class="">
                            <span>Company Name</span>
                            <span class="text-danger">*</span>
                        </label>
                        <input name="company_name" id="company_name" type="text" class="form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name') ?? $company_info->company_name}} ">

                        @error('company_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="position-relative form-group">
                        <label for="physical_address" class="">
                            <span>Physical Address</span>
                            <span class="text-danger">*</span>
                        </label>
                        <input name="physical_address" id="physical_address" type="text" class="form-control @error('physical_address') is-invalid @enderror" value="{{ old('physical_address') ?? $company_info->physical_address}} ">

                        @error('physical_address')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="postal_address" class="">Postal Address</label>
                        <input name="postal_address" id="postal_address" type="text" class="form-control @error('postal_address') is-invalid @enderror" value="{{ old('postal_address') ?? $company_info->postal_address}} ">

                        @error('postal_address')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="street_name" class="">
                            <span>Street Name</span>
                            <span class="text-danger">*</span>
                        </label>
                        <input name="street_name" id="street_name" type="text" class="form-control @error('street_name') is-invalid @enderror" value="{{ old('street_name') ?? $company_info->street_name}} ">

                        @error('street_name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="plot_no" class="">
                            <span>Plot No.</span>
                            <span class="text-danger">*</span>
                        </label>
                        <input name="plot_no" id="plot_no" type="text" class="form-control @error('plot_no') is-invalid @enderror" value="{{ old('plot_no') ?? $company_info->plot_no}} ">

                        @error('plot_no')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="house_no" class="">
                            <span>Floor No.</span>
                            <span class="text-danger">*</span>
                        </label>
                        <input name="house_no" id="house_no" type="text" class="form-control @error('house_no') is-invalid @enderror" value="{{ old('house_no') ?? $company_info->house_no}} ">

                        @error('house_no')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                         </span>
                        @enderror
                    </div>
                </div>
                
            </div>  

            <fieldset>  
                <legend>Contacts</legend> 
                <div class="form-row">  
                    <div class="col-md-4">
                        <div class="position-relative form-group">
                            <label for="office_phone_no1" class="">
                                <span>Office Phone No.1</span>
                                <span class="text-danger">*</span>
                            </label>
                            <input name="office_phone_no1" id="office_phone_no1" type="text" class="form-control @error('office_phone_no1') is-invalid @enderror" value="{{ old('office_phone_no1') ?? $company_info->office_phone_no1}} ">

                            @error('office_phone_no1')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                             </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="position-relative form-group">
                            <label for="office_phone_no2" class="">Office Phone No.2</label>
                            <input name="office_phone_no2" id="office_phone_no2" type="text" class="form-control @error('office_phone_no2') is-invalid @enderror" value="{{ old('office_phone_no2') ?? $company_info->office_phone_no2}} ">

                            @error('office_phone_no2')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                             </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="position-relative form-group">
                            <label for="fax_no" class="">Fax No.</label>
                            <input name="fax_no" id="fax_no" type="text" class="form-control @error('fax_no') is-invalid @enderror" value="{{ old('fax_no') ?? $company_info->fax_no}} ">

                            @error('fax_no')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                             </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="position-relative form-group">
                            <label for="email" class="">
                                <span>Email</span>
                                <span class="text-danger">*</span>
                            </label>
                            <input name="email" id="email" type="text" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') ?? $company_info->email}} ">

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                             </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div class="position-relative form-group">
                            <label for="website" class="">
                                <span>Website</span>
                                <span class="text-danger">*</span>
                            </label>
                            <input name="website" id="website" type="text" class="form-control @error('website') is-invalid @enderror" value="{{ old('website') ?? $company_info->website}} ">

                            @error('website')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                             </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </fieldset> 

        </div>
    </div>
</fieldset>

<fieldset>
    <legend>Location</legend>
    <div class="form-row">
        <div class="col-md-3">
            <div class="position-relative form-group">
                <label for="country_id" class="">
                    <span>Country</span>
                    <span class="text-danger">*</span>
                </label>
                <select name="country_id" id="country_id" class="form-control @error('country') is-invalid @enderror">
                    <option value="">Select Country</option>
                    @foreach($countries as $country)
                        <option value="{{$country->id}}" @if(($country->id == old('country_id')) || ($country->id == $country_id)) selected @endif>
                            {{$country->name}}
                        </option>
                    @endforeach
                </select>

                @error('country_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="col-md-3">
            <div class="position-relative form-group">
                <label for="region_id" class="">
                    <span>Region</span>
                    <span class="text-danger">*</span>
                </label>
                <select name="region_id" id="region_id" class="form-control @error('region_id') is-invalid @enderror">
                    @if( (old('country') == '') && ($country_id == ''))
                        <option value="">Select Country First</option>
                    @endif

                    @foreach($regions as $region)
                        @if( ($region->country_id == old('country_id')) || ($region->country_id == $country_id)) )
                            <option value="{{$region->id}}" @if(($region->id == old('region_id')) || ($region->id == $region_id)) selected @endif>
                                {{$region->name}}
                            </option>
                        @endif
                    @endforeach
                </select>

                @error('region_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="col-md-3">
            <div class="position-relative form-group">
                <label for="district_id" class="">
                    <span>District</span>
                    <span class="text-danger">*</span>
                </label>
                <select name="district_id" id="district_id" class="form-control @error('district_id') is-invalid @enderror">
                    @if( (old('region_id') == '') && ($region_id == ''))
                        <option value="">Select Region First</option>
                    @endif

                    @foreach($districts as $district)
                        @if( ($district->region_id == old('region_id')) || ($district->region_id == $region_id)) )
                            <option value="{{$district->id}}" @if(($district->id == old('district_id')) || ($district->id == $district_id)) selected @endif>
                                {{$district->name}}
                            </option>
                        @endif
                    @endforeach
                </select>

                @error('district_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="col-md-3">
            <div class="position-relative form-group">
                <label for="ward_id" class="">
                    <span>Ward</span>
                    <span class="text-danger">*</span>
                </label>
                <select name="ward_id" id="ward_id" class="form-control @error('ward_id') is-invalid @enderror">
                    @if( (old('district_id') == '') && ($district_id == ''))
                        <option value="">Select District First</option>
                    @else
                        <option value="">Select Ward</option>
                    @endif

                    @foreach($wards as $ward)
                        @if( ($ward->district_id == old('district_id')) || ($ward->district_id == $district_id)) )
                            <option value="{{$ward->id}}" @if(($ward->id == old('ward_id')) || ($ward->id == $ward_id)) selected @endif>
                                {{$ward->name}}
                            </option>
                        @endif
                    @endforeach
                </select>

                @error('ward_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <!-- Script for location fields -->
        <script type="text/javascript">

            $("#country_id").on('change',function () {
                var country_name = $(this).find(":selected").text();
                var country_id = $(this).find(":selected").val();
                var _token = $('input[name="_token"]').val();

                //clear regions selection
                $("#region_id option").remove();

                //clear districts selection
                $("#district_id option").remove();

                //clear clear wards
                $("#ward_id option").remove();

                get_regions(country_id,_token);
            });

            $("#region_id").on('change',function () {
                var region_name = $(this).find(":selected").text();
                var region_id = $(this).find(":selected").val();
                var _token = $('input[name="_token"]').val();

                if (region_id != '') {

                    //clear districts selection
                    $("#district_id option").remove();

                    //clear clear wards
                    $("#ward_id option").remove();

                    get_districts(region_id,_token);
                }
                else{

                    //clear districts selection
                    $("#district_id option").remove();

                    //add default selection
                    $('#district_id').append($("<option></option>").attr("value",'').text('Select Region First'));

                    //clear clear wards
                    $("#ward_id option").remove();

                    //add default selection
                    $('#ward_id').append($("<option></option>").attr("value",'').text('Select District First'));
                }

            });

            $("#district_id").on('change',function () {
                var district_name = $(this).find(":selected").text();
                var district_id = $(this).find(":selected").val();
                var _token = $('input[name="_token"]').val();

                if (district_id != '') {

                    //clear clear wards
                    $("#ward_id option").remove();

                    get_wards(district_id,_token);

                }
                else{

                    //clear clear wards
                    $("#ward_id option").remove();

                    //add default selection
                    $('#ward_id').append($("<option></option>").attr("value",'').text('Select District First'));
                }


            });


            function get_regions(country_id,_token){

                $.ajax({
                    url:"{{ route('regions.ajax_get') }}",
                    method:"POST",
                    data:{country_id:country_id,_token:_token},
                    success:function(data)
                    {
                        //add the list to selection option
                        //console.log(data);
                        data = JSON.parse(data);

                        if (data.length > 0) {
                            add_to_regions_selections(data);
                        }else{
                            add_default_region_selection();
                        }
                    }
                })

            }

            function add_to_regions_selections(regions){

                //add regions as selection options
                $('#region_id').append($("<option></option>").attr("value",'').text('Select Region'));
                $.each(regions,function(i,region){
                    $('#region_id').append($("<option></option>").attr("value",region.id).text(region.name));
                });


                //add default selection
                $('#district_id').append($("<option></option>").attr("value",'').text('Select Region First'));

                //add default selection
                $('#ward_id').append($("<option></option>").attr("value",'').text('Select District First'));

            }

            function add_default_region_selection(){


                //add default region selection
                $('#region_id').append($("<option></option>").attr("value",'').text('Select Country First'));

                //add default district selection
                $('#district_id').append($("<option></option>").attr("value",'').text('Select Region First'));

                //add default ward selection
                $('#ward_id').append($("<option></option>").attr("value",'').text('Select District First'));

            }

            function get_districts(region_id,_token){

                $.ajax({
                    url:"{{ route('districts.ajax_get') }}",
                    method:"POST",
                    data:{region_id:region_id,_token:_token},
                    success:function(data)
                    {
                        data = JSON.parse(data);

                        if (data.length > 0) {
                            add_to_district_selections(data);
                        }else{
                            add_default_district_selection();
                        }
                    }
                });

            }

            function add_to_district_selections(districts){

                //add district as selection options
                $('#district_id').append($("<option></option>").attr("value",'').text('Select District'));
                $.each(districts,function(i,district){
                    $('#district_id').append($("<option></option>").attr("value",district.id).text(district.name));
                });

                //add default selection
                $('#ward_id').append($("<option></option>").attr("value",'').text('Select District First'));

            }

            function add_default_district_selection(){

                //add default district selection
                $('#district_id').append($("<option></option>").attr("value",'').text('Select Region First'));

                //add default ward selection
                $('#ward_id').append($("<option></option>").attr("value",'').text('Select District First'));

            }

            function get_wards(district_id,_token){

                $.ajax({
                    url:"{{ route('wards.ajax_get') }}",
                    method:"POST",
                    data:{district_id:district_id,_token:_token},
                    success:function(data)
                    {
                        data = JSON.parse(data);

                        if (data.length > 0) {
                            add_to_ward_selections(data);
                        }else{
                            add_default_ward_selection();
                        }
                    }
                });

            }

            function add_to_ward_selections(wards){

                //add district as selection options
                $('#ward_id').append($("<option></option>").attr("value",'').text('Select Ward'));
                $.each(wards,function(i,ward){
                    $('#ward_id').append($("<option></option>").attr("value",ward.id).text(ward.name));
                });


            }

            function add_default_ward_selection(){

                //add default ward selection
                $('#ward_id').append($("<option></option>").attr("value",'').text('Select District First'));

            }

        </script>
    </div>
</fieldset>


<!--script for uploading images -->
<script type="text/javascript">

    $('#logo').on('change', function(){

        var input = this;
        var image_id = '#company-logo';
        upload_file(input,image_id);
    });

    function upload_file(input,image_id){
       
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $(image_id).attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }

    }

</script>  