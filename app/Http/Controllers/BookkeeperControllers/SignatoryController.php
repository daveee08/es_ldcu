<?php

namespace App\Http\Controllers\BookkeeperControllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use DB;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class SignatoryController extends Controller
{
    public function addSignatories(Request $request)
    {
        $request->validate([
            'signatories' => 'required|array|min:1',
            'signatories.*.name' => 'required|string',
            'signatories.*.title' => 'required|string',
            'signatories.*.description' => 'required|string',
        ]);

        $categories = DB::table('bk_signatory_grp')->pluck('id');

        if ($categories->isEmpty()) {
            return response()->json(['error' => 'No signatory groups found.'], 404);
        }

        $insertData = [];

        foreach ($categories as $groupId) {
            foreach ($request->signatories as $signatory) {
                $insertData[] = [
                    'name' => $signatory['name'],
                    'title' => $signatory['title'],
                    'description' => $signatory['description'],
                    'signatory_grp_id' => $groupId,
                    'createddatetime' => Carbon::now('Asia/Manila'),
                    'createdby' => auth()->user()->id,
                ];
            }
        }

        DB::table('signatory')->insert($insertData);

        return response()->json(['message' => 'Signatories added to all groups successfully.']);
    }

    public function addSignatory(Request $request)
    {
        foreach ($request->signatories as $signatory) {
            DB::table('signatory')->insert([
                'signatory_grp_id' => $request->group_id,
                'description' => $signatory['description'],
                'name' => $signatory['name'],
                'title' => $signatory['title'],
                'createddatetime' => Carbon::now('Asia/Manila'),
                'createdby' => auth()->user()->id,
            ]);
        }

        return response()->json([
            'message' => 'Signatories added successfully!',
        ]);
    }

    public function getSignatories(Request $request)
    {
        $signatories = DB::table('signatory')
            ->leftJoin('bk_signatory_grp', 'signatory.signatory_grp_id', '=', 'bk_signatory_grp.id')
            ->select('signatory.*', 'bk_signatory_grp.description as group_description')
            ->where('deleted', 0)
            ->orderBy('signatory_grp_id')
            ->get();

        return response()->json($signatories);
    }

    public function deleteSignatory(Request $request)
    {
        DB::table('signatory')
            ->where('id', $request->id)
            ->update([
            'deleted' => 1,
            'deleteddatetime' => Carbon::now('Asia/Manila'),
            'deletedby' => auth()->user()->id,
        ]);

        return response()->json([
            'message' => 'Signatory deleted successfully!',
        ]);
    }
}