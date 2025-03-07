<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class reportquestions extends Model
{
    //
    protected $fillable = [
        'reportid',
        'reportsectionid',
        'question_seq',
        'question',
        'questiontype',
        'questionstate'
    ];
}
