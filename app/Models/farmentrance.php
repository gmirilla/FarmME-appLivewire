<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class farmentrance extends Model
{
    //
    protected $fillable=[
        'farm_period','farmid','internalinspectionid','inspectorid', 'surname','fname','farmcode','nationalidno',
        'yob','phoneno', 'householdsize','address', 'lastinspection','inpsectionresult', 'crop','cropvariety',
        'regdate','fieldofficer','farmerpicture', 'annex6id','annex6accepted'
    ];

    public function inspectionsheet(){
        return $this->hasOne(internalinspection::class, 'id', 'internalinspectionid');
    }
    

    public function getvolumesold()
    {
         $volsold=misccodes::where('parameter','vol')->where('farmid', $this->farmid)->where('active', true)->get();
        return $volsold;

    }

    public function getcropdeliver()
    {
        // get the previous years crop delivered
    
        list($startYear, $endYear) = explode("/", $this->farm_period);
        $prevseason=($startYear - 1) . "/" . ($endYear - 1);
        $prevcropdel=misccodes::where('farmid', $this->farmid)->where('season', $prevseason)->where('parameter','cropdel')->first();
        return $prevcropdel;

    }

        public function getcropproduced()
    {
        // get the previous years crop produced
    
        list($startYear, $endYear) = explode("/", $this->farm_period);
        $prevseason=($startYear - 1) . "/" . ($endYear - 1);
        $prevcropprod=misccodes::where('farmid', $this->farmid)->where('season', $prevseason)->where('parameter','cropprod')->first();
        return $prevcropprod;

    }

        public function reportprodhistory()
    {
         $prodhistory=farmunits::where('farmid',$this->farmid)->where('active', true)->get();
        return $prodhistory;

    }
            public function reportvolcropdel()
    {
         $reportvolsold=misccodes::where('parameter','vol')->where('farmid', $this->farmid)->where('active', true)->orderBy('season', 'desc')->get();
        return $reportvolsold;

    }
    public function reportagrochems()
    {
         $reportagrochems=agrochemicalrecords::where('entranceid', $this->id)->where('active', true)->get();
        return $reportagrochems;

    }
        public function reportothercrops()
    {
         $reportothercrops=othercropsrecords::where('farmentranceid', $this->id)->where('active', true)->get();
        return $reportothercrops;

    }
            public function reportinspectorname()
    {
         $reportinspectorname=User::where('id', $this->inspectorid)->first();
        return $reportinspectorname;

    }

                public function getestimatedyield()
    {
         $estyield=farmunits::where('farmentranceid', $this->id)->where('active', true)->sum('estimatedyield');
        return $estyield ? $estyield : 0;

    }

}
