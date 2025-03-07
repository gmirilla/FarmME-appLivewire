<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class inspectionanswers extends Model
{
    //
    protected $fillable = [
        'internalinspectionid',
        'questionid',
        'sectionid',
        'reportid',
        'answer',
        'comments'
        
    ];
}
