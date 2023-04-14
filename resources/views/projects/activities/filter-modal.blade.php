
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="filtersModal" aria-hidden="true" id="filtersModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLongTitle">Client Filtering Form <span class="text-primary" id="question-no-label"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body" id="modalLongBody">

                <form id="valid-form">

                    <fieldset>
                        <legend class="text-danger"></legend>
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="project_id" class="">Client</label>
                                    <select name="project_id" id="project_id" class="form-control filter-select" data-column="3">
                                        <option value="">Select Client</option>

                                        {{-- @foreach($projects as $project)
                                            <option value="{{$project->id}}">{{$project->name}}</option>
                                        @endforeach --}}
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="name" class="">Activity Name</label>
                                    <input name="name" id="name" type="text" class="form-control filter-input" data-column="1">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="code" class="">Activity Code</label>
                                    <input name="code" id="code" type="text" class="form-control filter-input" data-column="8">
                                </div>
                            </div>
                        </div>
                    </fieldset>

                </form>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="filterButton">Filter</button>
                <button type="button" class="btn btn-primary" id="resetFiltersButton">Reset Filter</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<script src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('js/jszip.min.js') }}"></script>
<script src="{{ asset('js/pdfmake.min.js') }}"></script>
<script src="{{ asset('js/vfs_fonts.js') }}"></script>
<script src="{{ asset('js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('js/buttons.print.min.js') }}"></script>
<script src="{{ asset('js/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('js/dataTables.colReorder.min.js') }}"></script>



<!--Script -->
<script type="text/javascript">
    $(document).ready(function(){
        // DataTable
        fill_datatable();
        $('#notifications-div').delay(5000).fadeOut('slow');
    });

    function fill_datatable(filters = {}){
        $('#activitiesTable').DataTable({
            lengthMenu: [10, 50, 100, 200, 500, 1000,5000],
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('activities.getList') }}",
                data:{ filters: filters },
            },
            colReorder: true,
            columns: [
                { data: '#' },
                { data: 'Activity' },
                { data: 'Activity Code' },
                { data: 'Project' },
                { data: 'Project Code' },
                { data: 'Action' },
            ],
            dom: 'lBfrtip',
            buttons: {

                buttons: [
                    {
                        extend: 'colvis',
                        className: 'btn btn-primary',
                        text: '<i class="pe-7s-edit"> </i> Columns',
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-secondary',
                        text: '<i class="lnr-printer"> </i> Print',
                        exportOptions: {
                            columns: ':visible',
                        },
                        title: '',
                        message: function() {
                            let messageTop = "";

                            let titleBanner = $('#report-title-banner').html();
                            let reportTitle = '{{$reportTitle ?? ''}}';
                            let table = $('#activitiesTable').DataTable();
                            let searchValues = table.search();

                            let reportTitleHtml = '<div class="col-12 text-center"><h3 class="report-title" style="font-family: Times, Helvetica, sans-serif;">'+reportTitle+'</h3></div>';

                            let filtersHtml = '<span><strong>'+'Search Terms : </strong></span> '+searchValues;


                            messageTop  = titleBanner + reportTitleHtml;
                            messageTop += '<hr>';
                            messageTop += searchValues.length > 0 ? '<br>' + filtersHtml : '';

                            return messageTop;
                        },
                        customize: function (win) {
                            $(win.document.body).find('table').removeClass('table-responsive-sm');
                            $(win.document.body).find('table').removeClass('table-responsive-md');
                            $(win.document.body).find('table').css('font-size','10px');

                            //Modify column widths
                            let tableHeaders = $(win.document.body).find('table').children('thead').children('tr').children();
                            for(let i=0; i<tableHeaders.length; i++){
                                if(tableHeaders[i].innerText === "#"){tableHeaders[i].width = '5%';}
                                //console.log(tableHeaders[i].innerText);
                            }

                            //console.log($(win.document.body).find('table').children('thead').children('tr').children('th'));
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="lnr-cloud-download"> </i> PDF',
                        className: 'btn btn-secondary',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        exportOptions: {
                            columns: ':visible'
                        },

                        title: function(){
                            return '{{$reportTitle ?? ''}}';
                        },

                        messageTop: function() {
                            return "123";
                        },

                        customize: function (doc) {
                            pdfMake.fonts = {
                                Roboto: {
                                    normal: 'Roboto-Regular.ttf',
                                    bold: 'Roboto-Medium.ttf',
                                    italics: 'Roboto-Italic.ttf',
                                    bolditalics: 'Roboto-MediumItalic.ttf'
                                },
                                Arial: {
                                    normal: "Arial.ttf",
                                    bold: "Arial-Bold.ttf",
                                    italics: "Arial-Italic.ttf",
                                    bolditalics: "Arial-Bold-Italic.ttf",
                                    light: "Arial-Light.ttf",
                                    lightitalic: "Arial-Light-Italic.ttf",
                                    medium: "Arial-Medium.ttf",
                                    mediumitalic: "Arial-Medium-Italic.ttf"
                                },
                                Times: {
                                    normal: 'Times.ttf',
                                    bold: 'Times-Bold.ttf',
                                    italics: 'Times-Italics.ttf',
                                    bolditalics: 'Times-Bold-Italics.ttf'
                                }
                            };
                            doc.defaultStyle.font = "Roboto";

                            doc.pageMargins = [40, 80, 40, 30];
                            const companyLogoBase64 = '{{$companyLogoBase64 ?? ''}}';
                            doc.header = [
                                {
                                    margin: [30,15,30,10],
                                    columns: [
                                        {
                                            margin: [5,0,5,30],
                                            text: [
                                                { text: 'UNITED REPUBLIC OF TANZANIA \n', fontSize: 12, font: "Times", bold: true  },
                                                { text: 'MINISTRY OF AGRICULTURE \n', fontSize: 12, font: "Times", bold: true  },
                                                { text: 'SUGAR BOARD OF TANZANIA \n', fontSize: 18, font: "Times", bold: true },
                                                {
                                                    text: '',
                                                    fontSize: 12, font: "Times", bold: true
                                                },
                                            ],
                                            alignment: 'center',
                                            width: '*',
                                        },
                                        {
                                            image: companyLogoBase64 ,
                                            width: 65,
                                            height: 65,
                                            padding: 10,
                                        }
                                    ],
                                    columnGap: 10
                                }
                            ];


                            //Styling
                            doc.styles.title = {
                                color: '#008FDC',
                                fontSize: '12',
                                bold: true,
                                alignment: 'center',
                                font: "Times"
                            };

                            //adding content to message
                            let table = $('#activitiesTable').DataTable();
                            let searchValue = table.search();


                            doc.content[1].text = [
                                {canvas: [ { type: 'line', x1: 40, y1: 0, x2: 595-40, y2: 0, lineWidth: 1,color:'#595959' } ]},
                                {
                                    text: searchValue.length > 0 ? '\nSearch Terms : ' : '',
                                    fontSize: 9, font: "Roboto", bold: true, color: 'black'
                                },
                                {
                                    text: searchValue.length > 0 ?  searchValue : '',
                                    fontSize: 9, font: "Roboto", bold: true, color: '#008FDC'
                                }
                            ]

                            doc.styles.message = {
                                //color: '#008FDC',
                                color: 'black',
                                //fontSize: '10',
                                alignment: 'left',
                            };



                            doc.styles.tableHeader.alignment = 'left';
                            doc.defaultStyle.fontSize = 8;

                            //table font size and margins in cells
                            doc.styles.tableHeader.fontSize = 9;
                            doc.styles.tableHeader.margin = [2, 2, 2, 2];
                            doc.styles.tableBodyOdd.margin = [2, 1, 2, 1];
                            doc.styles.tableBodyEven.margin = [2, 1, 2, 1];

                            //table column width
                            //doc.content[2].table.widths = ["5%", "35%", "10%", "*"];

                            //style first row of the table
                            doc.content[2].table.body[0].forEach(function (h) {
                                h.fillColor = "#595959";
                                h.alignment= "left";
                            });

                            //Footer
                            doc['footer']=(function(currentPage, pageCount) {
                                let leftText = '{{date('Y-m-d H:i:s').' | '.url('/')}}';
                                return [
                                    {canvas: [ { type: 'line', x1: 40, y1: 0, x2: 595-40, y2: 0, lineWidth: 1,color:'#595959' } ]},
                                    {
                                        columns: [
                                            {
                                                alignment: 'left',
                                                text: [
                                                    { text: leftText, fontSize: 7 },
                                                ]

                                            },
                                            {
                                                alignment: 'right',
                                                text: [
                                                    { text: currentPage.toString(), italics: true, fontSize: 7 },
                                                    ' of ',
                                                    { text: pageCount.toString(), italics: true, fontSize: 7 }
                                                ]
                                            }
                                        ],
                                        margin: [40, 3,40,0]
                                    }
                                ]
                            });

                            //console.log(doc.content[2].table);
                            return doc;
                        }


                    },
                    {
                        extend: 'excelHtml5',
                        className: 'btn btn-secondary',
                        text: '<i class="lnr-cloud-download"> </i> Excel',
                        exportOptions: {
                            columns: ':visible'
                        },
                        title: function(){
                            return '{{$reportTitle ?? ''}}';
                        },
                        message: function() {
                            let messageTop = "";

                            let table = $('#activitiesTable').DataTable();
                            let searchValue = table.search();

                            messageTop += searchValue.length > 0 ? '\nSearch Terms : ' : '';
                            messageTop += searchValue.length > 0 ?  searchValue : '';

                            return messageTop;
                        },
                    },
                    {
                        extend: 'csvHtml5',
                        text: '<i class="pe-7s-file"> </i> CSV',
                        className: 'btn btn-secondary',
                        exportOptions: {
                            columns: ':visible'
                        },
                        title: function(){
                            return '{{$reportTitle ?? ''}}';
                        },
                        message: function() {
                            let messageTop = "";

                            let table = $('#activitiesTable').DataTable();
                            let searchValue = table.search();

                            messageTop += searchValue.length > 0 ? '\nSearch Terms : ' : '';
                            messageTop += searchValue.length > 0 ?  searchValue : '';

                            return messageTop;
                        },
                    },
                    {
                        extend: 'copyHtml5',
                        text: '<i class="pe-7s-copy-file"> </i> Copy',
                        className: 'btn btn-secondary',
                        exportOptions: {
                            columns: ':visible'
                        },
                        message: function(){
                            return '{{$reportTitle ?? ''}}';
                        },
                        title: function() {
                            let messageTop = "";

                            let table = $('#activitiesTable').DataTable();
                            let searchValue = table.search();


                            messageTop += searchValue.length > 0 ? '\nSearch Terms : ' : '';
                            messageTop += searchValue.length > 0 ?  searchValue : '';

                            return messageTop;
                        },
                    },
                ],
            },
        });
    }


    $('#filterButton').click(function(){
        console.log("filtering");

        var filters = {
            name: $('#name').val(),
            gender: $('#number').val(),
        }

        $('#activitiesTable').DataTable().destroy();
        fill_datatable(filters);
        closeModal();

    });

    $('#resetFiltersButton').click(function(){
        console.log("reseting-filters");

        $('#name').val('');
        $('#number').val('');

        fill_datatable();
    });

    $('#hide-filters-btn, #closeModalButton').click(function(){
        closeModal()
    });

    $('#show-filters-btn').click(function(){
        $('#filtersModal').modal('show');

    });


    function deleteRecord(activityId, activityCode) {
        var _token = $("meta[name='csrf-token']").attr("content");
        ajaxDelete(activityId, activityCode, _token);
    }


    function ajaxDelete(activityId, activityCode, _token) {


        Swal.fire({
            title: 'You are Deleting Activity ('+activityCode+') \n Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#008FDC',
            cancelButtonColor: '#FF0000',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.value) {

                //ajax request
                $.ajax({
                    url: "{{ route('activities.ajaxDelete') }}",
                    method: 'POST',
                    data: {
                        "id": activityId,
                        "_token": _token,
                    },
                    success: function (data) {
                        //console.log(data['feedback']);

                        if (data['feedback'] == 'success') {
                            sweet_alert_success(data['message']);
                            setTimeout(function () {
                                window.location.reload();
                            }, 1000);

                        } else if (data['feedback'] == 'fail') {
                            sweet_alert_error(data['message'])
                        }
                    }
                });

            }
        })

    }


</script>

<script type="text/javascript">

    $(".close-modal").on("click",function(){
        closeModal();
    });


    function closeModal(){
        //clearForm();
        $("[data-dismiss=modal]").trigger({ type: "click" });
    }

    function sweet_alert_success(success_message) {
        Swal.fire({
            type: "success",
            text: success_message,
            confirmButtonColor: '#008FDC',
        })
    }


    function sweet_alert_error(error_message) {
        Swal.fire({
            type: "error",
            text: error_message,
            confirmButtonColor: '#008FDC',
        })
    }

    function sweet_alert_warning(warning_message) {
        Swal.fire({
            type: "warning",
            text: warning_message,
            confirmButtonColor: '#008FDC',
        })
    }
</script>
