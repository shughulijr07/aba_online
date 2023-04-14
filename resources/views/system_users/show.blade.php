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
                    <div><span class="text-primary">{{ $system_user->name }}</span> Information</div>
                    <div class="page-title-subheading">Below are information of the user</div>
                </div>
            </div>

            <!--actions' menu starts here -->
            <div class="page-title-actions">
                <button type="button" data-toggle="tooltip" title="Print" data-placement="bottom" class="btn-shadow mr-3 btn btn-info invisible">
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
                            @include('actions.edit')
                            @include('actions.add')
                            @include('actions.list')
                            @include('actions.delete')

                            @if( in_array(Auth::user()->role_id,[1,2]) && isset($system_user->user) )
                                <li class="nav-item">
                                    <a class="nav-link" href="{{url('/admin_reset_password/'.$system_user->user->id)}}">
                                        <i class="nav-link-icon fas fa-key"></i>
                                        <span>Reset User Password</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <!--actions' menu ends here -->

        </div>
    </div>

    <!-- return errors if there are any -->
    @if( Session::has('message'))
        <div class="row ml-1 mr-1 mt-3" id="notifications-div">
            <div class="col-md-12 pl-3 alert alert-danger">{{Session::get('message')}}</div>
        </div>
    @endif
    <!-- end of errors -->

    <div class="row">

        <div class="col-md-12">
            <div class="mb-3 card">
                <div class="card-header-tab card-header">
                    <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                        <i class="header-icon lnr-dice mr-3 text-muted opacity-6"> </i>
                        User Information
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="widget-chart   card-btm-border  border-primary">
                                <img src="@if($system_user->image != '') /storage/{{$system_user->image}} @else /images/staff-image.png @endif" alt="User Image" style="width: 100%; height: auto; " id="system_user-image">
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="bootstrap-table mt-3">
                                <div class="fixed-table-toolbar"></div>

                                <div class="fixed-table-container" style="padding-bottom: 0px;">
                                    <div class="fixed-table-body">
                                        <div class="bootstrap-table">
                                            <div class="fixed-table-toolbar"></div>

                                            <div class="fixed-table-container">
                                                <div class="fixed-table-header"><table></table></div>
                                                <div class="fixed-table-body">
                                                    <div class="fixed-table-loading">
                                                        Loading, please wait...
                                                    </div>
                                                    <table data-toggle="table" data-url="#" data-sort-name="stargazers_count" data-sort-order="desc" class="table table-hover">
                                                        <thead></thead>
                                                        <tbody>
                                                        <tr><th>Name</th><td>{{$system_user->name}}</td></tr>
                                                        <tr><th>Gender</th><td>{{$system_user->gender}}</td></tr>
                                                        <tr><th>Phone</th><td>{{$system_user->phone_no}}</td></tr>
                                                        <tr><th>Email</th><td>{{$system_user->email}}</td></tr>
                                                        <tr><th>Company</th><td>{{$system_user->company}}</td></tr>
                                                        <tr><th>Description</th><td>{{$system_user->description}}</td></tr>
                                                        <tr><th>System Role</th><td>{{ $system_user->user ? ucwords(strtolower(str_replace("-"," ",$system_user->user->system_role->role_name))) : "" }}</td></tr>

                                                        @if( isset($autogenerated_password) && in_array(Auth::user()->role_id,[1,2]))
                                                            <tr><th>Autogenerated Password</th><td>{{$autogenerated_password}}</td></tr>
                                                        @endif

                                                        <tr><th>Status</th><td>{{$system_user->status}}</td></tr>
                                                        </tbody>
                                                    </table>

                                                </div>
                                                <div class="fixed-table-footer"><table><tbody><tr></tr></tbody></table></div>
                                            </div>
                                            <div class="fixed-table-pagination"></div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <!-- Script for location fields -->
    <script type="text/javascript">
        $(function(){
            $('#notifications-div').delay(10000).fadeOut('slow');
        });
    </script>

@endsection
