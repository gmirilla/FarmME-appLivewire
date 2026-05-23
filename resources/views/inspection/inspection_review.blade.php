<x-layouts.app>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

@php
    $year = date('Y');

    $statusBadge = fn($state) => match($state) {
        'APPROVED'    => 'bg-success',
        'CONDITIONAL' => 'bg-warning text-dark',
        'REJECTED'    => 'bg-danger',
        'SUBMITTED'   => 'bg-primary',
        'PENDING'     => 'bg-info text-dark',
        'ACTIVE'      => 'bg-success',
        default       => 'bg-secondary',
    };
@endphp

<style>
    .page-toolbar  { display: flex; align-items: center; justify-content: space-between;
                     flex-wrap: wrap; gap: .5rem; margin-bottom: 1.25rem; }
    .page-title    { font-size: 1.25rem; font-weight: 700; color: #212529; margin: 0; }
    .section-header { background:#f8f9fa; border-left:4px solid #0d6efd;
                      padding:6px 14px; font-weight:700; font-size:.78rem;
                      text-transform:uppercase; letter-spacing:.07em;
                      color:#495057; margin-bottom:.85rem; border-radius:0 4px 4px 0; }
    .score-bar     { height: 5px; background: #e9ecef; border-radius: 3px;
                     min-width: 60px; margin-top: 3px; }
    .score-fill    { height: 100%; border-radius: 3px; }
    .filter-label  { font-size: .78rem; font-weight: 600; color: #495057;
                     text-transform: uppercase; letter-spacing: .04em; }
    @media (max-width: 576px) { th, td { font-size: 0.75rem; padding: 0.25rem; } }
    .table td { word-wrap: break-word; }
</style>

{{-- ── VALIDATION ERRORS ─────────────────────────────────────────────────── --}}
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ── PAGE TOOLBAR ─────────────────────────────────────────────────────── --}}
<div class="page-toolbar">
    <h5 class="page-title">
        <i class="fa fa-clipboard me-2 text-primary"></i>Inspection Review
    </h5>
</div>

{{-- ── SUMMARY FILTER CARD ───────────────────────────────────────────────── --}}
<div class="card shadow-sm mb-4">
    <div class="card-header bg-light fw-bold" style="font-size:.85rem;text-transform:uppercase;letter-spacing:.05em">
        <i class="fa fa-bar-chart me-2 text-primary"></i>Generate Summary Report
    </div>
    <div class="card-body">
        <form action="{{ route('summarypage') }}" method="get">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="filter-label form-label">Season</label>
                    <select class="form-select" name="season">
                        @forelse ($seasons as $season)
                            <option value="{{ $season->season }}">{{ $season->season }}</option>
                        @empty
                            <option value="" disabled>No Season Available</option>
                        @endforelse
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label class="filter-label form-label">Report</label>
                    <select class="form-select" name="report">
                        @forelse ($reports as $report)
                            <option value="{{ $report->id }}">{{ $report->reportname }}</option>
                        @empty
                            <option value="" disabled>No Report Available</option>
                        @endforelse
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label class="filter-label form-label">Report State</label>
                    <select class="form-select" name="reportstate">
                        <option value="ALL">ALL</option>
                        <option value="APPROVED">APPROVED</option>
                        <option value="CONDITIONAL">APPROVED WITH CONDITIONS</option>
                        <option value="PENDING">PENDING</option>
                        <option value="SUBMITTED">SUBMITTED</option>
                        <option value="REJECTED">REJECTED</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <button class="btn btn-primary w-100" type="submit">
                        <i class="fa fa-bar-chart me-1"></i> Generate Summary
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ── INSPECTION TABLE CARD ─────────────────────────────────────────────── --}}
<div class="card shadow-sm">
    <div class="card-body table-responsive">
        <div class="d-flex justify-content-end mb-2">
            <button id="clearFilters" class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-times me-1"></i>Clear Filters
            </button>
        </div>
        <table class="table display table-sm table-striped table-hover align-middle" id="reports" style="width:100%">
            <thead class="table-dark">
                <tr>
                    <th style="width:8%">Season</th>
                    <th style="width:12%">Report Type</th>
                    <th style="width:8%">Filed By</th>
                    <th style="width:15%">Farm Name</th>
                    <th style="width:8%">Score</th>
                    <th style="width:7%">Status</th>
                    <th style="width:8%">Error Check</th>
                    <th style="width:12%">IMS Comment(s)</th>
                    <th style="width:6%">Verification</th>
                    <th style="width:8%">Actions</th>
                </tr>
                {{-- column filter row --}}
                <tr>
                    <th>
                        <select class="form-select form-select-sm" id="seasonFilter">
                            <option value="">All</option>
                            @forelse ($seasons as $season)
                                <option>{{ $season->season }}</option>
                            @empty
                                <option disabled>No seasons</option>
                            @endforelse
                        </select>
                    </th>
                    <th>
                        <select class="form-select form-select-sm" id="reportFilter">
                            <option value="">All</option>
                            @forelse ($reports as $report)
                                <option>{{ $report->reportname }}</option>
                            @empty
                                <option>No reports</option>
                            @endforelse
                        </select>
                    </th>
                    <th><input type="text" placeholder="Filed by…" class="form-control form-control-sm"/></th>
                    <th><input type="text" placeholder="Farm name…" class="form-control form-control-sm"/></th>
                    <th></th>
                    <th><input type="text" placeholder="Status…" class="form-control form-control-sm"/></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reportquestions as $inspection)
                    <tr>
                        <td class="small">{{ $inspection->season }}</td>
                        <td class="small">{{ $inspection->getreport()->reportname }}</td>
                        <td class="small">{{ $inspection->reportinspectorname()->iname }}</td>
                        <td class="small fw-semibold">{{ $inspection->getfarm()->farmname }}</td>
                        <td>
                            @if ($inspection->getreport()->max_score == 0)
                                <span class="badge bg-secondary">Check Report</span>
                            @else
                                @php
                                    $pct      = ($inspection->score / $inspection->getreport()->max_score) * 100;
                                    $barColor = $pct >= 70 ? '#198754' : ($pct >= 50 ? '#fd7e14' : '#dc3545');
                                @endphp
                                <div style="font-size:.78rem;font-weight:600">{{ number_format($pct, 1) }}%</div>
                                <div class="score-bar">
                                    <div class="score-fill" style="width:{{ $pct }}%;background:{{ $barColor }}"></div>
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $statusBadge($inspection->inspectionstate) }}" style="font-size:.7rem">
                                {{ $inspection->inspectionstate }}
                            </span>
                        </td>
                        <td class="d-none d-sm-table-cell small">
                            @if (!empty($inspection->getplotdetails()) || $inspection->inspectionstate == 'ACTIVE')
                                @if (strpos($inspection->getreport()->reportname, 'Entrance') != false)
                                    <span class="text-success"><i class="fa fa-check-circle me-1"></i>No Issues</span>
                                @endif
                            @else
                                @if (strpos($inspection->getreport()->reportname, 'Entrance') != false)
                                    <span class="text-danger"><i class="fa fa-exclamation-circle me-1"></i>Check Plots</span>
                                @endif
                            @endif
                        </td>
                        <td>
                            <form action="iapprove" method="POST">
                                @csrf
                                <input type="text" hidden name="iid" value="{{ $inspection->id }}">
                                <textarea class="form-control form-control-sm" name="comments"
                                          rows="2">{{ $inspection->comments }}</textarea>
                        </td>
                        <td class="small">
                            <b>{{ $inspection->getverificationstatus() }}</b>
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1">
                                {{-- View sheet --}}
                                <button name="viewsheet" type="submit" class="btn btn-success btn-sm"
                                        title="View Inspection Sheet">
                                    <i class="fa fa-eye"></i>
                                </button>

                                {{-- Verify (conditional, unverified) --}}
                                @if (in_array($inspection->inspectionstate, ['CONDITIONAL']) && $inspection->verifiedby == null)
                                    <button type="button" name="verifybtn" class="btn btn-info btn-sm"
                                            data-bs-toggle="modal" data-bs-target="#verifyModal"
                                            data-bs-whatever="{{ $inspection->farmname }} : {{ $inspection->reportname }}"
                                            data-bs-whatever2="{{ $inspection->id }}"
                                            data-bs-conditions="{{ $inspection->conditions }}"
                                            title="Verify Inspection">
                                        <i class="fa fa-search-plus"></i>
                                    </button>
                                @endif

                                {{-- Approve / Reject / Delete (submitted/pending) --}}
                                @if (in_array($inspection->inspectionstate, ['SUBMITTED', 'PENDING', 'ACTIVE']))
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

                                {{-- Evaluation Committee Decision --}}
                                @if (strpos($inspection->getreport()->reportname, 'Entrance') == false && $inspection->ecomm_checked == 1)
                                    <button type="button" name="approvewithconditionmodal"
                                            class="btn btn-warning btn-sm"
                                            data-bs-toggle="modal" data-bs-target="#exampleModal"
                                            data-bs-whatever="{{ $inspection->farmname }} : {{ $inspection->reportname }}"
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
                        <td colspan="10" class="text-center text-muted py-5">
                            <i class="fa fa-inbox fa-2x mb-2 d-block"></i>
                            No inspection records found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── MODAL: Evaluation Committee Decision ─────────────────────────────── --}}
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    <i class="fa fa-check-square-o me-2"></i>Evaluation Committee Decision
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="farmschedule" method="post" action="iapprove">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Inspection Report</label>
                        <input type="text" readonly class="form-control fcode" id="report_name" name="report_name">
                        <input type="text" class="form-control fiid" hidden name="iid">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Committee Decision</label>
                        <select name="ecdecision" id="ecdecision" required class="form-select">
                            <option value="APPROVED">APPROVED</option>
                            <option value="CONDITIONAL">APPROVED WITH CONDITIONS</option>
                            <option value="REJECTED">REJECTED</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Conditions / Comments</label>
                        <textarea class="form-control" name="apprconditions" id="approveconditions"
                                  rows="6"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Committee Members</label>
                        <div class="border rounded p-2">
                            @forelse ($approvalcommittees as $acmember)
                                <div class="form-check">
                                    <input type="checkbox" name="acmembers[]" value="{{ $acmember->id }}"
                                           class="form-check-input" id="ac_{{ $acmember->id }}">
                                    <label class="form-check-label" for="ac_{{ $acmember->id }}">
                                        <b>{{ $acmember->name }}</b>
                                        <span class="text-muted small">({{ $acmember->position }})</span>
                                    </label>
                                </div>
                            @empty
                                <p class="text-muted mb-0 small">No committee members for {{ $year }} on record.</p>
                            @endforelse
                        </div>
                        @if ($approvalcommittees->isNotEmpty())
                            <div class="form-check mt-2">
                                <input type="checkbox" name="addcommittee" class="form-check-input" id="addAllCommittee">
                                <label class="form-check-label" for="addAllCommittee">
                                    Add <u>ALL</u> active committee members to note
                                </label>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" name="approvewithcondition">
                        <i class="fa fa-check me-1"></i> Save Decision
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── MODAL: Verify Conditions ─────────────────────────────────────────── --}}
<div class="modal fade" id="verifyModal" tabindex="-1" aria-labelledby="verifyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verifyModalLabel">
                    <i class="fa fa-search-plus me-2"></i>Verify Conditions
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="verifyinspection" method="post" action="iapprove">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Inspection Report</label>
                        <input type="text" readonly class="form-control vcode" id="report_name_verify"
                               name="report_name_verify">
                        <input type="text" class="form-control fiid" hidden name="iid">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Approval Conditions</label>
                        <textarea class="form-control" readonly name="apprconditions"
                                  id="approveconditions_verify" rows="5"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Verification Notes <span class="text-muted fw-normal">(Optional)</span></label>
                        <textarea class="form-control" name="verify_note" id="verify_note" rows="4"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Verification Date</label>
                        <input type="date" name="verify_date" id="verify_date" class="form-control"
                               value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" name="verifyinspection">
                        <i class="fa fa-check me-1"></i> Verify
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Evaluation Committee Decision modal
    var exampleModal = document.getElementById('exampleModal');
    exampleModal.addEventListener('show.bs.modal', function (event) {
        var button        = event.relatedTarget;
        var recipient     = button.getAttribute('data-bs-whatever');
        var reportid      = button.getAttribute('data-bs-whatever2');
        var modalTitle    = exampleModal.querySelector('.modal-title');
        var modalBodyInput    = exampleModal.querySelector('.fcode');
        var modalBodyReportid = exampleModal.querySelector('.fiid');

        modalTitle.innerHTML  = '<i class="fa fa-check-square-o me-2"></i>Evaluation Committee Decision';
        modalBodyInput.value    = recipient;
        modalBodyReportid.value = reportid;
    });

    // Verify Conditions modal
    var verifyModal = document.getElementById('verifyModal');
    verifyModal.addEventListener('show.bs.modal', function (event) {
        var button     = event.relatedTarget;
        var recipient  = button.getAttribute('data-bs-whatever');
        var reportid   = button.getAttribute('data-bs-whatever2');
        var conditions = button.getAttribute('data-bs-conditions');
        var modalTitle         = verifyModal.querySelector('.modal-title');
        var modalBodyInput     = verifyModal.querySelector('.vcode');
        var modalBodyReportid  = verifyModal.querySelector('.fiid');
        var modalBodyConditions = verifyModal.querySelector('#approveconditions_verify');

        modalTitle.innerHTML    = '<i class="fa fa-search-plus me-2"></i>Verify Conditions &mdash; ' + recipient;
        modalBodyInput.value    = recipient;
        modalBodyReportid.value = reportid;
        modalBodyConditions.value = conditions;
    });
</script>

<script>
    $(document).ready(function () {
        var table = $('#reports').DataTable({
            dom: 'Bfrtip',
            pageLength: 200,
            order: [[1, 'asc']],
            stateSave: true,
            buttons: [{
                extend: 'excelHtml5',
                title: 'Inspection_Report_Review',
                exportOptions: { columns: ':visible' }
            }]
        });

        // Apply column filters from the second thead row
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
