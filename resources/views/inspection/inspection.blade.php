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
    $statusBadge = fn($state) => match($state) {
        'APPROVED'    => 'bg-success',
        'CONDITIONAL' => 'bg-warning text-dark',
        'REJECTED'    => 'bg-danger',
        'SUBMITTED'   => 'bg-primary',
        'PENDING'     => 'bg-info text-dark',
        'ACTIVE'      => 'bg-success',
        default       => 'bg-secondary',
    };

    $total      = $inspections->count();
    $inProgress = $inspections->whereIn('inspectionstate', ['ACTIVE', 'PENDING'])->count();
    $submitted  = $inspections->where('inspectionstate', 'SUBMITTED')->count();
    $reviewed   = $inspections->whereIn('inspectionstate', ['APPROVED', 'CONDITIONAL', 'REJECTED'])->count();
@endphp

<style>
    .metric-card  { border-left: 4px solid #198754; }
    .metric-num   { font-size: 1.8rem; font-weight: 700; line-height: 1.1; }
    .metric-sub   { font-size: .75rem; color: #6c757d; }
    .page-toolbar { display: flex; align-items: center; justify-content: space-between;
                    flex-wrap: wrap; gap: .5rem; margin-bottom: 1.25rem; }
    .page-title   { font-size: 1.25rem; font-weight: 700; color: #212529; margin: 0; }
    .score-bar    { height: 5px; background: #e9ecef; border-radius: 3px;
                    min-width: 60px; margin-top: 3px; }
    .score-fill   { height: 100%; border-radius: 3px; }
</style>

{{-- ── PAGE TOOLBAR ─────────────────────────────────────────────────────── --}}
<div class="page-toolbar">
    <h5 class="page-title">
        <i class="fa fa-clipboard me-2 text-success"></i>Inspections
    </h5>
    <a href="inspection/new" class="btn btn-success btn-sm">
        <i class="fa fa-plus me-1"></i> New Inspection
    </a>
</div>

{{-- ── METRIC CARDS ─────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card metric-card shadow-sm h-100">
            <div class="card-body">
                <div class="metric-sub mb-1">Total</div>
                <div class="metric-num text-success">{{ $total }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card metric-card shadow-sm h-100" style="border-left-color:#fd7e14">
            <div class="card-body">
                <div class="metric-sub mb-1">In Progress</div>
                <div class="metric-num" style="color:#fd7e14">{{ $inProgress }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card metric-card shadow-sm h-100" style="border-left-color:#0d6efd">
            <div class="card-body">
                <div class="metric-sub mb-1">Submitted</div>
                <div class="metric-num" style="color:#0d6efd">{{ $submitted }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card metric-card shadow-sm h-100" style="border-left-color:#6c757d">
            <div class="card-body">
                <div class="metric-sub mb-1">Reviewed</div>
                <div class="metric-num text-secondary">{{ $reviewed }}</div>
            </div>
        </div>
    </div>
</div>

{{-- ── INSPECTION TABLE ─────────────────────────────────────────────────── --}}
<div class="card shadow-sm">
    <div class="card-body table-responsive">
        <table class="table table-striped table-hover table-sm align-middle display"
               id="inspectiondt" style="width:100%">
            <thead class="table-dark">
                <tr>
                    <th>Season</th>
                    <th>Report Type</th>
                    <th>Farm</th>
                    <th>Score</th>
                    <th>Status</th>
                    <th>Last Updated</th>
                    <th style="width:110px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($inspections as $inspection)
                    <tr>
                        <td class="small text-muted">{{ $inspection->season }}</td>
                        <td class="small">{{ $inspection->reportname }}</td>
                        <td>
                            <span class="fw-bold text-success font-monospace small">{{ $inspection->farmcode }}</span>
                            <div class="small text-muted">{{ $inspection->farmname }}</div>
                        </td>
                        <td>
                            @if ($inspection->max_score == 0)
                                <span class="text-muted small">N/A</span>
                            @else
                                @php
                                    $pct      = ($inspection->score / $inspection->max_score) * 100;
                                    $barColor = $pct >= 70 ? '#198754' : ($pct >= 50 ? '#fd7e14' : '#dc3545');
                                @endphp
                                <div style="font-size:.78rem;font-weight:600;color:{{ $barColor }}">
                                    {{ number_format($pct, 1) }}%
                                </div>
                                <div class="score-bar">
                                    <div class="score-fill" style="width:{{ $pct }}%;background:{{ $barColor }}"></div>
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $statusBadge($inspection->inspectionstate) }}"
                                  style="font-size:.7rem">
                                {{ $inspection->inspectionstate }}
                            </span>
                        </td>
                        <td class="small text-muted">
                            {{ \Carbon\Carbon::parse($inspection->updated_at)->format('d M Y') }}
                        </td>
                        <td>
                            {{-- Continue / View / PDF — all share the same POST target --}}
                            <form action="inspection/continue" method="POST">
                                @csrf
                                <input type="hidden" name="farmid"       value="{{ $inspection->id }}">
                                <input type="hidden" name="inspectionid" value="{{ $inspection->iid }}">
                                <input type="hidden" name="reportid"     value="{{ $inspection->reportid }}">
                                <div class="d-flex flex-wrap gap-1">
                                    @if (in_array($inspection->inspectionstate, ['PENDING', 'ACTIVE']))
                                        <button type="submit" class="btn btn-warning btn-sm"
                                                title="Continue Inspection">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </button>
                                    @endif
                                    <button type="submit" name="viewsheet"
                                            class="btn btn-success btn-sm" title="View Inspection Sheet">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    <button type="submit" name="printsheet"
                                            class="btn btn-outline-danger btn-sm" title="Download PDF">
                                        <i class="fa fa-file-pdf-o"></i>
                                    </button>
                                </div>
                            </form>

                            {{-- Cancel + Change Date (ACTIVE inspections only) --}}
                            @if ($inspection->inspectionstate === 'ACTIVE')
                                <div class="d-flex flex-wrap gap-1 mt-1">
                                    <form action="{{ route('icancel') }}" method="POST"
                                          onsubmit="return confirm('Cancel this inspection? This cannot be undone.')">
                                        @csrf
                                        <input type="hidden" name="farmid"       value="{{ $inspection->id }}">
                                        <input type="hidden" name="inspectionid" value="{{ $inspection->iid }}">
                                        <input type="hidden" name="reportid"     value="{{ $inspection->reportid }}">
                                        <button type="submit" name="deletetbtn"
                                                class="btn btn-danger btn-sm" title="Cancel Inspection">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                            data-bs-toggle="modal" data-bs-target="#changeDateModal"
                                            data-bs-farmcode="{{ $inspection->farmcode }}"
                                            data-bs-orgdate="{{ $inspection->inspectiondate }}"
                                            data-bs-reportname="{{ $inspection->reportname }}"
                                            data-bs-inspectionid="{{ $inspection->iid }}"
                                            title="Change Inspection Date">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="fa fa-inbox fa-2x mb-2 d-block"></i>
                            No inspections on record for this season.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── MODAL: Change Inspection Date ───────────────────────────────────── --}}
<div class="modal fade" id="changeDateModal" tabindex="-1"
     aria-labelledby="changeDateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changeDateModalLabel">
                    <i class="fa fa-calendar me-2"></i>Change Inspection Date
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="updateinspectiondate" method="post" action="{{ route('changedate') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Inspection Type</label>
                        <input type="text" disabled class="form-control itype" name="inspectiontype">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Current Date</label>
                        <input type="text" disabled class="form-control orgdate" name="orgdate">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">New Date</label>
                        <input type="date" class="form-control" required name="newinspectiondate">
                        <input type="hidden" class="inspectionid" name="inspectionid">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check me-1"></i> Update Date
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var changeDateModal = document.getElementById('changeDateModal');
    changeDateModal.addEventListener('show.bs.modal', function (event) {
        var button       = event.relatedTarget;
        var farmcode     = button.getAttribute('data-bs-farmcode');
        var orgdate      = button.getAttribute('data-bs-orgdate');
        var reportname   = button.getAttribute('data-bs-reportname');
        var inspectionid = button.getAttribute('data-bs-inspectionid');

        changeDateModal.querySelector('.modal-title').innerHTML =
            '<i class="fa fa-calendar me-2"></i>Change Inspection Date &mdash; ' + farmcode;
        changeDateModal.querySelector('.itype').value        = reportname;
        changeDateModal.querySelector('.orgdate').value      = orgdate;
        changeDateModal.querySelector('.inspectionid').value = inspectionid;
    });
</script>

<script>
    $(document).ready(function () {
        $('#inspectiondt').DataTable({
            dom: 'Bfrtip',
            pageLength: 50,
            order: [[5, 'desc']],
            stateSave: true,
            buttons: [{
                extend: 'excelHtml5',
                title: 'Inspections_Report',
                exportOptions: { columns: ':not(:last-child)' }
            }]
        });
    });
</script>

</x-layouts.app>
