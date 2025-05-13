<?php

namespace App\Http\Controllers;

use App\Models\farm;
use App\Models\farmunits;
use App\Models\farmunityield;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FarmunityieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

        /**
     * Display a listing of all plot yields for a farm.
     */
    public function list(Request $request)
    {
        //
        $farmyields=farmunityield::where('farmid',$request->farmid)->get();
        $farmunits=farmunits::where('farmid',$request->farmid)->get();
        $farm=farm::where('id',$request->farmid)->first();

        return view('yields.listyields',compact('farmyields','farmunits', 'farm'));
    }

            /**
     * Add new Yield listing .
     */
    public function addyield(Request $request)
    {
        //
        //get user details
        Auth::check();
        $user = Auth::user();

        #TO DO add Validation check to ensure that every unit can only have a single record for each year 
        
        //generate new farmyield record , assign values and save 
        $farmyield= new farmunityield();
        $farmyield->year=$request->year;
        $farmyield->farmid=$request->farmid;
        $farmyield->farmunitid=$request->farmunits;
        $farmyield->estyield=$request->estyield;
        $farmyield->actualyield=$request->actualyield;
        $farmyield->created_by=$user->id;
        $farmyield->save();

        
        $farmyields=farmunityield::where('farmid',$request->farmid)->get();
        $farmunits=farmunits::where('farmid',$request->farmid)->get();
        $farm=farm::where('id',$request->farmid)->first();

        return view('yields.listyields',compact('farmyields','farmunits', 'farm'));
    }

     public function updyield(Request $request)
    {
        //
        //get user details
        Auth::check();
        $user = Auth::user();
        
        //get farmyield record 
        $farmyield=farmunityield::where('id',$request->farmyieldid)->first();

        

        switch ($request) {
            case $request->has('updateyieldbtn'):
                # update Yield record with new values
                $farmyield->actualyield=$request->actualyield;
                $farmyield->updated_by=$user->id;
                $farmyield->save();
                break;
            case $request->has('deleteyieldbtn'):
                # delete Yield Record...
                $farmyield->delete();
                break;
            
            default:
                # code...
                break;
        }

        
        $farmyields=farmunityield::where('farmid',$request->farmid)->get();
        $farmunits=farmunits::where('farmid',$request->farmid)->get();
        $farm=farm::where('id',$request->farmid)->first();

        return view('yields.listyields',compact('farmyields','farmunits', 'farm'));
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
    public function show(farmunityield $farmunityield)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(farmunityield $farmunityield)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, farmunityield $farmunityield)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(farmunityield $farmunityield)
    {
        //
    }
}
