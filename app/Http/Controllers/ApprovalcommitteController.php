<?php

namespace App\Http\Controllers;

use App\Models\approvalcommitte;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class ApprovalcommitteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function apprcomm_show()
    {
        $apprcomms=approvalcommitte::all();
        return view('adminconfig.appr_com_show', compact('apprcomms'));
    }

        public function apprcomm_add(Request $request)
    {
        $year=date('Y');
        $request->validate([
            'name' => [
                'required',
                Rule::unique('approvalcommittes')
                    ->where('position', $request->position)
                    ->where('year', $year),
            ],
            'position' => 'required',
        ]);

        

        if (!empty($request->signaturepath)) {
            # code...
            $request->validate([
                'signaturepath' => 'required|image|max:4096',
            ]);
            $path = $request->file('signaturepath')->store('public/signatures', 'public');
        }
         $year=date('Y');
        $committemember= new approvalcommitte();
        $committemember->name=$request->name;
        $committemember->signaturepath=$path;
        $committemember->is_active=$request->is_active ? true : false;
        $committemember->position=$request->position;
        $committemember->year=$year;
        $committemember->save();
        $apprcomms=approvalcommitte::all();
        return view('adminconfig.appr_com_show', compact('apprcomms'));
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
    public function show(approvalcommitte $approvalcommitte)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(approvalcommitte $approvalcommitte)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, approvalcommitte $approvalcommitte)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(approvalcommitte $approvalcommitte)
    {
        //
    }
}
