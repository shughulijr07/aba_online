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
                    <div class="text-primary">{{ $staff->first_name.' '.$staff->last_name }} Information</div>
                    <div class="page-title-subheading">Below are information of the staff</div>
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


                        <!-- Staff Page Custom Actions --->
                        @if( $view_type == 'show' )
                            @can('view-action',[$controller_name,'edit'])
                                <li class="nav-item">
                                    <a class="nav-link" href="{{url('/reset_staff_password/'.$staff->id)}}">
                                        <i class="nav-link-icon lnr-pencil"></i>
                                        <span>Reset Password</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    @if( isset($staff->staff_biographical_data_sheet))
                                        <a class="nav-link" href="{{url('/staff_biographical_data_sheets/'.$staff->staff_biographical_data_sheet->id)}}">
                                            <i class="nav-link-icon pe-7s-note2"></i>
                                            <span>View Bio Data</span>
                                        </a>
                                        <a class="nav-link" href="{{url('/staff_biographical_data_sheets/'.$staff->staff_biographical_data_sheet->id.'/edit')}}">
                                            <i class="nav-link-icon pe-7s-note2"></i>
                                            <span>Edit Bio Data</span>
                                        </a>
                                    @else
                                        <a class="nav-link" href="{{url('/create_staff_biographical_data_sheets/'.$staff->id)}}">
                                            <i class="nav-link-icon pe-7s-note2"></i>
                                            <span>Create Staff Bio Data</span>
                                        </a>
                                    @endif
                                </li>
                            @endcan
                        @endif
                        <!-- End Of Custom Staff Actions  -->

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
                <img src="@if($staff->image != '') /storage/{{$staff->image}} @else /images/staff-image.png @endif" alt="Staff Image" style="width: 100%; height: auto; " id="staff-image">
            </div>
            @if( in_array( auth()->user()->role_id,[1,3]))
            <div class="widget-chart   card-btm-border card-border card-shadow-primary border-danger card mt-2" style="max-height: 100px; width:auto; ">
                <img src="@if($staff->signature != '') /storage/{{$staff->signature}} @else /images/staff-signature.png @endif" alt="Staff Signature" style="width: auto; height: 70px; " id="staff-signature">
            </div>
            @endif
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
                                    <div class="widget-heading">Staff Number</div>
                                    <div class="widget-subheading">{{$staff->staff_no}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="widget-content">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">First Name</div>
                                    <div class="widget-subheading">{{$staff->first_name}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="widget-content">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Middle Name</div>
                                    <div class="widget-subheading">{{$staff->middle_name}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="widget-content">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Last Name</div>
                                    <div class="widget-subheading">{{$staff->last_name}}</div>
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
                                    <div class="widget-heading">Gender</div>
                                    <div class="widget-subheading">{{$staff->gender}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="widget-content">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Title</div>
                                    <div class="widget-subheading">@if(isset($staff->jobTitle->title)) {{$staff->jobTitle->title}} @endif</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="widget-content">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Department</div>
                                    <div class="widget-subheading">@if(isset($staff->department->name)) {{$staff->department->name}} @endif</div>
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
                                    <div class="widget-heading">Phone Number</div>
                                    <div class="widget-subheading">{{$staff->phone_no}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="widget-content">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Official Email</div>
                                    <div class="widget-subheading">{{$staff->official_email}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="widget-content">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Personal Email</div>
                                    <div class="widget-subheading">{{$staff->personal_email}}</div>
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
                                    <div class="widget-heading">Place Of Birth</div>
                                    <div class="widget-subheading">{{$staff->place_of_birth}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="widget-content">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Date Of Birth</div>
                                    <div class="widget-subheading">{{$staff->date_of_birth}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="widget-content">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Home Address</div>
                                    <div class="widget-subheading">{{$staff->home_address}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="widget-content">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Duty Station</div>
                                    <div class="widget-subheading">{{$staff->duty_station}}</div>
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
                                    <div class="widget-heading">Access To MMS</div>
                                    <div class="widget-subheading">{{$mms_access}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="widget-content">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Role In MMS</div>
                                    <div class="widget-subheading">{{$role_name}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="widget-content">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Supervisor</div>
                                    <div class="widget-subheading">
                                        <?php
                                        if($staff->supervisor_id == null){
                                            echo '';
                                        }else{
                                            $supervisor = \App\Models\Staff::find($staff->supervisor_id);
                                            echo ucwords($supervisor->first_name.' '.$supervisor->last_name);
                                        }
                                        ?>
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
                                    <div class="widget-heading">Date Of Employment</div>
                                    <div class="widget-subheading">{{$staff->date_of_employment}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="widget-content">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Status</div>
                                    <div class="widget-subheading">{{$staff->staff_status}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if( in_array($staff->staff_status,['Resigned','Retired','Suspended','Terminated']) && isset($staff_status_change))

                <?php
                    $date_title = $description_title ='';
                    switch ($staff->staff_status){
                        case 'On Contract Renew' : $date_title = ''; $description_title = ''; break;
                        case 'Resigned' : $date_title = 'Date Of Resigning'; $description_title = 'Reason For Resigning'; break;
                        case 'Retired' : $date_title = 'Date Of Retiring'; $description_title = 'Reason For Retiring'; break;
                        case 'Suspended' : $date_title = 'Date Of Suspension'; $description_title = 'Suspension Reason'; break;
                        case 'Terminated' : $date_title = 'Date Of Termination'; $description_title = 'Termination Reason'; break;
                        default : $date_title = ''; $description_title = ''; break;
                    }
                ?>
                <div class="main-card mb-1 card">
                    <div class="no-gutters row">
                        <div class="col-md-3">
                            <div class="widget-content">
                                <div class="widget-content-wrapper">
                                    <div class="widget-content-left">
                                        <div class="widget-heading">{{$date_title}}</div>
                                        <div class="widget-subheading">{{$staff_status_change->date}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="widget-content">
                                <div class="widget-content-wrapper">
                                    <div class="widget-content-left">
                                        <div class="widget-heading">{{$description_title}}</div>
                                        <div class="widget-subheading">{{$staff_status_change->description}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="widget-content">
                                <div class="widget-content-wrapper">
                                    <div class="widget-content-left">
                                        <div class="widget-heading">Attached Documents</div>
                                        <div class="widget-subheading">
                                            <?php
                                                $attachments = [];
                                                if(isset($staff_status_change->attachments)){
                                                    $attachments = json_decode($staff_status_change->attachments);
                                                }
                                            ?>

                                            @if( count($attachments) > 0)
                                                <?php $n=1;?>
                                                @foreach($attachments as $attachment)
                                                    <span class="mr-2">
                                                            <a href="{{url($attachment)}}" target="_blank" class="mr-3">
                                                                <i class="pe-7s-exapnd2"></i> Attachment {{$n}}
                                                            </a>
                                                        </span>
                                                    <?php $n++;?>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>


    <div class="row mt-4">

        <!-- Dependents-->
        <div class="col-md-12">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title">Dependents List</h5>
                    <table style="width: 100%;" id="example1" class="table table-hover table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name Of Dependent</th>
                            <th>Date Of Birth</th>
                            <th>Sex</th>
                            <th>Relationship</th>
                            <th>Birth Certificate No.</th>
                            <th>To be on medical</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php $n=1; ?>
                        @foreach($staff->dependents as $dependent)
                            <tr class='clickable-row' data-href="/staff_dependents/{{ $dependent->id }}">
                                <td>{{ $n }}</td>
                                <td>{{ $dependent->full_name }}</td>
                                <td>{{ date('d-m-Y',strtotime($dependent->date_of_birth)) }}</td>
                                <td>{{ $dependent->gender }}</td>
                                <td>{{ $dependent->relationship}}</td>
                                <td>{{ $dependent->birth_certificate_no }}</td>
                                <td>{{ $dependent->to_be_on_medical }}</td>
                            </tr>
                            <?php $n++; ?>
                        @endforeach

                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Emergency Contacts-->
        <div class="col-md-12">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title">Emergency Contacts List</h5>
                    <table style="width: 100%;" id="example2" class="table table-hover table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Relationship</th>
                            <th>Physical Address</th>
                            <th>Email</th>
                            <th>Phone</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php $n=1; ?>
                        @foreach($staff->emergency_contacts as $emergency_contact)
                            <tr class='clickable-row' data-href="/staff_emergency_contacts/{{ $emergency_contact->id }}">
                                <td>{{ $n }}</td>
                                <td>{{ $emergency_contact->full_name }}</td>
                                <td>{{ $emergency_contact->relationship}}</td>
                                <td>{{ $emergency_contact->physical_address }}</td>
                                <td>{{ $emergency_contact->email }}</td>
                                <td>{{ $emergency_contact->cell_phone }}</td>
                            </tr>
                            <?php $n++; ?>
                        @endforeach

                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
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
