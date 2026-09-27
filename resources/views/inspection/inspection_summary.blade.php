<x-layouts.app>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

@php
    $stateBadge = fn($s) => match($s) {
        'APPROVED'    => 'bg-success',
        'CONDITIONAL' => 'bg-warning text-dark',
        'REJECTED'    => 'bg-danger',
        'SUBMITTED'   => 'bg-primary',
        'PENDING'     => 'bg-info text-dark',
        'ACTIVE'      => 'bg-success',
        default       => 'bg-secondary',
    };

    $totalRecords = $internalinspection->count();
    $totalFarms   = $internalinspection->pluck('farmid')->unique()->count();
    $totalArea    = $internalinspection->unique('farmid')->sum(
        fn($i) => $i->getfarm()->getreportfarmarea($season)
    );

    if ($reporttype == 'Entrance') {
        $totalYield = $internalinspection->sum(
            fn($i) => !empty($i->farmentrance) ? $i->farmentrance->getestimatedyield() : 0
        );
    } else {
        $withConditions = $internalinspection->filter(fn($i) => !empty($i->conditions))->count();
    }
@endphp

<style>
    .metric-card  { border-left: 4px solid #198754; }
    .metric-num   { font-size: 1.8rem; font-weight: 700; line-height: 1.1; }
    .metric-sub   { font-size: .75rem; color: #6c757d; }
    .page-toolbar { display: flex; align-items: center; justify-content: space-between;
                    flex-wrap: wrap; gap: .5rem; margin-bottom: 1.25rem; }
    .page-title   { font-size: 1.25rem; font-weight: 700; color: #212529; margin: 0; }
    .section-header { background:#f8f9fa; border-left:4px solid #198754;
                  padding:8px 14px; font-weight:700; font-size:.85rem;
                  text-transform:uppercase; letter-spacing:.06em;
                  color:#495057; margin-bottom:1rem; border-radius:0 4px 4px 0; }
    @media (max-width: 576px) { th, td { font-size: 0.75rem; padding: 0.25rem; } }
    .table td { word-wrap: break-word; }
    th.sortable { cursor: pointer; user-select: none; white-space: nowrap; }
    th.sortable:hover { background-color: #343a40; }
    th.sortable .fa { font-size: .75rem; opacity: .6; margin-left: 4px; }
    th.sortable.sort-asc .fa, th.sortable.sort-desc .fa { opacity: 1; }
</style>

<div>
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
        <i class="fa fa-bar-chart me-2 text-primary"></i>Inspection Summary Report
    </h5>
    <div class="d-flex gap-2">
        <div class="dropdown">
            <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                <i class="fa fa-table me-1"></i> Columns
            </button>
            <div class="dropdown-menu p-3" style="min-width:280px;max-height:60vh;overflow:auto;">
                <div class="d-flex justify-content-between mb-2">
                    <a href="#" class="small" id="columnsSelectAll">Select all</a>
                    <a href="#" class="small" id="columnsSelectNone">Clear</a>
                </div>
                @foreach ($availableColumns as $key => $col)
                    <div class="form-check">
                        <input class="form-check-input column-toggle" type="checkbox" value="{{ $key }}"
                               id="col_{{ $key }}" @checked(in_array($key, $selectedColumns))>
                        <label class="form-check-label small" for="col_{{ $key }}">{{ $col['label'] }}</label>
                    </div>
                @endforeach
                <hr class="my-2">
                <div class="d-flex gap-2">
                    <button type="submit" form="columnsApplyForm" class="btn btn-primary btn-sm flex-fill">Apply</button>
                    <button type="submit" form="columnsSaveForm" class="btn btn-outline-primary btn-sm flex-fill">Save as default</button>
                </div>
            </div>
        </div>

        <form id="columnsApplyForm" method="GET" action="{{ route('summarypage') }}">
            <input type="hidden" name="season" value="{{ $season }}">
            <input type="hidden" name="report" value="{{ $reportname->id }}">
            <input type="hidden" name="reportstate" value="{{ $state }}">
            <div id="columnsApplyFields"></div>
        </form>
        <form id="columnsSaveForm" method="POST" action="{{ route('savesummarycolumns') }}">
            @csrf
            <input type="hidden" name="season" value="{{ $season }}">
            <input type="hidden" name="report" value="{{ $reportname->id }}">
            <input type="hidden" name="reportstate" value="{{ $state }}">
            <div id="columnsSaveFields"></div>
        </form>

        <button type="button" class="btn btn-success btn-sm" onclick="exportSummaryToExcel()">
            <i class="fa fa-file-excel-o me-1"></i> Excel
        </button>
        <a class="btn btn-danger btn-sm" id="pdfExportLink"
           href="{{ route('summarypdf', ['season' => $season, 'report' => $reportname->id, 'reportstate' => $state, 'columns' => $selectedColumns]) }}">
            <i class="fa fa-file-pdf-o me-1"></i> PDF
        </a>
    </div>
</div>

{{-- ── REPORT META ──────────────────────────────────────────────────────── --}}
<div class="card shadow-sm mb-3">
    <div class="card-body">
        <div class="row g-2 justify-content-center text-center">
            <div class="col-auto"><b>Season:</b> {{ $season }}</div>
            <div class="col-auto"><b>Report Name:</b> {{ $reportname->reportname }}</div>
            <div class="col-auto">
                <b>Report State:</b>
                <span class="badge {{ $stateBadge($state) }}">{{ $state }}</span>
            </div>
        </div>
    </div>
</div>

{{-- ── METRIC CARDS ─────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card metric-card shadow-sm h-100">
            <div class="card-body">
                <div class="metric-sub mb-1">Total Records</div>
                <div class="metric-num text-success">{{ $totalRecords }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card metric-card shadow-sm h-100" style="border-left-color:#0d6efd">
            <div class="card-body">
                <div class="metric-sub mb-1">Unique Farms</div>
                <div class="metric-num" style="color:#0d6efd">{{ $totalFarms }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card metric-card shadow-sm h-100" style="border-left-color:#fd7e14">
            <div class="card-body">
                <div class="metric-sub mb-1">Total Farm Area (ha)</div>
                <div class="metric-num" style="color:#fd7e14">{{ number_format($totalArea, 2) }}</div>
            </div>
        </div>
    </div>
    @if ($reporttype == 'Entrance')
        <div class="col-6 col-md-3">
            <div class="card metric-card shadow-sm h-100" style="border-left-color:#20c997">
                <div class="card-body">
                    <div class="metric-sub mb-1">Total Estimated Yield (kg)</div>
                    <div class="metric-num" style="color:#20c997">{{ number_format($totalYield, 2) }}</div>
                </div>
            </div>
        </div>
    @else
        <div class="col-6 col-md-3">
            <div class="card metric-card shadow-sm h-100" style="border-left-color:#dc3545">
                <div class="card-body">
                    <div class="metric-sub mb-1">With Conditions</div>
                    <div class="metric-num text-danger">{{ $withConditions }}</div>
                </div>
            </div>
        </div>
    @endif
</div>

{{-- ── SEARCH ────────────────────────────────────────────────────────────── --}}
<div class="d-flex justify-content-between align-items-center mb-2">
    <div class="section-header mb-0">Records</div>
    <div style="max-width:280px;width:100%">
        <input type="text" id="summarySearch" class="form-control form-control-sm"
               placeholder="Search table…">
    </div>
</div>

{{-- ── TABLE ─────────────────────────────────────────────────────────────── --}}
<div class="card shadow-sm">
    <div class="card-body table-responsive">

<table class="table table-striped table-hover table-sm" id="inspectiondt" style="width:100%">
    <thead class="table-dark">
        <tr>
            @foreach ($selectedColumns as $key)
                <th class="sortable">{{ $availableColumns[$key]['label'] }}<i class="fa fa-sort"></i></th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @forelse ( $internalinspection as $inspection )
        <tr>
            @foreach ($selectedColumns as $key)
                <td>{{ ($availableColumns[$key]['value'])($inspection, $season) }}</td>
            @endforeach
        </tr>
        @empty
        <tr>
            <td colspan="{{ max(count($selectedColumns), 1) }}" class="text-center text-muted py-4">
                <i class="fa fa-inbox fa-2x mb-2 d-block"></i>
                No records found for this report.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

    </div>
</div>
</div>

<script>
    // ── Live client-side search (Bootstrap-native replacement for DataTables search) ──
    document.getElementById('summarySearch').addEventListener('keyup', function () {
        var term = this.value.trim().toLowerCase();
        var rows = document.querySelectorAll('#inspectiondt tbody tr');
        rows.forEach(function (row) {
            row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
        });
    });

    // ── Click-to-sort table headers (Bootstrap-native replacement for DataTables sort) ──
    document.querySelectorAll('#inspectiondt th.sortable').forEach(function (th, index) {
        th.addEventListener('click', function () {
            var table = document.getElementById('inspectiondt');
            var tbody = table.querySelector('tbody');
            var rows  = Array.from(tbody.querySelectorAll('tr'))
                .filter(function (row) { return row.children.length > 1; }); // skip empty-state row

            var asc = !th.classList.contains('sort-asc');

            table.querySelectorAll('th.sortable').forEach(function (h) {
                h.classList.remove('sort-asc', 'sort-desc');
                h.querySelector('.fa').className = 'fa fa-sort';
            });
            th.classList.add(asc ? 'sort-asc' : 'sort-desc');
            th.querySelector('.fa').className = 'fa ' + (asc ? 'fa-sort-asc' : 'fa-sort-desc');

            rows.sort(function (a, b) {
                var aText = a.children[index].textContent.trim();
                var bText = b.children[index].textContent.trim();
                var aNum  = parseFloat(aText.replace(/,/g, ''));
                var bNum  = parseFloat(bText.replace(/,/g, ''));
                var cmp;
                if (!isNaN(aNum) && !isNaN(bNum) && aText !== '' && bText !== '') {
                    cmp = aNum - bNum;
                } else {
                    cmp = aText.localeCompare(bText);
                }
                return asc ? cmp : -cmp;
            });

            rows.forEach(function (row) { tbody.appendChild(row); });
        });
    });

    // ── Columns picker: select all / clear, and sync checked columns into the two forms ──
    document.getElementById('columnsSelectAll').addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelectorAll('.column-toggle').forEach(function (cb) { cb.checked = true; });
    });
    document.getElementById('columnsSelectNone').addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelectorAll('.column-toggle').forEach(function (cb) { cb.checked = false; });
    });

    function syncColumnsInto(fieldsContainerId) {
        var container = document.getElementById(fieldsContainerId);
        container.innerHTML = '';
        document.querySelectorAll('.column-toggle:checked').forEach(function (cb) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'columns[]';
            input.value = cb.value;
            container.appendChild(input);
        });
    }

    document.getElementById('columnsApplyForm').addEventListener('submit', function () {
        syncColumnsInto('columnsApplyFields');
    });
    document.getElementById('columnsSaveForm').addEventListener('submit', function () {
        syncColumnsInto('columnsSaveFields');
    });

    // ── Excel export (client-side, no external library required) ─────────────────
    function exportSummaryToExcel() {
        var table = document.getElementById('inspectiondt');
        var html  = table.outerHTML;
        var blob  = new Blob(['﻿', html], { type: 'application/vnd.ms-excel' });
        var link  = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = 'Inspection_Summary_{{ $season }}.xls';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>

</x-layouts.app>
