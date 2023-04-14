@csrf
<div class="form-row">
    <div class="col-md-12">
        <div class="position-relative form-group">
            <label for="title" class="">Staff Job Title</label>
            <input name="title" id="title" type="text" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') ?? $staff_job_title->title}} ">

            @error('title')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
             </span>
            @enderror
        </div>
    </div>
</div>