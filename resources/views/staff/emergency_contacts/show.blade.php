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
                    <div class="text-primary">{{ ucwords($staff_emergency_contact->full_name) }} Information</div>
                    <div class="page-title-subheading">Below are information of the Staff Emergency Contact</div>
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
                <img src="@if($staff_emergency_contact->image != '') /storage/{{$staff_emergency_contact->image}} @else /images/staff-image.png @endif" alt="Staff Image" style="width: 100%; height: auto; " id="staff-image">
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
                                    <div class="widget-subheading">{{ ucwords($staff_emergency_contact->full_name) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="widget-content">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Gender</div>
                                    <div class="widget-subheading">{{$staff_emergency_contact->gender}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="widget-content">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Relationship</div>
                                    <div class="widget-subheading">{{$staff_emergency_contact->relationship}}</div>
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
                                    <div class="widget-heading">Physical Address</div>
                                    <div class="widget-subheading">{{$staff_emergency_contact->physical_address}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="widget-content">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Email</div>
                                    <div class="widget-subheading">{{$staff_emergency_contact->email}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="widget-content">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">City</div>
                                    <div class="widget-subheading">{{$staff_emergency_contact->city}}</div>
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
                                    <div class="widget-heading">Cell Phone</div>
                                    <div class="widget-subheading">{{$staff_emergency_contact->cell_phone}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="widget-content">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Home Phone</div>
                                    <div class="widget-subheading">{{$staff_emergency_contact->home_phone}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="widget-content">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Business Phone</div>
                                    <div class="widget-subheading">{{$staff_emergency_contact->business_phone}}</div>
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
                                    <div class="widget-heading">Staff Name</div>
                                    <div class="widget-subheading">{{ ucwords($staff_emergency_contact->staff->first_name.' '.$staff_emergency_contact->staff->last_name) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="widget-content">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Staff No.</div>
                                    <div class="widget-subheading">{{ $staff_emergency_contact->staff->no }}</div>
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
