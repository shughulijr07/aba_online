@extends('layouts.administrator.admin')


@section('content')

    <!-- title -->
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div>
                    <div class="text-primary"><span class="text-danger">{{$employee_name}} </span> {{$year}} Performance  </div>
                    <div class="page-title-subheading"></div>
                </div>
            </div>

            <!--actions' menu starts here -->
            <!--actions' menu ends here -->

        </div>
    </div>


    <!-- data 1 -->
    <div class="container" style="min-width: 100%">
        <div class="row">

            <!-- Performance Objective Section -->
            <div class="col-md-12 col-lg-9 col-xl-9" id="performance_objective_section">
                <fieldset disabled>
                    @if(isset($performance_objective->id))
                        @include('performance_objectives.form')
                    @else

                        <div class="row">
                            <div class="col-sm-12" >

                                <div class="main-card mb-3 card">
                                    <div class="card-body">
                                        <h5 class="card-title text-danger">Employee's Performance Objectives</h5>
                                        <div class="text-center" style="margin: 220px 0;">
                                            <h5>
                                                <span class="text-danger">{{$employee_name}}</span>
                                                Have not submitted Performance Objectives for the year {{$year}}
                                            </h5>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    @endif
                </fieldset>
            </div>

            <!-- Message & Actions Section -->
            <div class="col-md-12 col-lg-3 col-xl-3">
                @if(session()->has('message'))
                    <div  class="mb-3 card alert alert-primary" id="notifications-div">
                        <div class="p-3 card-body ">
                            <div class="text-center">
                                <h5 class="" id="message">{{session()->get('message')}}</h5>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="mb-3 card">
                    <div class="card-header-tab card-header">
                        <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                            <i class="header-icon pe-7s-menu icon-gradient bg-happy-itmeo"> </i>
                            Status
                        </div>
                    </div>
                    <div class="p-0 card-body">
                        <div class="dropdown-menu-header mt-0 mb-0">
                            <div class="dropdown-menu-header-inner bg-heavy-rain">
                                <div class="menu-header-image opacity-1" style="background-image: url('assets/images/dropdown-header/city3.jpg');"></div>
                                <div class="menu-header-content text-dark">
                                    <h6 class="menu-header-title">{{$staff_performance_statuses[$staff_performance->status]}}</h6>
                                    <h7 class="menu-header-subtitle"></h7>
                                </div>
                            </div>
                        </div>
                        <ul class="tabs-animated-shadow tabs-animated nav nav-justified tabs-shadow-bordered p-3">
                            <li class="nav-item">
                                <a role="tab" class="nav-link show active" id="tab-c-0" data-toggle="tab" href="#tab-animated-0" aria-selected="true">
                                    <span>Summary</span>
                                </a>
                            </li>
                            @can('view-menu','leave_requests_for_approving_menu_item')
                                @if( auth()->user()->staff->id == $staff_performance->staff->supervisor_id ||  in_array(auth()->user()->role_id,[1]) )
                                    <li class="nav-item">
                                        <a role="tab" class="nav-link show" id="tab-c-1" data-toggle="tab" href="#tab-animated-1" aria-selected="false">
                                            <span>Assessment</span>
                                        </a>
                                    </li>
                                @endif
                            @endcan
                        </ul>
                        <div class="tab-content" style="padding-bottom: 30px;">

                            <!-- message content starts here -->
                            <div class="tab-pane show active" id="tab-animated-0" role="tabpanel">
                                <div class="scroll-area-lg">
                                    <div class="scrollbar-container ps ps--active-y">
                                        <div class="p-3">
                                            <div class="vertical-without-time vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                                                <div class="vertical-timeline-item vertical-timeline-element">
                                                    <div>
                                                        @if($staff_performance->status >= 50)
                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                                    <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                                </span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title text-danger">{{$staff_performance->year}} 4<sup>th</sup> QUOTER ASSESSMENT</h4>

                                                                <p>- Performance Marks : <span class="text-danger">{{$staff_performance->fourth_quoter_spv_marks.'%'}}</span></p>
                                                                <p>-
                                                                    Supervisor's Comments <br>
                                                                    <span class="text-danger ml-2">{{$staff_performance->fourth_quoter_spv_comments}}</span>
                                                                </p>
                                                                <?php $assessor = \App\Staff::find($staff_performance->fourth_quoter_assessing_spv);?>
                                                                @if( isset($assessor->id))
                                                                    <p>- Done By : <span class="text-danger">{{ucwords($assessor->first_name.' '.$assessor->last_name)}}</span></p>
                                                                @endif

                                                                <?php $approver = \App\Staff::find($staff_performance->fourth_quoter_approved_by);?>
                                                                @if( isset($approver->id))
                                                                    <p>- Done By : <span class="text-danger">{{ucwords($approver->first_name.' '.$approver->last_name)}}</span></p>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="vertical-timeline-item vertical-timeline-element">
                                                    <div>
                                                        @if($staff_performance->status >= 40)
                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                                    <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                                </span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title text-danger">{{$staff_performance->year}} 3<sup>rd</sup> QUOTER ASSESSMENT</h4>

                                                                <p>- Performance Marks : <span class="text-danger">{{$staff_performance->third_quoter_spv_marks.'%'}}</span></p>
                                                                <p>-
                                                                    Supervisor's Comments <br>
                                                                    <span class="text-danger ml-2">{{$staff_performance->third_quoter_spv_comments}}</span>
                                                                </p>
                                                                <?php $assessor = \App\Staff::find($staff_performance->third_quoter_assessing_spv);?>
                                                                @if( isset($assessor->id))
                                                                    <p>- Done By : <span class="text-danger">{{ucwords($assessor->first_name.' '.$assessor->last_name)}}</span></p>
                                                                @endif

                                                                <?php $approver = \App\Staff::find($staff_performance->third_quoter_approved_by);?>
                                                                @if( isset($approver->id))
                                                                    <p>- Approved By : <span class="text-danger">{{ucwords($approver->first_name.' '.$approver->last_name)}}</span></p>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="vertical-timeline-item vertical-timeline-element">
                                                    <div>
                                                        @if($staff_performance->status >= 30)
                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                                <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                            </span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title text-danger">{{$staff_performance->year}} 2<sup>nd</sup> QUOTER ASSESSMENT</h4>

                                                                <p>- Performance Marks : <span class="text-danger">{{$staff_performance->second_quoter_spv_marks.'%'}}</span></p>
                                                                <p>-
                                                                    Supervisor's Comments <br>
                                                                    <span class="text-danger ml-2">{{$staff_performance->second_quoter_spv_comments}}</span>
                                                                </p>
                                                                <?php $assessor = \App\Staff::find($staff_performance->second_quoter_assessing_spv);?>
                                                                @if( isset($assessor->id))
                                                                    <p>- Done By : <span class="text-danger">{{ucwords($assessor->first_name.' '.$assessor->last_name)}}</span></p>
                                                                @endif

                                                                <?php $approver = \App\Staff::find($staff_performance->second_quoter_approved_by);?>
                                                                @if( isset($approver->id))
                                                                    <p>- Approved By : <span class="text-danger">{{ucwords($approver->first_name.' '.$approver->last_name)}}</span></p>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="vertical-timeline-item vertical-timeline-element">
                                                    <div>
                                                        @if($staff_performance->status >= 20)
                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                                <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                            </span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title text-danger">{{$staff_performance->year}} 1<sup>st</sup> QUOTER ASSESSMENT</h4>

                                                                <p>- Performance Marks : <span class="text-danger">{{$staff_performance->first_quoter_spv_marks.'%'}}</span></p>
                                                                <p>-
                                                                    Supervisor's Comments <br>
                                                                    <span class="text-danger ml-2">{{$staff_performance->first_quoter_spv_comments}}</span>
                                                                </p>
                                                                <?php $assessor = \App\Staff::find($staff_performance->first_quoter_assessing_spv);?>
                                                                @if( isset($assessor->id))
                                                                    <p>- Done By : <span class="text-danger">{{ucwords($assessor->first_name.' '.$assessor->last_name)}}</span></p>
                                                                @endif

                                                                <?php $approver = \App\Staff::find($staff_performance->first_quoter_approved_by);?>
                                                                @if( isset($approver->id))
                                                                    <p>- Approved By : <span class="text-danger">{{ucwords($approver->first_name.' '.$approver->last_name)}}</span></p>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="vertical-timeline-item vertical-timeline-element">
                                                    <div>
                                                        @if($staff_performance->status == 10)
                                                            <span class="vertical-timeline-element-icon bounce-in">
                                                                <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                            </span>
                                                            <div class="vertical-timeline-element-content bounce-in">
                                                                <h4 class="timeline-title text-danger">No assessment have been done yet for the year {{$staff_performance->year}} </h4>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- message content ends here -->

                            <!-- actions starts here -->
                            <?php

                                $disable_first_quoter = '';
                                $disable_second_quoter = '';
                                $disable_third_quoter = '';
                                $disable_fourth_quoter = '';

                                $first_quoter_visibility = '';
                                $second_quoter_visibility = '';
                                $third_quoter_visibility = '';
                                $fourth_quoter_visibility = '';

                                if( !in_array($staff_performance->first_quoter_spv_marks,[null,'']) ){
                                    $disable_first_quoter = 'disabled'; $first_quoter_visibility = 'invisible';
                                }

                                if( !in_array($staff_performance->second_quoter_spv_marks,[null,'']) ){
                                    $disable_second_quoter = 'disabled'; $second_quoter_visibility = 'invisible';
                                }

                                if( !in_array($staff_performance->third_quoter_spv_marks,[null,'']) ){
                                    $disable_third_quoter = 'disabled'; $third_quoter_visibility = 'invisible';
                                }

                                if( !in_array($staff_performance->fourth_quoter_spv_marks,[null,'']) ){
                                    $disable_fourth_quoter = 'disabled'; $fourth_quoter_visibility = 'invisible';
                                }

                                if( auth()->user()->staff->id != $staff_performance->staff->supervisor_id ){


                                        $disable_first_quoter = 'disabled'; $first_quoter_visibility = 'invisible';
                                        $disable_second_quoter = 'disabled'; $second_quoter_visibility = 'invisible';
                                        $disable_third_quoter = 'disabled'; $third_quoter_visibility = 'invisible';
                                        $disable_fourth_quoter = 'disabled'; $fourth_quoter_visibility = 'invisible';

                                }

                            ?>

                            @if( auth()->user()->staff->id == $staff_performance->staff->supervisor_id ||  in_array(auth()->user()->role_id,[1]) )
                                <div class="tab-pane show" id="tab-animated-1" role="tabpanel">
                                    <div class="">
                                        <div class="">
                                            <div class="row text-center">
                                                <div class="col-md-12 mt-3">
                                                    <button class="mb-2 mr-2 btn-pill btn btn-outline-primary switch" id="first_quoter_switch">1st Quoter</button>
                                                    <button class="mb-2 mr-2 btn-pill btn btn-outline-danger switch" id="second_quoter_switch">2nd Quoter</button>
                                                </div>
                                                <div class="col-md-12 mt-1">
                                                    <button class="mb-2 mr-2 btn-pill btn btn-outline-danger switch" id="third_quoter_switch">3rd Quoter</button>
                                                    <button class="mb-2 mr-2 btn-pill btn btn-outline-danger switch" id="fourth_quoter_switch">4th Quoter</button>
                                                </div>
                                            </div>
                                            <div class="p-3 assessment-div" id="first_quoter_assessment_div">
                                                <form action="/first_quoter_staff_performance_assessment" method="POST" enctype="multipart/form-data" id="assessment_form">
                                                    @csrf

                                                    <fieldset {{$disable_first_quoter}}>
                                                        <legend class="text-primary">First Quoter Assessment</legend>
                                                        <input name="staff_performance_id" value="{{$staff_performance->id}}" type="text" style="display: none;" readonly>
                                                        <div class="form-row mt-2">
                                                            <div class="col-md-12">
                                                                <div class="position-relative form-group">
                                                                    <label for="first_quoter_spv_marks" class="">
                                                                        Marks (In %)
                                                                        <span class="text-danger"></span>
                                                                    </label>
                                                                    <input name="first_quoter_spv_marks" id="first_quoter_spv_marks" class="form-control @error('first_quoter_spv_marks') is-invalid @enderror" value="{{ old('first_quoter_spv_marks') ?? $staff_performance->first_quoter_spv_marks}}" autocomplete="off">

                                                                    @error('first_quoter_spv_marks')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-row mt-2">
                                                            <div class="col-md-12">
                                                                <div class="position-relative form-group">
                                                                    <label for="first_quoter_spv_comments" class="">
                                                                        Comments <span class="text-danger"></span>
                                                                    </label>
                                                                    <textarea name="first_quoter_spv_comments" id="first_quoter_spv_comments" class="form-control @error('first_quoter_spv_comments') is-invalid @enderror" autocomplete="off">{{ old('first_quoter_spv_comments') ?? $staff_performance->first_quoter_spv_comments}}</textarea>

                                                                    @error('first_quoter_spv_comments')
                                                                    <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                 </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>


                                                    <button class="mt-2 btn btn-primary {{$first_quoter_visibility}}" id="approve_objectives_btn">Submit First Quoter Assessment</button>
                                                </form>
                                            </div>
                                            <div class="p-3 assessment-div" id="second_quoter_assessment_div" style="display: none;">
                                                <form action="/second_quoter_staff_performance_assessment" method="POST" enctype="multipart/form-data" id="return_objectives_form">
                                                    @csrf
                                                    {{ csrf_field() }}

                                                    <fieldset {{$disable_second_quoter}}>
                                                        <legend class="text-primary">Second Quoter Assessment</legend>
                                                        <input name="staff_performance_id" value="{{$staff_performance->id}}" type="text" style="display: none;" readonly>
                                                        <div class="form-row mt-2">
                                                            <div class="col-md-12">
                                                                <div class="position-relative form-group">
                                                                    <label for="second_quoter_spv_marks" class="">
                                                                        Marks (In %)
                                                                        <span class="text-danger"></span>
                                                                    </label>
                                                                    <input name="second_quoter_spv_marks" id="second_quoter_spv_marks" class="form-control @error('second_quoter_spv_marks') is-invalid @enderror" value="{{ old('second_quoter_spv_marks') ?? $staff_performance->second_quoter_spv_marks}}" autocomplete="off">

                                                                    @error('second_quoter_spv_marks')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-row mt-2">
                                                            <div class="col-md-12">
                                                                <div class="position-relative form-group">
                                                                    <label for="second_quoter_spv_comments" class="">
                                                                        Comments <span class="text-danger"></span>
                                                                    </label>
                                                                    <textarea name="second_quoter_spv_comments" id="second_quoter_spv_comments" class="form-control @error('second_quoter_spv_comments') is-invalid @enderror" autocomplete="off">{{ old('second_quoter_spv_comments') ?? $staff_performance->second_quoter_spv_comments}}</textarea>

                                                                    @error('second_quoter_spv_comments')
                                                                    <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                 </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>

                                                    <button class="mt-2 btn btn-primary {{$second_quoter_visibility}}" id="return_objectives_btn">Submit Second Quoter Assessment</button>
                                                </form>
                                            </div>
                                            <div class="p-3 assessment-div" id="third_quoter_assessment_div" style="display: none;">
                                                <form action="/third_quoter_staff_performance_assessment" method="POST" enctype="multipart/form-data" id="change_spv_form">
                                                    @csrf
                                                    {{ csrf_field() }}

                                                    <fieldset {{$disable_third_quoter}}>
                                                        <legend class="text-primary">Third Quoter Assessment</legend>
                                                        <input name="staff_performance_id" value="{{$staff_performance->id}}" type="text" style="display: none;" readonly>
                                                        <div class="form-row mt-2">
                                                            <div class="col-md-12">
                                                                <div class="position-relative form-group">
                                                                    <label for="third_quoter_spv_marks" class="">
                                                                        Marks (In %)
                                                                        <span class="text-danger"></span>
                                                                    </label>
                                                                    <input name="third_quoter_spv_marks" id="third_quoter_spv_marks" class="form-control @error('third_quoter_spv_marks') is-invalid @enderror" value="{{ old('third_quoter_spv_marks') ?? $staff_performance->third_quoter_spv_marks}}" autocomplete="off">

                                                                    @error('third_quoter_spv_marks')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-row mt-2">
                                                            <div class="col-md-12">
                                                                <div class="position-relative form-group">
                                                                    <label for="third_quoter_spv_comments" class="">
                                                                        Comments <span class="text-danger"></span>
                                                                    </label>
                                                                    <textarea name="third_quoter_spv_comments" id="third_quoter_spv_comments" class="form-control @error('third_quoter_spv_comments') is-invalid @enderror" autocomplete="off">{{ old('third_quoter_spv_comments') ?? $staff_performance->third_quoter_spv_comments}}</textarea>

                                                                    @error('third_quoter_spv_comments')
                                                                    <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                 </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>

                                                    <button class="mt-2 btn btn-primary {{$third_quoter_visibility}}" id="change_spv_btn">Submit Third Quoter Assessment</button>
                                                </form>
                                            </div>
                                            <div class="p-3 assessment-div" id="fourth_quoter_assessment_div" style="display: none;">
                                                <form action="/fourth_quoter_staff_performance_assessment" method="POST" enctype="multipart/form-data" id="reject_form">
                                                    @csrf
                                                    {{ csrf_field() }}

                                                    <fieldset {{$disable_fourth_quoter}}>
                                                        <legend class="text-danger">Fourth Quoter Assessment</legend>
                                                        <input name="staff_performance_id" value="{{$staff_performance->id}}" type="text" style="display: none;" readonly>
                                                        <div class="form-row mt-2">
                                                            <div class="col-md-12">
                                                                <div class="position-relative form-group">
                                                                    <label for="fourth_quoter_spv_marks" class="">
                                                                        Marks (In %)
                                                                        <span class="text-danger"></span>
                                                                    </label>
                                                                    <input name="fourth_quoter_spv_marks" id="fourth_quoter_spv_marks" class="form-control @error('fourth_quoter_spv_marks') is-invalid @enderror" value="{{ old('fourth_quoter_spv_marks') ?? $staff_performance->fourth_quoter_spv_marks}}" autocomplete="off">

                                                                    @error('fourth_quoter_spv_marks')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-row mt-2">
                                                            <div class="col-md-12">
                                                                <div class="position-relative form-group">
                                                                    <label for="fourth_quoter_spv_comments" class="">
                                                                        Comments <span class="text-danger"></span>
                                                                    </label>
                                                                    <textarea name="fourth_quoter_spv_comments" id="fourth_quoter_spv_comments" class="form-control @error('fourth_quoter_spv_comments') is-invalid @enderror" autocomplete="off">{{ old('fourth_quoter_spv_comments') ?? $staff_performance->fourth_quoter_spv_comments}}</textarea>

                                                                    @error('fourth_quoter_spv_comments')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset>

                                                    <button class="mt-2 btn btn-danger {{$fourth_quoter_visibility}}" id="reject_performance_objective_btn">Submit 4th Quoter Assessment</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        @endif
                        <!-- actions ends here-->

                        </div>
                    </div>
                </div>
            </div>
            <!-- End Of Message & Actions Section -->

            <!-- some useful values -->
            <div style="display: none;">
                <span id="staff_id">{{$staff_performance->staff_id}}</span>
                <span id="objectives_year">{{$staff_performance->year}}</span>
                <span id="objectives_month">{{$staff_performance->month}}</span>
                <span>{{ csrf_field() }}</span>
            </div>

        </div>
    </div>

    <script type="text/javascript">
        $(document).ready( function(){
            $('#notifications-div').delay(5000).fadeOut('slow');
        });

        $(".switch").on("click",function(){
            var switch_id = $(this).attr('id');
            var id_parts = switch_id.split('_');
            var selected_quoter = id_parts[0];
            var required_div_id = '#'+selected_quoter+'_quoter_assessment_div';


            $(".assessment-div").hide();
            $(required_div_id).show("slow");

        });
    </script>

    <!--scripts -->
    <script type="text/javascript">


        /*********************** Leave && Performance Objective Link Checking Script  ************************/

        $(function () {

            $("#performance_objective_section  :input").prop("readonly",true);

        });


        //when input in vacation, sick or other is clicked
        $("input").on('click',function(){

            var row = $(this).parent().parent();
            var row_type = row.attr('class');

            if( row_type == 'vacation-row bg-light-dark' ||
                row_type == 'sick-row' ||
                row_type == 'holiday-row bg-light-dark' ||
                row_type == 'other-row'){

                var input_hrs = $(this).val();

                if(input_hrs != ''){
                    var year = $('#objectives_year').text();
                    var month = $('#objectives_month').text();
                    var staff_id = $('#staff_id').text();
                    var _token = $('input[name="_token"]').val();

                    var date_info = $(this).attr('id');
                    date_info = date_info.split('--');
                    var selected_date = date_info[2];

                    var leave_date = selected_date + '-' + month + '-' + year;

                    //alert( leave_date );

                    //send ajax request
                    check_if_date_exist_in_staff_leaves(staff_id,year,leave_date,_token);
                }

            }

        });




        function check_if_date_exist_in_staff_leaves(staff_id,year,leave_date,_token)
        {


            $.ajax({
                url:"{{ route('leaves.ajax_check_date') }}",
                method:"POST",
                data:{staff_id:staff_id,year:year,leave_date:leave_date,_token:_token},
                success:function(data)
                {
                    //add the list to selection option
                    //console.log(data);
                    leave_id = JSON.parse(data);

                    if(leave_id>0){
                        //window.location = "leave/"+leave_id;
                        window.open("leave_admin/"+leave_id, '_blank');
                    }else{
                        sweet_alert_error("This Date was not found in any of Employee's Approved Leaves");
                    }

                }
            })


        }



        /******************** alerts ********************/

        function sweet_alert_success(success_message){
            Swal.fire({
                type: "success",
                text: success_message,
                confirmButtonColor: '#213368',
            })
        }


        function sweet_alert_error(error_message){
            Swal.fire({
                type: "error",
                text: error_message,
                confirmButtonColor: '#213368',
            })
        }

        function sweet_alert_warning(warning_message){
            Swal.fire({
                type: "warning",
                text: warning_message,
                confirmButtonColor: '#213368',
            })
        }


    </script>


@endsection
