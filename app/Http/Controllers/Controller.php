<?php

namespace App\Http\Controllers;

use App\Models\farm;
use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    // Aborts with 403 if an INSPECTOR tries to access a farm not assigned to them.
    protected function authorizeInspectorFarmAccess(farm $targetFarm): void
    {
        $user = Auth::user();
        if ($user && $user->roles === 'INSPECTOR' && (int) $targetFarm->inspectorid !== (int) $user->id) {
            abort(403, 'You are not assigned to this farm.');
        }
    }
}
