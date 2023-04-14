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
                    <div class="text-primary">Client Information</div>
                    <div class="page-title-subheading">Below is information of a client</div>
                </div>
            </div>

            <!--actions' menu starts here -->
            @include('layouts.administrator.page-title-actions')
            <!--actions' menu ends here -->

        </div>
    </div>


    <!-- data 1 -->
    <div class="row">
        <div class="col-md-4 col-lg-8">
            <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-primary border-danger card">
                <div class="widget-chat-wrapper-outer">
                    <div class="widget-chart-content">
                        <div class="widget-title opacity-5 text-uppercase">Client Name</div>
                        <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                            <div class="widget-chart-flex align-items-center">
                                <div>
                                    {{$project->name}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-4">
            <div class="widget-chart widget-chart2 text-left mb-3 card-btm-border card-shadow-danger border-primary card">
                <div class="widget-chat-wrapper-outer">
                    <div class="widget-chart-content">
                        <div class="widget-title opacity-5 text-uppercase">Client Number</div>
                        <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                            <div class="widget-chart-flex align-items-center">
                                <div>
                                    {{$project->number}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="main-card mb-3 card">
        <div class="card-header-tab card-header">
            <div class="card-header-title font-size-lg text-capitalize font-weight-bold">
                <i class="header-icon lnr-briefcase mr-3 text-primary opacity-6"> </i>
                <span class="text-primary">Clients Activities</span>
            </div>
        </div>
        <div class="card-body">
            <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>Activity Code</th>
                    <th>Activity Name</th>
                </tr>
                </thead>
                <tbody>

                <?php $n = 1; ?>
                @foreach($project->activities as $activity)
                    <tr class='clickable-row' data-href="/activities/{{ $activity->id }}">
                        <td>{{ $n }}</td>
                        <td>{{ $activity->code }}</td>
                        <td>{{ $activity->name }}</td>
                    </tr>
                    <?php $n++; ?>
                @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <th>No.</th>
                    <th>Activity Code</th>
                    <th>Activity Name</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

@endsection
