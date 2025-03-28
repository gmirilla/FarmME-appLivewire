<?php

namespace App\Http\Controllers;

use App\Models\farm;
use App\Models\internalinspection;
use Illuminate\Http\Request;
use App\Models\User;

class dashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        #Get number of users on System
        $usercount=User::where('roles','like', '%ACTIVE%' )->count();
        $farmcount=farm::where('farmstate','like', '%ACTIVE%' )->count();
        $inspectioncount=internalinspection::where('inspectionstate','like', '%SUBMITTED%' )->count();

        return view('dashboard')->with('usercount',$usercount)->with('farmcount',$farmcount)->with('inspectioncount',$inspectioncount);
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
