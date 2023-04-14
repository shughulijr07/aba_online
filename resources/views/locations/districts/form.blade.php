@csrf
<div class="form-row">
    <div class="col-md-4">
        <div class="position-relative form-group">
            <label for="country_id" class="">Country</label>
            <select name="country" id="country" class="form-control @error('country') is-invalid @enderror">                
                <option value="">Select Country</option>
                @foreach($countries as $country)
                    <option value="{{$country->id}}" @if(($country->id == old('country')) || ($country->id == $country_id)) selected @endif>
                        {{$country->name}}
                    </option>
                @endforeach
            </select>
            
            @error('country')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="position-relative form-group">
            <label for="region" class="">
                <span>Region</span>
                <span class="text-danger">*   </span>
                <span id="region_desc" class="text-danger">
                    @if( (old('country') == '') && ($country_id == '')) ( Select Country First ) @endif 
                </span>
            </label>
            <select name="region" id="region" class="form-control @error('region') is-invalid @enderror"> 
                <option value="">Select Region</option>
                @foreach($regions as $region)
                    @if( ($region->country_id == old('country')) || ($region->country_id == $country_id)) )
                        <option value="{{$region->id}}" @if(($region->id == old('region')) || ($region->id == $region_id)) selected @endif>
                            {{$region->name}}
                        </option>
                    @endif
                @endforeach
            </select>

            @error('region')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="position-relative form-group">
            <label for="district" class="">
                <span>District</span>
                <span class="text-danger">*   </span>
                <span id="district_desc" class="text-danger">
                    @if(old('region') == '')    ( Select Region First ) @endif
                </span>
            </label>
            <input name="district" id="district_name" type="text" class="form-control @error('district') is-invalid @enderror" value="{{ old('district') ?? $district->name}} " @if((old('region') == '') && ($region_id == '')) disabled @endif >

            @error('district')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>
</div>

{{ csrf_field() }}

<script type="text/javascript">

    $("#country").on('change',function () {
        var country_name = $(this).find(":selected").text();
        var country_id = $(this).find(":selected").val(); 
        var _token = $('input[name="_token"]').val();

        //clear regions selection
        $("#region option").remove();

        //clear district
        $('#district_name').val('');
        $('#district_name').prop('disabled',true);
        
        get_regions(country_id,_token);
    });

    $("#region").on('change',function () {
        var region_name = $(this).find(":selected").text();
        var region_id = $(this).find(":selected").val();

        if (region_id != '') {
            $('#district_desc').text('');
            $('#district_name').prop('disabled',false);
        }
        else{
            $('#district_desc').text('( Select Region First )');
            $('#district_name').prop('disabled',true);            
        }
        

        //clear regions selection
        //$("#region option").remove();
        
    });


    function get_regions(country_id,_token)
    { 
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
                    add_to_selections(data);
                }else{
                    add_default_selection();
                }
            }
        })
    }


    function add_to_selections(regions){     

        //remove description at the top of region selection input
        $('#region_desc').text('');

        //add regions as selection options
        $('#region').append($("<option></option>").attr("value",'').text('Select Region'));
        $.each(regions,function(i,region){
            $('#region').append($("<option></option>").attr("value",region.id).text(region.name));
        });

    }

    function add_default_selection(){

        //remove description at the top of region selection input
        $('#region_desc').text('( Select Country First )');

        //add default select option
        $('#region').append($("<option></option>").attr("value",'').text('Select Region'));
    }

</script>