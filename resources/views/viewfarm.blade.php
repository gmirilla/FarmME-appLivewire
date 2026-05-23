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
    $authuser = Auth::user();
    $isAdmin  = $authuser->roles === 'ADMINISTRATOR';

    // Score helpers
    $lastScore    = null;
    $lastMaxScore = null;
    $lastPct      = null;
    if ($lastreport) {
        for ($i = 0; $i < $farmreports->count(); $i++) {
            if ($farmreports[$i]->iid == $lastreport->id) {
                $lastMaxScore = $farmreports[$i]->max_score;
                break;
            }
        }
        $lastPct = ($lastMaxScore > 0)
            ? number_format(($lastreport->score / $lastMaxScore) * 100, 2)
            : 0;
    }

    $scoreColor = fn($pct) => match(true) {
        $pct <= 49.99            => '#dc3545',
        $pct <= 69.99            => '#fd7e14',
        default                  => '#198754',
    };

    $statusBadge = fn($state) => match($state) {
        'APPROVED'    => 'bg-success',
        'CONDITIONAL' => 'bg-warning text-dark',
        'REJECTED'    => 'bg-danger',
        'SUBMITTED'   => 'bg-info text-dark',
        'ACTIVE'      => 'bg-success',
        'PENDING'     => 'bg-warning text-dark',
        'CLOSED'      => 'bg-secondary',
        default       => 'bg-secondary',
    };
@endphp

<style>
    .info-label  { font-size: .7rem; font-weight: 700; text-transform: uppercase;
                   letter-spacing: .05em; color: #6c757d; margin-bottom: 2px; }
    .info-value  { font-size: .95rem; color: #212529; margin-bottom: 0; }
    .metric-card { border-left: 4px solid #198754; }
    .metric-num  { font-size: 1.6rem; font-weight: 700; line-height: 1.1; }
    .metric-sub  { font-size: .75rem; color: #6c757d; }
    .score-bar   { height: 8px; border-radius: 4px; background: #e9ecef; }
    .score-fill  { height: 8px; border-radius: 4px; transition: width .6s ease; }
    .farmer-photo{ width: 80px; height: 80px; object-fit: cover;
                   border-radius: 50%; border: 3px solid #198754; }
    .farmer-photo-placeholder { width:80px;height:80px;border-radius:50%;
                   background:#e9ecef;display:flex;align-items:center;
                   justify-content:center;border:3px solid #dee2e6; }
    .section-header { background: #f8f9fa; border-left: 4px solid #198754;
                      padding: 8px 14px; font-weight: 700; font-size: .85rem;
                      text-transform: uppercase; letter-spacing: .06em;
                      color: #495057; margin-bottom: 1rem; border-radius: 0 4px 4px 0; }
    .action-btn  { font-size: .8rem; }
</style>

{{-- ── FARM HEADER ──────────────────────────────────────────────────────── --}}
<div class="card mb-3 border-0 shadow-sm">
    <div class="card-body py-3">
        <div class="d-flex align-items-center gap-3 flex-wrap">

            {{-- Farmer photo --}}
            <div class="position-relative flex-shrink-0">
                @if (!empty($farmerpicture))
                    <img src="{{ Request::root() . '/storage/' . $farmerpicture->farmerpicture }}"
                         alt="Farmer" class="farmer-photo">
                @else
                    <div class="farmer-photo-placeholder">
                        <i class="fa fa-user fa-2x text-secondary"></i>
                    </div>
                @endif

                @if ($farmdetails->getlastreport() === 'CONDITIONAL')
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark"
                          title="Under Conditional Approval" style="font-size:.6rem">
                        Conditional
                    </span>
                @endif

                <button type="button" class="btn btn-sm btn-success position-absolute bottom-0 end-0 rounded-circle p-1"
                        data-bs-toggle="modal" data-bs-target="#farmerPictureModal"
                        title="Update photo" style="width:24px;height:24px;line-height:1">
                    <i class="fa fa-camera" style="font-size:.65rem"></i>
                </button>
            </div>

            {{-- Identity --}}
            <div class="flex-grow-1">
                <h4 class="mb-0 fw-bold">{{ $farm->farmname }}</h4>
                <div class="text-muted small">
                    <span class="me-3"><i class="fa fa-barcode me-1"></i>{{ $farm->farmcode }}</span>
                    <span><i class="fa fa-map-marker me-1"></i>{{ $farm->community }}</span>
                </div>
            </div>

            {{-- Status badge + admin controls --}}
            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                <span class="badge fs-6 px-3 py-2 {{ $statusBadge($farm->farmstate) }}">
                    {{ $farm->farmstate ?? 'UNKNOWN' }}
                </span>
                @if ($isAdmin)
                    <button type="button" class="btn btn-outline-secondary btn-sm action-btn"
                            data-bs-toggle="modal" data-bs-target="#farmModal"
                            title="Change farm status">
                        <i class="fa fa-pencil-square-o me-1"></i> Status
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ── METRIC CARDS ─────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-3">
    <div class="col-6 col-md-3">
        <div class="card metric-card h-100 shadow-sm">
            <div class="card-body">
                <div class="metric-sub mb-1">Farm Area ({{ $farm->measurement ?? 'Ha' }})</div>
                <div class="metric-num text-success">
                    {{ number_format($farmdetails->getfarmareacurrent(), 2) }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card metric-card h-100 shadow-sm">
            <div class="card-body">
                <div class="metric-sub mb-1">No. of Plots</div>
                <div class="metric-num text-success">{{ $farmdetails->getfarmcount() ?: '—' }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card metric-card h-100 shadow-sm">
            <div class="card-body">
                <div class="metric-sub mb-1">Last Inspection Score</div>
                @if ($lastPct !== null)
                    <div class="metric-num" style="color: {{ $scoreColor($lastPct) }}">{{ $lastPct }}%</div>
                    <div class="score-bar mt-1">
                        <div class="score-fill" style="width:{{ $lastPct }}%; background:{{ $scoreColor($lastPct) }}"></div>
                    </div>
                @else
                    <div class="metric-num text-muted">—</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card metric-card h-100 shadow-sm">
            <div class="card-body">
                <div class="metric-sub mb-1">Next Inspection</div>
                <div class="metric-num" style="font-size:1.1rem">
                    {{ $farm->nextinspection ?? '—' }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── MAIN TWO-COLUMN SECTION ─────────────────────────────────────────── --}}
<div class="row g-3 mb-3">

    {{-- LEFT: Farm details --}}
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <div class="section-header">Farm Information</div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <div class="info-label">Crop</div>
                        <div class="info-value">{{ $farm->crop ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="info-label">Variety</div>
                        <div class="info-value">{{ $farm->cropvariety ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="info-label">House Latitude</div>
                        <div class="info-value">{{ $farm->latitude ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="info-label">House Longitude</div>
                        <div class="info-value">{{ $farm->longitude ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="info-label">Last Inspection</div>
                        <div class="info-value">{{ $farm->lastinspection ?? '—' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="info-label">Year of Certification</div>
                        <div class="info-value">{{ $farm->yearofcertification ?? '—' }}</div>
                    </div>
                </div>

                <a href="{{ route('edit_farmunit') }}?farmid={{ $farm->id }}"
                   class="btn btn-outline-success btn-sm action-btn">
                    <i class="fa fa-pencil me-1"></i> Edit Farm Details
                </a>
            </div>
        </div>
    </div>

    {{-- RIGHT: Inspector, contract, actions --}}
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex flex-column">

                {{-- Field staff --}}
                <div class="section-header">Field Staff</div>
                <div class="d-flex align-items-center gap-2 mb-4">
                    <div class="flex-grow-1">
                        <div class="info-label">Assigned Inspector</div>
                        <div class="info-value fw-bold">
                            {{ $farm->name ?? 'No Field Staff Assigned' }}
                        </div>
                    </div>
                    @if ($isAdmin)
                        <button type="button" class="btn btn-outline-secondary btn-sm action-btn flex-shrink-0"
                                data-bs-toggle="modal" data-bs-target="#staffModal">
                            <i class="fa fa-pencil-square-o me-1"></i> Reassign
                        </button>
                    @endif
                </div>

                {{-- Last inspection status --}}
                <div class="section-header">Last Inspection</div>
                @if ($lastreport)
                    <div class="d-flex gap-3 align-items-start mb-4">
                        <div>
                            <div class="info-label">State</div>
                            <span class="badge {{ $statusBadge($lastreport->inspectionstate) }} mt-1">
                                {{ $lastreport->inspectionstate }}
                            </span>
                        </div>
                        <div>
                            <div class="info-label">Score</div>
                            <span class="fw-bold" style="color: {{ $scoreColor($lastPct ?? 0) }}">
                                {{ $lastPct ?? '—' }}%
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <div class="info-label">Comment</div>
                            <div class="info-value small text-muted">
                                {{ $lastreport->comments ?? '—' }}
                            </div>
                        </div>
                    </div>
                @else
                    <p class="text-muted small mb-4">No inspections on record.</p>
                @endif

                {{-- Contract --}}
                <div class="section-header">Contract Agreement</div>
                <form action="{{ route('generatecontract') }}" method="get">
                    <input type="hidden" name="farmid" value="{{ $farm->id }}">
                    <div class="d-flex gap-2 align-items-center flex-wrap">
                        <select name="cdseason" class="form-select form-select-sm" style="max-width:160px" required>
                            @forelse ($seasons as $season)
                                @if (!empty($season))
                                    <option value="{{ $season }}">{{ $season }}</option>
                                @endif
                            @empty
                                <option value="">No entrances on record</option>
                            @endforelse
                        </select>
                        @if (!empty($season))
                            <a href="{{ route('viewcontract', ['farmid' => $farm->id, 'cdseason' => $season]) }}"
                               class="btn btn-outline-success btn-sm action-btn">
                                <i class="fa fa-file-text-o me-1"></i> View
                            </a>
                            <button type="submit" class="btn btn-success btn-sm action-btn">
                                <i class="fa fa-download me-1"></i> Download
                            </button>
                        @else
                            <button class="btn btn-outline-secondary btn-sm" disabled>No Contract Available</button>
                        @endif
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

{{-- ── INSPECTION HISTORY TABLE ─────────────────────────────────────────── --}}
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="section-header">Inspection History</div>
        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm align-middle" id="farms" style="width:100%">
                <thead class="table-dark">
                    <tr>
                        <th>Season</th>
                        <th>Report</th>
                        <th>Score</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Comment</th>
                        <th style="width:90px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($farmreports as $farmreport)
                        @php
                            $pct = $farmreport->max_score > 0
                                ? number_format(($farmreport->score / $farmreport->max_score) * 100, 2)
                                : 0;
                        @endphp
                        <tr>
                            <td><span class="badge bg-light text-dark border">{{ $farmreport->season ?? '—' }}</span></td>
                            <td class="small">{{ $farmreport->reportname }}</td>
                            <td style="min-width:100px">
                                @if ($farmreport->max_score == 0)
                                    <span class="text-muted small">N/A</span>
                                @else
                                    <span class="fw-bold small" style="color: {{ $scoreColor($pct) }}">{{ $pct }}%</span>
                                    <div class="score-bar mt-1">
                                        <div class="score-fill" style="width:{{ $pct }}%; background:{{ $scoreColor($pct) }}"></div>
                                    </div>
                                @endif
                            </td>
                            <td class="small text-nowrap">{{ \Carbon\Carbon::parse($farmreport->created_at)->format('d M Y') }}</td>
                            <td>
                                <span class="badge {{ $statusBadge($farmreport->inspectionstate) }}">
                                    {{ $farmreport->inspectionstate }}
                                </span>
                            </td>
                            <td class="small text-muted" style="max-width:180px">
                                {{ $farmreport->comments ?? '—' }}
                            </td>
                            <td>
                                <form action="/iapprove" method="post" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="iid" value="{{ $farmreport->iid }}">
                                    <button name="viewsheet" type="submit" class="btn btn-success btn-sm"
                                            title="View inspection sheet">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </form>

                                <form action="{{ route('continue') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" value="{{ $farm->id }}" name="farmid">
                                    <input type="hidden" value="{{ $farmreport->iid }}" name="inspectionid">
                                    <button class="btn btn-outline-danger btn-sm" name="printsheet"
                                            title="Download PDF">
                                        <i class="fa fa-file-pdf-o"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fa fa-inbox fa-2x mb-2 d-block"></i>
                                No inspections on record for this farm.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ── MODALS ────────────────────────────────────────────────────────────── --}}

{{-- Assign Staff --}}
<div class="modal fade" id="staffModal" tabindex="-1" aria-labelledby="staffModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staffModalLabel">
                    <i class="fa fa-user me-2"></i>Reassign Field Staff
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/farm/assignstaff" method="post">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Farm</label>
                        <input type="text" class="form-control" value="{{ $farm->farmname }}" disabled>
                        <input type="hidden" name="id" value="{{ $farm->farmcode }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select Staff Member</label>
                        <select class="form-select" name="staffid">
                            @forelse ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ $farm->uid == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @empty
                                <option value="">No users on system</option>
                            @endforelse
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-success" name="assignstaff">
                        <i class="fa fa-check me-1"></i> Save Assignment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Update Farm Status --}}
<div class="modal fade" id="farmModal" tabindex="-1" aria-labelledby="farmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="farmModalLabel">
                    <i class="fa fa-edit me-2"></i>Update Farm Status
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/farm/assignstaff" method="post">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Farm</label>
                        <input type="text" class="form-control" value="{{ $farm->farmname }}" disabled>
                        <input type="hidden" name="id" value="{{ $farm->farmcode }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">New Status</label>
                        <select class="form-select" name="farmid">
                            @foreach (['PENDING','ACTIVE','REMEDIAL','DISABLED','CLOSED'] as $state)
                                <option value="{{ $state }}"
                                    {{ $farm->farmstate === $state ? 'selected' : '' }}>
                                    {{ $state }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-success" name="farmstatus">
                        <i class="fa fa-check me-1"></i> Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Update Farmer Photo --}}
<div class="modal fade" id="farmerPictureModal" tabindex="-1" aria-labelledby="farmerPictureModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="farmerPictureModalLabel">
                    <i class="fa fa-camera me-2"></i>Update Farmer Photo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('updatepicture') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning d-flex align-items-start gap-2 small">
                        <i class="fa fa-exclamation-triangle mt-1"></i>
                        <span>Uploading a new image will <strong>replace</strong> the existing photo on the last Farm Entrance record.</span>
                    </div>
                    <input type="hidden" name="fcode" value="{{ $farm->farmcode }}">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Choose Photo</label>
                        <input type="file" name="farmerpicture" accept="image/*" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" name="newfarmerpicture">
                        <i class="fa fa-upload me-1"></i> Upload Photo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#farms').DataTable({
            dom: 'Bfrtip',
            pageLength: 25,
            order: [[3, 'desc']],
            buttons: [{
                extend: 'excelHtml5',
                title: 'Inspection_History_{{ $farm->farmcode }}',
                exportOptions: { columns: ':not(:last-child)' }
            }]
        });
    });
</script>
</x-layouts.app>
