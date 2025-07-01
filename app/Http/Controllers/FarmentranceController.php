<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\agrochemicalrecords;
use App\Models\farmentrance;
use App\Models\farm;
use App\Models\farmunits;
use App\Models\internalinspection;
use App\Models\misccodes;
use App\Models\othercropsrecords;
use App\Models\reports;
use Illuminate\Http\Request;
use Carbon\Carbon;


class FarmentranceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        
    }

        public function feprofile_update(Request $request)
    {
        //'farm_period','farmid','internalinspectionid','inspectorid', 


        Auth::check();
        $user=Auth::user();
        $farmerdetail=farm::where('farmcode', $request->fcode)->first();
        $farmentrance=farmentrance::where('id',$request->farmentranceid)->first();
       // $dofl=Carbon::parse($request->dateoflastinspection);

        
        //update farmer details
        $farmerdetail->cropvariety=$request->varietyofcrop;
        //$farmerdetail->lastinspection=$dofl->format('d-m-Y');
        $farmerdetail->address=$request->address;
        $farmerdetail->householdsize=$request->householdsize;
        $farmerdetail->yob=$request->yearofbirth;
//signature_path do not override if empty
       if (!empty($request->signature)) {
        # code...
    $request->validate([
    'signature' => 'required|image|max:2048',
]);
$path=$request->file('signature')->store('public/signatures','public');
        $farmerdetail->signaturepath=$path;
       }

        $farmerdetail->save();

        //update Farm Entrance
        // 'surname','fname','farmcode','nationalidno',
       // 'yob','phoneno', 'householdsize','address', 'lastinspection','inspectionresult', 'crop','variety','regdate'
       






        $farmentrance->surname=$farmerdetail->surname;
        $farmentrance->fname=$farmerdetail->fname;
        $farmentrance->farmcode=$farmerdetail->farmcode;
        $farmentrance->nationalidno=$farmerdetail->nationalidnumber;
        $farmentrance->yob=$farmerdetail->yob;
        $farmentrance->phoneno=$farmerdetail->phonenumber;
        $farmentrance->householdsize=$farmerdetail->householdsize;
        $farmentrance->address=$farmerdetail->address;
        $farmentrance->lastinspection=$farmerdetail->lastinspection;
        $farmentrance->inpsectionresult=$request->outcomeoflastinspection;
        $farmentrance->crop=$farmerdetail->crop;
        $farmentrance->cropvariety=$farmerdetail->cropvariety;
        $farmentrance->regdate=$request->regdate;

//farmerpicture_path do not override if empty
       if (!empty($request->farmerpicture)) {
        # code...
    $request->validate([
    'farmerpicture' => 'required|image|max:2048',
]);
$fpath=$request->file('farmerpicture')->store('public/farmerpictures','public');
        $farmentrance->farmerpicture=$fpath;
       }

        $farmentrance->save();






                $fcode='fcode='.$farmerdetail->farmcode;

        return redirect()->route('begin',$fcode);      

        
    }
    
    public function feprofile(Request $request)
    {
        //
        Auth::check();
        $user=Auth::user();
        $farmerdetail=farm::where('farmcode', $request->fcode)->first();
        $year0=date('Y');
        $year1=$year0+1;
        $currentseason=$year0."/".$year1;
        $seasonrange=[];

        for ($i=0; $i < 10; $i++) { 
            # code...
            $year=$year0-$i;
            $prevyear=$year-1;
            $season=$prevyear."/".$year;
            $seasonrange[$i]=$season;

        }

        $lastreport=internalinspection::where('farmid', $farmerdetail->id)->latest('updated_at')->first();

         //FInd the active report with Entrance in name 
        $report=reports::where('reportstate', 'ACTIVE')->where('reportname','like', '%Entrance%')->first();
        $farmentrance=farmentrance::where('farmid', $farmerdetail->id)->where('farm_period',$currentseason)->first();
        
       //dd($farmentrance);

        if (empty($farmentrance)) {
            # code...

            $farmentrance= new farmentrance();
            $farmentrance->farm_period=$currentseason;
            $farmentrance->farmid=$farmerdetail->id;
            $farmentrance->inspectorid=$user->id;
            $farmentrance->fieldofficer=$user->id;
            

            $farmentrance->save();   
        }

       


        return view('farmentance.farmentrance',compact('farmerdetail','currentseason','lastreport', 'report','farmentrance', 'seasonrange'));
    }

        public function disablefu(Request $request)
    {
        //
       
        $farmplot=farmunits::where('id',$request->fuid)->first();
        $farmplot->active=false;
        $farmplot->save();

        $fcode='fcode='.$request->farmcode;

        return redirect()->route('begin',$fcode);       
    }

       public function cropdelivered(Request $request)
    {
        //

        $misccode=misccodes::where('farmid', $request->farmid)->where('season', $request->prevseason)->where('parameter','cropdel')->first();

        if (empty($misccode)) {
            # code...
            $misccode= new misccodes();
        }

        $misccode->parameter='cropdel';
        $misccode->season=$request->prevseason;
        $misccode->value=$request->cropdelivered;
        $misccode->farmid=$request->farmid;

        $misccode->save();

        $fcode='fcode='.$request->farmcode;

        return redirect()->route('begin',$fcode);
    }

       public function cropproduced(Request $request)
    {
        //
         $misccode=misccodes::where('farmid', $request->farmid)->where('season', $request->prevseason)->where('parameter','cropprod')->first();

        if (empty($misccode)) {
            # code...
            $misccode= new misccodes();
        }

        $misccode->parameter='cropprod';
        $misccode->season=$request->prevseason;
        $misccode->value=$request->cropproduced;
        $misccode->farmid=$request->farmid;

        $misccode->save();


        $fcode='fcode='.$request->farmcode;

        return redirect()->route('begin',$fcode);
    }

            public function disablevolsold(Request $request)
    {
        //
       
        $misccode=misccodes::where('id',$request->vsid)->first();
        $misccode->active=false;
        $misccode->save();

        $fcode='fcode='.$request->farmcode;

        return redirect()->route('begin',$fcode);

        
    }
    
    public function addvolsold(Request $request)
    {
        //
       
        $misccode= new misccodes();
        $misccode->parameter='vol';
        $misccode->season=$request->volseason;
        $misccode->value=$request->volsold;
        $misccode->farmid=$request->farmid;
        $misccode->save();
        $fcode='fcode='.$request->farmcode;

        return redirect()->route('begin',$fcode);

        
    }


        public function getfeq(Request $request)
    {
        //FInd the active report with Entrance in name 
        //$report=reports::where('reportstate', 'ACTIVE')->where('reportname','like', '%Entrance%')->first();
        //$farmid=$request->farmid;
        
        //TO DO Handle resumtion

        
    }

        public function begin(Request $request)
    {
        //

        Auth::check();
        $user=Auth::user();

        $farmerdetail=farm::where('farmcode', $request->fcode)->first();
        $year0=date('Y');
        $year1=$year0+1;
        $currentseason=$year0."/".$year1;
        $seasonrange=[];

        for ($i=0; $i < 10; $i++) { 
            # code...
            $year=$year0-$i;
            $prevyear=$year-1;
            $season=$prevyear."/".$year;
            $seasonrange[$i]=$season;

        }

        $lastreport=internalinspection::where('farmid', $farmerdetail->id)->latest('updated_at')->first();
        $farmplots=farmunits::where('farmid', $farmerdetail->id)->where('active',true)->get();
        $otherplots=othercropsrecords::where('farmid',$farmerdetail->id)->where('active',true)->get();
        $agrochems=agrochemicalrecords::where('farmid',$farmerdetail->id)->where('active',true)->get();

         //FInd the active report with Entrance in name 
        $report=reports::where('reportstate', 'ACTIVE')->where('reportname','like', '%Entrance%')->first();
        $farmentrance=farmentrance::where('farmid', $farmerdetail->id)->where('farm_period',$currentseason)->first();
        
       //dd($farmentrance);

        if (empty($farmentrance)) {
            # code...

            $farmentrance= new farmentrance();
            $farmentrance->farm_period=$currentseason;
            $farmentrance->farmid=$farmerdetail->id;
            $farmentrance->inspectorid=$user->id;
            

            $farmentrance->save();   
        }

       




        return view('farmentance.beginfarmentrance', 
        compact('farmerdetail','currentseason','lastreport','farmplots','otherplots','agrochems', 'report','farmentrance', 'seasonrange'));
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
    public function show(farmentrance $farmentrance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(farmentrance $farmentrance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, farmentrance $farmentrance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(farmentrance $farmentrance)
    {
        //
    }
}
