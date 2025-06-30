<?php

namespace App\Http\Controllers\Financev2Controller;

use Illuminate\Http\Request;
use DB;
use Session;
use App\Http\Controllers\Controller;

class Disbursementv2Controller extends Controller
{
    public static function create_disbursement_expenses(Request $request)
    {

         $disbursement_type = $request->input('disbursement_type');
         $expenses_date = $request->input('expenses_date');
         $expenses_voucher_no = $request->input('expenses_voucher_no');
         $expenses_ref_no = $request->input('expenses_ref_no');
         $expenses_disbursement_to = $request->input('expenses_disbursement_to');
         $expenses_departmewnt = $request->input('expenses_departmewnt');
         $expenses_amount = $request->input('expenses_amount');
         $payment_type = $request->input('payment_type');
         $bank = $request->input('bank');
         $cheque_no = $request->input('cheque_no');
         $cheque_date = $request->input('cheque_date');
         $expenses_remarks = $request->input('expenses_remarks');

         $tableData = $request->input('tableData');

         $id = DB::table('disbursements')->insertGetId([
            'disbursement_type' => $disbursement_type,
            'date' => $expenses_date, 
            'voucher_no' => $expenses_voucher_no,
            'reference_no' => $expenses_ref_no,
            'disburse_to' => $expenses_disbursement_to,
            'company_department' => $expenses_departmewnt,
            'payment_type' => $payment_type,
            'bank' => $bank,
            'cheque_no' => $cheque_no,
            'cheque_date' => $cheque_date,
            'amount' => $expenses_amount,
            'remarks' => $expenses_remarks,
            'created_at' => now()
        ]);

        $status = 1;
        $message = 'Disbursement Expenses Created Successfully';

        if (empty($tableData)) {
            $message = 'No Items beeing added';
        } else {
            foreach ($tableData as $entry) {

                DB::table('supply_items')->insert([
                    'disbursement_id' => $id,
                    'item_name' => $entry['disbursement_item'],
                    'amount' => $entry['item_amount'],
                    'qty' => $entry['item_quantity'],
                    'total_amount' => $entry['item_total_amount'],
                    'created_at' => now(),
                ]);
            }
        }

        return response()->json([
            ['status' => $status, 'message' => $message]
        ]);
    }

    public static function create_disbursement_students_refund(Request $request)
    {

         $disbursement_type = $request->input('disbursement_type');
         $students_refund_date = $request->input('students_refund_date');
         $students_refund_voucher_no = $request->input('students_refund_voucher_no');
         $students_refund_stud_no = $request->input('students_refund_stud_no');
         $students_refund_to = $request->input('students_refund_to');
         $grade_level = $request->input('grade_level');
         $students_refund_cheque_amount = $request->input('students_refund_cheque_amount');
         $payment_type = $request->input('payment_type');
         $bank = $request->input('bank');
         $cheque_no = $request->input('cheque_no');
         $cheque_date = $request->input('cheque_date');
         $students_refund_remarks = $request->input('students_refund_remarks');

         $reimburse_type = $request->input('reimburse_type');
         
         $forward_nextsy_amount = $request->input('forward_sy_amount');
         $forward_nextsy_sy = $request->input('forward_sy_sy');
         $forward_nextsy_sem = $request->input('forward_sy_sem');
         $forward_nextsy_apply_to = $request->input('forward_sy_apply_to');

         $forward_siblings_amount = $request->input('forward_siblings_amount');
         $forward_siblings_sy = $request->input('forward_siblings_sy');
         $forward_siblings_sem = $request->input('forward_siblings_sem');
         $forward_siblings_apply_to = $request->input('forward_siblings_apply_to');

         $id = DB::table('disbursements')->insertGetId([
            'disbursement_type' => $disbursement_type,
            'date' => $students_refund_date, 
            'voucher_no' => $students_refund_voucher_no,
            'student_no' => $students_refund_stud_no,
            // 'disburse_to' => $expenses_disbursement_to,
            'refund_to' => $students_refund_to,
            'grade_level' => $grade_level,
            'payment_type' => $payment_type,
            'bank' => $bank,
            'cheque_no' => $cheque_no,
            'cheque_date' => $cheque_date,
            'amount' => $students_refund_cheque_amount,
            'remarks' => $students_refund_remarks,
            'created_at' => now()
        ]);

        $status = 1;
        $message = 'Disbursement Students Refund Created Successfully';

       

                DB::table('disburse_reimbursement')->insert([
                    'disbursements_id' => $id,
                    'reimburse_type' => $reimburse_type,
                    'forward_nextsy_amount' => $forward_nextsy_amount,
                    'forward_nextsy_sy' => $forward_nextsy_sy,
                    'forward_nextsy_semester' => $forward_nextsy_sem,
                    'forward_nextsy_applyto' => $forward_nextsy_apply_to,
                    'forward_siblings_amount' => $forward_siblings_amount,
                    'forward_siblings_sy' => $forward_siblings_sy,
                    'forward_siblings_sem' => $forward_siblings_sem,
                    'forward_siblings_applyto' => $forward_siblings_apply_to,
                    'created_at' => now(),
                ]);
        

        return response()->json([
            ['status' => $status, 'message' => $message]
        ]);
    }

    public function fetch_disbursements()
    {
        $disbursements = DB::table('disbursements')
            ->where('deleted', 0)
            ->select(
                'id',
                'disbursement_type',
                'date',
                'voucher_no',
                'refund_to',
                'disburse_to',
                'payment_type',
                'amount'
            )
            ->get();
    
        return response()->json($disbursements);
    }


    public static function delete_disbursements(Request $request)
    {
        // Retrieve and validate inputs
        $deletevoucherId = $request->input('deletevoucherId');

            DB::table('disbursements')->where('id', $deletevoucherId)->update([
            'deleted' => 1,
            'deleted_by' => auth()->user()->id,
            'deleted_date_time' => now(),
        ]);
      

        return response()->json([
            ['status' => 1, 'message' => 'Selected Voucher Deleted Successfully']
        ]);
    }

    public static function edit_disbursements_expenses(Request $request){

        $voucherid = $request->get('voucherid');
        $disburse_type = $request->get('disburse_type');


        $disbursement_expenses = DB::table('disbursements')
        ->where('deleted', 0)
        ->where('id',$voucherid)
        ->where('disbursement_type',$disburse_type)
        ->select(
            'id',
            'date',
            'voucher_no',
            'reference_no',
            'disburse_to',
            'company_department',
            'payment_type',
            'amount',
            'bank',
            'cheque_no',
            'cheque_date',
            'remarks',
        )
        ->get();

        $disbursement_expenses_items = DB::table('supply_items')
        ->where('deleted', 0)
        ->where('disbursement_id',$voucherid)
        ->select(
            'id',
            'item_name',
            'amount',
            'qty',
            'total_amount'
        )
        ->get();

        return response()->json([
            'disbursement_expenses' => $disbursement_expenses,
            'disbursement_expenses_items' => $disbursement_expenses_items
           
        ]);
    }

    public static function update_disbursement_expenses(Request $request)
    {

         $id = $request->input('id');
         $disbursement_type = $request->input('disbursement_type');
         $expenses_date = $request->input('expenses_date');
         $expenses_voucher_no = $request->input('expenses_voucher_no');
         $expenses_ref_no = $request->input('expenses_ref_no');
         $expenses_disbursement_to = $request->input('expenses_disbursement_to');
         $expenses_departmewnt = $request->input('expenses_departmewnt');
         $expenses_amount = $request->input('expenses_amount');
         $payment_type = $request->input('payment_type');
         $bank = $request->input('bank');
         $cheque_no = $request->input('cheque_no');
         $cheque_date = $request->input('cheque_date');
         $expenses_remarks = $request->input('expenses_remarks');

         $tableData = $request->input('tableData');

         DB::table('disbursements')->where('id', $id)->update([
            'disbursement_type' => $disbursement_type,
            'date' => $expenses_date, 
            'voucher_no' => $expenses_voucher_no,
            'reference_no' => $expenses_ref_no,
            'disburse_to' => $expenses_disbursement_to,
            'company_department' => $expenses_departmewnt,
            'payment_type' => $payment_type,
            'bank' => $bank,
            'cheque_no' => $cheque_no,
            'cheque_date' => $cheque_date,
            'amount' => $expenses_amount,
            'remarks' => $expenses_remarks,
            'updated_at' => now()
        ]);

        if (empty($tableData)) {
            $message = 'No Items beeing added';
        } else {
            // DB::table('supply_items')->where('disbursement_id', $id)->delete();
            foreach ($tableData as $entry) {

                if($entry['disbursement_item'] != 0){
                    DB::table('supply_items')->where('id', $entry['disbursement_item'])->update([
                        'disbursement_id' => $id,
                        'item_name' => $entry['item_name'],
                        'amount' => $entry['item_amount'],
                        'qty' => $entry['item_quantity'],
                        'total_amount' => $entry['item_total_amount'],
                        'updated_at' => now(),
                    ]);
                }else{
                    DB::table('supply_items')->insert([
                        'disbursement_id' => $id,
                        'item_name' => $entry['disbursement_item'],
                        'amount' => $entry['item_amount'],
                        'qty' => $entry['item_quantity'],
                        'total_amount' => $entry['item_total_amount'],
                        'created_at' => now(),
                    ]);
                }
            }
        }

        $status = 1;
        $message = 'Disbursement Expenses Updated Successfully';

        return response()->json([
            ['status' => $status, 'message' => $message]
        ]);
    }


    

}