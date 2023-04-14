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
                    <div class="text-primary">{{ ucwords($staff_dependent->full_name) }} Information</div>
                    <div class="page-title-subheading">Below are information of the Staff Dependent</div>
                </div>
            </div>

            <!--actions' menu starts here --><div class="page-title-actions">
                <button type="button" data-toggle="tooltip" title="Print" data-placement="bottom" class="btn-shadow mr-3 btn btn-info">
                    <i class="lnr-printer"></i>
                </button>
                <div class="d-inline-block dropdown">
                    <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-shadow dropdown-toggle btn btn-primary">
                        <span class="btn-icon-wrapper pr-2 opacity-7">
                            <i class="fa fa-tasks" aria-hidden="true"></i>
                        </span>
                        Actions
                    </button>
                    <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right">
                        <ul class="nav flex-column">

                        @if($view_type == 'index')
                            @include('actions.add')
                        @endif

                        @if($view_type == 'create')
                            @include('actions.list')
                        @endif

                        @if($view_type == 'edit')
                            @include('actions.add')
                            @include('actions.list')
                            @include('actions.delete')
                        @endif

                        @if($view_type == 'show')
                            @include('actions.edit')
                            @include('actions.add')
                            @include('actions.list')
                            @include('actions.delete')
                        @endif

                        </ul>
                    </div>
                </div>
            </div>

            <!--actions' menu ends here -->

        </div>
    </div>


    <!-- data 1 -->
    <div class="row">
        <div class="col-md-3">
            <div class="widget-chart   card-btm-border card-border card-shadow-primary border-danger card">
                <img src="@if($staff_dependent->image != '') /storage/{{$staff_dependent->image}} @else /images/staff-image.png @endif" alt="Staff Image" style="width: 100%; height: auto; " id="staff-image">
            </div>
        </div>

        <div class="col-md-9">

            @if(session()->has('message'))
            <div class="main-card mb-1 card alert  alert-primary" id="notifications-div">
                <div class="no-gutters row text-center">
                    <div class="col-md-12">
                        <div class="widget-content">
                            <div class="widget-content-wrapper text-center">
                                <h5 class="" id="message">{{session()->get('message')}}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="main-card mb-1 card">
                <div class="no-gutters row">
                    <div class="col-md-3">
                        <div class="widget-content">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Full Name</div>
                                    <div class="widget-subheading">{{ ucwords($staff_dependent->full_name) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="widget-content">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Gender</div>
                                    <div class="widget-subheading">{{$staff_dependent->gender}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="main-card mb-1 card">
                <div class="no-gutters row">
                    <div class="col-md-3">
                        <div class="widget-content">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Date Of Birth</div>
                                    <div class="widget-subheading">{{ date('d-m-Y',strtotime($staff_dependent->date_of_birth)) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="widget-content">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Relationship</div>
                                    <div class="widget-subheading">{{$staff_dependent->relationship}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="main-card mb-1 card">
                <div class="no-gutters row">
                    <div class="col-md-3">
                        <div class="widget-content">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Birth Certificate No.</div>
                                    <div class="widget-subheading">{{$staff_dependent->birth_certificate_no}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="widget-content">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">To Be On Medical</div>
                                    <div class="widget-subheading">{{$staff_dependent->to_be_on_medical}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="widget-content">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Birth/Marriage Certificate</div>
                                    <div class="widget-subheading">
                                        @if( isset($staff_dependent->certificate) )
                                            <a href="{{url($staff_dependent->certificate)}}" target="_blank" class="mr-3">
                                                <i class="pe-7s-exapnd2"></i> View Certificate
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="main-card mb-1 card">
                <div class="no-gutters row">
                    <div class="col-md-3">
                        <div class="widget-content">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Dependent To</div>
                                    <div class="widget-subheading">{{ ucwords($staff_dependent->staff->first_name.' '.$staff_dependent->staff->last_name) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="widget-content">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Dependent To No.</div>
                                    <div class="widget-subheading">{{ $staff_dependent->staff->no }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      
    </div>




    <script type="text/javascript">
        $(document).ready( function(){
            $('#notifications-div').delay(5000).fadeOut('slow');
        });
    </script>

@endsection
