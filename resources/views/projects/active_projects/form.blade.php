@csrf
{{ csrf_field() }}


<div class="row">

    <!-- Date & Year -->
    <div class="col-md-4">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title text-danger">Year & Month</h5>

                <div class="row mb-1">
                    <div class="col-md-12">
                        <fieldset>
                            <legend class="text-danger"></legend>
                            <div class="form-row">

                                <div class="col-md-12" id="year_div">
                                    <div class="position-relative form-group">
                                        <label for="year" class="">
                                            <span>Year</span>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select name="year" id="year"  class="form-control @error('country') is-invalid @enderror">
                                            <?php $varying_year=date("Y"); $last_year = date('Y') - 4; ?>
                                            @while( $varying_year >= $last_year )
                                                <option value="{{$varying_year}}" @if( ($varying_year == old('year')) ||  $varying_year == $active_project->year) selected @endif>
                                                    {{$varying_year}}
                                                </option>

                                                <?php $varying_year--;?>
                                            @endwhile
                                        </select>

                                        @error('year')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="position-relative form-group">
                                        <label for="month" class="">
                                            <span>Month</span>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select name="month" id="month" class="form-control @error('month') is-invalid @enderror">
                                            @foreach($months as $value => $month)
                                                <option value="{{$value}}" @if( ($value == old('month')) ||  $value == $active_project->month) selected @endif>{{$month}}</option>
                                            @endforeach
                                        </select>

                                        @error('month')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="position-relative form-group">
                                        <button class="mt-2 btn btn-primary">Set Active Clients</button>
                                    </div>
                                </div>

                            </div>
                        </fieldset>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Date & Year Ends Here-->

    <!-- Project Selection -->
    <div class="col-md-8">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title text-danger">Clients List</h5>
                <div class="row">
                    <div class="col-md-12">
                        @error('projects')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Client Name.</th>
                                <th>Client No.</th>
                                <th style="width: 15%;" class="text-center">
                                    <input type="checkbox"  class="select-all-rows-checkbox" value="0">
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php $n = 1;?>
                                @foreach($all_projects as $project)
                                    <tr class="data-row">
                                        <td>{{$n}}</td>
                                        <td>{{ $project->name }}</td>
                                        <td>{{ $project->number }}</td>
                                        <td class="data-column text-center">
                                            <input type="checkbox" name="projects[]" class="select-one-row-checkbox mr-3" value="{{$project->number}}" @if( in_array($project->number,$active_projects) ) checked @endif>
                                        </td>
                                    </tr>
                                    <?php $n++;?>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Project Selection Ends Here-->

</div>

<script type="text/javascript">

    //when select-multiple-rows checkbox is clicked
    $(".select-all-rows-checkbox").on("click",function(){

        var select_all = '';
        $(this).is(":checked") ? select_all = 'true' : select_all = 'false';

        //get all check boxes
        var all_checkboxes = $('.select-one-row-checkbox');

        //select of deselect all checkboxes
        all_checkboxes.each(function(index){

            select_all == 'true' ? $(this).prop('checked',true) :  $(this).prop('checked',false);
        });
    });



</script>


