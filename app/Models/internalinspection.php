<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class internalinspection extends Model
{
    //
    protected $fillable = [
        'farmid',
        'latitude',
        'longitude',
        'inspectorid',
        'inspectiondate',
        'reportid',
        'inspectionstate',
        'score',
        'updated_uid',
        'comments',
        'conditions',
        'season'
    ];

    public function getfarm() {
        $farm=farm::where('id', $this->farmid)->first();
        return $farm ;

    }

    public function getothercropsize() {
        $farmentrance=farmentrance::where('internalinspectionid',$this->id)->first();
        $othercroparea=othercropsrecords::where('farmid', $this->farmid)->where('active', true )
        ->where('season',$this->season)->where('farmentranceid',$farmentrance->id)->sum('area');
        return $othercroparea ;

    }

    public function farmentrance(){
        return $this->hasOne(farmentrance::class, 'internalinspectionid', 'id');
    }

    public function reportgingerproduction(){
        $farmentrance=farmentrance::where('farm_period', $this->season)->where('farmid',$this->farmid)->first();

        return $farmentrance;

    }
    
    
}
