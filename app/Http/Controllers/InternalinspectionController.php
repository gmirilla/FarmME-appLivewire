<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


use App\Models\farm;
use App\Models\inspectionanswers;
use App\Models\internalinspection;
use App\Models\reportquestions;
use App\Models\reports;
use App\Models\reportsection;
use Illuminate\Http\Request;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

class InternalinspectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
                //Check if user is authorized to view resource
                Auth::check();
                $user = Auth::user();
        

        $inspections = DB::table('internalinspections')
    ->join('farms', 'internalinspections.farmid', '=', 'farms.id')
    ->join('reports','internalinspections.reportid', '=', 'reports.id') // Join the 'insternalinspections' and 'farms' tables
    ->select('farmcode','farmname','inspectionstate', 'internalinspections.id as iid','farms.id','internalinspections.reportid as reportid','score','max_score',
    'internalinspections.inspectorid as inspectorid' , 'internalinspections.updated_at', 'reportname')
    ->where('internalinspections.inspectorid', $user->id)            // Select specific columns
    ->get();


        return view('inspection.inspection')->with('inspections',$inspections);


    }



    public function new(Request $request)
    {
                  //Check if user is authorized to view resource
                  Auth::check();
                  $user = Auth::user();
          

        $inspections= internalinspection::where('inspectorid', $user->id)->get();
        $farms=farm::where('inspectorid',$user->id)->get();
        $reports=reports::where('reportstate', 'ACTIVE')->get();
        //dd($farms);

        return view('inspection.inspection_new')
        ->with('farms',$farms)
        ->with('reports', $reports);


    }


    public function start(Request $request)
    {
                //Check if user is authorized to view resource
                Auth::check();
                $user = Auth::user();

        $newinspection= internalinspection::where('id', $request->internalinspectionid)->get();
        $farm=farm::where('id',$request->farmid)->first();
        $report=reports::where('reportstate', 'ACTIVE')->where('id', $request->reportid)->first();
        $reportsections=reportsection::where('reportid',$request->reportid)->where('sectionstate', 'ACTIVE')->get();
        $reportquestions=reportquestions::where('reportid',$request->reportid)->where('questionstate', 'ACTIVE')->orderBy('question_seq', 'asc')->get();
        


        #Create and save a new inspection record if a new inspection process start
        if ($request->internalinspectionid==null) {
            $newinspection= new internalinspection();
            $newinspection->farmid=$farm->id;
            $newinspection->latitude=$farm->latitude;
            $newinspection->longitude=$farm->longitude;
            $newinspection->inspectorid=$user->id;
            $newinspection->reportid=$request->reportid;
            $newinspection->inspectionstate='ACTIVE';
            $newinspection->save();
        } 
        $sectioncounter=$request->sectioncounter;

        if ($sectioncounter == null) {
            //dd($request);
        }

        




        return view('inspection.inspection_start')
        ->with('farm',$farm)
        ->with('report', $report)
        ->with('reportsections',$reportsections)
        ->with('reportquestions',$reportquestions)
        ->with('sectioncounter', $sectioncounter)
        ->with('inspection',$newinspection);

    }


    public function nextsection(Request $request)
    {
        //TO DO GET ID and Rank of User
        // allow users to begin a new inspection of any farm assigned to them
        Auth::check();
        $user = Auth::user();

   // dd($request);
//
        

        $inspection= internalinspection::where('id', $request->inspectionreportid)->first();

        $farm=farm::where('id',$request->farmid)->first();
        $report=reports::where('reportstate', 'ACTIVE')->where('id', $inspection->reportid)->first();
        $reportsections=reportsection::where('reportid',$inspection->reportid)->where('sectionstate', 'ACTIVE')->orderBy('section_seq', 'asc')->get();
        $reportquestions=reportquestions::where('reportid',$inspection->reportid)->where('questionstate', 'ACTIVE')->orderBy('question_seq', 'asc')->get();
        
        
        #Get the number of questions in the section
        $question=$request->question;
        $answers=$request->answers;
        $comments=$request->comments;



        #loop thru array 
        # a validation check to limit double posting
        $score=$inspection->score;
        $updatescore=0;
        for ($i=0; $i < count($answers); $i++) { 
            $checkanswer=inspectionanswers::where('internalinspectionid', $request->inspectionreportid)->where('questionid',$question[$i])->first();
            $checkcount=inspectionanswers::where('internalinspectionid', $request->inspectionreportid)->where('questionid',$question[$i])->count();
            if ($checkcount==0) {
                # This question has not been answered for this report before, save answer as new record
                $newanswer= new inspectionanswers();
                $newanswer->questionid=$question[$i];
                $newanswer->answer=$answers[$i];
                $newanswer->sectionidcomments=$comments[$i];
                $newanswer->reportid=$report->id;
                $newanswer->internalinspectionid=$request->inspectionreportid;
                $newanswer->sectionid=$request->sectionid[$i];
                $newanswer->save();

            } else {
                # this question has been answered previously double posting update record
                $updatescore=$updatescore+$checkanswer->answer;
                $checkanswer->questionid=$question[$i];
                $checkanswer->answer=$answers[$i];
                $checkanswer->sectionidcomments=$comments[$i];
                $checkanswer->reportid=$report->id;
                $checkanswer->sectionid=$request->sectionid[$i];
                $checkanswer->internalinspectionid=$request->inspectionreportid;
                $checkanswer->save();
            }
            
   
            $score=$score+$answers[$i];
        }
        #Update Inspection REport score
        $inspection->score=$score -$updatescore;
        $inspection->save();
               #Get Previous Answers
               $reportquestions= DB::table('reportquestions')
               ->leftJoin('inspectionanswers','reportquestions.id' , '=', 'inspectionanswers.questionid') // Join the 'reportquestions' and 'answers' tables
               ->select(
                  'reportquestions.id as id',
                  'reportquestions.reportid  as reportid',
                  'reportquestions.reportsectionid as reportsectionid',
                  'reportquestions.question_seq as question_seq',
                  'reportquestions.question as question',
                  'reportquestions.questiontype as questiontype',
                  'reportquestions.questionstate as questionstate',
                  'answer','sectionidcomments'
               )
               ->where('reportquestions.reportid',$inspection->reportid)->where('reportquestions.questionstate', 'ACTIVE')
              ->where('internalinspectionid',$inspection->id)->orderBy('question_seq', 'asc')
               ->get(); 
               
          
        $sectioncounter=$request->sectioncounter;

        #check if at the last section 
        if ($sectioncounter==$reportsections->count()) {
            
            #UPDATE internal inspection state to submitted
            $inspection->inspectionstate='SUBMITTED';
            $inspection->save();

            #Return  to Dashboard view 
        
        
                return redirect()->route('inspection');
        }

        #block of code to populate unanswered questions stack 
        $currentsection= $reportsections[$request->sectioncounter]->id;
        $test=inspectionanswers::where('sectionid',$currentsection)->where('internalinspectionid',$inspection->id) ->get();
        echo $currentsection;
        if ($test->count()>1) {
            # "More questions answered"; Do nothing

        } else {
            # " No More questions answered" repopulate all questions on report

            $reportquestions=reportquestions::where('reportid',$inspection->reportid)->where('questionstate', 'ACTIVE')->orderBy('question_seq', 'asc')->get();
        }
        

        return view('inspection.inspection_start')
        ->with('farm',$farm)
        ->with('report', $report)
        ->with('reportsections',$reportsections)
        ->with('reportquestions',$reportquestions)
        ->with('sectioncounter', $sectioncounter)
        ->with('inspection',$inspection);


    }



    public function continue(Request $request)
    {
                //Check if user is authorized to view resource
                Auth::check();
                $user = Auth::user();

                #first determine how many sections does the report have
                $sectioncount=reportsection::where('reportid', $request->id)->count();

                #GET previously completed inspection sheet
               $inspection=internalinspection::where('id', $request->inspectionid)->first();

               #Get Previous Answers
               $reportquestions= DB::table('reportquestions')
               ->leftJoin('inspectionanswers','reportquestions.id' , '=', 'inspectionanswers.questionid') // Join the 'reportquestions' and 'answers' tables
               ->select(
                  'reportquestions.id as id',
                  'reportquestions.reportid  as reportid',
                  'reportquestions.reportsectionid as reportsectionid',
                  'reportquestions.question_seq as question_seq',
                  'reportquestions.question as question',
                  'reportquestions.questiontype as questiontype',
                  'reportquestions.questionstate as questionstate',
                  'answer','sectionidcomments'
               )
               ->where('reportquestions.reportid',$inspection->reportid)->where('reportquestions.questionstate', 'ACTIVE')
               ->where('internalinspectionid',$inspection->id)->orderBy('question_seq', 'asc')
               ->get(); 



    $farm=farm::where('id',$request->farmid)->first();
    $report=reports::where('reportstate', 'ACTIVE')->where('id', $inspection->reportid)->first();
    $reportsections=reportsection::where('reportid',$inspection->reportid)->where('sectionstate', 'ACTIVE')->get();
    $sectioncounter=0;

###
###
        #block of code to populate unanswered questions stack 
        $reportsections=reportsection::where('reportid',$inspection->reportid)->where('sectionstate', 'ACTIVE')->orderBy('section_seq', 'asc')->get();
        $test=inspectionanswers::where('internalinspectionid',$inspection->id) ->get();

        if ($test->count()>1) {
            # "More questions answered"; Do nothing

        } else {
            # " No More questions answered" repopulate all questions on report

            $reportquestions=reportquestions::where('reportid',$inspection->reportid)->where('questionstate', 'ACTIVE')->orderBy('question_seq', 'asc')->get();
        }
        

        //return view('inspection.inspection_review')->with('reportquestion',$reportquestions);
        return view('inspection.inspection_start')
        ->with('farm',$farm)
        ->with('report', $report)
        ->with('reportsections',$reportsections)
        ->with('reportquestions',$reportquestions)
        ->with('sectioncounter', $sectioncounter)
        ->with('inspection',$inspection);



    }

    
    public function iapproval()
    {
                  //Check if user is authorized to view resource
                  Auth::check();
                  $user = Auth::user();

                  if (str_contains($user->roles,'ADMINISTRATOR')) {
                    # only viewable by administrators
                    $inspections= DB::table('internalinspections')
                    ->leftJoin('reports', 'internalinspections.reportid', '=','reports.id')
                    ->leftJoin('farms', 'internalinspections.farmid','=', 'farms.id')
                    ->select('reportname','max_score','score','internalinspections.id as iid', 'farmname','inspectionstate', 'internalinspections.created_at as cdate')
                    ->get();

                    return view('inspection.inspection_review')->with('reportquestions',$inspections);
                  }



                  return redirect()->route('unauthorized');


    }
    public function iapprove(Request $request)
    {
                  //Check if user is authorized to view resource
                  Auth::check();
                  $user = Auth::user();
                  $inspection=internalinspection::where('id',$request->iid)->first();

                  if (str_contains($user->roles,'ADMINISTRATOR')) {

                    # approve inspection
                    if ($request->has('approvebtn')) {
                        # code...
    
                        $inspection->inspectionstate='APPROVED';

                    }
                    if ($request->has('rejectbtn')) {
                        # code...
  
                        $inspection->inspectionstate='REJECTED';

                    }
                    $inspection->save();

                    return redirect()->route('iapproval');
                  }



                  return redirect()->route('unauthorized');


    }

    public function ireject(Request $request)
    {
                  //Check if user is authorized to view resource
                  Auth::check();
                  $user = Auth::user();

                  return redirect()->route('unauthorized');


    }



}
