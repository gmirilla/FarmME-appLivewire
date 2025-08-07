<?php

namespace App\Http\Controllers;

use App\Models\misccodes;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;

class MisccodesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function miscyieldest_show()
    {
        //
        $year0 = date('Y');
        $year1 = $year0 + 1;
        $currentseason = $year0 . "/" . $year1;
        $yieldcodes = misccodes::where('parameter', 'yieldest')->get();

        return view('adminconfig.yieldestimates', compact('yieldcodes', 'currentseason'));
    }

    public function miscyieldest_add(Request $request)
    {
        //
        // dd($request);



        $active = $request->boolean('active');


        $validated = $request->validate([
            'value' => 'required|string',
            'season' => 'required|string',
            'system' => 'required|string',
        ]);


        $exists = misccodes::where('season', $validated['season'])
            ->where('system', $validated['system'])
            ->where('active', $active)
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'season' => 'The combination of season, system, and active must be unique.',
            ])->withInput();
        }



        // Create a new record
        $active = $request->has('active') && $request->active === 'on';

        $record = misccodes::create([
            'farmid' => 9999,
            'parameter' => 'yieldest',
            'active' => $active,
            ...$validated,
        ]);

        return redirect()->route('mye_show');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(misccodes $misccodes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(misccodes $misccodes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, misccodes $misccodes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(misccodes $misccodes)
    {
        //
    }
}
