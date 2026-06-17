<x-layouts.app>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
    .page-toolbar  { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: .5rem; margin-bottom: 1.25rem; }
    .page-title    { font-size: 1.25rem; font-weight: 700; color: #212529; margin: 0; }
    .info-label    { font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: #6c757d; margin-bottom: 2px; }
    .info-value    { font-size: .95rem; color: #212529; word-break: break-word; }
    .section-header { background: #f8f9fa; border-left: 4px solid #198754; padding: 8px 14px;
                      font-weight: 700; font-size: .85rem; text-transform: uppercase;
                      letter-spacing: .06em; color: #495057; border-radius: 0 4px 4px 0; }
</style>

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

@php
    $year = date('Y');
    if (empty($farmentrance->getcropdeliver())) {
        $prevseason        = $seasonrange[0];
        $prevcropdelivered = null;
    } else {
        $prevseason        = $farmentrance->getcropdeliver()->season;
        $prevcropdelivered = $farmentrance->getcropdeliver()->value;
    }
    if (empty($farmentrance->getcropproduced())) {
        $prevcropproduced = null;
    } else {
        $prevcropproduced = $farmentrance->getcropproduced()->value;
    }
@endphp

{{-- ── PAGE TOOLBAR ─────────────────────────────────────────────────────── --}}
<div class="page-toolbar">
    <h5 class="page-title">
        <i class="fa fa-file-text-o me-2 text-success"></i>
        Annex 3: Field Entrance Form
    </h5>
    <span class="badge bg-success" style="font-size:.8rem">
        <i class="fa fa-calendar me-1"></i>{{ $currentseason }} Season
    </span>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     A. FIELD OPERATOR BIO-DATA
     ══════════════════════════════════════════════════════════════════════════ --}}
<div class="card shadow-sm mb-3">
    <div class="card-header section-header">
        A. Field Operator Bio-Data
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="info-label">Surname</div>
                <div class="info-value">{{ $farmerdetail->surname }}</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="info-label">Other Name</div>
                <div class="info-value">{{ $farmerdetail->fname }}</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="info-label">Gender</div>
                <div class="info-value">{{ $farmerdetail->gender }}</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="info-label">Farmer Code</div>
                <div class="info-value fw-bold text-success font-monospace">{{ $farmerdetail->farmcode }}</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="info-label">ID Number</div>
                <div class="info-value">{{ $farmerdetail->nationalidnumber }}</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="info-label">Year of Birth</div>
                <div class="info-value">{{ $farmerdetail->yob }}</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="info-label">Phone Number</div>
                <div class="info-value">{{ $farmerdetail->phonenumber }}</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="info-label">Household Size</div>
                <div class="info-value">{{ $farmerdetail->householdsize }}</div>
            </div>
            <div class="col-12 col-md-6">
                <div class="info-label">Address</div>
                <div class="info-value">{{ $farmerdetail->address }}</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="info-label">Date of Last Inspection</div>
                <div class="info-value">{{ $farmentrance->lastinspection }}</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="info-label">Outcome of Last Inspection</div>
                <div class="info-value">{{ $farmentrance->inspectionresult }}</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="info-label">Name of Crop</div>
                <div class="info-value">{{ $farmerdetail->crop }}</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="info-label">Variety of Crop</div>
                <div class="info-value">{{ $farmerdetail->cropvariety }}</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="info-label">Source of Crop</div>
                <div class="info-value">{{ $farmentrance->sourceofcrop }}</div>
            </div>
            @if (!empty($farmerdetail->signaturepath))
                <div class="col-6 col-md-3">
                    <div class="info-label">Farmer Signature</div>
                    <div class="mt-1">
                        <img src="{{ url('/storage/' . $farmerdetail->signaturepath) }}"
                             alt="Signature" style="width:80px;height:auto;border:1px solid #dee2e6;border-radius:4px;">
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     B. HISTORICAL PRODUCTION
     ══════════════════════════════════════════════════════════════════════════ --}}
<div class="card shadow-sm mb-3">
    <div class="card-header section-header">
        B. Historical Production: Hibiscus Production
    </div>
    <div class="card-body p-0">
        <p class="px-3 pt-2 text-muted small mb-0">
            <i class="fa fa-info-circle me-1"></i>
            Maize (M), Sorghum (S), Cowpea (C), Millet (ML), Sesame (Se), Groundnut (Gr)
        </p>
        <div class="table-responsive">
            <table class="table table-striped table-sm align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Year</th>
                        <th>Farm Size (Ha)</th>
                        <th>Harvest Volume (Bags)</th>
                        <th>Intercrop Crop</th>
                        <th>Intercrop System</th>
                        <th>Intra-row Spacing (m)</th>
                        <th style="width:80px"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($farmentrance->getvolumesold() as $volsold)
                        <tr>
                            <td class="small">{{ $volsold->season }}</td>
                            <td class="small">{{ $volsold->farmsize }}</td>
                            <td class="small">{{ $volsold->value }}</td>
                            <td class="small">{{ $volsold->crop }}</td>
                            <td class="small">{{ $volsold->system }}</td>
                            <td class="small">{{ $volsold->spacing }}</td>
                            <td>
                                <a href="{{ route('disablevolsold', ['farmcode' => $farmerdetail->farmcode, 'vsid' => $volsold->id]) }}"
                                   class="btn btn-danger btn-sm" title="Disable">
                                    <i class="fa fa-ban"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">
                                <i class="fa fa-inbox me-1"></i> No historical production records.
                            </td>
                        </tr>
                    @endforelse

                    {{-- Add new row --}}
                    <tr class="table-light">
                        <form action="{{ route('addvolsold') }}" method="post">
                            @csrf
                            <td>
                                <select name="volseason" id="seasonrange" class="form-select form-select-sm">
                                    @foreach ($seasonrange as $srange)
                                        <option value="{{ $srange }}">{{ $srange }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" step="any" name="farmsize" required class="form-control form-control-sm" placeholder="Ha"></td>
                            <td><input type="number" name="harvest" required class="form-control form-control-sm" placeholder="Bags"></td>
                            <td><input type="text" name="crop" required class="form-control form-control-sm"></td>
                            <td><input type="text" name="sysyem" required class="form-control form-control-sm"></td>
                            <td><input type="number" step="any" name="spacing" class="form-control form-control-sm" placeholder="m"></td>
                            <td>
                                <input type="hidden" name="farmcode"       value="{{ $farmerdetail->farmcode }}">
                                <input type="hidden" name="farmid"         value="{{ $farmerdetail->id }}">
                                <input type="hidden" name="farmentranceid" value="{{ $farmentrance->id }}">
                                <button type="submit" class="btn btn-success btn-sm" title="Add record">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </td>
                        </form>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     C. HISTORICAL AGROCHEMICALS
     ══════════════════════════════════════════════════════════════════════════ --}}
<div class="card shadow-sm mb-3">
    <div class="card-header section-header">
        C. Historical Production: Agrochemicals Used &mdash; {{ $prevseason }}
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-sm align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Farm Size (Ha)</th>
                        <th>Products (Fertilizers, Pesticides, Herbicides)</th>
                        <th>Quantity Applied (Litres/Bags)</th>
                        <th>Name of Person Who Applied</th>
                        <th class="text-center">PPE Used</th>
                        <th style="width:80px"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($agrochems as $agrochem)
                        <tr>
                            <td class="small">{{ $agrochem->farmsize }}</td>
                            <td class="small">{{ $agrochem->herbicidename }}</td>
                            <td class="small">{{ $agrochem->quantity }}</td>
                            <td class="small">{{ $agrochem->nameofperson }}</td>
                            <td class="text-center">
                                <input type="checkbox" class="form-check-input" disabled
                                       {{ $agrochem->ppeused ? 'checked' : '' }}>
                            </td>
                            <td>
                                <a href="{{ route('disablechems', ['farmcode' => $farmerdetail->farmcode, 'aid' => $agrochem->id]) }}"
                                   class="btn btn-danger btn-sm" title="Disable">
                                    <i class="fa fa-ban"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">
                                <i class="fa fa-inbox me-1"></i> No agrochemical records for {{ $prevseason }}.
                            </td>
                        </tr>
                    @endforelse

                    {{-- Add new row --}}
                    <tr class="table-light">
                        <form action="{{ route('addchems') }}" method="post">
                            @csrf
                            <td><input type="text" required name="farmsize" class="form-control form-control-sm" placeholder="Ha"></td>
                            <td><input type="text" required name="herbicide" class="form-control form-control-sm" placeholder="Chemical name..."></td>
                            <td><input type="text" required name="herbicideqty" class="form-control form-control-sm" placeholder="Qty"></td>
                            <td><input type="text" required name="herbicideapplier" class="form-control form-control-sm" placeholder="Applicator name"></td>
                            <td class="text-center">
                                <div class="form-check d-inline-block">
                                    <input type="checkbox" name="ppeused" class="form-check-input">
                                </div>
                            </td>
                            <td>
                                <input type="hidden" name="farmcode"    value="{{ $farmerdetail->farmcode }}">
                                <input type="hidden" name="farmid"      value="{{ $farmerdetail->id }}">
                                <input type="hidden" name="farmentrance" value="{{ $farmentrance->id }}">
                                <input type="hidden" name="season"      value="{{ $currentseason }}">
                                <button type="submit" name="addherbicide" class="btn btn-success btn-sm" title="Add record">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </td>
                        </form>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════
     D. CURRENT PRODUCTION
     ══════════════════════════════════════════════════════════════════════════ --}}
<div class="card shadow-sm mb-3">
    <div class="card-header section-header">
        D. Current Production &mdash; {{ $currentseason }}
    </div>
    <div class="card-body p-0">
        <p class="px-3 pt-2 text-muted small mb-0">
            <i class="fa fa-info-circle me-1"></i>
            Maize (M), Sorghum (S), Cowpea (C), Millet (ML), Sesame (Se), Groundnut (Gr)
        </p>
        <div class="table-responsive">
            <table class="table table-striped table-sm align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Plot</th>
                        <th>Plot Size (Ha)</th>
                        <th>Yield Est (Bags)</th>
                        <th>Crop</th>
                        <th>System</th>
                        <th>Spacing (m)</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th style="width:80px"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($farmplots as $farmplot)
                        <tr>
                            <td class="fw-bold small">{{ $farmplot->plotname }}</td>
                            <td class="small">{{ $farmplot->fuarea }}</td>
                            <td class="small">{{ $farmplot->estimatedyield }}</td>
                            <td class="small">{{ $farmplot->crop }}</td>
                            <td class="small">{{ $farmplot->system }}</td>
                            <td class="small">{{ $farmplot->spacing }}</td>
                            <td class="small text-muted">{{ $farmplot->fulatitude }}</td>
                            <td class="small text-muted">{{ $farmplot->fulongitude }}</td>
                            <td>
                                <a href="{{ route('disablefarm', ['farmcode' => $farmerdetail->farmcode, 'fuid' => $farmplot->id]) }}"
                                   class="btn btn-danger btn-sm" title="Disable">
                                    <i class="fa fa-ban"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-3">
                                <i class="fa fa-inbox me-1"></i> No farm plots recorded for this season.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 d-flex justify-content-end">
            <form action="{{ route('newfunit') }}" method="get">
                <input type="hidden" name="farmid"         value="{{ $farmerdetail->id }}">
                <input type="hidden" name="farmentranceid" value="{{ $farmentrance->id }}">
                <button class="btn btn-success btn-sm" name="newfunit">
                    <i class="fa fa-plus me-1"></i> Add Farm Plot
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ── PROCEED / GO BACK ────────────────────────────────────────────────── --}}
<div class="d-flex gap-2 mb-4">
    @if (empty($farmentrance->internalinspectionid))
        <form action="{{ route('start') }}" method="post">
            @csrf
            <input type="hidden" name="farmentrance"         value="{{ $farmentrance->id }}">
            <input type="hidden" name="internalinspectionid" value="{{ $farmentrance->internalinspectionid }}">
            <input type="hidden" name="farmid"               value="{{ $farmerdetail->id }}">
            <input type="hidden" name="reportid"             value="{{ $report->id }}">
            <button type="submit" class="btn btn-success">
                <i class="fa fa-arrow-right me-1"></i> Proceed to Inspection
            </button>
        </form>
    @else
        <form action="{{ route('continue') }}" method="post">
            @csrf
            <input type="hidden" name="farmentrance"         value="{{ $farmentrance->id }}">
            <input type="hidden" name="internalinspectionid" value="{{ $farmentrance->internalinspectionid }}">
            <input type="hidden" name="inspectionid"         value="{{ $farmentrance->internalinspectionid }}">
            <input type="hidden" name="farmid"               value="{{ $farmerdetail->id }}">
            <input type="hidden" name="id"                   value="{{ $report->id }}">
            <button type="submit" class="btn btn-success">
                <i class="fa fa-arrow-right me-1"></i> Continue Inspection
            </button>
        </form>
    @endif

    <a href="{{ route('feprofile', ['fcode' => $farmerdetail->farmcode]) }}"
       class="btn btn-outline-secondary">
        <i class="fa fa-arrow-left me-1"></i> Go Back
    </a>
</div>

</x-layouts.app>
