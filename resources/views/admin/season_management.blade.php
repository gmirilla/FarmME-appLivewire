<x-layouts.app>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ── CURRENT SEASON STATUS ─────────────────────────────────────────── --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Season Management</h4>
        <span class="badge fs-6
            {{ $currentSeason && $currentSeason->status === 'OPEN' ? 'bg-success' : 'bg-secondary' }}">
            {{ $currentSeasonString }} &mdash;
            {{ $currentSeason ? $currentSeason->status : 'NOT SET' }}
        </span>
    </div>
    <div class="card-body">
        <div class="row g-3">

            {{-- Open season --}}
            <div class="col-md-6">
                <div class="card border-success h-100">
                    <div class="card-header bg-success text-white"><b>Open Season</b></div>
                    <div class="card-body">
                        <p class="text-muted small">
                            Opens <b>{{ $currentSeasonString }}</b>. All farms will be set to
                            <b>ACTIVE</b> and their next inspection date updated.
                        </p>
                        @if ($currentSeason && $currentSeason->status === 'OPEN')
                            <div class="alert alert-info mb-0">Season is already open.</div>
                        @else
                            <form method="POST" action="{{ route('season.open') }}"
                                  onsubmit="return confirm('Open {{ $currentSeasonString }}? This will set ALL farms to ACTIVE.')">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Next Inspection Date (all farms)</label>
                                    <input type="date" name="nextinspection_date" class="form-control"
                                           value="{{ $currentSeason?->nextinspection_date }}" required>
                                    @error('nextinspection_date')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fa fa-unlock"></i> Open Season
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Close season --}}
            <div class="col-md-6">
                <div class="card border-danger h-100">
                    <div class="card-header bg-danger text-white"><b>Close Season</b></div>
                    <div class="card-body">
                        <p class="text-muted small">
                            Closes <b>{{ $currentSeasonString }}</b>. All <b>ACTIVE</b> farms will
                            be set to <b>CLOSED</b>. Inspectors will no longer see farms in
                            farm entrance.
                        </p>
                        @if (!$currentSeason || $currentSeason->status === 'CLOSED')
                            <div class="alert alert-secondary mb-0">Season is already closed.</div>
                        @else
                            <form method="POST" action="{{ route('season.close') }}"
                                  onsubmit="return confirm('Close {{ $currentSeasonString }}? All ACTIVE farms will be set to CLOSED.')">
                                @csrf
                                <button type="submit" class="btn btn-danger w-100 mt-4">
                                    <i class="fa fa-lock"></i> Close Season
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── MASS ASSIGN INSPECTOR ─────────────────────────────────────────── --}}
<div class="card mb-4">
    <div class="card-header"><h4>Mass Assign Inspector</h4></div>
    <div class="card-body">
        <form method="POST" action="{{ route('season.massassign') }}"
              onsubmit="return confirmAssign()">
            @csrf

            @if ($errors->has('farm_ids'))
                <div class="alert alert-danger">Please select at least one farm.</div>
            @endif

            <div class="row mb-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Inspector</label>
                    <select name="inspector_id" class="form-select" required id="inspectorSelect">
                        <option value="">-- Select Inspector --</option>
                        @foreach ($inspectors as $inspector)
                            <option value="{{ $inspector->id }}"
                                {{ old('inspector_id') == $inspector->id ? 'selected' : '' }}>
                                {{ $inspector->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('inspector_id')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-outline-secondary" onclick="toggleSelectAll()">
                        <i class="fa fa-check-square-o"></i> Select / Deselect All
                    </button>
                </div>
                <div class="col-md-4 text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-user-plus"></i> Assign Selected Farms
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-sm display" id="assignTable" style="width:100%">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAllChk" onclick="toggleSelectAll()"></th>
                            <th>Community</th>
                            <th>Farm Code</th>
                            <th>Farmer Name</th>
                            <th>Status</th>
                            <th>Current Inspector</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($farms as $farm)
                            <tr>
                                <td>
                                    <input type="checkbox" name="farm_ids[]"
                                           value="{{ $farm->id }}" class="farm-chk"
                                           {{ is_array(old('farm_ids')) && in_array($farm->id, old('farm_ids')) ? 'checked' : '' }}>
                                </td>
                                <td>{{ $farm->community }}</td>
                                <td>{{ $farm->farmcode }}</td>
                                <td>{{ $farm->farmname }}</td>
                                <td>
                                    <span class="badge
                                        {{ $farm->farmstate === 'ACTIVE' ? 'bg-success' :
                                           ($farm->farmstate === 'CLOSED' ? 'bg-secondary' : 'bg-warning text-dark') }}">
                                        {{ $farm->farmstate ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>{{ $farm->getinspectorName() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>

{{-- ── SEASON HISTORY ────────────────────────────────────────────────── --}}
<div class="card mb-4">
    <div class="card-header"><h4>Season History</h4></div>
    <div class="card-body table-responsive">
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th>Season</th>
                    <th>Status</th>
                    <th>Next Inspection Date</th>
                    <th>Opened By</th>
                    <th>Closed By</th>
                    <th>Last Updated</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($seasons as $season)
                    <tr>
                        <td><b>{{ $season->season }}</b></td>
                        <td>
                            <span class="badge {{ $season->status === 'OPEN' ? 'bg-success' : 'bg-secondary' }}">
                                {{ $season->status }}
                            </span>
                        </td>
                        <td>{{ $season->nextinspection_date ?? '—' }}</td>
                        <td>{{ $season->openedBy?->name ?? '—' }}</td>
                        <td>{{ $season->closedBy?->name ?? '—' }}</td>
                        <td>{{ $season->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">No season records yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    var allSelected = false;

    function toggleSelectAll() {
        allSelected = !allSelected;
        document.querySelectorAll('.farm-chk').forEach(chk => chk.checked = allSelected);
        document.getElementById('selectAllChk').checked = allSelected;
    }

    function confirmAssign() {
        const inspector = document.getElementById('inspectorSelect');
        const inspectorName = inspector.options[inspector.selectedIndex].text;
        const checked = document.querySelectorAll('.farm-chk:checked').length;
        if (checked === 0) { alert('Please select at least one farm.'); return false; }
        return confirm('Assign ' + checked + ' farm(s) to ' + inspectorName + '?');
    }

    $(document).ready(function () {
        $('#assignTable').DataTable({
            pageLength: 50,
            order: [[1, 'asc']],
            columnDefs: [{ orderable: false, targets: 0 }]
        });
    });
</script>
</x-layouts.app>
