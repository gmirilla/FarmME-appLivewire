<?php

namespace App\Http\Controllers;

use App\Models\farm;
use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    protected function authorizeInspectorFarmAccess(farm $targetFarm): void
    {
        $user = Auth::user();
        if ($user && $user->roles === 'INSPECTOR' && (int) $targetFarm->inspectorid !== (int) $user->id) {
            abort(403, 'You are not assigned to this farm.');
        }
    }
}
