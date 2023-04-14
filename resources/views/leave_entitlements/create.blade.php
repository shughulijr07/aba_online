@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-plus"></i>
                </div>
                <div>
                    <div class="text-primary">Create New Leave Entitlement For Employee</div>
                    <div class="page-title-subheading">
                        Create entitlement by filling the form below
                    </div>
                </div>
            </div>

            <!--actions' menu starts here -->
            @include('layouts.administrator.page-title-actions')
            <!--actions' menu ends here -->
    
        </div>
    </div>
    <div class="tab-content">
        <div class="tab-pane tabs-animation fade show active" id="tab-content-0" role="tabpanel">
            <div class="row">
                <div class="col-md-7">

                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <h5 class="card-title">Entitlement Form</h5>
                            <form action="/leave_entitlements" method="POST" enctype="multipart/form-data">
                                @csrf
                                @include('leave_entitlements.form')
                                <button class="mt-2 btn btn-primary">Create Entitlement</button>
                            </form>
                        </div>
                    </div>

                </div>

                <div class="col-sm-5"  id="suggestion-div">
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <h5 class="card-title text-danger">Leave Entitlement Suggestion</h5>
                            <table style="width: 100%;" id="suggestion-table" class="table table-hover table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>Type of Leave</th>
                                    <th class="text-center">Allocated Days</th>
                                    <th class="text-center">Period (In Years)</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach( $leave_types as $leave_type)
                                <tr class='data-row'>
                                    <td>{{$leave_type->name}}</td>
                                    <td class="text-center">{{$leave_type->days}}</td>
                                    <td class="text-center">{{$leave_type->period}}</td>
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

@endsection