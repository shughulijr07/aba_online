@csrf
<div class="form-row">
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="country" class="">Country</label>
            <select class="form-control" name="country_id" id="country">
                @foreach($countries as $country)
                    <option value="{{$country->id}}" @if($country->id==old('country')) selected @endif ?? @if($country->id==$region->country_id) selected @endif>
                        {{$country->name}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="regionName" class="">Region</label>
            <input name="name" id="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') ?? $region->name}} ">

            @error('regionName')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>
</div>