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
                    <div class="text-primary">Submission Unlocking Request No. {{ $time_sheet_late_submission->id }}</div>
                    <div class="page-title-subheading">Below is information about time sheet late submission request</div>
                </div>
            </div>

        <!--actions' menu starts here -->
        <!--actions' menu ends here -->

        </div>
    </div>


    <!-- data 1 -->
    <div class="row">

        <div class="col-md-12 col-lg-6 col-xl-6">
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
                        Submission Status
                    </div>
                </div>
                <div class="p-0 card-body">
                    <div class="dropdown-menu-header mt-0 mb-0">
                        <div class="dropdown-menu-header-inner bg-heavy-rain">
                            <div class="menu-header-image opacity-1" style="background-image: url('assets/images/dropdown-header/city3.jpg');"></div>
                            <div class="menu-header-content text-dark">
                                <h2 class="menu-header-title">{{ucwords($time_sheet_late_submission->status)}}</h2>
                                <h6 class="menu-header-subtitle">
                                </h6>
                            </div>
                        </div>
                    </div>

                    <!-- actions title -->
                    <!-- end of actions title -->

                </div>
            </div>


            <div class="mb-3 card">
                <div class="card-header-tab card-header">
                    <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                        <i class="header-icon lnr-dice mr-3 text-muted opacity-6"> </i>
                        Submission Unlocking Request
                    </div>
                </div>
                <div class="card-body">
                    <div class="bootstrap-table">
                        <div class="fixed-table-toolbar"></div>

                        <div class="fixed-table-container" style="padding-bottom: 0px;">
                            <div class="fixed-table-body">
                                <div class="fixed-table-loading" style="top: 42px; display: none;">
                                    Loading, please wait...
                                </div>
                                <table data-toggle="table" data-url="https://api.github.com/users/wenzhixin/repos?type=owner&amp;sort=full_name&amp;direction=asc&amp;per_page=10&amp;page=1" data-sort-name="stargazers_count" data-sort-order="desc" class="table table-hover">
                                    <thead></thead>
                                    <tbody>
                                    <tr class="bg-light-dark">
                                        <th >Employee Name</th>
                                        <td>{{ucwords($staff->first_name.' '.$staff->last_name)}}</td>
                                    </tr>
                                    <tr class="bg-light-dark">
                                        <th>Time Sheet Year</th>
                                        <td>{{$time_sheet_late_submission->year}}</td>
                                    </tr >
                                    <tr class="bg-light-dark">
                                        <th>Time Sheet Month</th>
                                        <td>{{$months[$time_sheet_late_submission->month]}}</td>
                                    </tr>
                                    <tr >
                                        <th>Reason Of Delaying</th>
                                        <td>{{$time_sheet_late_submission->reason}}</td>
                                    </tr>
                                    <tr >
                                        <th>Employee Title</th>
                                        <td>{{$time_sheet_late_submission->staff->jobTitle->title}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
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
