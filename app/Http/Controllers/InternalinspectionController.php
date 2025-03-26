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

class InternalinspectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //TO DO GET ID and Rank of User
        Auth::check();
        $user = Auth::user();

        $inspections = DB::table('internalinspections')
    ->join('farms', 'internalinspections.farmid', '=', 'farms.id')
    ->join('reports','internalinspections.reportid', '=', 'reports.id') // Join the 'insternalinspections' and 'farms' tables
    ->select('farmcode','farmname','inspectionstate', 'internalinspections.id as iid','farms.id','internalinspections.reportid as reportid','score','max_score',
    'internalinspections.inspectorid as inspectorid' , 'internalinspections.updated_at')
    ->where('internalinspections.inspectorid', $user->id)            // Select specific columns
    ->get();


        return view('inspection.inspection')->with('inspections',$inspections);


    }



    public function new(Request $request)
    {
        //TO DO GET ID and Rank of User
        // allow users to begin a new inspection of any farm assigned to them
        Auth::check();
        $user = Auth::user();



        $inspections= internalinspection::where('internalinspectorid', $user->id)->get();
        $farms=farm::where('inspectorid',$user->id)->get();
        $reports=reports::where('reportstate', 'ACTIVE')->get();
        //dd($farms);

        return view('inspection.inspection_new')
        ->with('farms',$farms)
        ->with('reports', $reports);


    }


    public function start(Request $request)
    {
        //TO DO GET ID and Rank of User
        // allow users to begin a new inspection of any farm assigned to them
        Auth::check();
        $user = Auth::user();




        $newinspection= internalinspection::where('internalinspection', $request->internalinspectionid)->get();
        $farm=farm::where('id',$request->farmid)->first();
        $report=reports::where('reportstate', 'ACTIVE')->where('id', $request->reportid)->first();
        $reportsections=reportsection::where('reportid',$request->reportid)->where('sectionstate', 'ACTIVE')->get();
        $reportquestions=reportquestions::where('reportid',$request->reportid)->where('questionstate', 'ACTIVE')->get();
        


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
        /** 
        else {
         #retrieve previously filled inspection sheet and answers
         $newinspection= internalinspection::where('id',$request->internalinspectionid)->first();
        // dd($newinspection);
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
         ->where('reportquestions.reportid',$request->reportid)->where('reportquestions.questionstate', 'ACTIVE')->where('internalinspectionid',$request->inspectionreportid)
         ->get();        // Select specific columns
        } 
     */



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
        $reportsections=reportsection::where('reportid',$inspection->reportid)->where('sectionstate', 'ACTIVE')->get();
        $reportquestions=reportquestions::where('reportid',$inspection->reportid)->where('questionstate', 'ACTIVE')->get();
        #Get the number of questions in the section
        $question=$request->question;
        $answers=$request->answers;
        $comments=$request->comments;



        #loop thru array 
        #TO DO create a validation check to limit double posting
        $score=$inspection->score;
        for ($i=0; $i < count($answers); $i++) { 
            $newanswer= new inspectionanswers();
            $newanswer->questionid=$question[$i];
            $newanswer->answer=$answers[$i];
            $newanswer->sectionidcomments=$comments[$i];
            $newanswer->reportid=$report->id;
            $newanswer->internalinspectionid=$request->inspectionreportid;
            $newanswer->save();
            $score=$score+$answers[$i];
        }
        #Update Inspection REport score
        $inspection->score=$score;
        $inspection->save();

/*
        # TODO previous answers
         #retrieve previously filled inspection sheet and answers
         $newinspection= internalinspection::where('id',$request->internalinspectionid)->first();
        // dd($newinspection);
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
         ->where('reportquestions.reportid',$request->reportid)->where('reportquestions.questionstate', 'ACTIVE')->where('internalinspectionid',$request->inspectionreportid)
         ->get();   

*/



        $sectioncounter=$request->sectioncounter;

        #check if at the last section 
        if ($sectioncounter==$reportsections->count()) {
            
            #UPDATE internal inspection state to submitted
            $inspection->inspectionstate='SUBMITTED';
            $inspection->save();

            #Return  to Inspections view 
            $inspections = DB::table('internalinspections')
            ->join('farms', 'internalinspections.farmid', '=', 'farms.id') // Join the 'insternalinspections' and 'farms' tables
            ->select('farmcode','farmname','inspectionstate', 'internalinspections.id','farms.id','score',
            'internalinspections.inspectorid', 'internalinspections.updated_at')
            ->where('internalinspections.inspectorid', $user->id)            // Select specific columns
            ->get();
        
        
                return view('dashboard');
        }
        

        return view('inspection.inspection_start')
        ->with('farm',$farm)
        ->with('report', $report)
        ->with('reportsections',$reportsections)
        ->with('reportquestions',$reportquestions)
        ->with('sectioncounter', $sectioncounter)
        ->with('inspection',$inspection);


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
    public function show(internalinspection $internalinspection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(internalinspection $internalinspection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, internalinspection $internalinspection)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(internalinspection $internalinspection)
    {
        //
    }
}
