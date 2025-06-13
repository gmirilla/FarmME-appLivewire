<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class farmentrance extends Model
{
    //
    protected $fillable=[
        'farm_period','farmid','internalinspectionid','inspectorid'
    ];

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

}
