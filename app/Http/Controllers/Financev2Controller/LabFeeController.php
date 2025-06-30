<?php

namespace App\Http\Controllers\Financev2Controller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LabFeeController extends Controller
{
    // Get all lab fees
    public function index()
    {
        $labFees = DB::table('fn_laboratory_fees_table')
            ->join('college_subjects', function($join){
                $join->on('fn_laboratory_fees_table.subject_id','=','college_subjects.id')->where('college_subjects.deleted','=','');
            })
            ->select('fn_laboratory_fees_table.*','college_subjects.subjCode','college_subjects.subjDesc', 'fn_laboratory_fees_table.laboratory_id as id')
            ->where('fn_laboratory_fees_table.deleted', 0)
            ->get();
            
        return response()->json($labFees);
    }

    // Store new lab fee
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required',
            'lab_units' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);

        $data = [
            'subject_id' => $validated['subject_id'],
            'assess_units' => $request->assessed_units,
            'lab_units' => $validated['lab_units'],
            'amount' => $validated['amount'],
            'school_year_id' => DB::table('sy')->where('isactive', 1)->first()->id,
            'semester_id' => DB::table('semester')->where('isactive', 1)->first()->id,
            'created_by' => Auth::id(),
            'created_date_time' => now(),
            'updated_by' => Auth::id(),
            'updated_date_time' => now()
        ];

        $laboratory_id = DB::table('fn_laboratory_fees_table')->insertGetId($data);
        $labFee = DB::table('fn_laboratory_fees_table')->where('laboratory_id', $laboratory_id)->first();

        return response()->json($labFee, 201);
    }

    // Update lab fee
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'lab_units' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);

        $data = [
            'assess_units' => $request->assessed_units,
            'lab_units' => $validated['lab_units'],
            'amount' => $validated['amount'],
            'school_year_id' => DB::table('sy')->where('isactive', 1)->first()->id,
            'semester_id' => DB::table('semester')->where('isactive', 1)->first()->id,
            'created_by' => Auth::id(),
            'created_date_time' => now(),
            'updated_by' => Auth::id(),
            'updated_date_time' => now()
        ];
        $affected = DB::table('fn_laboratory_fees_table')
            ->where('laboratory_id', $id)
            ->update($data);

        if ($affected === 0) {
            return response()->json(['message' => 'Lab fee not found'], 404);
        }

        $labFee = DB::table('fn_laboratory_fees_table')->where('laboratory_id', $id)->first();

        return response()->json($labFee);
    }

    // Soft delete lab fee
    public function destroy($id)
    {
        $affected = DB::table('fn_laboratory_fees_table')
            ->where('laboratory_id', $id)
            ->update([
                'deleted' => 1,
                'deleted_by' => Auth::id(),
                'deleted_date_time' => now(),
                'updated_by' => Auth::id(),
                'updated_date_time' => now()
            ]);

        if ($affected === 0) {
            return response()->json(['message' => 'Lab fee not found'], 404);
        }

        return response()->json(null, 204);
    }

    // Get single lab fee
    public function show($id)
    {
        $labFee = DB::table('fn_laboratory_fees_table')
            ->where('laboratory_id', $id)
            ->join('college_subjects', function($join){
                $join->on('fn_laboratory_fees_table.subject_id','=','college_subjects.id')->where('college_subjects.deleted','=','');
            })
            ->select('fn_laboratory_fees_table.*','college_subjects.subjCode','college_subjects.subjDesc', 'fn_laboratory_fees_table.laboratory_id as id')
            ->where('fn_laboratory_fees_table.deleted', 0)
            ->first();

        if (!$labFee) {
            return response()->json(['message' => 'Lab fee not found'], 404);
        }

        return response()->json($labFee);
    }
}