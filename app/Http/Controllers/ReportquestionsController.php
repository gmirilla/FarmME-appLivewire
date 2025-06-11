<?php

namespace App\Http\Controllers;


use App\Models\reportquestions;
use App\Models\reportsection;
use App\Models\reports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

                //Check if user is authorized to view resource
            
                Auth::check();
                $user = Auth::user();
        
                if ($user->roles!='ADMINISTRATOR') {
                    return view('unauthorized');
                }


        $section=reportsection::where('id', $request->sectionid)->first();
        
        $report=reports::where('id', $request->reportid)->first();
        $questions=reportquestions::where('reportsectionid',$request->sectionid)->orderBy('question_seq', 'asc')->get();



                #Get all ACTIVE questions on current report
                $allquestions=reportquestions::where('reportid',$request->reportid)->where('questionstate','ACTIVE')->get();

                #Calculte and Update report Maximum scores 
                #QUESTION TYPEA (YES/NO) =2
                #QUESTION TYPEB (POOR/FAIR/GOOD/VERYGOOD)=4 
                #QUESTION TYPEC Comment Only =1
           $maxscore=0;
         foreach ($allquestions as $question) {
            switch ($question->questiontype) {
                case 'TYPEB':
                    $maxscore=$maxscore+4;
                    break;
                    
                case 'TYPEA':
                    $maxscore=$maxscore+2;
                    break;

                case 'TYPEC':
                        $maxscore=$maxscore+1;
                        break;
                default:
                  ;
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
                        //Check if user is authorized to view resource
                        Auth::check();
                        $user = Auth::user();
                
                        if ($user->roles!='ADMINISTRATOR') {
                            return view('unauthorized');
                        }

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
         #QUESTION TYPEC Comment Only =1
         $maxscore=0;
         foreach ($allquestions as $question) {
            switch ($question->questiontype) {
                case 'TYPEB':
                    $maxscore=$maxscore+4;
                    break;
                    
                case 'TYPEA':
                    $maxscore=$maxscore+2;
                    break;

                case 'TYPEC':
                        $maxscore=$maxscore+1;
                        break;
                default:
                    $maxscore=0;
                    break;
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
                        //Check if user is authorized to view resource
                        Auth::check();
                        $user = Auth::user();
                
                        if ($user->roles!='ADMINISTRATOR') {
                            return view('unauthorized');
                        }
                
                
                        $question=reportquestions::where('id',$request->questionid)->first();
                        $report=reports::where('id', $question->reportid)->first();
                        $section=reportsection::where('id', $question->reportsectionid)->first();
                        $question->question_seq=$request->questionseq;
                        switch ($request->questionstate) {
                            case 'on':
                                # code...
                            $question->questionstate='ACTIVE';
                                break;

                            case 'off':
                                # code...
                            $question->questionstate='DISABLED';
                                break;
                            
                            default:
                                # code...
                                break;
                        }

                        $questions=reportquestions::where('reportsectionid',$question->reportsectionid)->orderBy('question_seq', 'asc')->get();

                    
                        $question->save();

                return view('report.reportquestion')
        ->with('section', $section)
        ->with('questions',$questions)
        ->with('report', $report);
    }

}
