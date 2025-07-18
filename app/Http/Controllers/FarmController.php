<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\farm;
use App\Models\reports;
use App\Models\internalinspection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Imports\farmImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\farmentrance;

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
                        $farmlist=farm::all()->sortBy('farmcode');
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


    public function onboarding()
    {
        //
                        //Check if user is authorized to view resource
                        Auth::check();
                        $user = Auth::user();

                        $reports=reports::where('reportstate', 'ACTIVE')->get();


        
                switch ($user->roles) {
                    case 'ADMINISTRATOR':
                        # code...
                        $farmlist=farm::where('farmstate','!=',  'APPROVED')->get()->sortByDesc('created_at');
                        break;
                    case 'INSPECTOR':
                        # code...
                        $farmlist=farm::where('inspectorid',$user->id)->where('farmstate','!=',  'APPROVED')->get(); 
                        break;
                                
                    default:
                        # code...
                        return redirect()->route('unauthorized');
                        break;
                }  

                        $year0=date('Y');
        $year1=$year0+1;
        $currentseason=$year0."/".$year1;

        return view('farmonboarding')->with('farmlist', $farmlist)->with('user',$user)->with('reports', $reports)->with('currentseason',$currentseason);
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
            'community'=>'required',
            'farmcode'=>'unique:farms,farmcode',
            'fname'=>'required|string',
            'phone'=>'required',
            'idno'=>'required',
            'city'=>'required',
            'state'=>'required',

        ]);
        
        $farmowner=$request->fname." ".$request->surname;
        $newfarm->farmname=$farmowner;
        $newfarm->community=$request->community;
        $newfarm->farmcode=$request->farmcode;
        $newfarm->yearofcertification=$request->yearofcert;
        $newfarm->fname=$request->fname;
        $newfarm->surname=$request->surname;
        $newfarm->phonenumber=$request->phone;
        $newfarm->gender=$request->gender;
        $newfarm->nationalidnumber=$request->idno;
        $newfarm->crop=$request->crop;
        $newfarm->cropvariety=$request->cropvariety;
        $newfarm->region=$request->region;
        $newfarm->state=$request->state;
        $newfarm->noofpermworkers=$request->nopworkers;
        $newfarm->nooftempworkers=$request->notworkers;


        $newfarm->farmstate='PENDING';
    
        $newfarm->save();

        $farmlist=farm::all();

        return redirect()->route('index');


    }
    public function updatefarm(Request $request)
    {

        $newfarm= farm::where('id',$request->fid)->first();
        //Validate data 

        $validate=$request->validate([
            'community'=>'required',
            'fname'=>'required|string',
            'phone'=>'required',
            'idno'=>'required',

        ]);

        

        $farmowner=$request->fname." ".$request->surname;
        $newfarm->farmname=$farmowner;
        $newfarm->community=$request->community;
        $newfarm->yearofcertification=$request->yearofcert;
        $newfarm->fname=$request->fname;
        $newfarm->surname=$request->surname;
        $newfarm->phonenumber=$request->phone;
        $newfarm->gender=$request->gender;
        $newfarm->nationalidnumber=$request->idno;
        $newfarm->crop=$request->crop;
        $newfarm->cropvariety=$request->cropvariety;
        $newfarm->region=$request->region;
        $newfarm->state=$request->state;
        $newfarm->noofpermworkers=$request->nopworkers;
        $newfarm->nooftempworkers=$request->notworkers;
        $newfarm->latitude=$request->latitude;
        $newfarm->longitude=$request->longitude;


    
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
        Auth::check();
        $user = Auth::user();
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
        Auth::check();
        $authuser = Auth::user();
        
        //Display Details of Farm
        $farms=farm::all();
        $id=farm::where('farmcode', $request->id)->first()->id;
        $EntranceReports = reports::whereRaw('LOWER(reportname) LIKE ?', ['%entrance%'])->get();
        $reportIds = $EntranceReports->pluck('id')->toArray();



        $lastreport=internalinspection::where('farmid', $id)
        ->whereNotIn('reportid', $reportIds)
        ->latest('updated_at')->first();

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
            'crop',
            'cropvariety',
            'nooffarmunits',
            'yearofcertification',
            'name', 'users.roles as uroles', 'users.id as uid'
        )->where('farmcode', $request->id)->first(); 
        $farmreports=DB::table('internalinspections')
        ->leftJoin('reports', 'internalinspections.reportid', '=', 'reports.id')
        ->select('internalinspections.id as iid','reportname','score','internalinspections.created_at as created_at','inspectionstate','max_score','comments','season' )
        ->where('farmid',$id)->get();

        #Get List of all Users on System
        $users=User::all();
        $farmerpicture=farm::where('farmcode',$request->id)->first()->getfarmerpicture();
        $seasons=internalinspection::where('farmid',$id)->pluck('season')->unique();
        $farmdetails=farm::where('farmcode',$request->id)->first();



        return view('viewfarm', compact('farm','farmreports', 'users', 'lastreport','authuser','farmerpicture','seasons','farmdetails'));
    }

    public function importfarms(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,csv'
    ]);

    Excel::import(new farmImport, $request->file('file'));

    return redirect()->route('index');
}
    public function import_list(Request $request)
{


    return view('farmimport');
}

    public function annualreport(Request $request)
{
    $farms=farm::where('farmstate', 'ACTIVE')->get();


    return view('excel.annualreport',compact('farms'));
}

public function viewcontract(Request $request)
{
        $users = User::get();

        //dd($request);

        $farmer=farm::where('id',$request->farmid)->first();
        $farmentrance=farmentrance::where('farm_period',$request->cdseason)->where('farmid',$request->farmid)->first();
        



        $data =compact('farmer','users','farmentrance');

    return view('farmercontract', $data);


}
}
