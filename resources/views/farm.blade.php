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
    $user      = auth()->user();
    $isAdmin   = $user->roles === 'ADMINISTRATOR';

    $total     = $farmlist->count();
    $active    = $farmlist->where('farmstate', 'ACTIVE')->count();
    $pending   = $farmlist->where('farmstate', 'PENDING')->count();
    $closed    = $farmlist->where('farmstate', 'CLOSED')->count();

    $statusBadge = fn($state) => match($state) {
        'ACTIVE'    => 'bg-success',
        'PENDING'   => 'bg-warning text-dark',
        'CLOSED'    => 'bg-secondary',
        'REMEDIAL'  => 'bg-danger',
        'DISABLED'  => 'bg-dark',
        default     => 'bg-light text-dark border',
    };
@endphp

<style>
    .metric-card   { border-left: 4px solid #198754; }
    .metric-num    { font-size: 1.8rem; font-weight: 700; line-height: 1.1; }
    .metric-sub    { font-size: .75rem; color: #6c757d; }
    .page-toolbar  { display: flex; align-items: center; justify-content: space-between;
                     flex-wrap: wrap; gap: .5rem; margin-bottom: 1.25rem; }
    .page-title    { font-size: 1.25rem; font-weight: 700; color: #212529; margin: 0; }
    .conditional-pill { font-size: .65rem; vertical-align: middle; }
</style>

{{-- ── PAGE TOOLBAR ─────────────────────────────────────────────────────── --}}
<div class="page-toolbar">
    <h5 class="page-title"><i class="fa fa-leaf me-2 text-success"></i>Farm Registry</h5>

    @if ($isAdmin)
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('season.index') }}" class="btn btn-warning btn-sm">
                <i class="fa fa-calendar me-1"></i> Season Management
            </a>
            <a href="{{ route('import_list') }}" class="btn btn-outline-success btn-sm">
                <i class="fa fa-upload me-1"></i> Import Farmers
            </a>
            <a href="/newfarm" class="btn btn-success btn-sm">
                <i class="fa fa-plus me-1"></i> Register New Farmer
            </a>
        </div>
    @endif
</div>

{{-- ── METRIC CARDS ─────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-3">
    <div class="col-6 col-md-3">
        <div class="card metric-card shadow-sm h-100">
            <div class="card-body">
                <div class="metric-sub mb-1">Total Farms</div>
                <div class="metric-num text-success">{{ $total }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card metric-card h-100 shadow-sm" style="border-left-color:#198754">
            <div class="card-body">
                <div class="metric-sub mb-1">Active</div>
                <div class="metric-num text-success">{{ $active }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card metric-card h-100 shadow-sm" style="border-left-color:#fd7e14">
            <div class="card-body">
                <div class="metric-sub mb-1">Pending</div>
                <div class="metric-num" style="color:#fd7e14">{{ $pending }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card metric-card h-100 shadow-sm" style="border-left-color:#6c757d">
            <div class="card-body">
                <div class="metric-sub mb-1">Closed</div>
                <div class="metric-num text-secondary">{{ $closed }}</div>
            </div>
        </div>
    </div>
</div>

{{-- ── FARM TABLE ───────────────────────────────────────────────────────── --}}
<div class="card shadow-sm">
    <div class="card-body table-responsive">
        <table class="table table-striped table-hover table-sm align-middle display"
               id="farms" style="width:100%">
            <thead class="table-dark">
                <tr>
                    <th>Community</th>
                    <th>Farm Code</th>
                    <th>Farmer Name</th>
                    @if ($isAdmin)
                        <th>Inspector</th>
                    @endif
                    <th>Last Inspection</th>
                    <th>Next Inspection</th>
                    <th>Status</th>
                    <th style="width:90px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($farmlist as $farm)
                    <tr>
                        <td class="small">{{ $farm->community }}</td>
                        <td>
                            <span class="fw-bold text-success font-monospace">{{ $farm->farmcode }}</span>
                        </td>
                        <td>
                            {{ $farm->farmname }}
                            @if ($farm->getlastreport() === 'CONDITIONAL')
                                <span class="badge bg-warning text-dark conditional-pill ms-1">
                                    Conditional
                                </span>
                            @endif
                        </td>
                        @if ($isAdmin)
                            <td class="small text-muted">{{ $farm->getinspectorName() }}</td>
                        @endif
                        <td class="small">{{ $farm->lastinspection ?? '—' }}</td>
                        <td class="small">{{ $farm->nextinspection ?? '—' }}</td>
                        <td>
                            <span class="badge {{ $statusBadge($farm->farmstate) }}">
                                {{ $farm->farmstate ?? 'UNKNOWN' }}
                            </span>
                        </td>
                        <td>
                            @if ($isAdmin && $farm->inspectorid)
                                <button type="button"
                                        class="btn btn-success btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#scheduleModal"
                                        data-bs-farmcode="{{ $farm->farmcode }}"
                                        title="Schedule Inspection">
                                    <i class="fa fa-calendar"></i>
                                </button>
                            @endif
                            <a href="/farm/view?id={{ $farm->farmcode }}"
                               class="btn btn-outline-success btn-sm"
                               title="View Farm">
                                <i class="fa fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $isAdmin ? 8 : 7 }}" class="text-center text-muted py-5">
                            <i class="fa fa-inbox fa-2x mb-2 d-block"></i>
                            No farms found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── SCHEDULE INSPECTION MODAL ────────────────────────────────────────── --}}
@if ($isAdmin)
<div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scheduleModalLabel">
                    <i class="fa fa-calendar me-2"></i>Schedule Inspection
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="farmschedule" method="post" action="/farm/schedule">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Farm Code</label>
                        <input type="text" readonly class="form-control fcode" id="farmcode" name="farmcode">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Inspection Type</label>
                        <select class="form-select" id="scheduletype" name="inspectiontype">
                            @forelse ($reports as $report)
                                <option value="{{ $report->id }}">{{ $report->reportname }}</option>
                            @empty
                                <option value="0">No Reports Available</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Inspection Date</label>
                        <input type="date" class="form-control" id="scheduledate" name="newinspectiondate">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check me-1"></i> Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script>
    // Populate farm code into the schedule modal
    const scheduleModal = document.getElementById('scheduleModal');
    if (scheduleModal) {
        scheduleModal.addEventListener('show.bs.modal', function (event) {
            const farmcode = event.relatedTarget.getAttribute('data-bs-farmcode');
            scheduleModal.querySelector('.modal-title').innerHTML =
                '<i class="fa fa-calendar me-2"></i>Schedule Inspection &mdash; ' + farmcode;
            scheduleModal.querySelector('#farmcode').value = farmcode;
        });
    }

    $(document).ready(function () {
        $('#farms').DataTable({
            dom: 'Bfrtip',
            pageLength: 50,
            order: [[1, 'asc']],
            stateSave: true,
            buttons: [{
                extend: 'excelHtml5',
                title: 'Farmers_Report',
                exportOptions: { columns: ':not(:last-child)' }
            }]
        });
    });
</script>
</x-layouts.app>
