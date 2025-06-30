<?php

namespace App\Http\Controllers\BookkeeperControllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use DB;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class ExpensesItemController extends Controller
{
    public function displayItems(Request $request)
    {
        $items = DB::table('bk_expenses_items')
        ->where('deleted', 0)
        ->get();

        return response()->json($items);
    }
    public function addExpenseItem(Request $request)
    {
        DB::table('bk_expenses_items')->insert([
            'itemcode' => $request->itemCode,
            'description' => $request->itemName,
            'qty' => $request->quantity,
            'amount' => $request->amount,
            'itemtype' => $request->itemType,
            'coaid' => $request->debitAccount,
            'createddatetime' => Carbon::now('Asia/Manila'),
            'createdby' => auth()->user()->id,
        ]);

        return response()->json([
            'message' => 'Expense item added successfully!',
        ]);
    }

    public function deleteExpenseItem(Request $request)
    {
        DB::table('bk_expenses_items')
        ->where('id', $request->id)
        ->update([
            'deleted' => 1,
            'deleteddatetime' => Carbon::now('Asia/Manila'),
            'deletedby' => auth()->user()->id,
        ]);

        return response()->json([
            'message' => 'Expense item deleted successfully!',
        ]);
    }

    // public function editExpenseItem(Request $request)
    // {
    //     $item = DB::table('bk_expenses_items')
    //         ->leftJoin('chart_of_accounts', 'bk_expenses_items.coaid', '=', 'chart_of_accounts.id')
    //         ->leftJoin('bk_sub_chart_of_accounts', 'bk_expenses_items.coaid', '=', 'bk_sub_chart_of_accounts.id')
    //         ->where('bk_expenses_items.id', $request->id)
    //         ->where('bk_expenses_items.deleted', 0)
    //         ->select(
    //             'bk_expenses_items.*',
    //             DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.id ELSE bk_sub_chart_of_accounts.id END AS coaid'),
    //             DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.account_name ELSE bk_sub_chart_of_accounts.sub_account_name END AS account_name'),
    //             DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.code ELSE bk_sub_chart_of_accounts.sub_code END AS account_code')
    //         )
    //         ->first();

    //     return response()->json($item);
    // }

//     public function editExpenseItem(Request $request)
// {
//     $item = DB::table('bk_expenses_items')
//         ->leftJoin('chart_of_accounts', 'bk_expenses_items.coaid', '=', 'chart_of_accounts.id')
//         ->leftJoin('bk_sub_chart_of_accounts', 'bk_expenses_items.coaid', '=', 'bk_sub_chart_of_accounts.id')
//         ->where('bk_expenses_items.id', $request->id)
//         ->where('bk_expenses_items.deleted', 0)
//         ->select(
//             'bk_expenses_items.*',
//             DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.id ELSE bk_sub_chart_of_accounts.id END AS coaid'),
//             DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.account_name ELSE bk_sub_chart_of_accounts.sub_account_name END AS account_name'),
//             DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.code ELSE bk_sub_chart_of_accounts.sub_code END AS account_code'),
//             DB::raw('CASE 
//                 WHEN bk_expenses_items.itemtype LIKE "%inventory%" THEN "inventory" 
//                 ELSE bk_expenses_items.itemtype 
//                 END AS itemtype') // Standardize itemtype values
//         )
//         ->first();

//     return response()->json($item);
// }

public function editExpenseItem(Request $request)
{
    $item = DB::table('bk_expenses_items')
        ->leftJoin('chart_of_accounts', 'bk_expenses_items.coaid', '=', 'chart_of_accounts.id')
        ->leftJoin('bk_sub_chart_of_accounts', 'bk_expenses_items.coaid', '=', 'bk_sub_chart_of_accounts.id')
        ->where('bk_expenses_items.id', $request->id)
        ->where('bk_expenses_items.deleted', 0)
        ->select(
            'bk_expenses_items.*',
            DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.id ELSE bk_sub_chart_of_accounts.id END AS coaid'),
            DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.account_name ELSE bk_sub_chart_of_accounts.sub_account_name END AS account_name'),
            DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.code ELSE bk_sub_chart_of_accounts.sub_code END AS account_code'),
            // DB::raw('CASE 
            //     WHEN bk_expenses_items.itemtype LIKE "%inventory%" THEN "inventory" 
            //     ELSE bk_expenses_items.itemtype 
            //     END AS itemtype') // Standardize itemtype values
        )
        ->first();

    return response()->json($item);
}

    public function updateExpenseItem(Request $request)
    {
        DB::table('bk_expenses_items')
            ->where('id', $request->id)
            ->update([
                'itemcode' => $request->itemCode,
                'description' => $request->itemName,
                'qty' => $request->quantity,
                'amount' => $request->amount,
                'itemtype' => $request->itemType,
                'coaid' => $request->debitAccount,
                'updateddatetime' => Carbon::now('Asia/Manila'),
                'updatedby' => auth()->user()->id,
            ]);

        return response()->json([
            'message' => 'Expense item updated successfully!',
        ]);
    }
}