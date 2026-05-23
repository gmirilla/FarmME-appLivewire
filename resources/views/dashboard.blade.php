<x-layouts.app>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
    .stat-card {
        border-left: 4px solid #198754;
        transition: box-shadow .15s ease;
    }
    .stat-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.1); }
    .stat-card.yellow  { border-left-color: #ffc107; }
    .stat-card.blue    { border-left-color: #0d6efd; }
    .stat-card.red     { border-left-color: #dc3545; }
    .stat-card.orange  { border-left-color: #fd7e14; }
    .stat-card.teal    { border-left-color: #0dcaf0; }
    .stat-card.purple  { border-left-color: #6f42c1; }

    .stat-icon {
        width: 48px; height: 48px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem;
        background: rgba(25,135,84,.1);
        color: #198754;
        flex-shrink: 0;
    }
    .stat-icon.yellow  { background: rgba(255,193,7,.15);  color: #ffc107; }
    .stat-icon.blue    { background: rgba(13,110,253,.1);  color: #0d6efd; }
    .stat-icon.red     { background: rgba(220,53,69,.1);   color: #dc3545; }
    .stat-icon.orange  { background: rgba(253,126,20,.1);  color: #fd7e14; }
    .stat-icon.teal    { background: rgba(13,202,240,.1);  color: #0dcaf0; }
    .stat-icon.purple  { background: rgba(111,66,193,.1);  color: #6f42c1; }

    .section-label {
        font-size: .7rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .08em; color: #6c757d; margin-bottom: .25rem;
    }
    .stat-value { font-size: 1.75rem; font-weight: 700; line-height: 1.1; color: #212529; }
    .stat-sub   { font-size: .8rem; color: #6c757d; margin-top: .15rem; }

    .section-divider {
        background: #f8f9fa;
        border-left: 4px solid #198754;
        padding: 6px 14px;
        font-weight: 700; font-size: .8rem;
        text-transform: uppercase; letter-spacing: .07em;
        color: #495057; border-radius: 0 4px 4px 0;
        margin-bottom: 1rem;
    }

    .quick-link { border-radius: 8px; font-size: .85rem; }
</style>

@php
    $datayear = date('Y');
    $isAdmin  = $user->roles === 'ADMINISTRATOR';
    $seasonOpen = $currentSeason && $currentSeason->status === 'OPEN';
@endphp

{{-- ── Header ── --}}
<div class="d-flex flex-wrap justify-content-between align-items-start mb-4 gap-2">
    <div>
        <h4 class="mb-1 fw-bold" style="color:#198754;">
            Welcome back, {{ $user->fname }}
        </h4>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span class="badge {{ $isAdmin ? 'bg-success' : 'bg-primary' }}">
                {{ $user->roles }}
            </span>
            <span class="text-muted small">Season {{ $currentSeasonString }}</span>
            <span class="badge {{ $seasonOpen ? 'bg-success' : 'bg-secondary' }}">
                <i class="fa fa-circle" style="font-size:.6rem"></i>
                {{ $seasonOpen ? 'Season Open' : 'Season Closed' }}
            </span>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="d-flex flex-wrap gap-2">
        @if ($isAdmin)
            <a href="{{ route('season.index') }}" class="btn btn-warning btn-sm quick-link">
                <i class="fa fa-calendar"></i> Season Management
            </a>
            <a href="{{ route('index') }}" class="btn btn-success btn-sm quick-link">
                <i class="fa fa-leaf"></i> View Farms
            </a>
            <a href="{{ route('iapproval') }}" class="btn btn-outline-success btn-sm quick-link">
                <i class="fa fa-check-square-o"></i> Review Inspections
            </a>
        @else
            <a href="{{ route('onboarding') }}" class="btn btn-success btn-sm quick-link">
                <i class="fa fa-pencil"></i> Farm Entrance
            </a>
            <a href="{{ route('inspection') }}" class="btn btn-outline-success btn-sm quick-link">
                <i class="fa fa-file-text-o"></i> My Inspections
            </a>
        @endif
    </div>
</div>

{{-- ── Farm Overview ── --}}
<div class="section-divider"><i class="fa fa-leaf me-1"></i> Farm Overview</div>
<div class="row g-3 mb-4">

    @if ($isAdmin)
    <div class="col-6 col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon blue"><i class="fa fa-users"></i></div>
                <div>
                    <div class="section-label">Active Users</div>
                    <div class="stat-value">{{ $usercount }}</div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="col-6 col-md-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon"><i class="fa fa-pagelines"></i></div>
                <div>
                    <div class="section-label">Active Farms</div>
                    <div class="stat-value">{{ $farmcount }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card stat-card yellow h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon yellow"><i class="fa fa-clock-o"></i></div>
                <div>
                    <div class="section-label">Pending Entrance</div>
                    <div class="stat-value">{{ $farmpendingcount }}</div>
                </div>
            </div>
        </div>
    </div>

    @if ($isAdmin)
    <div class="col-6 col-md-3">
        <div class="card stat-card teal h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon teal"><i class="fa fa-database"></i></div>
                <div>
                    <div class="section-label">Total Registered</div>
                    <div class="stat-value">{{ $farmcount + $farmpendingcount }}</div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="col-6 col-md-3">
        <div class="card stat-card purple h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon purple"><i class="fa fa-map-marker"></i></div>
                <div>
                    <div class="section-label">Total Plots</div>
                    <div class="stat-value">{{ number_format($plotcount) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card stat-card orange h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon orange"><i class="fa fa-map-o"></i></div>
                <div>
                    <div class="section-label">Total Acreage</div>
                    <div class="stat-value">{{ number_format($farmarea, 2) }}</div>
                    <div class="stat-sub">hectares</div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ── Inspections ── --}}
<div class="section-divider"><i class="fa fa-file-text-o me-1"></i> Inspections</div>
<div class="row g-3 mb-4">

    <div class="col-6 col-md-4">
        <div class="card stat-card blue h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon blue"><i class="fa fa-paper-plane-o"></i></div>
                <div>
                    <div class="section-label">Submitted</div>
                    <div class="stat-value">{{ $inspectioncount }}</div>
                    <div class="stat-sub">Awaiting review</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon"><i class="fa fa-check-circle"></i></div>
                <div>
                    <div class="section-label">Approved</div>
                    <div class="stat-value">{{ $inspectionapprovedcount }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4">
        <div class="card stat-card red h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon red"><i class="fa fa-times-circle"></i></div>
                <div>
                    <div class="section-label">Rejected</div>
                    <div class="stat-value">{{ $inspectionrejectedcount }}</div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ── Yield ── --}}
<div class="section-divider"><i class="fa fa-balance-scale me-1"></i> Yield — {{ $datayear }}</div>
<div class="row g-3 mb-4">

    <div class="col-6 col-md-6">
        <div class="card stat-card teal h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon teal"><i class="fa fa-bar-chart"></i></div>
                <div>
                    <div class="section-label">Estimated Yield</div>
                    @if (is_numeric($estyield))
                        <div class="stat-value">{{ number_format($estyield, 2) }}</div>
                        <div class="stat-sub">kg</div>
                    @else
                        <div class="stat-value text-muted" style="font-size:1.1rem">{{ $estyield }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-6">
        <div class="card stat-card purple h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon purple"><i class="fa fa-check-circle-o"></i></div>
                <div>
                    <div class="section-label">Actual Yield</div>
                    @if (is_numeric($actualyield))
                        <div class="stat-value">{{ number_format($actualyield, 2) }}</div>
                        <div class="stat-sub">kg</div>
                    @else
                        <div class="stat-value text-muted" style="font-size:1.1rem">{{ $actualyield }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

</x-layouts.app>
