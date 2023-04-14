@extends('layouts.administrator.admin')


@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon text-primary">
                <i class="fas fa-file"></i>
            </div>
            <div class="p-3">
                <div class="page-title-subheading">
                    Employee
                </div>
                <div class="text-primary">{{ $employee_name }}</div>

            </div>

            <div class="p-3">
                <div class="page-title-subheading">
                    Supervisor
                </div>
                <div class="text-primary">{{ $spv_name }}</div>

            </div>

            <div class="p-3">
                <div class="page-title-subheading">
                    Year
                </div>
                <div class="text-primary">{{ $time_sheet->year }}</div>

            </div>

            <div class="p-3">
                <div class="page-title-subheading">
                    Month
                </div>
                <div class="text-primary">{{ date('F', mktime(0, 0, 0, $time_sheet->month, 1)) }}</div>

            </div>


            <div class="p-3">
                <div class="page-title-subheading">
                    Status
                </div>
                <div class="text-primary">{{ $time_sheet->status }}</div>

            </div>

        </div>

        <!--actions' menu starts here -->
        <!--actions' menu ends here -->

    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="col-auto float-right ml-auto">
            {{-- <a href="{{ route('timesheet-add-client', ['time_sheet'=>$time_sheet->id]) }}" class="btn btn-primary">Add Client</a> --}}

        </div>
        <div class="p-3">
            @if (count($clientsheets) > 0)
            <table class="table table-bordered table-hover table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Clients</th>
                        <th>Task</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clientsheets as $clientsheet)
                    @php
                    $client = App\Models\Project::find($clientsheet->project_id);
                    @endphp
                    <tr>
                        <td>{{ $client->name }}</td>
                        <td class="position-relative">
                            @foreach (App\Models\Task::where('timesheet_client_id', $clientsheet->id)->get() as $task)
                            <div class="d-flex">
                                <p>{{ $task->task_name }} </p>
                               @if ($time_sheet->status == 10)
                                    <div class="d-flex ml-auto">
                                    <a href="#">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <a href="/delete_task/{{$task->id}}">
                                        <i class="fas fa-trash-alt ml-3"></i>
                                    </a>
                                </div>
                               @endif
                            </div>
                            @endforeach
                            <div id="hiddenTaskForm{{ $client->id }}" style="visibility:hidden; opacity: none; position:absolute; left: 0" class="col-md-8 shadow p-3 bg-white z-index-6">
                                <!-- <div id=" hiddenTaskForm{{ $client->id }}" style="display: none; position:absolute;" class="shadow p-3 bg-light z-index-6"> -->
                                <form action="{{ route('timesheet-add-client') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="timesheet_client_id" value="{{$clientsheet->id}}">
                                    <input type="hidden" name="time_sheet_id" value="{{ $time_sheet->id }}">
                                    <input type="hidden" name="mode" value="addtask">
                                    <div class="form-group">
                                        <label for="">Task Name</label>
                                        <input type="text" name="task_name" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-success">Save Detail</button>
                                    <button type="button" class="btn btn-sm btn-light cancelTask" onclick="cancelTask({{ $client->id}})">Cancel</button>
                                </form>
                            </div>
                            @if ($time_sheet->status == 10)
                                <div class="d-flex justify-content-end">
                                <button onclick="addTask({{ $client->id}})" class="btn btn-primary">Add Task</button>
                            </div>
                            @endif

                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
            @endif


        </div>
        <div class=" px-3">
            <div class="d-flex justify-content-between align-items-center flex-sm-row flex-column">
                <form action="{{ route('timesheet-add-client') }}" method="post" class="mb-md-0 mb-4">
                    @csrf
                    <input type="hidden" name="time_sheet_id" value="{{ $time_sheet->id }}">
                    <input type="hidden" name="mode" value="addclient">
                    @if ($time_sheet->status == 10)
                         <div class="d-flex align-items-center gap-3">
                            <div class="form-group my-0">
                                <select name="project_id" id="client-select" class="form-control" required>
                                    <option value="">Select client</option>
                                    @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->number }} {{ $client->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <button class="btn btn-primary ml-3" id="add-client-btn">Add Client</button>
                         </div>
                         @endif
                </form>
                <div class="d-flex justify-content-end">
                    {{-- <div class="btn btn-lg px-5 text-white" style="background: #3490dc;">Save</div> --}}
                    <a href="/time-sheets" class="btn btn-lg px-5 text-white"  style="background: #3490dc;">
                        @if ($time_sheet->status == 10)
                            Save
                        @else
                            Cancel
                        @endif
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>


<div class=" modal fade" tabindex="0" role="dialog" aria-labelledby="modal" aria-hidden="true" id="modal">
    <div class="modal-dialog modal-lg" id="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #00838F; color: #ffffff;">
                <h5 class="modal-title" id="modalTitle">Modal Title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body" id="modalBody">Modal Body Goes Here</div>

            <div class="modal-footer" id="modalFooter">Modal Footer Goes Here</div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="add_client" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Add client to Timesheet</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    @csrf
                    <input type="hidden" name="time_sheet_id" value="{{ $time_sheet->id }}">
                    <div class="form-group">
                        <label for="">Client</label>
                        <select name="project_id" id="" class="form-control">
                            <option value="">select client</option>
                            @foreach ($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->number }} {{ $client->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
        // var showButton = document.getElementById("addTask");
        // var hiddenDiv = document.getElementById("hiddenTaskForm");

        // showButton.onclick = function() {
        //     hiddenDiv.style.display = "block";
        // };
        $(document).ready(function() {
            $(function() {
                $('#add-client-btn').hide();
                $('#client-select').change(function() {
                    var opt = $(this).val();
                    if (opt) {
                        $('#add-client-btn').show(100);
                    } else {
                        $('#add-client-btn').hide(100);
                    }
                });
            });
        });

        function addTask(id) {
            hiddenDiv = document.getElementById("hiddenTaskForm" + id)
            hiddenDiv.style.opacity = "1";
            hiddenDiv.style.visibility = "visible";
        }

        function cancelTask(id) {
            hiddenDiv = document.getElementById("hiddenTaskForm" + id)
            hiddenDiv.style.opacity = "0";
            hiddenDiv.style.visibility = "hidden";
        }
    </script>
@endsection