<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\farm;
use App\Models\internalinspection;
use Illuminate\Http\Request;

class FarmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $farmlist=farm::latest()->paginate(25);

        return view('farm')->with('farmlist', $farmlist);
    }

    /**
     * Show the form for creating a new farm.
     */
    public function create()
    {
        //

        return view('newfarm');

    }

    /**
     * Store a newly created resource in farm.
     */
    public function store(Request $request)
    {

    
        $newfarm= new farm();
        //Validate data 
        
        $validate=$request->validate([
            'farmowner'=>'required',
            'community'=>'required',
            'farmcode'=>'unique:farms,farmcode'
        ]);
        $newfarm->farmname=$request->farmowner;
        $newfarm->community=$request->community;
        $newfarm->farmcode=$request->farmcode;
        
        
        $newfarm->save();

        $farmlist=farm::latest()->paginate(25);

        return view('farm')->with('farmlist', $farmlist);


    }
    /**
     * Schedule a new inspection date
     */
    public function newinspectiondate(Request $request)
    {
        //

        $farms=farm::all();
        $id=farm::where('farmcode', $request->farmcode)->first()->id;
        $farm=$farms->find($id);
        $farm->nextinspection=$request->newinspectiondate;
       // dd($farm);
        $farm->save();

        $farmlist=farm::latest()->paginate(25);

        return view('farm')->with('farmlist', $farmlist);
    }
        /**
     * Schedule a new inspection date
     */
    public function displayfarm(Request $request)
    {
        //
       // dd($request);
        
        //Display Details of Farm
        $farms=farm::all();
        $id=farm::where('farmcode', $request->id)->first()->id;
        $farm=$farms->find($id);
        $farmreports=DB::table('internalinspections')
        ->leftJoin('reports', 'internalinspections.reportid', '=', 'reports.id')
        ->select('reportname','score','internalinspections.created_at as created_at','inspectionstate','max_score' )
        ->where('farmid',$id)->get();



        return view('viewfarm')->with('farm', $farm)->with('farmreports', $farmreports);
    }


    /**
     * Display the specified resource.
     */
    public function show(farm $farm)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(farm $farm)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, farm $farm)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(farm $farm)
    {
        //
    }
}
