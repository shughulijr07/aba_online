
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="filtersModal" aria-hidden="true" id="filtersModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLongTitle">Users Filtering Filter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body" id="modalLongBody">

                <div class="row" style="margin-bottom: 10px;">
                    <div class="col-md-12">
                        <div class="form-row">
                            <div class="col-md-5">
                                <div class="position-relative form-group">
                                    <label for="name" class="">Name</label>
                                    <input name="name" id="name" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="position-relative form-group">
                                    <label for="gender" class="">
                                        <span>Gender</span>
                                    </label>
                                    <select name="gender" id="gender" class="form-control">
                                        <option value="">Select Gender</option>
                                        <option value="Female">Female</option>
                                        <option value="Male">Male</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="phone_no" class="">Phone No.</label>
                                    <input name="phone_no" id="phone_no" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="position-relative form-group">
                                    <label for="email" class="">Email</label>
                                    <input name="email" id="email" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="position-relative form-group">
                                    <label for="company" class="">Company</label>
                                    <input name="company" id="company" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="status" class="">
                                        <span>Status</span>
                                    </label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Select Status</option>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4" id="role-selection-div">
                                <div class="position-relative form-group">
                                    <label for="system_role_id" class="">
                                        <span>Role in System</span>
                                    </label>
                                    <select name="system_role_id" id="system_role_id" class="form-control">
                                        <option value="">Select a Role</option>
                                        @foreach($system_roles as $system_role)
                                            @if($system_role->id != 1)
                                                <option value="{{$system_role->id}}">{{ucwords(str_replace('-',' ',$system_role->name))}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="filterButton">Filter</button>
                <button type="button" class="btn btn-primary" id="resetFiltersButton">Reset Filter</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>






<!--Script -->
<script type="text/javascript">
    $(document).ready(function(){
        // DataTable
        fill_datatable();
        $('#filters-panel').hide();
        $('#notifications-div').delay(5000).fadeOut('slow');
    });



    function fill_datatable(filters = {}){
        $('#systemUsersTable').DataTable({
            "autoWidth": true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('ajaxGetSystemUsers') }}",
                data:{ filters: filters },
            },
            columns: [
                //{ data: '#' },
                { data: 'Id' },
                { data: 'Name' },
                { data: 'Gender' },
                { data: 'Phone' },
                { data: 'Email' },
                { data: 'Company' },
                { data: 'Role' },
                { data: 'Status' },
                { data: 'Actions' },
            ],
        });
    }


    $('#filterButton').click(function(){
        console.log("filtering");

        var filters = {
            name: $('#name').val(),
            gender: $('#gender').val(),
            email: $('#email').val(),
            phone_no: $('#phone_no').val(),
            company: $('#company').val(),
            status: $('#status').val(),
            system_role_id: $('#system_role_id').val(),
        }

        $('#systemUsersTable').DataTable().destroy();
        fill_datatable(filters);
        closeModal();

    });

    $('#resetFiltersButton').click(function(){
        console.log("resetting-filters");

        $('#name').val('');
        $('#gender').val('');
        $('#email').val('');
        $('#phone_no').val('');
        $('#company').val('');
        $('#status').val('');
        $('#system_role_id').val('');

        $('#systemUsersTable').DataTable().destroy();
        fill_datatable();
    });

    $('#hide-filters-btn, #closeModalButton').click(function(){
        closeModal()
    });

    $('#show-filters-btn').click(function(){
        $('#filtersModal').modal('show');

    });


</script>

<script type="text/javascript">

    $(".close-modal").on("click",function(){
        closeModal();
    });


    function closeModal(){
        //clearForm();
        $("[data-dismiss=modal]").trigger({ type: "click" });
    }
</script>

