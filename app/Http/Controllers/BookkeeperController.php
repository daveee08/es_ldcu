<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;
use PDF;
use App\Models\Finance\AccountsReceivableModel;
use DateTime;
use DateInterval;
use DatePeriod;
use Carbon\Carbon;

class BookkeeperController extends Controller
{
    public function loadFiscal(Request $request){
        $fiscalyears = DB::table('bk_fiscal_year')
            ->where('isactive', 1)
            ->where('deleted', 0)
            ->get();

        return $fiscalyears;
    }

    public function storecoa(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'classification' => 'required|string',
                'code' => 'required|string|unique:chart_of_accounts,code',
                'account_name' => 'required|string',
                'account_type' => 'required|integer',
                'financial_statement' => 'required|integer',
                'normal_balance' => 'required|integer',
                'cashflow_statement' => 'required|string',
            ]);

            // If validation fails, return the errors
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $classification = $request->classification;
            $checkClassification = DB::table('bk_classifications')->where('desc', $classification)->first();

            if ($checkClassification) {
                DB::table('bk_classifications')->where('desc', $classification)->update([
                    'desc' => $classification
                ]);
            } else {
                DB::table('bk_classifications')->insert([
                    'desc' => $classification
                ]);
            }

            // Get all existing IDs from both tables
            $existingChartIds = DB::table('chart_of_accounts')->pluck('id')->toArray();
            $existingSubChartIds = DB::table('bk_sub_chart_of_accounts')->pluck('id')->toArray();
            $allExistingIds = array_merge($existingChartIds, $existingSubChartIds);

            // Find the first available ID (starting from 1)
            $newId = 1;
            while (in_array($newId, $allExistingIds)) {
                $newId++;
            }

            // Insert data into the database
            $result = DB::table('chart_of_accounts')->insertGetId([
                'id' => $newId,
                'classification' => strtolower($request->classification),
                'code' => $request->code,
                'account_name' => $request->account_name,
                'account_type' => $request->account_type,
                'financial_statement' => $request->financial_statement,
                'normal_balance' => $request->normal_balance,
                'cashflow_statement' => $request->cashflow_statement,
                'createddatetime' => now(),
            ]);

            // Check if the insertion was successful
            if ($result) {
                return response()->json(['success' => true, 'message' => 'Account added successfully!']);
            } else {
                return response()->json(['success' => false, 'message' => 'Something went wrong!'], 400);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 400);
        }
    }

    //working code
    // public function fetchcoa(Request $request){
    //     try {
    //         $result = DB::table('chart_of_accounts')
    //         ->where('chart_of_accounts.deleted',0)
    //         ->join('bk_account_type',function ($join) {
    //             $join->on('chart_of_accounts.account_type', '=', 'bk_account_type.id');
    //         })
    //         ->join('bk_statement_type',function ($join) {
    //             $join->on('chart_of_accounts.financial_statement', '=', 'bk_statement_type.id');
    //         })
    //         ->join('bk_normalbalance_type',function ($join) {
    //             $join->on('chart_of_accounts.normal_balance', '=', 'bk_normalbalance_type.id');
    //         })
    //         ->select('chart_of_accounts.id','classification','code','account_name','account_type',
    //         'financial_statement as fs','normal_balance','bk_account_type.desc as at', 'bk_statement_type.desc as fst', 'bk_normalbalance_type.desc as nbt','cashflow_statement' )
    //         ->get()
    //         ->groupBy('classification');

    //         if ($result) {
    //             return response()->json(['success' => true, 'data' => $result]);
    //         }else{
    //             return response()->json(['success' => false, 'message' => 'Something went wrong!'], 400);
    //         }
    //     } catch (\Throwable $th) {
    //         //throw $th;
    //         return response()->json(['success' => false, 'message' => $th->getMessage()], 400);
    //     }
    // }

    // public function fetchcoa(Request $request){
    //     try {
    //         $result = DB::table('chart_of_accounts')
    //         ->where('chart_of_accounts.deleted',0)
    //         ->join('bk_account_type',function ($join) {
    //             $join->on('chart_of_accounts.account_type', '=', 'bk_account_type.id');
    //         })
    //         ->join('bk_statement_type',function ($join) {
    //             $join->on('chart_of_accounts.financial_statement', '=', 'bk_statement_type.id');
    //         })
    //         ->join('bk_normalbalance_type',function ($join) {
    //             $join->on('chart_of_accounts.normal_balance', '=', 'bk_normalbalance_type.id');
    //         })
            
    //         ->leftjoin('bk_sub_chart_of_accounts',function ($join) {
    //             $join->on('chart_of_accounts.id', '=', 'bk_sub_chart_of_accounts.coaid');
    //         })

    //         ->select('chart_of_accounts.id','classification','code','account_name','account_type',
    //         'financial_statement as fs','normal_balance','bk_account_type.desc as at', 'bk_statement_type.desc as fst', 'bk_normalbalance_type.desc as nbt','cashflow_statement', 'bk_sub_chart_of_accounts.sub_code', 'bk_sub_chart_of_accounts.sub_account_name' )
    //         ->get()
    //         ->groupBy('classification');

    //         if ($result) {
    //             return response()->json(['success' => true, 'data' => $result]);
    //         }else{
    //             return response()->json(['success' => false, 'message' => 'Something went wrong!'], 400);
    //         }
    //     } catch (\Throwable $th) {
    //         //throw $th;
    //         return response()->json(['success' => false, 'message' => $th->getMessage()], 400);
    //     }
    // }

    //working code
    // public function fetchcoa(Request $request){
    //     try {
    //         // First get all main accounts
    //         $mainAccounts = DB::table('chart_of_accounts')
    //             ->where('chart_of_accounts.deleted', 0)
    //             ->join('bk_account_type', 'chart_of_accounts.account_type', '=', 'bk_account_type.id')
    //             ->join('bk_statement_type', 'chart_of_accounts.financial_statement', '=', 'bk_statement_type.id')
    //             ->join('bk_normalbalance_type', 'chart_of_accounts.normal_balance', '=', 'bk_normalbalance_type.id')
    //             ->select(
    //                 'chart_of_accounts.id',
    //                 'classification',
    //                 'code',
    //                 'account_name',
    //                 'account_type',
    //                 'financial_statement as fs',
    //                 'normal_balance',
    //                 'bk_account_type.desc as at',
    //                 'bk_statement_type.desc as fst',
    //                 'bk_normalbalance_type.desc as nbt',
    //                 'cashflow_statement'
    //             )
    //             ->get();
    
    //         // Then get all sub-accounts grouped by coaid
    //         $subAccounts = DB::table('bk_sub_chart_of_accounts')
    //             ->where('deleted', 0)
    //             ->select('coaid', 'sub_code', 'sub_account_name')
    //             ->get()
    //             ->groupBy('coaid');
    
    //         // Combine the data
    //         $result = $mainAccounts->map(function($account) use ($subAccounts) {
    //             return [
    //                 'main' => $account,
    //                 'subs' => $subAccounts->get($account->id, [])
    //             ];
    //         })->groupBy('main.classification');
    
    //         if ($result) {
    //             return response()->json(['success' => true, 'data' => $result]);
    //         } else {
    //             return response()->json(['success' => false, 'message' => 'Something went wrong!'], 400);
    //         }
    //     } catch (\Throwable $th) {
    //         return response()->json(['success' => false, 'message' => $th->getMessage()], 400);
    //     }
    // }

    // public function fetchcoa(Request $request){
    //     try {
       
    //         // First get all main accounts
    //         $mainAccounts = DB::table('chart_of_accounts')
    //             ->where('chart_of_accounts.deleted', 0)
    //             ->join('bk_account_type', 'chart_of_accounts.account_type', '=', 'bk_account_type.id')
    //             ->join('bk_statement_type', 'chart_of_accounts.financial_statement', '=', 'bk_statement_type.id')
    //             ->join('bk_normalbalance_type', 'chart_of_accounts.normal_balance', '=', 'bk_normalbalance_type.id')
    //             ->select(
    //                 'chart_of_accounts.id',
    //                 'classification',
    //                 'code',
    //                 'account_name',
    //                 'account_type',
    //                 'financial_statement as fs',
    //                 'normal_balance',
    //                 'bk_account_type.desc as at',
    //                 'bk_statement_type.desc as fst',
    //                 'bk_normalbalance_type.desc as nbt',
    //                 'cashflow_statement'
    //             )
              
    //             ->get();

    
    //         // Then get all sub-accounts grouped by coaid
    //         $subAccounts = DB::table('bk_sub_chart_of_accounts')
    //             ->where('deleted', 0)
    //             ->select('id','coaid', 'sub_code', 'sub_account_name')
    //             ->get()
    //             ->groupBy('coaid');
    
    //         // Combine the data
    //         $result = [];
    //         foreach ($mainAccounts as $account) {
    //             $result[$account->classification][] = [
    //                 'main' => $account,
    //                 'subs' => $subAccounts->get($account->id, [])
    //             ];
    //         }
    
    //         if ($result) {
    //             return response()->json(['success' => true, 'data' => $result]);
    //         } else {
    //             return response()->json(['success' => false, 'message' => 'Something went wrong!'], 400);
    //         }
    //     } catch (\Throwable $th) {
    //         return response()->json(['success' => false, 'message' => $th->getMessage()], 400);
    //     }
    // }

    public function fetchcoa(Request $request)
    {
        try {
            $classification = $request->input('classification', 'all');
            
            // First get all main accounts
            $mainAccountsQuery = DB::table('chart_of_accounts')
                ->where('chart_of_accounts.deleted', 0)
                ->join('bk_account_type', 'chart_of_accounts.account_type', '=', 'bk_account_type.id')
                ->join('bk_statement_type', 'chart_of_accounts.financial_statement', '=', 'bk_statement_type.id')
                ->join('bk_normalbalance_type', 'chart_of_accounts.normal_balance', '=', 'bk_normalbalance_type.id')
                ->select(
                    'chart_of_accounts.id',
                    'chart_of_accounts.classification', // Specify table for classification
                    'code',
                    'account_name',
                    'account_type',
                    'financial_statement as fs',
                    'normal_balance',
                    'bk_account_type.desc as at',
                    'bk_statement_type.desc as fst',
                    'bk_normalbalance_type.desc as nbt',
                    'cashflow_statement'
                );

            if ($classification !== 'all') {
                $mainAccountsQuery->where('chart_of_accounts.classification', $classification);
            }

            $mainAccounts = $mainAccountsQuery->get(); // Execute the query here

            // Then get all sub-accounts grouped by coaid
            $subAccounts = DB::table('bk_sub_chart_of_accounts')
                ->where('deleted', 0)
                ->select('id', 'coaid', 'sub_code', 'sub_account_name')
                ->get()
                ->groupBy('coaid');

            // Combine the data
            $result = [];
            foreach ($mainAccounts as $account) {
                if (!isset($result[$account->classification])) {
                    $result[$account->classification] = [];
                }

                $result[$account->classification][] = [
                    'main' => $account,
                    'subs' => $subAccounts->get($account->id, [])
                ];
            }

            if (!empty($result)) {
                return response()->json(['success' => true, 'data' => $result]);
            } else {
                return response()->json(['success' => false, 'message' => 'No accounts found'], 400);
            }
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function fetchtypes(Request $request){
        try {
            $account_types = DB::table('bk_account_type')
            ->where('deleted',0)
            ->select('id','desc')
            ->get();


            $financial_statements = DB::table('bk_statement_type')
            ->where('deleted',0)
            ->select('id','desc')
            ->get();

            $normal_balances = DB::table('bk_normalbalance_type')
            ->where('deleted',0)
            ->select('id','desc')
            ->get();

            $classifications = DB::table('bk_classifications')
            ->where('deleted',0)
            ->select('id','desc')
            ->get();

            $result = ['success'=> true,'at'=> $account_types,'fst'=> $financial_statements,'nbt'=> $normal_balances,'cls'=> $classifications];

            if ($result) {
                return response()->json(['success' => true, 'data' => $result ]);
            }else{
                return response()->json(['success' => false, 'message' => 'Something went wrong!'], 400);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['success' => false, 'message' => $th->getMessage()], 400);
        }
    }

    public function storetypes(Request $request){
        try {
            $request->validate([
                'desc'=> 'required|string',
            ]);
            $result = null;
            
            switch ($request->type) {
                case '1':
                    $result = DB::table('bk_account_type')->insertGetId(['desc' => $request->desc]);
                    break;
                case '2':
                    $result = DB::table('bk_statement_type')->insertGetId(['desc' => $request->desc]);
                    break;
                case '3':
                    $result = DB::table('bk_normalbalance_type')->insertGetId(['desc' => $request->desc]);
                    break;
                case '4':
                    $result = DB::table('bk_classifications')->insertGetId(['desc' => $request->desc]);
                    break;
                
                default:
                    break;
            }

            if($result){
                return response()->json(['success'=> true ]);
            }else{
                return response()->json(['success'=> false]);
            }
            
        } catch (\Throwable $th) {
            return response()->json(['success'=> false,'message'=> $th->getMessage()], 400);
        }
    }

    public function destroytype(Request $request){
        try {
            $result = null;
            switch ($request->type) {
                case '1':
                    $result = DB::table('bk_account_type')->where('id', $request->id)->update(['deleted' => 1]);
                    break;
                case '2':    
                    $result = DB::table('bk_statement_type')->where('id', $request->id)->update(['deleted' => 1]);
                    break;
                case '3':
                    $result = DB::table('bk_normalbalance_type')->where('id', $request->id)->update(['deleted' => 1]);
                    break;
                case '4':
                    $result = DB::table('bk_classifications')->where('id', $request->id)->update(['deleted' => 1]);
                    break;
                
                default:
                    break;
            }

            if($result){
                return response()->json(['success'=> true ]);
            }else{
                return response()->json(['success'=> false]);
            }
        } catch (\Throwable $th) {            
            return response()->json(['success'=> false,'message'=> $th->getMessage()], 400);
        }
    }

    public function destroycoa(Request $request){
        try {
            $result = DB::table('chart_of_accounts')
            ->where('chart_of_accounts.id', $request->id)
            ->update([
                'deleted' => 1  
            ]);
            if($result){
                return response()->json(['success'=> true ]);
            }else{
                return response()->json(['success'=> false]);
            }
        } catch (\Throwable $th) {            
            return response()->json(['success'=> false,'message'=> $th->getMessage()], 400);
        }
    }

    public static function editcoa(Request $request){

        $coa_id = $request->get('coa_id');


        $chart_of_accounts = DB::table('chart_of_accounts')
        ->join('bk_account_type', 'chart_of_accounts.account_type', '=', 'bk_account_type.id')
        ->join('bk_normalbalance_type', 'chart_of_accounts.normal_balance', '=', 'bk_normalbalance_type.id')
        ->join('bk_statement_type', 'chart_of_accounts.financial_statement', '=', 'bk_statement_type.id')
        ->where('chart_of_accounts.deleted', 0)
        ->where('chart_of_accounts.id',$coa_id)
        ->select(
            'chart_of_accounts.id',
            'chart_of_accounts.classification',
            'chart_of_accounts.code',
            'chart_of_accounts.account_name',
            'chart_of_accounts.account_type',
            'chart_of_accounts.financial_statement',
            'chart_of_accounts.normal_balance',
            'chart_of_accounts.cashflow_statement'
        )
        ->get();

        $bk_account_type_all = DB::table('bk_account_type')
        ->get();

        $bk_normalbalance_type_all = DB::table('bk_normalbalance_type')
        ->get();

        $bk_statement_type_all = DB::table('bk_statement_type')
        ->get();

        return response()->json([
            'chart_of_accounts' => $chart_of_accounts,
            'bk_account_type_all' => $bk_account_type_all,
            'bk_normalbalance_type_all' => $bk_normalbalance_type_all,
            'bk_statement_type_all' => $bk_statement_type_all
        ]);
    }

    public static function updatecoa(Request $request)
    {
        // Retrieve and validate inputs
        $coa_id = $request->input('coa_id');
        $classification_edit = $request->input('classification_edit');
        $code_edit = $request->input('code_edit');
        $accountName_edit = $request->input('accountName_edit');
        $accountType_edit = $request->input('accountType_edit');
        $financialStatement_Edit = $request->input('financialStatement_Edit');
        $normalBalance_edit = $request->input('normalBalance_edit');
        $cashflow_statement_edit = $request->input('cashflow_statement_edit');

            DB::table('chart_of_accounts')->where('id', $coa_id)->update([
            'classification' => $classification_edit,
            'code' => $code_edit,
            'account_name' => $accountName_edit,
            'account_type' => $accountType_edit,
            'financial_statement' => $financialStatement_Edit,
            'normal_balance' => $normalBalance_edit,
            'cashflow_statement' => $cashflow_statement_edit
        ]);
        
        return response()->json([
            ['status' => 1, 'message' => 'Chart of Account Updated Successfully']
        ]);
    }

    public static function editacctype(Request $request){

        $acctype_id = $request->get('acctype_id');

        $account_type = DB::table('bk_account_type')
        ->where('id',$acctype_id)
        ->select(
            'id',
            'desc'
        )
        ->get();

        return response()->json([
            'account_type' => $account_type
        ]);
    }

    public static function editstatementtype(Request $request){

        $statement_type_id = $request->get('statement_type_id');

        $statement_type = DB::table('bk_statement_type')
        ->where('id',$statement_type_id)
        ->select(
            'id',
            'desc'
        )
        ->get();

        return response()->json([
            'statement_type' => $statement_type
        ]);
    }

    public static function updateacctype(Request $request)
    {
        // Retrieve and validate inputs
        $acctype_id = $request->input('acctype_id');
        $acctype_desc = $request->input('acctype_desc');

            DB::table('bk_account_type')->where('id', $acctype_id)->update([
            'desc' => $acctype_desc
        ]);
        
        return response()->json([
            ['status' => 1, 'message' => 'Account Type Updated Successfully']
        ]);
    }

    public static function updatestatementtype(Request $request)
    {
        // Retrieve and validate inputs
        $statementtype_id = $request->input('statementtype_id');
        $statement_desc = $request->input('statement_desc');

            DB::table('bk_statement_type')->where('id', $statementtype_id)->update([
            'desc' => $statement_desc
        ]);
        
        return response()->json([
            ['status' => 1, 'message' => 'Statement Type Updated Successfully']
        ]);
    }


    public static function editnormalbalance(Request $request){

        $normal_balance_id = $request->get('normal_balance_id');

        $normal_balance = DB::table('bk_normalbalance_type')
        ->where('id',$normal_balance_id)
        ->select(
            'id',
            'desc'
        )
        ->get();

        return response()->json([
            'normal_balance' => $normal_balance
        ]);
    }

    public static function updatenormalbalance(Request $request)
    {
        // Retrieve and validate inputs
        $normalbalance_id = $request->input('normalbalance_id');
        $normalbalance_desc = $request->input('normalbalance_desc');

            DB::table('bk_normalbalance_type')->where('id', $normalbalance_id)->update([
            'desc' => $normalbalance_desc
        ]);
        
        return response()->json([
            ['status' => 1, 'message' => 'Normal Balance Updated Successfully']
        ]);
    }


    public static function storesupplier(Request $request)
    {
        // Retrieve and validate inputs
        $supplier_name = $request->input('supplier_name');
        $contact_number = $request->input('contact_number');
        $email_address = $request->input('email_address');
        $supplier_address = $request->input('supplier_address');
        $credit_account = $request->input('credit_account');

            DB::table('purchasing_supplier')->insert([
            'suppliername' => $supplier_name,
            'address' => $supplier_address,
            'contactno' => $contact_number,
            'email' => $email_address,
            'coaid' => $credit_account,
            'createdby' => auth()->user()->id,
            'createddatetime' => now(),
        ]);


        return response()->json([
            ['status' => 1, 'message' => 'Supplier Created Successfully']
        ]);
    }

    // public function fetchsupplier()
    // {
    //     $purchasing_supplier = DB::table('purchasing_supplier')
    //         ->join('chart_of_accounts', 'purchasing_supplier.coaid', '=', 'chart_of_accounts.id')
    //         ->where('purchasing_supplier.deleted', 0)
    //         ->select(
    //             'purchasing_supplier.id',
    //             'purchasing_supplier.suppliername',
    //             'purchasing_supplier.address',
    //             'purchasing_supplier.contactno',
    //             'purchasing_supplier.email',
    //             'purchasing_supplier.coaid',
    //             'chart_of_accounts.account_name'
    //         )
    //         ->get();
    
    //     return response()->json($purchasing_supplier);
    // }

    public function fetchsupplier()
    {
        $purchasing_supplier = DB::table('purchasing_supplier')
            ->leftjoin('chart_of_accounts', 'purchasing_supplier.coaid', '=', 'chart_of_accounts.id')
            ->leftjoin('bk_sub_chart_of_accounts', 'purchasing_supplier.coaid', '=', 'bk_sub_chart_of_accounts.id')
            ->where('purchasing_supplier.deleted', 0)
            ->select(
                'purchasing_supplier.id',
                'purchasing_supplier.suppliername',
                'purchasing_supplier.address',
                'purchasing_supplier.contactno',
                'purchasing_supplier.email',
                DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.id ELSE bk_sub_chart_of_accounts.id END AS coaid'),
                DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.account_name ELSE bk_sub_chart_of_accounts.sub_account_name END AS account_name')
            )
            ->get();
    
        return response()->json($purchasing_supplier);
    }

    // public static function editsupplier(Request $request){

    //     $supplier_id = $request->get('supplier_id');

    //     $supplier = DB::table('purchasing_supplier')
    //     ->leftjoin('chart_of_accounts', 'purchasing_supplier.coaid', '=', 'chart_of_accounts.id')
    //     ->leftjoin('bk_sub_chart_of_accounts', 'purchasing_supplier.coaid', '=', 'bk_sub_chart_of_accounts.id')
    //     ->where('purchasing_supplier.id',$supplier_id)
    //     ->select(
    //         'purchasing_supplier.id',
    //         'purchasing_supplier.suppliername',
    //         'purchasing_supplier.address',
    //         'purchasing_supplier.contactno',
    //         'purchasing_supplier.email',
    //         // 'purchasing_supplier.coaid',
    //         // 'chart_of_accounts.account_name'
    //         DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.id ELSE bk_sub_chart_of_accounts.id END AS coaid'),
    //         DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.account_name ELSE bk_sub_chart_of_accounts.sub_account_name END AS account_name')
    //     )
    //     ->get();

    //     // $chart_of_accounts = DB::table('chart_of_accounts')
    //     // ->get();
    //     $chart_of_accounts = DB::table('chart_of_accounts')
    //     ->get();

    //     $bk_sub_chart_of_accounts = DB::table('bk_sub_chart_of_accounts')
    //     ->where('coaid', $chart_of_accounts->first()->id)->get();


    //     return response()->json([
    //         'supplier' => $supplier,
    //         'chart_of_accounts' => $chart_of_accounts,
    //         'bk_sub_chart_of_accounts' => $bk_sub_chart_of_accounts
    //     ]);
    // }

    // public static function editsupplier(Request $request) {
    //     $supplier_id = $request->get('supplier_id');
    
    //     // First get the supplier with its COA information
    //     $supplier = DB::table('purchasing_supplier')
    //         ->leftJoin('chart_of_accounts', 'purchasing_supplier.coaid', '=', 'chart_of_accounts.id')
    //         ->leftJoin('bk_sub_chart_of_accounts', 'purchasing_supplier.coaid', '=', 'bk_sub_chart_of_accounts.id')
    //         ->where('purchasing_supplier.id', $supplier_id)
    //         ->select(
    //             'purchasing_supplier.id',
    //             'purchasing_supplier.suppliername',
    //             'purchasing_supplier.address',
    //             'purchasing_supplier.contactno',
    //             'purchasing_supplier.email',
    //             'purchasing_supplier.coaid',
    //             'chart_of_accounts.id as coa_id',
    //             'bk_sub_chart_of_accounts.id as sub_coa_id',
    //             DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.account_name ELSE bk_sub_chart_of_accounts.sub_account_name END AS account_name')
    //         )
    //         ->first();
    
    //     // Get all main chart of accounts
    //     $chart_of_accounts = DB::table('chart_of_accounts')->get();
    
    //     // Determine which COA to use for getting sub-accounts
    //     $coa_id_for_subaccounts = null;
        
    //     if ($supplier) {
    //         // Check if the supplier's coaid points to a main COA or sub COA
    //         $is_sub_account = DB::table('bk_sub_chart_of_accounts')
    //             ->where('id', $supplier->coaid)
    //             ->exists();
            
    //         if ($is_sub_account) {
    //             // If it's a sub-account, get its parent COA
    //             $sub_account = DB::table('bk_sub_chart_of_accounts')
    //                 ->where('id', $supplier->coaid)
    //                 ->first();
    //             $coa_id_for_subaccounts = $sub_account ? $sub_account->coaid : null;
    //         } else {
    //             // If it's a main COA, use it directly
    //             $coa_id_for_subaccounts = $supplier->coaid;
    //         }
    //     }
    
    //     // Get sub-accounts based on the determined COA
    //     $bk_sub_chart_of_accounts = collect();
    //     if ($coa_id_for_subaccounts) {
    //         $bk_sub_chart_of_accounts = DB::table('bk_sub_chart_of_accounts')
    //             ->where('coaid', $coa_id_for_subaccounts)
    //             ->get();
    //     }
    
    //     return response()->json([
    //         'supplier' => $supplier,
    //         'chart_of_accounts' => $chart_of_accounts,
    //         'bk_sub_chart_of_accounts' => $bk_sub_chart_of_accounts
    //     ]);
    // }

    // public static function editsupplier(Request $request) {
    //     $supplier_id = $request->get('supplier_id');
    
    //     // First get the supplier with its COA information
    //     $supplier = DB::table('purchasing_supplier')
    //         ->leftJoin('chart_of_accounts', 'purchasing_supplier.coaid', '=', 'chart_of_accounts.id')
    //         ->leftJoin('bk_sub_chart_of_accounts', 'purchasing_supplier.coaid', '=', 'bk_sub_chart_of_accounts.id')
    //         ->where('purchasing_supplier.id', $supplier_id)
    //         ->select(
    //             'purchasing_supplier.id',
    //             'purchasing_supplier.suppliername',
    //             'purchasing_supplier.address',
    //             'purchasing_supplier.contactno',
    //             'purchasing_supplier.email',
    //             'purchasing_supplier.coaid',
    //             'chart_of_accounts.id as coa_id',
    //             'bk_sub_chart_of_accounts.id as sub_coa_id',
    //             DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.account_name ELSE bk_sub_chart_of_accounts.sub_account_name END AS account_name')
    //         )
    //         ->first();
    
    //     // Get all main chart of accounts
    //     $chart_of_accounts = DB::table('chart_of_accounts')->get();
    
    //     // Determine which COA to use for getting sub-accounts
    //     $coa_id_for_subaccounts = null;
        
    //     if ($supplier) {
    //         // Check if the supplier's coaid points to a main COA or sub COA
    //         $is_sub_account = DB::table('bk_sub_chart_of_accounts')
    //             ->where('id', $supplier->coaid)
    //             ->exists();
            
    //         if ($is_sub_account) {
    //             // If it's a sub-account, get its parent COA
    //             $sub_account = DB::table('bk_sub_chart_of_accounts')
    //                 ->where('id', $supplier->coaid)
    //                 ->first();
    //             $coa_id_for_subaccounts = $sub_account ? $sub_account->coaid : null;
    //         } else {
    //             // If it's a main COA, use it directly
    //             $coa_id_for_subaccounts = $supplier->coaid;
    //         }
    //     }
    
    //     // Get sub-accounts based on the determined COA
    //     $bk_sub_chart_of_accounts = collect();
    //     if ($coa_id_for_subaccounts) {
    //         $bk_sub_chart_of_accounts = DB::table('bk_sub_chart_of_accounts')
    //             ->where('coaid', $coa_id_for_subaccounts)
    //             ->get();
    //     }
    
    //     return response()->json([
    //         'supplier' => $supplier,
    //         'chart_of_accounts' => $chart_of_accounts,
    //         'bk_sub_chart_of_accounts' => $bk_sub_chart_of_accounts
    //     ]);
    // }

    public static function editsupplier(Request $request){

        $supplier_id = $request->get('supplier_id');

        $supplier = DB::table('purchasing_supplier')
        ->leftjoin('chart_of_accounts', 'purchasing_supplier.coaid', '=', 'chart_of_accounts.id')
        ->leftjoin('bk_sub_chart_of_accounts', 'purchasing_supplier.coaid', '=', 'bk_sub_chart_of_accounts.id')
        ->where('purchasing_supplier.id',$supplier_id)
        ->select(
            'purchasing_supplier.id',
            'purchasing_supplier.suppliername',
            'purchasing_supplier.address',
            'purchasing_supplier.contactno',
            'purchasing_supplier.email',
            // 'purchasing_supplier.coaid',
            // 'chart_of_accounts.account_name'
            DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.id ELSE bk_sub_chart_of_accounts.id END AS coaid'),
            DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.account_name ELSE bk_sub_chart_of_accounts.sub_account_name END AS account_name')
        )
        ->get();

        // $chart_of_accounts = DB::table('chart_of_accounts')
        // ->get();
        $chart_of_accounts = DB::table('chart_of_accounts')
        ->get();

        $bk_sub_chart_of_accounts = DB::table('bk_sub_chart_of_accounts')
        ->get();


        return response()->json([
            'supplier' => $supplier,
            'chart_of_accounts' => $chart_of_accounts,
            'bk_sub_chart_of_accounts' => $bk_sub_chart_of_accounts
        ]);
    }



    public static function updatesupplier(Request $request)
    {
        // Retrieve and validate inputs
        $supplier_id = $request->input('supplier_id');
       
        $suppliername = $request->input('suppliername');
        $suppllier_contactno = $request->input('suppllier_contactno');
        $supplier_email = $request->input('supplier_email');
        $suppllier_address = $request->input('suppllier_address');
        $credit_account = $request->input('credit_account');
      

            DB::table('purchasing_supplier')->where('id', $supplier_id)->update([
            'suppliername' => $suppliername,
            'address' => $suppllier_address,
            'contactno' => $suppllier_contactno,
            'email' => $supplier_email,
            'coaid' => $credit_account
        ]);
        
        return response()->json([
            ['status' => 1, 'message' => 'Supplier Updated Successfully']
        ]);
    }

    public static function deletesupplier(Request $request)
    {
        // Retrieve and validate inputs
        $deletesupplierId = $request->input('deletesupplierId');

            DB::table('purchasing_supplier')->where('id', $deletesupplierId)->update([
            'deleted' => 1,
            'deletedby' => auth()->user()->id,
            'deleteddatetime' => now(),
        ]);
      

        return response()->json([
            ['status' => 1, 'message' => 'Supplier Deleted Successfully']
        ]);
    }

    public static function storeitem(Request $request)
    {
        // Retrieve and validate inputs
        $item_name = $request->input('item_name');
        $item_code = $request->input('item_code');
        $item_quantity = $request->input('item_quantity');
        $item_amount = $request->input('item_amount');
        $itemType = $request->input('itemType');
        $debit_account = $request->input('debit_account');

        // Check for duplicate item description
        // $duplicate = DB::table('bk_expenses_items')
        //     ->where('description', $item_name)
        //     ->where('deleted', 0)
        //     ->exists();

        // if ($duplicate) {
        //     return response()->json([
        //         ['status' => 2, 'message' => 'Item with this description already exists']
        //     ]);
        // }

        DB::table('bk_expenses_items')->insert([
            'description' => $item_name,
            'itemcode' => $item_code,
            'itemtype' => $itemType,
            'qty' => $item_quantity,
            'amount' => $item_amount,
            'coaid' => $debit_account,
            'createdby' => auth()->user()->id,
            'createddatetime' => now()
        ]);

        return response()->json([
            ['status' => 1, 'message' => 'Item Created Successfully']
        ]);
    }

    public function fetchitem()
    {
        $items = DB::table('bk_expenses_items')
            ->leftJoin('bk_item_assignment', 'bk_expenses_items.id', '=', 'bk_item_assignment.itemid')
            ->leftJoin('bk_receiving_details_history', 'bk_expenses_items.id', '=', 'bk_receiving_details_history.itemid')
            ->where('bk_expenses_items.itemtype', 'inventory')
            ->where('bk_expenses_items.deleted', 0)
            ->select(
                'bk_expenses_items.id',
                'bk_expenses_items.itemcode',
                'bk_expenses_items.description',
                'bk_expenses_items.qty',
                'bk_expenses_items.amount',
                'bk_expenses_items.coaid',
                'bk_expenses_items.stock_onhand',
                DB::raw('COALESCE(SUM(bk_item_assignment.assign_stock), 0) as total_assign_stock'),
                DB::raw('COALESCE(SUM(bk_receiving_details_history.receivedqty), 0) as total_stockin')
            )
            ->groupBy([
                'bk_expenses_items.id',
                'bk_expenses_items.itemcode',
                'bk_expenses_items.description',
                'bk_expenses_items.qty',
                'bk_expenses_items.amount',
                'bk_expenses_items.coaid',
                'bk_expenses_items.stock_onhand'
            ])
            ->get();
    
        return response()->json($items);
    }

    public function selectedItem(Request $request)
    {
        $selected_item = db::table('bk_expenses_items')
            ->select('id', 'amount', 'qty')
            ->where('id', $request->input('itemId'))
            ->where('deleted', 0)
            ->get();

            $chart_of_accounts_default_inventory = DB::table('chart_of_accounts')
            ->where('id', 22)
            ->first();

            $chart_of_accounts = DB::table('chart_of_accounts')
            ->get();

    
            $bk_sub_chart_of_accounts = DB::table('bk_sub_chart_of_accounts')
            ->get();

        return response()->json([
            'chart_of_accounts_default_inventory' => $chart_of_accounts_default_inventory,
            'selected_item' => $selected_item,
            'chart_of_accounts' => $chart_of_accounts,
            'bk_sub_chart_of_accounts' => $bk_sub_chart_of_accounts
        ]);
    }

    public static function storePO(Request $request)
    {
        // Retrieve and validate inputs
        $poRefNumber = $request->input('poRefNumber');
        $select_supplier = $request->input('select_supplier');
        $podate = $request->input('podate');
        $poremarks = $request->input('poremarks');
        $grand_total_amount = (float)str_replace(',', '', $request->input('grand_total_amount'));

        $po_items = $request->input('po_items');

        // Check if gradePointDesc is provided
        if (empty($poRefNumber)) {
            return response()->json([
                ['status' => 2, 'message' => 'PO Reference Number is required']
            ]);
        }

        if (empty($select_supplier)) {
            return response()->json([
                ['status' => 2, 'message' => 'Supplier is required']
            ]);
        }

        if (empty($podate)) {
            return response()->json([
                ['status' => 2, 'message' => 'Date is required']
            ]);
        }

        if (empty($poremarks)) {
            return response()->json([
                ['status' => 2, 'message' => 'Remarks is required']
            ]);
        }

        // Check if PO Reference Number already exists
        $existingPO = DB::table('purchasing')->where('refno', $poRefNumber)->first();
        if ($existingPO) {
            return response()->json([
                ['status' => 2, 'message' => 'PO Reference Number already exists']
            ]);
        }

     

        // Insert grade point data into the database
        if (empty($po_items)) {
            return response()->json([
                ['status' => 2, 'message' => 'No Items Found']
            ]);
        }

        $id = DB::table('purchasing')->insertGetId([
            'refno' => $poRefNumber,
            'supplierid' => $select_supplier,
            'remarks' => $poremarks,
            'totalamount' => $grand_total_amount,
            'postdatetime' => $podate,
            'createdby' => auth()->user()->id,
            'createddatetime' => now(),
        ]);
        
        foreach ($po_items as $entry) {

            DB::table('purchasing_details')->insert([
                'headerid' => $id,
                'itemid' => $entry['itemId'],
                'amount' => $entry['amount'],
                'qty' => $entry['quantity'],
                'totalamount' => $entry['total'],
                'createdby' => auth()->user()->id,
                'createddatetime' => now(),
            ]);
        }

        return response()->json([
            ['status' => 1, 'message' => 'Purchase Order Created Successfully']
        ]);
    }


    // public function fetchPO()
    // {
    //     $PO = DB::table('purchasing')
    //         ->where('purchasing.deleted', 0)
    //         ->join('purchasing_supplier', 'purchasing.supplierid', '=', 'purchasing_supplier.id')
    //         ->join('purchasing_details', 'purchasing.id', '=', 'purchasing_details.headerid')
    //         ->select(
    //             'purchasing.id',
    //             'purchasing.refno',
    //             'purchasing.supplierid',
    //             'purchasing.remarks',
    //             'purchasing.totalamount',
    //             'purchasing.postdatetime',
    //             'purchasing.dstatus',
    //             'purchasing_supplier.suppliername'
    //         )
    //         ->get();
    
    //     return response()->json($PO);
    // }

    public function fetchPO()
    {
        $PO = DB::table('purchasing')
            ->where('purchasing.deleted', 0)
            ->where('purchasing_details.deleted', 0)
            ->join('purchasing_supplier', 'purchasing.supplierid', '=', 'purchasing_supplier.id')
            ->join('purchasing_details', 'purchasing.id', '=', 'purchasing_details.headerid')
            ->select(
                'purchasing.id',
                'purchasing.refno',
                'purchasing.supplierid',
                'purchasing.remarks',
                DB::raw('SUM(purchasing_details.totalamount) as totalamount'),
                'purchasing.postdatetime',
                'purchasing.dstatus',
                'purchasing_supplier.suppliername'
            )
            ->groupBy('purchasing.id')
            ->orderBy(DB::raw('CAST(purchasing.postdatetime AS DATETIME)'), 'desc')
            ->get();
    
        return response()->json($PO);
    }


    public static function editPO(Request $request){

        $po_id = $request->get('po_id');

        $PO = DB::table('purchasing')
        ->join('purchasing_supplier', 'purchasing.supplierid', '=', 'purchasing_supplier.id')
        ->where('purchasing.id',$po_id)
        ->where('purchasing.deleted',0)
        ->select(
            'purchasing.id',
            'purchasing.refno',
            'purchasing.supplierid',
            'purchasing.remarks',
            'purchasing.totalamount',
            'purchasing.postdatetime',
            'purchasing.dstatus',
            'purchasing_supplier.suppliername'
        )
        ->get();

        $PO_items = DB::table('purchasing_details')
        ->join('bk_expenses_items', 'purchasing_details.itemid', '=', 'bk_expenses_items.id')
        ->join('purchasing', 'purchasing_details.headerid', '=', 'purchasing.id')
        ->leftjoin('bk_disbursements', 'purchasing.refno', '=', 'bk_disbursements.reference_no')
        ->where('purchasing_details.headerid',$po_id)
        ->where('purchasing_details.deleted',0)
        ->select(
            'purchasing_details.id',
            'purchasing_details.headerid',
            'purchasing_details.itemid',
            'purchasing_details.amount',
            'purchasing_details.qty',
            'purchasing_details.totalamount',
            'bk_expenses_items.description'
        )
        ->groupBy('purchasing_details.id')
        ->get();

        $purchasing_supplier = DB::table('purchasing_supplier')
        ->where('deleted',0)
        ->get();

        $PO_disbursements_balance = DB::table('purchasing')
        ->join('bk_disbursements', 'purchasing.refno', '=', 'bk_disbursements.reference_no')
        ->where('purchasing.id',$po_id)
        ->where('purchasing.deleted',0)
        ->select(
            'purchasing.id',
            DB::raw('FORMAT(SUM(bk_disbursements.amount), 0) as totalamount'),
        )
        ->get();

        return response()->json([
            'PO' => $PO,
            'PO_items' => $PO_items,
            'purchasing_supplier' => $purchasing_supplier,
            'PO_disbursements_balance' => $PO_disbursements_balance
        ]);
    }

    public static function deletePO(Request $request)
    {
        // Retrieve and validate inputs
        $deletepoId = $request->input('deletepoId');

            DB::table('purchasing')->where('id', $deletepoId)->update([
            'deleted' => 1,
            'deletedby' => auth()->user()->id,
            'deleteddatetime' => now(),
        ]);
      

        return response()->json([
            ['status' => 1, 'message' => 'Purchase Order Deleted Successfully']
        ]);
    }

    public static function store_expenses_monitoring(Request $request)
    {
        // Retrieve and validate inputs
        $expenseDate = $request->input('expenseDate');
        $referenceNo = $request->input('referenceNo');
        $expenseName = $request->input('expenseName');
        $employeeName = $request->input('employeeName');
        $department = $request->input('department');
        $remarks = $request->input('remarks');
        $grand_total_amount = (float)str_replace(',', '', $request->input('grand_total_amount'));

        $expensesItem = $request->input('expensesItem');

        // Check if gradePointDesc is provided
        if (empty($referenceNo)) {
            return response()->json([
                ['status' => 2, 'message' => 'Expenses Reference Number is required']
            ]);
        }

        $id = DB::table('expense')->insertGetId([
            'refnum' => $referenceNo,
            'transdate' => $expenseDate,
            'description' => $expenseName,
            'requestedbyid' => $employeeName,
            'departmentid' => $department,
            'remarks' => $remarks,
            'amount' => $grand_total_amount,
            'createdby' => auth()->user()->id,
            'createddatetime' => now(),
        ]);

        // Insert grade point data into the database
        foreach ($expensesItem as $entry) {

            DB::table('expensedetail')->insert([
                'headerid' => $id,
                'itemid' => $entry['itemId'],
                'itemprice' => $entry['itemAmount'],
                'qty' => $entry['itemQuantity'],
                'total' => $entry['itemTotal'],
                'createdby' => auth()->user()->id,
                'createddatetime' => now(),
            ]);
        }

        return response()->json([
            ['status' => 1, 'message' => 'Expenses Monitoring Created Successfully']
        ]);
    }

    public function fetch_expenses_monitoring()
    {
        $Expenses_monitoring = DB::table('expense')
            ->where('expense.deleted', 0)
            ->join('teacher', 'expense.requestedbyid', '=', 'teacher.id')
            ->join('hr_departments', 'expense.departmentid', '=', 'hr_departments.id')
            ->select(
                'expense.id',
                'expense.refnum',
                'expense.description',
                'expense.requestedbyid',
                'expense.departmentid',
                'expense.remarks',
                'expense.amount',
                'expense.transdate',
                'expense.status',
                'teacher.firstname',
                'teacher.lastname',
                'hr_departments.department'
            )
            ->get();
    
        return response()->json($Expenses_monitoring);
    }

    public static function edit_expenses_monitoring(Request $request){

        $expense_monitoring_id = $request->get('expense_monitoring_id');

        $expense_monitoring = DB::table('expense')
        ->where('id',$expense_monitoring_id)
        ->where('deleted',0)
        ->select(
            'id',
            'description',
            'requestedbyid',
            'departmentid',
            'amount',
            'transdate',
            'status',
            'refnum',
            'remarks'
        )
        ->get();

        $expense_monitoring_items = DB::table('expensedetail')
        ->join('bk_expenses_items', 'expensedetail.itemid', '=', 'bk_expenses_items.id')
        ->where('expensedetail.headerid',$expense_monitoring_id)
        ->where('expensedetail.deleted',0)
        ->select(
            'expensedetail.id',
            'expensedetail.itemid',
            'expensedetail.headerid',
            'expensedetail.itemprice',
            'expensedetail.qty',
            'expensedetail.total',
            'bk_expenses_items.description'
        )
        ->get();

        $employees = DB::table('teacher')
        ->where('deleted',0)
        ->get();

        $departments = DB::table('hr_departments')
        ->where('deleted',0)
        ->get();

        return response()->json([
            'expense_monitoring' => $expense_monitoring,
            'expense_monitoring_items' => $expense_monitoring_items,
            'employees' => $employees,
            'departments' => $departments
        ]);
    }

    public static function edit_selected_expenses_monitoring(Request $request){

        $selected_expenses_item_id = $request->get('selected_expenses_item_id');

        $expense_monitoring_items = DB::table('expensedetail')
        ->join('bk_expenses_items', 'expensedetail.itemid', '=', 'bk_expenses_items.id')
        ->where('expensedetail.id',$selected_expenses_item_id)
        ->where('expensedetail.deleted',0)
        ->select(
            'expensedetail.id',
            'expensedetail.itemid',
            'expensedetail.headerid',
            'expensedetail.itemprice',
            'expensedetail.qty',
            'expensedetail.total',
            'bk_expenses_items.description'
        )
        ->get();

        $expense_items = DB::table('bk_expenses_items')
        ->where('deleted',0)
        ->get();


        return response()->json([
            'expense_monitoring_items' => $expense_monitoring_items,
            'expense_items' => $expense_items
        ]);
    }

    public static function update_selected_expenses_monitoring(Request $request)
    {
        // Retrieve and validate inputs
        $id_edit_item_selected = $request->input('id_edit_item_selected');
       
        $itemName_edit_selected = $request->input('itemName_edit_selected');
        $amount_edit_selected = $request->input('amount_edit_selected');
        $qty_edit_selected = $request->input('qty_edit_selected');
        $totalAmount_edit_selected = $request->input('totalAmount_edit_selected');
      
            DB::table('expensedetail')->where('id', $id_edit_item_selected)->update([
            'itemid' => $itemName_edit_selected,
            'itemprice' => $amount_edit_selected,
            'qty' => $qty_edit_selected,
            'total' => $totalAmount_edit_selected
        ]);
        
        return response()->json([
            ['status' => 1, 'message' => 'Expenses Monitoring Item Updated Successfully']
        ]);
    }

    public static function update_expenses_monitoring(Request $request)
    {
        // Retrieve and validate inputs
        $expenses_items = $request->input('expenses_items');
        $expenseID_edit = $request->input('expenseID_edit');
        $expenseDate_edit = $request->input('expenseDate_edit');
        $referenceNo_edit = $request->input('referenceNo_edit');
        $expenseName_edit = $request->input('expenseName_edit');
        $employeeName_edit = $request->input('employeeName_edit');
        $department_edit = $request->input('department_edit');
        $remarks_edit = $request->input('remarks_Edit');
        
        // Check if expenseDate_edit is provided
        if (empty($expenseDate_edit)) {
            return response()->json([
                ['status' => 2, 'message' => 'Expenses Date is required']
            ]);
        }

        try {
            DB::table('expense')
                ->where('id', $expenseID_edit)
                ->update([
                    'description' => $expenseName_edit,
                    'requestedbyid' => $employeeName_edit,
                    'departmentid' => $department_edit,
                    'remarks' => $remarks_edit,
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => now()
                ]);

            foreach ($expenses_items as $entry) {
                if ($entry['expensesid'] == 0) {
                    DB::table('expensedetail')->insert([
                        'headerid' => $expenseID_edit,
                        'itemid' => $entry['expenses_item_id'],
                        'itemprice' => $entry['expenses_amount'],
                        'qty' => $entry['expenses_quantity'],
                        'total' => $entry['expenses_total'],
                        'createdby' => auth()->user()->id,
                        'createddatetime' => now(),
                    ]);
                    
                }
                
                // else {
                //     DB::table('expensedetail')->where('headerid', $expenseID_edit)->update([
                //         'itemid' => $entry['expenses_item_id'],
                //         'itemprice' => $entry['expenses_amount'],
                //         'qty' => $entry['expenses_quantity'],
                //         'total' => $entry['expenses_total'],
                //         'updatedby' => auth()->user()->id,
                //         'updateddatetime' => now(),
                //     ]);
                // }
            }

            return response()->json([
                ['status' => 1, 'message' => 'Expenses Monitoring Updated Successfully']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                ['status' => 3, 'message' => 'An error occurred while updating: ' . $e->getMessage()]
            ]);
        }
    }


    public static function delete_selected_expenses_monitoring(Request $request)
    {
        // Retrieve and validate inputs
        $deletepoId = $request->input('deletepoId');

            DB::table('expensedetail')->where('id', $deletepoId)->update([
            'deleted' => 1,
            'deletedby' => auth()->user()->id
        ]);
      

        return response()->json([
            ['status' => 1, 'message' => 'Selected Expenses Monitoring Item Deleted Successfully']
        ]);
    }


    public static function delete_expenses_monitoring(Request $request)
    {
        // Retrieve and validate inputs
        $deleteexpmonitorId = $request->input('deleteexpmonitorId');

            DB::table('expense')->where('id', $deleteexpmonitorId)->update([
            'deleted' => 1,
            'deletedby' => auth()->user()->id,
            'deleteddatetime' => now(),
        ]);
      

        return response()->json([
            ['status' => 1, 'message' => 'Selected expenses deleted successfully']
        ]);
    }


    public static function po_update_approved(Request $request)
    {
        // Retrieve and validate inputs
        $po_id_edit = $request->input('po_id_edit');
        $poReferenceNumber_edit = $request->input('poReferenceNumber_edit');
        $remarks_edit = $request->input('remarks_edit');
        $poDate_edit = $request->input('poDate_edit');
        $supplier_edit = $request->input('supplier_edit');

        $po_items = $request->input('po_items');

        $approve_po = $request->input('approve_po');
        
        // Check if expenseDate_edit is provided
        // if (empty($expenseDate_edit)) {
        //     return response()->json([
        //         ['status' => 2, 'message' => 'Expenses Date is required']
        //     ]);
        // }

        try {
            DB::table('purchasing')
                ->where('id', $po_id_edit)
                ->update([
                    'dstatus' => $approve_po,
                    'remarks' => $remarks_edit,
                    'postdatetime' => $poDate_edit,
                    'supplierid' => $supplier_edit,

                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => now()
            ]);

            if ($approve_po == "POSTED") {
             
                $checkReferenceNo = DB::table('receiving')
                ->where('refnum', $poReferenceNumber_edit)
                ->where('deleted', 0)
                ->first();
    
                if (!empty($checkReferenceNo)) {
                    DB::table('receiving')
                        ->where('refnum', $poReferenceNumber_edit)
                        ->update([
                            'dstatus' => $approve_po
                        ]);
                } else {
                    $receiving_id = DB::table('receiving')
                        ->insertGetId([
                            'poid' => $po_id_edit,
                            'refnum' => $poReferenceNumber_edit,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => now()
                    ]);

                    foreach ($po_items as $entry) {
                        DB::table('receiving_details')->insert([
                            'headerid' => $receiving_id,
                            'poid' => $po_id_edit,
                            'itemid' => $entry['itemId'],
                            'amount' => $entry['amount'],
                            'qty' => $entry['quantity'],
                            'total' => $entry['total'],
                            'disbursed_qty' => $entry['disbursed_qty'],
                            'disbursed_total' => $entry['disbursed_total'],
                            'createdby' => auth()->user()->id,
                            'createddatetime' => now(),
                        ]);
                    }
                }
            }
            // else {
                
            // }




            return response()->json([
                ['status' => 1, 'message' => 'Purchase Order Updated Successfully']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                ['status' => 3, 'message' => 'An error occurred while updating: ' . $e->getMessage()]
            ]);
        }

    }

    // public function fetchReceiving()
    // {
    //     $PO = DB::table('receiving')
    //         ->where('receiving.deleted', 0)
    //         ->where(function($query) {
    //             $query->where('receiving_details.qty', '!=', 0)
    //                   ->where('receiving_details.qty', '!=', 0.0 );
    //         })
    //         // ->where('receiving_details.total', 0)
    //         ->join('purchasing', 'receiving.poid', '=', 'purchasing.id')
    //         ->join('purchasing_supplier', 'purchasing.supplierid', '=', 'purchasing_supplier.id')
    //         ->join('receiving_details', 'receiving.id', '=', 'receiving_details.headerid')
    //         ->leftjoin('bk_disbursements', 'receiving.refnum', '=', 'bk_disbursements.reference_no')
    //         ->groupBy('receiving.id')
           
    //         ->select(
    //             'receiving.id',
    //             'purchasing.refno',
    //             'purchasing.supplierid',
    //             'purchasing.remarks',
    //             // 'purchasing.totalamount',
    //             DB::raw('SUM(receiving_details.total) as totalamount'),
    //             'purchasing.postdatetime',
    //             'receiving.rstatus',
    //             'receiving.dstatus',
    //             'purchasing_supplier.suppliername'
    //         )
    //         ->get();
    //         // ->groupBy('purchasing.id');
    
    //     return response()->json($PO);
    // }



    // public function fetchReceiving()
    // {
    //     // First get the receiving records with their total amounts
    //     $PO = DB::table('receiving')
    //         ->where('receiving.deleted', 0)
    //         ->join('purchasing', 'receiving.poid', '=', 'purchasing.id')
    //         ->join('purchasing_supplier', 'purchasing.supplierid', '=', 'purchasing_supplier.id')
    //         ->join('receiving_details', 'receiving.id', '=', 'receiving_details.headerid')
    //         ->where('receiving_details.qty', '!=', 0)
    //         ->where('receiving_details.qty', '!=', 0.0)
    //         ->groupBy('receiving.id')
    //         ->select(
    //             'receiving.id',
    //             'purchasing.refno',
    //             'purchasing.supplierid',
    //             'purchasing.remarks',
    //             DB::raw('SUM(receiving_details.total) as totalamount'),
    //             'purchasing.postdatetime',
    //             'receiving.rstatus',
    //             'receiving.dstatus',
    //             'purchasing_supplier.suppliername'
    //         )
    //         ->get();
    
    //     // Then get the disbursement totals for these receiving records
    //     $Disburse = DB::table('receiving')
    //         ->where('receiving.deleted', 0)
    //         ->join('purchasing', 'receiving.poid', '=', 'purchasing.id')
    //         ->join('purchasing_supplier', 'purchasing.supplierid', '=', 'purchasing_supplier.id')
    //         ->join('bk_disbursements', 'receiving.refnum', '=', 'bk_disbursements.reference_no')
    //         ->groupBy('receiving.id')
    //         ->select(
    //             'receiving.id',
    //             DB::raw('SUM(bk_disbursements.amount) as disbursetotal')
    //         )
    //         ->get()
    //         ->keyBy('id'); // Key by ID for easy lookup
    
    //     // Filter the PO results to only include records where totalamount != disbursetotal and disbursetotal is lesser than totalamount
    //     $filteredPO = $PO->filter(function ($item) use ($Disburse) {
    //         $disburseItem = $Disburse->get($item->id);
    //         return $disburseItem ? ($item->totalamount != $disburseItem->disbursetotal && $disburseItem->disbursetotal < $item->totalamount) : true;
    //     })->values();
    
    //     return response()->json(['PO' => $filteredPO, 'Disburse' => $Disburse]);
    // }


    public function fetchReceiving()
    {
        // First get the receiving records with their total amounts
        $PO = DB::table('receiving')
            ->where('receiving.deleted', 0)
            ->join('purchasing', 'receiving.poid', '=', 'purchasing.id')
            ->join('purchasing_supplier', 'purchasing.supplierid', '=', 'purchasing_supplier.id')
            ->join('receiving_details', 'receiving.id', '=', 'receiving_details.headerid')
            ->groupBy('receiving.id')
            ->select(
                'receiving.id',
                'purchasing.refno',
                'purchasing.supplierid',
                'purchasing.remarks',
                DB::raw('SUM(receiving_details.total) as totalamount'),
                DB::raw('SUM(receiving_details.qty) as totalquantity'),
                DB::raw('SUM(receiving_details.disbursed_total) as total_disbursedamount'),
                DB::raw('SUM(receiving_details.disbursed_qty) as total_disbursedquantity'),
                'purchasing.postdatetime',
                'receiving.rstatus',
                'receiving.dstatus',
                'purchasing_supplier.suppliername'
            )
            ->get();
    
        // Then get the disbursement totals for these receiving records
        $Disburse = DB::table('receiving')
            ->where('receiving.deleted', 0)
            ->join('purchasing', 'receiving.poid', '=', 'purchasing.id')
            ->join('purchasing_supplier', 'purchasing.supplierid', '=', 'purchasing_supplier.id')
            ->join('bk_disbursements', 'receiving.refnum', '=', 'bk_disbursements.reference_no')
            ->groupBy('receiving.id')
            ->select(
                'receiving.id',
                DB::raw('SUM(bk_disbursements.amount) as disbursetotal')
            )
            ->get()
            ->keyBy('id'); // Key by ID for easy lookup
    
        // Filter the PO results to only include records where totalamount != disbursetotal and disbursetotal is lesser than totalamount
        $filteredPO = $PO->filter(function ($item) use ($Disburse) {
            $disburseItem = $Disburse->get($item->id);
            return $disburseItem ? ($item->totalquantity != 0 && $item->totalquantity != 0.00 || $item->total_disbursedamount != $disburseItem->disbursetotal && $disburseItem->disbursetotal < $item->total_disbursedamount) : true;
        })->values();
    
        // return response()->json(['PO' => $PO]);
        return response()->json($filteredPO);
    }
   

    public static function editReceiving(Request $request){

        $po_id = $request->get('po_id');

        $receiving = DB::table('receiving')
        ->join('purchasing', 'receiving.poid', '=', 'purchasing.id')
        ->join('purchasing_supplier', 'purchasing.supplierid', '=', 'purchasing_supplier.id')
        ->where('receiving.id',$po_id)
        ->where('receiving.deleted',0)
        ->select(
            'receiving.id',
            'purchasing.refno',
            'purchasing.supplierid',
            // 'purchasing.remarks',
            'purchasing.totalamount',
            'purchasing.postdatetime',
            'receiving.invoiceno',
            'receiving.rstatus',
            'receiving.datedelivered',
            'receiving.remarks',
            'purchasing_supplier.suppliername'
        )
        ->get();

        $receiving_items = DB::table('receiving_details')
        ->join('bk_expenses_items', 'receiving_details.itemid', '=', 'bk_expenses_items.id')
        ->where('receiving_details.headerid', $po_id)
        ->select(
            'receiving_details.id',
            'receiving_details.itemid',
            'receiving_details.amount',
            'receiving_details.qty',
            'receiving_details.total',
            'receiving_details.receivedqty',
            'receiving_details.rtotal',
            'receiving_details.disbursed_qty',
            'receiving_details.disbursed_total',
            // 'receiving_details.disbursed_total',
            'bk_expenses_items.description'
        )
        ->get();
    
    $total_amount_receiving = number_format($receiving_items->sum('total'), 0);

    $total_amount_receiving_received = number_format($receiving_items->sum('disbursed_total'), 0);
        

        $purchasing_supplier = DB::table('purchasing_supplier')
        ->where('deleted',0)
        ->get();

        $receiving_disbursements_balance = DB::table('receiving')
        ->join('purchasing', 'receiving.poid', '=', 'purchasing.id')
        ->join('bk_disbursements', 'purchasing.refno', '=', 'bk_disbursements.reference_no')
        ->where('receiving.id',$po_id)
        ->where('receiving.deleted',0)
        ->select(
            'purchasing.id',
            DB::raw('FORMAT(SUM(bk_disbursements.amount), 0) as totalamount'),
        )
        ->get();

        return response()->json([
            'receiving' => $receiving,
            'receiving_items' => $receiving_items,
            'total_amount_receiving' => $total_amount_receiving,
            'total_amount_receiving_received' => $total_amount_receiving_received,
            'purchasing_supplier' => $purchasing_supplier,
            'receiving_disbursements_balance' => $receiving_disbursements_balance
        ]);
    }

    // public static function receiving_update_received(Request $request)
    // {
    //     // Retrieve and validate inputs
    //     $po_id_edit = $request->input('po_id_edit');
    //     $poReferenceNumber_edit = $request->input('poReferenceNumber_edit');
    //     $remarks_edit = $request->input('remarks_edit');
    //     $poDate_edit = $request->input('poDate_edit');
    //     $supplier_edit = $request->input('supplier_edit');
    //     $datereceived_edit = $request->input('datereceived_edit');
    //     $invoice_no_edit = $request->input('invoice_no_edit');

    //     $po_items = $request->input('po_items');

    //     $approve_po = $request->input('approve_po');
        
    //     // Check if expenseDate_edit is provided
    //     // if (empty($expenseDate_edit)) {
    //     //     return response()->json([
    //     //         ['status' => 2, 'message' => 'Expenses Date is required']
    //     //     ]);
    //     // }

    //     try {
           

    //         if ($approve_po == "RECEIVED") {
    //             DB::table('receiving')
    //             ->where('id', $po_id_edit)
    //             ->update([
    //                 'rstatus' => $approve_po,
    //                 'remarks' => $remarks_edit,
    //                 'posteddatetime' => $poDate_edit,
    //                 'datedelivered' => $datereceived_edit,
    //                 'invoiceno' => $invoice_no_edit,
    //                 'updatedby' => auth()->user()->id,
    //                 'updateddatetime' => now()
    //         ]);

    //             foreach ($po_items as $entry) {
        
    //                 DB::table('receiving_details')
    //                 // ->where('poid', $po_id_edit)
    //                 ->where('itemid', $entry['itemId'])
    //                 ->where('headerid', $po_id_edit)
    //                 ->update([
    //                     'amount' => $entry['amount'],
    //                     'qty' => $entry['quantity'],
    //                     'total' => $entry['total'],
    //                     'receivedqty' => $entry['receivedqty'],
    //                     'rtotal' => $entry['rtotal'],
    //                     'createdby' => auth()->user()->id,
    //                     'createddatetime' => now(),
    //                 ]);
    //             }


    //         }
    //         // else {
                
    //         // }




    //         return response()->json([
    //             ['status' => 1, 'message' => 'Receiving Updated Successfully']
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             ['status' => 3, 'message' => 'An error occurred while updating: ' . $e->getMessage()]
    //         ]);
    //     }

    // }

    public static function receiving_update_received(Request $request)
    {
        // Retrieve and validate inputs
        $po_id_edit = $request->input('po_id_edit');
        $poReferenceNumber_edit = $request->input('poReferenceNumber_edit');
        $remarks_edit = $request->input('remarks_edit');
        $poDate_edit = $request->input('poDate_edit');
        $supplier_edit = $request->input('supplier_edit');
        $datereceived_edit = $request->input('datereceived_edit');
        $invoice_no_edit = $request->input('invoice_no_edit');
        $amount = str_replace(',', '', $request->input('amount'));

        $po_items = $request->input('po_items');
        $entries = $request->input('entries');

        $approve_po = $request->input('approve_po');
        
        try {
           
 
            if ($approve_po == "RECEIVED") {

                if (empty($remarks_edit)) {
                    return response()->json([
                        ['status' => 2, 'message' => 'Please provide remarks']
                    ]);
                }
                
            if (empty($entries)) {
                return response()->json([
                    ['status' => 2, 'message' => 'Journal Entries required']
                ]);
            }
            
                $checkExisting = DB::table('bk_receiving_history')
                ->where('invoiceno', $invoice_no_edit)
                ->first();

                if (!empty($checkExisting)) {
                    // DB::table('bk_receiving_history')
                    // ->where('invoiceno', $invoice_no_edit)
                    // ->update([
                    //     'rstatus' => $approve_po,
                    //     'remarks' => $remarks_edit,
                    //     'posteddatetime' => $poDate_edit,
                    //     'datedelivered' => $datereceived_edit,
                    //     'invoiceno' => $invoice_no_edit,
                    //     'amount' => $amount,
                    //     'updatedby' => auth()->user()->id,
                    //     'updateddatetime' => now()
                    // ]);
                    return response()->json([
                        ['status' => 4, 'message' => 'Invoice Number Already Exists']
                    ]);

                } 
                else {
                    $insertId = DB::table('bk_receiving_history')
                    ->insertGetId([
                        'poid' => $po_id_edit,
                        'rstatus' => $approve_po,
                        'remarks' => $remarks_edit,
                        'posteddatetime' => $poDate_edit,
                        'datedelivered' => $datereceived_edit,
                        'invoiceno' => $invoice_no_edit,
                        'amount' => $amount,
                        'isReceived' => 1,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => now()
                    ]);
                }

                foreach ($po_items as $entry) {
        
                    if ((int)$entry['receivedqty'] !== 0 && (float)str_replace(',', '', $entry['rtotal']) !== 0.00) {
                        DB::table('bk_receiving_details_history')
                        ->insert([
                            'headerid' => $insertId,
                            'itemid' => $entry['itemId'],
                            'amount' => $entry['amount'],
                            'qty' => $entry['old_quantity'],
                            'total' => $entry['old_total'],
                            'receivedqty' => $entry['receivedqty'],
                            'rtotal' => str_replace(',', '', $entry['rtotal']),
                            'createdby' => auth()->user()->id,
                            'createddatetime' => now()
                        ]);
                    }
                }

                foreach ($po_items as $entry) {
        
                    DB::table('receiving_details')
                    // ->where('poid', $po_id_edit)
                    ->where('itemid', $entry['itemId'])
                    ->where('headerid', $po_id_edit)
                    ->update([
                    'amount' => $entry['amount'],
                    'qty' => $entry['quantity'],
                    'total' => $entry['total'],
                    // 'disbursed_qty' => $entry['disbursed_qty'],
                    // 'disbursed_total' => $entry['disbursed_total'],
                    'receivedqty' => $entry['receivedqty'],
                    'rtotal' => $entry['rtotal'],
                    'createdby' => auth()->user()->id,
                    'createddatetime' => now(),
                    ]);
                }

                DB::table('receiving')
                    ->where('id', $po_id_edit)
                    ->update([
                    'rstatus' => $approve_po,
                    'remarks' => $remarks_edit,
                    'posteddatetime' => $poDate_edit,
                    'datedelivered' => $datereceived_edit,
                    'invoiceno' => $invoice_no_edit,
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => now()
                ]);

                DB::table('purchasing')
                ->where('refno', $poReferenceNumber_edit)
                ->update([
                'dstatus' => 'SUBMITTED',
                'updatedby' => auth()->user()->id,
                'updateddatetime' => now()
            ]);

                $active_fiscal_year_id = DB::table('bk_fiscal_year')
                    ->where('deleted', 0)
                    ->where('isactive', 1)
                    ->value('id');
                
                 


             
                $voucherNumber = self::generateVoucherNumberJE();
                foreach ($entries as $entry) {
                    // $invoice_no_edit = str_replace('INV-', 'JE-', $invoice_no_edit);
                   
                    DB::table('bk_generalledg')->insert([
                        'voucherNo' => $voucherNumber,
                        'bookkeeper_transvoucher' => $invoice_no_edit,
                        'active_fiscal_year_id' => $active_fiscal_year_id,
                        'date' => $datereceived_edit,
                        'coaid' => $entry['account'],
                        'debit_amount' => $entry['debit'],
                        'credit_amount' => $entry['credit'],
                        'remarks' => $remarks_edit,
                        'createddatetime' => now('Asia/Manila'),
                        'createdby' => auth()->id()
                    ]);
                }

                


            }

            return response()->json([
                ['status' => 1, 'message' => 'Receiving Updated Successfully']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                ['status' => 3, 'message' => 'An error occurred while updating: ' . $e->getMessage()]
            ]);
        }

    }

    public static function storefixed_asset(Request $request)
    {
        // Retrieve and validate inputs
        $depreciationOption = $request->input('depreciationOption');
        $fixed_asset_code = $request->input('fixed_asset_code');
        $fixed_asset_name = $request->input('fixed_asset_name');
        $fixed_asset_purchased_date = $request->input('fixed_asset_purchased_date');
        $fixed_asset_value = $request->input('fixed_asset_value');
        $fixed_asset_warranty = $request->input('fixed_asset_warranty');
        $fixed_asset_serial_number = $request->input('fixed_asset_serial_number');
        $fixed_asset_serial_warranty = $request->input('fixed_asset_serial_warranty');
        $fixed_asset_remarks = $request->input('fixed_asset_remarks');

        $fixed_asset_depreciation_method = $request->input('fixed_asset_depreciation_method');
        $fixed_asset_residual_value = $request->input('fixed_asset_residual_value');
        $fixed_asset_useful_life = $request->input('fixed_asset_useful_life');
        $fixed_asset_depreciation_start_date = $request->input('fixed_asset_depreciation_start_date');
        $fixed_asset_depreciation_rate_per_annum = $request->input('fixed_asset_depreciation_rate_per_annum');
        $fixed_asset_accumulated_depreciation = $request->input('fixed_asset_accumulated_depreciation');
        $fixed_asset_depreciation_je_debit_account = $request->input('fixed_asset_depreciation_je_debit_account');
        $fixed_asset_depreciation_je_credit_account = $request->input('fixed_asset_depreciation_je_credit_account');
        $fixed_asset_loss_on_sale_je_debit_account = $request->input('fixed_asset_loss_on_sale_je_debit_account');
        $fixed_asset_loss_on_sale_je_credit_account = $request->input('fixed_asset_loss_on_sale_je_credit_account');

        if($depreciationOption == "With Depreciation Value"){

            $fixed_asset_id = DB::table('bk_fixedassets')->insertGetId([
                'depreciation_option' => $depreciationOption,
                'code' => $fixed_asset_code,
                'asset_name' => $fixed_asset_name,
                'purchased_date' => $fixed_asset_purchased_date,
                'asset_value' => $fixed_asset_value,
                'warranty' => $fixed_asset_warranty,
                'serial_number' => $fixed_asset_serial_number,
                'serial_warranty' => $fixed_asset_serial_warranty,
                'remarks' => $fixed_asset_remarks,
                'created_by' => auth()->user()->id,
                'createddatetime' => now(),
            ]);
    
            DB::table('bk_fixedassets_with_depreciation')->insert([
                'fixed_assets_id' => $fixed_asset_id,
                'depreciation_method' => $fixed_asset_depreciation_method,
                'residual_value' => $fixed_asset_residual_value,
                'asset_life_yrs' => $fixed_asset_useful_life,
                'depreciation_start_date' => $fixed_asset_depreciation_start_date,
                'depreciation_rate_per_annum' => $fixed_asset_depreciation_rate_per_annum,
                'accumulated_depreciation' => $fixed_asset_accumulated_depreciation,
                'depreciation_je_debit_acc' => $fixed_asset_depreciation_je_debit_account,
    
                'depreciation_je_credit_acc' => $fixed_asset_depreciation_je_credit_account,
                'depreciation_against_saleje_debit_acc' => $fixed_asset_loss_on_sale_je_debit_account,
                'depreciation_against_saleje_credit_acc' => $fixed_asset_loss_on_sale_je_credit_account,
    
                'created_by' => auth()->user()->id,
                'createddatetime' => now(),
            ]);
        }
        else{

            DB::table('bk_fixedassets')->insert([
                'depreciation_option' => $depreciationOption,
                'code' => $fixed_asset_code,
                'asset_name' => $fixed_asset_name,
                'purchased_date' => $fixed_asset_purchased_date,
                'asset_value' => $fixed_asset_value,
                'warranty' => $fixed_asset_warranty,
                'serial_number' => $fixed_asset_serial_number,
                'serial_warranty' => $fixed_asset_serial_warranty,
                'created_by' => auth()->user()->id,
                'createddatetime' => now(),
            ]);

            

        }

        return response()->json([
            ['status' => 1, 'message' => 'Fixed Assets Created Successfully']
        ]);
    }

    public function fetchfixed_asset()
    {
        $fixed_assets = DB::table('bk_fixedassets')
            // ->join('bk_fixedassets_with_depreciation', 'bk_fixedassets.id', '=', 'bk_fixedassets_with_depreciation.fixed_assets_id')
            ->leftJoin('bk_fixedassets_with_depreciation', 'bk_fixedassets.id', '=', 'bk_fixedassets_with_depreciation.fixed_assets_id')
            ->where('bk_fixedassets.deleted', 0)
            ->select(
                'bk_fixedassets.id',
                'bk_fixedassets.depreciation_option',
                'bk_fixedassets.code',
                'bk_fixedassets.asset_name',
                'bk_fixedassets.asset_value',
                'bk_fixedassets.purchased_date',
                'bk_fixedassets_with_depreciation.asset_life_yrs',
                'bk_fixedassets_with_depreciation.depreciation_rate_per_annum'
            )
            ->get();

        // $fixedassets_with_depreciation = [];
        // foreach($fixed_assets as $fixed_asset){
        //     $fixedassets_with_depreciation[$fixed_asset->id] = DB::table('bk_fixedassets_with_depreciation')->where('fixed_assets_id', $fixed_asset->id)->first();
        // }
    
           return response()->json($fixed_assets);
    }

    public static function editfixed_asset(Request $request){

        $fixed_asset_id = $request->get('fixed_asset_id');

        $bk_fixedassets = DB::table('bk_fixedassets')
        ->where('id',$fixed_asset_id)
        ->select(
            'id',
            'depreciation_option',
            'code',
            'asset_name',
            'purchased_date',
            'asset_value',
            'warranty',
            'serial_number',
            'serial_warranty',
            'remarks'
        )
        ->get();

        $bk_fixedassets_with_depreciation = DB::table('bk_fixedassets_with_depreciation')
        ->where('fixed_assets_id',$fixed_asset_id)
        ->select(
            'id',
            'depreciation_method',
            'residual_value',
            'asset_life_yrs',
            'depreciation_start_date',
            'depreciation_rate_per_annum',
            'accumulated_depreciation',
            'depreciation_je_debit_acc',
            'depreciation_je_credit_acc',
            'depreciation_against_saleje_debit_acc',
            'depreciation_against_saleje_credit_acc'
        )
        ->get();


        $bk_fixedassets_with_depreciation_view_je = DB::table('bk_fixedassets_with_depreciation')
        ->leftJoin('bk_fixedassets', 'bk_fixedassets_with_depreciation.fixed_assets_id', '=', 'bk_fixedassets.id')
        ->leftJoin('chart_of_accounts as debit_account', 'bk_fixedassets_with_depreciation.depreciation_je_debit_acc', '=', 'debit_account.id')
        ->leftJoin('chart_of_accounts as credit_account', 'bk_fixedassets_with_depreciation.depreciation_je_credit_acc', '=', 'credit_account.id')
        ->leftJoin('chart_of_accounts as debit_account_against_saleje', 'bk_fixedassets_with_depreciation.depreciation_against_saleje_debit_acc', '=', 'debit_account_against_saleje.id')
        ->leftJoin('chart_of_accounts as credit_account_against_saleje', 'bk_fixedassets_with_depreciation.depreciation_against_saleje_credit_acc', '=', 'credit_account_against_saleje.id')

        ->leftJoin('bk_sub_chart_of_accounts as subdebitacc', function($join) {
            $join->on('bk_fixedassets_with_depreciation.depreciation_je_debit_acc', '=', 'subdebitacc.id');
                
        })
        ->leftJoin('bk_sub_chart_of_accounts as subcreditacc', function($join) {
            $join->on('bk_fixedassets_with_depreciation.depreciation_je_credit_acc', '=', 'subcreditacc.id');
               
        })
        ->leftJoin('bk_sub_chart_of_accounts as subdebit_account_against_saleje', function($join) {
            $join->on('bk_fixedassets_with_depreciation.depreciation_against_saleje_debit_acc', '=', 'subdebit_account_against_saleje.id');
                
        })
        ->leftJoin('bk_sub_chart_of_accounts as subcredit_account_against_saleje', function($join) {
            $join->on('bk_fixedassets_with_depreciation.depreciation_against_saleje_credit_acc', '=', 'subcredit_account_against_saleje.id');
                
        })

        ->where('bk_fixedassets_with_depreciation.fixed_assets_id', $fixed_asset_id)
        ->select(
            'bk_fixedassets_with_depreciation.id',
            'bk_fixedassets_with_depreciation.depreciation_method',
            'bk_fixedassets_with_depreciation.residual_value',
            'bk_fixedassets_with_depreciation.asset_life_yrs',
            'bk_fixedassets_with_depreciation.depreciation_start_date',
            'bk_fixedassets_with_depreciation.depreciation_rate_per_annum',
            'bk_fixedassets_with_depreciation.accumulated_depreciation',
            'bk_fixedassets_with_depreciation.depreciation_je_debit_acc',
            'bk_fixedassets_with_depreciation.depreciation_je_credit_acc',
            'bk_fixedassets_with_depreciation.depreciation_against_saleje_debit_acc',
            'bk_fixedassets_with_depreciation.depreciation_against_saleje_credit_acc',
            'bk_fixedassets.remarks',
            // 'debit_account.account_name as debit_account_name',
            // 'credit_account.account_name as credit_account_name',

            // 'debit_account_against_saleje.account_name as debit_account_against_saleje',
            // 'credit_account_against_saleje.account_name as credit_account_against_saleje'
            DB::raw('CASE 
            WHEN bk_fixedassets_with_depreciation.depreciation_je_debit_acc IS NULL THEN NULL
            WHEN subdebitacc.id IS NOT NULL THEN subdebitacc.sub_account_name 
            ELSE debit_account.account_name 
            END AS debit_account_name'),
            
            DB::raw('CASE 
                WHEN bk_fixedassets_with_depreciation.depreciation_je_credit_acc IS NULL THEN NULL
                WHEN subcreditacc.id IS NOT NULL THEN subcreditacc.sub_account_name 
                ELSE credit_account.account_name 
            END AS credit_account_name'),
            
            DB::raw('CASE 
                WHEN bk_fixedassets_with_depreciation.depreciation_against_saleje_debit_acc IS NULL THEN NULL
                WHEN subdebit_account_against_saleje.id IS NOT NULL THEN subdebit_account_against_saleje.sub_account_name 
                ELSE debit_account_against_saleje.account_name 
            END AS debit_account_against_saleje'),

            DB::raw('CASE 
                WHEN bk_fixedassets_with_depreciation.depreciation_against_saleje_credit_acc IS NULL THEN NULL
                WHEN subcredit_account_against_saleje.id IS NOT NULL THEN subcredit_account_against_saleje.sub_account_name 
                ELSE credit_account_against_saleje.account_name 
            END AS credit_account_against_saleje'),

        // 'bk_classifiedsetup.classid',
        // 'bk_classifiedsetup.debitaccid',
        // 'bk_classifiedsetup.creditaccid',
        // 'bk_classifiedsetup.payment_debitaccid'
        )
        ->groupBy('bk_fixedassets_with_depreciation.id')
        ->get();

        $accounts = DB::table('chart_of_accounts')
        ->where('deleted',0)
        ->get();

        $bk_sub_chart_of_accounts = DB::table('bk_sub_chart_of_accounts')
        ->where('deleted',0)
        ->get();

        // $chart_of_accounts = DB::table('chart_of_accounts')
        // ->get();

        return response()->json([
            'bk_fixedassets' => $bk_fixedassets,
            'bk_fixedassets_with_depreciation' => $bk_fixedassets_with_depreciation,
            'bk_fixedassets_with_depreciation_view_je' => $bk_fixedassets_with_depreciation_view_je,
            'accounts' => $accounts,
            'sub_chart_of_accounts' => $bk_sub_chart_of_accounts
        ]);
    }


    public static function updatefixed_asset(Request $request)
    {
        // Retrieve and validate inputs
        $fixed_asset_id_edit = $request->input('fixed_asset_id_edit');
       
        $depreciationOption = $request->input('depreciationOption');
        $fixed_asset_code_edit = $request->input('fixed_asset_code_edit');
        $fixed_asset_name_edit = $request->input('fixed_asset_name_edit');
        $fixed_asset_purchased_date_edit = $request->input('fixed_asset_purchased_date_edit');
        $fixed_asset_value_edit = $request->input('fixed_asset_value_edit');

        $fixed_asset_warranty_edit = $request->input('fixed_asset_warranty_edit');
        $fixed_asset_serial_number_edit = $request->input('fixed_asset_serial_number_edit');
        $fixed_asset_serial_warranty_edit = $request->input('fixed_asset_serial_warranty_edit');
        $fixed_asset_remarks_edit = $request->input('fixed_asset_remarks_edit');

        $fixed_asset_depreciation_method_edit = $request->input('fixed_asset_depreciation_method_edit');
        $fixed_asset_residual_value_edit = $request->input('fixed_asset_residual_value_edit');
        $fixed_asset_useful_life_edit = $request->input('fixed_asset_useful_life_edit');
        $fixed_asset_depreciation_start_date_edit = $request->input('fixed_asset_depreciation_start_date_edit');
        $fixed_asset_depreciation_rate_per_annum_edit = $request->input('fixed_asset_depreciation_rate_per_annum_edit');
        $fixed_asset_accumulated_depreciation_edit = $request->input('fixed_asset_accumulated_depreciation_edit');

        $fixed_asset_depreciation_je_debit_account_edit = $request->input('fixed_asset_depreciation_je_debit_account_edit');
        $fixed_asset_depreciation_je_credit_account_edit = $request->input('fixed_asset_depreciation_je_credit_account_edit');
        $fixed_asset_loss_on_sale_je_debit_account_edit = $request->input('fixed_asset_loss_on_sale_je_debit_account_edit');
        $fixed_asset_loss_on_sale_je_credit_account_edit = $request->input('fixed_asset_loss_on_sale_je_credit_account_edit');
      

        DB::table('bk_fixedassets')->where('id', $fixed_asset_id_edit)->update([
            'depreciation_option' => $depreciationOption,
            'code' => $fixed_asset_code_edit,
            'asset_name' => $fixed_asset_name_edit,
            'purchased_date' => $fixed_asset_purchased_date_edit,
            'asset_value' => $fixed_asset_value_edit,
            'warranty' => $fixed_asset_warranty_edit,
            'serial_number' => $fixed_asset_serial_number_edit,
            'serial_warranty' => $fixed_asset_serial_warranty_edit,
            'remarks' => $fixed_asset_remarks_edit
        ]);

        DB::table('bk_fixedassets_with_depreciation')->where('fixed_assets_id', $fixed_asset_id_edit)->update([
            'depreciation_method' => $fixed_asset_depreciation_method_edit,
            'residual_value' => $fixed_asset_residual_value_edit,
            'asset_life_yrs' => $fixed_asset_useful_life_edit,
            'depreciation_start_date' => $fixed_asset_depreciation_start_date_edit,
            'depreciation_rate_per_annum' => $fixed_asset_depreciation_rate_per_annum_edit,
            'accumulated_depreciation' => $fixed_asset_accumulated_depreciation_edit,
            'depreciation_je_debit_acc' => $fixed_asset_depreciation_je_debit_account_edit,
            'depreciation_je_credit_acc' => $fixed_asset_depreciation_je_credit_account_edit,
            'depreciation_against_saleje_debit_acc' => $fixed_asset_loss_on_sale_je_debit_account_edit,
            'depreciation_against_saleje_credit_acc' => $fixed_asset_loss_on_sale_je_credit_account_edit
        ]);

        
        return response()->json([
            ['status' => 1, 'message' => 'Fixed Asset Updated Successfully']
        ]);
    }


    public static function deletefixed_asset(Request $request)
    {
        // Retrieve and validate inputs
        $deletefixedassetId = $request->input('deletefixedassetId');

            DB::table('bk_fixedassets')->where('id', $deletefixedassetId)->update([
            'deleted' => 1,
            'deleted_by' => auth()->user()->id,
            'deleted_date_time' => now(),
        ]);
      

        return response()->json([
            ['status' => 1, 'message' => 'Fixed Asset Deleted Successfully']
        ]);
    }

    // public static function storecashierJE(Request $request)
    // {
    //     // Retrieve and validate inputs
    //     $debit_account = $request->input('debit_account');

    //     $lastVoucher = DB::table('bk_generalledg')
    //         ->where('voucherNo', 'like', 'JV - %')
    //         ->orderByDesc('id')
    //         ->first();

    //     if ($lastVoucher) {
    //         preg_match('/\d+$/', $lastVoucher->voucherNo, $matches);
    //         $lastNumber = isset($matches[0]) ? (int) $matches[0] : 0;
    //         $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    //     } else {
    //         $newNumber = '0001';
    //     }

    //     $voucherNo = 'JV - ' . $newNumber;

    //         DB::table('bk_generalledg')->insert([
    //         'coaid' => $debit_account,
    //         'voucherNo' => $voucherNo,
    //         'date' => now(),
    //         'createdby' => auth()->user()->id,
    //         'createddatetime' => now(),
    //     ]);


    //     return response()->json([
    //         ['status' => 1, 'message' => 'Cashier JE Created Successfully']
    //     ]);
    // }


    public static function store_disbursement(Request $request)
    {
        $expenses_date = $request->input('expenses_date');
        $expenses_voucher_no = $request->input('expenses_voucher_no');
        $expenses_ref_no = $request->input('expenses_ref_no');
        $expenses_disbursement_to = $request->input('expenses_disbursement_to');
        $expenses_department = $request->input('expenses_department');
        $payment_type = $request->input('payment_type');

        $expenses_amount = $request->input('expenses_amount');
        $bank = $request->input('bank');
        $cheque_no = $request->input('cheque_no');
        $cheque_date = $request->input('cheque_date');
        $expenses_remarks = $request->input('expenses_remarks');

        $entries = $request->input('entries');

        $grand_total_amount = (float)str_replace(',', '', $request->input('grand_total_amount'));

        $expensesItem = $request->input('expensesItem');
        // $expensesItems = json_decode($request->expensesItem, true);

        if (empty($expenses_ref_no)) {
            return response()->json([
                ['status' => 2, 'message' => 'Expenses Reference Number is required']
            ]);
        }

        if (empty($expenses_disbursement_to)) {
            return response()->json([
                ['status' => 2, 'message' => 'Disburse To is required']
            ]);
        }

        if (empty($expenses_date)) {
            return response()->json([
                ['status' => 2, 'message' => 'Date is required']
            ]);
        }

        if (empty($expenses_remarks)) {
            return response()->json([
                ['status' => 2, 'message' => 'Remarks is required']
            ]);
        }

        if (substr($expenses_ref_no, 0, 2) != 'PO') {
            $checkReferenceNo = DB::table('bk_disbursements')
                ->where('reference_no', $expenses_ref_no)
                ->where('deleted', 0)
                ->first();

            if (!empty($checkReferenceNo)) {
                return response()->json([
                    ['status' => 2, 'message' => 'Expenses Reference Number is already existing']
                ]);
            }
        }

        // $checkReferenceNo = DB::table('bk_disbursements')
        //     ->where('reference_no', $expenses_ref_no)
        //     ->where('deleted', 0)
        //     ->first();

        // if (!empty($checkReferenceNo)) {
        //     return response()->json([
        //         ['status' => 2, 'message' => 'Expenses Reference Number is already existing']
        //     ]);
        // }

        $active_fiscal_year_id = DB::table('bk_fiscal_year')
        ->where('deleted', 0)
        ->where('isactive', 1)
        ->value('id');

        if (empty($entries)) {
            return response()->json([
                ['status' => 2, 'message' => 'Journal Entries required']
            ]);
        }
        $voucherNumber = self::generateVoucherNumberJE();
        foreach ($entries as $entry) {
            DB::table('bk_generalledg')->insert([
                'voucherNo' => $voucherNumber,
                'bookkeeper_transvoucher' => $expenses_voucher_no,
                'active_fiscal_year_id' => $active_fiscal_year_id,
                'date' => $expenses_date,
                'coaid' => $entry['account'],
                'debit_amount' => $entry['debit'],
                'credit_amount' => $entry['credit'],
                'remarks' => $expenses_remarks,
                'createddatetime' => now('Asia/Manila'),
                'createdby' => auth()->id()
            ]);
        }

        /////////////////////////

        $id = DB::table('bk_disbursements')->insertGetId([
            'date' => $expenses_date,
            'voucher_no' => $expenses_voucher_no,
            'reference_no' => $expenses_ref_no,
            'disburse_to' => $expenses_disbursement_to,
            'company_department' => $expenses_department,
            'payment_type' => $payment_type,
            'amount' => $expenses_amount,
            'bank' => $bank,
            'cheque_no' => $cheque_no,
            'cheque_date' => $cheque_date,
            'remarks' => $expenses_remarks,
            // 'amount' => $grand_total_amount,
            'created_at' => now(),
        ]);



        if (empty($expensesItem)) {
            return response()->json([
                ['status' => 2, 'message' => 'Items required']
            ]);
        }

        // if (empty($expensesItem)) {
        //     return response()->json([
        //         ['status' => 1, 'message' => 'Disbursements Created Successfully']
        //     ]);
        // }

        foreach ($expensesItem as $entry) {

            DB::table('bk_disbursement_items')->insert([
                'headerid' => $id,
                'itemid' => $entry['itemId'],
                'amount' => $entry['itemAmount'],
                'qty' => $entry['itemQuantity'],
                // 'totalamount' => $entry['itemTotal'],
                'created_at' => now(),
            ]);
        }
    



        return response()->json([
            ['status' => 1, 'message' => 'Disbursements Created Successfully']
        ]);
    }

    public function fetch_disbursement()
    {
        $disbursements = DB::table('bk_disbursements')
            ->where('bk_disbursements.deleted', 0)
            ->leftjoin('teacher', 'bk_disbursements.disburse_to', '=', 'teacher.id')
            ->leftjoin('hr_departments', 'bk_disbursements.company_department', '=', 'hr_departments.id')
            ->select(
                'bk_disbursements.id',
                'bk_disbursements.voucher_no',
                // DB::raw("CONCAT(teacher.firstname, ' ', teacher.lastname) as disburse_to"),
                'teacher.firstname',
                'teacher.lastname',
                'hr_departments.department as company_department',
                'bk_disbursements.remarks',
                'bk_disbursements.amount',
                'bk_disbursements.date'
            )
            ->get();
    
        return response()->json($disbursements);
    }

    // public static function edit_disbursement(Request $request){

    //     $expense_monitoring_id = $request->get('expense_monitoring_id');

    //     $expense_monitoring = DB::table('bk_disbursements')
    //     ->where('id',$expense_monitoring_id)
    //     ->where('deleted',0)
    //     ->select(
    //         'id',
    //         'disbursement_type',
    //         'date',
    //         'voucher_no',
    //         'reference_no',
    //         'student_no',
    //         'refund_to',
    //         'grade_level',
    //         'disburse_to',
    //         'company_department',
    //         'payment_type',
    //         'bank',
    //         'cheque_no',
    //         'cheque_date',
    //         'amount',
    //         'remarks'
    //     )
    //     ->get();

    //     $expense_monitoring_items = DB::table('bk_disbursement_items')
    //     ->join('bk_expenses_items', 'bk_disbursement_items.itemid', '=', 'bk_expenses_items.id')
    //     ->where('bk_disbursement_items.headerid',$expense_monitoring_id)
    //     ->where('bk_disbursement_items.deleted',0)
    //     ->select(
    //         'bk_disbursement_items.id',
    //         'bk_disbursement_items.itemid',
    //         'bk_disbursement_items.headerid',
    //         'bk_disbursement_items.amount',
    //         'bk_disbursement_items.qty',
    //         'bk_disbursement_items.totalamount',
    //         'bk_expenses_items.description'
    //     )
    //     ->get();

       

    //     return response()->json([
    //         'expense_monitoring' => $expense_monitoring,
    //         'expense_monitoring_items' => $expense_monitoring_items
    //     ]);
    // }

    
    // public static function edit_disbursement(Request $request){

    //     $expense_monitoring_id = $request->get('expense_monitoring_id');

    //     $expense_monitoring = DB::table('bk_disbursements')
    //     ->where('id',$expense_monitoring_id)
    //     ->where('deleted',0)
    //     ->select(
    //         'id',
    //         'disbursement_type',
    //         'date',
    //         'voucher_no',
    //         'reference_no',
    //         'student_no',
    //         'refund_to',
    //         'grade_level',
    //         'disburse_to',
    //         'company_department',
    //         'payment_type',
    //         'bank',
    //         'cheque_no',
    //         'cheque_date',
    //         'amount',
    //         'remarks'
    //     )
    //     ->get();

    //     $voucherNo = $expense_monitoring[0]->voucher_no;

    //     $bk_general_ledger = DB::table('bk_generalledg')
    //     ->leftjoin('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //     ->leftjoin('bk_sub_chart_of_accounts', 'bk_generalledg.coaid', '=', 'bk_sub_chart_of_accounts.id')
    //     ->where('bk_generalledg.voucherNo',$voucherNo)
    //     ->where('bk_generalledg.deleted',0)
    //     ->select(
    //         'bk_generalledg.id',
    //         'bk_generalledg.voucherNo',
    //         'bk_generalledg.coaid',
    //         DB::raw('CASE 
    //             WHEN bk_generalledg.coaid IS NULL THEN NULL
    //             WHEN bk_sub_chart_of_accounts.id IS NOT NULL THEN bk_sub_chart_of_accounts.sub_account_name
    //             ELSE chart_of_accounts.account_name
    //         END AS account_name'),
    //         'bk_generalledg.debit_amount',
    //         'bk_generalledg.credit_amount'
    //     )
    //     ->get();

    //     $bk_general_ledger = DB::table('bk_generalledg')
    //     ->get();



    //     $expense_monitoring_items = DB::table('bk_disbursement_items')
    //     ->join('bk_expenses_items', 'bk_disbursement_items.itemid', '=', 'bk_expenses_items.id')
    //     ->where('bk_disbursement_items.headerid',$expense_monitoring_id)
    //     ->where('bk_disbursement_items.deleted',0)
    //     ->select(
    //         'bk_disbursement_items.id',
    //         'bk_disbursement_items.itemid',
    //         'bk_disbursement_items.headerid',
    //         'bk_disbursement_items.amount',
    //         'bk_disbursement_items.qty',
    //         'bk_disbursement_items.totalamount',
    //         'bk_expenses_items.description'
    //     )
    //     ->get();

       

    //     return response()->json([
    //         'expense_monitoring' => $expense_monitoring,
    //         'expense_monitoring_items' => $expense_monitoring_items,
    //         'bkdisbursements_general_ledger' => $bk_general_ledger
    //     ]);
    // }

    public static function edit_disbursement(Request $request){

        $expense_monitoring_id = $request->get('expense_monitoring_id');

        $expense_monitoring = DB::table('bk_disbursements')
        ->where('id',$expense_monitoring_id)
        ->where('deleted',0)
        ->select(
            'id',
            'disbursement_type',
            'date',
            'voucher_no',
            'reference_no',
            'student_no',
            'refund_to',
            'grade_level',
            'disburse_to',
            'company_department',
            'payment_type',
            'bank',
            'cheque_no',
            'cheque_date',
            'amount',
            'remarks'
        )
        ->get();

        $voucherNo = $expense_monitoring[0]->voucher_no;

        $bk_general_ledger = DB::table('bk_generalledg')
        ->leftjoin('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
        ->leftjoin('bk_sub_chart_of_accounts', 'bk_generalledg.coaid', '=', 'bk_sub_chart_of_accounts.id')
        ->where('bk_generalledg.bookkeeper_transvoucher',$voucherNo)
        ->where('bk_generalledg.deleted',0)
        ->select(
            'bk_generalledg.id',
            'bk_generalledg.voucherNo',
            'bk_generalledg.coaid',
            DB::raw('CASE 
                WHEN bk_generalledg.coaid IS NULL THEN NULL
                WHEN bk_sub_chart_of_accounts.id IS NOT NULL THEN bk_sub_chart_of_accounts.sub_account_name
                ELSE chart_of_accounts.account_name
            END AS account_name'),
            'bk_generalledg.debit_amount',
            'bk_generalledg.credit_amount'
        )
        ->get();

        $chart_of_accounts = DB::table('chart_of_accounts')
        ->get();

        $bk_sub_chart_of_accounts = DB::table('bk_sub_chart_of_accounts')
        ->get();



        $expense_monitoring_items = DB::table('bk_disbursement_items')
        ->join('bk_expenses_items', 'bk_disbursement_items.itemid', '=', 'bk_expenses_items.id')
        ->where('bk_disbursement_items.headerid',$expense_monitoring_id)
        ->where('bk_disbursement_items.deleted',0)
        ->select(
            'bk_disbursement_items.id',
            'bk_disbursement_items.itemid',
            'bk_disbursement_items.headerid',
            'bk_disbursement_items.amount',
            'bk_disbursement_items.qty',
            'bk_disbursement_items.totalamount',
            'bk_expenses_items.description'
        )
        ->get();

       

        return response()->json([
            'expense_monitoring' => $expense_monitoring,
            'expense_monitoring_items' => $expense_monitoring_items,
            'bkdisbursements_general_ledger' => $bk_general_ledger,
            'chart_of_accounts' => $chart_of_accounts,
            'sub_chart_of_accounts' => $bk_sub_chart_of_accounts
        ]);
    }


    public static function update_selected_disbursement_items(Request $request)
    {
        // Retrieve and validate inputs
        $id_edit_item_selected = $request->input('id_edit_item_selected');
       
        $itemName_edit_selected = $request->input('itemName_edit_selected');
        $amount_edit_selected = $request->input('amount_edit_selected');
        $qty_edit_selected = $request->input('qty_edit_selected');
        $totalAmount_edit_selected = $request->input('totalAmount_edit_selected');
      
            DB::table('bk_disbursement_items')->where('id', $id_edit_item_selected)->update([
            'itemid' => $itemName_edit_selected,
            'amount' => $amount_edit_selected,
            'qty' => $qty_edit_selected,
            // 'totalamount' => $totalAmount_edit_selected
        ]);
        
        return response()->json([
            ['status' => 1, 'message' => 'Disbursement Items Updated Successfully']
        ]);
    }

    public static function edit_selected_disbursement_items(Request $request){

        $selected_expenses_item_id = $request->get('selected_expenses_item_id');

        $expense_monitoring_items = DB::table('bk_disbursement_items')
        ->join('bk_expenses_items', 'bk_disbursement_items.itemid', '=', 'bk_expenses_items.id')
        ->where('bk_disbursement_items.id',$selected_expenses_item_id)
        ->where('bk_disbursement_items.deleted',0)
        ->select(
            'bk_disbursement_items.id',
            'bk_disbursement_items.itemid',
            'bk_disbursement_items.headerid',
            'bk_disbursement_items.amount',
            'bk_disbursement_items.qty',
            'bk_disbursement_items.totalamount',
            'bk_expenses_items.description'
        )
        ->get();

        $expense_items = DB::table('bk_expenses_items')
        ->where('deleted',0)
        ->get();


        return response()->json([
            'expense_monitoring_items' => $expense_monitoring_items,
            'expense_items' => $expense_items
        ]);
    }

    public static function update_disbursement(Request $request)
    {
        // Retrieve and validate inputs
        $expenses_items = $request->input('expenses_items');
        $expenseID_edit = $request->input('expenseID_edit');
        $expenseDate_edit = $request->input('expenseDate_edit');
        $voucherNo_edit = $request->input('voucherNo_edit');
        $refNo_edit = $request->input('refNo_edit');
        $disburse_to_edit = $request->input('disburse_to_edit');
        $department_edit = $request->input('department_edit');
        $paymentType_Edit = $request->input('paymentType_Edit');

        $amount_edit = $request->input('amount_edit');
        $bank_edit = $request->input('bank_edit');
        $chequeNo_edit = $request->input('chequeNo_edit');
        $chequeDate_edit = $request->input('chequeDate_edit');
        $remarks_edit = $request->input('remarks_edit');

        $entries = $request->input('entries');
        
        // Check if expenseDate_edit is provided
        if (empty($expenseDate_edit)) {
            return response()->json([
                ['status' => 2, 'message' => 'Expenses Date is required']
            ]);
        }

        try {
            DB::table('bk_disbursements')
                ->where('id', $expenseID_edit)
                ->update([
                    'date' => $expenseDate_edit,
                    'voucher_no' => $voucherNo_edit,
                    'reference_no' => $refNo_edit,
                    'disburse_to' => $disburse_to_edit,
                    'company_department' => $department_edit,
                    'payment_type' => $paymentType_Edit,
                    'amount' => $amount_edit,
                    'bank' => $bank_edit,
                    'cheque_no' => $chequeNo_edit,
                    'cheque_date' => $chequeDate_edit,
                    'remarks' => $remarks_edit,
                    'updated_at' => now()
                ]);

            foreach ($expenses_items as $entry) {
                if ($entry['expensesid'] == 0) {
                    DB::table('bk_disbursement_items')->insert([
                        'headerid' => $expenseID_edit,
                        'itemid' => $entry['expenses_item_id'],
                        'amount' => $entry['expenses_amount'],
                        'qty' => $entry['expenses_quantity'],
                        // 'totalamount' => $entry['expenses_total'],
                        'created_at' => now()
                    ]);
                    
                }
             
            }
            $active_fiscal_year_id = DB::table('bk_fiscal_year')
            ->where('deleted', 0)
            ->where('isactive', 1)
            ->value('id');

            foreach ($entries as $entry) {
                if ($entry['expensesid'] == 0) {
                    DB::table('bk_generalledg')->insert([
                        'voucherNo' => $voucherNo_edit,
                        'active_fiscal_year_id' => $active_fiscal_year_id,
                        'date' => $expenseDate_edit,
                        'coaid' => $entry['account'],
                        'debit_amount' => $entry['debit'],
                        'credit_amount' => $entry['credit'],
                        'remarks' => $remarks_edit,
                        'createddatetime' => now('Asia/Manila'),
                        'createdby' => auth()->id()
                    ]);
                } else {
                    DB::table('bk_generalledg')
                        ->where('id', $entry['expensesid'])
                        ->update([
                            'voucherNo' => $voucherNo_edit,
                            'active_fiscal_year_id' => $active_fiscal_year_id,
                            'date' => $expenseDate_edit,
                            'coaid' => $entry['account'],
                            'debit_amount' => $entry['debit'],
                            'credit_amount' => $entry['credit'],
                            'remarks' => $remarks_edit,
                            'updateddatetime' => now('Asia/Manila'),
                            'updatedby' => auth()->id()
                        ]);
                }
             
            }

            return response()->json([
                ['status' => 1, 'message' => 'Disbursements Updated Successfully']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                ['status' => 3, 'message' => 'An error occurred while updating: ' . $e->getMessage()]
            ]);
        }
    }

    public static function delete_disbursement(Request $request)
    {
        // Retrieve and validate inputs
        $deletedisbursementId = $request->input('deletedisbursementId');

            DB::table('bk_disbursements')->where('id', $deletedisbursementId)->update([
            'deleted' => 1,
            'deleted_by' => auth()->user()->id,
            'deleted_date_time' => now(),
        ]);
      

        return response()->json([
            ['status' => 1, 'message' => 'Selected disbursement deleted successfully']
        ]);
    }

    public static function delete_selected_disbursement_items(Request $request)
    {
        // Retrieve and validate inputs
        $deletedisburse_itemId = $request->input('deletedisburse_itemId');

            DB::table('bk_disbursement_items')->where('id', $deletedisburse_itemId)->update([
            'deleted' => 1,
            // 'deletedby' => auth()->user()->id
        ]);
      

        return response()->json([
            ['status' => 1, 'message' => 'Selected Disbursement Item Deleted Successfully']
        ]);
    }


    // public function fetchstock_history()
    // {
    //     $PO = DB::table('receiving_details')
    //         ->where('receiving_details.receivedqty', '>', 0)
    //         ->where('receiving_details.deleted', 0)
    //         ->join('receiving', 'receiving_details.headerid', '=', 'receiving.id')
    //         ->join('bk_expenses_items', 'receiving_details.itemid', '=', 'bk_expenses_items.id')
           
    //         ->select(
    //             'receiving_details.id',
    //             'receiving_details.itemid',
    //             'receiving_details.receivedqty',
    //             'receiving.posteddatetime',
    //             'receiving.invoiceno',
    //             'receiving.remarks',
    //             'bk_expenses_items.description',
    //             'bk_expenses_items.itemcode'
    //         )
    //         ->get();
    
    //     return response()->json($PO);
    // }

    public function fetchstock_history()
    {
        $PO = DB::table('bk_receiving_details_history')
            ->where('bk_receiving_details_history.receivedqty', '>', 0)
            ->where('bk_receiving_details_history.deleted', 0)
            ->join('bk_receiving_history', 'bk_receiving_details_history.headerid', '=', 'bk_receiving_history.id')
            ->join('bk_expenses_items', 'bk_receiving_details_history.itemid', '=', 'bk_expenses_items.id')
            ->leftjoin('bk_item_assignment', 'bk_receiving_details_history.assign_itemId', '=', 'bk_item_assignment.id')
            ->leftjoin('hr_departments', 'bk_item_assignment.item_assign_department', '=', 'hr_departments.id')
           
            ->select(
                'bk_receiving_details_history.id',
                'bk_receiving_details_history.itemid',
                'bk_receiving_details_history.receivedqty',
                'bk_receiving_history.posteddatetime',
                'bk_receiving_history.invoiceno',
                'bk_receiving_history.remarks',
                'bk_receiving_history.isReceived',
                'bk_expenses_items.description',
                'bk_expenses_items.itemcode',
                'bk_item_assignment.item_assign_employee',
                'bk_item_assignment.item_assign_department',
                'hr_departments.department'
            )
            ->orderByRaw("DATE_FORMAT(bk_receiving_history.posteddatetime, '%Y-%m-%d %H:%i:%s') desc")
            ->get();
    
        return response()->json($PO);
    }



    public function print_expenses_monitoring(Request $request)
    {
        $query = DB::table('expense')
            ->join('teacher', 'expense.requestedbyid', '=', 'teacher.id')
            ->join('hr_departments', 'expense.departmentid', '=', 'hr_departments.id')
            ->select(
                'expense.*',
                // 'bk_fiscal_year.description as fiscal_desc',
                'teacher.firstname',
                'teacher.lastname',
                'hr_departments.department'
            );
        
        // if (!empty($request->date_range)) {
        //     $dates = explode(' - ', $request->date_range);
        //     if (count($dates) == 2) {
        //         $startDate = trim($dates[0]);
        //         $endDate = trim($dates[1]);
        //         $query->whereBetween('expense.transdate', [$startDate, $endDate]);
        //     }
        // }
        // return $request->date_range;

        if (!empty($request->date_range)) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) == 2) {
                $startDate = trim($dates[0]) . ' 00:00:00';
                $endDate = trim($dates[1]) . ' 23:59:59';
                $query->whereBetween('expense.transdate', [$startDate, $endDate]);
            }
        }


        if (!empty($request->employee_val)) {
            $query->where('teacher.firstname', $request->employee_val);
        }

        if (!empty($request->department_val)) {
            $query->where('hr_departments.department', $request->department_val);
        }

        $entries = $query->orderBy('expense.transdate')->get();

        // $fiscal_desc = $entries->first()->fiscal_desc ?? null;

        $schoolinfo = DB::table('schoolinfo')->first();


        // Generate PDF
        $pdf = Pdf::loadView('bookkeeper.pages.printables.ExpensesMonitoring_PDF', [
            'entries' => $entries,
            // 'fiscal_year' => $request->fiscal_year,
            // 'signatories' => $signatories,
            // 'fiscal_desc' => $fiscal_desc,
            'schoolinfo' => $schoolinfo,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('ExpensesMonitoring_' . now()->format('Ymd_His') . '.pdf');
    }


    public function print_disbursements(Request $request)
    {
        $query = DB::table('bk_disbursements')
            // ->join('teacher', 'expense.requestedbyid', '=', 'teacher.id')
            // ->join('hr_departments', 'expense.departmentid', '=', 'hr_departments.id')
            ->where('bk_disbursements.deleted', 0)
            ->select(
                'bk_disbursements.id',
                'bk_disbursements.voucher_no',
                'bk_disbursements.disburse_to',
                'bk_disbursements.company_department',
                'bk_disbursements.remarks',
                'bk_disbursements.amount',
                'bk_disbursements.date'
            );
        
        // if (!empty($request->date_range)) {
        //     $dates = explode(' - ', $request->date_range);
        //     if (count($dates) == 2) {
        //         $startDate = trim($dates[0]);
        //         $endDate = trim($dates[1]);
        //         $query->whereBetween('expense.transdate', [$startDate, $endDate]);
        //     }
        // }

        if (!empty($request->date_range)) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) == 2) {
                $startDate = trim($dates[0]) . ' 00:00:00';
                $endDate = trim($dates[1]) . ' 23:59:59';
                $query->whereBetween('bk_disbursements.date', [$startDate, $endDate]);
            }
        }


        if (!empty($request->employee_val)) {
            $query->where('teacher.firstname', $request->employee_val);
        }

        if (!empty($request->department_val)) {
            $query->where('hr_departments.department', $request->department_val);
        }

        $entries = $query->orderBy('bk_disbursements.date')->get();

        // $fiscal_desc = $entries->first()->fiscal_desc ?? null;

        $schoolinfo = DB::table('schoolinfo')->first();


        // Generate PDF
        $pdf = Pdf::loadView('bookkeeper.pages.printables.Disbursements_PDF', [
            'entries' => $entries,
            // 'fiscal_year' => $request->fiscal_year,
            // 'signatories' => $signatories,
            // 'fiscal_desc' => $fiscal_desc,
            'schoolinfo' => $schoolinfo,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('Disbursements_' . now()->format('Ymd_His') . '.pdf');
    }

    public function print_fixed_assets(Request $request)
    {
     
        $query = DB::table('bk_fixedassets')
            // ->join('bk_fixedassets_with_depreciation', 'bk_fixedassets.id', '=', 'bk_fixedassets_with_depreciation.fixed_assets_id')
            ->leftJoin('bk_fixedassets_with_depreciation', 'bk_fixedassets.id', '=', 'bk_fixedassets_with_depreciation.fixed_assets_id')
            ->where('bk_fixedassets.deleted', 0)
            ->select(
                'bk_fixedassets.id',
                'bk_fixedassets.depreciation_option',
                'bk_fixedassets.code',
                'bk_fixedassets.asset_name',
                'bk_fixedassets.asset_value',
                'bk_fixedassets.purchased_date',
                'bk_fixedassets_with_depreciation.asset_life_yrs',
                'bk_fixedassets_with_depreciation.depreciation_rate_per_annum'
            );
     

        $entries = $query->orderBy('bk_fixedassets.purchased_date')->get();

        // $fiscal_desc = $entries->first()->fiscal_desc ?? null;

        $schoolinfo = DB::table('schoolinfo')->first();


        // Generate PDF
        $pdf = Pdf::loadView('bookkeeper.pages.printables.FixedAssets_PDF', [
            'entries' => $entries,
            // 'fiscal_year' => $request->fiscal_year,
            // 'signatories' => $signatories,
            // 'fiscal_desc' => $fiscal_desc,
            'schoolinfo' => $schoolinfo,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('Disbursements_' . now()->format('Ymd_His') . '.pdf');
    }

   



    // public static function fetch_equity_statement(Request $request){

    //     $fiscal_year = $request->get('fiscal_year');

    //     $query_for_income_statement = DB::table('bk_generalledg')
    //     ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //     ->where('chart_of_accounts.deleted', 0);

    //     if ($fiscal_year) {
    //         $query_for_income_statement->where('bk_generalledg.active_fiscal_year_id', $fiscal_year);
    //     }

    //     $incomeStatement = $query_for_income_statement->select(
    //         'chart_of_accounts.id',
    //         'chart_of_accounts.classification',
    //         'chart_of_accounts.account_name',
    //         'chart_of_accounts.code',
    //         DB::raw('SUM(bk_generalledg.debit_amount - bk_generalledg.credit_amount) as net_amount')
    //     )
    //     ->groupBy(
    //         'chart_of_accounts.id',
    //         'chart_of_accounts.classification',
    //         'chart_of_accounts.account_name',
    //         'chart_of_accounts.code'
    //     )
    //     ->get();


    //     $query_for_beginning_balance = DB::table('bk_generalledg')
    //     ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //     ->where('chart_of_accounts.deleted', 0)
    //     ->where('chart_of_accounts.classification', 'LIKE', 'equity%');

    //     if ($fiscal_year) {
    //         $query_for_beginning_balance->where('bk_generalledg.active_fiscal_year_id', $fiscal_year);
    //     }

    //     $beginningBalance = $query_for_beginning_balance->select(
    //         'chart_of_accounts.id',
    //         'chart_of_accounts.classification',
    //         'chart_of_accounts.account_name',
    //         'chart_of_accounts.code',
    //         DB::raw('SUM(bk_generalledg.debit_amount - bk_generalledg.credit_amount) as net_amount')
    //     )
    //     ->groupBy(
    //         'chart_of_accounts.id',
    //         'chart_of_accounts.classification',
    //         'chart_of_accounts.account_name',
    //         'chart_of_accounts.code'
    //     )
    //     ->get();


    //     $query_for_withdrawals = DB::table('bk_generalledg')
    //     ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //     ->where('chart_of_accounts.deleted', 0)
    //     ->where('chart_of_accounts.account_name', 'LIKE', 'Withdrawal%');

    //     if ($fiscal_year) {
    //         $query_for_withdrawals->where('bk_generalledg.active_fiscal_year_id', $fiscal_year);
    //     }

    //     $withdrawals = $query_for_withdrawals->select(
    //         'chart_of_accounts.id',
    //         'chart_of_accounts.classification',
    //         'chart_of_accounts.account_name',
    //         'chart_of_accounts.code',
    //         DB::raw('SUM(bk_generalledg.debit_amount - bk_generalledg.credit_amount) as net_amount')
    //     )
    //     ->groupBy(
    //         'chart_of_accounts.id',
    //         'chart_of_accounts.classification',
    //         'chart_of_accounts.account_name',
    //         'chart_of_accounts.code'
    //     )
    //     ->get();




    //     return response()->json([
    //         'incomeStatement' => $incomeStatement,
    //         'beginningBalance' => $beginningBalance,
    //         'withdrawals' => $withdrawals
    //     ]);

    // }

    // public static function fetch_equity_statement(Request $request){

    //     $fiscal_year = $request->get('fiscal_year');

    //     $fiscal_db = DB::table('bk_fiscal_year')->where('deleted', 0)->select(
    //         'bk_fiscal_year.description'
    //     )->get();

    //     $previous_fiscal_year = null;

    //     foreach ($fiscal_db as $fiscal) {
    //         if ($fiscal->description == $fiscal_year) {
    //             $previous_fiscal_year = $fiscal_db[$fiscal_db->search(function($item) use ($fiscal) {
    //                 return $item->description < $fiscal->description;
    //             })]->description;
    //             break;
    //         }
    //     }

    //     $query_for_income_statement = DB::table('bk_generalledg')
    //     ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //     ->where('chart_of_accounts.deleted', 0);

    //     if ($fiscal_year) {
    //         $query_for_income_statement->where('bk_generalledg.active_fiscal_year_id', $fiscal_year);
    //     }

    //     $incomeStatement = $query_for_income_statement->select(
    //         'chart_of_accounts.id',
    //         'chart_of_accounts.classification',
    //         'chart_of_accounts.account_name',
    //         'chart_of_accounts.code',
    //         DB::raw('SUM(bk_generalledg.debit_amount - bk_generalledg.credit_amount) as net_amount')
    //     )
    //     ->groupBy(
    //         'chart_of_accounts.id',
    //         'chart_of_accounts.classification',
    //         'chart_of_accounts.account_name',
    //         'chart_of_accounts.code'
    //     )
    //     ->get();


    //     $query_for_beginning_balance = DB::table('bk_generalledg')
    //     ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //     ->where('chart_of_accounts.deleted', 0)
    //     ->where('chart_of_accounts.classification', 'LIKE', 'equity%');

    //     if ($fiscal_year) {
    //         $query_for_beginning_balance->where('bk_generalledg.active_fiscal_year_id', $fiscal_year);
    //     }

    //     $beginningBalance = $query_for_beginning_balance->select(
    //         'chart_of_accounts.id',
    //         'chart_of_accounts.classification',
    //         'chart_of_accounts.account_name',
    //         'chart_of_accounts.code',
    //         DB::raw('SUM(bk_generalledg.debit_amount - bk_generalledg.credit_amount) as net_amount')
    //     )
    //     ->groupBy(
    //         'chart_of_accounts.id',
    //         'chart_of_accounts.classification',
    //         'chart_of_accounts.account_name',
    //         'chart_of_accounts.code'
    //     )
    //     ->get();


    //     $query_for_withdrawals = DB::table('bk_generalledg')
    //     ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //     ->where('chart_of_accounts.deleted', 0)
    //     ->where('chart_of_accounts.account_name', 'LIKE', 'Withdrawal%');

    //     if ($fiscal_year) {
    //         $query_for_withdrawals->where('bk_generalledg.active_fiscal_year_id', $fiscal_year);
    //     }

    //     $withdrawals = $query_for_withdrawals->select(
    //         'chart_of_accounts.id',
    //         'chart_of_accounts.classification',
    //         'chart_of_accounts.account_name',
    //         'chart_of_accounts.code',
    //         DB::raw('SUM(bk_generalledg.debit_amount - bk_generalledg.credit_amount) as net_amount')
    //     )
    //     ->groupBy(
    //         'chart_of_accounts.id',
    //         'chart_of_accounts.classification',
    //         'chart_of_accounts.account_name',
    //         'chart_of_accounts.code'
    //     )
    //     ->get();




    //     return response()->json([
    //         'incomeStatement' => $incomeStatement,
    //         'beginningBalance' => $beginningBalance,
    //         'withdrawals' => $withdrawals
    //     ]);

    // }

    // public static function fetch_equity_statement(Request $request) {
    //     $fiscal_year = $request->get('fiscal_year');
    //     $fiscalYearText = $request->input('fiscalYearText');
    
    //     $fiscal_db = DB::table('bk_fiscal_year')->where('deleted', 0)
    //         ->select('bk_fiscal_year.description')
    //         ->orderBy('description', 'asc')
    //         ->get();
    
    //     $previous_fiscal_year = null;
    
    //     foreach ($fiscal_db as $index => $fiscal) {
    //         if ($fiscal->description == $fiscalYearText && $index > 0) {
    //             $previous_fiscal_year = $fiscal_db[$index - 1]->description;
    //             break;
    //         }
    //     }
    
    //     // Function to get data for a specific fiscal year
    //     $getFiscalData = function($year) {
    //         $incomeStatement = DB::table('bk_generalledg')
    //             ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //             ->where('chart_of_accounts.deleted', 0)
    //             ->when($year, function($query) use ($year) {
    //                 return $query->where('bk_generalledg.active_fiscal_year_id', $year);
    //             })
    //             ->select(
    //                 'chart_of_accounts.id',
    //                 'chart_of_accounts.classification',
    //                 'chart_of_accounts.account_name',
    //                 'chart_of_accounts.code',
    //                 DB::raw('SUM(bk_generalledg.debit_amount - bk_generalledg.credit_amount) as net_amount')
    //             )
    //             ->groupBy(
    //                 'chart_of_accounts.id',
    //                 'chart_of_accounts.classification',
    //                 'chart_of_accounts.account_name',
    //                 'chart_of_accounts.code'
    //             )
    //             ->get();
    
    //         $beginningBalance = DB::table('bk_generalledg')
    //             ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //             ->where('chart_of_accounts.deleted', 0)
    //             ->where('chart_of_accounts.classification', 'LIKE', 'equity%')
    //             ->when($year, function($query) use ($year) {
    //                 return $query->where('bk_generalledg.active_fiscal_year_id', $year);
    //             })
    //             ->select(
    //                 'chart_of_accounts.id',
    //                 'chart_of_accounts.classification',
    //                 'chart_of_accounts.account_name',
    //                 'chart_of_accounts.code',
    //                 DB::raw('SUM(bk_generalledg.debit_amount - bk_generalledg.credit_amount) as net_amount')
    //             )
    //             ->groupBy(
    //                 'chart_of_accounts.id',
    //                 'chart_of_accounts.classification',
    //                 'chart_of_accounts.account_name',
    //                 'chart_of_accounts.code'
    //             )
    //             ->get();
    
    //         $withdrawals = DB::table('bk_generalledg')
    //             ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //             ->where('chart_of_accounts.deleted', 0)
    //             ->where('chart_of_accounts.account_name', 'LIKE', 'Withdrawal%')
    //             ->when($year, function($query) use ($year) {
    //                 return $query->where('bk_generalledg.active_fiscal_year_id', $year);
    //             })
    //             ->select(
    //                 'chart_of_accounts.id',
    //                 'chart_of_accounts.classification',
    //                 'chart_of_accounts.account_name',
    //                 'chart_of_accounts.code',
    //                 DB::raw('SUM(bk_generalledg.debit_amount - bk_generalledg.credit_amount) as net_amount')
    //             )
    //             ->groupBy(
    //                 'chart_of_accounts.id',
    //                 'chart_of_accounts.classification',
    //                 'chart_of_accounts.account_name',
    //                 'chart_of_accounts.code'
    //             )
    //             ->get();
    
    //         return [
    //             'incomeStatement' => $incomeStatement,
    //             'beginningBalance' => $beginningBalance,
    //             'withdrawals' => $withdrawals
    //         ];
    //     };
    
    //     $currentYearData = $getFiscalData($fiscal_year);
    //     $previousYearData = $previous_fiscal_year ? $getFiscalData($previous_fiscal_year) : null;
    
    //     return response()->json([
    //         'currentYear' => [
    //             'incomeStatement' => $currentYearData['incomeStatement'],
    //             'beginningBalance' => $currentYearData['beginningBalance'],
    //             'withdrawals' => $currentYearData['withdrawals']
    //         ],
    //         'previousYear' => $previousYearData ? [
    //             'incomeStatement' => $previousYearData['incomeStatement'],
    //             'beginningBalance' => $previousYearData['beginningBalance'],
    //             'withdrawals' => $previousYearData['withdrawals']
    //         ] : null
    //     ]);
    // }

    // working code
    // public function fetch_cashflow_statement(Request $request){
    //     try {
    //         $fiscal_year = $request->get('fiscal_year');


    //         $result = DB::table('chart_of_accounts')
    //         ->where('chart_of_accounts.deleted', 0)
    //         ->leftJoin('bk_generalledg', function ($join) use ($fiscal_year) {
    //             $join->on('chart_of_accounts.id', '=', 'bk_generalledg.coaid')
    //                 ->where('bk_generalledg.active_fiscal_year_id', $fiscal_year);
    //         })
    //         ->select(
    //             'bk_generalledg.debit_amount',
    //             'bk_generalledg.credit_amount',
    //             'bk_generalledg.date',
    //             'chart_of_accounts.id',
    //             'classification',
    //             'code',
    //             'account_name',
    //             'account_type',
    //             'financial_statement as fs',
    //             'normal_balance',
    //             'cashflow_statement'
    //         )
    //         ->get()
    //         ->groupBy('cashflow_statement');

    //         if (!empty($request->date_range)) {
    //             $dates = explode(' - ', $request->date_range);
    //             if (count($dates) == 2) {
    //                 $startDate = trim($dates[0]) . ' 00:00:00';
    //                 $endDate = trim($dates[1]) . ' 23:59:59';
    //                 $result->whereBetween('bk_generalledg.date', [$startDate, $endDate]);
    //             }
    //         }
        

    //         if ($result) {
    //             return response()->json(['success' => true, 'data' => $result]);
    //         }else{
    //             return response()->json(['success' => false, 'message' => 'Something went wrong!'], 400);
    //         }
    //     } catch (\Throwable $th) {
    //         //throw $th;
    //         return response()->json(['success' => false, 'message' => $th->getMessage()], 400);
    //     }
    // }

    // public function fetch_cashflow_statement(Request $request) {
    //     try {
    //         $fiscal_year = $request->get('fiscal_year');
    //         $result = DB::table('chart_of_accounts')
    //             ->where('chart_of_accounts.deleted', 0)
    //             ->leftJoin('bk_generalledg', function ($join) use ($fiscal_year) {
    //                 $join->on('chart_of_accounts.id', '=', 'bk_generalledg.coaid')
    //                     ->where('bk_generalledg.active_fiscal_year_id', $fiscal_year);
    //             })
    //             ->select(
    //                 'bk_generalledg.debit_amount',
    //                 'bk_generalledg.credit_amount',
    //                 'bk_generalledg.date',
    //                 'chart_of_accounts.id',
    //                 'classification',
    //                 'code',
    //                 'account_name',
    //                 'account_type',
    //                 'financial_statement as fs',
    //                 'normal_balance',
    //                 'cashflow_statement'
    //             );

    //         if (!empty($request->date_range)) {
    //             $dates = explode(' - ', $request->date_range);
    //             if (count($dates) == 2) {
    //                 $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dates[0]))->startOfDay();
    //                 $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dates[1]))->endOfDay();
    //                 $result->whereBetween('bk_generalledg.date', [$startDate, $endDate]);
    //             }
    //         } 
    //         else {
    //             $result->where('bk_generalledg.active_fiscal_year_id', $fiscal_year);
    //         }

    //         $result = $result->get()->groupBy('cashflow_statement');

    //         if ($result) {
    //             return response()->json(['success' => true, 'data' => $result]);
    //         } else {
    //             return response()->json(['success' => false, 'message' => 'Something went wrong!'], 400);
    //         }
    //     } catch (\Throwable $th) {
    //         return response()->json(['success' => false, 'message' => $th->getMessage()], 400);
    //     }
    // }

    public function fetch_cashflow_statement(Request $request) {
        try {
            $fiscal_year = $request->get('fiscal_year');
            $result = DB::table('chart_of_accounts')
                ->where('chart_of_accounts.deleted', 0)
                ->leftJoin('bk_sub_chart_of_accounts', 'chart_of_accounts.id', '=', 'bk_sub_chart_of_accounts.coaid')
                ->leftJoin('bk_generalledg', function ($join) use ($fiscal_year) {
                    $join->on(function($query) {
                        $query->on('chart_of_accounts.id', '=', 'bk_generalledg.coaid')
                              ->whereNull('bk_sub_chart_of_accounts.id')
                              ->orOn('bk_sub_chart_of_accounts.id', '=', 'bk_generalledg.coaid');
                    })
                    ->where('bk_generalledg.active_fiscal_year_id', $fiscal_year);
                })
                ->select(
                    'bk_generalledg.debit_amount',
                    'bk_generalledg.credit_amount',
                    'bk_generalledg.date',
                    DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.id ELSE bk_sub_chart_of_accounts.id END AS id'),
                    DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.account_name ELSE bk_sub_chart_of_accounts.sub_account_name END AS account_name'),
                    DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.code ELSE bk_sub_chart_of_accounts.sub_code END AS code'),
                    'classification',
                    'account_type',
                    'financial_statement as fs',
                    'normal_balance',
                    'cashflow_statement'
                );
    
            if (!empty($request->date_range)) {
                $dates = explode(' - ', $request->date_range);
                if (count($dates) == 2) {
                    $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dates[0]))->startOfDay();
                    $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dates[1]))->endOfDay();
                    $result->whereBetween('bk_generalledg.date', [$startDate, $endDate]);
                }
            } 
            else {
                $result->where('bk_generalledg.active_fiscal_year_id', $fiscal_year);
            }
    
            $result = $result->get()->groupBy('cashflow_statement');
    
            if ($result) {
                return response()->json(['success' => true, 'data' => $result]);
            } else {
                return response()->json(['success' => false, 'message' => 'Something went wrong!'], 400);
            }
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 400);
        }
    }

   
    // public static function fetch_equity_statement(Request $request) {
    //     $fiscal_year = $request->get('fiscal_year');
    //     $fiscalYearText = $request->input('fiscalYearText');
        
    //     // Get all fiscal years ordered
    //     $fiscal_db = DB::table('bk_fiscal_year')->where('deleted', 0)
    //         ->select('id', 'description')
    //         ->orderBy('description', 'asc')
    //         ->get();
        
    //     $previous_fiscal_year = null;
    //     $current_fiscal_id = null;
        
    //     // Find current fiscal year ID and previous year
    //     foreach ($fiscal_db as $index => $fiscal) {
    //         if ($fiscal->description == $fiscalYearText) {
    //             $current_fiscal_id = $fiscal->id;
    //             if ($index > 0) {
    //                 $previous_fiscal_year = $fiscal_db[$index - 1]->id;
    //             }
    //             break;
    //         }
    //     }
        
    //     // If we didn't find by description, try by ID
    //     if (!$current_fiscal_id && $fiscal_year) {
    //         foreach ($fiscal_db as $index => $fiscal) {
    //             if ($fiscal->id == $fiscal_year) {
    //                 $current_fiscal_id = $fiscal->id;
    //                 if ($index > 0) {
    //                     $previous_fiscal_year = $fiscal_db[$index - 1]->id;
    //                 }
    //                 break;
    //             }
    //         }
    //     }
        
    //     // Function to get data for a specific fiscal year ID
    //     $getFiscalData = function($year_id) use ($request) {
    //         $queryBuilder = function($query) use ($year_id, $request) {
    //             if (!empty($request->date_range)) {
    //                 $dates = explode(' - ', $request->date_range);
    //                 if (count($dates) == 2) {
    //                     $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dates[0]))->startOfDay();
    //                     $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dates[1]))->endOfDay();
    //                     $query->whereBetween('bk_generalledg.date', [$startDate, $endDate]);
    //                 }
    //             } else {
    //                 $query->where('bk_generalledg.active_fiscal_year_id', $year_id);
    //             }
    //             return $query;
    //         };
            
    //         $incomeStatement = DB::table('bk_generalledg')
    //             ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //             ->where('chart_of_accounts.deleted', 0)
    //             ->when($year_id, $queryBuilder)
    //             ->select(
    //                 'chart_of_accounts.id',
    //                 'chart_of_accounts.classification',
    //                 'chart_of_accounts.account_name',
    //                 'chart_of_accounts.code',
    //                 DB::raw('SUM(bk_generalledg.debit_amount - bk_generalledg.credit_amount) as net_amount')
    //             )
    //             ->groupBy(
    //                 'chart_of_accounts.id',
    //                 'chart_of_accounts.classification',
    //                 'chart_of_accounts.account_name',
    //                 'chart_of_accounts.code'
    //             )
    //             ->get();
        
    //         $beginningBalance = DB::table('bk_generalledg')
    //             ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //             ->where('chart_of_accounts.deleted', 0)
    //             ->where('chart_of_accounts.classification', 'LIKE', 'equity%')
    //             ->when($year_id, $queryBuilder)
    //             ->select(
    //                 'chart_of_accounts.id',
    //                 'chart_of_accounts.classification',
    //                 'chart_of_accounts.account_name',
    //                 'chart_of_accounts.code',
    //                 DB::raw('SUM(bk_generalledg.debit_amount - bk_generalledg.credit_amount) as net_amount')
    //             )
    //             ->groupBy(
    //                 'chart_of_accounts.id',
    //                 'chart_of_accounts.classification',
    //                 'chart_of_accounts.account_name',
    //                 'chart_of_accounts.code'
    //             )
    //             ->get();
        
    //         $withdrawals = DB::table('bk_generalledg')
    //             ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //             ->where('chart_of_accounts.deleted', 0)
    //             ->where('chart_of_accounts.account_name', 'LIKE', 'Withdrawal%')
    //             ->when($year_id, $queryBuilder)
    //             ->select(
    //                 'chart_of_accounts.id',
    //                 'chart_of_accounts.classification',
    //                 'chart_of_accounts.account_name',
    //                 'chart_of_accounts.code',
    //                 DB::raw('SUM(bk_generalledg.debit_amount - bk_generalledg.credit_amount) as net_amount')
    //             )
    //             ->groupBy(
    //                 'chart_of_accounts.id',
    //                 'chart_of_accounts.classification',
    //                 'chart_of_accounts.account_name',
    //                 'chart_of_accounts.code'
    //             )
    //             ->get();
        
    //         return [
    //             'incomeStatement' => $incomeStatement,
    //             'beginningBalance' => $beginningBalance,
    //             'withdrawals' => $withdrawals
    //         ];
    //     };
        
    //     $currentYearData = $getFiscalData($current_fiscal_id ?? $fiscal_year);
    //     $previousYearData = $previous_fiscal_year ? $getFiscalData($previous_fiscal_year) : null;
        
    //     return response()->json([
    //         'currentYear' => [
    //             'incomeStatement' => $currentYearData['incomeStatement'],
    //             'beginningBalance' => $currentYearData['beginningBalance'],
    //             'withdrawals' => $currentYearData['withdrawals']
    //         ],
    //         'previousYear' => $previousYearData ? [
    //             'incomeStatement' => $previousYearData['incomeStatement'],
    //             'beginningBalance' => $previousYearData['beginningBalance'],
    //             'withdrawals' => $previousYearData['withdrawals']
    //         ] : null
    //     ]);
    // }

    public static function fetch_equity_statement(Request $request) {
        $fiscal_year = $request->get('fiscal_year');
        $fiscalYearText = $request->input('fiscalYearText');
        
        // Get all fiscal years ordered
        $fiscalYears = DB::table('bk_fiscal_year')
            ->where('deleted', 0)
            ->select('id', 'description')
            ->orderBy('description', 'asc')
            ->get();
        
        $currentFiscalId = null;
        $previousFiscalId = null;
        
        // Find current fiscal year ID and previous year
        foreach ($fiscalYears as $index => $fiscal) {
            if ($fiscal->description === $fiscalYearText || $fiscal->id == $fiscal_year) {
                $currentFiscalId = $fiscal->id;
                if ($index > 0) {
                    $previousFiscalId = $fiscalYears[$index - 1]->id;
                }
                break;
            }
        }
        
        // Function to get data for a specific fiscal year ID
        $getFiscalData = function($yearId) use ($request) {
            $queryBuilder = function($query) use ($yearId, $request) {
                if (!empty($request->date_range)) {
                    $dates = explode(' - ', $request->date_range);
                    if (count($dates) === 2) {
                        $startDate = Carbon::createFromFormat('m/d/Y', trim($dates[0]))->startOfDay();
                        $endDate = Carbon::createFromFormat('m/d/Y', trim($dates[1]))->endOfDay();
                        $query->whereBetween('bk_generalledg.date', [$startDate, $endDate]);
                    }
                } else {
                    $query->where('bk_generalledg.active_fiscal_year_id', $yearId);
                }
                return $query;
            };
            
            $commonSelect = [
                DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.id ELSE bk_sub_chart_of_accounts.id END AS id'),
                'chart_of_accounts.classification',
                DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.account_name ELSE bk_sub_chart_of_accounts.sub_account_name END AS account_name'),
                DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.code ELSE bk_sub_chart_of_accounts.sub_code END AS code'),
                DB::raw('SUM(bk_generalledg.debit_amount - bk_generalledg.credit_amount) as net_amount')
            ];
            
            $commonGroupBy = [
                'bk_generalledg.coaid',
                'chart_of_accounts.classification',
                DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.account_name ELSE bk_sub_chart_of_accounts.sub_account_name END'),
                DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.code ELSE bk_sub_chart_of_accounts.sub_code END')
            ];
            
            $incomeStatement = DB::table('bk_generalledg')
                ->leftJoin('bk_sub_chart_of_accounts', 'bk_generalledg.coaid', '=', 'bk_sub_chart_of_accounts.id')
                ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
                ->where('chart_of_accounts.deleted', 0)
                ->when($yearId, $queryBuilder)
                ->select($commonSelect)
                ->groupBy($commonGroupBy)
                ->get();
        
            $beginningBalance = DB::table('bk_generalledg')
                ->leftJoin('bk_sub_chart_of_accounts', 'bk_generalledg.coaid', '=', 'bk_sub_chart_of_accounts.id')
                ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
                ->where('chart_of_accounts.deleted', 0)
                ->where('chart_of_accounts.classification', 'LIKE', 'equity%')
                ->when($yearId, $queryBuilder)
                ->select($commonSelect)
                ->groupBy($commonGroupBy)
                ->get();
        
            $withdrawals = DB::table('bk_generalledg')
                ->leftJoin('bk_sub_chart_of_accounts', 'bk_generalledg.coaid', '=', 'bk_sub_chart_of_accounts.id')
                ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
                ->where('chart_of_accounts.deleted', 0)
                ->where('chart_of_accounts.account_name', 'LIKE', 'Withdrawal%')
                ->when($yearId, $queryBuilder)
                ->select($commonSelect)
                ->groupBy($commonGroupBy)
                ->get();
        
            return [
                'incomeStatement' => $incomeStatement,
                'beginningBalance' => $beginningBalance,
                'withdrawals' => $withdrawals
            ];
        };
        
        $currentYearData = $getFiscalData($currentFiscalId ?? $fiscal_year);
        $previousYearData = $previousFiscalId ? $getFiscalData($previousFiscalId) : null;
        
        return response()->json([
            'currentYear' => $currentYearData,
            'previousYear' => $previousYearData
        ]);
    }

    // working code
    // public static function fetch_equity_statement(Request $request) {
    //     $fiscal_year = $request->get('fiscal_year');
    //     $fiscalYearText = $request->input('fiscalYearText');
        
    //     // Get all fiscal years ordered
    //     $fiscal_db = DB::table('bk_fiscal_year')->where('deleted', 0)
    //         ->select('id', 'description')
    //         ->orderBy('description', 'asc')
    //         ->get();
        
    //     $previous_fiscal_year = null;
    //     $current_fiscal_id = null;
        
    //     // Find current fiscal year ID and previous year
    //     foreach ($fiscal_db as $index => $fiscal) {
    //         if ($fiscal->description == $fiscalYearText) {
    //             $current_fiscal_id = $fiscal->id;
    //             if ($index > 0) {
    //                 $previous_fiscal_year = $fiscal_db[$index - 1]->id;
    //             }
    //             break;
    //         }
    //     }
        
    //     // If we didn't find by description, try by ID
    //     if (!$current_fiscal_id && $fiscal_year) {
    //         foreach ($fiscal_db as $index => $fiscal) {
    //             if ($fiscal->id == $fiscal_year) {
    //                 $current_fiscal_id = $fiscal->id;
    //                 if ($index > 0) {
    //                     $previous_fiscal_year = $fiscal_db[$index - 1]->id;
    //                 }
    //                 break;
    //             }
    //         }
    //     }
        
    //     // Function to get data for a specific fiscal year ID
    //     $getFiscalData = function($year_id) {
    //         $incomeStatement = DB::table('bk_generalledg')
    //             ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //             ->where('chart_of_accounts.deleted', 0)
    //             ->when($year_id, function($query) use ($year_id) {
    //                 return $query->where('bk_generalledg.active_fiscal_year_id', $year_id);
    //             })
    //             ->select(
    //                 'chart_of_accounts.id',
    //                 'chart_of_accounts.classification',
    //                 'chart_of_accounts.account_name',
    //                 'chart_of_accounts.code',
    //                 DB::raw('SUM(bk_generalledg.debit_amount - bk_generalledg.credit_amount) as net_amount')
    //             )
    //             ->groupBy(
    //                 'chart_of_accounts.id',
    //                 'chart_of_accounts.classification',
    //                 'chart_of_accounts.account_name',
    //                 'chart_of_accounts.code'
    //             )
    //             ->get();
        
    //         $beginningBalance = DB::table('bk_generalledg')
    //             ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //             ->where('chart_of_accounts.deleted', 0)
    //             ->where('chart_of_accounts.classification', 'LIKE', 'equity%')
    //             ->when($year_id, function($query) use ($year_id) {
    //                 return $query->where('bk_generalledg.active_fiscal_year_id', $year_id);
    //             })
    //             ->select(
    //                 'chart_of_accounts.id',
    //                 'chart_of_accounts.classification',
    //                 'chart_of_accounts.account_name',
    //                 'chart_of_accounts.code',
    //                 DB::raw('SUM(bk_generalledg.debit_amount - bk_generalledg.credit_amount) as net_amount')
    //             )
    //             ->groupBy(
    //                 'chart_of_accounts.id',
    //                 'chart_of_accounts.classification',
    //                 'chart_of_accounts.account_name',
    //                 'chart_of_accounts.code'
    //             )
    //             ->get();
        
    //         $withdrawals = DB::table('bk_generalledg')
    //             ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //             ->where('chart_of_accounts.deleted', 0)
    //             ->where('chart_of_accounts.account_name', 'LIKE', 'Withdrawal%')
    //             ->when($year_id, function($query) use ($year_id) {
    //                 return $query->where('bk_generalledg.active_fiscal_year_id', $year_id);
    //             })
    //             ->select(
    //                 'chart_of_accounts.id',
    //                 'chart_of_accounts.classification',
    //                 'chart_of_accounts.account_name',
    //                 'chart_of_accounts.code',
    //                 DB::raw('SUM(bk_generalledg.debit_amount - bk_generalledg.credit_amount) as net_amount')
    //             )
    //             ->groupBy(
    //                 'chart_of_accounts.id',
    //                 'chart_of_accounts.classification',
    //                 'chart_of_accounts.account_name',
    //                 'chart_of_accounts.code'
    //             )
    //             ->get();
        
    //         return [
    //             'incomeStatement' => $incomeStatement,
    //             'beginningBalance' => $beginningBalance,
    //             'withdrawals' => $withdrawals
    //         ];
    //     };
        
    //     $currentYearData = $getFiscalData($current_fiscal_id ?? $fiscal_year);
    //     $previousYearData = $previous_fiscal_year ? $getFiscalData($previous_fiscal_year) : null;
        
    //     return response()->json([
    //         'currentYear' => [
    //             'incomeStatement' => $currentYearData['incomeStatement'],
    //             'beginningBalance' => $currentYearData['beginningBalance'],
    //             'withdrawals' => $currentYearData['withdrawals']
    //         ],
    //         'previousYear' => $previousYearData ? [
    //             'incomeStatement' => $previousYearData['incomeStatement'],
    //             'beginningBalance' => $previousYearData['beginningBalance'],
    //             'withdrawals' => $previousYearData['withdrawals']
    //         ] : null
    //     ]);
    // }

    // public static function fetch_equity_statement(Request $request) {
    //     $fiscal_year = $request->get('fiscal_year');
    //     $fiscalYearText = $request->input('fiscalYearText');
        
    //     // Get all fiscal years ordered
    //     $fiscal_db = DB::table('bk_fiscal_year')->where('deleted', 0)
    //         ->select('id', 'description')
    //         ->orderBy('description', 'asc')
    //         ->get();
        
    //     $previous_fiscal_year = null;
    //     $current_fiscal_id = null;
        
    //     // Find current fiscal year ID and previous year
    //     foreach ($fiscal_db as $index => $fiscal) {
    //         if ($fiscal->description == $fiscalYearText) {
    //             $current_fiscal_id = $fiscal->id;
    //             if ($index > 0) {
    //                 $previous_fiscal_year = $fiscal_db[$index - 1]->id;
    //             }
    //             break;
    //         }
    //     }
        
    //     // If we didn't find by description, try by ID
    //     if (!$current_fiscal_id && $fiscal_year) {
    //         foreach ($fiscal_db as $index => $fiscal) {
    //             if ($fiscal->id == $fiscal_year) {
    //                 $current_fiscal_id = $fiscal->id;
    //                 if ($index > 0) {
    //                     $previous_fiscal_year = $fiscal_db[$index - 1]->id;
    //                 }
    //                 break;
    //             }
    //         }
    //     }
        
    //     // Function to get data for a specific fiscal year ID
    //     $getFiscalData = function($year_id) {
    //         $incomeStatement = DB::table('bk_generalledg')
    //             ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //             ->where('chart_of_accounts.deleted', 0)
    //             ->when($year_id, function($query) use ($year_id) {
    //                 return $query->where('bk_generalledg.active_fiscal_year_id', $year_id);
    //             })
    //             ->select(
    //                 'chart_of_accounts.id',
    //                 'chart_of_accounts.classification',
    //                 'chart_of_accounts.account_name',
    //                 'chart_of_accounts.code',
    //                 DB::raw('SUM(bk_generalledg.debit_amount - bk_generalledg.credit_amount) as net_amount')
    //             )
    //             ->groupBy(
    //                 'chart_of_accounts.id',
    //                 'chart_of_accounts.classification',
    //                 'chart_of_accounts.account_name',
    //                 'chart_of_accounts.code'
    //             )
    //             ->get();
        
    //         $beginningBalance = DB::table('bk_generalledg')
    //             ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //             ->where('chart_of_accounts.deleted', 0)
    //             ->where('chart_of_accounts.classification', 'LIKE', 'equity%')
    //             ->when($year_id, function($query) use ($year_id) {
    //                 return $query->where('bk_generalledg.active_fiscal_year_id', $year_id);
    //             })
    //             ->select(
    //                 'chart_of_accounts.id',
    //                 'chart_of_accounts.classification',
    //                 'chart_of_accounts.account_name',
    //                 'chart_of_accounts.code',
    //                 DB::raw('SUM(bk_generalledg.debit_amount - bk_generalledg.credit_amount) as net_amount')
    //             )
    //             ->groupBy(
    //                 'chart_of_accounts.id',
    //                 'chart_of_accounts.classification',
    //                 'chart_of_accounts.account_name',
    //                 'chart_of_accounts.code'
    //             )
    //             ->get();
        
    //         $withdrawals = DB::table('bk_generalledg')
    //             ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //             ->where('chart_of_accounts.deleted', 0)
    //             ->where('chart_of_accounts.account_name', 'LIKE', 'Withdrawal%')
    //             ->when($year_id, function($query) use ($year_id) {
    //                 return $query->where('bk_generalledg.active_fiscal_year_id', $year_id);
    //             })
    //             ->select(
    //                 'chart_of_accounts.id',
    //                 'chart_of_accounts.classification',
    //                 'chart_of_accounts.account_name',
    //                 'chart_of_accounts.code',
    //                 DB::raw('SUM(bk_generalledg.debit_amount - bk_generalledg.credit_amount) as net_amount')
    //             )
    //             ->groupBy(
    //                 'chart_of_accounts.id',
    //                 'chart_of_accounts.classification',
    //                 'chart_of_accounts.account_name',
    //                 'chart_of_accounts.code'
    //             )
    //             ->get();
        
    //         return [
    //             'incomeStatement' => $incomeStatement,
    //             'beginningBalance' => $beginningBalance,
    //             'withdrawals' => $withdrawals
    //         ];
    //     };
        
    //     $currentYearData = $getFiscalData($current_fiscal_id ?? $fiscal_year);
    //     $previousYearData = $previous_fiscal_year ? $getFiscalData($previous_fiscal_year) : null;
        
    //     return response()->json([
    //         'currentYear' => [
    //             'incomeStatement' => $currentYearData['incomeStatement'],
    //             'beginningBalance' => $currentYearData['beginningBalance'],
    //             'withdrawals' => $currentYearData['withdrawals']
    //         ],
    //         'previousYear' => $previousYearData ? [
    //             'incomeStatement' => $previousYearData['incomeStatement'],
    //             'beginningBalance' => $previousYearData['beginningBalance'],
    //             'withdrawals' => $previousYearData['withdrawals']
    //         ] : null
    //     ]);
    // }

    public function print_cashflow_statement(Request $request)
    {
        $fiscal_year = $request->get('fiscal_year');


        $result = DB::table('chart_of_accounts')
        ->where('chart_of_accounts.deleted', 0)
        ->leftJoin('bk_sub_chart_of_accounts', 'chart_of_accounts.id', '=', 'bk_sub_chart_of_accounts.coaid')
        ->leftJoin('bk_generalledg', function ($join) use ($fiscal_year) {
            $join->on(function($query) {
                $query->on('chart_of_accounts.id', '=', 'bk_generalledg.coaid')
                      ->whereNull('bk_sub_chart_of_accounts.id')
                      ->orOn('bk_sub_chart_of_accounts.id', '=', 'bk_generalledg.coaid');
            })
            ->where('bk_generalledg.active_fiscal_year_id', $fiscal_year);
        })
        ->select(
            'bk_generalledg.debit_amount',
            'bk_generalledg.credit_amount',
            'bk_generalledg.date',
            DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.id ELSE bk_sub_chart_of_accounts.id END AS id'),
            DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.account_name ELSE bk_sub_chart_of_accounts.sub_account_name END AS account_name'),
            DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.code ELSE bk_sub_chart_of_accounts.sub_code END AS code'),
            'classification',
            'account_type',
            'financial_statement as fs',
            'normal_balance',
            'cashflow_statement'
        );

        if (!empty($request->date_range)) {
            $dates = explode(' - ', $request->date_range);
            if (count($dates) == 2) {
                $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dates[0]))->startOfDay();
                $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dates[1]))->endOfDay();
                $result->whereBetween('bk_generalledg.date', [$startDate, $endDate]);
            }
        } 
        else {
            $result->where('bk_generalledg.active_fiscal_year_id', $fiscal_year);
        }

        $result = $result->get()->groupBy('cashflow_statement');

        $schoolinfo = DB::table('schoolinfo')->first();



        // Generate PDF
        $pdf = Pdf::loadView('bookkeeper.pages.printables.CashflowStatement_PDF', [
            'entries' => $result,
            // 'fiscal_year' => $request->fiscal_year,
            // 'signatories' => $signatories,
            // 'fiscal_desc' => $fiscal_desc,
            'schoolinfo' => $schoolinfo,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream('CashflowStatement_' . now()->format('Ymd_His') . '.pdf');
    }

    // public function print_equity_statement(Request $request)
    // {
    //     $fiscal_year = $request->get('fiscal_year');
    //     $fiscalYearText = $request->input('fiscalYearText');
        
    //     // Get all fiscal years ordered
    //     $fiscal_db = DB::table('bk_fiscal_year')->where('deleted', 0)
    //         ->select('id', 'description')
    //         ->orderBy('description', 'asc')
    //         ->get();
        
    //     $previous_fiscal_year = null;
    //     $current_fiscal_id = null;
        
    //     // Find current fiscal year ID and previous year
    //     foreach ($fiscal_db as $index => $fiscal) {
    //         if ($fiscal->description == $fiscalYearText) {
    //             $current_fiscal_id = $fiscal->id;
    //             if ($index > 0) {
    //                 $previous_fiscal_year = $fiscal_db[$index - 1]->id;
    //             }
    //             break;
    //         }
    //     }
        
    //     // If we didn't find by description, try by ID
    //     if (!$current_fiscal_id && $fiscal_year) {
    //         foreach ($fiscal_db as $index => $fiscal) {
    //             if ($fiscal->id == $fiscal_year) {
    //                 $current_fiscal_id = $fiscal->id;
    //                 if ($index > 0) {
    //                     $previous_fiscal_year = $fiscal_db[$index - 1]->id;
    //                 }
    //                 break;
    //             }
    //         }
    //     }
        
    //     // Function to get data for a specific fiscal year ID
    //     $getFiscalData = function($year_id) use ($request) {
    //         $queryBuilder = function($query) use ($year_id, $request) {
    //             if (!empty($request->date_range)) {
    //                 $dates = explode(' - ', $request->date_range);
    //                 if (count($dates) == 2) {
    //                     $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dates[0]))->startOfDay();
    //                     $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dates[1]))->endOfDay();
    //                     $query->whereBetween('bk_generalledg.date', [$startDate, $endDate]);
    //                 }
    //             } else {
    //                 $query->where('bk_generalledg.active_fiscal_year_id', $year_id);
    //             }
    //             return $query;
    //         };
            
    //         $incomeStatement = DB::table('bk_generalledg')
    //             ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //             ->where('chart_of_accounts.deleted', 0)
    //             ->when($year_id, $queryBuilder)
    //             ->select(
    //                 'chart_of_accounts.id',
    //                 'chart_of_accounts.classification',
    //                 'chart_of_accounts.account_name',
    //                 'chart_of_accounts.code',
    //                 DB::raw('SUM(bk_generalledg.debit_amount - bk_generalledg.credit_amount) as net_amount')
    //             )
    //             ->groupBy(
    //                 'chart_of_accounts.id',
    //                 'chart_of_accounts.classification',
    //                 'chart_of_accounts.account_name',
    //                 'chart_of_accounts.code'
    //             )
    //             ->get();
        
    //         $beginningBalance = DB::table('bk_generalledg')
    //             ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //             ->where('chart_of_accounts.deleted', 0)
    //             ->where('chart_of_accounts.classification', 'LIKE', 'equity%')
    //             ->when($year_id, $queryBuilder)
    //             ->select(
    //                 'chart_of_accounts.id',
    //                 'chart_of_accounts.classification',
    //                 'chart_of_accounts.account_name',
    //                 'chart_of_accounts.code',
    //                 DB::raw('SUM(bk_generalledg.debit_amount - bk_generalledg.credit_amount) as net_amount')
    //             )
    //             ->groupBy(
    //                 'chart_of_accounts.id',
    //                 'chart_of_accounts.classification',
    //                 'chart_of_accounts.account_name',
    //                 'chart_of_accounts.code'
    //             )
    //             ->get();
        
    //         $withdrawals = DB::table('bk_generalledg')
    //             ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //             ->where('chart_of_accounts.deleted', 0)
    //             ->where('chart_of_accounts.account_name', 'LIKE', 'Withdrawal%')
    //             ->when($year_id, $queryBuilder)
    //             ->select(
    //                 'chart_of_accounts.id',
    //                 'chart_of_accounts.classification',
    //                 'chart_of_accounts.account_name',
    //                 'chart_of_accounts.code',
    //                 DB::raw('SUM(bk_generalledg.debit_amount - bk_generalledg.credit_amount) as net_amount')
    //             )
    //             ->groupBy(
    //                 'chart_of_accounts.id',
    //                 'chart_of_accounts.classification',
    //                 'chart_of_accounts.account_name',
    //                 'chart_of_accounts.code'
    //             )
    //             ->get();
        
    //         return [
    //             'incomeStatement' => $incomeStatement,
    //             'beginningBalance' => $beginningBalance,
    //             'withdrawals' => $withdrawals
    //         ];
    //     };
        
    //     $currentYearData = $getFiscalData($current_fiscal_id ?? $fiscal_year);
    //     $previousYearData = $previous_fiscal_year ? $getFiscalData($previous_fiscal_year) : null;
        
    //     $result = [
    //         'currentYear' => [
    //             'incomeStatement' => $currentYearData['incomeStatement'],
    //             'beginningBalance' => $currentYearData['beginningBalance'],
    //             'withdrawals' => $currentYearData['withdrawals']
    //         ],
    //         'previousYear' => $previousYearData ? [
    //             'incomeStatement' => $previousYearData['incomeStatement'],
    //             'beginningBalance' => $previousYearData['beginningBalance'],
    //             'withdrawals' => $previousYearData['withdrawals']
    //         ] : null
    //     ];
    
    //     // Get school info (make sure this is defined)
    //     $schoolinfo = DB::table('schoolinfo')->first();
    
    //     // Generate PDF
    //     $pdf = Pdf::loadView('bookkeeper.pages.printables.EquityStatement_PDF', [
    //         'entries' => $result,
    //         // 'fiscal_year' => $request->fiscal_year,
    //         // 'signatories' => $signatories,
    //         // 'fiscal_desc' => $fiscal_desc,
    //         'schoolinfo' => $schoolinfo,
    //     ])->setPaper('A4', 'portrait');
    
    //     return $pdf->stream('EquityStatement_' . now()->format('Ymd_His') . '.pdf');
    // }

    public function print_equity_statement(Request $request)
    {
        $fiscal_year = $request->get('fiscal_year');
        $fiscalYearText = $request->input('fiscalYearText');
        
        // Get all fiscal years ordered
        $fiscal_db = DB::table('bk_fiscal_year')->where('deleted', 0)
            ->select('id', 'description')
            ->orderBy('description', 'asc')
            ->get();
        
        $previous_fiscal_year = null;
        $current_fiscal_id = null;
        
        // Find current fiscal year ID and previous year
        foreach ($fiscal_db as $index => $fiscal) {
            if ($fiscal->description == $fiscalYearText) {
                $current_fiscal_id = $fiscal->id;
                if ($index > 0) {
                    $previous_fiscal_year = $fiscal_db[$index - 1]->id;
                }
                break;
            }
        }
        
        // If we didn't find by description, try by ID
        if (!$current_fiscal_id && $fiscal_year) {
            foreach ($fiscal_db as $index => $fiscal) {
                if ($fiscal->id == $fiscal_year) {
                    $current_fiscal_id = $fiscal->id;
                    if ($index > 0) {
                        $previous_fiscal_year = $fiscal_db[$index - 1]->id;
                    }
                    break;
                }
            }
        }
        
        // Function to get data for a specific fiscal year ID
        $getFiscalData = function($year_id) use ($request) {
            $queryBuilder = function($query) use ($year_id, $request) {
                if (!empty($request->date_range)) {
                    $dates = explode(' - ', $request->date_range);
                    if (count($dates) == 2) {
                        $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dates[0]))->startOfDay();
                        $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dates[1]))->endOfDay();
                        $query->whereBetween('bk_generalledg.date', [$startDate, $endDate]);
                    }
                } else {
                    $query->where('bk_generalledg.active_fiscal_year_id', $year_id);
                }
                return $query;
            };
            
            $commonSelect = [
                DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.id ELSE bk_sub_chart_of_accounts.id END AS id'),
                'chart_of_accounts.classification',
                DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.account_name ELSE bk_sub_chart_of_accounts.sub_account_name END AS account_name'),
                DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.code ELSE bk_sub_chart_of_accounts.sub_code END AS code'),
                DB::raw('SUM(bk_generalledg.debit_amount - bk_generalledg.credit_amount) as net_amount')
            ];
            
            $commonGroupBy = [
                'bk_generalledg.coaid',
                'chart_of_accounts.classification',
                DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.account_name ELSE bk_sub_chart_of_accounts.sub_account_name END'),
                DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.code ELSE bk_sub_chart_of_accounts.sub_code END')
            ];
            
            $incomeStatement = DB::table('bk_generalledg')
                ->leftJoin('bk_sub_chart_of_accounts', 'bk_generalledg.coaid', '=', 'bk_sub_chart_of_accounts.id')
                ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
                ->where('chart_of_accounts.deleted', 0)
                ->when($year_id, $queryBuilder)
                ->select($commonSelect)
                ->groupBy($commonGroupBy)
                ->get();
        
            $beginningBalance = DB::table('bk_generalledg')
                ->leftJoin('bk_sub_chart_of_accounts', 'bk_generalledg.coaid', '=', 'bk_sub_chart_of_accounts.id')
                ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
                ->where('chart_of_accounts.deleted', 0)
                ->where('chart_of_accounts.classification', 'LIKE', 'equity%')
                ->when($year_id, $queryBuilder)
                ->select($commonSelect)
                ->groupBy($commonGroupBy)
                ->get();
        
            $withdrawals = DB::table('bk_generalledg')
                ->leftJoin('bk_sub_chart_of_accounts', 'bk_generalledg.coaid', '=', 'bk_sub_chart_of_accounts.id')
                ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
                ->where('chart_of_accounts.deleted', 0)
                ->where('chart_of_accounts.account_name', 'LIKE', 'Withdrawal%')
                ->when($year_id, $queryBuilder)
                ->select($commonSelect)
                ->groupBy($commonGroupBy)
                ->get();
        
            return [
                'incomeStatement' => $incomeStatement,
                'beginningBalance' => $beginningBalance,
                'withdrawals' => $withdrawals
            ];
        };
        
        $currentYearData = $getFiscalData($current_fiscal_id ?? $fiscal_year);
        $previousYearData = $previous_fiscal_year ? $getFiscalData($previous_fiscal_year) : null;
        
        $result = [
            'currentYear' => [
                'incomeStatement' => $currentYearData['incomeStatement'],
                'beginningBalance' => $currentYearData['beginningBalance'],
                'withdrawals' => $currentYearData['withdrawals']
            ],
            'previousYear' => $previousYearData ? [
                'incomeStatement' => $previousYearData['incomeStatement'],
                'beginningBalance' => $previousYearData['beginningBalance'],
                'withdrawals' => $previousYearData['withdrawals']
            ] : null
        ];
    
        // Get school info (make sure this is defined)
        $schoolinfo = DB::table('schoolinfo')->first();
    
        // Generate PDF
        $pdf = Pdf::loadView('bookkeeper.pages.printables.EquityStatement_PDF', [
            'entries' => $result,
            // 'fiscal_year' => $request->fiscal_year,
            // 'signatories' => $signatories,
            // 'fiscal_desc' => $fiscal_desc,
            'schoolinfo' => $schoolinfo,
        ])->setPaper('A4', 'portrait');
    
        return $pdf->stream('EquityStatement_' . now()->format('Ymd_His') . '.pdf');
    }


    public function fetchreceived_history()
    {
        $receiving_history = DB::table('bk_receiving_history')
            ->where('bk_receiving_history.deleted', 0)
            ->join('receiving', 'bk_receiving_history.poid', '=', 'receiving.id')
            ->join('purchasing', 'receiving.poid', '=', 'purchasing.id')
            ->join('purchasing_supplier', 'purchasing.supplierid', '=', 'purchasing_supplier.id')
           
            ->select(
                'bk_receiving_history.id',
                'bk_receiving_history.invoiceno',
                'bk_receiving_history.remarks',
                'bk_receiving_history.datedelivered',
                'bk_receiving_history.rstatus',
                'bk_receiving_history.amount',
                'purchasing_supplier.suppliername',
                
            )
            ->get();
    
        return response()->json($receiving_history);
    }

    public static function editReceivinghistory(Request $request){

        $po_id = $request->get('po_id');

        $receiving = DB::table('bk_receiving_history')
        ->join('receiving', 'bk_receiving_history.poid', '=', 'receiving.id')
        ->join('purchasing', 'receiving.poid', '=', 'purchasing.id')
        ->join('purchasing_supplier', 'purchasing.supplierid', '=', 'purchasing_supplier.id')
        ->where('bk_receiving_history.id',$po_id)
        ->where('bk_receiving_history.deleted',0)
        ->select(
            'bk_receiving_history.id',
            'purchasing.refno',
            'purchasing.supplierid',
            // 'purchasing.remarks',
            'purchasing.totalamount',
            'purchasing.postdatetime',
            'bk_receiving_history.invoiceno',
            'bk_receiving_history.rstatus',
            'bk_receiving_history.datedelivered',
            'bk_receiving_history.remarks',
            'purchasing_supplier.suppliername'
        )
        ->get();

       

        $receiving_items = DB::table('bk_receiving_details_history')
        ->join('bk_expenses_items', 'bk_receiving_details_history.itemid', '=', 'bk_expenses_items.id')
        ->where('bk_receiving_details_history.headerid',$po_id)
        ->select(
            'bk_receiving_details_history.id',
            'bk_receiving_details_history.itemid',
            'bk_receiving_details_history.amount',
            'bk_receiving_details_history.qty',
            'bk_receiving_details_history.total',
            'bk_receiving_details_history.receivedqty',
            'bk_receiving_details_history.rtotal',
            'bk_expenses_items.description'
        )
        ->get();

        $voucherNo = $receiving[0]->invoiceno;

        $bk_general_ledger = DB::table('bk_generalledg')
        ->leftjoin('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
        ->leftjoin('bk_sub_chart_of_accounts', 'bk_generalledg.coaid', '=', 'bk_sub_chart_of_accounts.id')
        ->where('bk_generalledg.bookkeeper_transvoucher',$voucherNo)
        ->where('bk_generalledg.deleted',0)
        ->select(
            'bk_generalledg.id',
            'bk_generalledg.voucherNo',
            'bk_generalledg.coaid',
            DB::raw('CASE 
                WHEN bk_generalledg.coaid IS NULL THEN NULL
                WHEN bk_sub_chart_of_accounts.id IS NOT NULL THEN bk_sub_chart_of_accounts.sub_account_name
                ELSE chart_of_accounts.account_name
            END AS account_name'),
            DB::raw('COALESCE(bk_generalledg.debit_amount, 0) as debit_amount'), // Ensure not null
        DB::raw('COALESCE(bk_generalledg.credit_amount, 0) as credit_amount') // Ensure not null
        )
        ->get();

        $chart_of_accounts = DB::table('chart_of_accounts')
        ->get();

        $bk_sub_chart_of_accounts = DB::table('bk_sub_chart_of_accounts')
        ->get();


        $purchasing_supplier = DB::table('purchasing_supplier')
        ->where('deleted',0)
        ->get();

        return response()->json([
            'receiving' => $receiving,
            'receiving_items' => $receiving_items,
            'purchasing_supplier' => $purchasing_supplier,
            'bkdisbursements_general_ledger' => $bk_general_ledger,
            'chart_of_accounts' => $chart_of_accounts,
            'sub_chart_of_accounts' => $bk_sub_chart_of_accounts
        ]);
    }



    // public static function storeSubCOA(Request $request)
    // {
    //     // Retrieve and validate inputs
    //     $sub_coa_id = $request->input('sub_coa_id');
    //     $sub_code = $request->input('sub_code');
    //     $sub_accountName = $request->input('sub_accountName');

    //     DB::table('bk_sub_chart_of_accounts')->insert([
    //         'coaid' => $sub_coa_id,
    //         'sub_code' => $sub_code,
    //         'sub_account_name' => $sub_accountName,
           
    //     ]);

    //     return response()->json([
    //         ['status' => 1, 'message' => 'Sub Account Created Successfully']
    //     ]);
    // }

    // public static function storeSubCOA(Request $request)
    // {
    //     // Retrieve and validate inputs
    //     $sub_coa_id = $request->input('sub_coa_id');
    //     $sub_code = $request->input('sub_code');
    //     $sub_accountName = $request->input('sub_accountName');
    
    //     // Find the next available ID that doesn't exist in chart_of_accounts
    //     $maxId = DB::table('chart_of_accounts')->max('id');
    //     $newId = $maxId + 1; // Start from the next ID after the maximum
    
    //     // Alternatively, query for gaps in the ID sequence
    //     // $existingIds = DB::table('chart_of_accounts')->pluck('id')->toArray();
    //     // Then find the first available ID not in $existingIds
    
    //     DB::table('bk_sub_chart_of_accounts')->insert([
    //         'id' => $newId, // Explicitly set the ID
    //         'coaid' => $sub_coa_id,
    //         'sub_code' => $sub_code,
    //         'sub_account_name' => $sub_accountName,
    //     ]);
    
    //     return response()->json([
    //         ['status' => 1, 'message' => 'Sub Account Created Successfully']
    //     ]);
    // }

    public static function storeSubCOA(Request $request)
    {
        // Retrieve inputs
        $sub_coa_id = $request->input('sub_coa_id');
        $sub_code = $request->input('sub_code');
        $sub_accountName = $request->input('sub_accountName');

        // Get all existing IDs from both tables
        $existingChartIds = DB::table('chart_of_accounts')->pluck('id')->toArray();
        $existingSubChartIds = DB::table('bk_sub_chart_of_accounts')->pluck('id')->toArray();
        $allExistingIds = array_merge($existingChartIds, $existingSubChartIds);

        // Find the first available ID (starting from 1)
        $newId = 1;
        while (in_array($newId, $allExistingIds)) {
            $newId++;
        }

        // Insert the new sub-account
        DB::table('bk_sub_chart_of_accounts')->insert([
            'id' => $newId,
            'coaid' => $sub_coa_id,
            'sub_code' => $sub_code,
            'sub_account_name' => $sub_accountName,
        ]);

        return response()->json([
            ['status' => 1, 'message' => 'Sub Account Created Successfully']
        ]);
    }

    public static function edit_subcoa(Request $request){

        $coa_id = $request->get('coa_id');


        $chart_of_accounts = DB::table('chart_of_accounts')
        ->join('bk_account_type', 'chart_of_accounts.account_type', '=', 'bk_account_type.id')
        ->join('bk_normalbalance_type', 'chart_of_accounts.normal_balance', '=', 'bk_normalbalance_type.id')
        ->join('bk_statement_type', 'chart_of_accounts.financial_statement', '=', 'bk_statement_type.id')
        ->leftjoin('bk_sub_chart_of_accounts', 'chart_of_accounts.id', '=', 'bk_sub_chart_of_accounts.coaid')
        ->where('bk_sub_chart_of_accounts.deleted', 0)
        ->where('bk_sub_chart_of_accounts.id',$coa_id)
        ->select(
            'chart_of_accounts.id',
            'chart_of_accounts.classification',
            'chart_of_accounts.code',
            'chart_of_accounts.account_name',
            'chart_of_accounts.account_type',
            'chart_of_accounts.financial_statement',
            'chart_of_accounts.normal_balance',
            'chart_of_accounts.cashflow_statement',
            'bk_sub_chart_of_accounts.id as sub_coa_id',
            'bk_sub_chart_of_accounts.sub_code',
            'bk_sub_chart_of_accounts.sub_account_name'
        )
        ->get();

        $bk_account_type_all = DB::table('bk_account_type')
        ->get();

        $bk_normalbalance_type_all = DB::table('bk_normalbalance_type')
        ->get();

        $bk_statement_type_all = DB::table('bk_statement_type')
        ->get();

        return response()->json([
            'chart_of_accounts' => $chart_of_accounts,
            'bk_account_type_all' => $bk_account_type_all,
            'bk_normalbalance_type_all' => $bk_normalbalance_type_all,
            'bk_statement_type_all' => $bk_statement_type_all
        ]);
    }


    public static function update_subcoa(Request $request)
    {
        // Retrieve and validate inputs
        $sub_account_edit_edit = $request->input('sub_account_edit_edit');
        $sub_classification_edit_edit = $request->input('sub_classification_edit_edit');
        $sub_code_edit_edit = $request->input('sub_code_edit_edit');
        $sub_account_name_edit_edit = $request->input('sub_account_name_edit_edit');
        $sub_sub_code_edit_edit = $request->input('sub_sub_code_edit_edit');
        $sub_sub_account_name_edit_edit = $request->input('sub_sub_account_name_edit_edit');

            DB::table('bk_sub_chart_of_accounts')->where('id', $sub_account_edit_edit)->update([
            'sub_code' => $sub_sub_code_edit_edit,
            'sub_account_name' => $sub_sub_account_name_edit_edit
        ]);
        
        return response()->json([
            ['status' => 1, 'message' => 'Sub Account Updated Successfully']
        ]);
    }

    public function destroy_subcoa(Request $request){
        try {
            $result = DB::table('bk_sub_chart_of_accounts')
            ->where('bk_sub_chart_of_accounts.id', $request->id)
            ->update([
                'deleted' => 1  
            ]);
            if($result){
                return response()->json(['success'=> true ]);
            }else{
                return response()->json(['success'=> false]);
            }
        } catch (\Throwable $th) {            
            return response()->json(['success'=> false,'message'=> $th->getMessage()], 400);
        }
    }

    public static function fetch_types(Request $request){

        $bk_account_type_all = DB::table('bk_account_type')
        ->where('deleted', 0)
        ->get();

        $bk_normalbalance_type_all = DB::table('bk_normalbalance_type')
        ->where('deleted', 0)
        ->get();

        $bk_statement_type_all = DB::table('bk_statement_type')
        ->where('deleted', 0)
        ->get();

        return response()->json([
            'bk_account_type_all' => $bk_account_type_all,
            'bk_normalbalance_type_all' => $bk_normalbalance_type_all,
            'bk_statement_type_all' => $bk_statement_type_all
        ]);
    }


    // public function filter_acctreceivable(Request $request)
    // {
    //     $selectedschoolyear = $request->get('selectedschoolyear');
    //     $selecteddaterange  = $request->get('selecteddaterange');
    //     $selecteddepartment = $request->get('selecteddepartment');
    //     $selectedgradelevel = $request->get('selectedgradelevel');
    //     $selectedsemester   = $request->get('selectedsemester');
    //     $selectedsection    = $request->get('selectedsection');
    //     $selectedgrantee    = $request->get('selectedgrantee');
    //     $selectedmode       = $request->get('selectedmode'); 
    
    //     $allstudents = AccountsReceivableModel::allstudents($selectedschoolyear,$selecteddaterange,$selecteddepartment,$selectedgradelevel,$selectedsemester,$selectedsection,$selectedgrantee,$selectedmode);
        
    //     $overalltotalassessment     = $allstudents->sum('totalassessment');
    //     $overalltotaldiscount       = $allstudents->sum('discount');
    //     $overalltotalnetassessed    = $allstudents->sum('netassessed');
    //     $overalltotalpayment        = $allstudents->sum('totalpayment');
    //     $overalltotalbalance        = $allstudents->sum('balance');
    
    //     if ($request->ajax()) {
    //         return response()->json([
    //             'overalltotalassessment' => $overalltotalassessment,
    //             'overalltotaldiscount' => $overalltotaldiscount,
    //             'overalltotalnetassessed' => $overalltotalnetassessed,
    //             'overalltotalpayment' => $overalltotalpayment,
    //             'overalltotalbalance' => $overalltotalbalance,
    //             'students' => $allstudents
    //         ]);
    //     }
    
    //     return view('bookkeeper.pages.home')
    //         ->with('students', $allstudents)
    //         ->with('overalltotalassessment', $overalltotalassessment)
    //         ->with('overalltotaldiscount', $overalltotaldiscount)
    //         ->with('overalltotalnetassessed', $overalltotalnetassessed)
    //         ->with('overalltotalpayment', $overalltotalpayment)
    //         ->with('overalltotalbalance', $overalltotalbalance);
    // }

    public function filter_acctreceivable(Request $request)
    {
        $fiscal_year_active = DB::table('bk_fiscal_year')
            ->where('isactive', 1)
            ->where('deleted', 0)
            ->first();

        if ($fiscal_year_active) {
            $records = DB::table('bk_generalledg')
                ->where('active_fiscal_year_id', $fiscal_year_active->id)
                ->where('deleted', 0)
                ->get();

            // STUDENT LEDGER
            $totalassessment = $records->filter(function ($record) {
                    return str_contains($record->remarks, '- LEDGER');
                })->sum(function ($record) {
                    return (float) $record->debit_amount;
                });

            // DISCOUNT
            $totaldiscount = $records->filter(function ($record) {
                    return str_contains($record->remarks, '- DISCOUNT');
                })->sum(function ($record) {
                    return (float) $record->debit_amount;
                }); 
    
            $totaldiscountvoid = $records->filter(function ($record) {
                    return str_contains($record->remarks, '- DISCOUNT Reversal');
                })->sum(function ($record) {
                    return (float) $record->credit_amount;
                });  

            $totaldiscount -= $totaldiscountvoid;

            // PAYMENT
            $totalpayment = $records->filter(function ($record) {
                    return str_contains($record->remarks, '- Payments');
                })->sum(function ($record) {
                    return (float) $record->credit_amount;
                });  
            
            $totalpaymentvoid = $records->filter(function ($record) {
                    return str_contains($record->remarks, '- Reversal');
                })->sum(function ($record) {
                    return (float) $record->credit_amount;
                });  
                
            $totalpayment -= $totalpaymentvoid;

            // ADJUSTMENT
            $totalcreditadjustment = $records->filter(function ($record) {
                    return str_contains($record->remarks, '- Credit ADJUSTMENT');
                })->sum(function ($record) {
                    return (float) $record->credit_amount;
                });  

            $totaldebitadjustment = $records->filter(function ($record) {
                    return str_contains($record->remarks, '- Debit ADJUSTMENT');
                })->sum(function ($record) {
                    return (float) $record->credit_amount;
                });  

            $totalcreditadjustmentvoid = $records->filter(function ($record) {
                    return str_contains($record->remarks, '- Credit Reversal');
                })->sum(function ($record) {
                    return (float) $record->credit_amount;
                });  

            $totaldebitadjustmentvoid = $records->filter(function ($record) {
                    return str_contains($record->remarks, '- Debit Reversal');
                })->sum(function ($record) {
                    return (float) $record->credit_amount;
                });  

            $totalcreditadjustment -= $totalcreditadjustmentvoid;
            $totaldebitadjustment -= $totaldebitadjustmentvoid;

            $overalltotalcreditadjustment = $totalcreditadjustment;
            $overalltotaldebitadjustment = $totaldebitadjustment;
            $overalltotaldiscount = $totaldiscount;
            $overalltotalassessment = $totalassessment + $overalltotaldebitadjustment;
            $overalltotalpayment = $totalpayment + $overalltotalcreditadjustment + $overalltotaldiscount;  
            $overalltotalbalance = $overalltotalassessment - $overalltotalpayment;  
            
        } else {
            $overalltotalassessment     = [];
            $overalltotaldiscount       = [];
            $overalltotalpayment        = [];
            $overalltotalbalance        = [];
        }
        
        if ($request->ajax()) {
            return response()->json([
                'overalltotalassessment' => $overalltotalassessment,
                'overalltotaldiscount' => $overalltotaldiscount,
                'overalltotalpayment' => $overalltotalpayment,
                'overalltotalbalance' => $overalltotalbalance
            ]);
        }
    
        return view('bookkeeper.pages.home')
            ->with('overalltotalassessment', $overalltotalassessment)
            ->with('overalltotaldiscount', $overalltotaldiscount)
            ->with('overalltotalpayment', $overalltotalpayment)
            ->with('overalltotalbalance', $overalltotalbalance);
    }

    public function filter_total_received_history(Request $request)
    {

    
        $all_receiving_items = DB::table('bk_receiving_history')
            ->where('deleted', 0)
            ->sum('amount');
        
        $overalltotalreceived_history = $all_receiving_items;

          // ->where('bk_generalledg.debit_amount' - 'bk_generalledg.credit_amount', '>', 0)
        $all_generalledg = DB::table('bk_generalledg')
            ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
            ->where('chart_of_accounts.account_name', 'like', '%payable%')
            ->sum(DB::raw('bk_generalledg.debit_amount - bk_generalledg.credit_amount'));
        
        $overalltotalgeneralledger = $all_generalledg;

        // return $overalltotalgeneralledger;

        $overallpayables = $overalltotalgeneralledger + $overalltotalreceived_history;

      
      
        $all_disbursements = DB::table('bk_disbursements')
            ->where('deleted', 0)
            ->sum(DB::raw('round(amount, 2)'));

        $all_disbursements = number_format($all_disbursements, 2, '.', ',');
        
        // $overalltotaldisbursements = number_format($all_disbursements, 2, '.', ',');
        $overalltotaldisbursements = $all_disbursements;
        
        if ($request->ajax()) {
            return response()->json([
                'overalltotalreceived_history' => $overalltotalreceived_history,
                'overalltotalgeneralledger' => $overalltotalgeneralledger,
                'overallpayables' => $overallpayables,
                'overalltotaldisbursements' => $overalltotaldisbursements,
            ]);
        }
    
    //     return view('bookkeeper.pages.home')
    //         ->with('students', $allstudents)
    //         ->with('overalltotalassessment', $overalltotalassessment)
    //         ->with('overalltotaldiscount', $overalltotaldiscount)
    //         ->with('overalltotalnetassessed', $overalltotalnetassessed)
    //         ->with('overalltotalpayment', $overalltotalpayment)
    //         ->with('overalltotalbalance', $overalltotalbalance);
    }

    // public static function cashflow_fiscal(Request $request){

    //     $fiscal_yearid = $request->get('fiscal_year_id');

    //     $fiscal_year = DB::table('bk_fiscal_year')
    //     // ->join('receiving', 'bk_receiving_history.poid', '=', 'receiving.id')
    //     // ->join('purchasing', 'receiving.poid', '=', 'purchasing.id')
    //     // ->join('purchasing_supplier', 'purchasing.supplierid', '=', 'purchasing_supplier.id')
    //     ->where('bk_fiscal_year.id',$fiscal_yearid)
    //     ->where('bk_fiscal_year.deleted',0)
    //     ->select(
    //         'bk_fiscal_year.id',
    //         'bk_fiscal_year.stime',
    //         'bk_fiscal_year.etime',
           
    //     )
    //     ->get();

    //     // $receiving_items = DB::table('bk_receiving_details_history')
    //     // ->join('bk_expenses_items', 'bk_receiving_details_history.itemid', '=', 'bk_expenses_items.id')
    //     // ->where('bk_receiving_details_history.headerid',$po_id)
    //     // ->select(
    //     //     'bk_receiving_details_history.id',
    //     //     'bk_receiving_details_history.itemid',
    //     //     'bk_receiving_details_history.amount',
    //     //     'bk_receiving_details_history.qty',
    //     //     'bk_receiving_details_history.total',
    //     //     'bk_receiving_details_history.receivedqty',
    //     //     'bk_receiving_details_history.rtotal',
    //     //     'bk_expenses_items.description'
    //     // )
    //     // ->get();

    //     // $purchasing_supplier = DB::table('purchasing_supplier')
    //     // ->where('deleted',0)
    //     // ->get();

    //     return response()->json([
    //         'fiscal_year' => $fiscal_year,
    //         // 'receiving_items' => $receiving_items,
    //         // 'purchasing_supplier' => $purchasing_supplier
    //     ]);
    // }

    // public static function cashflow_fiscal(Request $request){

    //     $fiscal_yearid = $request->get('fiscal_year_id');

    //     $fiscal_year = DB::table('bk_fiscal_year')
    //     ->where('bk_fiscal_year.id',$fiscal_yearid)
    //     ->where('bk_fiscal_year.deleted',0)
    //     ->select(
    //         'bk_fiscal_year.id',
    //         'bk_fiscal_year.stime',
    //         'bk_fiscal_year.etime',
           
    //     )
    //     ->get();

    //     $cashflow_total_amount = DB::table('chart_of_accounts')
    //             ->where('chart_of_accounts.deleted', 0)
    //             ->leftJoin('bk_generalledg', function ($join) use ($fiscal_year) {
    //                 $join->on('chart_of_accounts.id', '=', 'bk_generalledg.coaid')
    //                     ->where('bk_generalledg.active_fiscal_year_id', $fiscal_year);
    //             })
    //             ->select(
    //                 'bk_generalledg.debit_amount',
    //                 'bk_generalledg.credit_amount',
    //                 'bk_generalledg.date',
    //                 'chart_of_accounts.id',
    //                 'classification',
    //                 'code',
    //                 'account_name',
    //                 'account_type',
    //                 'financial_statement as fs',
    //                 'normal_balance',
    //                 'cashflow_statement'
    //             );

    //     $result = $cashflow_total_amount->get()->groupBy('cashflow_statement');

    //     return response()->json([
    //         'fiscal_year' => $fiscal_year,
           
    //     ]);
    // }

    // public static function cashflow_fiscal(Request $request)
    // {
    //     $fiscal_yearid = $request->get('fiscal_year_id');
    
    //     $fiscal_year = DB::table('bk_fiscal_year')
    //         ->where('bk_fiscal_year.id', $fiscal_yearid)
    //         ->where('bk_fiscal_year.deleted', 0)
    //         ->select(
    //             'bk_fiscal_year.id',
    //             'bk_fiscal_year.stime',
    //             'bk_fiscal_year.etime',
    //         )
    //         ->first();
    
    //     $cashflow_data = DB::table('chart_of_accounts')
    //         ->where('chart_of_accounts.deleted', 0)
    //         ->leftJoin('bk_generalledg', function ($join) use ($fiscal_yearid) {
    //             $join->on('chart_of_accounts.id', '=', 'bk_generalledg.coaid')
    //                 ->where('bk_generalledg.active_fiscal_year_id', $fiscal_yearid);
    //         })
    //         ->select(
    //             DB::raw('YEAR(bk_generalledg.date) as year'),
    //             DB::raw('MONTH(bk_generalledg.date) as month'),
    //             DB::raw('DATE_FORMAT(bk_generalledg.date, "%Y-%m") as month_year'),
    //             DB::raw('SUM(bk_generalledg.debit_amount - bk_generalledg.credit_amount) as total_amount')
    //         )
    //         ->whereNotNull('bk_generalledg.date')
    //         ->groupBy('year', 'month', 'month_year')
    //         ->orderBy('year')
    //         ->orderBy('month')
    //         ->get();
    
    //     // Format the results by month
    //     $result = $cashflow_data->mapWithKeys(function ($item) {
    //         $monthName = date('F Y', mktime(0, 0, 0, $item->month, 1, $item->year));
    //         return [$monthName => $item->total_amount];
    //     });
    
    //     return response()->json([
    //         'fiscal_year' => $fiscal_year,
    //         'cashflow_by_month' => $result,
    //     ]);
    // }

    // working ...........
    // public static function cashflow_fiscal(Request $request)
    // {
    //     $fiscal_yearid = $request->get('fiscal_year_id');

    //     // Get fiscal year start/end dates
    //     $fiscal_year = DB::table('bk_fiscal_year')
    //         ->where('id', $fiscal_yearid)
    //         ->where('deleted', 0)
    //         ->first();

    //     // Get all transactions within fiscal year dates
    //     $transactions = DB::table('bk_generalledg')
    //         ->where('active_fiscal_year_id', $fiscal_yearid)
    //         // ->where('deleted', 0)
    //         ->whereBetween('date', [$fiscal_year->stime, $fiscal_year->etime])
    //         ->select(
    //             DB::raw('YEAR(date) as year'),
    //             DB::raw('MONTH(date) as month'),
    //             DB::raw('SUM(debit_amount - credit_amount) as net_amount')
    //         )
    //         ->groupBy('year', 'month')
    //         ->orderBy('year')
    //         ->orderBy('month')
    //         ->get();

    //     // Generate all months in fiscal year
    //     $start = new DateTime($fiscal_year->stime);
    //     $end = new DateTime($fiscal_year->etime);
    //     $interval = new DateInterval('P1M');
    //     $period = new DatePeriod($start, $interval, $end);

    //     $results = [];
    //     foreach ($period as $dt) {
    //         // Generate a key for each month in the fiscal year.'Month Year' Format
    //         $monthKey = $dt->format('F Y');
    //         $results[$monthKey] = '0.00'; // Initialize all months
            
    //         // Fill in actual amounts where they exist
    //         foreach ($transactions as $txn) {
    //             $txnMonth = date('F Y', mktime(0, 0, 0, $txn->month, 1, $txn->year));
    //             if ($txnMonth === $monthKey) {
    //                 $results[$monthKey] = number_format($txn->net_amount, 2);
    //                 break;
    //             }
    //         }
    //     }

    //     return response()->json([
    //         'fiscal_year' => $fiscal_year,
    //         'cashflow_by_month' => $results
    //     ]);
    // }

    //finally
    // public static function cashflow_fiscal(Request $request)
    // {
    //     $fiscal_yearid = $request->get('fiscal_year_id');
    
    //     // Get fiscal year start/end dates
    //     $fiscal_year = DB::table('bk_fiscal_year')
    //         ->select('stime', 'etime')
    //         ->where('id', $fiscal_yearid)
    //         ->where('deleted', 0)
    //         ->first();
    
    //     // Adjust etime to include the full month by setting it to the last day of the month
    //     $etime = new DateTime($fiscal_year->etime);
    //     $etime->modify('last day of this month');
    //     $adjusted_etime = $etime->format('Y-m-d');
    
    //     // Get all transactions within fiscal year dates
    //     $transactions = DB::table('bk_generalledg')
    //         ->where('active_fiscal_year_id', $fiscal_yearid)
    //         ->whereBetween('date', [$fiscal_year->stime, $adjusted_etime])
    //         ->select(
    //             DB::raw('YEAR(date) as year'),
    //             DB::raw('MONTH(date) as month'),
    //             DB::raw('SUM(debit_amount - credit_amount) as net_amount')
    //         )
    //         ->groupBy('year', 'month')
    //         ->orderBy('year')
    //         ->orderBy('month')
    //         ->get();
    
    //     // Generate all months in fiscal year
    //     $start = new DateTime($fiscal_year->stime);
    //     $end = new DateTime($adjusted_etime);
    //     $interval = new DateInterval('P1M');
    //     $period = new DatePeriod($start, $interval, $end);
    
    //     $results = [];
    //     foreach ($period as $dt) {
    //         // Generate a key for each month in the fiscal year.'Month Year' Format
    //         $monthKey = $dt->format('F Y');
    //         $results[$monthKey] = '0.00'; // Initialize all months
            
    //         // Fill in actual amounts where they exist
    //         foreach ($transactions as $txn) {
    //             $txnMonth = date('F Y', mktime(0, 0, 0, $txn->month, 1, $txn->year));
    //             if ($txnMonth === $monthKey) {
    //                 $results[$monthKey] = number_format($txn->net_amount, 2);
    //                 break;
    //             }
    //         }
    //     }
    
    //     return response()->json([
    //         'fiscal_year' => $fiscal_year,
    //         'cashflow_by_month' => $results
    //     ]);
    // }

    public static function cashflow_fiscal(Request $request)
    {
        $fiscal_yearid = $request->get('fiscal_year_id');
    
        // Get fiscal year start/end dates
        $fiscal_year = DB::table('bk_fiscal_year')
            ->select('stime', 'etime')
            ->where('id', $fiscal_yearid)
            ->where('deleted', 0)
            ->first();
    
        // Adjust etime to include the full month by setting it to the last day of the month
        $etime = new DateTime($fiscal_year->etime);
        $etime->modify('last day of this month');
        $adjusted_etime = $etime->format('Y-m-d');
    
        // Get all transactions within fiscal year dates
        $transactions = DB::table('bk_generalledg')
            ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
            ->where('chart_of_accounts.deleted', 0)
            ->where('chart_of_accounts.account_name', 'LIKE', 'Cash on Hand%')
            ->where('active_fiscal_year_id', $fiscal_yearid)
            ->whereBetween('date', [$fiscal_year->stime, $adjusted_etime])
            ->select(
                DB::raw('YEAR(date) as year'),
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(debit_amount - credit_amount) as net_amount')
                // DB::raw('SUM(debit_amount) as net_amount')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

    
        // Generate all months in fiscal year
        $start = new DateTime($fiscal_year->stime);
        $end = new DateTime($adjusted_etime);
        $interval = new DateInterval('P1M');
        $period = new DatePeriod($start, $interval, $end);

       
    
        $results = [];
        foreach ($period as $dt) {
            // Generate a key for each month in the fiscal year.'Month Year' Format
            // $monthKey = $dt->format('F Y');
            $monthKey = $dt->format('F');
            $results[$monthKey] = '0.00'; // Initialize all months
            
            // Fill in actual amounts where they exist
            foreach ($transactions as $txn) {
                $txnMonth = date('F', mktime(0, 0, 0, $txn->month, 1, $txn->year));
                if ($txnMonth === $monthKey) {
                    $results[$monthKey] = number_format($txn->net_amount, 2);
                    break;
                }
            }
            // foreach ($transactions as $txn) {
            //     $txnMonth = date('F Y', mktime(0, 0, 0, $txn->month, 1, $txn->year));
            //     if ($txnMonth === $monthKey) {
            //         $results[$monthKey] = number_format($txn->net_amount, 2);
            //         break;
            //     }
            // }
        }

        //////////////////////////////// for starting date
        if (!$fiscal_year) {
            return response()->json(['error' => 'Fiscal year not found'], 404);
        }
    
        // Extract the month and year from stime
        $stimes = new DateTime($fiscal_year->stime);
        $targetMonthKeys = $stimes->format('F Y'); // e.g., "January 2023"
    
        // Adjust etime to include the full month by setting it to the last day of the month
        $etimes = new DateTime($fiscal_year->etime);
        $etimes->modify('last day of this month');
        $adjusted_etimes = $etimes->format('Y-m-d');
    
        // Get transactions for the fiscal year
        $transactionss = DB::table('bk_generalledg')
            ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
            ->where('chart_of_accounts.deleted', 0)
            ->where('chart_of_accounts.account_name', 'LIKE', 'Cash on Hand%')
            ->where('active_fiscal_year_id', $fiscal_yearid)
            ->whereBetween('date', [$fiscal_year->stime, $adjusted_etimes])
            ->select(
                DB::raw('YEAR(date) as year'),
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(debit_amount - credit_amount) as net_amount')
                // DB::raw('SUM(debit_amount) as net_amount')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
    
        // Find the transaction matching the stime month
        $resultForStimeMonths = '0.00'; // Default if no transactions found
    
        foreach ($transactions as $txns) {
            $txnMonths = date('F Y', mktime(0, 0, 0, $txns->month, 1, $txn->year));
            if ($txnMonths === $targetMonthKeys) {
                $resultForStimeMonths = number_format($txns->net_amount, 2);
                break;
            }
        }

        ///////////////////////////////// for end date

        // Extract the month and year from stime
        $stimess = new DateTime($fiscal_year->stime);
        $targetMonthKeyss = $stimess->format('F Y'); // e.g., "January 2023"

        // Adjust etime to include the full month by setting it to the last day of the month
        $etimess = new DateTime($fiscal_year->etime);
        $etimess->modify('last day of this month');
        $adjusted_etimess = $etimess->format('Y-m-d');

        // Get all transactions within the fiscal year
        $transactionsss = DB::table('bk_generalledg')
            ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
            ->where('chart_of_accounts.deleted', 0)
            ->where('chart_of_accounts.account_name', 'LIKE', 'Cash on Hand%')
            ->where('active_fiscal_year_id', $fiscal_yearid)
            ->whereBetween('date', [$fiscal_year->stime, $adjusted_etimess])
            ->select(
                DB::raw('YEAR(date) as year'),
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(debit_amount - credit_amount) as net_amount')
                // DB::raw('SUM(debit_amount) as net_amount')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Calculate the total cashflow for the entire fiscal year
        $totalCashflowss = DB::table('bk_generalledg')
            ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
            ->where('chart_of_accounts.deleted', 0)
            ->where('chart_of_accounts.account_name', 'LIKE', 'Cash on Hand%')
            ->where('active_fiscal_year_id', $fiscal_yearid)
            ->whereBetween('date', [$fiscal_year->stime, $adjusted_etimess])
            ->select(DB::raw('SUM(debit_amount - credit_amount) as total_net_amount'))
            // ->select(DB::raw('SUM(debit_amount) as total_net_amount'))
            ->first();

        $totalNetAmountss = $totalCashflowss->total_net_amount ?? 0;
        $formattedTotalss = number_format($totalNetAmountss, 2);

        // Find the transaction matching the stime month
        $resultForStimeMonthss = '0.00'; // Default if no transactions found

        foreach ($transactionsss as $txnss) {
            $txnMonthss = date('F Y', mktime(0, 0, 0, $txnss->month, 1, $txnss->year));
            if ($txnMonthss === $targetMonthKeyss) {
                $resultForStimeMonthss = number_format($txnss->net_amount, 2);
                break;
            }
        }

    
        return response()->json([
            'fiscal_year' => $fiscal_year,
            'cashflow_by_month' => $results,
            'cashflow_for_stime_month' => [
                'month' => $targetMonthKeys,
                'startdate_net_amount' => $resultForStimeMonths
            ],
            'total_cashflow_till_end_month' => [
                'from' => $fiscal_year->stime,
                'to' => $adjusted_etimess,
                'total_till_end_date_net_amount' => $formattedTotalss
            ]
        ]);
    }

    // public function filter_acctreceivable_income_expenses(Request $request)
    // {
    //     $selectedschoolyear = $request->get('selectedschoolyear_income_expenses');
    //     $selecteddaterange  = $request->get('selecteddaterange');
    //     $selecteddepartment = $request->get('selecteddepartment');
    //     $selectedgradelevel = $request->get('selectedgradelevel');
    //     $selectedsemester   = $request->get('selectedsemester');
    //     $selectedsection    = $request->get('selectedsection');
    //     $selectedgrantee    = $request->get('selectedgrantee');
    //     $selectedmode       = $request->get('selectedmode'); 
    
    //     $allstudents = AccountsReceivableModel::allstudents($selectedschoolyear,$selecteddaterange,$selecteddepartment,$selectedgradelevel,$selectedsemester,$selectedsection,$selectedgrantee,$selectedmode);
        
    //     $overalltotalassessment     = $allstudents->sum('totalassessment');
    //     $overalltotaldiscount       = $allstudents->sum('discount');
    //     $overalltotalnetassessed    = $allstudents->sum('netassessed');
    //     $overalltotalpayment        = $allstudents->sum('totalpayment');
    //     $overalltotalbalance        = $allstudents->sum('balance');
    
    //     if ($request->ajax()) {
    //         return response()->json([
    //             'overalltotalassessment' => $overalltotalassessment,
    //             'overalltotaldiscount' => $overalltotaldiscount,
    //             'overalltotalnetassessed' => $overalltotalnetassessed,
    //             'overalltotalpayment' => $overalltotalpayment,
    //             'overalltotalbalance' => $overalltotalbalance,
    //             'students' => $allstudents
    //         ]);
    //     }
    
    //     return view('bookkeeper.pages.home')
    //         ->with('students', $allstudents)
    //         ->with('overalltotalassessment', $overalltotalassessment)
    //         ->with('overalltotaldiscount', $overalltotaldiscount)
    //         ->with('overalltotalnetassessed', $overalltotalnetassessed)
    //         ->with('overalltotalpayment', $overalltotalpayment)
    //         ->with('overalltotalbalance', $overalltotalbalance);
    // }

    // public function filter_acctreceivable_income_expenses(Request $request)
    // {
    //     $selectedschoolyear = $request->get('selectedschoolyear_income_expenses');
    //     $selectedfiscalyear = $request->get('selectedfiscalyear_income_expenses');
    //     $selecteddaterange = $request->get('selecteddaterange');
    //     $selecteddepartment = $request->get('selecteddepartment');
    //     $selectedgradelevel = $request->get('selectedgradelevel');
    //     $selectedsemester = $request->get('selectedsemester');
    //     $selectedsection = $request->get('selectedsection');
    //     $selectedgrantee = $request->get('selectedgrantee');
    //     $selectedmode = $request->get('selectedmode'); 

    //     // Get all students data
    //     $allstudents = AccountsReceivableModel::allstudents($selectedschoolyear,$selecteddaterange,$selecteddepartment,$selectedgradelevel,$selectedsemester,$selectedsection,$selectedgrantee,$selectedmode);
        
    //     // Get fiscal year start/end dates
    //     $fiscal_year = DB::table('bk_fiscal_year')
    //         ->select('stime', 'etime')
    //         ->where('id', $selectedfiscalyear)
    //         ->where('deleted', 0)
    //         ->first();

    //     if (!$fiscal_year) {
    //         return response()->json(['error' => 'Fiscal year not found'], 404);
    //     }

    //     // Adjust etime to include the full month by setting it to the last day of the month
    //     $etime = new DateTime($fiscal_year->etime);
    //     $etime->modify('last day of this month');
    //     $adjusted_etime = $etime->format('Y-m-d');

    //     // Process student payments by month
    //     $monthlyPayments = [];
    //     foreach ($allstudents as $student) {
    //         // Assuming there's a createddatetime field in the student data
    //         $paymentDate = new DateTime($student->createddatetime);
    //         $monthKey = $paymentDate->format('F Y'); // Format: "Month Year"
            
    //         // Only include payments within the fiscal year
    //         if ($paymentDate >= new DateTime($fiscal_year->stime) && $paymentDate <= $etime) {
    //             if (!isset($monthlyPayments[$monthKey])) {
    //                 $monthlyPayments[$monthKey] = 0;
    //             }
    //             $monthlyPayments[$monthKey] += $student->totalpayment;
    //         }
    //     }

    //     // Generate all months in fiscal year for complete reporting
    //     $start = new DateTime($fiscal_year->stime);
    //     $end = new DateTime($adjusted_etime);
    //     $interval = new DateInterval('P1M');
    //     $period = new DatePeriod($start, $interval, $end);

    //     $results = [];
    //     foreach ($period as $dt) {
    //         $monthKey = $dt->format('F Y');
    //         $results[$monthKey] = isset($monthlyPayments[$monthKey]) 
    //             ? number_format($monthlyPayments[$monthKey], 2)
    //             : '0.00';
    //     }

    //     // Calculate overall totals
    //     $overalltotalassessment = $allstudents->sum('totalassessment');
    //     $overalltotaldiscount = $allstudents->sum('discount');
    //     $overalltotalnetassessed = $allstudents->sum('netassessed');
    //     $overalltotalpayment = $allstudents->sum('totalpayment');
    //     $overalltotalbalance = $allstudents->sum('balance');

    //     if ($request->ajax()) {
    //         return response()->json([
    //             'overalltotalassessment' => $overalltotalassessment,
    //             'overalltotaldiscount' => $overalltotaldiscount,
    //             'overalltotalnetassessed' => $overalltotalnetassessed,
    //             'overalltotalpayment' => $overalltotalpayment,
    //             'overalltotalbalance' => $overalltotalbalance,
    //             'monthlyPayments' => $results, // Add monthly breakdown to response
    //             'students' => $allstudents
    //         ]);
    //     }

    //     return view('bookkeeper.pages.home')
    //         ->with('students', $allstudents)
    //         ->with('overalltotalassessment', $overalltotalassessment)
    //         ->with('overalltotaldiscount', $overalltotaldiscount)
    //         ->with('overalltotalnetassessed', $overalltotalnetassessed)
    //         ->with('overalltotalpayment', $overalltotalpayment)
    //         ->with('overalltotalbalance', $overalltotalbalance)
    //         ->with('monthlyPayments', $results);
    // }

    // public function filter_acctreceivable_income_expenses(Request $request)
    // {
    //     $selectedschoolyear = $request->get('selectedschoolyear_income_expenses');
    //     $selectedfiscalyear = $request->get('selectedfiscalyear_income_expenses');
    //     $selecteddaterange = $request->get('selecteddaterange');
    //     $selecteddepartment = $request->get('selecteddepartment');
    //     $selectedgradelevel = $request->get('selectedgradelevel');
    //     $selectedsemester = $request->get('selectedsemester');
    //     $selectedsection = $request->get('selectedsection');
    //     $selectedgrantee = $request->get('selectedgrantee');
    //     $selectedmode = $request->get('selectedmode'); 
    
    //     // Get fiscal year start/end dates first
    //     $fiscal_year = DB::table('bk_fiscal_year')
    //         ->select('stime', 'etime')
    //         ->where('id', $selectedfiscalyear)
    //         ->where('deleted', 0)
    //         ->first();
    
    //     if (!$fiscal_year) {
    //         return response()->json(['error' => 'Fiscal year not found'], 404);
    //     }
    
    //     // Adjust etime to include the full month by setting it to the last day of the month
    //     $etime = new DateTime($fiscal_year->etime);
    //     $etime->modify('last day of this month');
    //     $adjusted_etime = $etime->format('Y-m-d');
    
    //     // First get all students that match the filters
    //     $allstudents = AccountsReceivableModel::allstudents(
    //         $selectedschoolyear, $selecteddaterange, $selecteddepartment, 
    //         $selectedgradelevel, $selectedsemester, $selectedsection, 
    //         $selectedgrantee, $selectedmode
    //     );
    
    //     // Get student IDs from the filtered students
    //     $studentIds = $allstudents->pluck('id')->toArray();
    
    //     // Get all payments within the fiscal year for these students
    //     $paymentsQuery = DB::table('studledger')
    //         ->select(
    //             DB::raw('YEAR(createddatetime) as year'),
    //             DB::raw('MONTH(createddatetime) as month'),
    //             DB::raw('SUM(payment) as total_payment')
    //         )
    //         ->where('deleted', 0)
    //         ->where('payment', '>', 0)
    //         ->where('classid', null)
    //         ->where('particulars', 'not like', '%DISCOUNT:%')
    //         ->where('particulars', 'not like', '%ADJ:%')
    //         ->where('syid', $selectedschoolyear)
    //         ->whereBetween('createddatetime', [$fiscal_year->stime, $adjusted_etime])
    //         ->whereIn('studid', $studentIds); // Only payments for the filtered students
    
    //     // Apply semester filter if needed (similar to gettotalpayment logic)
    //     // if ($selectedsemester && ($selecteddepartment == 5 || $selecteddepartment == 6)) {
    //     //     $paymentsQuery->where('semid', $selectedsemester);
    //     // }
    
    //     $payments = $paymentsQuery
    //         ->groupBy('year', 'month')
    //         ->orderBy('year')
    //         ->orderBy('month')
    //         ->get();
    
    //     // Generate all months in fiscal year for complete reporting
    //     $start = new DateTime($fiscal_year->stime);
    //     $end = new DateTime($adjusted_etime);
    //     $interval = new DateInterval('P1M');
    //     $period = new DatePeriod($start, $interval, $end);
    
    //     $results = [];
    //     foreach ($period as $dt) {
    //         $monthKey = $dt->format('F Y');
    //         $monthNum = $dt->format('n');
    //         $yearNum = $dt->format('Y');
            
    //         // Find matching payment for this month
    //         $payment = $payments->first(function($item) use ($monthNum, $yearNum) {
    //             return $item->month == $monthNum && $item->year == $yearNum;
    //         });
            
    //         $results[$monthKey] = $payment ? number_format($payment->total_payment, 2) : '0.00';
    //     }
    
    //     // Calculate overall totals
    //     $overalltotalassessment = $allstudents->sum('totalassessment');
    //     $overalltotaldiscount = $allstudents->sum('discount');
    //     $overalltotalnetassessed = $allstudents->sum('netassessed');
    //     $overalltotalpayment = $allstudents->sum('totalpayment');
    //     $overalltotalbalance = $allstudents->sum('balance');
    
    //     if ($request->ajax()) {
    //         return response()->json([
    //             'overalltotalassessment' => $overalltotalassessment,
    //             'overalltotaldiscount' => $overalltotaldiscount,
    //             'overalltotalnetassessed' => $overalltotalnetassessed,
    //             'overalltotalpayment' => $overalltotalpayment,
    //             'overalltotalbalance' => $overalltotalbalance,
    //             'monthlyPayments' => $results,
    //             'students' => $allstudents
    //         ]);
    //     }
    
    //     return view('bookkeeper.pages.home')
    //         ->with('students', $allstudents)
    //         ->with('overalltotalassessment', $overalltotalassessment)
    //         ->with('overalltotaldiscount', $overalltotaldiscount)
    //         ->with('overalltotalnetassessed', $overalltotalnetassessed)
    //         ->with('overalltotalpayment', $overalltotalpayment)
    //         ->with('overalltotalbalance', $overalltotalbalance)
    //         ->with('monthlyPayments', $results);
    // }

    // working nani
    // public function filter_acctreceivable_income_expenses(Request $request)
    // {
    //     $selectedschoolyear = $request->get('selectedschoolyear_income_expenses');
    //     $selectedfiscalyear = $request->get('selectedfiscalyear_income_expenses');
    //     $selecteddaterange = $request->get('selecteddaterange');
    //         $selecteddepartment = $request->get('selecteddepartment');
    //         $selectedgradelevel = $request->get('selectedgradelevel');
    //         $selectedsemester = $request->get('selectedsemester');
    //         $selectedsection = $request->get('selectedsection');
    //         $selectedgrantee = $request->get('selectedgrantee');
    //         $selectedmode = $request->get('selectedmode'); 
    
    //     // Get fiscal year dates
    //     $fiscal_year = DB::table('bk_fiscal_year')
    //         ->select('stime', 'etime')
    //         ->where('id', $selectedfiscalyear)
    //         ->where('deleted', 0)
    //         ->first();
    
    //     if (!$fiscal_year) {
    //         return response()->json(['error' => 'Fiscal year not found'], 404);
    //     }
    
    //     // Get all filtered students
    //     $allstudents = AccountsReceivableModel::allstudents(
    //         $selectedschoolyear, $selecteddaterange, $selecteddepartment, 
    //         $selectedgradelevel, $selectedsemester, $selectedsection, 
    //         $selectedgrantee, $selectedmode
    //     );
    
    //     $studentIds = $allstudents->pluck('id')->toArray();
    
    //     // Get ALL payments for these students
    //     $allPayments = DB::table('studledger')
    //         ->select('createddatetime', 'payment')
    //         ->where('deleted', 0)
    //         ->where('payment', '>', 0)
    //         ->where('classid', null)
    //         ->where('particulars', 'not like', '%DISCOUNT:%')
    //         ->where('particulars', 'not like', '%ADJ:%')
    //         ->where('syid', $selectedschoolyear)
    //         ->whereIn('studid', $studentIds)
    //         ->get();
    
    //     // Group payments by their actual month/year
    //     $paymentGroups = [];
    //     foreach ($allPayments as $payment) {
    //         $date = new DateTime($payment->createddatetime);
    //         $monthYear = $date->format('F Y');
            
    //         if (!isset($paymentGroups[$monthYear])) {
    //             $paymentGroups[$monthYear] = 0;
    //         }
    //         $paymentGroups[$monthYear] += $payment->payment;
    //     }
    
    //     // Sort payments by date (chronological order)
    //     uksort($paymentGroups, function($a, $b) {
    //         return strtotime($a) - strtotime($b);
    //     });
    
    //     // Calculate overall totals
    //     $overalltotalassessment = $allstudents->sum('totalassessment');
    //     $overalltotaldiscount = $allstudents->sum('discount');
    //     $overalltotalnetassessed = $allstudents->sum('netassessed');
    //     $overalltotalpayment = $allstudents->sum('totalpayment');
    //     $overalltotalbalance = $allstudents->sum('balance');
    
    //     // Verify sum matches overalltotalpayment
    //     $sumPayments = array_sum($paymentGroups);
    //     if (abs($sumPayments - $overalltotalpayment) > 0.01) {
    //         // If discrepancy exists, adjust the first month
    //         $adjustment = $overalltotalpayment - $sumPayments;
    //         if (!empty($paymentGroups)) {
    //             $firstMonth = array_key_first($paymentGroups);
    //             $paymentGroups[$firstMonth] += $adjustment;
    //         }
    //     }
    
    //     // Format the amounts
    //     $formattedPayments = [];
    //     foreach ($paymentGroups as $month => $total) {
    //         $formattedPayments[$month] = number_format($total, 2);
    //     }
    
    //     if ($request->ajax()) {
    //         return response()->json([
    //             'overalltotalassessment' => $overalltotalassessment,
    //             'overalltotaldiscount' => $overalltotaldiscount,
    //             'overalltotalnetassessed' => $overalltotalnetassessed,
    //             'overalltotalpayment' => $overalltotalpayment,
    //             'overalltotalbalance' => $overalltotalbalance,
    //             'monthlyPayments' => $formattedPayments,
    //             'students' => $allstudents
    //         ]);
    //     }
    
    //     return view('bookkeeper.pages.home')
    //         ->with('students', $allstudents)
    //         ->with('overalltotalassessment', $overalltotalassessment)
    //         ->with('overalltotaldiscount', $overalltotaldiscount)
    //         ->with('overalltotalnetassessed', $overalltotalnetassessed)
    //         ->with('overalltotalpayment', $overalltotalpayment)
    //         ->with('overalltotalbalance', $overalltotalbalance)
    //         ->with('monthlyPayments', $formattedPayments);
    // }

    // working najud ni siya kay
    // public function filter_acctreceivable_income_expenses(Request $request)
    // {
    //     $selectedschoolyear = $request->get('selectedschoolyear_income_expenses');
    //     $selectedfiscalyear = $request->get('selectedfiscalyear_income_expenses');
    //     $selecteddaterange = $request->get('selecteddaterange');
    //         $selecteddepartment = $request->get('selecteddepartment');
    //         $selectedgradelevel = $request->get('selectedgradelevel');
    //         $selectedsemester = $request->get('selectedsemester');
    //         $selectedsection = $request->get('selectedsection');
    //         $selectedgrantee = $request->get('selectedgrantee');
    //         $selectedmode = $request->get('selectedmode'); 
    
    //     // Get fiscal year dates
    //     $fiscal_year = DB::table('bk_fiscal_year')
    //         ->select('stime', 'etime')
    //         ->where('id', $selectedfiscalyear)
    //         ->where('deleted', 0)
    //         ->first();
    
    //     if (!$fiscal_year) {
    //         return response()->json(['error' => 'Fiscal year not found'], 404);
    //     }
    
    //     // Get all filtered students
    //     $allstudents = AccountsReceivableModel::allstudents(
    //         $selectedschoolyear, $selecteddaterange, $selecteddepartment, 
    //         $selectedgradelevel, $selectedsemester, $selectedsection, 
    //         $selectedgrantee, $selectedmode
    //     );
    
    //     $studentIds = $allstudents->pluck('id')->toArray();
    
    //     // Get ALL payments for these students
    //     $allPayments = DB::table('studledger')
    //         ->select('createddatetime', 'payment')
    //         ->where('deleted', 0)
    //         ->where('payment', '>', 0)
    //         ->where('classid', null)
    //         ->where('particulars', 'not like', '%DISCOUNT:%')
    //         ->where('particulars', 'not like', '%ADJ:%')
    //         ->where('syid', $selectedschoolyear)
    //         ->whereIn('studid', $studentIds)
    //         ->get();
    
    //     // Group payments by their actual month/year
    //     $paymentGroups = [];
    //     foreach ($allPayments as $payment) {
    //         $date = new DateTime($payment->createddatetime);
    //         $monthYear = $date->format('F');
            
    //         if (!isset($paymentGroups[$monthYear])) {
    //             $paymentGroups[$monthYear] = 0;
    //         }
    //         $paymentGroups[$monthYear] += $payment->payment;
    //     }
    
    //     // Sort payments by date (chronological order)
    //     uksort($paymentGroups, function($a, $b) {
    //         return strtotime($a) - strtotime($b);
    //     });
    
    //     // Calculate overall totals
    //     $overalltotalassessment = $allstudents->sum('totalassessment');
    //     $overalltotaldiscount = $allstudents->sum('discount');
    //     $overalltotalnetassessed = $allstudents->sum('netassessed');
    //     $overalltotalpayment = $allstudents->sum('totalpayment');
    //     $overalltotalbalance = $allstudents->sum('balance');
    
    //     // Verify sum matches overalltotalpayment
    //     $sumPayments = array_sum($paymentGroups);
    //     if (abs($sumPayments - $overalltotalpayment) > 0.01) {
    //         // If discrepancy exists, adjust the first month
    //         $adjustment = $overalltotalpayment - $sumPayments;
    //         if (!empty($paymentGroups)) {
    //             $firstMonth = array_key_first($paymentGroups);
    //             $paymentGroups[$firstMonth] += $adjustment;
    //         }
    //     }
    
    //     // Format the amounts
    //     $formattedPayments = [];
    //     foreach ($paymentGroups as $month => $total) {
    //         $formattedPayments[$month] = number_format($total, 2);
    //     }
    
    //     if ($request->ajax()) {
    //         return response()->json([
    //             'overalltotalassessment' => $overalltotalassessment,
    //             'overalltotaldiscount' => $overalltotaldiscount,
    //             'overalltotalnetassessed' => $overalltotalnetassessed,
    //             'overalltotalpayment' => $overalltotalpayment,
    //             'overalltotalbalance' => $overalltotalbalance,
    //             'monthlyPayments' => $formattedPayments,
    //             // 'students' => $allstudents
    //         ]);
    //     }
    
    //     return view('bookkeeper.pages.home')
    //         ->with('students', $allstudents)
    //         ->with('overalltotalassessment', $overalltotalassessment)
    //         ->with('overalltotaldiscount', $overalltotaldiscount)
    //         ->with('overalltotalnetassessed', $overalltotalnetassessed)
    //         ->with('overalltotalpayment', $overalltotalpayment)
    //         ->with('overalltotalbalance', $overalltotalbalance)
    //         ->with('monthlyPayments', $formattedPayments);
    // }

    // public function filter_acctreceivable_income_expenses(Request $request)
    // {
    //     // Get parameters
    //     $selectedschoolyear = $request->get('selectedschoolyear_income_expenses');
    //     $selectedfiscalyear = $request->get('selectedfiscalyear_income_expenses');
    //     $selecteddaterange = $request->get('selecteddaterange');
    //             $selecteddepartment = $request->get('selecteddepartment');
    //             $selectedgradelevel = $request->get('selectedgradelevel');
    //             $selectedsemester = $request->get('selectedsemester');
    //             $selectedsection = $request->get('selectedsection');
    //             $selectedgrantee = $request->get('selectedgrantee');
    //             $selectedmode = $request->get('selectedmode'); 
    
    //     // Get fiscal year dates
    //     $fiscal_year = DB::table('bk_fiscal_year')
    //         ->select('stime', 'etime')
    //         ->where('id', $selectedfiscalyear)
    //         ->where('deleted', 0)
    //         ->first();
    
    //     if (!$fiscal_year) {
    //         return response()->json(['error' => 'Fiscal year not found'], 404);
    //     }
    
    //     // Adjust etime to end of month
    //     $etime = new DateTime($fiscal_year->etime);
    //     $etime->modify('last day of this month');
    //     $adjusted_etime = $etime->format('Y-m-d');
    
    //     // Get filtered students
    //     $allstudents = AccountsReceivableModel::allstudents(
    //         $selectedschoolyear, $selecteddaterange, $selecteddepartment, 
    //         $selectedgradelevel, $selectedsemester, $selectedsection, 
    //         $selectedgrantee, $selectedmode
    //     );
    
    //     $studentIds = $allstudents->pluck('id')->toArray();
    
    //     // Get payments within fiscal year
    //     $allPayments = DB::table('studledger')
    //         ->select('createddatetime', 'payment')
    //         ->where('deleted', 0)
    //         ->where('payment', '>', 0)
    //         ->where('classid', null)
    //         ->where('particulars', 'not like', '%DISCOUNT:%')
    //         ->where('particulars', 'not like', '%ADJ:%')
    //         ->where('syid', $selectedschoolyear)
    //         ->whereBetween('createddatetime', [$fiscal_year->stime, $adjusted_etime])
    //         ->whereIn('studid', $studentIds)
    //         ->get();
    
    //     // Create all months in fiscal year period
    //     $start = new DateTime($fiscal_year->stime);
    //     $end = new DateTime($adjusted_etime);
    //     $interval = new DateInterval('P1M');
    //     $period = new DatePeriod($start, $interval, $end);
    
    //     // Initialize monthly totals
    //     $monthlyTotals = [];
    //     foreach ($period as $dt) {
    //         $monthKey = $dt->format('F Y');
    //         $monthlyTotals[$monthKey] = [
    //             'year' => $dt->format('Y'),
    //             'month' => $dt->format('m'),
    //             'total' => 0
    //         ];
    //     }
    
    //     // Group payments by month
    //     foreach ($allPayments as $payment) {
    //         $paymentDate = new DateTime($payment->createddatetime);
    //         $monthKey = $paymentDate->format('F Y');
            
    //         if (isset($monthlyTotals[$monthKey])) {
    //             $monthlyTotals[$monthKey]['total'] += $payment->payment;
    //         }
    //     }
    
    //     // Calculate overall totals
    //     $overalltotalpayment = $allstudents->sum('totalpayment');
    //     $sumMonthlyPayments = array_sum(array_column($monthlyTotals, 'total'));
    
    //     // Adjust for any discrepancy
    //     if (abs($sumMonthlyPayments - $overalltotalpayment) > 0.01) {
    //         $adjustment = $overalltotalpayment - $sumMonthlyPayments;
    //         if (!empty($monthlyTotals)) {
    //             // Apply adjustment to the month with highest payments
    //             usort($monthlyTotals, function($a, $b) {
    //                 return $b['total'] <=> $a['total'];
    //             });
    //             $monthlyTotals[0]['total'] += $adjustment;
    //         }
    //     }
    
    //     // Format for output
    //     $formattedPayments = [];
    //     foreach ($monthlyTotals as $month => $data) {
    //         $formattedPayments[$month] = number_format($data['total'], 2);
    //     }
    
    //     // Return response
    //     if ($request->ajax()) {
    //         return response()->json([
    //             'monthlyPayments' => $formattedPayments,
    //             // ... other response data ...
    //         ]);
    //     }
    
    //     // return view('bookkeeper.pages.home')
    //     //     ->with('monthlyPayments', $formattedPayments)
    //     //     // ... other view data ...
    // }

    // public function filter_acctreceivable_income_expenses(Request $request)
    // {
    //     // Get parameters
    //     $selectedschoolyear = $request->get('selectedschoolyear_income_expenses');
    //     $selectedfiscalyear = $request->get('selectedfiscalyear_income_expenses');
    //     $selecteddaterange = $request->get('selecteddaterange');
    //             $selecteddepartment = $request->get('selecteddepartment');
    //             $selectedgradelevel = $request->get('selectedgradelevel');
    //             $selectedsemester = $request->get('selectedsemester');
    //             $selectedsection = $request->get('selectedsection');
    //             $selectedgrantee = $request->get('selectedgrantee');
    //             $selectedmode = $request->get('selectedmode'); 
    
    //    // Get fiscal year dates
    //    $fiscal_year = DB::table('bk_fiscal_year')
    //    ->select('stime', 'etime')
    //    ->where('id', $selectedfiscalyear)
    //    ->where('deleted', 0)
    //    ->first();

    // if (!$fiscal_year) {
    //     return response()->json(['error' => 'Fiscal year not found'], 404);
    // }

    // // Adjust etime to end of month
    // $etime = new DateTime($fiscal_year->etime);
    // $etime->modify('last day of this month');
    // $adjusted_etime = $etime->format('Y-m-d');

    // // Get filtered students
    // $allstudents = AccountsReceivableModel::allstudents(
    //     $selectedschoolyear, $selecteddaterange, $selecteddepartment, 
    //     $selectedgradelevel, $selectedsemester, $selectedsection, 
    //     $selectedgrantee, $selectedmode
    // );

    // $studentIds = $allstudents->pluck('id')->toArray();

    // // Get payments within fiscal year
    // $allPayments = DB::table('studledger')
    //     ->select('createddatetime', 'payment')
    //     ->where('deleted', 0)
    //     ->where('payment', '>', 0)
    //     ->where('classid', null)
    //     ->where('particulars', 'not like', '%DISCOUNT:%')
    //     ->where('particulars', 'not like', '%ADJ:%')
    //     ->where('syid', $selectedschoolyear)
    //     ->whereBetween('createddatetime', [$fiscal_year->stime, $adjusted_etime])
    //     ->whereIn('studid', $studentIds)
    //     ->get();

    // // Create all months in fiscal year period (month names only)
    // $start = new DateTime($fiscal_year->stime);
    // $end = new DateTime($adjusted_etime);
    // $interval = new DateInterval('P1M');
    // $period = new DatePeriod($start, $interval, $end);

    // // Initialize monthly totals with month names only
    // $monthlyPayments = [];
    // $monthOrder = [];
    // foreach ($period as $dt) {
    //     $monthKey = $dt->format('F'); // Just month name without year
    //     if (!isset($monthlyPayments[$monthKey])) {
    //         $monthlyPayments[$monthKey] = 0;
    //         $monthOrder[] = $monthKey;
    //     }
    // }

    // // Group payments by month name only
    // foreach ($allPayments as $payment) {
    //     $paymentDate = new DateTime($payment->createddatetime);
    //     $monthKey = $paymentDate->format('F'); // Just month name
        
    //     if (isset($monthlyPayments[$monthKey])) {
    //         $monthlyPayments[$monthKey] += $payment->payment;
    //     }
    // }

    // // Reorder according to fiscal year sequence
    // $orderedPayments = [];
    // foreach ($monthOrder as $month) {
    //     $orderedPayments[$month] = $monthlyPayments[$month];
    // }

    // // Calculate overall totals
    // $overalltotalassessment = $allstudents->sum('totalassessment');
    // $overalltotaldiscount = $allstudents->sum('discount');
    // $overalltotalnetassessed = $allstudents->sum('netassessed');
    // $overalltotalpayment = $allstudents->sum('totalpayment');
    // $overalltotalbalance = $allstudents->sum('balance');

    // // Verify sum matches overalltotalpayment
    // $sumMonthlyPayments = array_sum($orderedPayments);
    // if (abs($sumMonthlyPayments - $overalltotalpayment) > 0.01) {
    //     $adjustment = $overalltotalpayment - $sumMonthlyPayments;
    //     if (!empty($orderedPayments)) {
    //         // Apply adjustment to first month with payments
    //         foreach ($orderedPayments as $month => $total) {
    //             if ($total > 0) {
    //                 $orderedPayments[$month] += $adjustment;
    //                 break;
    //             }
    //         }
    //     }
    // }

    // // Format for output
    // $formattedPayments = [];
    // foreach ($orderedPayments as $month => $total) {
    //     $formattedPayments[$month] = number_format($total, 2);
    // }

    // if ($request->ajax()) {
    //     return response()->json([
    //         'monthlyPayments' => $formattedPayments,
    //         'overalltotalpayment' => number_format($overalltotalpayment, 2),
    //         'overalltotalassessment' => number_format($overalltotalassessment, 2),
    //         'overalltotaldiscount' => number_format($overalltotaldiscount, 2),
    //         'overalltotalnetassessed' => number_format($overalltotalnetassessed, 2),
    //         'overalltotalbalance' => number_format($overalltotalbalance, 2),
    //         'students' => $allstudents
    //     ]);
    // }

    // return view('bookkeeper.pages.home')
    //     ->with('monthlyPayments', $formattedPayments)
    //     ->with('overalltotalpayment', $overalltotalpayment)
    //     ->with('overalltotalassessment', $overalltotalassessment)
    //     ->with('overalltotaldiscount', $overalltotaldiscount)
    //     ->with('overalltotalnetassessed', $overalltotalnetassessed)
    //     ->with('overalltotalbalance', $overalltotalbalance)
    //     ->with('students', $allstudents);
    // }

    //working najud ni
    // public function filter_acctreceivable_income_expenses(Request $request)
    // {
    //     $selectedschoolyear = $request->get('selectedschoolyear_income_expenses');
    //     $selectedfiscalyear = $request->get('selectedfiscalyear_income_expenses');
    //     $selectedfiscalyear_text = $request->get('selectedFiscalYearText');

    //     $selecteddaterange = $request->get('selecteddaterange');
    //     $selecteddepartment = $request->get('selecteddepartment');
    //     $selectedgradelevel = $request->get('selectedgradelevel');
    //     $selectedsemester = $request->get('selectedsemester');
    //     $selectedsection = $request->get('selectedsection');
    //     $selectedgrantee = $request->get('selectedgrantee');
    //     $selectedmode = $request->get('selectedmode'); 
    
    //     // Get fiscal year dates
    //     $fiscal_year = DB::table('bk_fiscal_year')
    //         ->select('stime', 'etime')
    //         ->where('id', $selectedfiscalyear)
    //         ->where('deleted', 0)
    //         ->first();
    
    //     if (!$fiscal_year) {
    //         return response()->json(['error' => 'Fiscal year not found'], 404);
    //     }
    
    //     // Get all filtered students
    //     $allstudents = AccountsReceivableModel::allstudents(
    //         $selectedschoolyear, $selecteddaterange, $selecteddepartment, 
    //         $selectedgradelevel, $selectedsemester, $selectedsection, 
    //         $selectedgrantee, $selectedmode
    //     );
    
    //     $studentIds = $allstudents->pluck('id')->toArray();
    
    //     // Get ALL payments for these students
    //     $allPayments = DB::table('studledger')
    //         ->select('createddatetime', 'payment')
    //         ->where('deleted', 0)
    //         ->where('payment', '>', 0)
    //         ->where('classid', null)
    //         ->where('particulars', 'not like', '%DISCOUNT:%')
    //         ->where('particulars', 'not like', '%ADJ:%')
    //         ->where('syid', $selectedschoolyear)
    //         ->whereIn('studid', $studentIds)
    //         ->get();
    
    //     // Create all months in the fiscal year period
    //     $start = new DateTime($fiscal_year->stime);
    //     $end = new DateTime($fiscal_year->etime);
    //     $interval = new DateInterval('P1M');
    //     $period = new DatePeriod($start, $interval, $end);
    
    //     $allMonths = [];
    //     foreach ($period as $dt) {
    //         $monthName = $dt->format('F');
    //         $allMonths[$monthName] = 0; // Initialize all months with 0
    //     }
    
    //     // Group payments by their actual month (prioritizing createddatetime month)
    //     foreach ($allPayments as $payment) {
    //         $date = new DateTime($payment->createddatetime);
    //         $monthName = $date->format('F');
            
    //         // Only add to months that exist in the fiscal year
    //         if (array_key_exists($monthName, $allMonths)) {
    //             $allMonths[$monthName] += $payment->payment;
    //         }
    //     }
    
    //     // Calculate overall totals
    //     $overalltotalassessment = $allstudents->sum('totalassessment');
    //     $overalltotaldiscount = $allstudents->sum('discount');
    //     $overalltotalnetassessed = $allstudents->sum('netassessed');
    //     $overalltotalpayment = $allstudents->sum('totalpayment');
    //     $overalltotalbalance = $allstudents->sum('balance');
    
    //     // Verify sum matches overalltotalpayment
    //     $sumPayments = array_sum($allMonths);
    //     if (abs($sumPayments - $overalltotalpayment) > 0.01) {
    //         // If discrepancy exists, adjust the first month with payment
    //         $adjustment = $overalltotalpayment - $sumPayments;
    //         foreach ($allMonths as $month => $total) {
    //             if ($total > 0) {
    //                 $allMonths[$month] += $adjustment;
    //                 break;
    //             }
    //         }
    //     }
    
    //     // Format the amounts
    //     $formattedPayments = [];
    //     foreach ($allMonths as $month => $total) {
    //         $formattedPayments[$month] = number_format($total, 2);
    //     }
    //     ////////////////////////////////////////////////////////

    //     // $bk_disbursements = DB::table('bk_disbursements')
    //     // ->select('id', 'date', 'disbursement_type', 'voucher_no', 'reference_no', 'amount')
    //     // ->whereYear('date', $selectedfiscalyear_text)
    //     // ->where('deleted', 0)
    //     // ->get();

    //     $fiscal_years = DB::table('bk_fiscal_year')
    //         ->select('stime', 'etime')
    //         ->where('id', $selectedfiscalyear)
    //         ->where('deleted', 0)
    //         ->first();

    //     if (!$fiscal_years) {
    //         return response()->json(['error' => 'Fiscal year not found'], 404);
    //     }

    //     // Create all months in the fiscal year period
    //     $starts = new DateTime($fiscal_years->stime);
    //     $ends = new DateTime($fiscal_years->etime);
    //     $intervals = new DateInterval('P1M');
    //     $periods = new DatePeriod($starts, $intervals, $ends);

    //     $allMonthss = [];
    //     foreach ($periods as $dts) {
    //         $monthNames = $dts->format('F Y');
    //         $allMonthss[$monthNames] = 0; // Initialize all months with 0
    //     }

    //     // Get all disbursements for the fiscal year
    //     $bk_disbursements = DB::table('bk_disbursements')
    //         ->select('date', 'amount')
    //         ->where('date', '>=', $fiscal_year->stime)
    //         ->where('date', '<=', $fiscal_year->etime)
    //         ->where('deleted', 0)
    //         ->get();

    //         // return $bk_disbursements;

    //     // Group disbursements by month
    //     foreach ($bk_disbursements as $disbursement) {
    //         $dates = new DateTime($disbursement->date);
    //         $monthNames = $dates->format('F Y');
            
    //         // Only add to months that exist in the fiscal year
    //         if (array_key_exists($monthNames, $allMonthss)) {
    //             $allMonthss[$monthNames] += $disbursement->amount;
    //         }
    //     }

    //         // return $allMonths;

    
    //     if ($request->ajax()) {
    //         return response()->json([
    //             'overalltotalassessment' => $overalltotalassessment,
    //             'overalltotaldiscount' => $overalltotaldiscount,
    //             'overalltotalnetassessed' => $overalltotalnetassessed,
    //             'overalltotalpayment' => $overalltotalpayment,
    //             'overalltotalbalance' => $overalltotalbalance,
    //             'monthlyPayments' => $formattedPayments,
    //             'bk_disbursements' => $allMonths,
    //         ]);
    //     }
    
    // }
    

    //working code
    // public function filter_acctreceivable_income_expenses(Request $request)
    // {
    //     $selectedschoolyear = $request->get('selectedschoolyear_income_expenses');
    //     $selectedfiscalyear = $request->get('selectedfiscalyear_income_expenses');
    //     $selectedfiscalyear_text = $request->get('selectedFiscalYearText');

    //     $selecteddaterange = $request->get('selecteddaterange');
    //     $selecteddepartment = $request->get('selecteddepartment');
    //     $selectedgradelevel = $request->get('selectedgradelevel');
    //     $selectedsemester = $request->get('selectedsemester');
    //     $selectedsection = $request->get('selectedsection');
    //     $selectedgrantee = $request->get('selectedgrantee');
    //     $selectedmode = $request->get('selectedmode'); 

    //     // Get fiscal year dates
    //     $fiscal_year = DB::table('bk_fiscal_year')
    //         ->select('stime', 'etime')
    //         ->where('id', $selectedfiscalyear)
    //         ->where('deleted', 0)
    //         ->first();

    //     if (!$fiscal_year) {
    //         return response()->json(['error' => 'Fiscal year not found'], 404);
    //     }

    //     // Get all filtered students
    //     $allstudents = AccountsReceivableModel::allstudents(
    //         $selectedschoolyear, $selecteddaterange, $selecteddepartment, 
    //         $selectedgradelevel, $selectedsemester, $selectedsection, 
    //         $selectedgrantee, $selectedmode
    //     );

    //     $studentIds = $allstudents->pluck('id')->toArray();

    //     // Get ALL payments for these students
    //     $allPayments = DB::table('studledger')
    //         ->select('createddatetime', 'payment')
    //         ->where('deleted', 0)
    //         ->where('payment', '>', 0)
    //         ->where('classid', null)
    //         ->where('particulars', 'not like', '%DISCOUNT:%')
    //         ->where('particulars', 'not like', '%ADJ:%')
    //         ->where('syid', $selectedschoolyear)
    //         ->whereIn('studid', $studentIds)
    //         ->get();

    //     // Create all months in the fiscal year period
    //     $start = new DateTime($fiscal_year->stime);
    //     $end = new DateTime($fiscal_year->etime);
    //     $interval = new DateInterval('P1M');
    //     $period = new DatePeriod($start, $interval, $end);

    //     $allMonths = [];
    //     foreach ($period as $dt) {
    //         $monthName = $dt->format('F');
    //         $allMonths[$monthName] = 0; // Initialize all months with 0
    //     }

    //     // Group payments by their actual month (prioritizing createddatetime month)
    //     foreach ($allPayments as $payment) {
    //         $date = new DateTime($payment->createddatetime);
    //         $monthName = $date->format('F');
            
    //         // Only add to months that exist in the fiscal year
    //         if (array_key_exists($monthName, $allMonths)) {
    //             $allMonths[$monthName] += $payment->payment;
    //         }
    //     }

    //     // Calculate overall totals
    //     $overalltotalassessment = $allstudents->sum('totalassessment');
    //     $overalltotaldiscount = $allstudents->sum('discount');
    //     $overalltotalnetassessed = $allstudents->sum('netassessed');
    //     $overalltotalpayment = $allstudents->sum('totalpayment');
    //     $overalltotalbalance = $allstudents->sum('balance');

    //     // Verify sum matches overalltotalpayment
    //     $sumPayments = array_sum($allMonths);
    //     if (abs($sumPayments - $overalltotalpayment) > 0.01) {
    //         // If discrepancy exists, adjust the first month with payment
    //         $adjustment = $overalltotalpayment - $sumPayments;
    //         foreach ($allMonths as $month => $total) {
    //             if ($total > 0) {
    //                 $allMonths[$month] += $adjustment;
    //                 break;
    //             }
    //         }
    //     }

    //     // Format the amounts
    //     $formattedPayments = [];
    //     foreach ($allMonths as $month => $total) {
    //         $formattedPayments[$month] = number_format($total, 2);
    //     }

    //     // Process disbursements
    //     $fiscal_years = DB::table('bk_fiscal_year')
    //         ->select('stime', 'etime')
    //         ->where('id', $selectedfiscalyear)
    //         ->where('deleted', 0)
    //         ->first();

    //     if (!$fiscal_years) {
    //         return response()->json(['error' => 'Fiscal year not found'], 404);
    //     }

    //     // Create all months in the fiscal year period for disbursements
    //     $starts = new DateTime($fiscal_years->stime);
    //     $ends = new DateTime($fiscal_years->etime);
    //     $intervals = new DateInterval('P1M');
    //     $periods = new DatePeriod($starts, $intervals, $ends);

    //     $disbursementsByMonth = [];
    //     foreach ($periods as $dts) {
    //         $monthNames = $dts->format('F');
    //         $disbursementsByMonth[$monthNames] = 0; // Initialize all months with 0
    //     }

    //     // Get all disbursements for the fiscal year
    //     $bk_disbursements = DB::table('bk_disbursements')
    //         ->select('date', 'amount')
    //         ->where('date', '>=', $fiscal_year->stime)
    //         ->where('date', '<=', $fiscal_year->etime)
    //         ->where('deleted', 0)
    //         ->get();

    //     // Group disbursements by month
    //     foreach ($bk_disbursements as $disbursement) {
    //         $dates = new DateTime($disbursement->date);
    //         // $monthNames = $dates->format('F Y');
    //         $monthNames = $dates->format('F');
            
    //         // Only add to months that exist in the fiscal year
    //         if (array_key_exists($monthNames, $disbursementsByMonth)) {
    //             $disbursementsByMonth[$monthNames] += $disbursement->amount;
    //         }
    //     }

    //     // Format disbursement amounts
    //     $formattedDisbursements = [];
    //     foreach ($disbursementsByMonth as $month => $total) {
    //         $formattedDisbursements[$month] = number_format($total, 2);
    //     }

    //     // Calculate overall monthly disbursement
    //     $overallmonthlydisbursement = array_sum($disbursementsByMonth);

    //     if ($request->ajax()) {
    //         return response()->json([
    //             'overalltotalassessment' => $overalltotalassessment,
    //             'overalltotaldiscount' => $overalltotaldiscount,
    //             'overalltotalnetassessed' => $overalltotalnetassessed,
    //             'overalltotalpayment' => $overalltotalpayment,
    //             'overalltotalbalance' => $overalltotalbalance,
    //             'monthlyPayments' => $formattedPayments,
    //             'monthlyDisbursements' => $formattedDisbursements,
    //             'overallmonthlydisbursement' => number_format($overallmonthlydisbursement, 2),
    //         ]);
    //     }
    // }

    public function filter_acctreceivable_income_expenses(Request $request)
    {
        $selectedschoolyear = $request->get('selectedschoolyear_income_expenses');
        $selectedfiscalyear = $request->get('selectedfiscalyear_income_expenses');
        $selectedfiscalyear_text = $request->get('selectedFiscalYearText');

        $selecteddaterange = $request->get('selecteddaterange');
        $selecteddepartment = $request->get('selecteddepartment');
        $selectedgradelevel = $request->get('selectedgradelevel');
        $selectedsemester = $request->get('selectedsemester');
        $selectedsection = $request->get('selectedsection');
        $selectedgrantee = $request->get('selectedgrantee');
        $selectedmode = $request->get('selectedmode'); 

        // Get fiscal year dates
        $fiscal_year = DB::table('bk_fiscal_year')
            ->select('stime', 'etime')
            ->where('id', $selectedfiscalyear)
            ->where('deleted', 0)
            ->first();

        if (!$fiscal_year) {
            return response()->json(['error' => 'Fiscal year not found'], 404);
        }

        // Get all filtered students
        $allstudents = AccountsReceivableModel::allstudents(
            $selectedschoolyear, $selecteddaterange, $selecteddepartment, 
            $selectedgradelevel, $selectedsemester, $selectedsection, 
            $selectedgrantee, $selectedmode
        );

        $studentIds = $allstudents->pluck('id')->toArray();

        // Get ALL payments for these students
        $allPayments = DB::table('studledger')
            ->select('createddatetime', 'payment')
            ->where('deleted', 0)
            ->where('payment', '>', 0)
            ->where('classid', null)
            ->where('particulars', 'not like', '%DISCOUNT:%')
            ->where('particulars', 'not like', '%ADJ:%')
            ->where('syid', $selectedschoolyear)
            ->whereIn('studid', $studentIds)
            ->get();

        // Create all months in the fiscal year period
        $start = new DateTime($fiscal_year->stime);
        $end = new DateTime($fiscal_year->etime);
        $interval = new DateInterval('P1M');
        $period = new DatePeriod($start, $interval, $end);

        $allMonths = [];
        foreach ($period as $dt) {
            $monthName = $dt->format('F');
            $allMonths[$monthName] = 0; // Initialize all months with 0
        }

        // Group payments by their actual month (prioritizing createddatetime month)
        foreach ($allPayments as $payment) {
            $date = new DateTime($payment->createddatetime);
            $monthName = $date->format('F');
            
            // Only add to months that exist in the fiscal year
            if (array_key_exists($monthName, $allMonths)) {
                $allMonths[$monthName] += $payment->payment;
            }
        }

        // Calculate overall totals
        $overalltotalassessment = $allstudents->sum('totalassessment');
        $overalltotaldiscount = $allstudents->sum('discount');
        $overalltotalnetassessed = $allstudents->sum('netassessed');
        $overalltotalpayment = $allstudents->sum('totalpayment');
        $overalltotalbalance = $allstudents->sum('balance');

        // Verify sum matches overalltotalpayment
        $sumPayments = array_sum($allMonths);
        if (abs($sumPayments - $overalltotalpayment) > 0.01) {
            // If discrepancy exists, adjust the first month with payment
            $adjustment = $overalltotalpayment - $sumPayments;
            foreach ($allMonths as $month => $total) {
                if ($total > 0) {
                    $allMonths[$month] += $adjustment;
                    break;
                }
            }
        }

        // Format the amounts
        $formattedPayments = [];
        foreach ($allMonths as $month => $total) {
            $formattedPayments[$month] = number_format($total, 2);
        }

        // Process disbursements
        $fiscal_years = DB::table('bk_fiscal_year')
            ->select('stime', 'etime')
            ->where('id', $selectedfiscalyear)
            ->where('deleted', 0)
            ->first();

        if (!$fiscal_years) {
            return response()->json(['error' => 'Fiscal year not found'], 404);
        }

        // Create all months in the fiscal year period for disbursements
        $starts = new DateTime($fiscal_years->stime);
        $ends = new DateTime($fiscal_years->etime);
        $intervals = new DateInterval('P1M');
        $periods = new DatePeriod($starts, $intervals, $ends);

        $disbursementsByMonth = [];
        foreach ($periods as $dts) {
            $monthNames = $dts->format('F');
            $disbursementsByMonth[$monthNames] = 0; // Initialize all months with 0
        }

        // Get all disbursements for the fiscal year
        $bk_disbursements = DB::table('bk_disbursements')
            ->select('date', 'amount')
            ->where('date', '>=', $fiscal_year->stime)
            ->where('date', '<=', $fiscal_year->etime)
            ->where('deleted', 0)
            ->get();

        // Group disbursements by month
        foreach ($bk_disbursements as $disbursement) {
            $dates = new DateTime($disbursement->date);
            // $monthNames = $dates->format('F Y');
            $monthNames = $dates->format('F');
            
            // Only add to months that exist in the fiscal year
            if (array_key_exists($monthNames, $disbursementsByMonth)) {
                $disbursementsByMonth[$monthNames] += $disbursement->amount;
            }
        }

        // Format disbursement amounts
        $formattedDisbursements = [];
        foreach ($disbursementsByMonth as $month => $total) {
            $formattedDisbursements[$month] = number_format($total, 2);
        }

        // Calculate overall monthly disbursement
        $overallmonthlydisbursement = array_sum($disbursementsByMonth);

        if ($request->ajax()) {
            return response()->json([
                'overalltotalassessment' => $overalltotalassessment,
                'overalltotaldiscount' => $overalltotaldiscount,
                'overalltotalnetassessed' => $overalltotalnetassessed,
                'overalltotalpayment' => $overalltotalpayment,
                'overalltotalbalance' => $overalltotalbalance,
                'monthlyPayments' => $formattedPayments,
                'monthlyDisbursements' => $formattedDisbursements,
                'overallmonthlydisbursement' => number_format($overallmonthlydisbursement, 2),
            ]);
        }
    }

    public function updateDepreciation(Request $request)
    {
        $selectedId = $request->id;
        DB::table('bk_depreciation')->where('id', $selectedId)->update(['isActive' => 1]);

        // Set all others to inactive
        DB::table('bk_depreciation')->where('id', '!=', $selectedId)->update(['isActive' => 0]);

        return response()->json(['success' => true]);
    }

    public function filter_top_expenses(Request $request)
    {
        $selectedschoolyear = $request->get('selectedschoolyear_income_expenses');
        $selectedfiscalyear = $request->get('selectedfiscalyear_income_expenses');
        $selectedfiscalyear_text = $request->get('selectedFiscalYearText');

        // sum('bk_disbursements.amount')
        $result = DB::table('bk_disbursements')
            ->join('bk_generalledg', 'bk_disbursements.voucher_no', '=', 'bk_generalledg.voucherNo')
            ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
            ->where('bk_generalledg.active_fiscal_year_id', $selectedfiscalyear)
            ->where('bk_generalledg.debit_amount', '>', 0)
            ->where('bk_generalledg.credit_amount', '=', 0)
            ->select('chart_of_accounts.account_name', DB::raw('SUM(bk_disbursements.amount) as total_amount'))
            ->groupBy('chart_of_accounts.account_name')
            ->orderBy('total_amount', 'desc')
            ->take(3)
            ->get();
     

        return response()->json
        ([
            'result' => $result,
        ]);
       
    }

    public function all_expenses_items(Request $request)
    {
        $expenses_items = DB::table('bk_expenses_items')
            ->where('deleted', 0)
            ->get();

        return response()->json($expenses_items);
    }

    public static function setActive_cashierJE(Request $request)
    {
        // Retrieve and validate inputs
        $debit_account = $request->input('debit_account');
  
        $activeFiscal = DB::table('bk_fiscal_year')->where('isactive', 1)->first();
        $now = Carbon::now();
        $updatedBy = auth()->id();

        if ($activeFiscal) {
            $existRecords = DB::table('bk_generalledg')
                ->where('active_fiscal_year_id', $activeFiscal->id)
                ->where('remarks', 'like', '%- Payments')
                ->where('debit_amount', '!=', 0.00)
                ->get();

            foreach ($existRecords as $record) {
                DB::table('bk_generalledg')
                    ->where('id', $record->id)
                    ->update([
                        'coaid' => $debit_account,
                        'updateddatetime' => $now,
                        'updatedby' => $updatedBy
                    ]);
            }
        }

        DB::table('chart_of_accounts')->where('id', '!=', $debit_account)->update([
            'cashierje_isctive' => 0,
        ]);

        DB::table('bk_sub_chart_of_accounts')->where('id', '!=', $debit_account)->update([
            'cashierje_isctive' => 0,
        ]);

        $exists = DB::table('chart_of_accounts')->where('id', $debit_account)->exists();

        if ($exists) {
            DB::table('chart_of_accounts')->where('id', $debit_account)->update([
                'cashierje_isctive' => 1,
            ]);
        } else {
            DB::table('bk_sub_chart_of_accounts')->where('id', $debit_account)->update([
                'cashierje_isctive' => 1,
            ]);
        }
        
        return response()->json([
            ['status' => 1, 'message' => 'Cashier JE Updated Successfully']
        ]);
    }

    public function fetch_active_cashierJE(Request $request)
    {
        $setactive_jeacc = DB::table('chart_of_accounts')
            ->where('deleted', 0)
            ->where('cashierje_isctive', 1)
            ->select('id')
            ->first();

        if (empty($setactive_jeacc)) {
            $setactive_jeacc = DB::table('bk_sub_chart_of_accounts')
                ->where('deleted', 0)
                ->where('cashierje_isctive', 1)
                ->select('id')
                ->first();
        }

        $all_jeacc = DB::table('chart_of_accounts')
            ->where('deleted', 0)
            ->get();

        $all_subjeacc = DB::table('bk_sub_chart_of_accounts')
            ->where('deleted', 0)
            ->get();
        

        return response()->json([
            'active_cashierJE' => $setactive_jeacc,
            'all_cashierJE' => $all_jeacc,
            'all_subjeacc' => $all_subjeacc
        ]);
    }
    public static function edit_enrollment_setup(Request $request) {
        $enrollmentSetup_academicId = $request->get('enrollmentSetup_academicId');
    
        $classified = DB::table('bk_classifiedsetup')
        ->leftJoin('gradelevel', 'bk_classifiedsetup.levelid', '=', 'gradelevel.id')
        ->leftJoin('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
        ->leftJoin('itemclassification', 'bk_classifiedsetup.classid', '=', 'itemclassification.id')
        ->leftJoin('chart_of_accounts as debitacc', 'bk_classifiedsetup.debitaccid', '=', 'debitacc.id')
        ->leftJoin('chart_of_accounts as creditacc', 'bk_classifiedsetup.creditaccid', '=', 'creditacc.id')
        ->leftJoin('chart_of_accounts as cashier_debitacc', 'bk_classifiedsetup.payment_debitaccid', '=', 'cashier_debitacc.id')
        // Modified sub-account joins to properly link to the classified setup table
        ->leftJoin('bk_sub_chart_of_accounts as subdebitacc', function($join) {
            $join->on('bk_classifiedsetup.debitaccid', '=', 'subdebitacc.id');
                
        })
        ->leftJoin('bk_sub_chart_of_accounts as subcreditacc', function($join) {
            $join->on('bk_classifiedsetup.creditaccid', '=', 'subcreditacc.id');
               
        })
        ->leftJoin('bk_sub_chart_of_accounts as subcashier_debitacc', function($join) {
            $join->on('bk_classifiedsetup.payment_debitaccid', '=', 'subcashier_debitacc.id');
                
        })
        ->select(
              'bk_classifiedsetup.id',
              'academicprogram.id as acadprogid',
              'academicprogram.progname',
              'gradelevel.levelname',
              'gradelevel.id as levelid',
              'itemclassification.description',
    
              // Modified CASE statements to properly check for sub-accounts
              DB::raw('CASE 
                  WHEN bk_classifiedsetup.debitaccid IS NULL THEN NULL
                  WHEN subdebitacc.id IS NOT NULL THEN subdebitacc.sub_account_name 
                  ELSE debitacc.account_name 
              END AS debitacc'),
              
              DB::raw('CASE 
                  WHEN bk_classifiedsetup.creditaccid IS NULL THEN NULL
                  WHEN subcreditacc.id IS NOT NULL THEN subcreditacc.sub_account_name 
                  ELSE creditacc.account_name 
              END AS creditacc'),
              
              DB::raw('CASE 
                  WHEN bk_classifiedsetup.payment_debitaccid IS NULL THEN NULL
                  WHEN subcashier_debitacc.id IS NOT NULL THEN subcashier_debitacc.sub_account_name 
                  ELSE cashier_debitacc.account_name 
              END AS cashier_debitacc'),
    
              'bk_classifiedsetup.classid',
              'bk_classifiedsetup.debitaccid',
              'bk_classifiedsetup.creditaccid',
              'bk_classifiedsetup.payment_debitaccid'
        )
        // ->where('academicprogram.id', $enrollmentSetup_academicId)
        ->where('bk_classifiedsetup.id', $enrollmentSetup_academicId)
        ->where(function($query) {
            $query->where('bk_classifiedsetup.deleted', 0)
                  ->orWhereNull('bk_classifiedsetup.deleted');
        })
        ->groupBy('bk_classifiedsetup.classid')
        ->get();
    
        $chart_of_accounts = DB::table('chart_of_accounts')
        ->where('deleted',0)
        ->get();
    
        $bk_sub_chart_of_accounts = DB::table('bk_sub_chart_of_accounts')
        ->where('deleted',0)
        ->get();
    
        $itemclassification = DB::table('itemclassification')
        ->where('deleted',0)
        ->get();
    
        return response()->json([
            'classified' => $classified,
            'chart_of_accounts' => $chart_of_accounts,
            'itemclassification' => $itemclassification,
            'sub_chart_of_accounts' => $bk_sub_chart_of_accounts
        ]);
    }

    // public static function edit_enrollment_setup(Request $request){

    //     $enrollmentSetup_academicId = $request->get('enrollmentSetup_academicId');

    //     $classified = DB::table('bk_classifiedsetup')
    //     ->leftJoin('gradelevel', 'bk_classifiedsetup.levelid', '=', 'gradelevel.id')
    //     ->leftJoin('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
    //     ->leftJoin('itemclassification', 'bk_classifiedsetup.classid', '=', 'itemclassification.id')
    //     ->leftJoin('chart_of_accounts as debitacc', 'bk_classifiedsetup.debitaccid', '=', 'debitacc.id')
    //     ->leftJoin('chart_of_accounts as creditacc', 'bk_classifiedsetup.creditaccid', '=', 'creditacc.id')
    //     ->leftJoin('chart_of_accounts as cashier_debitacc', 'bk_classifiedsetup.payment_debitaccid', '=', 'cashier_debitacc.id')
    //     ->select(
    //           'bk_classifiedsetup.id',
    //           'academicprogram.id as acadprogid',
    //           'academicprogram.progname',
    //           'gradelevel.levelname',
    //           'gradelevel.id as levelid',
    //           'itemclassification.description',
    //           'debitacc.account_name as debitacc',
    //           'creditacc.account_name as creditacc',
              
    //           'cashier_debitacc.account_name as cashier_debitacc',
    //           'bk_classifiedsetup.classid'
    //     )
    //     ->where('academicprogram.id', $enrollmentSetup_academicId)
    //     ->where(function($query) {
    //         $query->where('bk_classifiedsetup.deleted', 0)
    //               ->orWhereNull('bk_classifiedsetup.deleted');
    //     })
    //     ->groupBy('bk_classifiedsetup.classid')
    //     ->get();

    //     $chart_of_accounts = DB::table('chart_of_accounts')
    //     ->where('deleted',0)
    //     ->get();


    //     $bk_sub_chart_of_accounts = DB::table('bk_sub_chart_of_accounts')
    //     ->where('deleted',0)
    //     ->get();

    //     $itemclassification = DB::table('itemclassification')
    //     ->where('deleted',0)
    //     ->get();


    //     return response()->json([
    //         'classified' => $classified,
    //         'chart_of_accounts' => $chart_of_accounts,
    //         'itemclassification' => $itemclassification,
    //         'sub_chart_of_accounts' => $bk_sub_chart_of_accounts
    //     ]);
    // }

    // public function enrollment_setup_update(Request $request)
    // {
        
    //     // DB::table('teacher')
    //     // ->where('id', $request->get('id'))
        
    //     // ->update([
    //     //     'picurl' => $filePath,
    //     //     'firstname' => $request->get('employee_firstname'),
    //     //     'lastname' => $request->get('employee_lastname'),
    //     //     'middlename' => $request->get('employee_middlename'),
    //     //     'phonenumber' => $request->get('employee_cellphone'),
    //     //     'email' => $request->get('employee_email'),
    //     //     'residentaddr' => $request->get('employee_address'),
    //     //     'rfid' => $request->get('employee_rfid')
    //     // ]);



    // if ($request->has('classifications') && is_array($request->classifications)) {
    //     // return $request->highestEducations; 
    //     foreach ($request->classifications as $classificationAccountData) {
    //         $exists = DB::table('bk_classifiedsetup')
    //             // ->where('employeeid', $request->get('classid'))
    //             ->where('id', $classificationAccountData['classified_setupid'])
    //             ->exists();
    
    //         if ($exists) {
    //             DB::table('bk_classifiedsetup')
    //                 // ->where('id', $request->get('classified_setupid'))
    //                 ->where('id', $classificationAccountData['classified_setupid'])
    //                 ->update([
    //                     'classid' => $classificationAccountData['classacc'],
    //                     'debitaccid' => $classificationAccountData['debitacc'] ,
    //                     'creditaccid' => $classificationAccountData['creditacc'] ,
    //                     'updateddatetime' => now()
    //                 ]);
    //         }
    //          else {
    //             DB::table('bk_classifiedsetup')
    //                 ->insert([
    //                     'classid' => $classificationAccountData['classacc'],
    //                     'levelid' => $classificationAccountData['classified_setup_levelid'],
    //                     'acadprogid' => $classificationAccountData['classified_setup_acadprogid'],
    //                     'debitaccid' => $classificationAccountData['debitacc'] ,
    //                     'creditaccid' => $classificationAccountData['creditacc'] ,
    //                     'createddatetime' => now()
    //                 ]);
    //         }
    //     }
    // }

    // return response()->json([
    //     'status' => 1,
    //     'message' => 'Enrollment Updated Successfully'
    // ]);
        

    // }

    // public function enrollment_setup_update(Request $request)
    // {
        
    //     // DB::table('teacher')
    //     // ->where('id', $request->get('id'))
        
    //     // ->update([
    //     //     'picurl' => $filePath,
    //     //     'firstname' => $request->get('employee_firstname'),
    //     //     'lastname' => $request->get('employee_lastname'),
    //     //     'middlename' => $request->get('employee_middlename'),
    //     //     'phonenumber' => $request->get('employee_cellphone'),
    //     //     'email' => $request->get('employee_email'),
    //     //     'residentaddr' => $request->get('employee_address'),
    //     //     'rfid' => $request->get('employee_rfid')
    //     // ]);



    // if ($request->has('classifications') && is_array($request->classifications)) {
    //     // return $request->highestEducations; 
    //     foreach ($request->classifications as $classificationAccountData) {
    //         $exists = DB::table('bk_classifiedsetup')
    //             // ->where('employeeid', $request->get('classid'))
    //             ->where('id', $classificationAccountData['classified_setupid'])
    //             ->exists();
    
    //         if ($exists) {
    //             DB::table('bk_classifiedsetup')
    //                 // ->where('id', $request->get('classified_setupid'))
    //                 ->where('id', $classificationAccountData['classified_setupid'])
    //                 ->update([
    //                     'classid' => $classificationAccountData['classacc'],
    //                     'debitaccid' => $classificationAccountData['debitacc'] ,
    //                     'creditaccid' => $classificationAccountData['creditacc'] ,
    //                     'payment_debitaccid' => $request->cashier_debit_account,
    //                     'updateddatetime' => now()
    //                 ]);
    //         }
    //          else {
    //             // $allLevels = DB::table('gradelevel')->select('id', 'acadprogid')->where('acadprogid', $request->acadprogid)->get();
    //             // foreach ($allLevels as $level) {
    //             //     DB::table('bk_classifiedsetup')->insert([
    //             //         'levelid' => $level->id,
    //             //         'classid' => $classificationAccountData['classacc'],
    //             //         'acadprogid' => $request->acadprogid,
    //             //         'debitaccid' => $classificationAccountData['debitacc'] ,
    //             //         'creditaccid' => $classificationAccountData['creditacc'] ,
    //             //         'payment_debitaccid' => $request->cashier_debit_account,
    //             //         // 'payment_debitaccid' => $classificationAccountData['payment_debitacc'],
    //             //         // 'createdby' => auth()->user()->id,
    //             //         'createddatetime'  => now()
    //             //     ]);
    //             // }
    //             // $allLevels = DB::table('gradelevel')->select('id', 'acadprogid')->where('acadprogid', $request->acadprogid)->get();
    //             //   foreach ($allLevels as $level) {
    //                 DB::table('bk_classifiedsetup')->insert([
    //                     'levelid' =>  $classificationAccountData['classified_setup_levelid'],
    //                     'classid' => $classificationAccountData['classacc'],
    //                     'acadprogid' =>  $classificationAccountData['classified_setup_acadprogid'],
    //                     'debitaccid' => $classificationAccountData['debitacc'] ,
    //                     'creditaccid' => $classificationAccountData['creditacc'] ,
    //                     'payment_debitaccid' => $request->cashier_debit_account,
    //                     // 'payment_debitaccid' => $classificationAccountData['payment_debitacc'],
    //                     // 'createdby' => auth()->user()->id,
    //                     'createddatetime'  => now()
    //                 ]);
    //             // }
    //         }
    //     }
    // }

    // return response()->json([
    //     'status' => 1,
    //     'message' => 'Enrollment Updated Successfully'
    // ]);
        

    // }

    // public function enrollment_setup_update(Request $request)
    // {
    //     if ($request->has('classifications') && is_array($request->classifications)) {
    //         foreach ($request->classifications as $classificationAccountData) {
    //             if (isset($classificationAccountData['classified_setupid']) && isset($classificationAccountData['classified_setup_levelid'])
    //                 && isset($classificationAccountData['classified_setup_acadprogid']) && isset($classificationAccountData['classacc'])
    //                 && isset($classificationAccountData['debitacc']) && isset($classificationAccountData['creditacc'])
    //                 && isset($request->cashier_debit_account)) {
    //                 $exists = DB::table('bk_classifiedsetup')
    //                     ->where('id', $classificationAccountData['classified_setupid'])
    //                     ->exists();

    //                 if ($exists) {
    //                     DB::table('bk_classifiedsetup')
    //                         ->where('id', $classificationAccountData['classified_setupid'])
    //                         ->update([
    //                             'classid' => $classificationAccountData['classacc'],
    //                             'debitaccid' => $classificationAccountData['debitacc'] ,
    //                             'creditaccid' => $classificationAccountData['creditacc'] ,
    //                             'payment_debitaccid' => $request->cashier_debit_account,
    //                             'updateddatetime' => now()
    //                         ]);
    //                 } else {
    //                     DB::table('bk_classifiedsetup')->insert([
    //                         'levelid' =>  $classificationAccountData['classified_setup_levelid'],
    //                         'classid' => $classificationAccountData['classacc'],
    //                         'acadprogid' =>  $classificationAccountData['classified_setup_acadprogid'],
    //                         'debitaccid' => $classificationAccountData['debitacc'] ,
    //                         'creditaccid' => $classificationAccountData['creditacc'] ,
    //                         'payment_debitaccid' => $request->cashier_debit_account,
    //                         // 'payment_debitaccid' => $classificationAccountData['payment_debitacc'],
    //                         // 'createdby' => auth()->user()->id,
    //                         'createddatetime'  => now()
    //                     ]);
    //                 }
    //             }
    //         }
    //     }

    //     return response()->json([
    //         'status' => 1,
    //         'message' => 'Enrollment Updated Successfully'
    //     ]);
    // }

    // public function enrollment_setup_update(Request $request)
    // {
    //     // if ($request->has('classifications') && is_array($request->classifications)) {
    //     //     foreach ($request->classifications as $classificationAccountData) {
    //     //         // Skip if any required field is missing or empty
    //     //         if (empty($classificationAccountData['classified_setup_levelid']) || 
    //     //             empty($classificationAccountData['classified_setup_acadprogid']) || 
    //     //             empty($classificationAccountData['classacc']) || 
    //     //             empty($classificationAccountData['debitacc']) || 
    //     //             empty($classificationAccountData['creditacc']) || 
    //     //             empty($request->cashier_debit_account)) {
    //     //             continue;
    //     //         }

    //     //         $data = [
    //     //             'levelid' => $classificationAccountData['classified_setup_levelid'],
    //     //             'classid' => $classificationAccountData['classacc'],
    //     //             'acadprogid' => $classificationAccountData['classified_setup_acadprogid'],
    //     //             'debitaccid' => $classificationAccountData['debitacc'],
    //     //             'creditaccid' => $classificationAccountData['creditacc'],
    //     //             'payment_debitaccid' => $request->cashier_debit_account,
    //     //             'updateddatetime' => now()
    //     //         ];

    //     //         if (!empty($classificationAccountData['classified_setupid'])) {
    //     //             // Update existing record
    //     //             if (empty($classificationAccountData['classid']) && !empty($classificationAccountData['classified_setupid'])) {
    //     //                 DB::table('bk_classifiedsetup')
    //     //                     ->where('id', $classificationAccountData['classified_setupid'])
    //     //                     ->update($data);
    //     //             } 
    //     //         }
                
    //     //         else {
    //     //             // Insert new record
    //     //             $data['createddatetime'] = now();
    //     //             // $data['createdby'] = auth()->user()->id; // Uncomment if needed
    //     //             DB::table('bk_classifiedsetup')->insert($data);
    //     //         }
    //     //     }
    //     // }

    //     if ($request->has('classifications') && is_array($request->classifications)) {
    //         foreach ($request->classifications as $classificationAccountData) {
    //             // Skip if any required field is missing or empty
    //             if (empty($classificationAccountData['classified_setup_levelid']) || 
    //                 empty($classificationAccountData['classified_setup_acadprogid']) || 
    //                 empty($classificationAccountData['classacc']) || 
    //                 empty($classificationAccountData['debitacc']) || 
    //                 empty($classificationAccountData['creditacc']) || 
    //                 empty($request->cashier_debit_account)) {
    //                 continue;
    //             }

    //             $data = [
    //                 'levelid' => $classificationAccountData['classified_setup_levelid'],
    //                 'classid' => $classificationAccountData['classacc'],
    //                 'acadprogid' => $classificationAccountData['classified_setup_acadprogid'],
    //                 'debitaccid' => $classificationAccountData['debitacc'],
    //                 'creditaccid' => $classificationAccountData['creditacc'],
    //                 'payment_debitaccid' => $request->cashier_debit_account,
    //                 'updateddatetime' => now()
    //             ];

    //             if (isset($classificationAccountData['classid'])) {
    //                 $data['classid'] = $classificationAccountData['classid'];
    //             }

    //             if (!empty($classificationAccountData['classified_setupid'])) {
    //                 // Update existing record
    //                 DB::table('bk_classifiedsetup')
    //                     ->where('id', $classificationAccountData['classified_setupid'])
    //                     ->update($data);
    //             } 
    //             else {
    //                 // Insert new record
    //                 $data['createddatetime'] = now();
    //                 // $data['createdby'] = auth()->user()->id; // Uncomment if needed
    //                 DB::table('bk_classifiedsetup')->insert($data);
    //             }
    //         }
    //     }



    //     return response()->json([
    //         'status' => 1,
    //         'message' => 'Enrollment Updated Successfully'
    //     ]);
    // }

    public function enrollment_setup_update(Request $request)
{
    if ($request->has('classifications') && is_array($request->classifications)) {
        foreach ($request->classifications as $classificationAccountData) {
            // Skip if any required field is missing or empty (except 'classacc')
            if (empty($classificationAccountData['classified_setup_levelid']) || 
                empty($classificationAccountData['classified_setup_acadprogid']) || 
                empty($classificationAccountData['debitacc']) || 
                empty($classificationAccountData['creditacc']) || 
                empty($request->cashier_debit_account)) {
                continue;
            }

            $data = [
                'levelid' => $classificationAccountData['classified_setup_levelid'],
                'acadprogid' => $classificationAccountData['classified_setup_acadprogid'],
                'debitaccid' => $classificationAccountData['debitacc'],
                'creditaccid' => $classificationAccountData['creditacc'],
                'payment_debitaccid' => $request->cashier_debit_account,
                'updateddatetime' => now()
            ];

            // Only set classid if classacc is not empty
            if (!empty($classificationAccountData['classacc'])) {
                $data['classid'] = $classificationAccountData['classacc'];
            }

            if (isset($classificationAccountData['classid'])) {
                $data['classid'] = $classificationAccountData['classid'];
            }

            if (!empty($classificationAccountData['classified_setupid'])) {
                // Update existing record
                DB::table('bk_classifiedsetup')
                    ->where('id', $classificationAccountData['classified_setupid'])
                    ->update($data);
            } 
            else {
                // Insert new record
                $data['createddatetime'] = now();
                // $data['createdby'] = auth()->user()->id; // Uncomment if needed
                DB::table('bk_classifiedsetup')->insert($data);
            }
        }
    }

    return response()->json([
        'status' => 1,
        'message' => 'Enrollment Updated Successfully'
    ]);
}

    public static function enrollment_setup_delete(Request $request)
    {
        $classified_setupid = $request->get('classified_setupid');
        $classified_acadprogid = $request->get('classified_acadprogid');
        $classified_levelid = $request->get('classified_levelid');

        if (is_null($classified_setupid) || empty($classified_setupid)) {
            DB::table('bk_classifiedsetup')
                ->where('acadprogid', $classified_acadprogid)
                ->where('levelid', $classified_levelid)
                ->update([
                    'deleted' => 1,
                    'deleteddatetime' => now()
                ]);
        } else {
            DB::table('bk_classifiedsetup')
                ->where('classid', $classified_setupid)
                ->where('acadprogid', $classified_acadprogid)
                ->update([
                    'deleted' => 1,
                    'deleteddatetime' => now()
                ]);
        }

        return array(
            (object) [
                'status' => 1,
                'message' => 'Deleted Successfully!',
            ]
        );
    }

    // public static function setActive_discountJE(Request $request)
    // {
    //     // Retrieve and validate inputs
    //     $discount_debit_account_id = $request->input('discount_debit_account_id');

    //     // Set all accounts and sub accounts to inactive except the selected one
    //     DB::table('chart_of_accounts')->where('id', '!=', $discount_debit_account_id)->update([
    //         'discountje_isctive' => 0,
    //     ]);

    //     DB::table('bk_sub_chart_of_accounts')->where('id', '!=', $discount_debit_account_id)->update([
    //         'discountje_isctive' => 0,
    //     ]);

    //     $exists = DB::table('chart_of_accounts')->where('id', $discount_debit_account_id)->exists();

    //     if ($exists) {
    //         DB::table('chart_of_accounts')->where('id', $discount_debit_account_id)->update([
    //             'discountje_isctive' => 1,
    //         ]);
    //     } else {
    //         DB::table('bk_sub_chart_of_accounts')->where('id', $discount_debit_account_id)->update([
    //             'discountje_isctive' => 1,
    //         ]);
    //     }

    //     return response()->json([
    //         ['status' => 1, 'message' => 'Discount JE Updated Successfully']
    //     ]);
    // }

    // public function setActive_discountJE(Request $request)
    // {
    //     $discount_debit_account_id = $request->get('discount_debit_account_id');
    //     $discount_debit_is_sub = $request->get('discount_debit_is_sub');
    //     $discount_credit_account_id = $request->get('discount_credit_account_id');
    //     $discount_credit_is_sub = $request->get('discount_credit_is_sub');

    //     // Reset all flags first
    //     DB::table('chart_of_accounts')->update([
    //         'discountje_debit_isctive' => 0,
    //         'discountje_credit_isctive' => 0,
    //     ]);

    //     DB::table('bk_sub_chart_of_accounts')->update([
    //         'discountje_debit_isctive' => 0,
    //         'discountje_credit_isctive' => 0,
    //     ]);

    //     // Update debit account active flag
    //     if ($discount_debit_is_sub == 0) {
    //         DB::table('chart_of_accounts')
    //             ->where('id', $discount_debit_account_id)
    //             ->where('deleted', 0)
    //             ->update(['discountje_debit_isctive' => 1]);
    //     } else {
    //         DB::table('bk_sub_chart_of_accounts')
    //             ->where('id', $discount_debit_account_id)
    //             ->where('deleted', 0)
    //             ->update(['discountje_debit_isctive' => 1]);
    //     }

    //     // Update credit account active flag
    //     if ($discount_credit_is_sub == 0) {
    //         DB::table('chart_of_accounts')
    //             ->where('id', $discount_credit_account_id)
    //             ->where('deleted', 0)
    //             ->update(['discountje_credit_isctive' => 1]);
    //     } else {
    //         DB::table('bk_sub_chart_of_accounts')
    //             ->where('id', $discount_credit_account_id)
    //             ->where('deleted', 0)
    //             ->update(['discountje_credit_isctive' => 1]);
    //     }

    //     return response()->json([
    //         ['status' => 1, 'message' => 'Adjustment JE Updated Successfully']
    //     ]);
    // }

    // public function setActive_discountJE_discount(Request $request)
    // {
    //     $academic_program_id = $request->get('academic_program');
    //     $discount_debit_account_id = $request->get('discount_debit_account_id');
    //     $discount_debit_is_sub = $request->get('discount_debit_is_sub');
    //     $discount_credit_account_id = $request->get('discount_credit_account_id');
    //     $discount_credit_is_sub = $request->get('discount_credit_is_sub');
    
    //     // Format the values to combine ID and sub flag
    //     // $debitValue = $discount_debit_is_sub === '1' 
    //     //     ? 'sub:' . $discount_debit_account_id 
    //     //     : $discount_debit_account_id;
            
    //     // $creditValue = $discount_credit_is_sub === '1' 
    //     //     ? 'sub:' . $discount_credit_account_id 
    //     //     : $discount_credit_account_id;
    
    //     // Get all grade levels for the academic program
    //     $gradeLevels = DB::table('gradelevel')
    //         ->where('deleted', 0)
    //         ->where('acadprogid', $academic_program_id)
    //         ->get();
    
    //     // Prepare data for bulk insert
    //     $insertData = [];
    //     $now = Carbon::now('Asia/Manila');
    //     $userId = auth()->user()->id;
    
    //     foreach ($gradeLevels as $level) {
    //         $insertData[] = [
    //             'levelid' => $level->id,
    //             'acadprogid' => $academic_program_id,
    //             'debitaccid' => $discount_debit_account_id,
    //             'creditaccid' => $discount_credit_account_id,
    //             'createdby' => $userId,
    //             'createddatetime' => $now
    //         ];
    //     }
    
    //     // Perform bulk insert
    //     DB::table('bk_discount_setup')->insert($insertData);
    
    //     return response()->json([
    //         'status' => 1, 
    //         'message' => 'Discount JE configuration applied to all grade levels under selected academic program'
    //     ]);
    // }

    public function setActive_discountJE_discount(Request $request)
    {
        $academic_program_id = $request->get('academic_program');
        $discount_debit_account_id = $request->get('discount_debit_account_id');
        $discount_debit_is_sub = $request->get('discount_debit_is_sub');
        $discount_credit_account_id = $request->get('discount_credit_account_id');
        $discount_credit_is_sub = $request->get('discount_credit_is_sub');
    
        // Get all grade levels for the academic program
        $gradeLevels = DB::table('gradelevel')
            ->where('deleted', 0)
            ->where('acadprogid', $academic_program_id)
            ->get();
    
        // Prepare data for bulk insert
        $insertData = [];
        $now = Carbon::now('Asia/Manila');
        $userId = auth()->user()->id;
    
        foreach ($gradeLevels as $level) {
            // Check if a record already exists for this acadprogid and levelid
            $exists = DB::table('bk_discount_setup')
                ->where('acadprogid', $academic_program_id)
                ->where('levelid', $level->id)
                ->where('deleted', 0)
                ->exists();
            
            if (!$exists) {
                $insertData[] = [
                    'levelid' => $level->id,
                    'acadprogid' => $academic_program_id,
                    'debitaccid' => $discount_debit_account_id,
                    'creditaccid' => $discount_credit_account_id,
                    'createdby' => $userId,
                    'createddatetime' => $now
                ];
            }
            //  else {
            //     return response()->json([
            //         'status' => 2, 
            //         'message' => 'Setup for specific acad and acad level is already exist'
            //     ]);
            // }
        }
    
        // Perform bulk insert only if there is data to insert
        if (!empty($insertData)) {
            DB::table('bk_discount_setup')->insert($insertData);
        }
    
        return response()->json([
            'status' => 1, 
            'message' => 'Discount JE configuration applied to all grade levels under selected academic program'
        ]);
    }


    public function fetch_active_discountJE(Request $request)
    {
        $setactive_jeacc = DB::table('chart_of_accounts')
            ->where('deleted', 0)
            ->where('discountje_debit_isctive', 1)
            ->select('id')
            ->first();

        if (empty($setactive_jeacc)) {
            $setactive_jeacc = DB::table('bk_sub_chart_of_accounts')
                ->where('deleted', 0)
                ->where('discountje_debit_isctive', 1)
                ->select('id')
                ->first();
        }

        $all_jeacc = DB::table('chart_of_accounts')
            ->where('deleted', 0)
            ->get();

        $all_subjeacc = DB::table('bk_sub_chart_of_accounts')
            ->where('deleted', 0)
            ->get();
        

        return response()->json([
            'active_cashierJE' => $setactive_jeacc,
            'all_cashierJE' => $all_jeacc,
            'all_subjeacc' => $all_subjeacc
        ]);
    }

    // public static function setactive_AccountJEdebit(Request $request)
    // {
    //     // Retrieve and validate inputs
    //     // $adjustment_debit_account_id = $request->input('adjustment_debit_account_id');
    //     // $adjusdtment_credit_account_id = $request->input('adjusdtment_credit_account_id');
  
    //     $adjustment_debit_account_id = $request->input('d_adjustment_debit_account_id');
    //     $adjusdtment_credit_account_id = $request->input('d_adjusdtment_credit_account_id');

    //     // d_adjustment_debit_account_id: d_adjustment_debit_account_id,
    //     // d_adjusdtment_credit_account_id: d_adjusdtment_credit_account_id,
    //     // c_adjustment_debit_account_id: c_adjustment_debit_account_id,
    //     // c_adjusdtment_credit_account_id: c_adjusdtment_credit_account_id,

    //     // Set all accounts and sub accounts to inactive except the selected one
    //     DB::table('chart_of_accounts')->where('id', '!=', $adjustment_debit_account_id)->update([
    //         'adjustmentje_debactive' => 0,
    //     ]);

    //     DB::table('bk_sub_chart_of_accounts')->where('id', '!=', $adjustment_debit_account_id)->update([
    //         'adjustmentje_debactive' => 0,
    //     ]);

    //     $exists = DB::table('chart_of_accounts')->where('id', $adjustment_debit_account_id)->exists();

    //     if ($exists) {
    //         DB::table('chart_of_accounts')->where('id', $adjustment_debit_account_id)->update([
    //             'adjustmentje_debactive' => 1,
    //         ]);
    //     } else {
    //         DB::table('bk_sub_chart_of_accounts')->where('id', $adjustment_debit_account_id)->update([
    //             'adjustmentje_debactive' => 1,
    //         ]);
    //     }

    //     ///////////////////////////////////////////////////////////////////////////////////

    //     DB::table('chart_of_accounts')->where('id', '!=', $adjusdtment_credit_account_id)->update([
    //         'adjustmentje_credactive' => 0,
    //     ]);

    //     DB::table('bk_sub_chart_of_accounts')->where('id', '!=', $adjusdtment_credit_account_id)->update([
    //         'adjustmentje_credactive' => 0,
    //     ]);

    //     $exists = DB::table('chart_of_accounts')->where('id', $adjusdtment_credit_account_id)->exists();

    //     if ($exists) {
    //         DB::table('chart_of_accounts')->where('id', $adjusdtment_credit_account_id)->update([
    //             'adjustmentje_credactive' => 1,
    //         ]);
    //     } else {
    //         DB::table('bk_sub_chart_of_accounts')->where('id', $adjusdtment_credit_account_id)->update([
    //             'adjustmentje_credactive' => 1,
    //         ]);
    //     }

    //     return response()->json([
    //         ['status' => 1, 'message' => 'Adjustment JE Updated Successfully']
    //     ]);
    // }

    public function setactive_AccountJEdebit(Request $request)
    {
        $data = $request->all();
      
        // First, reset all relevant fields in both tables to 0
        DB::table('chart_of_accounts')->update([
            'd_adjustmentje_debactive' => 0,
            'd_adjustmentje_credactive' => 0,
            'c_adjustmentje_debactive' => 0,
            'c_adjustmentje_credactive' => 0,
        ]);

        DB::table('bk_sub_chart_of_accounts')->update([
            'd_adjustmentje_debactive' => 0,
            'd_adjustmentje_credactive' => 0,
            'c_adjustmentje_debactive' => 0,
            'c_adjustmentje_credactive' => 0,
        ]);

        // Helper to update based on sub flag
        $updateFlag = function ($account, $column) {
            if ($account['sub'] == 0) {
                DB::table('chart_of_accounts')
                    ->where('id', $account['id'])
                    ->update([$column => 1]);
            } else {
                DB::table('bk_sub_chart_of_accounts')
                    ->where('id', $account['id'])
                    ->update([$column => 1]);
            }
        };

        // Perform updates
        $updateFlag($data['d_adjustment_debit_account_id'], 'd_adjustmentje_debactive');
        $updateFlag($data['d_adjusdtment_credit_account_id'], 'd_adjustmentje_credactive');
        $updateFlag($data['c_adjustment_debit_account_id'], 'c_adjustmentje_debactive');
        $updateFlag($data['c_adjusdtment_credit_account_id'], 'c_adjustmentje_credactive');

        return response()->json([
            ['status' => 1, 'message' => 'Adjustment JE Updated Successfully']
        ]);
    }

    public function fetch_active_accountJE(Request $request)
    {
        // Fetch active debit account
        $setactive_jeacc_adj_debit = DB::table('chart_of_accounts')
            ->where('deleted', 0)
            ->where('discountje_debit_isctive', 1)
            ->select('id')
            ->first();
    
        if (empty($setactive_jeacc_adj_debit)) {
            $setactive_jeacc_adj_debit = DB::table('bk_sub_chart_of_accounts')
                ->where('deleted', 0)
                ->where('discountje_debit_isctive', 1)
                ->select('id')
                ->first();
        }
    
        // Fetch active credit account
        $setactive_jeacc_adj_credit = DB::table('chart_of_accounts')
            ->where('deleted', 0)
            ->where('d_adjustmentje_credactive', 1)
            ->select('id')
            ->first();
    
        if (empty($setactive_jeacc_adj_credit)) {
            $setactive_jeacc_adj_credit = DB::table('bk_sub_chart_of_accounts')
                ->where('deleted', 0)
                ->where('d_adjustmentje_credactive', 1)
                ->select('id')
                ->first();
        }
    
        // Fetch all accounts
        $all_jeacc = DB::table('chart_of_accounts')
            ->where('deleted', 0)
            ->get();
    
        $all_subjeacc = DB::table('bk_sub_chart_of_accounts')
            ->where('deleted', 0)
            ->get();
    
        return response()->json([
            'setactive_jeacc_adj_debit' => $setactive_jeacc_adj_debit,
            'setactive_jeacc_adj_credit' => $setactive_jeacc_adj_credit,
            'all_cashierJE' => $all_jeacc,
            'all_subjeacc' => $all_subjeacc
        ]);
    }

    public static function delete_selected_disbursement_je(Request $request)
    {
        // Retrieve and validate inputs
        $deletedisburse_itemId = $request->input('deletedisburse_itemId');

            DB::table('bk_generalledg')->where('id', $deletedisburse_itemId)->update([
            'deleted' => 1,
            // 'deletedby' => auth()->user()->id
        ]);
      

        return response()->json([
            ['status' => 1, 'message' => 'Selected Disbursement JE Deleted Successfully']
        ]);
    }

    public static function editItem(Request $request){

        $item_id = $request->get('item_id');

        $selected_item = DB::table('bk_expenses_items')
        ->leftJoin('bk_item_assignment', 'bk_expenses_items.id', '=', 'bk_item_assignment.itemid')
        ->where('bk_expenses_items.id',$item_id)
        ->select(
            'bk_expenses_items.id',
            'bk_expenses_items.description',
            'bk_expenses_items.qty',
            'bk_expenses_items.stock_onhand',
            DB::raw('COALESCE(SUM(bk_item_assignment.assign_stock), 0) as total_assign_stock')
        )
        ->get();

        return response()->json([
            'selected_item' => $selected_item
        ]);
    }

    public static function assignitem(Request $request)
    {
        // Retrieve and validate inputs
        $itemid = $request->input('itemid');
        $department_select = $request->input('department_select');
        $employee_select = $request->input('employee_select');
        $onhand_stock = $request->input('onhand_stock');
        $assign = $request->input('assign');
        $remarks = $request->input('remarks');

        $new_onhand_stock = $onhand_stock - $assign;

        DB::table('bk_expenses_items')->where('id', $itemid)->update([
            'stock_onhand' => $new_onhand_stock
        ]);

        $insert_assign_itemId = DB::table('bk_item_assignment')->insertGetId([
            'itemid' => $itemid,
            'item_assign_department' => $department_select,
            'item_assign_employee' => $employee_select,
            'assign_stock' => $assign,
            'remarks' => $remarks,
        ]);

        $insertId = DB::table('bk_receiving_history')
        ->insertGetId([
            'invoiceno' => "---",
            'remarks' => $remarks,
            'isReceived' => 0,
            'posteddatetime' => now(),
            'createdby' => auth()->user()->id,
            'createddatetime' => now()
        ]);

        DB::table('bk_receiving_details_history')
        ->insert([
            'headerid' => $insertId,
            'itemid' => $itemid,
            'assign_itemId' => $insert_assign_itemId,
            'receivedqty' => $assign,
            'qty' => $onhand_stock,
            'createdby' => auth()->user()->id,
            'createddatetime' => now()
        ]);


        return response()->json([
            ['status' => 1, 'message' => 'Item Assigned Successfully']
        ]);
    }

    public static function po_update_new(Request $request)
    {
        // Retrieve and validate inputs
        $po_id_edit = $request->input('po_id_edit');
        $poReferenceNumber_edit = $request->input('poReferenceNumber_edit');
        $remarks_edit = $request->input('remarks_edit');
        $poDate_edit = $request->input('poDate_edit');
        $supplier_edit = $request->input('supplier_edit');

        $po_items = $request->input('po_items');

        // $approve_po = $request->input('approve_po');
        
        // Check if expenseDate_edit is provided
        // if (empty($expenseDate_edit)) {
        //     return response()->json([
        //         ['status' => 2, 'message' => 'Expenses Date is required']
        //     ]);
        // }

        try {
            DB::table('purchasing')
                ->where('id', $po_id_edit)
                ->update([
                    // 'dstatus' => $approve_po,
                    'remarks' => $remarks_edit,
                    'postdatetime' => $poDate_edit,
                    'supplierid' => $supplier_edit,

                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => now()
            ]);

            // if ($approve_po == "POSTED") {
    
                foreach ($po_items as $entry) {
                    if ($entry['po_newly_addedid'] == 0) {
                        DB::table('purchasing_details')->insert([
                            'headerid' => $po_id_edit,
                            // 'poid' => $po_id_edit,
                            'itemid' => $entry['itemId'],
                            'amount' => $entry['amount'],
                            'qty' => $entry['quantity'],
                            'totalamount' => $entry['total'],
                            'createdby' => auth()->user()->id,
                            'createddatetime' => now(),
                        ]);
                    }
                }
           
            return response()->json([
                ['status' => 1, 'message' => 'Purchase Order Updated Successfully']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                ['status' => 3, 'message' => 'An error occurred while updating: ' . $e->getMessage()]
            ]);
        }

    }

    public static function deletePO_item(Request $request)
    {
        // Retrieve and validate inputs
        $deletepoId = $request->input('deletepoId');

            DB::table('purchasing_details')->where('id', $deletepoId)->update([
            'deleted' => 1,
            'deletedby' => auth()->user()->id,
            'deleteddatetime' => now(),
        ]);
      

        return response()->json([
            ['status' => 1, 'message' => 'Purchase Order Item Deleted Successfully']
        ]);
    }


    public static function editItem_stock(Request $request){

        $item_id = $request->get('id');

        $item = DB::table('bk_expenses_items')
        ->leftJoin('chart_of_accounts', 'bk_expenses_items.coaid', '=', 'chart_of_accounts.id')
        ->leftJoin('bk_sub_chart_of_accounts', 'bk_expenses_items.coaid', '=', 'bk_sub_chart_of_accounts.id')
        ->where('bk_expenses_items.id', $item_id)
        ->where('bk_expenses_items.deleted', 0)
        ->select(
            'bk_expenses_items.*',
            DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.id ELSE bk_sub_chart_of_accounts.id END AS coaid'),
            DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.account_name ELSE bk_sub_chart_of_accounts.sub_account_name END AS account_name'),
            DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.code ELSE bk_sub_chart_of_accounts.sub_code END AS account_code'),
            DB::raw('CASE 
                WHEN bk_expenses_items.itemtype LIKE "%inventory%" THEN "inventory" 
                ELSE bk_expenses_items.itemtype 
                END AS itemtype') // Standardize itemtype values
        )
        ->first();

    return response()->json($item);

        // return response()->json([
        //     'selected_item' => $selected_item
        // ]);
    }

    public static function updateItem_stock(Request $request)
    {
        // Retrieve and validate inputs
        $item_idd = $request->input('item_idd');
       
        $itemName_edit = $request->input('itemName_edit');
        $itemCode_edit = $request->input('itemCode_edit');
        $quantity_edit = $request->input('quantity_edit');
        $amount_edit = $request->input('amount_edit');
        $itemType = $request->input('itemType');
      

            DB::table('bk_expenses_items')->where('id', $item_idd)->update([
            'description' => $itemName_edit,
            'itemcode' => $itemCode_edit,
            'qty' => $quantity_edit,
            'amount' => $amount_edit,
            'itemtype' => $itemType
        ]);
        
        return response()->json([
            ['status' => 1, 'message' => 'Expenses Item Updated Successfully']
        ]);
    }

    public function deleteExpenseItem_stock(Request $request)
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


    protected static  function generateVoucherNumberJE()
    {
        $latestVoucher = DB::table('bk_generalledg')
            ->where('voucherNo', 'LIKE', 'JE%')
            ->orderByDesc('id')
            ->value('voucherNo');
    
        \Log::debug("Latest voucher found: " . $latestVoucher);
    
        $nextNumber = 1;
        if ($latestVoucher) {
            $cleaned = preg_replace('/[^0-9]/', '', $latestVoucher);
            \Log::debug("Cleaned number: " . $cleaned);
            $latestNumber = (int) $cleaned;
            $nextNumber = $latestNumber + 1;
        }
    
        $newVoucher = 'JE - ' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
        \Log::debug("Generated new voucher: " . $newVoucher);
        
        return $newVoucher;
    }

    public function getNextRefNo()
{
    $purchasing = DB::table('purchasing')
        ->where('deleted', 0)
        ->select('refno')
        ->orderBy('refno', 'desc')
        ->first();

    if ($purchasing) {
        $refno = $purchasing->refno;
        $num = (int) substr($refno, 2);
        $refno = 'PO' . str_pad($num + 1, 4, '0', STR_PAD_LEFT);
    } else {
        $refno = 'PO0001';
    }

    return response()->json(['refno' => $refno]);
}


public function v2_voidtransactions(Request $request)
{
    if($request->ajax())
    {
        $transid = $request->get('receiving_history_id');
        $uname = $request->get('uname');
        $pword = $request->get('pword');
        $remarks = $request->get('remarks');

        $return = 0;
        // $syid = CashierModel::getSYID();
        // $semid = CashierModel::getSemID();
        $studid = 0;
        $feesid = 0;
        $esURL = '';

        $po_items = $request->input('po_items');

        $checkuser = db::table('users')
            ->where('email', $uname)
            ->get();

        if(count($checkuser) > 0)
        {
            $checkpermission = db::table('chrngpermission')
                ->where('userid', $checkuser[0]->id)
                ->get();


            if(count($checkpermission) > 0 || auth()->user()->email == 'ckgroup')
            {

                if(hash::check($pword, $checkuser[0]->password))
                {
                    if($remarks != '')
                    {

                        DB::table('bk_receiving_history')
                            ->where('id', $transid)
                            ->update([
                                'deleted' => 1,
                                'deletedby' => auth()->user()->id,
                                'deleteddatetime' => Carbon::now('Asia/Manila'),
                                'remarks' => $remarks
                        ]);

                        DB::table('bk_receiving_details_history')
                            ->where('headerid', $transid)
                            ->update([
                                'deleted' => 1,
                                'deletedby' => auth()->user()->id,
                                'deleteddatetime' => Carbon::now('Asia/Manila'),
                              
                        ]);
                        
                        foreach ($po_items as $entry) {
        
                            DB::table('receiving_details')

                            // ->where('poid', $po_id_edit)
                            ->join('bk_receiving_history', 'receiving_details.headerid', '=', 'bk_receiving_history.poid')
                            ->where('receiving_details.itemid', $entry['itemId'])
                            ->where('bk_receiving_history.id', $transid)
                            ->update([
                            'receiving_details.amount' => $entry['amount'],
                            'receiving_details.qty' =>  DB::raw("qty + " . $entry['receivedqty']),
                            'receiving_details.total' => DB::raw("total + " . $entry['rtotal']),
                            'receiving_details.receivedqty' => DB::raw("receivedqty - " . $entry['receivedqty']),
                            // 'rtotal' => $entry['rtotal'],
                            'receiving_details.rtotal' => DB::raw("rtotal - " . $entry['rtotal']),
                            'receiving_details.deletedby' => auth()->user()->id,
                            'receiving_details.deleteddatetime' => now(),
                            ]);
                        }
                        //////////////////////////

                        // Fetch the existing entries
                        $receiving_history = DB::table('bk_receiving_history')
                        ->join('bk_generalledg', 'bk_receiving_history.invoiceno', '=', 'bk_generalledg.bookkeeper_transvoucher')
                        ->leftJoin('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
                        ->leftJoin('bk_sub_chart_of_accounts', 'bk_generalledg.coaid', '=', 'bk_sub_chart_of_accounts.id')
                        ->where('bk_generalledg.deleted', 0)
                        ->where('bk_receiving_history.id', $transid)
                        ->select(
                            'bk_generalledg.*',
                            DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.id ELSE bk_sub_chart_of_accounts.id END AS coaid'),
                            DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.account_name ELSE bk_sub_chart_of_accounts.sub_account_name END AS account_name'),
                            DB::raw('CASE WHEN bk_sub_chart_of_accounts.id IS NULL THEN chart_of_accounts.code ELSE bk_sub_chart_of_accounts.sub_code END AS account_code'),
                            'bk_receiving_history.invoiceno'
                        )
                        ->get();

                            if ($receiving_history->isNotEmpty()) {
                        
                                
                                $voucherNos = $receiving_history->pluck('voucherNo')->unique();
                                
                                DB::table('bk_generalledg')
                                    ->whereIn('voucherNo', $voucherNos)
                                    ->update([
                                        'deleted' => 1,
                                        'deletedby' => auth()->id(), 
                                        'deleteddatetime' => now()
                                    ]);
                                
                                // Insert new entries with swapped credit/debit
                                foreach ($receiving_history as $entry) {
                                    $newEntry = [
                                        'voucherNo' => $entry->voucherNo,
                                        'bookkeeper_transvoucher' => $entry->bookkeeper_transvoucher,
                                        'coaid' => $entry->coaid,
                                        // 'account_name' => $entry->account_name,
                                        // 'account_code' => $entry->account_code,
                                        'date' => $entry->date,
                                        'debit_amount' => $entry->credit_amount, // Swapped
                                        'credit_amount' => $entry->debit_amount, // Swapped
                                        'remarks' => $entry->remarks,
                                        // 'invoiceno' => $entry->invoiceno,
                                        'active_fiscal_year_id' => $entry->active_fiscal_year_id,
                                        'createdby' => auth()->id(),
                                        'createddatetime' => now(),
                                        'deleted' => 0,
                                        'sub' => $entry->sub
                                    ];
                                    
                                    DB::table('bk_generalledg')->insert($newEntry);
                                }
                                

                            
                            }

                            $return = 1;
                    }
                    else
                    {
                        $return = 2;
                    }

                }
                else
                {
                    $return = 0;
                }
            }
            else
            {
                $return = 3;
            }

        }
        else
        {
            $return = 5;
        }

        $data = array(
         
            'return' => $return
           
        );

        echo json_encode($data);

    }
}



    public function print_stock_history(Request $request)
    {
        $query = DB::table('bk_receiving_details_history')
            ->where('bk_receiving_details_history.receivedqty', '>', 0)
            ->where('bk_receiving_details_history.deleted', 0)
            ->join('bk_receiving_history', 'bk_receiving_details_history.headerid', '=', 'bk_receiving_history.id')
            ->leftjoin('bk_item_assignment', 'bk_receiving_details_history.assign_itemId', '=', 'bk_item_assignment.id')
            ->leftjoin('teacher', 'bk_item_assignment.item_assign_employee', '=', 'teacher.id')
            ->leftjoin('hr_departments', 'bk_item_assignment.item_assign_department', '=', 'hr_departments.id')
            ->leftjoin('bk_expenses_items', 'bk_receiving_details_history.itemid', '=', 'bk_expenses_items.id')
            ->select(
                'bk_item_assignment.*',
                'bk_receiving_details_history.id',
                'bk_receiving_details_history.itemid',
                'bk_receiving_details_history.receivedqty',
                'bk_receiving_history.posteddatetime',
                'bk_receiving_history.invoiceno',
                'bk_receiving_history.remarks',
                'bk_receiving_history.isReceived',
                'bk_expenses_items.description',
                'bk_expenses_items.itemcode',
                'teacher.id',
                'teacher.firstname',
                'teacher.lastname',
                // 'bk_item_assignment.item_assign_employee',
                // 'bk_item_assignment.item_assign_department',
                'hr_departments.department'
            );
        
        // if (!empty($request->date_range)) {
        //     $dates = explode(' - ', $request->date_range);
        //     if (count($dates) == 2) {
        //         $startDate = trim($dates[0]);
        //         $endDate = trim($dates[1]);
        //         $query->whereBetween('expense.transdate', [$startDate, $endDate]);
        //     }
        // }
        // return $request->date_range;

        if (!empty($request->date_range)) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) == 2) {
                $startDate = trim($dates[0]) . ' 00:00:00';
                $endDate = trim($dates[1]) . ' 23:59:59';
                $query->whereBetween('bk_receiving_details_history.posteddatetime', [$startDate, $endDate]);
            }
        }


        // if (!empty($request->selectedAssign)) {
        //     $query->where('teacher.id', $request->selectedAssign);
        // }
        if ($request->selectedAssign != '00') {
            $query->where('bk_receiving_history.isReceived', $request->selectedAssign);
        }

        if (!empty($request->selectedDepartment)) {
            $query->where('hr_departments.department', $request->selectedDepartment);
        }

        if (!empty($request->selectedItem)) {
            $query->where('bk_expenses_items.description', $request->selectedItem);
        }

        $entries = $query->orderBy('bk_receiving_history.posteddatetime')->get();

        // $fiscal_desc = $entries->first()->fiscal_desc ?? null;

        $schoolinfo = DB::table('schoolinfo')->first();


        // Generate PDF
        // $pdf = Pdf::loadView('bookkeeper.pages.printables.Stock_history_PDF', [
        //     'entries' => $entries,
           
        //     'schoolinfo' => $schoolinfo,
        // ])->setPaper('A4', 'portrait');

        // return $pdf->stream('ExpensesMonitoring_' . now()->format('Ymd_His') . '.pdf');

        return view('bookkeeper.pages.printables.Stock_history_PDF', compact(
            'entries',
            'schoolinfo'
        ));
    }

    public static function updatecls(Request $request)
    {
        // Retrieve and validate inputs
        $cls_id = $request->input('cls_id');
        $cls_desc = $request->input('cls_desc');

            DB::table('bk_classifications')->where('id', $cls_id)->update([
            'desc' => $cls_desc
        ]);
        
        return response()->json([
            ['status' => 1, 'message' => 'Classifications Updated Successfully']
        ]);
    }

    public static function editcls(Request $request){

        $cls_id = $request->get('cls_id');

        $classifications = DB::table('bk_classifications')
        ->where('id',$cls_id)
        ->select(
            'id',
            'desc'
        )
        ->get();

        return response()->json([
            'classifications' => $classifications
        ]);
    }


    public function fetch_discountJE_discount(Request $request)
    {
        $classified = DB::table('bk_discount_setup')
            ->leftJoin('gradelevel', 'bk_discount_setup.levelid', '=', 'gradelevel.id')
            ->leftJoin('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
            ->leftJoin('chart_of_accounts as debitacc', 'bk_discount_setup.debitaccid', '=', 'debitacc.id')
            ->leftJoin('chart_of_accounts as creditacc', 'bk_discount_setup.creditaccid', '=', 'creditacc.id')
            
            ->leftJoin('bk_sub_chart_of_accounts as subdebitacc', function($join) {
                $join->on('bk_discount_setup.debitaccid', '=', 'subdebitacc.id');
            })
            ->leftJoin('bk_sub_chart_of_accounts as subcreditacc', function($join) {
                $join->on('bk_discount_setup.creditaccid', '=', 'subcreditacc.id');
            })
    
            ->select(
                'bk_discount_setup.id',
                'academicprogram.id as acadprogid',
                'academicprogram.progname',
                'gradelevel.levelname',
                'gradelevel.id as levelid',
                DB::raw('CASE 
                    WHEN bk_discount_setup.debitaccid IS NULL THEN NULL
                    WHEN subdebitacc.id IS NOT NULL THEN subdebitacc.sub_account_name 
                    ELSE debitacc.account_name 
                END AS debitacc'),
                DB::raw('CASE 
                    WHEN bk_discount_setup.creditaccid IS NULL THEN NULL
                    WHEN subcreditacc.id IS NOT NULL THEN subcreditacc.sub_account_name 
                    ELSE creditacc.account_name 
                END AS creditacc'),
            )
            ->where(function($query) {
                $query->where('bk_discount_setup.deleted', 0)
                      ->orWhereNull('bk_discount_setup.deleted');
            })
            ->get();
    
        $groupedByProgram = $classified->groupBy('acadprogid');
        $finalData = [];
    
        foreach ($groupedByProgram as $acadprogid => $itemsByProgram) {
            $programName = $itemsByProgram->first()->progname;
            
            $levels = $itemsByProgram->groupBy('levelid')->map(function($gradeLevelItems) {
                return [
                    'id' => $gradeLevelItems->first()->id,
                    'levelid' => $gradeLevelItems->first()->levelid,
                    'levelname' => $gradeLevelItems->first()->levelname,
                    'debitacc' => $gradeLevelItems->first()->debitacc,
                    'creditacc' => $gradeLevelItems->first()->creditacc
                ];
            })->values();
    
            $finalData[] = [
                'progid' => $acadprogid,
                'progname' => $programName,
                'levels' => $levels
            ];
        }
    
        return response()->json($finalData);
    }

    public static function edit_discountje(Request $request){

        $discountje_id = $request->get('discountje_id');

        $discountje = DB::table('bk_discount_setup')
        ->leftJoin('chart_of_accounts as debitacc', 'bk_discount_setup.debitaccid', '=', 'debitacc.id')
        ->leftJoin('chart_of_accounts as creditacc', 'bk_discount_setup.creditaccid', '=', 'creditacc.id')
        ->leftJoin('bk_sub_chart_of_accounts as subdebitacc', function($join) {
            $join->on('bk_discount_setup.debitaccid', '=', 'subdebitacc.id');
                
        })
        ->leftJoin('bk_sub_chart_of_accounts as subcreditacc', function($join) {
            $join->on('bk_discount_setup.creditaccid', '=', 'subcreditacc.id');
               
        })
        ->where('bk_discount_setup.id',$discountje_id)
        ->select(
            'bk_discount_setup.id',
            'bk_discount_setup.levelid',
            'bk_discount_setup.acadprogid',
            DB::raw('CASE 
                WHEN bk_discount_setup.debitaccid IS NULL THEN NULL
                WHEN subdebitacc.id IS NOT NULL THEN subdebitacc.sub_account_name 
                ELSE debitacc.account_name 
            END AS debitacc_name'),
            DB::raw('CASE 
                WHEN bk_discount_setup.debitaccid IS NULL THEN NULL
                WHEN subdebitacc.id IS NOT NULL THEN subdebitacc.id 
                ELSE debitacc.id 
            END AS debitacc_id'),
            DB::raw('CASE 
                WHEN bk_discount_setup.creditaccid IS NULL THEN NULL
                WHEN subcreditacc.id IS NOT NULL THEN subcreditacc.sub_account_name 
                ELSE creditacc.account_name 
            END AS creditacc_name'),
            DB::raw('CASE 
                WHEN bk_discount_setup.creditaccid IS NULL THEN NULL
                WHEN subcreditacc.id IS NOT NULL THEN subcreditacc.id 
                ELSE creditacc.id 
            END AS creditacc_id'),

        )
        ->get();

        // $chart_of_accounts = DB::table('chart_of_accounts')
        // ->get();
        $chart_of_accounts = DB::table('chart_of_accounts')
        ->where('deleted', 0)
        ->get();

        $bk_sub_chart_of_accounts = DB::table('bk_sub_chart_of_accounts')
        ->where('deleted', 0)
        ->get();

        $academicprogram = DB::table('academicprogram')
        // ->where('deleted', 0)
        ->get();
        $gradelevel = DB::table('gradelevel')
        ->where('deleted', 0)
        ->get();



        return response()->json([
            'discountje' => $discountje,
            'chart_of_accounts' => $chart_of_accounts,
            'bk_sub_chart_of_accounts' => $bk_sub_chart_of_accounts,
            'academicprogram' => $academicprogram,
            'gradelevel' => $gradelevel
        ]);
    }


    //reference lang ni
    // public static function update_discountje(Request $request)
    // {
    //     // Retrieve and validate inputs
    //     $discount_je_id = $request->input('discount_je_id');
       
    //     $academic_program_edit = $request->input('academic_program_edit');
    //     $grade_level_edit = $request->input('grade_level_edit');
    //     $debit_account_edit = $request->input('debit_account_edit');
    //     $credit_account_edit = $request->input('credit_account_edit');
      

    //         DB::table('bk_discount_setup')->where('id', $discount_je_id)->update([
    //         'levelid' => $grade_level_edit,
    //         'acadprogid' => $academic_program_edit,
    //         'debitaccid' => $debit_account_edit,
    //         'creditaccid' => $credit_account_edit
    //     ]);
        
    //     return response()->json([
    //         ['status' => 1, 'message' => 'Discount Account Updated Successfully']
    //     ]);
    // }

    // working 2nd reference
    // public static function update_discountje(Request $request)
    // {
    //     // Retrieve and validate inputs
    //     $discount_je_id = $request->input('discount_je_id');
       
    //     $academic_program_edit = $request->input('academic_program_edit');
    //     $grade_level_edit = $request->input('grade_level_edit');
    //     $debit_account_edit = $request->input('debit_account_edit');
    //     $credit_account_edit = $request->input('credit_account_edit');
      

    //     if (DB::table('bk_discount_setup')->where('id', $discount_je_id)->where('levelid', $grade_level_edit)->exists()) {
    //         DB::table('bk_discount_setup')->where('id', $discount_je_id)->where('levelid', $grade_level_edit)->update([
    //             'debitaccid' => $debit_account_edit,
    //             'creditaccid' => $credit_account_edit
    //         ]);
    //     } 
    //     else {
    //         DB::table('bk_discount_setup')->where('id', $discount_je_id)->update([
    //             'levelid' => $grade_level_edit,
    //             'acadprogid' => $academic_program_edit,
    //             'debitaccid' => $debit_account_edit,
    //             'creditaccid' => $credit_account_edit
    //         ]);
    //     }
        
    //     return response()->json([
    //         ['status' => 1, 'message' => 'Discount Account Updated Successfully']
    //     ]);
    // }

    public static function update_discountje(Request $request)
    {
        // Retrieve and validate inputs
        $discount_je_id = $request->input('discount_je_id');
        $academic_program_edit = $request->input('academic_program_edit');
        $grade_level_edit = $request->input('grade_level_edit');
        $debit_account_edit = $request->input('debit_account_edit');
        $credit_account_edit = $request->input('credit_account_edit');

        // First, mark any existing records with the same levelid as deleted
        DB::table('bk_discount_setup')
            ->where('levelid', $grade_level_edit)
            ->where('id', '!=', $discount_je_id) // Exclude the current record being updated
            ->update(['deleted' => 1]);

        // Then update the current record
        DB::table('bk_discount_setup')
            ->where('id', $discount_je_id)
            ->update([
                'levelid' => $grade_level_edit,
                'acadprogid' => $academic_program_edit,
                'debitaccid' => $debit_account_edit,
                'creditaccid' => $credit_account_edit,
                'deleted' => 0 
            ]);

        return response()->json([
            ['status' => 1, 'message' => 'Discount Account Updated Successfully']
        ]);
    }

    public static function delete_discountje(Request $request)
    {
        // Retrieve and validate inputs
        $deletesdiscountId = $request->input('deletesdiscountId');

            DB::table('bk_discount_setup')->where('id', $deletesdiscountId)->update([
            'deleted' => 1,
            'deletedby' => auth()->user()->id,
            'deleteddatetime' => now(),
        ]);
      

        return response()->json([
            ['status' => 1, 'message' => 'Discount Account Deleted Successfully']
        ]);
    }


    public function setactive_debadj(Request $request)
    {
        $academic_program_id = $request->get('academic_program');
        $discount_debit_account_id = $request->get('discount_debit_account_id');
        $discount_debit_is_sub = $request->get('discount_debit_is_sub');
        $discount_credit_account_id = $request->get('discount_credit_account_id');
        $discount_credit_is_sub = $request->get('discount_credit_is_sub');
    
        // Get all grade levels for the academic program
        $gradeLevels = DB::table('gradelevel')
            ->where('deleted', 0)
            ->where('acadprogid', $academic_program_id)
            ->get();
    
        // Prepare data for bulk insert
        $insertData = [];
        $now = Carbon::now('Asia/Manila');
        $userId = auth()->user()->id;
    
        foreach ($gradeLevels as $level) {
            // Check if a record already exists for this acadprogid and levelid
            $exists = DB::table('bk_debit_adjustment')
                ->where('acadprogid', $academic_program_id)
                ->where('levelid', $level->id)
                ->where('deleted', 0)
                ->exists();
            
            if (!$exists) {
                $insertData[] = [
                    'levelid' => $level->id,
                    'acadprogid' => $academic_program_id,
                    'deb_adj_debitaccid' => $discount_debit_account_id,
                    'deb_adj_creditaccid' => $discount_credit_account_id,
                    'createdby' => $userId,
                    'createddatetime' => $now
                ];
            }
            //  else {
            //     return response()->json([
            //         'status' => 2, 
            //         'message' => 'Setup for specific acad and acad level is already exist'
            //     ]);
            // }
        }
    
        // Perform bulk insert only if there is data to insert
        if (!empty($insertData)) {
            DB::table('bk_debit_adjustment')->insert($insertData);
        }
    
        return response()->json([
            'status' => 1, 
            'message' => 'Debit Adjustment JE configuration applied to all grade levels under selected academic program'
        ]);
    }

    public function fetch_debadj(Request $request)
    {
        $classified = DB::table('bk_debit_adjustment')
            ->leftJoin('gradelevel', 'bk_debit_adjustment.levelid', '=', 'gradelevel.id')
            ->leftJoin('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
            ->leftJoin('chart_of_accounts as debitacc', 'bk_debit_adjustment.deb_adj_debitaccid', '=', 'debitacc.id')
            ->leftJoin('chart_of_accounts as creditacc', 'bk_debit_adjustment.deb_adj_creditaccid', '=', 'creditacc.id')
            
            ->leftJoin('bk_sub_chart_of_accounts as subdebitacc', function($join) {
                $join->on('bk_debit_adjustment.deb_adj_debitaccid', '=', 'subdebitacc.id');
            })
            ->leftJoin('bk_sub_chart_of_accounts as subcreditacc', function($join) {
                $join->on('bk_debit_adjustment.deb_adj_creditaccid', '=', 'subcreditacc.id');
            })
    
            ->select(
                'bk_debit_adjustment.id',
                'academicprogram.id as acadprogid',
                'academicprogram.progname',
                'gradelevel.levelname',
                'gradelevel.id as levelid',
                DB::raw('CASE 
                    WHEN bk_debit_adjustment.deb_adj_debitaccid IS NULL THEN NULL
                    WHEN subdebitacc.id IS NOT NULL THEN subdebitacc.sub_account_name 
                    ELSE debitacc.account_name 
                END AS debitacc'),
                DB::raw('CASE 
                    WHEN bk_debit_adjustment.deb_adj_creditaccid IS NULL THEN NULL
                    WHEN subcreditacc.id IS NOT NULL THEN subcreditacc.sub_account_name 
                    ELSE creditacc.account_name 
                END AS creditacc'),
            )
            ->where(function($query) {
                $query->where('bk_debit_adjustment.deleted', 0)
                      ->orWhereNull('bk_debit_adjustment.deleted');
            })
            ->get();
    
        $groupedByProgram = $classified->groupBy('acadprogid');
        $finalData = [];
    
        foreach ($groupedByProgram as $acadprogid => $itemsByProgram) {
            $programName = $itemsByProgram->first()->progname;
            
            $levels = $itemsByProgram->groupBy('levelid')->map(function($gradeLevelItems) {
                return [
                    'id' => $gradeLevelItems->first()->id,
                    'levelid' => $gradeLevelItems->first()->levelid,
                    'levelname' => $gradeLevelItems->first()->levelname,
                    'debitacc' => $gradeLevelItems->first()->debitacc,
                    'creditacc' => $gradeLevelItems->first()->creditacc
                ];
            })->values();
    
            $finalData[] = [
                'progid' => $acadprogid,
                'progname' => $programName,
                'levels' => $levels
            ];
        }
    
        return response()->json($finalData);
    }


    public static function edit_debadj(Request $request){

        $debadjje_id = $request->get('debadjje_id');

        $debadjje_id = DB::table('bk_debit_adjustment')
        ->leftJoin('chart_of_accounts as debitacc', 'bk_debit_adjustment.deb_adj_debitaccid', '=', 'debitacc.id')
        ->leftJoin('chart_of_accounts as creditacc', 'bk_debit_adjustment.deb_adj_creditaccid', '=', 'creditacc.id')
        ->leftJoin('bk_sub_chart_of_accounts as subdebitacc', function($join) {
            $join->on('bk_debit_adjustment.deb_adj_debitaccid', '=', 'subdebitacc.id');
                
        })
        ->leftJoin('bk_sub_chart_of_accounts as subcreditacc', function($join) {
            $join->on('bk_debit_adjustment.deb_adj_creditaccid', '=', 'subcreditacc.id');
               
        })
        ->where('bk_debit_adjustment.id',$debadjje_id)
        ->select(
            'bk_debit_adjustment.id',
            'bk_debit_adjustment.levelid',
            'bk_debit_adjustment.acadprogid',
            DB::raw('CASE 
                WHEN bk_debit_adjustment.deb_adj_debitaccid IS NULL THEN NULL
                WHEN subdebitacc.id IS NOT NULL THEN subdebitacc.sub_account_name 
                ELSE debitacc.account_name 
            END AS debitacc_name'),
            DB::raw('CASE 
                WHEN bk_debit_adjustment.deb_adj_debitaccid IS NULL THEN NULL
                WHEN subdebitacc.id IS NOT NULL THEN subdebitacc.id 
                ELSE debitacc.id 
            END AS debitacc_id'),
            DB::raw('CASE 
                WHEN bk_debit_adjustment.deb_adj_creditaccid IS NULL THEN NULL
                WHEN subcreditacc.id IS NOT NULL THEN subcreditacc.sub_account_name 
                ELSE creditacc.account_name 
            END AS creditacc_name'),
            DB::raw('CASE 
                WHEN bk_debit_adjustment.deb_adj_creditaccid IS NULL THEN NULL
                WHEN subcreditacc.id IS NOT NULL THEN subcreditacc.id 
                ELSE creditacc.id 
            END AS creditacc_id'),

        )
        ->get();

        // $chart_of_accounts = DB::table('chart_of_accounts')
        // ->get();
        $chart_of_accounts = DB::table('chart_of_accounts')
        ->where('deleted', 0)
        ->get();

        $bk_sub_chart_of_accounts = DB::table('bk_sub_chart_of_accounts')
        ->where('deleted', 0)
        ->get();

        $academicprogram = DB::table('academicprogram')
        // ->where('deleted', 0)
        ->get();
        $gradelevel = DB::table('gradelevel')
        ->where('deleted', 0)
        ->get();



        return response()->json([
            'debadjje_id' => $debadjje_id,
            'chart_of_accounts' => $chart_of_accounts,
            'bk_sub_chart_of_accounts' => $bk_sub_chart_of_accounts,
            'academicprogram' => $academicprogram,
            'gradelevel' => $gradelevel
        ]);
    }

    public static function update_debadj(Request $request)
    {
      
        $debadj_je_id = $request->input('debadj_je_id');
       
        $deb_adj_academic_program_edit = $request->input('deb_adj_academic_program_edit');
        $deb_adj_grade_level_edit = $request->input('deb_adj_grade_level_edit');
        $deb_adj_debit_account_edit = $request->input('deb_adj_debit_account_edit');
        $deb_adj_credit_account_edit = $request->input('deb_adj_credit_account_edit');
      

        //     DB::table('bk_debit_adjustment')->where('id', $debadj_je_id)->update([
        //     'levelid' => $deb_adj_grade_level_edit,
        //     'acadprogid' => $deb_adj_academic_program_edit,
        //     'deb_adj_debitaccid' => $deb_adj_debit_account_edit,
        //     'deb_adj_creditaccid' => $deb_adj_credit_account_edit
        // ]);

        DB::table('bk_debit_adjustment')
        ->where('levelid', $deb_adj_grade_level_edit)
        ->where('id', '!=', $debadj_je_id) // Exclude the current record being updated
        ->update(['deleted' => 1]);

    // Then update the current record
        DB::table('bk_debit_adjustment')
            ->where('id', $debadj_je_id)
            ->update([
                'levelid' => $deb_adj_grade_level_edit,
                'acadprogid' => $deb_adj_academic_program_edit,
                'deb_adj_debitaccid' => $deb_adj_debit_account_edit,
                'deb_adj_creditaccid' => $deb_adj_credit_account_edit,
                'deleted' => 0 
            ]);
        
        return response()->json([
            ['status' => 1, 'message' => 'Debit Adjustment JE Updated Successfully']
        ]);
    }


    public static function delete_debadj(Request $request)
    {
        // Retrieve and validate inputs
        $deletedebadjId = $request->input('deletedebadjId');

            DB::table('bk_debit_adjustment')->where('id', $deletedebadjId)->update([
            'deleted' => 1,
            'deletedby' => auth()->user()->id,
            'deleteddatetime' => now(),
        ]);
      

        return response()->json([
            ['status' => 1, 'message' => 'Debit Adjustment JE Deleted Successfully']
        ]);
    }


    public function setactive_credadj(Request $request)
    {
        $academic_program_id = $request->get('academic_program');
        $cred_adj_debit_account_id = $request->get('cred_adj_debit_account_id');
        $cred_adj_debit_account_id_is_sub = $request->get('cred_adj_debit_account_id_is_sub');
        $cred_adj_credit_account_id = $request->get('cred_adj_credit_account_id');
        $cred_adj_credit_account_id_is_sub = $request->get('cred_adj_credit_account_id_is_sub');
    
        // Get all grade levels for the academic program
        $gradeLevels = DB::table('gradelevel')
            ->where('deleted', 0)
            ->where('acadprogid', $academic_program_id)
            ->get();
    
        // Prepare data for bulk insert
        $insertData = [];
        $now = Carbon::now('Asia/Manila');
        $userId = auth()->user()->id;
    
        foreach ($gradeLevels as $level) {
            // Check if a record already exists for this acadprogid and levelid
            $exists = DB::table('bk_credit_adjustment')
                ->where('acadprogid', $academic_program_id)
                ->where('levelid', $level->id)
                ->where('deleted', 0)
                ->exists();
            
            if (!$exists) {
                $insertData[] = [
                    'levelid' => $level->id,
                    'acadprogid' => $academic_program_id,
                    'cred_adj_debitaccid' => $cred_adj_debit_account_id,
                    'cred_adj_creditaccid' => $cred_adj_credit_account_id,
                    'createdby' => $userId,
                    'createddatetime' => $now
                ];
            }
            //  else {
            //     return response()->json([
            //         'status' => 2, 
            //         'message' => 'Setup for specific acad and acad level is already exist'
            //     ]);
            // }
        }
    
        // Perform bulk insert only if there is data to insert
        if (!empty($insertData)) {
            DB::table('bk_credit_adjustment')->insert($insertData);
        }
    
        return response()->json([
            'status' => 1, 
            'message' => 'Credit Adjustment JE configuration applied to all grade levels under selected academic program'
        ]);
    }

    public function fetch_credadj(Request $request)
    {
        $classified = DB::table('bk_credit_adjustment')
            ->leftJoin('gradelevel', 'bk_credit_adjustment.levelid', '=', 'gradelevel.id')
            ->leftJoin('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
            ->leftJoin('chart_of_accounts as debitacc', 'bk_credit_adjustment.cred_adj_debitaccid', '=', 'debitacc.id')
            ->leftJoin('chart_of_accounts as creditacc', 'bk_credit_adjustment.cred_adj_creditaccid', '=', 'creditacc.id')
            
            ->leftJoin('bk_sub_chart_of_accounts as subdebitacc', function($join) {
                $join->on('bk_credit_adjustment.cred_adj_debitaccid', '=', 'subdebitacc.id');
            })
            ->leftJoin('bk_sub_chart_of_accounts as subcreditacc', function($join) {
                $join->on('bk_credit_adjustment.cred_adj_creditaccid', '=', 'subcreditacc.id');
            })
    
            ->select(
                'bk_credit_adjustment.id',
                'academicprogram.id as acadprogid',
                'academicprogram.progname',
                'gradelevel.levelname',
                'gradelevel.id as levelid',
                DB::raw('CASE 
                    WHEN bk_credit_adjustment.cred_adj_debitaccid IS NULL THEN NULL
                    WHEN subdebitacc.id IS NOT NULL THEN subdebitacc.sub_account_name 
                    ELSE debitacc.account_name 
                END AS debitacc'),
                DB::raw('CASE 
                    WHEN bk_credit_adjustment.cred_adj_creditaccid IS NULL THEN NULL
                    WHEN subcreditacc.id IS NOT NULL THEN subcreditacc.sub_account_name 
                    ELSE creditacc.account_name 
                END AS creditacc'),
            )
            ->where(function($query) {
                $query->where('bk_credit_adjustment.deleted', 0)
                      ->orWhereNull('bk_credit_adjustment.deleted');
            })
            ->get();
    
        $groupedByProgram = $classified->groupBy('acadprogid');
        $finalData = [];
    
        foreach ($groupedByProgram as $acadprogid => $itemsByProgram) {
            $programName = $itemsByProgram->first()->progname;
            
            $levels = $itemsByProgram->groupBy('levelid')->map(function($gradeLevelItems) {
                return [
                    'id' => $gradeLevelItems->first()->id,
                    'levelid' => $gradeLevelItems->first()->levelid,
                    'levelname' => $gradeLevelItems->first()->levelname,
                    'debitacc' => $gradeLevelItems->first()->debitacc,
                    'creditacc' => $gradeLevelItems->first()->creditacc
                ];
            })->values();
    
            $finalData[] = [
                'progid' => $acadprogid,
                'progname' => $programName,
                'levels' => $levels
            ];
        }
    
        return response()->json($finalData);
    }

    public static function edit_credadj(Request $request){

        $credadjje_id = $request->get('credadjje_id');

        $credadjje_id = DB::table('bk_credit_adjustment')
        ->leftJoin('chart_of_accounts as debitacc', 'bk_credit_adjustment.cred_adj_debitaccid', '=', 'debitacc.id')
        ->leftJoin('chart_of_accounts as creditacc', 'bk_credit_adjustment.cred_adj_creditaccid', '=', 'creditacc.id')
        ->leftJoin('bk_sub_chart_of_accounts as subdebitacc', function($join) {
            $join->on('bk_credit_adjustment.cred_adj_debitaccid', '=', 'subdebitacc.id');
                
        })
        ->leftJoin('bk_sub_chart_of_accounts as subcreditacc', function($join) {
            $join->on('bk_credit_adjustment.cred_adj_creditaccid', '=', 'subcreditacc.id');
               
        })
        ->where('bk_credit_adjustment.id',$credadjje_id)
        ->select(
            'bk_credit_adjustment.id',
            'bk_credit_adjustment.levelid',
            'bk_credit_adjustment.acadprogid',
            DB::raw('CASE 
                WHEN bk_credit_adjustment.cred_adj_debitaccid IS NULL THEN NULL
                WHEN subdebitacc.id IS NOT NULL THEN subdebitacc.sub_account_name 
                ELSE debitacc.account_name 
            END AS debitacc_name'),
            DB::raw('CASE 
                WHEN bk_credit_adjustment.cred_adj_debitaccid IS NULL THEN NULL
                WHEN subdebitacc.id IS NOT NULL THEN subdebitacc.id 
                ELSE debitacc.id 
            END AS debitacc_id'),
            DB::raw('CASE 
                WHEN bk_credit_adjustment.cred_adj_creditaccid IS NULL THEN NULL
                WHEN subcreditacc.id IS NOT NULL THEN subcreditacc.sub_account_name 
                ELSE creditacc.account_name 
            END AS creditacc_name'),
            DB::raw('CASE 
                WHEN bk_credit_adjustment.cred_adj_creditaccid IS NULL THEN NULL
                WHEN subcreditacc.id IS NOT NULL THEN subcreditacc.id 
                ELSE creditacc.id 
            END AS creditacc_id'),

        )
        ->get();

        // $chart_of_accounts = DB::table('chart_of_accounts')
        // ->get();
        $chart_of_accounts = DB::table('chart_of_accounts')
        ->where('deleted', 0)
        ->get();

        $bk_sub_chart_of_accounts = DB::table('bk_sub_chart_of_accounts')
        ->where('deleted', 0)
        ->get();

        $academicprogram = DB::table('academicprogram')
        // ->where('deleted', 0)
        ->get();
        $gradelevel = DB::table('gradelevel')
        ->where('deleted', 0)
        ->get();



        return response()->json([
            'credadjje_id' => $credadjje_id,
            'chart_of_accounts' => $chart_of_accounts,
            'bk_sub_chart_of_accounts' => $bk_sub_chart_of_accounts,
            'academicprogram' => $academicprogram,
            'gradelevel' => $gradelevel
        ]);
    }

    public static function update_credadj(Request $request)
    {
      
        $credadj_je_id = $request->input('credadj_je_id');
       
        $cred_adj_academic_program_edit = $request->input('cred_adj_academic_program_edit');
        $cred_adj_grade_level_edit = $request->input('cred_adj_grade_level_edit');
        $cred_adj_debit_account_edit = $request->input('cred_adj_debit_account_edit');
        $cred_adj_credit_account_edit = $request->input('cred_adj_credit_account_edit');
      

        //     DB::table('bk_credit_adjustment')->where('id', $credadj_je_id)->update([
        //     'levelid' => $cred_adj_grade_level_edit,
        //     'acadprogid' => $cred_adj_academic_program_edit,
        //     'cred_adj_debitaccid' => $cred_adj_debit_account_edit,
        //     'cred_adj_creditaccid' => $cred_adj_credit_account_edit
        // ]);

        DB::table('bk_credit_adjustment')
        ->where('levelid', $cred_adj_grade_level_edit)
        ->where('id', '!=', $credadj_je_id) // Exclude the current record being updated
        ->update(['deleted' => 1]);

    // Then update the current record
        DB::table('bk_credit_adjustment')
            ->where('id', $credadj_je_id)
            ->update([
                'levelid' => $cred_adj_grade_level_edit,
                'acadprogid' => $cred_adj_academic_program_edit,
                'cred_adj_debitaccid' => $cred_adj_debit_account_edit,
                'cred_adj_creditaccid' => $cred_adj_credit_account_edit,
                'deleted' => 0 
            ]);
        
        return response()->json([
            ['status' => 1, 'message' => 'Credit Adjustment JE Updated Successfully']
        ]);
    }

    public static function delete_credadj(Request $request)
    {
        // Retrieve and validate inputs
        $deletecredadjId = $request->input('deletecredadjId');

            DB::table('bk_credit_adjustment')->where('id', $deletecredadjId)->update([
            'deleted' => 1,
            'deletedby' => auth()->user()->id,
            'deleteddatetime' => now(),
        ]);
      

        return response()->json([
            ['status' => 1, 'message' => 'Credit Adjustment JE Deleted Successfully']
        ]);
    }




    // public function printGeneralLedger(Request $request)
    // {
    //     $startDate = null;
    //     $endDate = null;
    //     $fiscalYearId = $request->input('fiscal_year');
    //     $dateRange = $request->input('date_range');
        
    //     // Date range handling (same as before)
    //     if ($dateRange) {
    //         [$startDate, $endDate] = explode(' - ', $dateRange);
    //         try {
    //             $startDate = \Carbon\Carbon::createFromFormat('Y-m-d', trim($startDate))->startOfDay();
    //             $endDate = \Carbon\Carbon::createFromFormat('Y-m-d', trim($endDate))->endOfDay();
    //         } catch (\Exception $e) {
    //             return back()->with('error', 'Invalid date format.');
    //         }
    //     } elseif ($fiscalYearId) {
    //         $fiscalYear = DB::table('bk_fiscal_year')
    //             ->where('id', $fiscalYearId)
    //             ->where('isactive', 1)
    //             ->where('deleted', 0)
    //             ->first();
        
    //         if (!$fiscalYear) {
    //             return back()->with('error', 'Invalid or inactive fiscal year.');
    //         }
        
    //         $startDate = \Carbon\Carbon::parse($fiscalYear->stime)->startOfDay();
    //         $endDate = \Carbon\Carbon::parse($fiscalYear->etime)->endOfDay();
    //     } else {
    //         // Default to current month
    //         $startDate = \Carbon\Carbon::now()->startOfMonth()->startOfDay();
    //         $endDate = \Carbon\Carbon::now()->endOfMonth()->endOfDay();
    //     }

    //     // Paginated query
    //     $generalLedger = DB::table('bk_generalledg')
    //         ->leftJoin('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //         ->leftJoin('bk_sub_chart_of_accounts', 'bk_generalledg.coaid', '=', 'bk_sub_chart_of_accounts.id')
    //         ->select(
    //             'bk_generalledg.voucherNo',
    //             'bk_generalledg.date',
    //             'bk_generalledg.coaid',
    //             'bk_generalledg.debit_amount',
    //             'bk_generalledg.credit_amount',
    //             'bk_generalledg.remarks',
    //             'bk_generalledg.sub',
    //             DB::raw("CASE 
    //                 WHEN bk_generalledg.sub = 0 OR bk_generalledg.sub IS NULL THEN chart_of_accounts.account_name 
    //                 ELSE bk_sub_chart_of_accounts.sub_account_name 
    //                 END AS account_name"),
    //             DB::raw("CASE 
    //                 WHEN bk_generalledg.sub = 0 OR bk_generalledg.sub IS NULL THEN chart_of_accounts.code 
    //                 ELSE bk_sub_chart_of_accounts.sub_code 
    //                 END AS code")
    //             )
    //         ->where('bk_generalledg.deleted', 0)
    //         ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
    //             $q->whereBetween('bk_generalledg.date', [$startDate, $endDate]);
    //         })
    //         ->orderBy('bk_generalledg.date', 'desc')
    //         ->get(); // Adjust per page count as needed

    //     $schoolinfo = DB::table('schoolinfo')->first();
    //     $signatories = DB::table('signatory')
    //         ->join('bk_signatory_grp', 'signatory.signatory_grp_id', '=', 'bk_signatory_grp.id')
    //         ->where('signatory.signatory_grp_id', 2)
    //         ->select('signatory.*')
    //         ->get();

    //     return view('bookkeeper.pages.printables.GeneralLedger_PDF', compact(
    //         'generalLedger',
    //         'schoolinfo',
    //         'signatories',
    //         'startDate',
    //         'endDate',
    //         'fiscalYearId'
    //     ));
    // }
    

    



}
