<?php

namespace App\Http\Controllers;

use App\Models\reports;
use App\Models\reportsection;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

    }


    public function new(Request $request)
    {
        //
        $allreports=reports::all();
        $sections=reportsection::all();
        $selectedreport=0;


        return view('report.report')
        ->with('reports', $allreports)
        ->with('sections',$sections)
        ->with('selectedreport',$selectedreport);

    }
//Create a new report and return to view
    public function new_report(Request $request)
    {
        //
        $validate=$request->validate(['reportname'=> 'required|unique:reports,reportname']);
        $newreport= new reports();
        $newreport->reportname=$request->reportname;
        $newreport->reportstate='ACTIVE';
        $newreport->save();
        $allreports=reports::all();

        $sections=reportsection::all(); 
        $selectedreport=$newreport->id;

        return view('report.report')
        ->with('reports', $allreports)
        ->with('sections',$sections)
        ->with('selectedreport',$selectedreport);

        

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
    public function show(reports $reports)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(reports $reports)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, reports $reports)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(reports $reports)
    {
        //
    }
}
