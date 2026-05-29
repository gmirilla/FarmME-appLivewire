<?php

namespace App\Http\Controllers;

use App\Models\farm;
use App\Models\Season;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SeasonController extends Controller
{
    public function index()
    {
        $currentSeasonString = Season::currentString();
        $currentSeason = Season::current();
        $seasons = Season::orderByDesc('created_at')->get();
        $inspectors = User::where('roles', 'INSPECTOR')->orderBy('name')->get();
        $farms = farm::orderBy('community')->orderBy('farmcode')->get();

        return view('admin.season_management', compact(
            'currentSeasonString',
            'currentSeason',
            'seasons',
            'inspectors',
            'farms'
        ));
    }

    public function open(Request $request)
    {
        $request->validate([
            'nextinspection_date' => 'required|date',
        ]);

        $seasonString = Season::currentString();

        DB::transaction(function () use ($request, $seasonString) {
            // Upsert the season record
            $season = Season::firstOrNew(['season' => $seasonString]);
            $season->status = 'OPEN';
            $season->nextinspection_date = $request->nextinspection_date;
            $season->opened_by = Auth::id();
            $season->save();

            // Mark all farms ACTIVE and set next inspection date
            farm::query()->update([
                'farmstate'      => 'ACTIVE',
                'inspectorid' => null,
                'nextinspection' => $request->nextinspection_date,
            ]);
        });

        return redirect()->route('season.index')
            ->with('success', "Season {$seasonString} opened. All farms set to ACTIVE with next inspection on {$request->nextinspection_date}.");
    }

    public function close(Request $request)
    {
        $seasonString = Season::currentString();

        DB::transaction(function () use ($seasonString) {
            $season = Season::firstOrNew(['season' => $seasonString]);
            $season->status = 'CLOSED';
            $season->closed_by = Auth::id();
            $season->save();

            // Mark all currently ACTIVE farms as CLOSED
            farm::where('farmstate', 'ACTIVE')->update(['farmstate' => 'CLOSED']);
        });

        return redirect()->route('season.index')
            ->with('success', "Season {$seasonString} closed. All active farms set to CLOSED.");
    }

    public function massassign(Request $request)
    {
        $request->validate([
            'inspector_id' => 'required|exists:users,id',
            'farm_ids'     => 'required|array|min:1',
            'farm_ids.*'   => 'exists:farms,id',
        ]);

        $inspector = User::findOrFail($request->inspector_id);
        $count = farm::whereIn('id', $request->farm_ids)
            ->update(['inspectorid' => $request->inspector_id]);

        return redirect()->route('season.index')
            ->with('success', "{$count} farm(s) assigned to {$inspector->name}.");
    }
}
