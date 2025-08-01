<?php

namespace App\Http\Controllers;

use App\Models\agrochemicalrecords;
use Illuminate\Http\Request;

class AgrochemicalrecordsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
        public function disable(Request $request)
    {
        //

        $fcode='fcode='.$request->farmcode;

        $chem=agrochemicalrecords::where('id', $request->aid)->first();
        $chem->active=false;
        $chem->save();

                
        $fcode='fcode='.$request->farmcode;

        return redirect()->route('begin',$fcode);

    }

    public function add(Request $request)
    {
        //'farmid','entranceid','herbicidename','quantity','nameofperson','hectaresapplied','season'

      $newherbicide= new agrochemicalrecords();
      $newherbicide->farmid=$request->farmid;
      $newherbicide->quantity=$request->herbicideqty;
      $newherbicide->nameofperson=$request->herbicideapplier;
      $newherbicide->season=$request->season;
      $newherbicide->herbicidename=$request->herbicide;
      $newherbicide->hectaresapplied=$request->farmsize;
      $newherbicide->farmsize=$request->farmsize;
      if (empty($request->ppeused)) {
        # code...
        $ppeused=false;
      } else {
        # code...
        $ppeused=true;
      }
      
      $newherbicide->ppeused=$ppeused;
      $newherbicide->entranceid=$request->farmentrance; 

      $newherbicide->save();

        $fcode='fcode='.$request->farmcode;
        

        return redirect()->route('begin',$fcode);
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
    public function show(agrochemicalrecords $agrochemicalrecords)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(agrochemicalrecords $agrochemicalrecords)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, agrochemicalrecords $agrochemicalrecords)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(agrochemicalrecords $agrochemicalrecords)
    {
        //
    }
}
