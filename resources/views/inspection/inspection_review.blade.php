<x-layouts.app>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

    <link rel="stylesheet" href=
"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    @php
        $year = date('Y');
    @endphp
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="card bg-transparent">
        <div class="card-header">
            <h4>Inspection Review</h4>
            <form action="{{ route('summarypage') }}" method="get">
                <div class="d-flex flex-row">
                    <div class="p-3">
                        <label for="" class="form-label">Season</label>
                        <select class="form-select" id="season" name="season">
                            @forelse ($seasons as $season)
                                <option value="{{ $season->season }}" selected>{{ $season->season }}</option>
                            @empty
                                <option value="No seasons available" disabled>No Season Available</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="p-3">
                        <label for="" class="form-label">Report</label>
                        <select class="form-select" id="report" name="report">
                            @forelse ($reports as $report)
                                <option value="{{ $report->id }}" selected>{{ $report->reportname }}</option>
                            @empty
                                <option value=null disabled>No Report Available</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="p-3">
                        <label for="" class="form-label">Report State</label>
                        <select class="form-select" id="reportstate" name="reportstate">
                            <option value="ALL" selected>ALL</option>
                            <option value="APPROVED">APPROVED</option>
                            <option value="CONDITIONAL">APPROVED WITH CONDITIONS</option>
                            <option value="PENDING">PENDING</option>
                            <option value="SUBMITTED">SUBMITTED</option>
                            <option value="REJECTED">REJECTED</option>
                        </select>
                    </div>
                    <div class="col-auto p-3">
                        <br>
                        <button class="btn btn-success" type="type">Generate Summary </button>
                    </div>




                </div>
            </form>


        </div>
        <div class="card-body table-responsive">
            <button id="clearFilters" class="btn btn-secondary mb-2">Clear Filters</button>

            <table class="table display table-striped table-hover" id="reports">
                <thead>
                    <tr>
                        <th>Season</th>
                        <th>ReportType</th>
                        <th>FiledBy</th>
                        <th>FarmName</th>
                        <th>Score</th>
                        <th>Status</th>
                        <th>Error Check</th>
                        <th>Date</th>
                        <th>IMS Mgr Comment(s)</th>
                        <th>Verification</th>
                        <th>Action</th>
                    </tr>
                    <tr>
                        <th>
                            <select class="form-select" id="season" name="season">
                                <option value="">All</option>
                                @forelse ($seasons as $season)
                                    <option>{{ $season->season }}</option>
                                @empty
                                    <option value="No seasons available" disabled>No Season Available</option>
                                @endforelse
                            </select>

                        </th>
                        <th>
                            <select class="form-select" id="report" name="report">
                                <option value="">All</option>
                                @forelse ($reports as $report)
                                    <option>{{ $report->reportname }}</option>
                                @empty
                                    <option>No Report Available</option>
                                @endforelse
                            </select>
                        </th>
                        <th><input type="text" placeholder="Search Filed By" /></th>
                        <th><input type="text" placeholder="Search Farm Name" /></th>
                        <th></th>
                        <th><input type="text" placeholder="Search Status" /></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>

                    </tr>
                </thead>
                <tbody>

                    @forelse ($reportquestions as $inspection)
                        <tr>
                            <td>{{ $inspection->season }}</td>
                            <td>{{ $inspection->getreport()->reportname }}</td>
                            <td>{{ $inspection->reportinspectorname()->iname }}</td>
                            <td>{{ $inspection->getfarm()->farmname }}</td>
                            <td>
                                @if ($inspection->getreport()->max_score == 0)
                                    Check Report
                                @else
                                    <b> {{ number_format(($inspection->score / $inspection->getreport()->max_score) * 100, 2) }}%
                                    </b>
                            </td>
                    @endif
                    <td>
                        @switch($inspection->inspectionstate)
                            @case('APPROVED')
                                <b style="color:green"> {{ $inspection->inspectionstate }}</b>
                            @break

                            @case('CONDITIONAL')
                                <b style="color: orange"> {{ $inspection->inspectionstate }}</b>
                            @break

                            @case('REJECTED')
                                <b style="color: red"> {{ $inspection->inspectionstate }}</b>
                            @break

                            @default
                                {{ $inspection->inspectionstate }}
                        @endswitch
                    </td>
                    <td>

                        @if (!empty($inspection->getplotdetails()) || $inspection->inspectionstate == 'ACTIVE')
                            @if (strpos($inspection->getreport()->reportname, 'Entrance') != false)
                                <b style="color: green; font-size: 0.75em ">No Issues detected</b>
                            @endif
                        @else
                            @if (strpos($inspection->getreport()->reportname, 'Entrance') != false)
                                <b style="color: red; font-size: 0.75em">Potential Issues detected (Check Plots
                                    mapped)</b>
                            @endif
                        @endif
                    </td>
                    <td>{{ $inspection->created_at }}</td>
                    <td>
                        <form action="iapprove" method="POST">
                            @csrf
                            <textarea class="form-control" name="comments" id="comments">{{ $inspection->comments }}</textarea>
                    </td>
                    <td><b>{{ $inspection->getverificationstatus() }}</b></td>
                    <td>
                        <div style="margin-top: 5px">
                            <input type="text" hidden name="iid" value={{ $inspection->id }}>
                            <button name="viewsheet" type="submit" id="viewbtn" class="btn btn-success"
                                data-toggle="tooltip" data-placement="right" title="View inspection Sheet"><i
                                    class="fa fa-eye"></i></button>

                        </div>
                        @if (in_array($inspection->inspectionstate, ['CONDITIONAL']) && $inspection->verifiedby == null)
                            <div style="margin-top: 5px">

                                <button type="button" name="verifybtn" class="btn btn-info" data-bs-toggle="modal"
                                    data-bs-target="#verifyModal"
                                    data-bs-whatever="{{ $inspection->farmname }} : {{ $inspection->reportname }}"
                                    data-bs-whatever2="{{ $inspection->id }}"
                                    data-bs-conditions="{{ $inspection->conditions }}" data-toggle="tooltip"
                                    data-placement="right" title="Verify Inspection"><i
                                        class="fa fa-search-plus"></i></button>
                            </div>
                        @endif
                        @if (in_array($inspection->inspectionstate, ['SUBMITTED', 'PENDING', 'ACTIVE']))
                            <div style="margin-top: 5px">
                                <button type="submit" name="approvebtn" class="btn btn-success" data-toggle="tooltip"
                                    data-placement="right" title="Approve Inspection (IMS Manager)"><i
                                        class="fa fa-check-square-o"></i></button>
                            </div>
                            <div style="margin-top: 5px">

                                <button type="submit" name="rejectbtn" class="btn btn-danger"><i
                                        class="fa fa-times-circle" data-toggle="tooltip" data-placement="right"
                                        title="Inspection not Approved (IMS Manager)"></i></button>
                            </div>
                            <div style="margin-top: 5px">
                                <button type="submit" name="deletetbtn" class="btn btn-danger"><i
                                        class="fa fa-trash" data-toggle="tooltip" data-placement="right"
                                        title="Delete Inspection"></i></button>
                            </div>
                        @endif
                        @if (strpos($inspection->getreport()->reportname, 'Entrance') == false && $inspection->ecomm_checked == 1)
                            <!-- Entrance String is not found display approve with condition button-->

                            <div style=" margin-top: 5px">
                                <button type="button" name="approvewithconditionmodal" class="btn btn-warning"
                                    data-bs-toggle="modal" data-bs-target="#exampleModal"
                                    data-bs-whatever="{{ $inspection->farmname }} : {{ $inspection->reportname }}"
                                    data-bs-whatever2="{{ $inspection->id }}" data-toggle="tooltip"
                                    data-placement="right" title="Evaluation Committee Decision"><i
                                        class="fa fa-check-square-o"></i></button>
                            </div>
                        @endif
                        </form>
                    </td>

                    </tr>
                    @empty
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>No Inspection Sheets on System </td>
                            <td></td>
                            <td></td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>
        <!-- MODAL approve with condition -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Conditions for Approval</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="farmschedule" method="post" action='iapprove'>
                        @csrf
                        <div class="modal-body">

                            <div class="mb-3">
                                <label for="iid" class="col-form-label">Inspection Report ID:</label>
                                <input type="text" readonly class="form-control fcode" id="report_name"
                                    name="report_name">
                                <input type="text" class="form-control fiid" hidden name="iid">
                            </div>
                            <div class="mb-3">
                                <label for="selectstate" class="col-form-label">Committee Decision:</label>
                                <select name="ecdecision" id="ecdecision" required class="form-select">
                                    <option value="APPROVED">APPROVED</option>
                                    <option value="CONDITIONAL">APPROVED WITH CONDITIONS</option>
                                    <option value="REJECTED">REJECTED</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="message-text" class="col-form-label">Conditions</label>
                                <textarea class="form form-control" name="apprconditions" id="approveconditions" cols="20" rows="10"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="list_ac" class="col-form-label">{{ $year }} Current Approval
                                    Committee</label>
                                <p>
                                    @forelse ($approvalcommittees as $acmember)
                                        <b>{{ $acmember->name }}</b>({{ $acmember->position }})<br />
                                    @empty
                                        <b>No Committee Members for {{ $year }} on Record</b>
                                    @endforelse
                                </p>
                                @if ($approvalcommittees->isNotEmpty())
                                    <label for="addcommittee" class="form-label">Add Committee Members to Note </label>
                                    <input type="checkbox" name="addcommittee" class="form-checked">
                                @endif

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-success" value="Save" name="approvewithcondition">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- MODAL for Verification-->
        <div class="modal fade" id="verifyModal" tabindex="-1" aria-labelledby="verifyModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="verifyModalLabel">Conditions for Approval</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="verifyinspection" method="post" action='iapprove'>
                        @csrf
                        <div class="modal-body">

                            <div class="mb-3">
                                <label for="iid" class="col-form-label">Inspection Report ID:</label>
                                <input type="text" readonly class="form-control vcode" id="report_name_verify"
                                    name="report_name_verify">
                                <input type="text" class="form-control fiid" hidden name="iid">
                            </div>
                            <div class="mb-3">
                                <label for="message-text" class="col-form-label">Conditions</label>
                                <textarea class="form form-control" readonly name="apprconditions" id="approveconditions_verify" cols="20"
                                    rows="6"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="message-text" class="col-form-label">Verification Notes (Optional)</label>
                                <textarea class="form form-control" name="verify_note" id="verify_note" cols="20" rows="6"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="message-text" class="col-form-label">Verification Date</label>
                                <input type="date" name="verify_date" id="verify_date" class="form-control"
                                    default="{{ date('d-m-Y') }}">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <input type="submit" class="btn btn-success" value="Verify" name="verifyinspection">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            //Config for approve with Condition Modal
            var exampleModal = document.getElementById('exampleModal')
            exampleModal.addEventListener('show.bs.modal', function(event) {
                // Button that triggered the modal
                var button = event.relatedTarget
                // Extract info from data-bs-* attributes
                var recipient = button.getAttribute('data-bs-whatever')
                var reportid = button.getAttribute('data-bs-whatever2')
                // If necessary, you could initiate an AJAX request here
                // and then do the updating in a callback.
                //
                // Update the modal's content.
                var modalTitle = exampleModal.querySelector('.modal-title')
                var modalBodyInput = exampleModal.querySelector('.fcode')
                var modalBodyreportid = exampleModal.querySelector('.fiid')

                modalTitle.textContent = 'Approval Conditions for Farm  '
                modalBodyInput.value = recipient
                modalBodyreportid.value = reportid
            })

            //Config for VerifyModal
            var verifyModal = document.getElementById('verifyModal')
            verifyModal.addEventListener('show.bs.modal', function(event) {
                // Button that triggered the modal
                var button = event.relatedTarget
                // Extract info from data-bs-* attributes
                var recipient = button.getAttribute('data-bs-whatever')
                var reportid = button.getAttribute('data-bs-whatever2')
                var conditions = button.getAttribute('data-bs-conditions')
                // If necessary, you could initiate an AJAX request here
                // and then do the updating in a callback.
                //
                // Update the modal's content.
                var modalTitle = verifyModal.querySelector('.modal-title')
                var modalBodyInput = verifyModal.querySelector('.vcode')
                var modalBodyreportid = verifyModal.querySelector('.fiid')
                var modalBodyConditions = verifyModal.querySelector('#approveconditions_verify')

                modalTitle.textContent = 'Verification Conditions for Farm  '
                modalBodyInput.value = recipient
                modalBodyreportid.value = reportid
                modalBodyConditions.value = conditions
            })
        </script>
        <script>
            $(document).ready(function() {
                var table = $('#reports').DataTable({
                    dom: 'Bfrtip',
                    pageLength: 200,
                    order: [
                        [1, 'asc']
                    ],
                    stateSave: true,
                    buttons: [{
                        extend: 'excelHtml5',
                        title: 'Inspection_Report_review',
                        exportOptions: {
                            columns: ':visible'
                        }
                    }]
                });

                // Apply column filters
                $('#reports thead tr:eq(1) th').each(function(i) {
                    $('input, select', this).on('keyup change', function() {
                        if (table.column(i).search() !== this.value) {
                            table.column(i).search(this.value).draw();
                        }
                    });
                });

                $('#clearFilters').on('click', function() {
                    // Clear each input/select in the filter row
                    $('#reports thead tr:eq(1) th').each(function(i) {
                        $('input, select', this).val('');
                        table.column(i).search('').draw();
                    });
                });
            });
        </script>
    </x-layouts.app>
