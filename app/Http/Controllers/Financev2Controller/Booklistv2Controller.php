<?php

namespace App\Http\Controllers\Financev2Controller;

use Illuminate\Http\Request;
use DB;
use Session;
use App\Http\Controllers\Controller;

class Booklistv2Controller extends Controller
{
    public static function create_booklist(Request $request)
    {
        // Retrieve and validate inputs
        $bookCode = $request->input('bookCode');
        $bookName = $request->input('bookName');
        $amount = $request->input('amount');
        $feesClassification = $request->input('feesClassification');

        // Check if recognitionTypeDesc is provided
        if (empty($bookName)) {
            return response()->json([
                ['status' => 2, 'message' => 'Book Title is required']
            ]);
        }

            DB::table('fn_book_ledger')->insert([
            'code' => $bookCode,
            'title' => $bookName,
            'amount' => $amount,
            'classification_id' => $feesClassification,
            'created_by' => auth()->user()->id,
            'created_date_time' => now(),
        ]);
        return response()->json([
            ['status' => 1, 'message' => 'Book created successfully. Code: '.$bookCode.' | Title: '.$bookName]
        ]);
    }

    public function fetch_booklist()
    {
        $booklist = DB::table('fn_book_ledger')
            ->where('deleted', 0)
            ->select(
                'id',
                'code',
                'title',
                'classification_id',
                'amount',
            )
            ->get();
    
        return response()->json($booklist);
    }

    public static function edit_booklist(Request $request){

        $booklist_id = $request->get('booklist_id');


        $booklist = DB::table('fn_book_ledger')
        ->where('deleted', 0)
        ->where('id',$booklist_id)
        ->select(
            'id',
            'code',
            'title',
            'classification_id',
            'amount',
        )
        ->get();

        return response()->json([
            'booklist' => $booklist
           
        ]);
    }

    public static function update_booklist(Request $request)
    {
        // Retrieve and validate inputs
        $booklistTypeId = $request->input('booklistTypeId');
        $editbookCode = $request->input('editbookCode');
        $editbookName = $request->input('editbookName');
        $editfeesClassification = $request->input('editfeesClassification');
        $editamount = $request->input('editamount');

        // Check if recognitionTypeDesc is provided
        if (empty($editbookName)) {
            return response()->json([
                ['status' => 2, 'message' => 'Book Title is required']
            ]);
        }

            DB::table('fn_book_ledger')->where('id', $booklistTypeId)->update([
            'code' => $editbookCode,
            'title' => $editbookName,
            'classification_id' => $editfeesClassification,
            'amount' => $editamount,
            'updated_by' => auth()->user()->id,
            'updated_date_time' => now(),

        ]);
        return response()->json([
            ['status' => 1, 'message' => 'Book updated successfully. Code: '.$editbookCode.' | Title: '.$editbookName]
        ]);
    }


    public static function delete_booklist(Request $request)
    {
        // Retrieve and validate inputs
        $deletebooklistId = $request->input('deletebooklistId');

            DB::table('fn_book_ledger')->where('id', $deletebooklistId)->update([
            'deleted' => 1,
            'deleted_by' => auth()->user()->id,
            'deleted_date_time' => now(),
        ]);
      

        return response()->json([
            ['status' => 1, 'message' => 'Selected Book Deleted Successfully']
        ]);
    }
}