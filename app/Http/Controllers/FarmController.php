<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\farm;
use App\Models\reports;
use App\Models\internalinspection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FarmController extends Controller
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

                        $reports=reports::where('reportstate', 'ACTIVE')->get();

        
                switch ($user->roles) {
                    case 'ADMINISTRATOR':
                        # code...
                        $farmlist=farm::all()->sortByDesc('created_at');
                        break;
                    case 'INSPECTOR':
                        # code...
                        $farmlist=farm::where('inspectorid',$user->id)->get(); 
                        break;
                                
                    default:
                        # code...
                        return redirect()->route('unauthorized');
                        break;
                }  
  

        

        return view('farm')->with('farmlist', $farmlist)->with('user',$user)->with('reports', $reports);
    }

        /**
     * Assign Staff to farm  and update farm recordsand retun to Farm page
     */
    public function assignstaff(Request $request)
    {
        //
        $farm=farm::where('farmcode', $request->id)->first();

        if ($request->has('assignstaff')) {
            # Assign staff button clicked. Update new staff


            $farm->inspectorid=$request->staffid;
            $farm->save();

        }
        if ($request->has('farmstatus')) {
            # Farm Status. Update new Farm status

            $farm->farmstate=$request->farmid;
            $farm->save();

        }


    return $this->displayfarm($request);



    }

    /**
     * Show the form for creating a new farm.
     */
    public function create()
    {
        //
        Auth::check();
        $user = Auth::user();

switch ($user->roles) {
    case 'ADMINISTRATOR':
        # code...
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
        $newfarm->farmstate='PENDING';
        
        
        $newfarm->save();

        $farmlist=farm::all();

        return redirect()->route('index');


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

        #Create a Pending inspection request
        $newinspection= new internalinspection();
        $newinspection->farmid=$id;
        $newinspection->inspectorid=$farm->inspectorid;
        $newinspection->reportid=$request->inspectiontype;
        $newinspection->inspectionstate="PENDING";
        $newinspection->save();

       return redirect()->route('index');
    }

        /**
     * Show farm details
     */
    public function displayfarm(Request $request)
    {
        //dd($request);
        
        //Display Details of Farm
        $farms=farm::all();
        $id=farm::where('farmcode', $request->id)->first()->id;
        $report=internalinspection::where('farmid', $id)->latest('updated_at')->first();

 
        

        $farm=DB::table('farms')
        ->leftJoin('users', 'farms.inspectorid', '=','users.id')
        ->select(
            'farms.id as id',
            'farmname',
            'community',
            'farmcode',
            'farmstate',
            'lastinspection',
            'nextinspection',
            'latitude',
            'longitude',
            'farmarea',
            'inspectorid',
            'measurement',
            'name', 'users.roles as uroles', 'users.id as uid'
        )->where('farmcode', $request->id)->first(); 
        $farmreports=DB::table('internalinspections')
        ->leftJoin('reports', 'internalinspections.reportid', '=', 'reports.id')
        ->select('internalinspections.id as iid','reportname','score','internalinspections.created_at as created_at','inspectionstate','max_score' )
        ->where('farmid',$id)->get();

        #Get List of all Users on System
        $users=User::all();



        return view('viewfarm')->with('farm', $farm)
        ->with('farmreports', $farmreports)->with('users',$users)->with('lastreport', $report);
    }


}
