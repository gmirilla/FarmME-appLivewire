<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Ramsey\Uuid\v1;

class userController extends Controller
{
    /**
     * Display a listing of all users on the system .
     */
    public function index()
    {
        //
           //Check if user is authorized to view resource
           Auth::check();
           $user = Auth::user();

   switch ($user->roles) {
       case 'ADMINISTRATOR':
           # code...
           $users=User::all();
    
           break;
       case 'INSPECTOR':
           # code...
           return redirect()->route('unauthorized');
           break;
                   
       default:
           # code...
           return redirect()->route('unauthorized');
           break;
   }     

        
        $users=User::all();
    

        return view('user.user_admin')->with('users',$users );

    }


        /**
     * Update user roles .
     */
    public function user_update(Request $request)
    {
        //
        $user=User::where('id', $request->userid)->first();
        $user->roles=$request->userrole;
        $user->save();      
        $users=User::all();
    

        return view('user.user_admin')->with('users',$users );

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
