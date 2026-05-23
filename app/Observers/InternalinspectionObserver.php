<?php

namespace App\Observers;

use App\Models\internalinspection;
use Illuminate\Support\Facades\Auth;

class InternalinspectionObserver
{
    public function saving(internalinspection $inspection): void
    {
        if (Auth::check()) {
            $inspection->updated_uid = Auth::id();
        }
    }
}
