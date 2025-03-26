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
        'inspectiontype',
        'reportid',
        'inspectionstate',
        'score'
    ];

    public function farm() {
        return $this->hasOne(farm::class);
    }
    
    
}
