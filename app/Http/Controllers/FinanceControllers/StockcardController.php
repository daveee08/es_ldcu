<?php

namespace App\Http\Controllers\FinanceControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;


class StockcardController extends Controller
{
    public function getStockCard(Request $request)
{
    $query = DB::table('stock_card')
        ->join('items', function ($join) {
            $join->on('items.id', '=', 'stock_card.itemid')
                 ->where('items.deleted', 0);
        })
        ->join('users', function ($join) {
            $join->on('users.id', '=', 'stock_card.transacted_by');
        })
        ->leftJoin('hr_departments', function ($join) {
            $join->on('hr_departments.id', '=', 'stock_card.department');
        })
        ->where('stock_card.deleted', 0)
        ->orderBy('stock_card.created_at', 'desc')
        ->select(
            'items.description',
            'stock_card.*',
            'hr_departments.department as deparment_name',
            'users.name',
            'items.id as itemid'
        );

    // Filter by item ID if provided
    if ($request->has('itemid') && !empty($request->itemid)) {
        $query->where('stock_card.itemid', $request->itemid);
    }

    $data = $query->get();

    return response()->json($data);
}


public function print(Request $request)
    {
        $itemid = $request->input('item');

        $schoolinfo = DB::table('schoolinfo')->get();

        $sy = DB::table('sy')
            ->where('isactive', '1')
            ->get();

        $query = DB::table('stock_card')
            ->join('items', function ($join) {
                $join->on('items.id', '=', 'stock_card.itemid');
                $join->where('items.deleted', 0);
            })
            ->join('users', function ($join) {
                $join->on('users.id', '=', 'stock_card.transacted_by');
            })
            ->leftJoin('hr_departments', function ($join) {
                $join->on('hr_departments.id', '=', 'stock_card.department');
            })
            ->where('stock_card.deleted', 0);

        if (!empty($itemid)) {
            $query->where('stock_card.itemid', $itemid);
        }

        $data = $query->orderBy('stock_card.created_at', 'desc')
            ->select(
                'items.description',
                'stock_card.*',
                'hr_departments.department as deparment_name',
                'users.name'
            )
            ->get();

        $printedby = DB::table('teacher')
            ->select('teacher.lastname', 'teacher.firstname', 'teacher.middlename', 'teacher.suffix')
            ->where('userid', auth()->user()->id)
            ->first();

        $printeddatetime = date('F d, Y h:i:s A');

        return view('finance/reports/pdf/pdf_stock_card', [
            'data' => $data,
            'schoolinfo' => $schoolinfo,
            'sy' => $sy,
            'printeddatetime' => $printeddatetime,
            'printedby' => $printedby
        ]);
    }
}

