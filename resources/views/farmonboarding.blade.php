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
    $userid        = Auth::user()->id;
    $seasonOpen    = \App\Models\Season::isOpen();
    $currentSeason = \App\Models\Season::currentString();

    $statusBadge = fn($state) => match($state) {
        'ACTIVE'   => 'bg-success',
        'PENDING'  => 'bg-warning text-dark',
        'CLOSED'   => 'bg-secondary',
        'REMEDIAL' => 'bg-danger',
        'DISABLED' => 'bg-dark',
        default    => 'bg-light text-dark border',
    };

    $total   = $farmlist->count();
    $active  = $farmlist->where('farmstate', 'ACTIVE')->count();
    $pending = $farmlist->where('farmstate', 'PENDING')->count();
@endphp

<style>
    .metric-card    { border-left: 4px solid #198754; }
    .metric-num     { font-size: 1.8rem; font-weight: 700; line-height: 1.1; }
    .metric-sub     { font-size: .75rem; color: #6c757d; }
    .page-toolbar   { display: flex; align-items: center; justify-content: space-between;
                      flex-wrap: wrap; gap: .5rem; margin-bottom: 1.25rem; }
    .page-title     { font-size: 1.25rem; font-weight: 700; color: #212529; margin: 0; }
    .offline-banner { background: #ffc107; color: #212529; padding: .5rem 1rem;
                      text-align: center; font-weight: 600; margin-bottom: 1rem;
                      border-radius: 6px; }
</style>

{{-- ── PAGE TOOLBAR ─────────────────────────────────────────────────────── --}}
<div class="page-toolbar">
    <h5 class="page-title">
        <i class="fa fa-pencil-square-o me-2 text-success"></i>Farm Entrance
    </h5>
    <span class="badge {{ $seasonOpen ? 'bg-success' : 'bg-secondary' }}" style="font-size:.8rem">
        <i class="fa fa-calendar me-1"></i>
        {{ $currentSeason }} &mdash; {{ $seasonOpen ? 'Season Open' : 'Season Closed' }}
    </span>
</div>

{{-- ── METRIC CARDS ─────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <div class="card metric-card shadow-sm h-100">
            <div class="card-body">
                <div class="metric-sub mb-1">Assigned Farms</div>
                <div class="metric-num text-success">{{ $total }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="card metric-card shadow-sm h-100" style="border-left-color:#198754">
            <div class="card-body">
                <div class="metric-sub mb-1">Active</div>
                <div class="metric-num text-success">{{ $active }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="card metric-card shadow-sm h-100" style="border-left-color:#fd7e14">
            <div class="card-body">
                <div class="metric-sub mb-1">Pending Entrance</div>
                <div class="metric-num" style="color:#fd7e14">{{ $pending }}</div>
            </div>
        </div>
    </div>
</div>

{{-- hidden userid for offline JS --}}
<input type="text" value="{{ $userid }}" hidden disabled id="userid">

{{-- ── OFFLINE MODE CONTAINER ────────────────────────────────────────────── --}}
<div class="offline"></div>

{{-- ── FARM TABLE ────────────────────────────────────────────────────────── --}}
<div class="card shadow-sm">
    <div class="card-body table-responsive">
        <table class="table table-striped table-hover table-sm align-middle display"
               id="farms" style="width:100%">
            <thead class="table-dark">
                <tr>
                    <th>Community</th>
                    <th>Farm Code</th>
                    <th>Farm Name</th>
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
                        <td>{{ $farm->farmname }}</td>
                        <td>
                            <span class="badge {{ $statusBadge($farm->farmstate) }}">
                                {{ $farm->farmstate }}
                            </span>
                        </td>
                        <td>
                            <a href="/farm/view?id={{ $farm->farmcode }}"
                               class="btn btn-outline-success btn-sm"
                               title="View Farm">
                                <i class="fa fa-eye"></i>
                            </a>
                            <a href="{{ route('feprofile') }}?fcode={{ $farm->farmcode }}"
                               class="btn btn-success btn-sm"
                               title="Begin Farm Entrance">
                                <i class="fa fa-pencil"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="fa fa-inbox fa-2x mb-2 d-block"></i>
                            {{ $seasonOpen
                                ? 'No farms assigned to you.'
                                : 'Season is closed. No farms available for entrance.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    window.addEventListener('offline', () => {
        document.body.insertAdjacentHTML('afterbegin',
            '<div class="offline-banner"><i class="fa fa-wifi"></i> You\'re offline. Showing cached data.</div>');
    });
</script>

<script>
    $(document).ready(function () {
        $('#farms').DataTable({
            dom: 'Bfrtip',
            pageLength: 200,
            order: [[1, 'asc']],
            stateSave: true,
            buttons: [{
                extend: 'excelHtml5',
                title: 'LIST_OF_FARMERS',
                exportOptions: { columns: ':visible' }
            }]
        });
    });
</script>

<script>
    const farmData   = @json($farmlist);
    const farms      = Object.values(farmData);
    const reportData = @json($reports);
    const reports    = Object.values(reportData);

    if (navigator.onLine) {
        farms.forEach(farm => {
            db.farms.put({
                farmcode:    farm.farmcode,
                community:   farm.community,
                farmname:    farm.farmname,
                farmstate:   farm.farmstate,
                inspectorid: farm.inspectorid
            });
        });

        reports.forEach(report => {
            db.reports.put({
                reportid:    report.id,
                reportname:  report.reportname,
                reportstate: report.reportstate
            });
        });
    }
</script>

<script>
    if (!navigator.onLine) {
        console.log("Offline mode detected. Redirecting to offline view...");
        const tableHTML = `
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Community</th>
                        <th>Farm Code</th>
                        <th>Farm Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="farm-rows"></tbody>
            </table>`;
        document.querySelector(".offline").innerHTML = tableHTML;

        const currentUserId = document.getElementById("userid");
        const x = currentUserId.value;

        db.farms.toArray().then(farms => {
            const tbody = document.getElementById("farm-rows");
            let rowsHTML = "";

            farms
                .filter(farm => farm.inspectorid == x)
                .forEach((farm, index) => {
                    rowsHTML += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${farm.community}</td>
                            <td>${farm.farmcode}</td>
                            <td>${farm.farmname}</td>
                            <td>${farm.farmstate}</td>
                            <td>
                                <button class="btn btn-secondary" data-code="${farm.farmcode}" data-state="${farm.farmstate}">
                                    Offline Form
                                </button>
                            </td>
                        </tr>
                    `;
                });

            if (rowsHTML === "") {
                rowsHTML = `
                    <tr>
                        <td colspan="6" class="text-center">No farms found for this inspector.</td>
                    </tr>
                `;
            }

            tbody.innerHTML = rowsHTML;

            document.querySelectorAll(".btn-secondary").forEach(btn => {
                btn.addEventListener("click", () => {
                    const farmcode  = btn.dataset.code;
                    const farmstate = btn.dataset.state;
                    goToOfflineFormFE(farmcode, farmstate);
                });
            });
        });
    }

    function goToOfflineFormFE(farmcode, farmstate) {
        console.log("Farm state:", farmstate);
        if (farmstate == "PENDING") {
            localStorage.setItem("selectedFarm", farmcode);
            window.location.href = "/offline-fe";
        } else {
            alert("Unable to start Farm Entrance: farmer is not in PENDING state.");
        }
    }
</script>

</x-layouts.app>
