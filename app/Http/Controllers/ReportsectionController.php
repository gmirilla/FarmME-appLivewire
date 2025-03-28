<?php

namespace App\Http\Controllers;

use App\Models\reportsection;
use App\Models\reports;
use Illuminate\Http\Request;

class ReportsectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function newsection(Request $request)
    {
        //


        $newsection = new reportsection();
        $newsection->reportid=$request->reportid;
        $newsection->sectionname=$request->sectionname;
        $newsection->section_seq=$request->section_seq;
        $newsection->sectionstate=$request->sectionstate;

        $newsection->save();

        $sections=reportsection::where('reportid',  $request->reportid)->orderBy('section_seq', 'asc')->get();
        $report=reports::where('id',  $request->reportid)->get();


        return view('report.reportsection')
        ->with('sections', $sections)
        ->with('reports', $report);
    }

    public function getsection(Request $report)
    {
        //

    $sections=reportsection::where('reportid',$report->reportid)->get();

        

        return response()->json($sections);
    }

    public function showsection(Request $request)
    {
        //
        

    $sections=reportsection::where('reportid',$request->reportid)->orderBy('section_seq', 'asc')->get();
    $report=reports::where('id',$request->reportid)->get();


        

        return view('report.reportsection')->with('sections', $sections)->with('reports', $report);
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
    public function show(reportsection $reportsection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(reportsection $reportsection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, reportsection $reportsection)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(reportsection $reportsection)
    {
        //
    }
}
