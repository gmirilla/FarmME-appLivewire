<?php

namespace App\Http\Controllers;
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

}
