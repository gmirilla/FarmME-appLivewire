<?php

namespace App\Http\Controllers;

use App\Models\farmunits;
use App\Models\farm;
use Illuminate\Http\Request;

class FarmunitsController extends Controller
{
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
    public function show(farmunits $farmunits)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        //

        $farm=farm::where('id',$request->farmid)->first();
        $farmunits=farmunits::where('farmid',$request->farmid)->get();
        //dd($request);

        return view("editfarmdetails", compact('farm','farmunits'));
    }

    public function listfunits(Request $request)
    {
        //
        $farm=farm::where('id',$request->fid)->first();
        $farmunits=farmunits::where('farmid',$request->fid)->get();
        //dd($request);

        return view("editfunitdetails", compact('farm','farmunits'));
    }


    public function editfunit(Request $request)
    {
        //
        try {
            //code...

            
            $farm=farm::where('id',$request->farmid)->first();
            $farmunit=farmunits::where('id', $request->fid)->first();

            switch ($request) {
                case $request->has('updatefu'):
                    # code...

                    return redirect()->route('newfunit',$request);
 
                    break;

                case $request->has('deletefu'):
                        # code...
                        $farmunit->delete();
                    break;
                    
                default:
                    # code...
        
                    break;
            }

        } catch (\Throwable $th) {
            //throw $th;
        }
        //dd($request);
        $totalfarmunitcount=farmunits::where('farmid', $request->farmid)->count();
        $totalfarmunitarea=farmunits::where('farmid', $request->farmid)->sum('fuarea');
        $farm->farmarea=$totalfarmunitarea;
        $farm->nooffarmunits=$totalfarmunitcount;

        $farm->save();
        
        $farmunits=farmunits::where('farmid',$request->farmid)->get();

        return view("editfunitdetails", compact('farm','farmunits'));
    }

    public function newfunit(Request $request)
    {
        //

        try {
            //code...
            $farm=farm::where('id',$request->farmid)->first();
            $farmunit=farmunits::find($request->fid);

        } catch (\Throwable $th) {
            //throw $th;
        }
        $farmunits=farmunits::where('farmid',$request->farmid)->get();
        //dd($request);

        return view("newfunit", compact('farm','farmunit'));
    }

    public function savefunit(Request $request)
    {
        //
        try {
            //code...
            $farm=farm::where('id',$request->fid)->first();


            if ( $request->has('farmunitid')) {
                # code...
                $farmunit= farmunits::where('id',$request->farmunitid)->firstOrCreate();
            }

            if ( !($request->has('farmunitid'))) {
                # code...
                $farmunit= new farmunits();
            }

            $farmunit->farmid=$request->fid;
            $farmunit->fuarea=$request->fuarea;
            $farmunit->fulatitude=$request->fulatitude;
            $farmunit->fulongitude=$request->fulongitude;
            $farmunit->save();

            //Update total Farm Area and unit count

            $totalfarmunitcount=$farmunit::where('farmid', $request->fid )->count();
            $totalfarmunitarea=$farmunit::where('farmid', $request->fid )->sum('fuarea');
            $farm->farmarea=$totalfarmunitarea;
            $farm->nooffarmunits=$totalfarmunitcount;

            $farm->save();

        } catch (\Throwable $th) {
            //throw $th;
        }
        
        $farmunits=farmunits::where('farmid',$request->fid)->get();
        
        return view("editfunitdetails", compact('farm','farmunits'));
    }






    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, farmunits $farmunits)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(farmunits $farmunits)
    {
        //
    }
}
