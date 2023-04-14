@extends('layouts.administrator.admin')


@section('content')

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon text-primary">
                    <i class="fas fa-list-ul"></i>
                </div>
                <div>
                    <div class="text-primary">List Of All Staff With Their Supervisors</div>
                    <div class="page-title-subheading">
                        To update staff's supervisor, click on the row containing staff details,<br>
                        then use the panel on the right side to update supervisor.
                    </div>
                </div>
            </div>

            <!--actions' menu starts here -->
            @include('layouts.administrator.page-title-actions')
            <!--actions' menu ends here -->

        </div>
    </div>

    <div class="row">

        <!-- Supervisors List-->
        <div class="col-md-12" id="supervisors_list_div">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Staff No</th>
                            <th>Staff Name</th>
                            <th>Department</th>
                            <th>Supervisor</th>
                            <th>Supervisor Id</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($staff as $staff)
                            <tr class='staff-row'>
                                <td class="staff-id">{{ $staff->id }}</td>
                                <td class="staff-no">{{ $staff->staff_no }}</td>
                                <td class="staff-name">{{ ucwords($staff->first_name.' '.$staff->last_name) }}</td>
                                <td class="staff-dep">{{ $staff->department->name }}</td>
                                <td class="supervisor-name">
                                    <?php
                                    if($staff->supervisor_id == null || $staff->supervisor_id == ''){
                                        echo '';
                                    }else{
                                        $supervisor = \App\Models\Staff::find($staff->supervisor_id);
                                        echo ucwords($supervisor->first_name.' '.$supervisor->last_name);
                                    }
                                    ?>
                                </td>
                                <td class="supervisor-id">
                                    {{$staff->supervisor_id == null ? '' : $staff->supervisor_id}}
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <!-- Supervisors List Ends Here-->


        <!-- Supervisors Editing Form-->
        <div class="col-md-4" id="update_spv_div" style="display: none;">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title">Update Supervisor</h5>
                    <div class="p-3">
                        <form action="/staff_supervisor_update" method="POST" enctype="multipart/form-data" id="change_spv_form">
                            @csrf
                            {{ csrf_field() }}

                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="position-relative form-group">
                                        <label for="staff_name" class="">
                                            <span>Staff Name</span>
                                        </label>
                                        <input name="staff_name" id="staff_name" class="form-control" readonly>
                                        <input name="staff_id" id="staff_id" class="form-control" style="display: none;" readonly>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="position-relative form-group">
                                        <label for="supervisor_id" class="">
                                            <span>Supervisor</span>
                                        </label><br>
                                        <select name="supervisor_id" id="supervisor_id" class="form-control" style="width: 100%;">
                                            <option value="">Select Supervisor</option>
                                            @foreach($supervisors as $supervisor)
                                                <option value="{{$supervisor->id}}" @if(($supervisor->id == old('supervisor_id'))) selected @endif>
                                                    {{ucwords($supervisor->first_name.' '.$supervisor->last_name)}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <button class="mt-2 btn btn-primary" id="update_spv_btn">Update Supervisor</button>
                            <button class="mt-2 btn btn-danger" id="cancel">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Supervisors Editing Form Ends Here-->

    </div>

    <script type="text/javascript">

        $(document).ready(function(){

            $("#supervisor_id").select2({
                width: 'resolve',
            });
        });

        $(".staff-row").on("click",function(){

            $("#supervisors_list_div").removeClass('col-md-12');
            $("#supervisors_list_div").addClass('col-md-8');
            $("#update_spv_div").show();

            var staff_id = $(this).find(".staff-id").text();
            var staff_name = $(this).find(".staff-name").text();
            var supervisor_id = $(this).find(".supervisor-id").text().trim();

            $("#staff_id").val(staff_id);
            $("#staff_name").val(staff_name);
            $("#supervisor_id").val(supervisor_id);
        });

        $("#cancel").on('click',function(e){
            e.preventDefault();
            $("#update_spv_div").hide();
            $("#supervisors_list_div").removeClass('col-md-8');
            $("#supervisors_list_div").addClass('col-md-12');
        });
    </script>

@endsection
