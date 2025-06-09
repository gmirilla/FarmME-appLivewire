<?php

namespace App\Http\Controllers;

use App\Models\reportquestions;
use Illuminate\Support\Facades\Auth;

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
        //Check if user is authorized to view resource
        Auth::check();
        $user = Auth::user();

        if ($user->roles!='ADMINISTRATOR') {
            return view('unauthorized');
        }
        

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
                //Check if user is authorized to view resource
                Auth::check();
                $user = Auth::user();
        
                if ($user->roles!='ADMINISTRATOR') {
                    return view('unauthorized');
                }



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

    //Duplicate a  report and return to view
    public function reportcopy(Request $request)
    {
        //
                //Check if user is authorized to view resource
                Auth::check();
                $user = Auth::user();
        
                if ($user->roles!='ADMINISTRATOR') {
                    return view('unauthorized');
                }





        $validate=$request->validate(['newreportname'=> 'required|unique:reports,reportname']);
        $orgreport=reports::where('id',$request->orgreportid)->first();
        $newreport= new reports();
        $newreport->reportname=$request->newreportname;
        $newreport->max_score=$orgreport->max_score;
        $newreport->reportstate='ACTIVE';
        $newreport->save();

        // Copy all sections 

        $orgsection= reportsection::where('reportid',$orgreport->id)->get();
        
        foreach ($orgsection as $section) {
            # code...
            $newsection= new reportsection();
            $newsection->reportid=$newreport->id;
            $newsection->sectionname=$section->sectionname;
            $newsection->section_seq=$section->section_seq;
            $newsection->sectionstate=$section->sectionstate;

            $newsection->save();

            //Nested Loop to copy all questions in a section
            $orgquestions=reportquestions::where('reportsectionid',$section->id)->get();
            foreach ($orgquestions as $orgquestion) {
                # code...
                $newquestion= new reportquestions();
                $newquestion->reportid=$newreport->id;
                $newquestion->reportsectionid=$newsection->id;
                $newquestion->question_seq=$orgquestion->question_seq;
                $newquestion->question=$orgquestion->question;
                $newquestion->questiontype=$orgquestion->questiontype;
                $newquestion->questionstate=$orgquestion->questionstate;
                $newquestion->save();
            }

        }

        $allreports=reports::all();

        $sections=reportsection::all(); 
        $selectedreport=$newreport->id;

        return view('report.report')
        ->with('reports', $allreports)
        ->with('sections',$sections)
        ->with('selectedreport',$selectedreport);

        

    }


}
