<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\farm;
use App\Models\farmunityield;
use App\Models\farmunits;
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
                $year = date("Y");
                $next=$year+1;
                $currentseason=$year."/".$next;

        switch ($user->roles) {
            case 'ADMINISTRATOR':
                # code...
                $usercount=User::whereNotIn('roles',['NONE','DISABLED'] )->count();
                $farmcount=farm::where('farmstate','like', '%ACTIVE%' )->count();
                $activefarms=farm::where('farmstate','like', '%ACTIVE%')->get();
                $farmarea=0;
                foreach ($activefarms as $activefarm) {
                    # code...
                    $farmarea+=$activefarm->getfarmareacurrent();
                }
                $farmpendingcount=farm::where('farmstate','like', '%PENDING%' )->count();
                $inspectioncount=internalinspection::where('inspectionstate','like', '%SUBMITTED%' )->count();
                $inspectionapprovedcount=internalinspection::where('inspectionstate','like', '%APPROVED%' )->count();
                $inspectionrejectedcount=internalinspection::where('inspectionstate','like', '%REJECTED%' )->count();
                $estyield=farmunits::where('season', $currentseason)->where('active', true)->sum('estimatedyield');
                $actualyield=farmunityield::where('year', $year)->sum('actualyield');


                break;
            case 'INSPECTOR':
                # code...
                $usercount=1;
                $farmcount=farm::where('farmstate','like', '%ACTIVE%' )->where('inspectorid',$user->id)->count();
                $farmpendingcount=farm::where('farmstate','like', '%PENDING%' )->where('inspectorid',$user->id)->count();
                 $farmarea=farm::where('farmstate','like', '%ACTIVE%' )->where('inspectorid',$user->id)->sum('farmarea');
                $inspectioncount=internalinspection::where('inspectionstate','like', '%SUBMITTED%' )->where('inspectorid',$user->id)->count(); 
                $inspectionapprovedcount=internalinspection::where('inspectionstate','like', '%APPROVED%' )->where('inspectorid',$user->id)->count();
                $inspectionrejectedcount=internalinspection::where('inspectionstate','like', '%REJECTED%' )->where('inspectorid',$user->id)->count();
                $estyield="N/A";
                $actualyield="N/A";
                break;
                        
            default:
                # code...
                return redirect()->route('unauthorized');
                break;
        }     
    


        return view('dashboard', 
        compact('usercount','farmcount','inspectioncount','farmpendingcount', 'inspectionapprovedcount',  'inspectionrejectedcount','farmarea','user','estyield','actualyield'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


}
