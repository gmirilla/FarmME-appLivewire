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
    .info-label { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:#6c757d; margin-bottom:2px; }
    .info-value  { font-size:.95rem; color:#212529; margin-bottom:.75rem; }
    .section-header { background:#f8f9fa; border-left:4px solid #198754; padding:8px 14px; font-weight:700;
        font-size:.85rem; text-transform:uppercase; letter-spacing:.06em; color:#495057;
        margin-bottom:1rem; border-radius:0 4px 4px 0; }
    .farmer-photo { width:80px; height:80px; object-fit:cover; border-radius:50%; border:3px solid #198754; }
    .score-bar  { height:8px; border-radius:4px; background:#e9ecef; }
    .score-fill { height:8px; border-radius:4px; transition:width .6s ease; }
    .metric-card { border-left:4px solid #198754; }
</style>

@php
    Auth::check();
    $user = Auth::user();

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

    $scoreColor = fn($pct) => match(true) {
        $pct <= 49.99 => '#dc3545',
        $pct <= 69.99 => '#fd7e14',
        default       => '#198754',
    };

    $farmArea   = number_format($farmdetails->getfarmareacurrent(), 2);
    $plotCount  = $farmdetails->getfarmcount();

    // Compute last inspection score
    $lastScore = null;
    $lastScorePct = 0;
    if ($lastreport) {
        foreach ($farmreports as $fr) {
            if ($fr->iid == $lastreport->id && $fr->max_score > 0) {
                $lastScorePct = round(($lastreport->score / $fr->max_score) * 100, 2);
                $lastScore = $lastScorePct;
                break;
            }
        }
    }
@endphp

{{-- ── Header Card ── --}}
<div class="card mb-3">
    <div class="card-body d-flex align-items-center gap-3 flex-wrap">
        <div class="position-relative flex-shrink-0">
            @if (!empty($farmerpicture))
                <img src="{{ Request::root() . '/storage/' . $farmerpicture->farmerpicture }}"
                     class="farmer-photo" alt="Farmer Photo">
            @else
                <img src="{{ Request::root() . '/storage/farmmap.png' }}"
                     class="farmer-photo" alt="No Photo">
            @endif
            <button type="button" class="btn btn-sm btn-success position-absolute bottom-0 end-0 rounded-circle p-1"
                    data-bs-toggle="modal" data-bs-target="#farmerPictureModal">
                <i class="fa fa-camera"></i>
            </button>
        </div>
        <div class="flex-grow-1">
            <h5 class="mb-1 fw-bold">
                {{ $farm->farmname }}
                <span class="badge {{ $statusBadge($farm->farmstate) }} ms-2" style="font-size:.75rem">
                    {{ $farm->farmstate }}
                </span>
                @if ($farmdetails->getlastreport() == 'CONDITIONAL')
                    <span class="badge bg-warning text-dark ms-1" style="font-size:.7rem">Conditional</span>
                @endif
            </h5>
            <div class="text-muted small">{{ $farm->farmcode }} &nbsp;|&nbsp; {{ $farm->community }}</div>
        </div>
        <a href="{{ route('index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa fa-arrow-left"></i> Back
        </a>
    </div>
</div>

{{-- ── Metric Cards ── --}}
<div class="row g-2 mb-3">
    <div class="col-6 col-md-3">
        <div class="card metric-card h-100">
            <div class="card-body py-2">
                <div class="info-label">Farm Area</div>
                <div class="fw-bold fs-5">{{ $farmArea }} <small class="text-muted fs-6">{{ $farm->measurement }}</small></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card metric-card h-100">
            <div class="card-body py-2">
                <div class="info-label">Plots</div>
                <div class="fw-bold fs-5">{{ $plotCount ?: '—' }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card metric-card h-100">
            <div class="card-body py-2">
                <div class="info-label">Last Score</div>
                @if ($lastScore !== null)
                    <span class="fw-bold" style="color:{{ $scoreColor($lastScorePct) }}">{{ $lastScorePct }}%</span>
                    <div class="score-bar mt-1">
                        <div class="score-fill" style="width:{{ $lastScorePct }}%; background:{{ $scoreColor($lastScorePct) }}"></div>
                    </div>
                @else
                    <div class="text-muted">N/A</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card metric-card h-100">
            <div class="card-body py-2">
                <div class="info-label">Next Inspection</div>
                <div class="fw-bold">{{ $farm->nextinspection ?: '—' }}</div>
            </div>
        </div>
    </div>
</div>

{{-- ── Two-column body ── --}}
<div class="row g-3 mb-3">
    {{-- Farm Info --}}
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-body">
                <div class="section-header">Farm Information</div>
                <div class="info-label">Farm Name</div>
                <div class="info-value">{{ $farm->farmname }}</div>
                <div class="info-label">Farm Code</div>
                <div class="info-value">{{ $farm->farmcode }}</div>
                <div class="info-label">Community</div>
                <div class="info-value">{{ $farm->community }}</div>
                <div class="info-label">Certified Crop</div>
                <div class="info-value">{{ $farm->crop ?: '—' }}</div>
                <div class="info-label">Crop Variety</div>
                <div class="info-value">{{ $farm->cropvariety ?: '—' }}</div>
                <div class="info-label">GPS Coordinates</div>
                <div class="info-value">{{ $farm->latitude }}, {{ $farm->longitude }}</div>
                <div class="info-label">Year of Certification</div>
                <div class="info-value">{{ $farm->yearofcertification ?: '—' }}</div>

                <form action="/fu/edit" method="get" class="mt-2">
                    <input hidden type="text" name="farmid" value="{{ $farm->id }}">
                    <button type="submit" class="btn btn-success btn-sm" name="editfunits">
                        <i class="fa fa-pencil"></i> Edit Farm Details
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Staff + Contract --}}
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-body">
                <div class="section-header">Field Staff &amp; Contract</div>

                <div class="info-label">Assigned Inspector</div>
                <div class="info-value d-flex align-items-center gap-2">
                    <span>{{ $farm->name ?: 'No Field Staff Assigned' }}</span>
                    @if ($authuser->roles === 'ADMINISTRATOR')
                        <button type="button" class="btn btn-outline-success btn-sm"
                                data-bs-toggle="modal" data-bs-target="#staffModal">
                            <i class="fa fa-pencil-square-o"></i>
                        </button>
                    @endif
                </div>

                <div class="info-label">Farm Status</div>
                <div class="info-value d-flex align-items-center gap-2">
                    <span class="badge {{ $statusBadge($farm->farmstate) }}">{{ $farm->farmstate }}</span>
                    @if ($authuser->roles === 'ADMINISTRATOR')
                        <button type="button" class="btn btn-outline-success btn-sm"
                                data-bs-toggle="modal" data-bs-target="#farmModal">
                            <i class="fa fa-pencil-square-o"></i>
                        </button>
                    @endif
                </div>

                <div class="info-label">Last Inspection</div>
                <div class="info-value">
                    @if ($lastreport)
                        {{ $farm->lastinspection }}
                        &nbsp;
                        <span class="badge {{ $statusBadge($lastreport->inspectionstate) }}">
                            {{ $lastreport->inspectionstate }}
                        </span>
                        @if ($lastScore !== null)
                            &nbsp;
                            <span style="color:{{ $scoreColor($lastScorePct) }}" class="fw-bold">{{ $lastScorePct }}%</span>
                        @endif
                    @else
                        <span class="text-muted">No inspections on record</span>
                    @endif
                </div>

                <div class="section-header mt-3">Contract Agreement</div>
                <form action="{{ route('generatecontract') }}" method="get">
                    <select name="cdseason" class="form-select form-select-sm mb-2" required>
                        @forelse ($seasons as $season)
                            @if (!empty($season))
                                <option value="{{ $season }}">{{ $season }}</option>
                            @endif
                        @empty
                            <option value="">No Farm entrance conducted</option>
                        @endforelse
                    </select>
                    <input type="hidden" name="farmid" value="{{ $farm->id }}">
                    <div class="d-flex gap-2 flex-wrap">
                        @if (!empty($season))
                            <a href="{{ route('viewcontract', ['farmid' => $farm->id, 'cdseason' => $season]) }}"
                               class="btn btn-success btn-sm">View Contract</a>
                            <button type="submit" class="btn btn-success btn-sm">Download Contract</button>
                        @else
                            <a href="#" class="btn btn-success btn-sm disabled">View Contract</a>
                            <button type="submit" class="btn btn-success btn-sm" disabled>Download Contract</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ── Inspection History ── --}}
<div class="card mb-3">
    <div class="card-body">
        <div class="section-header">Inspection Records</div>
        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm display" id="farms">
                <thead>
                    <tr>
                        <th>Season</th>
                        <th>Report Name</th>
                        <th>Score</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Comment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($farmreports as $farmreport)
                        @php
                            $pct = $farmreport->max_score > 0
                                ? round(($farmreport->score / $farmreport->max_score) * 100, 2)
                                : 0;
                        @endphp
                        <tr>
                            <td>{{ $farmreport->season }}</td>
                            <td>{{ $farmreport->reportname }}</td>
                            <td style="min-width:100px">
                                @if ($farmreport->max_score == 0)
                                    <span class="text-muted">0.00%</span>
                                @else
                                    <span class="fw-bold" style="color:{{ $scoreColor($pct) }}">{{ $pct }}%</span>
                                    <div class="score-bar mt-1">
                                        <div class="score-fill" style="width:{{ $pct }}%; background:{{ $scoreColor($pct) }}"></div>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $farmreport->created_at }}</td>
                            <td>
                                <span class="badge {{ $statusBadge($farmreport->inspectionstate) }}">
                                    {{ $farmreport->inspectionstate }}
                                </span>
                            </td>
                            <td>
                                <textarea class="form-control form-control-sm" rows="2" readonly>{{ $farmreport->comments }}</textarea>
                            </td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    <form action="/iapprove" method="post">
                                        @csrf
                                        <input type="hidden" name="iid" value="{{ $farmreport->iid }}">
                                        <button name="viewsheet" type="submit" class="btn btn-success btn-sm"
                                                title="View Inspection Sheet">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('continue') }}" method="POST">
                                        @csrf
                                        <input type="hidden" value="{{ $farm->id }}" name="farmid">
                                        <input type="hidden" value="{{ $farmreport->iid }}" name="inspectionid">
                                        <button class="btn btn-danger btn-sm" name="printsheet" title="Download PDF">
                                            <i class="fa fa-file-pdf-o"></i>
                                        </button>
                                    </form>
                                </div>
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

{{-- ── Modals ── --}}
<div class="modal fade" id="staffModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reassign Field Staff</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="/farm/assignstaff" method="post">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Farm Name</label>
                        <input disabled type="text" readonly class="form-control" value="{{ $farm->farmname }}">
                        <input hidden type="text" name="id" value="{{ $farm->farmcode }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Staff</label>
                        <select class="form-select" name="staffid">
                            @forelse ($users as $u)
                                <option value="{{ $u->id }}" {{ (int)$farm->uid === (int)$u->id ? 'selected' : '' }}>
                                    {{ $u->name }}
                                </option>
                            @empty
                                <option value="">No users on system</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-success" name="assignstaff">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="farmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Farm Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="/farm/assignstaff" method="post">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Farm Name</label>
                        <input disabled type="text" readonly class="form-control" value="{{ $farm->farmname }}">
                        <input hidden type="text" name="id" value="{{ $farm->farmcode }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Farm Status</label>
                        <select class="form-select" name="farmid">
                            @foreach (['PENDING','ACTIVE','REMEDIAL','DISABLED','CLOSED'] as $st)
                                <option value="{{ $st }}" {{ $farm->farmstate === $st ? 'selected' : '' }}>{{ $st }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-success" name="farmstatus">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="farmerPictureModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Farmer Picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('updatepicture') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <p class="text-danger small">
                        <i class="fa fa-exclamation-triangle"></i>
                        Uploading a new image will <u>replace</u> the existing picture on the last Farm Entrance record.
                    </p>
                    <div class="mb-3">
                        <label class="form-label">Farm Name</label>
                        <input type="text" class="form-control" value="{{ $farm->farmname }}" readonly disabled>
                        <input type="hidden" name="fcode" value="{{ $farm->farmcode }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Farmer Picture</label>
                        <input type="file" name="farmerpicture" accept="image/*" class="form-control">
                    </div>
                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" name="newfarmerpicture">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    new DataTable('#farms', {
        dom: 'Bfrtip',
        order: [[3, 'desc']],
        buttons: [{
            extend: 'excelHtml5',
            title: 'Inspection_History',
            exportOptions: { columns: [0,1,2,3,4,5] }
        }]
    });
</script>
</x-layouts.app>
