<?php

namespace App\Http\Controllers\Financev2Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\FinanceModel;


class StudentAccountV2Controller extends Controller
{
    public function studentAccounts()
    {
        // Your logic to handle student accounts goes here
        return view('finance_v2.pages.studentaccount'); 
    }

    public function getStudentAccounts(Request $request)
    {
        $academicLevel = $request->input('gradelevel');
        $academicProgram = $request->input('academicprogram');
        $schoolyear = $request->input('schoolyear');
        $semester = $request->input('semester');

        $students = DB::table('studinfo')
            ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id');
            
        $students->leftJoin('sections', function ($join) {
            $join->on('studinfo.sectionid', '=', 'sections.id')
                ->whereBetween('gradelevel.id', [1, 12]);
        });

        $students->leftJoin('sections as sections_shs', function ($join) {
            $join->on('studinfo.sectionid', '=', 'sections_shs.id')
                ->whereBetween('gradelevel.id', [13, 14]);
        })->leftJoin('sh_strand', function ($join) {
            $join->on('studinfo.strandid', '=', 'sh_strand.id')
                ->whereBetween('gradelevel.id', [13, 14]);
        });

        $students->leftJoin('college_sections', function ($join) {
            $join->on('studinfo.sectionid', '=', 'college_sections.id')
                ->where('gradelevel.id', '>=', 17);
        })->leftJoin('college_courses', function ($join) {
            $join->on('studinfo.courseid', '=', 'college_courses.id')
                ->where('gradelevel.id', '>=', 17);
        });

        $students->leftJoin('studentstatus', 'studinfo.studstatus', '=', 'studentstatus.id')
         ->select(
            'college_courses.courseDesc as college_course',
            'college_sections.sectionDesc as college_section',
            'gradelevel.levelname',
            'sections.sectionname as basic_section',
            'sections_shs.sectionname as shs_section',
            'sh_strand.strandname as shs_strand',
            'studentstatus.description as student_status',
            'studinfo.*'
        );

        return response()->json($students->orderBy('studinfo.lastname', 'asc')->get());
    }

    public function getStudentLedger(Request $request)
{
    $studid = $request->input('studid');

    $studentQuery = DB::table('studinfo')
        ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
        ->leftJoin('sections', function ($join) {
            $join->on('studinfo.sectionid', '=', 'sections.id')
                ->whereBetween('gradelevel.id', [1, 12]);
        })
        ->leftJoin('sections as sections_shs', function ($join) {
            $join->on('studinfo.sectionid', '=', 'sections_shs.id')
                ->whereBetween('gradelevel.id', [13, 14]);
        })
        ->leftJoin('sh_strand', function ($join) {
            $join->on('studinfo.strandid', '=', 'sh_strand.id')
                ->whereBetween('gradelevel.id', [13, 14]);
        })
        ->leftJoin('college_sections', function ($join) {
            $join->on('studinfo.sectionid', '=', 'college_sections.id')
                ->where('gradelevel.id', '>=', 17);
        })
        ->leftJoin('college_courses', function ($join) {
            $join->on('studinfo.courseid', '=', 'college_courses.id')
                ->where('gradelevel.id', '>=', 17);
        })
        ->leftJoin('studentstatus', 'studinfo.studstatus', '=', 'studentstatus.id')
        ->leftJoin('grantee', 'studinfo.grantee', '=', 'grantee.id')
        ->select(
            DB::raw("CONCAT(studinfo.lastname, ', ', studinfo.firstname, ' ', 
                    COALESCE(studinfo.middlename, ''), ' ', 
                    COALESCE(studinfo.suffix, '')) as studentname"),
            'studinfo.id',
            'studinfo.sid',
            'gradelevel.levelname',
            'sections.sectionname as basic_section',
            'sections_shs.sectionname as shs_section',
            'sh_strand.strandcode as shs_strand',
            'college_sections.sectionDesc as college_section',
            'college_courses.courseDesc as college_course',
            'studentstatus.description as student_status',
            'grantee.description as grantee',
            'studinfo.sectionid' // ✅ Added sectionid
        )
        ->where('studinfo.id', $studid);

    $student = $studentQuery->first();

    // Fetch student ledger details
    $studentLedger = DB::table('studledger')
        ->join('itemclassification', 'studledger.classid', '=', 'itemclassification.id')
        ->where('studid', $student->id)
        ->where('studledger.deleted', 0)
        ->select(
            'itemclassification.description',
            'studledger.particulars', 
            'studledger.amount', 
            'studledger.payment'
        )
        ->get();

    return response()->json([
        'student' => $student,
        'ledger' => $studentLedger
    ]);
}


    public function adjustment_history(Request $request)
    {
        $studid = $request->input('studid');

        $adjustment_history = DB::table('adjustments')
            ->join('adjustmentdetails', 'adjustments.id', '=', 'adjustmentdetails.headerid')
            ->join('users', 'adjustments.createdby', '=', 'users.id')
            ->where('adjustmentdetails.studid', $studid) // Corrected `studid` usage
            ->where('adjustmentdetails.deleted', 0)
            ->where('adjustments.deleted', 0)
            ->select(
                'adjustments.id as adjustment_id',
                'adjustments.description',
                DB::raw("CONCAT(DAY(adjustments.createddatetime), ' ', MONTHNAME(adjustments.createddatetime), ' ', YEAR(adjustments.createddatetime)) as createddatetime"),
                'users.name as transactionby',
                'adjustments.amount as adjustment_amount',
            )
            ->get();

        return response()->json([
            'adjustment_history' => $adjustment_history
        ]);
    }


    public function additems(Request $request)
    {
        // $request->validate([
        //     'itemCode' => 'required|string|unique:items,item_code',
        //     'itemDescription' => 'required|string',
        //     'chartOfAccount' => 'required|exists:chart_of_accounts,id',
        //     'tuitionItem' => 'required|boolean',
        //     'nonTuitionItem' => 'required|boolean',
        // ]);

        // Insert into database
        $item = DB::table('fn_items')
        ->insert([
            'itemcode' => $request->itemCode,
            'description' => $request->itemDescription,
            'chartofacc' => $request->chartOfAccount,
            // 'tuition_item' => $request->tuitionItem,
            // 'non_tuition_item' => $request->nonTuitionItem
        ]);

        return response()->json(['success' => true, 'message' => 'Item added successfully!']);
    }


    public function saveAdjustment(Request $request)
    {
        $studid = $request->input('studid');
        $amount = $request->input('amount');
        $payment = $request->input('payment', 0.0);
        $particulars = $request->input('particulars');
        $refnum = $request->input('refnum');
        $classid = $request->input('classid');
        $isdebit = $request->input('isdebit', 0);
        $iscredit = $request->input('iscredit', 0);

        $adjustment = DB::table('fn_adjustment')->insertGetId([
            'refnum' => $refnum,
            'amount' => $amount,
            'syid' => FinanceModel::getSYID(),
            'semid' => FinanceModel::getsemID(),
            'description' => $particulars,
            'isdebit' => $isdebit, 
            'iscredit' => $iscredit,
            'classid' => $classid,
            'deleted' => 0,
            'createdby' => auth()->user()->id,
            'createddatetime' => Carbon::now('Asia/Manila'),
        ]);

        DB::table('fn_adjustmentdetails')->insert([
            'headerid' => $adjustment,
            'studid' => $studid,
            'deleted' => 0,
            'createddatetime' => Carbon::now('Asia/Manila'),
            'createdby' => auth()->user()->id,
        ]);

        DB::table('studledger')->insert([
            'studid' => $studid,
            'particulars' => "ADJ: $particulars - REFERENCE NUM: $refnum",
            'amount' => $amount,
            'payment' => $payment,
            'classid' => $classid,
            'syid' => FinanceModel::getSYID(),
            'semid' => FinanceModel::getsemID(),
            'createdby' => auth()->user()->id,
            'createddatetime' => Carbon::now('Asia/Manila'),
            'deleted' => 0,
        ]);

        return response()->json(['success' => true, 'message' => 'Adjustment saved successfully.']);
    }
}
