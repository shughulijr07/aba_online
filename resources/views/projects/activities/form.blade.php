@csrf
<div class="form-row">
    <div class="col-md-12" >
        <div class="position-relative form-group">
            <label for="project_id" class="">
                <span>Client</span>
                <span class="text-danger description_required">*</span>
            </label>
            <select name="project_id" id="project_id" class="form-control @error('project_id') is-invalid @enderror">
                <option value="">Select Client</option>
                @foreach($projects as $project)
                    <option value="{{$project->id}}" @if(($project->id == old('project_id')) || ($project->id == $activity->project_id)) selected @endif>
                        {{$project->number." : ".$project->name}}
                    </option>
                @endforeach
            </select>

            @error('project_id')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>
    <div class="col-md-4">
        <div class="position-relative form-group">
            <label for="code" class="">
                Activity Code
                <span class="text-danger description_required">*</span>
            </label>
            <input name="code" id="code" type="text" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') ?? $activity->code}}">

            @error('code')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
             </span>
            @enderror
        </div>
    </div>
    <div class="col-md-8">
        <div class="position-relative form-group">
            <label for="name" class="">
                Activity Name
                <span class="text-danger description_required">*</span>
            </label>
            <input name="name" id="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') ?? $activity->name}}">

            @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
             </span>
            @enderror
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#project_id').select2();
</script>
