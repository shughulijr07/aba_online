@csrf
<div class="form-row">
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="country" class="">List Of Countries In Africa</label>
            <select class="form-control" id="af_country">
                @foreach($africanCountries as $af_country)
                    <option value="{{$af_country->id}}" @if($af_country->name==$country->name) selected @endif>
                        {{$af_country->name}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="name" class="">Selected Country</label>
            <input name="name" id="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') ?? $country->name}} ">

            @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
             </span>
            @enderror
        </div>
    </div>
</div>
<script type="text/javascript">

    //prevent user from entering crops by typing
    $('#name').keypress(function(e) { 
        e.preventDefault();
    });

    $("#af_country").on('change',function () {
        var selectedCountry = $(this).find(":selected").text();
        $("#name").val(selectedCountry.trim());
    });
</script>