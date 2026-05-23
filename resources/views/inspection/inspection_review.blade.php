<x-layouts.app>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        @media (max-width: 576px) { th, td { font-size: 0.75rem; padding: 0.25rem; } }
        @media (min-width: 768px) { th, td { font-size: 0.75rem; padding: 0.5rem; } }
        @media (min-width: 992px) { th, td { font-size: 1rem; padding: 0.75rem; } }
        .table td { word-wrap: break-word; }
        .score-bar  { height:8px; border-radius:4px; background:#e9ecef; }
        .score-fill { height:8px; border-radius:4px; transition:width .6s ease; }
    </style>

    @php
        $year = date('Y');

        $statusBadge = fn($state) => match($state) {
            'APPROVED'    => 'bg-success',
            'CONDITIONAL' => 'bg-warning text-dark',
            'REJECTED'    => 'bg-danger',
            'SUBMITTED'   => 'bg-info text-dark',
            'ACTIVE'      => 'bg-primary',
            'PENDING'     => 'bg-secondary',
            default       => 'bg-secondary',
        };

        $scoreColor = fn($pct) => match(true) {
            $pct <= 49.99 => '#dc3545',
            $pct <= 69.99 => '#fd7e14',
            default       => '#198754',
        };
    @endphp

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h4 class="mb-3">Inspection Review</h4>

    {{-- Generate Summary filter form --}}
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('summarypage') }}" method="get">
                <div class="d-flex flex-wrap gap-3 align-items-end">
                    <div>
                        <label class="form-label">Season</label>
                        <select class="form-select" name="season">
                            @forelse ($seasons as $season)
                                <option value="{{ $season->season }}">{{ $season->season }}</option>
                            @empty
                                <option disabled>No Season Available</option>
                            @endforelse
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Report</label>
                        <select class="form-select" name="report">
                            @forelse ($reports as $report)
                                <option value="{{ $report->id }}">{{ $report->reportname }}</option>
                            @empty
                                <option disabled>No Report Available</option>
                            @endforelse
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Report State</label>
                        <select class="form-select" name="reportstate">
                            <option value="ALL">ALL</option>
                            <option value="APPROVED">APPROVED</option>
                            <option value="CONDITIONAL">APPROVED WITH CONDITIONS</option>
                            <option value="PENDING">PENDING</option>
                            <option value="SUBMITTED">SUBMITTED</option>
                            <option value="REJECTED">REJECTED</option>
                        </select>
                    </div>
                    <div>
                        <button class="btn btn-success" type="submit">
                            <i class="fa fa-bar-chart"></i> Generate Summary
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Review Table --}}
    <div class="card bg-transparent">
        <div class="card-body table-responsive fs-6">
            <button id="clearFilters" class="btn btn-secondary btn-sm mb-2">Clear Filters</button>

            <table class="table display table-sm table-striped table-hover" id="reports">
                <thead>
                    <tr>
                        <th>Season</th>
                        <th>Report Type</th>
                        <th>Filed By</th>
                        <th>Farm Name</th>
                        <th>Score</th>
                        <th>Status</th>
                        <th>Error Check</th>
                        <th>IMS Mgr Comment(s)</th>
                        <th>Verification</th>
                        <th>Action</th>
                    </tr>
                    <tr>
                        <th>
                            <select class="form-select form-select-sm" id="colSeason">
                                <option value="">All</option>
                                @forelse ($seasons as $season)
                                    <option>{{ $season->season }}</option>
                                @empty
                                    <option disabled>No Season Available</option>
                                @endforelse
                            </select>
                        </th>
                        <th>
                            <select class="form-select form-select-sm" id="colReport">
                                <option value="">All</option>
                                @forelse ($reports as $report)
                                    <option>{{ $report->reportname }}</option>
                                @empty
                                    <option>No Report Available</option>
                                @endforelse
                            </select>
                        </th>
                        <th><input type="text" placeholder="Search Filed By" class="form-control form-control-sm"/></th>
                        <th><input type="text" placeholder="Search Farm" class="form-control form-control-sm"/></th>
                        <th></th>
                        <th><input type="text" placeholder="Search Status" class="form-control form-control-sm"/></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reportquestions as $inspection)
                        @php
                            $rep = $inspection->getreport();
                            $pct = ($rep && $rep->max_score > 0)
                                ? round(($inspection->score / $rep->max_score) * 100, 2)
                                : null;
                        @endphp
                        <tr>
                            <td>{{ $inspection->season }}</td>
                            <td>{{ $rep?->reportname }}</td>
                            <td>{{ $inspection->reportinspectorname()?->iname }}</td>
                            <td>{{ $inspection->getfarm()?->farmname }}</td>
                            <td style="min-width:90px">
                                @if ($pct === null)
                                    <span class="text-muted small">Check Report</span>
                                @else
                                    <span class="fw-bold" style="color:{{ $scoreColor($pct) }}">{{ $pct }}%</span>
                                    <div class="score-bar mt-1">
                                        <div class="score-fill" style="width:{{ $pct }}%; background:{{ $scoreColor($pct) }}"></div>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $statusBadge($inspection->inspectionstate) }}" style="font-size:0.75em">
                                    {{ $inspection->inspectionstate }}
                                </span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                @if (!empty($inspection->getplotdetails()) || $inspection->inspectionstate === 'ACTIVE')
                                    @if ($rep && strpos($rep->reportname, 'Entrance') !== false)
                                        <b style="color:green;font-size:.75em">No Issues detected</b>
                                    @endif
                                @else
                                    @if ($rep && strpos($rep->reportname, 'Entrance') !== false)
                                        <b style="color:red;font-size:.75em">Potential Issues detected (Check Plots mapped)</b>
                                    @endif
                                @endif
                            </td>
                            <td>
                                <form action="iapprove" method="POST">
                                    @csrf
                                    <input type="hidden" name="iid" value="{{ $inspection->id }}">
                                    <textarea class="form-control form-control-sm" name="comments" rows="2">{{ $inspection->comments }}</textarea>
                            </td>
                            <td><b>{{ $inspection->getverificationstatus() }}</b></td>
                            <td>
                                    <div class="d-flex flex-column gap-1">
                                        <button name="viewsheet" type="submit" class="btn btn-success btn-sm"
                                                title="View Inspection Sheet">
                                            <i class="fa fa-eye"></i>
                                        </button>

                                        @if (in_array($inspection->inspectionstate, ['CONDITIONAL']) && $inspection->verifiedby === null)
                                            <button type="button" class="btn btn-info btn-sm"
                                                    data-bs-toggle="modal" data-bs-target="#verifyModal"
                                                    data-bs-whatever="{{ $inspection->getfarm()?->farmname }} : {{ $rep?->reportname }}"
                                                    data-bs-whatever2="{{ $inspection->id }}"
                                                    data-bs-conditions="{{ $inspection->conditions }}"
                                                    title="Verify Conditions">
                                                <i class="fa fa-search-plus"></i>
                                            </button>
                                        @endif

                                        @if (in_array($inspection->inspectionstate, ['SUBMITTED','PENDING','ACTIVE']))
                                            <button type="submit" name="approvebtn" class="btn btn-success btn-sm"
                                                    title="Approve (IMS Manager)">
                                                <i class="fa fa-check-square-o"></i>
                                            </button>
                                            <button type="submit" name="rejectbtn" class="btn btn-danger btn-sm"
                                                    title="Reject (IMS Manager)">
                                                <i class="fa fa-times-circle"></i>
                                            </button>
                                            <button type="submit" name="deletetbtn" class="btn btn-danger btn-sm"
                                                    title="Delete Inspection">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        @endif

                                        @if ($rep && strpos($rep->reportname, 'Entrance') === false && $inspection->ecomm_checked == 1)
                                            <button type="button" class="btn btn-warning btn-sm"
                                                    data-bs-toggle="modal" data-bs-target="#ecModal"
                                                    data-bs-whatever="{{ $inspection->getfarm()?->farmname }} : {{ $rep?->reportname }}"
                                                    data-bs-whatever2="{{ $inspection->id }}"
                                                    title="Evaluation Committee Decision">
                                                <i class="fa fa-check-square-o"></i>
                                            </button>
                                        @endif
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                                <i class="fa fa-inbox fa-2x mb-2 d-block"></i>
                                No Inspection Sheets on System.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Evaluation Committee Decision Modal --}}
    <div class="modal fade" id="ecModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Evaluation Committee Decision</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="post" action="iapprove">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Inspection Report</label>
                            <input type="text" readonly class="form-control fcode" name="report_name">
                            <input type="hidden" class="fiid" name="iid">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Committee Decision</label>
                            <select name="ecdecision" required class="form-select">
                                <option value="APPROVED">APPROVED</option>
                                <option value="CONDITIONAL">APPROVED WITH CONDITIONS</option>
                                <option value="REJECTED">REJECTED</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Conditions / Comments</label>
                            <textarea class="form-control" name="apprconditions" rows="6"></textarea>
                        </div>
                        <div class="mb-3">
                            @forelse ($approvalcommittees as $acmember)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                           name="acmembers[]" value="{{ $acmember->id }}"
                                           id="acm{{ $acmember->id }}">
                                    <label class="form-check-label" for="acm{{ $acmember->id }}">
                                        <b>{{ $acmember->name }}</b> ({{ $acmember->position }})
                                    </label>
                                </div>
                            @empty
                                <p class="text-muted small">No Committee Members for {{ $year }} on record.</p>
                            @endforelse

                            @if ($approvalcommittees->isNotEmpty())
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox"
                                           name="addcommittee" id="addAllCommittee">
                                    <label class="form-check-label" for="addAllCommittee">
                                        Add <u>ALL</u> Active Committee Members
                                    </label>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" name="approvewithcondition">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Verify Conditions Modal --}}
    <div class="modal fade" id="verifyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Verify Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="post" action="iapprove">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Inspection Report</label>
                            <input type="text" readonly class="form-control vcode" name="report_name_verify">
                            <input type="hidden" class="fiid" name="iid">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Conditions</label>
                            <textarea class="form-control" readonly name="apprconditions"
                                      id="approveconditions_verify" rows="6"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Verification Notes (Optional)</label>
                            <textarea class="form-control" name="verify_note" rows="4"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Verification Date</label>
                            <input type="date" name="verify_date" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" name="verifyinspection">Verify</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var ecModal = document.getElementById('ecModal');
        ecModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            ecModal.querySelector('.modal-title').textContent = 'Evaluation Committee Decision';
            ecModal.querySelector('.fcode').value = button.getAttribute('data-bs-whatever');
            ecModal.querySelector('.fiid').value  = button.getAttribute('data-bs-whatever2');
        });

        var verifyModal = document.getElementById('verifyModal');
        verifyModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            verifyModal.querySelector('.modal-title').textContent = 'Verify Conditions';
            verifyModal.querySelector('.vcode').value = button.getAttribute('data-bs-whatever');
            verifyModal.querySelector('.fiid').value  = button.getAttribute('data-bs-whatever2');
            document.getElementById('approveconditions_verify').value = button.getAttribute('data-bs-conditions');
        });
    </script>
    <script>
        $(document).ready(function () {
            var table = $('#reports').DataTable({
                dom: 'Bfrtip',
                pageLength: 200,
                order: [[0, 'desc']],
                stateSave: true,
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Inspection_Report_review',
                    exportOptions: { columns: [0,1,2,3,4,5,6,7,8] }
                }]
            });

            // Column-level filters
            $('#reports thead tr:eq(1) th').each(function (i) {
                $('input, select', this).on('keyup change', function () {
                    if (table.column(i).search() !== this.value) {
                        table.column(i).search(this.value).draw();
                    }
                });
            });

            $('#clearFilters').on('click', function () {
                $('#reports thead tr:eq(1) th').each(function (i) {
                    $('input, select', this).val('');
                    table.column(i).search('').draw();
                });
            });
        });
    </script>
</x-layouts.app>
