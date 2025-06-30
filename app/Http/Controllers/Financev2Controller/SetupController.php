<?php

namespace App\Http\Controllers\Financev2Controller;

use Illuminate\Http\Request;
use DB;
use Session;
use App\Http\Controllers\Controller;

class SetupController extends Controller
{
    public function setup($page)
    {
        switch($page){
            case 1:
                return view('finance_v2.pages.setup.fees_classification');
                
            case 2:
                return view('finance_v2.pages.setup.laboratory_fees');
                
            case 3:
                return view('finance_v2.pages.setup.payment_schedule');
                
            case 4:
                return view('finance_v2.pages.setup.school_fees');
                
        }
    }

    public function storeClassif(Request $request)
    {
        $request->validate([
            'desc' => 'required|string|max:255',
        ]);

        $result = DB::table('fn_itemclassification')->insertGetId([
            'description' => $request->desc,
            'chartofacc' => $request->coa,
            'createddatetime' => now(),
            'updateddatetime' => now(),
            'deleted' => 0, // Default value
        ]);

        return response()->json([
            'success' => $result,
        ]);
    }

    public function destroyClassif(Request $request)
    {
        return response()->json([
            'success' => DB::table('fn_itemclassification')
                ->where('id', $request->id)
                ->update(['deleted' => 1, 'updateddatetime' => now()])
        ]);
    }

    public function fetchClassif(Request $request)
    {
        $result = DB::table('fn_itemclassification')->where('deleted', 0)->get();
        return $result;
    }

    public function storeFeesItem(Request $request)
    {
        $request->validate([
            'item_code' => 'required|string|max:255|unique:items,itemcode',
            'item_desc' => 'required|string|max:255',
            'item_type' => 'required|in:tuition item,non-tuition item',
        ]);

        $result = DB::table('fn_items')->insertGetId([
            'itemcode' => $request->item_code,
            'description' => $request->item_desc,
            'type' => $request->item_type,
            'createddatetime' => now(),
            'updateddatetime' => now()
        ]);

        return response()->json([
            'success' => $result,
            'data' => DB::table('fn_items')->where('deleted', 0)->get()
        ]);
    }

    public function fetchFeesItem(Request $request)
    {
        $result = DB::table('fn_items')->where('deleted', 0)->get();
        return $result;
    }
}