<x-layouts.app>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

@php
    $isAdmin       = $user->roles === 'ADMINISTRATOR';
    $year          = date('Y');
    $currentSeason = \App\Models\Season::currentString();
    $seasonOpen    = \App\Models\Season::isOpen();
    $totalFarms    = $farmcount + $farmpendingcount;
@endphp

<style>
    .metric-card  { border-left: 4px solid #198754; transition: box-shadow .15s; }
    .metric-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.10) !important; }
    .metric-num   { font-size: 2rem; font-weight: 700; line-height: 1.1; }
    .metric-sub   { font-size: .72rem; text-transform: uppercase; letter-spacing: .06em;
                    color: #6c757d; margin-bottom: 4px; }
    .section-header { background:#f8f9fa; border-left:4px solid #198754;
                      padding:6px 14px; font-weight:700; font-size:.78rem;
                      text-transform:uppercase; letter-spacing:.07em;
                      color:#495057; margin-bottom:.85rem; border-radius:0 4px 4px 0; }
    .quick-link   { text-decoration:none; color:inherit; }
    .quick-link-card { border:1px solid #dee2e6; border-radius:8px; padding:1.1rem .75rem;
                       text-align:center; transition:all .2s; cursor:pointer; }
    .quick-link-card:hover { border-color:#198754; background:#f0faf3;
                              box-shadow:0 2px 10px rgba(25,135,84,.15); }
    .quick-link-card .ql-icon { font-size:1.6rem; color:#198754; margin-bottom:.4rem; }
    .quick-link-card .ql-label { font-size:.78rem; font-weight:600; color:#495057;
                                  text-transform:uppercase; letter-spacing:.04em; }
    .welcome-bar  { background:linear-gradient(135deg,#1a6e38 0%,#2e9e58 100%);
                    border-radius:10px; color:#fff; padding:1.25rem 1.5rem; margin-bottom:1.25rem; }
</style>

{{-- ── WELCOME HEADER ───────────────────────────────────────────────────── --}}
<div class="welcome-bar d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
        <div style="font-size:.8rem;opacity:.8">
            {{ now()->format('l, d F Y') }}
        </div>
        <h4 class="mb-0 fw-bold">Welcome back, {{ $user->fname ?? $user->name }}</h4>
        <div style="font-size:.82rem;opacity:.85">Here's your farm overview for today.</div>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        @if ($isAdmin)
            <span class="badge bg-white text-dark" style="font-size:.8rem">
                <i class="fa fa-users me-1"></i> {{ $usercount }} Active User{{ $usercount != 1 ? 's' : '' }}
            </span>
        @endif
        <span class="badge {{ $seasonOpen ? 'bg-success' : 'bg-secondary' }}" style="font-size:.8rem">
            <i class="fa fa-calendar me-1"></i>
            {{ $currentSeason }} &mdash; {{ $seasonOpen ? 'Season Open' : 'Season Closed' }}
        </span>
    </div>
</div>

{{-- ── FARMS ────────────────────────────────────────────────────────────── --}}
<div class="section-header">Farms</div>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card metric-card shadow-sm h-100">
            <div class="card-body">
                <div class="metric-sub">Active Farms</div>
                <div class="metric-num text-success">{{ $farmcount }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card metric-card shadow-sm h-100" style="border-left-color:#fd7e14">
            <div class="card-body">
                <div class="metric-sub">Pending Entrance</div>
                <div class="metric-num" style="color:#fd7e14">{{ $farmpendingcount }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card metric-card shadow-sm h-100" style="border-left-color:#0d6efd">
            <div class="card-body">
                <div class="metric-sub">Total Registered</div>
                <div class="metric-num" style="color:#0d6efd">{{ $totalFarms }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card metric-card shadow-sm h-100" style="border-left-color:#6c757d">
            <div class="card-body">
                <div class="metric-sub">Total Area (Ha)</div>
                <div class="metric-num text-secondary">{{ number_format($farmarea, 1) }}</div>
                <div style="font-size:.72rem;color:#adb5bd">{{ $plotcount }} plot{{ $plotcount != 1 ? 's' : '' }}</div>
            </div>
        </div>
    </div>
</div>

{{-- ── INSPECTIONS ──────────────────────────────────────────────────────── --}}
<div class="section-header" style="border-left-color:#0d6efd">Inspections</div>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <div class="card metric-card shadow-sm h-100" style="border-left-color:#0d6efd">
            <div class="card-body">
                <div class="metric-sub">Submitted</div>
                <div class="metric-num" style="color:#0d6efd">{{ $inspectioncount }}</div>
                <div style="font-size:.72rem;color:#adb5bd">Awaiting review</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="card metric-card shadow-sm h-100">
            <div class="card-body">
                <div class="metric-sub">Approved</div>
                <div class="metric-num text-success">{{ $inspectionapprovedcount }}</div>
                <div style="font-size:.72rem;color:#adb5bd">Passed</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="card metric-card shadow-sm h-100" style="border-left-color:#dc3545">
            <div class="card-body">
                <div class="metric-sub">Rejected</div>
                <div class="metric-num text-danger">{{ $inspectionrejectedcount }}</div>
                <div style="font-size:.72rem;color:#adb5bd">Require action</div>
            </div>
        </div>
    </div>
</div>

{{-- ── YIELD ────────────────────────────────────────────────────────────── --}}
<div class="section-header" style="border-left-color:#fd7e14">Yield — {{ $year }}</div>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-6">
        <div class="card metric-card shadow-sm h-100" style="border-left-color:#fd7e14">
            <div class="card-body">
                <div class="metric-sub">Estimated Yield</div>
                @if (is_numeric($estyield))
                    <div class="metric-num" style="color:#fd7e14">{{ number_format($estyield, 0) }}</div>
                    <div style="font-size:.78rem;color:#adb5bd">Kgs &mdash; {{ $currentSeason }} season</div>
                @else
                    <div class="metric-num text-muted" style="font-size:1.3rem">{{ $estyield }}</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-6 col-md-6">
        <div class="card metric-card shadow-sm h-100" style="border-left-color:#198754">
            <div class="card-body">
                <div class="metric-sub">Actual Yield</div>
                @if (is_numeric($actualyield))
                    <div class="metric-num text-success">{{ number_format($actualyield, 0) }}</div>
                    <div style="font-size:.78rem;color:#adb5bd">Kgs &mdash; {{ $year }}</div>
                @else
                    <div class="metric-num text-muted" style="font-size:1.3rem">{{ $actualyield }}</div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ── QUICK LINKS ──────────────────────────────────────────────────────── --}}
<div class="section-header" style="border-left-color:#6c757d">Quick Links</div>
<div class="row g-3 mb-2">

    <div class="col-6 col-md-2">
        <a href="{{ route('index') }}" class="quick-link">
            <div class="quick-link-card">
                <div class="ql-icon"><i class="fa fa-leaf"></i></div>
                <div class="ql-label">Farm List</div>
            </div>
        </a>
    </div>

    <div class="col-6 col-md-2">
        <a href="{{ route('onboarding') }}" class="quick-link">
            <div class="quick-link-card">
                <div class="ql-icon"><i class="fa fa-pencil-square-o"></i></div>
                <div class="ql-label">Farm Entrance</div>
            </div>
        </a>
    </div>

    <div class="col-6 col-md-2">
        <a href="{{ route('inspection') }}" class="quick-link">
            <div class="quick-link-card">
                <div class="ql-icon"><i class="fa fa-clipboard"></i></div>
                <div class="ql-label">Inspections</div>
            </div>
        </a>
    </div>

    @if ($isAdmin)
        <div class="col-6 col-md-2">
            <a href="{{ route('iapproval') }}" class="quick-link">
                <div class="quick-link-card">
                    <div class="ql-icon"><i class="fa fa-check-circle-o"></i></div>
                    <div class="ql-label">Approvals</div>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-2">
            <a href="{{ route('season.index') }}" class="quick-link">
                <div class="quick-link-card">
                    <div class="ql-icon"><i class="fa fa-calendar"></i></div>
                    <div class="ql-label">Season Mgmt</div>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-2">
            <a href="{{ route('annualreport') }}" class="quick-link">
                <div class="quick-link-card">
                    <div class="ql-icon"><i class="fa fa-bar-chart"></i></div>
                    <div class="ql-label">Annual Report</div>
                </div>
            </a>
        </div>
    @endif

</div>

</x-layouts.app>
