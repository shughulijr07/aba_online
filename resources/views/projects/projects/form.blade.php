@csrf
<div class="form-row">
    <div class="col-md-7">
        <div class="position-relative form-group">
            <label for="name" class="">Client Name</label>
            <input name="name" id="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') ?? $project->name}}">

            @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
             </span>
            @enderror
        </div>
    </div>
    <div class="col-md-5">
        <div class="position-relative form-group">
            <label for="number" class="">Client Number</label>
            <input name="number" id="number" type="text" class="form-control @error('number') is-invalid @enderror" value="{{ old('number') ?? $project->number}}">

            @error('number')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
             </span>
            @enderror
        </div>
    </div>
</div>