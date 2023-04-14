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
                    <div class="text-primary">{{ $leave_entitlement->staff->first_name.' '.$leave_entitlement->staff->last_name}} Leave Entitlements For {{$leave_entitlement->year}}</div>
                    <div class="page-title-subheading">Below is the summary of entitlements</div>
                </div>
            </div>

            <!--actions' menu starts here -->
            <div class="page-title-actions">
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

                            @if($view_type == 'show')
                                @include('actions.edit')
                                @include('actions.add')
                                @include('actions.list')
                                @include('actions.delete')

                                @if($carry_over_mode == 2)
                                    @can('view-action',[$controller_name,'edit'])
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{url('/perform_carry_over/'.$leave_entitlement->staff->id)}}">
                                                <i class="nav-link-icon pe-7s-next"></i>
                                                <span>Perform Carry Over</span>
                                            </a>
                                        </li>
                                    @endcan
                                @endif

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

        <div class="col-sm-12"  id="suggestion-div">

            @if(session()->has('message'))
                <div  class="mb-3 card alert alert-primary" id="notifications-div">
                    <div class="p-3 card-body ">
                        <div class="text-center">
                            <h5 class="" id="message">{{session()->get('message')}}</h5>
                        </div>
                    </div>
                </div>
            @endif

            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title text-danger">{{$leave_entitlement->year}} Leave Entitlement</h5>
                    <p class="text-danger" id="selected-fee"></p>
                    <table style="width: 100%;" id="suggestion-table" class="table table-hover table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>Type of Leave</th>
                            <th>Standard Days</th>
                            <th>Additional Days</th>
                            <th>Carry Over</th>
                            <th>Total Days</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($leave_entitlement->lines as $line)
                        <tr>
                            <td>{{ $leave_types[$line->type_of_leave]['name'] }}</td>
                            <td>{{ $leave_types[$line->type_of_leave]['days'] }}</td>
                            <td>
                                <?php
                                    $total_extended_days = 0;
                                    foreach ($line->extensions as $extension){
                                        $total_extended_days += $extension->no_days;
                                    }
                                ?>
                                {{ $total_extended_days}}
                            </td>
                            <td>
                                <?php
                                $total_carry_overs = 0;
                                if ( isset($line->carry_over->no_days) ){
                                    $total_carry_overs = $line->carry_over->no_days;
                                }
                                ?>
                                {{ $total_carry_overs}}
                            </td>
                            <td>{{$line->number_of_days}}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
      
    </div>

    <script type="text/javascript">

        $(document).ready( function(){
            $('#notifications-div').delay(10000).fadeOut('slow');
        });

    </script>

@endsection
