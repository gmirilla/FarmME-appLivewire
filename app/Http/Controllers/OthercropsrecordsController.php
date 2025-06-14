<?php

namespace App\Http\Controllers;

use App\Models\othercropsrecords;
use Illuminate\Http\Request;

class OthercropsrecordsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
            public function disable(Request $request)
    {
        //

        $fcode='fcode='.$request->farmcode;

        $othercrop=othercropsrecords::where('id', $request->oid)->first();
        $othercrop->active=false;
        $othercrop->save();

                
        $fcode='fcode='.$request->farmcode;

        return redirect()->route('begin',$fcode);

    }

        public function add(Request $request)
    {
        //'season','plotname','crop', 'area', 'location','active','farmid'

        $newotherplot= new othercropsrecords();
        $newotherplot->season=$request->season;
        $newotherplot->plotname=$request->otherplotname;
        $newotherplot->crop=$request->otherplotcrop;
        $newotherplot->area=$request->otherplotarea;
        $newotherplot->location=$request->otherplotlocation;
        $newotherplot->farmid=$request->farmid;
        $newotherplot->farmentranceid=$request->farmentranceid;
        $newotherplot->save();

        $fcode='fcode='.$request->farmcode;
        

        return redirect()->route('begin',$fcode);

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
    public function show(othercropsrecords $othercropsrecords)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(othercropsrecords $othercropsrecords)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, othercropsrecords $othercropsrecords)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(othercropsrecords $othercropsrecords)
    {
        //
    }
}
