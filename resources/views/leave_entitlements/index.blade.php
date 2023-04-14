@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-primary">List Of Leave Entitlements For All Employees For The Year {{date('Y')}} </div>
                    <div class="page-title-subheading">
                        Below is a list entitlements
                    </div>
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

                            @if($view_type == 'index')
                                @include('actions.add')

                                @if($carry_over_mode == 2)
                                    @can('view-action',[$controller_name,'edit'])
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{url('/perform_carry_over/all')}}">
                                                <i class="nav-link-icon pe-7s-next"></i>
                                                <span>Perform Carry Over For All</span>
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
            <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>Employee No.</th>
                    <th>Employee Name</th>
                    <th>Year</th>
                </tr>
                </thead>
                <tbody>

                <?php $n=1; ?>
                @foreach($leave_entitlements as $leave_entitlement)
                <tr class='clickable-row' data-href="/leave_entitlements/{{ $leave_entitlement->id }}">
                    <td>{{ $n }}</td>
                    <td>{{ $leave_entitlement->staff->staff_no }}</td>
                    <td>{{ ucwords($leave_entitlement->staff->first_name.' '.$leave_entitlement->staff->last_name) }}</td>
                    <td>{{ $leave_entitlement->year }}</td>
                </tr>
                <?php $n++; ?>
                @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <th>No.</th>
                    <th>Employee No.</th>
                    <th>Employee Name</th>
                    <th>Year</th>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <script type="text/javascript">

        $(document).ready( function(){
            $('#notifications-div').delay(10000).fadeOut('slow');
        });

    </script>

@endsection
