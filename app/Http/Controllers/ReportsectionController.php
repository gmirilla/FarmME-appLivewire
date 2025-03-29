<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

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
                //Check if user is authorized to view resource
                Auth::check();
                $user = Auth::user();
        
                if ($user->roles!='ADMINISTRATOR') {
                    return view('unauthorized');
                }


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
                        //Check if user is authorized to view resource
                        Auth::check();
                        $user = Auth::user();
                
                        if ($user->roles!='ADMINISTRATOR') {
                            return view('unauthorized');
                        }

    $sections=reportsection::where('reportid',$report->reportid)->get();

        

        return response()->json($sections);
    }

    public function showsection(Request $request)
    {
        //
                   //Check if user is authorized to view resource
                   Auth::check();
                   $user = Auth::user();
           
                   if ($user->roles!='ADMINISTRATOR') {
                       return view('unauthorized');
                   }     

    $sections=reportsection::where('reportid',$request->reportid)->orderBy('section_seq', 'asc')->get();
    $report=reports::where('id',$request->reportid)->get();


        

        return view('report.reportsection')->with('sections', $sections)->with('reports', $report);
    }


}
