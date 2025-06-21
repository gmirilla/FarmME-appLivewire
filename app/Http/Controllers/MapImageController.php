<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;



class MapImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
        public function test()
    {
        //
        return view('mapping.maptest');
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
    $dataUrl = $request->input('image');

   // $dataUrl=$request->test;

    //dd($request);



    if (!$dataUrl || !str_starts_with($dataUrl, 'data:image/png;base64,')) {
        return response()->json(['error' => 'Invalid image format'], 422);
    }

    // Extract Base64 string and decode it
    $base64 = str_replace('data:image/png;base64,', '', $dataUrl);
    $binary = base64_decode($base64);

    // Generate a unique filename
    $filename = 'map_' . Str::uuid() . '.png';


    // Save the file to storage (no compression)
    Storage::disk('public')->put("maps/{$filename}", $binary);

    return response()->json([
        'message' => 'PNG image saved successfully',
        'filename' => $filename,
        'path' => Storage::url("maps/{$filename}"),

    ]);

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
