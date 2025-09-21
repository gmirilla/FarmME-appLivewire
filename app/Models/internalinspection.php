<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;


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
        'season', 'approvalcommittee', 'approvedby', 'approveddate',
        'verifiedby', 'verifieddate', 'verificationcomments'
    ];
        public function getverificationstatus()
    {
        //$report = reports::where('id', $this->reporid)->first();
        if (in_array($this->inspectionstate, ['CONDITIONAL'])) {
            # code...
            if ($this->verifiedby != null){
                $verificationstatus="VERIFIED";
            }
            else{
                $verificationstatus="PENDING";
            }
        } else {
            # code...
            $verificationstatus= null;
        }
        
        return $verificationstatus;
    }


    public function getfarm()
    {
        $farm = farm::where('id', $this->farmid)->first();
        return $farm;
    }


    public function getreport()
    {
        $report = reports::where('id', $this->reportid)->first();
        return $report;
    }


    public function getothercropsize()
    {
        $farmentrance = farmentrance::where('internalinspectionid', $this->id)->first();
        $othercroparea = othercropsrecords::where('farmid', $this->farmid)->where('active', true)
            ->where('season', $this->season)->where('farmentranceid', $farmentrance->id)->sum('area');
        return $othercroparea;
    }

    public function getplotdetails()
    {
        try {
            $farmentrance = farmentrance::where('internalinspectionid', $this->id)->first();
            if (!$farmentrance) {
                return null; // or handle the case where no farmentrance is found
            }
            $farmplot = farmunits::where('farmentranceid', $farmentrance->id)->where('active', true)
                ->orderBy('created_at', 'asc') // Oldest first
                ->first();
            return $farmplot;
        } catch (\Exception $e) {
            // Log the exception or handle it as needed
            $errorMessage = "Something went wrong at " . date("Y-m-d H:i:s");
            $errorCause = "Error: " . $e->getMessage();
            $errorTrace = "Internal Inspection ID get plot details:  " . $this->id;

            // Write the error message to the log file
            Log::error($errorMessage);
            Log::error($errorCause);
            Log::error($errorTrace);
            return null; // or some default value
        }
    }

    public function farmentrance()
    {
        return $this->hasOne(farmentrance::class, 'internalinspectionid', 'id');
    }

    public function getapprcomm()
    {

        $approvercommArray = explode(',', $this->approvalcommittee);

        $apprcomm = approvalcommitte::whereIn('id', $approvercommArray)->get();
        return $apprcomm;
    }

    public function reportgingerproduction()
    {
        $farmentrance = farmentrance::where('farm_period', $this->season)->where('farmid', $this->farmid)->first();

        return $farmentrance;
    }

    public function reportinspectorname()
    {
        $reportinspectorname = User::where('id', $this->inspectorid)->first();
        return $reportinspectorname;
    }

        public function getverifiedby()
    {
        $verifiedby = User::where('id', $this->verifiedby)->first();
        return $verifiedby;
    }
}
