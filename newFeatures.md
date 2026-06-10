# New Features — Implementation Guide

This document describes features added to FarmME that are not covered in `CLAUDE.md`.
Each section explains what the feature does, what was changed, and how to replicate it
in a sibling project with a similar structure.

---

## Feature: Disable / Re-enable Farms

### What it does

- An **ADMINISTRATOR** can set any farm's state to `DISABLED` via the existing farm status modal on the farm detail page.
- Disabled farms are **hidden from every listing** — the main farm list, the onboarding (farm entrance) list, the new-inspection farm picker, and the dashboard counts.
- Inspectors who attempt to reach a disabled farm via a direct URL receive a 403.
- A dedicated **Disabled Farms** admin page lists all disabled farms and allows the administrator to re-enable each one, choosing whether to restore it as `ACTIVE` or `PENDING`.

### No migration required

The `farmstate` column already supports arbitrary string values. `DISABLED` was already an option in the existing farm status modal. No database change is needed.

---

### Files changed

| File | What changed |
|---|---|
| `app/Http/Controllers/FarmController.php` | `index()`, `onboarding()`, new `disabled()`, new `reenable()`, guard in `displayfarm()` |
| `app/Http/Controllers/InternalinspectionController.php` | `new()` — excludes DISABLED from farm picker |
| `routes/web.php` | Two new admin routes: `GET /farm/disabled`, `POST /farm/reenable` |
| `resources/views/farm.blade.php` | "Disabled Farms" button added to admin toolbar |
| `resources/views/admin/disabled_farms.blade.php` | New view (created) |

---

### How to replicate in a sibling project

#### 1. Confirm the state column supports `DISABLED`

Your farm/entity table needs a string state column (e.g. `farmstate`). If it only holds fixed enum values, add `DISABLED` to the migration:

```php
// In a new migration:
Schema::table('farms', function (Blueprint $table) {
    // If farmstate is a plain varchar this is a no-op — the value is already allowed.
    // If it is a MySQL ENUM, alter it:
    DB::statement("ALTER TABLE farms MODIFY COLUMN farmstate ENUM('PENDING','ACTIVE','CLOSED','REMEDIAL','DISABLED')");
});
```

If it is already a plain `string` column, skip this step entirely.

---

#### 2. Exclude DISABLED from all listings

Find every controller method that queries the entity table and add a `where` clause.

**Main list (admin sees all non-disabled, inspector sees their own non-disabled):**

```php
// Administrator branch
$farmlist = farm::where('farmstate', '!=', 'DISABLED')
    ->orderBy('farmcode')->get();

// Inspector branch
$farmlist = farm::where('inspectorid', $user->id)
    ->whereNotIn('farmstate', ['CLOSED', 'DISABLED'])
    ->orderBy('farmcode')->get();
```

**Onboarding / secondary list (admin branch):**

```php
$farmlist = farm::where('farmstate', '!=', 'DISABLED')
    ->orderByDesc('created_at')->get();
```

**Inspection new-inspection farm picker:**

```php
$farms = farm::where('inspectorid', $user->id)
    ->where('farmstate', '!=', 'DISABLED')
    ->get();
```

**Rule of thumb:** Any `farm::` query that populates a user-facing list needs the exclusion clause. Search for `farm::where` and `farm::all()` across your controllers.

---

#### 3. Guard the detail view against direct URL access

In the method that loads and renders a single farm (e.g. `displayfarm()`), add this block **after** the farm is loaded and **after** any existing authorization check:

```php
$farmdetails = farm::where('farmcode', $request->id)->firstOrFail();
// ... existing authorization check ...

if ($farmdetails->farmstate === 'DISABLED' && $authuser->roles !== 'ADMINISTRATOR') {
    abort(403, 'This farm is disabled.');
}
```

This ensures that even if an inspector has a bookmarked URL, they cannot view a disabled farm.

---

#### 4. Add `disabled()` and `reenable()` to the Farm controller

```php
public function disabled()
{
    Auth::check();
    $user = Auth::user();

    if ($user->roles !== 'ADMINISTRATOR') {
        return redirect()->route('unauthorized');
    }

    $farmlist = farm::where('farmstate', 'DISABLED')
        ->orderBy('community')
        ->orderBy('farmcode')
        ->get();

    return view('admin.disabled_farms', compact('farmlist', 'user'));
}

public function reenable(Request $request)
{
    Auth::check();
    $user = Auth::user();

    if ($user->roles !== 'ADMINISTRATOR') {
        return redirect()->route('unauthorized');
    }

    $request->validate([
        'farmid'   => 'required|exists:farms,id',
        'newstate' => 'required|in:ACTIVE,PENDING',
    ]);

    farm::where('id', $request->farmid)
        ->where('farmstate', 'DISABLED')   // safety: only re-enable if actually disabled
        ->update(['farmstate' => $request->newstate]);

    return redirect()->route('disabled.farms')
        ->with('success', 'Farm re-enabled as ' . $request->newstate . '.');
}
```

The `->where('farmstate', 'DISABLED')` on the update is a safety rail — it prevents `reenable()` from accidentally updating a farm that is already active if the form is replayed.

---

#### 5. Add routes (admin-only)

In `routes/web.php`, inside an admin-middleware group:

```php
Route::middleware(['auth', 'verified', RoleMiddleware::class . ':ADMINISTRATOR'])
    ->group(function () {
        Route::get('/farm/disabled',  [FarmController::class, 'disabled'])->name('disabled.farms');
        Route::post('/farm/reenable', [FarmController::class, 'reenable'])->name('farm.reenable');
    });
```

Place these **before** the `require __DIR__.'/auth.php'` line.

---

#### 6. Add the toolbar button to the farm list view

In the admin toolbar section of your farm list blade:

```blade
@if ($user->roles == 'ADMINISTRATOR')
    <a href="{{ route('disabled.farms') }}" class="btn btn-secondary" style="margin:5px">
        <i class="fa fa-ban"></i> Disabled Farms
    </a>
@endif
```

---

#### 7. Create `resources/views/admin/disabled_farms.blade.php`

The view needs:

- A **summary banner** showing how many farms are disabled (or a "none" message).
- A **DataTable** with columns: Farm Code, Farm Name, Community, Crop, Last Inspector, Last Inspection, Re-enable button.
- A **Re-enable modal** that shows the farm name (populated via `data-*` attributes) and a dropdown to choose between `ACTIVE` and `PENDING`.
- The modal form POSTs to `route('farm.reenable')` with `farmid` (the numeric DB id) and `newstate`.

Key modal pattern:

```blade
<button type="button"
        class="btn btn-success btn-sm"
        data-bs-toggle="modal"
        data-bs-target="#reEnableModal"
        data-farm-id="{{ $farm->id }}"
        data-farm-name="{{ $farm->farmname }}"
        data-farm-code="{{ $farm->farmcode }}">
    <i class="fa fa-check-circle"></i> Re-enable
</button>

{{-- Modal --}}
<div class="modal fade" id="reEnableModal" ...>
    <form action="{{ route('farm.reenable') }}" method="POST">
        @csrf
        <input type="hidden" name="farmid" id="modalFarmId">
        <select name="newstate" class="form-select" required>
            <option value="ACTIVE">ACTIVE</option>
            <option value="PENDING">PENDING</option>
        </select>
        <button type="submit" class="btn btn-success">Re-enable Farm</button>
    </form>
</div>

<script>
document.getElementById('reEnableModal').addEventListener('show.bs.modal', function (event) {
    var btn = event.relatedTarget;
    document.getElementById('modalFarmId').value = btn.getAttribute('data-farm-id');
    // populate display name as needed
});
</script>
```

---

### Checklist

- [ ] Confirm `farmstate` column is a plain `string` (or add `DISABLED` to ENUM if needed)
- [ ] Add `where('farmstate', '!=', 'DISABLED')` to every farm listing query
- [ ] Add DISABLED guard to `displayfarm()` (or equivalent detail method)
- [ ] Add `disabled()` method to FarmController
- [ ] Add `reenable()` method to FarmController (with `->where('farmstate','DISABLED')` safety rail on update)
- [ ] Add two admin-only routes: `GET /farm/disabled`, `POST /farm/reenable`
- [ ] Add "Disabled Farms" button to admin toolbar in farm list view
- [ ] Create `resources/views/admin/disabled_farms.blade.php` with DataTable + re-enable modal
- [ ] Verify: disabled farm URL returns 403 for inspector
- [ ] Verify: re-enabled farm appears in the main farm list with correct state

---

## Feature: Season Management

See `CLAUDE.md` — Season Management section for full implementation details.

## Security & Quality Fixes (Fixes 1–5)

See `CLAUDE.md` — Security & Quality Fixes Applied section for full details.
