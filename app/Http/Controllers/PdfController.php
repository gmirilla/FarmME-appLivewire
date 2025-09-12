<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\farm;
use App\Models\farmentrance;
use App\Models\inspectionanswers;
use App\Models\internalinspection;
use App\Models\reportquestions;
use App\Models\reports;
use App\Models\reportsection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function test()
    {
        $users = User::get();

        $data = [
            'title' => 'Welcome to ItSolutionStuff.com',
            'date' => date('m/d/Y'),
            'users' => $users
        ]; 

        $pdf = Pdf::loadView('myPDF', $data);

        return $pdf->download('itsolutionstuff.pdf');
    }


        public function generateContractPDF(Request $request)
    {
        $users = User::get();

        //dd($request);

        $farmer=farm::where('id',$request->farmid)->first();
        $farmentrance=farmentrance::where('farm_period',$request->cdseason)->where('farmid',$request->farmid)->first();



        $data =compact('farmer','users','farmentrance');

        $pdf = Pdf::loadView('pdf.annex6pdf', $data);

        return $pdf->download('annex6_'.$farmer->farmcode.'.pdf');
    }

        public function generatePDF(Request $request)
    {

        
        $users = User::get();
        Auth::check();
        $user = Auth::user();
        $inspection=internalinspection::where('id',$request->inspectionid)->first();



                                $reportquestions= DB::table('reportquestions')
                                ->leftJoin('inspectionanswers','reportquestions.id' , '=', 'inspectionanswers.questionid') 
                                ->leftJoin('reportsections', 'reportquestions.reportsectionid', '=', 'reportsections.id')// Join the 'reportquestions' and 'answers' tables
                                ->select(
                                   'reportquestions.id as id',
                                   'reportquestions.reportid  as reportid',
                                   'reportquestions.reportsectionid as reportsectionid',
                                   'reportquestions.question_seq as question_seq',
                                   'reportquestions.question as question','reportquestions.indicator as indicator',
                                   'reportquestions.questiontype as questiontype',
                                   'reportquestions.questionstate as questionstate',
                                   'answer','sectionidcomments', 'section_seq'
                                )
                                ->where('reportquestions.reportid',$inspection->reportid)->where('reportquestions.questionstate', 'ACTIVE')
                                ->where('internalinspectionid',$inspection->id)->orderBy('section_seq', 'asc')->orderBy('question_seq', 'asc')
                                ->get(); 
          
                                $reportname=reports::where('id', $inspection->reportid)->first();
                                $farm=farm::where('id',$inspection->farmid)->first();

                                if (strpos($reportname,'Entrance')) {
                                    # code...

                                    $farmentrance=farmentrance::where('internalinspectionid',$request->inspectionid)->first();


                                     $data=compact('reportname','reportquestions', 'user', 'inspection','farm','farmentrance');

                                    $pdf=Pdf::loadView('pdf.entrancepdf', $data);

                                } else {
                                    # code...
                                     $data=compact('reportname','reportquestions', 'user', 'inspection','farm');

                              $pdf=Pdf::loadView('pdf.inspectionpdf', $data);
                                }
                                
        
    
                             
                              $unsanitizedpdfname=$reportname->reportname.'_'.$farm->farmname.'.pdf';
                               $pdfname=preg_replace("/\//", "_", $unsanitizedpdfname);

/**
 * 
 * $data = [
            'title' => 'Welcome to ItSolutionStuff.com',
            'date' => date('m/d/Y'),
            'users' => $users
        ]; 

        $pdf = Pdf::loadView('myPDF', $data);
 * 
 * 
 */
        

        return $pdf->download($pdfname);
    }





    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
