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

       // dd($request);

        $section=reportsection::where('id', $request->sectionid)->first();
        
        $report=reports::where('id', $request->reportid)->first();
        $questions=reportquestions::where('reportsectionid',$request->sectionid)->orderBy('question_seq', 'asc')->get();



                #Get all ACTIVE questions on current report
                $allquestions=reportquestions::where('reportid',$request->reportid)->where('questionstate','ACTIVE')->get();

                #Calculte and Update report Maximum scores 
                #QUESTION TYPEA (YES/NO) =2
                #QUESTION TYPEB (POOR/FAIR/GOOD/VERYGOOD)=4 
                $maxscore=0;
               
                foreach ($allquestions as $question) {
                   switch ($question->questiontype) {
                       case 'TYPEB':
                           $maxscore=$maxscore+4;
                           break; 
                       case 'TYPEA':
                           $maxscore=$maxscore+2;
                           break;
                   }
                }
                $report=reports::where('id', $request->reportid)->first();
                $report->max_score=$maxscore;
                $report->save();
        
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

        $questions=reportquestions::where('reportsectionid',$request->reportsectionid)->orderBy('question_seq', 'asc')->get();

        #Get all ACTIVE questions on current report
        $allquestions=reportquestions::where('reportid',$request->reportid)->where('questionstate','ACTIVE')->get();

         #Calculte and Update report Maximum scores 
         #QUESTION TYPEA (YES/NO) =2
         #QUESTION TYPEB (POOR/FAIR/GOOD/VERYGOOD)=4 
         $maxscore=0;
         foreach ($allquestions as $question) {
            switch ($question->questiontype) {
                case 'TYPEB':
                    $maxscore=$maxscore+4;
                    
                case 'TYPEA':
                    $maxscore=$maxscore+2;
                default:
                    $maxscore=0;
            }
         }
         $report->max_score=$maxscore;
         $report->save();

 


        return view('report.reportquestion')
        ->with('section', $section)
        ->with('questions',$questions)
        ->with('report', $report);
    }


    /**
     * Show the form for editing a question.
     */
    public function editquestion(Request $request)
    {
        //TO DO **
        

        return view('report.report_edit_question');
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
