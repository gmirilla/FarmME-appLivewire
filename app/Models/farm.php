<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class farm extends Model
{
    //Hold the Basic details of the Farm
    protected $fillable = [
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
        'nooffarmunits',
        'yearofcertification',
        'fname',
        'surname',
        'phonenumber',
        'nationalidnumber',
        'gender',
        'noofpermworkers',
        'nooftempworkers',
        'village',
        'state',
        'region',
        'crop',
        'cropvariety',
        'yob', 'signaturepath',''


    ];

    public function getinspectorName()
    {
        $inspector = User::where('id', $this->inspectorid)->first();
        return $inspector ? $inspector->name : 'Inspector Not Assigned';

    }

        public function getfarmplots()
    {
        $farmplots = farmunits::where('farmid', $this->id)->get();
        return $farmplots ? $farmplots->count() : 'No Farm Units';

    }
     public function getfarmerpicture()
    {
        $farmentrance = farmentrance::where('farmid', $this->id)
                                  ->whereNotNull('farmerpicture')
                                  ->latest()
                                  ->first();

        return $farmentrance;

    }

         public function getfarmareacurrent()
    {
                $year = date("Y");
                $next=$year+1;
                $currentseason=$year."/".$next;
                $farmareacurrent=farmunits::where('season', $currentseason)->where('farmid', $this->id)->where('active', true)->sum('fuarea');

        return $farmareacurrent? $farmareacurrent: 0;

    }

             public function getfarmcount()
    {
                $year = date("Y");
                $next=$year+1;
                $currentseason=$year."/".$next;
                $farmcount=farmunits::where('season', $currentseason)->where('farmid', $this->id)->where('active', true)->count();

        return $farmcount? $farmcount: 0;

    }

                 public function getreportfarmcount($season)
    {

                $currentseason=$season;
                $farmcount=farmunits::where('season', $currentseason)->where('farmid', $this->id)->where('active', true)->count();

        return $farmcount? $farmcount: 0;

    }

                     public function getreportfarmarea($season)
    {

                $currentseason=$season;
               $farmareacurrent=farmunits::where('season', $currentseason)->where('farmid', $this->id)->where('active', true)->sum('fuarea');

        return $farmareacurrent? $farmareacurrent: 0;

    }



}
