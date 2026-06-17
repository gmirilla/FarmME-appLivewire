<x-layouts.app>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

@php
    $totalUnits  = $farmunits->count();
    $activeUnits = $farmunits->where('active', true)->count();
@endphp

<style>
    .metric-card  { border-left: 4px solid #198754; }
    .metric-num   { font-size: 1.8rem; font-weight: 700; line-height: 1.1; }
    .metric-sub   { font-size: .75rem; color: #6c757d; }
    .page-toolbar { display: flex; align-items: center; justify-content: space-between;
                    flex-wrap: wrap; gap: .5rem; margin-bottom: 1.25rem; }
    .page-title   { font-size: 1.25rem; font-weight: 700; color: #212529; margin: 0; }
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

{{-- ── PAGE TOOLBAR ─────────────────────────────────────────────────────── --}}
<div class="page-toolbar">
    <h5 class="page-title">
        <i class="fa fa-map-o me-2 text-success"></i>Farm Plots &mdash;
        <span class="text-success font-monospace">{{ $farm->farmcode }}</span>
    </h5>
    <div class="d-flex flex-wrap gap-2">
        <form method="get" action="{{ route('edit_farmunit') }}">
            @csrf
            <input type="hidden" name="farmid" value="{{ $farm->id }}">
            <button class="btn btn-outline-primary btn-sm">
                <i class="fa fa-leaf me-1"></i> Farm Details
            </button>
        </form>
        <button class="btn btn-primary btn-sm" disabled>
            <i class="fa fa-map-o me-1"></i> Farm Plots
        </button>
        <form method="get" action="{{ route('list_yield') }}">
            @csrf
            <input type="hidden" name="farmid" value="{{ $farm->id }}">
            <button class="btn btn-outline-primary btn-sm" name="getyield">
                <i class="fa fa-bar-chart me-1"></i> Yield Details
            </button>
        </form>
    </div>
</div>

{{-- ── SUMMARY CARDS ────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <div class="card metric-card shadow-sm h-100">
            <div class="card-body">
                <div class="metric-sub mb-1">Farm Code</div>
                <div class="fw-bold text-success font-monospace" style="font-size:1.2rem">
                    {{ $farm->farmcode }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="card metric-card shadow-sm h-100" style="border-left-color:#0d6efd">
            <div class="card-body">
                <div class="metric-sub mb-1">Registered Plots</div>
                <div class="metric-num" style="color:#0d6efd">{{ $farm->nooffarmunits ?? $totalUnits }}</div>
                <div style="font-size:.7rem;color:#adb5bd">{{ $activeUnits }} active</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="card metric-card shadow-sm h-100" style="border-left-color:#6c757d">
            <div class="card-body">
                <div class="metric-sub mb-1">Total Area (Ha)</div>
                <div class="metric-num text-secondary">{{ number_format($farm->farmarea ?? 0, 2) }}</div>
            </div>
        </div>
    </div>
</div>

{{-- ── FARM PLOTS TABLE ─────────────────────────────────────────────────── --}}
<div class="card shadow-sm">
    <div class="card-body table-responsive p-0">
        <table class="table table-striped table-hover table-sm align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th class="ps-3">Plot ID</th>
                    <th>Area (ha)</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th style="width:220px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($farmunits as $funit)
                    <form method="post" action="{{ route('editfu') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="fid"    value="{{ $funit->id }}">
                        <input type="hidden" name="farmid" value="{{ $farm->id }}">
                        <tr>
                            <td class="ps-3 fw-bold font-monospace small">{{ $funit->id }}</td>
                            <td class="small">{{ $funit->fuarea }}</td>
                            <td class="small text-muted">{{ $funit->fulatitude }}</td>
                            <td class="small text-muted">{{ $funit->fulongitude }}</td>
                            <td>
                                @if ($funit->active)
                                    <button type="submit" name="updatefu"
                                            class="btn btn-success btn-sm me-1">
                                        <i class="fa fa-pencil me-1"></i>Update
                                    </button>
                                    <button type="submit" name="deletefu"
                                            class="btn btn-danger btn-sm">
                                        <i class="fa fa-ban me-1"></i>Inactivate
                                    </button>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="fa fa-ban me-1"></i>Inactive
                                    </span>
                                @endif
                            </td>
                        </tr>
                    </form>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="fa fa-map-o fa-2x mb-2 d-block"></i>
                            No farm plots recorded yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-transparent d-flex justify-content-end">
        <form action="{{ route('newfunit') }}" method="get">
            <input type="hidden" name="farmid" value="{{ $farm->id }}">
            <button class="btn btn-success btn-sm">
                <i class="fa fa-plus me-1"></i> Add Farm Plot
            </button>
        </form>
    </div>
</div>

</x-layouts.app>
