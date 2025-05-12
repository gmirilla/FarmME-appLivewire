<?php

namespace App\Http\Controllers;

use App\Models\farm;
use App\Models\farmunits;
use App\Models\farmunityield;
use Illuminate\Http\Request;

class FarmunityieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

        /**
     * Display a listing of all plot yields for a farm.
     */
    public function list(Request $request)
    {
        //
        $farmyields=farmunityield::where('farmid',$request->farmid)->get();
        $farmunits=farmunits::where('farmid',$request->farmid)->get();
        $farm=farm::where('id',$request->farmid)->first();

        return view('yields.listyields',compact('farmyields','farmunits', 'farm'));
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
    public function show(farmunityield $farmunityield)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(farmunityield $farmunityield)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, farmunityield $farmunityield)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(farmunityield $farmunityield)
    {
        //
    }
}
