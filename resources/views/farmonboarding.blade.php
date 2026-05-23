<x-layouts.app>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

@php
    $userid = Auth::user()->id;

    $statusBadge = fn($state) => match($state) {
        'ACTIVE'  => 'bg-success',
        'PENDING' => 'bg-warning text-dark',
        'CLOSED'  => 'bg-secondary',
        default   => 'bg-secondary',
    };

    $totalFarms   = $farmlist->count();
    $activeFarms  = $farmlist->where('farmstate', 'ACTIVE')->count();
    $pendingFarms = $farmlist->where('farmstate', 'PENDING')->count();
@endphp

{{-- Page toolbar --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">
        Farm Entrances &mdash; {{ $currentseason }}
    </h4>
    <span class="badge {{ $farmlist->isEmpty() ? 'bg-secondary' : 'bg-success' }} fs-6">
        Season {{ $farmlist->isEmpty() ? 'Closed' : 'Open' }}
    </span>
</div>

{{-- Metric Cards --}}
<div class="row g-2 mb-3">
    <div class="col-4">
        <div class="card text-center">
            <div class="card-body py-2">
                <div class="fs-4 fw-bold">{{ $totalFarms }}</div>
                <div class="text-muted small">Assigned Farms</div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card text-center border-success">
            <div class="card-body py-2">
                <div class="fs-4 fw-bold text-success">{{ $activeFarms }}</div>
                <div class="text-muted small">Active</div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card text-center border-warning">
            <div class="card-body py-2">
                <div class="fs-4 fw-bold text-warning">{{ $pendingFarms }}</div>
                <div class="text-muted small">Pending Entrance</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body table-responsive offline">
        <table>
            <tbody id="farm-rows"><!-- JS will populate this when offline --></tbody>
        </table>

        <input type="hidden" value="{{ $userid }}" id="userid">

        <table class="table table-striped display" id="farms">
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
            <tbody>
                @php $counter = 0; @endphp
                @forelse ($farmlist as $farm)
                    @php $counter++; @endphp
                    <tr>
                        <td>{{ $counter }}</td>
                        <td>{{ $farm->community }}</td>
                        <td>{{ $farm->farmcode }}</td>
                        <td>{{ $farm->farmname }}</td>
                        <td>
                            <span class="badge {{ $statusBadge($farm->farmstate) }}">
                                {{ $farm->farmstate }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="/farm/view?id={{ $farm->farmcode }}"
                                   class="btn btn-outline-success btn-sm" title="View Farm">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="{{ route('feprofile') }}?fcode={{ $farm->farmcode }}"
                                   class="btn btn-success btn-sm" title="Begin Farm Entrance">
                                    <i class="fa fa-pencil"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="fa fa-inbox fa-2x mb-2 d-block"></i>
                            @if ($farmlist->isEmpty())
                                Season is closed or no farms assigned for this season.
                            @else
                                No farms registered on the system.
                            @endif
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
            '<div class="offline-banner alert alert-warning m-2">You\'re offline. Showing cached data.</div>');
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
                        <th>#</th><th>Community</th><th>Farm Code</th>
                        <th>Farm Name</th><th>Status</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody id="farm-rows"></tbody>
            </table>`;
        document.querySelector(".offline").innerHTML = tableHTML;

        const x = document.getElementById("userid").value;

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
                                <button class="btn btn-secondary btn-sm"
                                        data-code="${farm.farmcode}"
                                        data-state="${farm.farmstate}">
                                    Offline Form
                                </button>
                            </td>
                        </tr>`;
                });

            if (rowsHTML === "") {
                rowsHTML = `<tr><td colspan="6" class="text-center">No farms found for this inspector.</td></tr>`;
            }

            tbody.innerHTML = rowsHTML;

            document.querySelectorAll(".btn-secondary").forEach(btn => {
                btn.addEventListener("click", () => {
                    goToOfflineFormFE(btn.dataset.code, btn.dataset.state);
                });
            });
        });
    }

    function goToOfflineFormFE(farmcode, farmstate) {
        if (farmstate == "PENDING") {
            localStorage.setItem("selectedFarm", farmcode);
            window.location.href = "/offline-fe";
        } else {
            alert("Unable to start Farm Entrance: farmer is not in PENDING state.");
        }
    }
</script>
</x-layouts.app>
