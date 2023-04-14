@csrf
<div class="form-row">
    <div class="col-md-12">
        <div class="position-relative form-group">
            <label for="name" class="">Item Name</label>
            <input name="name" id="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') ?? $numbered_item->name}}">

            @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
             </span>
            @enderror
        </div>
    </div>
</div>