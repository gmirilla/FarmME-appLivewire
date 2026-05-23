<?php

namespace App\Services;

use App\Models\approvalcommitte;
use App\Models\farmentrance;
use App\Models\farm;
use App\Models\inspectionanswers;
use App\Models\internalinspection;
use App\Models\reportsection;
use App\Models\reports;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InspectionApprovalService
{
    public function handleApprovalAction(internalinspection $inspection, Request $request, User $user): void
    {
        if ($request->has('verifyinspection')) {
            $this->verify($inspection, $request, $user);
        } elseif ($request->has('approvewithcondition')) {
            $this->approveWithCondition($inspection, $request);
        } elseif ($request->has('approvebtn')) {
            $this->approve($inspection, $user);
        } elseif ($request->has('rejectbtn')) {
            $this->reject($inspection);
        }
    }

    public function delete(internalinspection $inspection): void
    {
        inspectionanswers::where('internalinspectionid', $inspection->id)->delete();
        farmentrance::where('internalinspectionid', $inspection->id)->delete();
        $inspection->delete();
    }

    public function renderViewSheet(internalinspection $inspection, Request $request, User $user): mixed
    {
        $reportquestions = DB::table('reportquestions')
            ->leftJoin('inspectionanswers', 'reportquestions.id', '=', 'inspectionanswers.questionid')
            ->leftJoin('reportsections', 'reportquestions.reportsectionid', '=', 'reportsections.id')
            ->select(
                'reportquestions.id as id',
                'reportquestions.reportid as reportid',
                'reportquestions.reportsectionid as reportsectionid',
                'reportquestions.indicator as indicator',
                'reportquestions.question_seq as question_seq',
                'reportquestions.question as question',
                'reportquestions.questiontype as questiontype',
                'reportquestions.questionstate as questionstate',
                'answer',
                'sectionidcomments',
                'section_seq'
            )
            ->where('reportquestions.reportid', $inspection->reportid)
            ->where('reportquestions.questionstate', 'ACTIVE')
            ->where('internalinspectionid', $inspection->id)
            ->orderBy('section_seq', 'asc')
            ->orderBy('question_seq', 'asc')
            ->get();

        $sectionlist = reportsection::where('reportid', $inspection->reportid)
            ->where('sectionstate', 'ACTIVE')
            ->orderBy('section_seq', 'asc')
            ->get();

        $reportname = reports::where('id', $inspection->reportid)->first();
        $farm = farm::where('id', $inspection->farmid)->first();
        $inspector = User::where('id', $inspection->inspectorid)->first();

        if (strpos($reportname->reportname, 'Entrance')) {
            $farmentrance = farmentrance::where('internalinspectionid', $inspection->id)->first();
            $data = compact('reportname', 'reportquestions', 'user', 'inspection', 'farm', 'farmentrance');
            return view('inspection.viewfarmentrance', $data);
        }

        return view('inspection.inspection_view_sheet', compact('reportname', 'reportquestions', 'user', 'inspection', 'farm', 'inspector', 'sectionlist'));
    }

    private function verify(internalinspection $inspection, Request $request, User $user): void
    {
        $inspection->verifiedby = $user->id;
        $inspection->verifieddate = $request->verify_date;
        $inspection->verificationcomments = $request->verify_note;
        $inspection->save();
    }

    private function approveWithCondition(internalinspection $inspection, Request $request): void
    {
        if ($inspection->IMSmanager_approval === null) {
            $inspection->IMSmanager_approval = $inspection->inspectionstate;
        }
        $inspection->conditions = $request->apprconditions;
        $inspection->inspectionstate = $request->ecdecision;
        $inspection->ecomm_checked = 2;

        $approvercommstring = implode(',', $request->acmembers);

        if ($request->has('addcommittee')) {
            $approvercommstring = approvalcommitte::where('is_active', true)
                ->where('year', date('Y'))
                ->pluck('id')
                ->implode(',');
        }

        $inspection->approvalcommittee = $approvercommstring;
        $inspection->save();
    }

    private function approve(internalinspection $inspection, User $user): void
    {
        $inspection->ecomm_checked = 1;
        $inspection->inspectionstate = 'APPROVED';
        $inspection->approvedby = $user->id;
        $inspection->approveddate = date('Y-m-d');

        $report = reports::where('id', $inspection->reportid)->first();
        if (strpos($report->reportname, 'Entrance')) {
            $farm = farm::where('id', $inspection->farmid)->first();
            $farm->farmstate = 'ACTIVE';
            $farm->save();
        }

        $inspection->save();
    }

    private function reject(internalinspection $inspection): void
    {
        $inspection->inspectionstate = 'REJECTED';
        $inspection->save();
    }
}
