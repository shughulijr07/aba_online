@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="pe-7s-news-paper"></i>
                </div>
                <div>
                    <div class="text-primary">Retirement Records</div>
                    <div class="page-title-subheading">
                        Use the form below to filter and see your travelling records.
                    </div>
                </div>
            </div>

            <!--actions' menu starts here -->
            <!--actions' menu ends here -->

        </div>
    </div>
    <div class="tab-content">
        <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">

            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title text-danger">Searching Form</h5>

                    <form action="/my_retirement_records" method="POST" enctype="multipart/form-data" id="valid-form" target="_blank">
                        @csrf
                        {{ csrf_field() }}

                        <fieldset>
                            <legend class="text-danger"></legend>
                            <div class="form-row">



                                <div class="col-md-4" id="year_div" >
                                    <div class="position-relative form-group">
                                        <label for="year" class="">
                                            <span>Year</span>
                                        </label>
                                        <select name="year" id="year" class="form-control">
                                            <?php $varying_year=date("Y");?>
                                            @while( $varying_year >= $initial_year )
                                                <option value="{{$varying_year}}" @if($varying_year == $year) selected @endif>
                                                    {{$varying_year}}
                                                </option>

                                                <?php $varying_year--;?>
                                            @endwhile
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </fieldset>

                        <button class="mt-2 btn btn-primary" id="request_leave_btn">View</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection

