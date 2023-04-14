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
                    <div class="text-primary">System Role Information</div>
                    <div class="page-title-subheading">Below are information of role</div>
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
                        <div class="widget-title opacity-5 text-uppercase">Role Name</div>
                        <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                            <div class="widget-chart-flex align-items-center">
                                <div>
                                    {{$system_role->role_name}}
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
                        <div class="widget-title opacity-5 text-uppercase">Number Users</div>
                        <div class="widget-numbers mt-2 fsize-4 mb-0 w-100">
                            <div class="widget-chart-flex align-items-center">
                                <div>10</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-------- System Role Permissions -------->
    <div class="main-card mb-3 card">
        <div class="card-header-tab card-header">
            <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                <i class="header-icon lnr-laptop-phone mr-3 text-muted opacity-6"> </i>{{ucwords(str_replace('-',' ',$system_role->role_name))}} Permissions</div>
            <div class="btn-actions-pane-right actions-icon-btn">
                <div>
                    <button type="button" class="btn-primary mr-2" id="save-changes-btn" style="border-radius: 3px; display: none;">Update Permissions</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                <thead>
                    <tr style="background-color: #213368; color: white;">
                        <td></td>
                        <td>Select All</td>
                        <td></td>
                        <td><input type="checkbox" class="column-permissions" value="view"></td>
                        <td><input type="checkbox" class="column-permissions" value="add"></td>
                        <td><input type="checkbox" class="column-permissions" value="edit"></td>
                        <td><input type="checkbox" class="column-permissions" value="remove"></td>
                        <td></td>
                    </tr>
                    <tr style="background-color: #F15A24; color: white;">
                        <th>Id</th>
                        <th>Permission Name</th>
                        <th>Category</th>
                        <th>View</th>
                        <th>Add</th>
                        <th>Edit</th>
                        <th>Delete</th>
                        <th>All</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($role_permissions as $role_permission)
                        <?php
                            $view_visibility = '';
                            $add_visibility = '';
                            $edit_visibility = '';
                            $delete_visibility = '';

                            switch ($role_permission->permission->category){
                                case 'class' : $view_visibility = 'visible'; $add_visibility = 'visible'; $edit_visibility = 'visible'; $delete_visibility = 'visible'; break;
                                case 'action' : $view_visibility = 'visible'; $add_visibility = 'visible'; $edit_visibility = 'visible'; $delete_visibility = 'visible'; break;
                                case 'process' : $view_visibility = 'visible'; $add_visibility = 'invisible'; $edit_visibility = 'visible'; $delete_visibility = 'invisible'; break;
                                case 'menu' : $view_visibility = 'visible'; $add_visibility = 'invisible'; $edit_visibility = 'invisible'; $delete_visibility = 'invisible'; break;
                                case 'menu-item' : $view_visibility = 'visible'; $add_visibility = 'invisible'; $edit_visibility = 'invisible'; $delete_visibility = 'invisible'; break;
                                case 'sub-menu-item' : $view_visibility = 'visible'; $add_visibility = 'invisible'; $edit_visibility = 'invisible'; $delete_visibility = 'invisible'; break;
                                default : $view_visibility = 'visible'; $add_visibility = 'visible'; $edit_visibility = 'visible'; $delete_visibility = 'visible'; break;
                            }
                        ?>
                        <tr class='permission'>
                            <td class="permission-id">{{ $role_permission->id }}</td>
                            <td>{{ ucwords(str_replace('_',' ',$role_permission->permission->permission_name))}}</td>
                            <td>{{ ucwords(str_replace('-',' ',$role_permission->permission->category))}}</td>
                            <td><input type="checkbox" class="row-{{$role_permission->id}} {{$view_visibility}} one-permission view" value="{{$role_permission->view}}" @if($role_permission->view == 'true') checked @endif></td>
                            <td><input type="checkbox" class="row-{{$role_permission->id}} {{$add_visibility}} one-permission add" value="{{$role_permission->add}}" @if($role_permission->add == 'true') checked @endif></td>
                            <td><input type="checkbox" class="row-{{$role_permission->id}} {{$edit_visibility}} one-permission edit" value="{{$role_permission->edit}}" @if($role_permission->edit == 'true') checked @endif></td>
                            <td><input type="checkbox" class="row-{{$role_permission->id}} {{$delete_visibility}} one-permission remove" value="{{$role_permission->delete}}" @if($role_permission->delete == 'true') checked @endif></td>
                            <td>
                                <input type="checkbox" class="row-permissions" id="row-{{$role_permission->id}}" @if($role_permission->view == 'true' && $role_permission->add == 'true' && $role_permission->edit == 'true' && $role_permission->delete == 'true') checked @endif>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <th>Id</th>
                    <th>Permission Name</th>
                    <th>Category</th>
                    <th>View</th>
                    <th>Add</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
                </tfoot>
            </table>
        </div>
        @csrf
        {{ csrf_field() }}
    </div>

    <script type="text/javascript">

        //when all-permission in a column checkbox is clicked
        $(".column-permissions").on("click",function(){
            $("#save-changes-btn").show("slow");

            var class_name = $(this).val();
            var permission_selected = '';

            $(this).is(":checked") ? permission_selected = 'true' : permission_selected = 'false';

            //select all permissions with specified class name
            var permissions = $('.'+class_name);

            //chang the values in all permissions and check or un-check the permission box accordingly
            permissions.each(function(index){
                $(this).val(permission_selected);
                permission_selected == 'true' ? $(this).prop('checked',true) :  $(this).prop('checked',false);
            });
        });

        //when all-permission in a row checkbox is clicked
        $(".row-permissions").on("click",function(){
            $("#save-changes-btn").show("slow");

            var permission_selected = '';
            $(this).is(":checked") ? permission_selected = 'true' : permission_selected = 'false';

            var class_name = $(this).attr("id");

            //select all permissions with specified class name
            var permissions = $('.'+class_name);

            //chang the values in all permissions and check or un-check the permission box accordingly
            permissions.each(function(index){
                $(this).val(permission_selected);
                permission_selected == 'true' ? $(this).prop('checked',true) :  $(this).prop('checked',false);
            });
        });

        //when one permission checkbox is clicked
        $(".one-permission").on("click",function(){
            $("#save-changes-btn").show("slow");

            if($(this).is(":checked")){
                $(this).val('true');
            }
            else{
                $(this).val('false');
            }
        });

        $("#save-changes-btn").on("click", function(){
            var permissions = $(".permission");
            var new_permissions = [];
            var _token = $('input[name="_token"]').val();

            //get values in each permission

            permissions.each(function (index) {
                var permission_id = $(this).children(".permission-id").text();
                var view = $(this).children("td").children(".view").val();
                var add = $(this).children("td").children(".add").val();
                var edit = $(this).children("td").children(".edit").val();
                var remove = $(this).children("td").children(".remove").val();

                new_permissions[index] = [permission_id,view,add,edit,remove];
            });

            update_permissions(new_permissions,_token);
        });


        /************************* FUNCTIONS GOES HERE **************************/

        function update_permissions(new_permissions,_token){ console.log(new_permissions);

            //convert array to json
            var json_new_permissions = JSON.stringify(new_permissions); console.log(json_new_permissions);

            Swal.fire({
                title: 'Are you sure? Yo want to update permissions for this role ? ',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#213368',
                cancelButtonColor: '#F15A24',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {

                //ajax request
                $.ajax({
                    url:"{{ route('system_role_permissions.ajax_update_multiple') }}",
                    method:"POST",
                    data:{new_permissions:json_new_permissions,_token:_token},
                    success:function(data)
                    {
                        //add the list to selection option
                        //console.log(data);
                        data = JSON.parse(data);

                        if (data.length > 0) {
                            update_permissions_response(data);
                        }else{

                        }
                    }
                });

            }
        })
        }

        function update_permissions_response(data){


            if (data =='success') {

                $(document).off(".firstCall");//stop ajaxStart call

                sweet_alert_success('Permission Updated Successfully');

                //reload the page
                setTimeout(function(){ window.location.reload(); }, 1000);


            }

            else{
                sweet_alert_error('Failed to update permissions, please try again');
            }

        }



        /******************* sweet alerts *******************************/

        function sweet_alert_success(success_message){
            Swal.fire({
                type: "success",
                text: success_message,
                confirmButtonColor: '#213368',
            })
        }


        function sweet_alert_error(error_message){
            Swal.fire({
                type: "error",
                text: error_message,
                confirmButtonColor: '#213368',
            })
        }

        function sweet_alert_warning(warning_message){
            Swal.fire({
                type: "warning",
                text: warning_message,
                confirmButtonColor: '#213368',
            })
        }

    </script>

@endsection
