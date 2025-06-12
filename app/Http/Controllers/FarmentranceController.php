<?php

namespace App\Http\Controllers;

use App\Models\farmentrance;
use App\Models\farm;
use App\Models\farmunits;
use App\Models\internalinspection;
use Illuminate\Http\Request;

class FarmentranceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

        public function begin(Request $request)
    {
        //
        $farmerdetail=farm::where('farmcode', $request->fcode)->first();
        $year0=date('Y');
        $year1=$year0+1;
        $currentseason=$year0."/".$year1;
        $lastreport=internalinspection::where('farmid', $farmerdetail->id)->latest('updated_at')->first();
        $farmplots=farmunits::where('farmid', $farmerdetail->id)->get();

     

        return view('farmentance.beginfarmentrance', compact('farmerdetail','currentseason','lastreport','farmplots'));
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
    public function show(farmentrance $farmentrance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(farmentrance $farmentrance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, farmentrance $farmentrance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(farmentrance $farmentrance)
    {
        //
    }
}
