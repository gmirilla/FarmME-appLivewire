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

class FarmentranceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
