@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-plus"></i>
                </div>
                <div>
                    @if( $time_sheet->status == '0')
                        <div class="text-primary">Time Sheet Correction Form</div>
                        <div class="page-title-subheading">
                            Please correct information in the timesheet as instructed the submit it again
                        </div>
                    @elseif( $time_sheet->status == '10')
                        <div class="text-primary">Time Sheet Draft For The Month Of {{$months[$time_sheet->month]}}</div>
                        <div class="page-title-subheading">
                            After filling the Time Sheet you can save it to drafts again or submit it.
                        </div>
                    @endif
                </div>
            </div>

            <!--actions' menu starts here -->
            <!--actions' menu ends here -->

        </div>
    </div>
    <div class="tab-content">
        <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">

            <div class="container" style="min-width: 90%">
                <div class="row">

                    <!-- Time Sheet Section -->
                    <div class="col-md-12 col-lg-9 col-xl-9">
                        <form action="/create_time_sheet_entries" method="POST" enctype="multipart/form-data" id="timesheet_form">
                            @csrf
                            {{ csrf_field() }}

                            @include('time_sheets.time_sheet_form')
                        </form>
                    </div>
                    <!-- End Of Time Sheet Section -->

                    <!-- Message Section -->
                    <div class="col-md-12 col-lg-3 col-xl-3">
                        @if(session()->has('message'))
                            <div  class="mb-3 card alert alert-primary" id="notifications-div">
                                <div class="p-3 card-body ">
                                    <div class="text-center">
                                        <h5 class="text-white" id="message">{{session()->get('message')}}</h5>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="mb-3 card">
                            <div class="card-header-tab card-header">
                                <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                                    <i class="header-icon pe-7s-menu icon-gradient bg-happy-itmeo"> </i>
                                    Time Sheet Status
                                </div>
                            </div>
                            <div class="p-0 card-body">
                                <div class="dropdown-menu-header mt-0 mb-0">
                                    <div class="dropdown-menu-header-inner bg-heavy-rain">
                                        <div class="menu-header-image opacity-1" style="background-image: url('assets/images/dropdown-header/city3.jpg');"></div>
                                        <div class="menu-header-content text-dark">
                                            <h5 class="menu-header-title">{{$time_sheet_statuses[$time_sheet->status]}}</h5>
                                            <h6 class="menu-header-subtitle"></h6>
                                        </div>
                                    </div>
                                </div>
                                <ul class="tabs-animated-shadow tabs-animated nav nav-justified tabs-shadow-bordered p-3">
                                    <li class="nav-item">
                                        <a role="tab" class="nav-link show active" id="tab-c-0" data-toggle="tab" href="#tab-animated-0" aria-selected="true">
                                            <span>Message</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a role="tab" class="nav-link show" id="tab-c-2" data-toggle="tab" href="#tab-animated-2" aria-selected="true">
                                            <span>Summary</span>
                                        </a>
                                    </li>
                                </ul>

        <div class="tab-content" style="padding-bottom: 30px;">
            <!-- message content starts here -->
            <div class="tab-pane show active" id="tab-animated-0" role="tabpanel">
                <div class="">
                    <div class="">
                        <div class="p-3">
                            <div class="vertical-without-time vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                                <div class="vertical-timeline-item vertical-timeline-element">
                                    <div>
                                        @if($time_sheet->status == '0')
                                            <span class="vertical-timeline-element-icon bounce-in">
                                                    <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                </span>
                                            <div class="vertical-timeline-element-content bounce-in">
                                                <h4 class="timeline-title text-danger">TIME SHEET RETURNED FOR CORRECTIONS</h4>
                                                <p>
                                                    Hello, your time sheet have been returned for corrections by
                                                    @if($time_sheet->returns->last()->level == 'spv') Supervisor
                                                    @elseif($time_sheet->returns->last()->level == 'hrm') Human Resource Manager
                                                    @elseif($time_sheet->returns->last()->level == 'md')  Managing Director
                                                    @else Administrator
                                                    @endif
                                                    .<br><br>
                                                    - <span class="text-danger">Reason & Correction Instruction</span> <br>
                                                    <span class="ml-2">{{$time_sheet->returns->last()->comments}}</span> <br><br>
                                                    - <span class="text-danger">Time of Returning</span> <br>
                                                    <span class="ml-2">{{date("d-m-Y H:i:s", strtotime($time_sheet->updated_at))}}</span>
                                                </p>

                                                <p>
                                                    <br>
                                                    - <span class="text-danger">For Assistance  </span><br>
                                                    <span class="ml-2">
                                                            Please contact System Administrator or Human Resource Manager
                                                        </span>
                                                </p>
                                            </div>
                                        @endif
                                        @if($time_sheet->status == '10')
                                            <span class="vertical-timeline-element-icon bounce-in">
                                                    <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                                </span>
                                            <div class="vertical-timeline-element-content bounce-in">
                                                <h4 class="timeline-title text-danger">TIME SHEET STILL IN DRAFTS</h4>
                                                <p>
                                                    This Time Sheet is not yet submitted, it is still saved in Drafts.<br><br>
                                                    - <span class="text-danger">Last changes were made on  </span><br>
                                                    <span class="ml-2">
                                                            {{date("d-m-Y H:i:s", strtotime($time_sheet->updated_at))}}
                                                        </span>
                                                </p>

                                                <p>
                                                    <br>
                                                    - <span class="text-danger">For Assistance  </span><br>
                                                    <span class="ml-2">
                                                            Please contact System Administrator or Human Resource Manager
                                                        </span>
                                                </p>
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

            <!-- summary content starts here -->
            <div class="tab-pane show" id="tab-animated-2" role="tabpanel">
                <div class="">
                    <div class="">
                        <div class="p-3">
                            <div class="vertical-without-time vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                                <div class="vertical-timeline-item vertical-timeline-element invisible">
                                    <div>
                                <span class="vertical-timeline-element-icon bounce-in">
                                    <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                </span>
                                        <div class="vertical-timeline-element-content bounce-in">
                                            <h4 class="timeline-title text-danger">TIME SHEET SUMMARY</h4>
                                            <p>
                                                <br>
                                                <span class="text-danger">For Assistance</span><br>
                                                - Please contact system Administrator or Human Resource Manager
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="vertical-timeline-item vertical-timeline-element">
                                    <div>
                                <span class="vertical-timeline-element-icon bounce-in">
                                    <i class="badge badge-dot badge-dot-xl badge-danger"> </i>
                                </span>
                                        <div class="vertical-timeline-element-content bounce-in">
                                            <h4 class="timeline-title text-danger">LOE For Clients</h4>
                                            <table class="table table-sm table-bordered">
                                                <thead>
                                                <tr class="bg-primary text-white">
                                                    <th class="">Client</th>
                                                    <th class="text-right">Hrs</th>
                                                    <th class="text-right"> % </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php $n = 1; ?>
                                                {{-- @foreach($projects as $project_number=>$project_name)
                                                    <tr>
                                                        <td >{{$project_name}}</td>
                                                        <td class="no-wrap text-right" id="hrs--project--{{$project_number}}">0</td>
                                                        <td class="no-wrap text-right text-danger" id="percentage--project--{{$project_number}}">0.0</td>
                                                    </tr>
                                                @endforeach --}}

                                                @foreach($projects as $client_data2)
                                                    <tr>
                                                        <td >{{$client_data2->name}}</td>
                                                        <td class="no-wrap text-right" id="hrs--project--{{$client_data2->number}}">0</td>
                                                        <td class="no-wrap text-right text-danger" id="percentage--project--{{$client_data2->number}}">0.0</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- summary content ends here -->
        </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Of Message Section -->

                </div>
            </div>
        </div>
    </div>

@endsection