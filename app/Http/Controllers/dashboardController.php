<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
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
                //Check if user is authorized to view resource
                Auth::check();
                $user = Auth::user();

        switch ($user->roles) {
            case 'ADMINISTRATOR':
                # code...
                $usercount=User::where('roles','like', '%ACTIVE%' )->count();
                $farmcount=farm::where('farmstate','like', '%ACTIVE%' )->count();
                $farmpendingcount=farm::where('farmstate','like', '%PENDING%' )->count();
                $inspectioncount=internalinspection::where('inspectionstate','like', '%SUBMITTED%' )->count();
                break;
            case 'INSPECTOR':
                # code...
                $usercount=1;
                $farmcount=farm::where('farmstate','like', '%ACTIVE%' )->where('inspectorid',$user->id)->count();
                $farmpendingcount=farm::where('farmstate','like', '%PENDING%' )->where('inspectorid',$user->id)->count();
                $inspectioncount=internalinspection::where('inspectionstate','like', '%SUBMITTED%' )->where('inspectorid',$user->id)->count(); 
                break;
                        
            default:
                # code...
                return redirect()->route('unauthorized');
                break;
        }     
    

        return view('dashboard')
        ->with('usercount',$usercount)
        ->with('farmcount',$farmcount)
        ->with('inspectioncount',$inspectioncount)
        ->with('farmpendingcount',$farmpendingcount);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


}
