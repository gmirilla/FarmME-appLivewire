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
        'cropvariety'


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
}
