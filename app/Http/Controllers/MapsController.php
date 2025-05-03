<?php

namespace App\Http\Controllers;

use App\Models\maps;
use Illuminate\Http\Request;

class MapsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view("mapping.maptest");
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
    public function show(maps $maps)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(maps $maps)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, maps $maps)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(maps $maps)
    {
        //
    }
}
