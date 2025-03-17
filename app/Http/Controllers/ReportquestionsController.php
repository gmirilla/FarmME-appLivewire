<?php

namespace App\Http\Controllers;

use App\Models\reportquestions;
use App\Models\reportsection;
use App\Models\reports;
use Illuminate\Http\Request;

class ReportquestionsController extends Controller
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
    public function showquestion(Request $request)
    {
        //
       // dd($request);

        $section=reportsection::where('id', $request->sectionid)->first();
        
        $report=reports::where('id', $request->reportid)->first();
        $questions=reportquestions::where('reportsectionid',$request->sectionid)->get();
 
     //dd($sections);

        return view('report.reportquestion')
        ->with('section', $section)
        ->with('questions',$questions)
        ->with('report', $report);
    }


    public function newquestion(Request $request)
    {
        //

        $section=reportsection::where('id', $request->reportsectionid)->first();
        
        $report=reports::where('id', $request->reportid)->first();

        $newquestion= new reportquestions() ;
        $newquestion->reportid=$request->reportid;
        $newquestion->reportsectionid=$request->reportsectionid;
        $newquestion->question_seq=$request->question_seq;
        $newquestion->question=$request->question;
        $newquestion->questiontype=$request->questiontype;
        $newquestion->questionstate='ACTIVE';

        $newquestion->save();

        $questions=reportquestions::where('reportsectionid',$request->reportsectionid)->get();

 


        return view('report.reportquestion')
        ->with('section', $section)
        ->with('questions',$questions)
        ->with('report', $report);
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
    public function show(reportquestions $reportquestions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(reportquestions $reportquestions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, reportquestions $reportquestions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(reportquestions $reportquestions)
    {
        //
    }
}
