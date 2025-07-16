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

    public function farmentrance(){
        return $this->hasOne(farmentrance::class, 'internalinspectionid', 'id');
    }
    
    
}
