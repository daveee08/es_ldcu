<?php

namespace App\Http\Controllers\BookkeeperControllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use DB;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use PDF;

class SubsidiaryLedgerController extends Controller
{
    // public function index(Request $request)
    // {
    //     // Ledger Entries
    //     $query = DB::table('bk_generalledg')
    //         ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //         ->select(
    //             'bk_generalledg.*',
    //             'chart_of_accounts.classification',
    //             'chart_of_accounts.account_name',
    //             'chart_of_accounts.code',
    //             'chart_of_accounts.account_type'
    //         );

    //     if ($request->filled('fiscal_year')) {
    //         $query->where('bk_generalledg.active_fiscal_year_id', $request->fiscal_year);
    //     }

    //     if ($request->filled('account_id')) {
    //         $query->where('bk_generalledg.coaid', $request->account_id);
    //     }

    //     $ledger = $query->get();
        

    //     return response()->json([
    //         'ledger' => $ledger
    //     ]);
    // }

    // public function index(Request $request)
    // {
    //     // Ledger Entries
    //     $accountUnion = DB::table('chart_of_accounts')
    //         ->select(
    //             'id as coaid',
    //             'account_name',
    //             'code',
    //             'classification'
    //         )
    //         ->unionAll(
    //             DB::table('bk_sub_chart_of_accounts')
    //                 ->leftJoin('chart_of_accounts', 'bk_sub_chart_of_accounts.coaid', '=', 'chart_of_accounts.id')
    //                 ->select(
    //                     'bk_sub_chart_of_accounts.id as coaid',
    //                     'sub_account_name as account_name',
    //                     'sub_code as code',
    //                     'chart_of_accounts.classification'

    //                 )
    //         );
      
    //     $ledger = DB::table('bk_generalledg')
    //         ->leftJoinSub($accountUnion, 'accounts', function ($join) {
    //             $join->on('bk_generalledg.coaid', '=', 'accounts.coaid');
    //         })
    //         ->select(
    //             'bk_generalledg.voucherNo',
    //             'bk_generalledg.date',
    //             'bk_generalledg.coaid',
    //             'bk_generalledg.debit_amount',
    //             'bk_generalledg.credit_amount',
    //             'bk_generalledg.remarks',
    //             'bk_generalledg.sub',
    //             'accounts.account_name',
    //             'accounts.code',
    //             'accounts.classification'
    //         )
    //         ->where('bk_generalledg.deleted', 0)
    //         // ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
    //         //     $q->whereBetween('bk_generalledg.date', [$startDate, $endDate]);
    //         // })
    //         ->orderBy('bk_generalledg.date', 'desc')
    //         ->get();


    //     return response()->json([
    //         'ledger' => $ledger
    //     ]);
    // }

    // public function index(Request $request)
    // {
    //     \Log::debug('Request parameters:', $request->all());
    //     // Get DataTable parameters
    //     $start = $request->input('start', 0);
    //     $length = $request->input('length', 10);
    //     $searchValue = $request->input('search.value', '');
    //     $orderColumnIndex = $request->input('order.0.column', 0);
    //     $orderDirection = $request->input('order.0.dir', 'asc');
    //     $fiscalYear = $request->input('fiscal_year');
    //     $accountId = $request->input('account_id');
    
    //     // Column mappings for ordering
    //     $columns = [
    //         'voucherNo',
    //         'date',
    //         'remarks',
    //         'code',
    //         'account_name',
    //         'debit_amount',
    //         'credit_amount',
    //         DB::raw('debit_amount - credit_amount') // Balance
    //     ];
    //     $orderColumn = $columns[$orderColumnIndex] ?? 'date';

    //     // Account Union query
    //     $accountUnion = DB::table('chart_of_accounts')
    //         ->select(
    //             'id as coaid',
    //             'account_name',
    //             'code',
    //             'classification'
    //         )
    //         ->unionAll(
    //             DB::table('bk_sub_chart_of_accounts')
    //                 ->leftJoin('chart_of_accounts', 'bk_sub_chart_of_accounts.coaid', '=', 'chart_of_accounts.id')
    //                 ->select(
    //                     'bk_sub_chart_of_accounts.id as coaid',
    //                     'sub_account_name as account_name',
    //                     'sub_code as code',
    //                     'chart_of_accounts.classification'
    //                 )
    //         );

    //     // Base query for data and counts
    //     $baseQuery = DB::table('bk_generalledg')
    //         ->leftJoinSub($accountUnion, 'accounts', function ($join) {
    //             $join->on('bk_generalledg.coaid', '=', 'accounts.coaid');
    //         })
    //         ->where('bk_generalledg.deleted', 0);

    //     // Apply fiscal year filter if provided
    //     if ($fiscalYear) {
    //         $baseQuery->where('bk_generalledg.active_fiscal_year_id', $fiscalYear);
    //     }

    //     // Apply account filter if provided
    //     // if ($accountId) {
    //     //     $baseQuery->where('bk_generalledg.coaid', $accountId);
    //     // }
    //     if ($accountId) {
    //         $baseQuery->where('bk_generalledg.coaid', (int) $accountId)->get();
    //     }
       
    //     // Query for totals (unpaginated, with filters)
    //     $totals = $baseQuery->select(
    //         DB::raw('COALESCE(SUM(debit_amount), 0) as total_debit'),
    //         DB::raw('COALESCE(SUM(credit_amount), 0) as total_credit'),
    //         DB::raw('COALESCE(SUM(debit_amount - credit_amount), 0) as total_balance')
    //     )->first();

    //     // Query for filtered data
    //     $dataQuery = clone $baseQuery;
    //     $dataQuery->select(
    //         'bk_generalledg.voucherNo',
    //         'bk_generalledg.date',
    //         'bk_generalledg.coaid',
    //         'bk_generalledg.debit_amount',
    //         'bk_generalledg.credit_amount',
    //         'bk_generalledg.remarks',
    //         'bk_generalledg.sub',
    //         'accounts.account_name',
    //         'accounts.code',
    //         'accounts.classification',
    //         DB::raw('debit_amount - credit_amount as balance')
    //     );

    //     // Apply search filter if provided
    //     if (!empty($searchValue)) {
    //         $dataQuery->where(function($q) use ($searchValue) {
    //             $q->where('bk_generalledg.voucherNo', 'like', "%$searchValue%")
    //             ->orWhere('bk_generalledg.date', 'like', "%$searchValue%")
    //             ->orWhere('bk_generalledg.remarks', 'like', "%$searchValue%")
    //             ->orWhere('accounts.code', 'like', "%$searchValue%")
    //             ->orWhere('accounts.account_name', 'like', "%$searchValue%");
    //         });
    //     }

    //     // Get filtered count
    //     $filteredCount = $dataQuery->count();

    //     // Get total records count (without search filters)
    //     $totalRecords = $baseQuery->count();

    //     // Get paginated data
    //     $data = $dataQuery->orderBy($orderColumn, $orderDirection)
    //                     ->skip($start)
    //                     ->take($length)
    //                     ->get();
       
    //     return response()->json([
    //         'draw' => intval($request->input('draw', 1)),
    //         'recordsTotal' => $totalRecords,
    //         'recordsFiltered' => $filteredCount,
    //         'data' => $data,
    //         'totalDebit' => $totals->total_debit,
    //         'totalCredit' => $totals->total_credit,
    //         'totalBalance' => $totals->total_balance
    //     ]);
    // }
       
    public function index(Request $request)
    {
        // Validate inputs
        $request->validate([
            'fiscal_year' => 'nullable|integer',
            'account_id' => 'nullable|integer'
        ]);

        // Get DataTable parameters
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $searchValue = $request->input('search.value', '');
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');
        $fiscalYear = $request->input('fiscal_year');
        $accountId = $request->input('account_id');
        $costcenter_id = $request->input('costcenter_id');
        $costcenter_desc = $request->input('costcenter_desc');

        $bk_sub_ids = [];

        $dateRange = $request->input('date_range');

        // Parse date range
        $startDate = null;
        $endDate = null;

        if ($dateRange) {
            [$startDate, $endDate] = explode(' - ', $dateRange);
            try {
                $startDate = \Carbon\Carbon::createFromFormat('Y-m-d', trim($startDate))->startOfDay();
                $endDate = \Carbon\Carbon::createFromFormat('Y-m-d', trim($endDate))->endOfDay();
            } catch (\Exception $e) {
                return response()->json(['error' => 'Invalid date format.'], 400);
            }
        } elseif ($fiscalYearId) {
            $fiscalYear = DB::table('bk_fiscal_year')
                ->where('id', $fiscalYearId)
                ->where('isactive', 1)
                ->where('deleted', 0)
                ->first();
        
            if (!$fiscalYear) {
                return response()->json(['error' => 'Invalid or inactive fiscal year.'], 400);
            }
        
            $startDate = \Carbon\Carbon::parse($fiscalYear->stime)->startOfDay();
            $endDate = \Carbon\Carbon::parse($fiscalYear->etime)->endOfDay();
        } else {
            // Default to current month
            $startDate = \Carbon\Carbon::now()->startOfMonth()->startOfDay();
            $endDate = \Carbon\Carbon::now()->endOfMonth()->endOfDay();
        }

        // Column mappings for ordering
        $columns = [
            'voucherNo',
            'date',
            'remarks',
            'code',
            'account_name',
            'debit_amount',
            'credit_amount',
            DB::raw('debit_amount - credit_amount') // Balance
        ];
        $orderColumn = $columns[$orderColumnIndex] ?? 'date';
    
        // Account Union query
        $accountUnion = DB::table('chart_of_accounts')
            ->select(
                'id as coaid',
                'account_name',
                'code',
                'classification'
            )
            ->unionAll(
                DB::table('bk_sub_chart_of_accounts')
                    ->leftJoin('chart_of_accounts', 'bk_sub_chart_of_accounts.coaid', '=', 'chart_of_accounts.id')
                    ->select(
                        'bk_sub_chart_of_accounts.id as coaid',
                        'sub_account_name as account_name',
                        'sub_code as code',
                        'chart_of_accounts.classification'
                    )
            );
    
        // Base query for data and counts
        $baseQuery = DB::table('bk_generalledg')
            ->leftJoinSub($accountUnion, 'accounts', function ($join) {
                $join->on('bk_generalledg.coaid', '=', 'accounts.coaid');
            })
            ->where('bk_generalledg.deleted', 0);
    
        // Apply fiscal year filter if provided
        if ($fiscalYear) {
            $baseQuery->where('bk_generalledg.active_fiscal_year_id', $fiscalYear);
        }

        // Apply date filters
        if ($startDate && $endDate) {
            $baseQuery->whereBetween('bk_generalledg.date', [$startDate, $endDate]);
        }
    
        if ($costcenter_desc) {
            $baseQuery->where(function ($query) use ($costcenter_desc) {
                $query->where('accounts.account_name', 'like', '%-' . strtoupper($costcenter_desc))
                      ->orWhere('accounts.account_name', 'like', '%- ' . strtoupper($costcenter_desc));
            });
        }

       // Check if selected account is a COA
        $ifcoa = DB::table('chart_of_accounts')->where('id', $accountId)->where('deleted', 0)->first();
  
        if ($ifcoa) {
            $bk_sub_ids = DB::table('bk_sub_chart_of_accounts')
                ->where('coaid', $ifcoa->id)
                ->where('deleted', 0)
                ->pluck('id')
                ->unique()
                ->values();

            // Filter by sub-accounts of the COA
            $baseQuery->where(function ($q) use ($accountId, $bk_sub_ids) {
                $q->where('bk_generalledg.coaid', $accountId)
                  ->orWhereIn('bk_generalledg.coaid', $bk_sub_ids);
            });
        } elseif ($accountId) {
            // It's a BSCOA (child account), filter directly
            $baseQuery->where('bk_generalledg.coaid', (int) $accountId);
        }
    
        // Query for filtered data
        $dataQuery = clone $baseQuery;
        // $dataQuery->distinct()->select(
            $dataQuery->select(
            'bk_generalledg.voucherNo',
            'bk_generalledg.date',
            'bk_generalledg.coaid',
            'bk_generalledg.debit_amount',
            'bk_generalledg.credit_amount',
            'bk_generalledg.remarks',
            'bk_generalledg.sub',
            'accounts.account_name',
            'accounts.code',
            'accounts.classification',
            DB::raw('debit_amount - credit_amount as balance')
        );
    
        $totals = DB::table(DB::raw("({$dataQuery->toSql()}) as sub"))
            ->mergeBindings($dataQuery)
            ->select(
                DB::raw('COALESCE(SUM(debit_amount), 0) as total_debit'),
                DB::raw('COALESCE(SUM(credit_amount), 0) as total_credit'),
                DB::raw('COALESCE(SUM(debit_amount - credit_amount), 0) as total_balance')
            )
            ->first();
        // Apply search filter if provided
        if (!empty($searchValue)) {
            $dataQuery->where(function($q) use ($searchValue) {
                $q->where('bk_generalledg.voucherNo', 'like', "%$searchValue%")
                  ->orWhere('bk_generalledg.date', 'like', "%$searchValue%")
                  ->orWhere('bk_generalledg.remarks', 'like', "%$searchValue%")
                  ->orWhere('accounts.code', 'like', "%$searchValue%")
                  ->orWhere('accounts.account_name', 'like', "%$searchValue%");
            });
        }
    
        // Get filtered count
        $filteredCount = $dataQuery->count();
    
        // Get total records count (without search filters)
        $totalRecords = $baseQuery->count();
    
        // Get paginated data
        $data = $dataQuery
            ->orderBy($orderColumn, $orderDirection)
            ->skip($start)
            ->take($length)
            ->get();
  
        return response()->json([
            'draw' => intval($request->input('draw', 1)),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredCount,
            'data' => $data,
            'totalDebit' => $totals->total_debit,
            'totalCredit' => $totals->total_credit,
            'totalBalance' => $totals->total_balance
        ]);
    }

    // public function printSubsidiaryLedger(Request $request)
    // {
    //     // $cashtrans = DB::table('chrngtrans')
    //     //     ->join('chrngcashtrans', 'chrngtrans.transno', '=', 'chrngcashtrans.transno')
    //     //     ->where('chrngtrans.cancelled', 0)
    //     //     ->where('chrngcashtrans.deleted', 0)
    //     //     ->select(
    //     //         'chrngtrans.transdate',
    //     //         'chrngtrans.ornum',
    //     //         'chrngtrans.studname',
    //     //         'chrngtrans.paytype',
    //     //         'chrngcashtrans.particulars',
    //     //         'chrngcashtrans.amount',
    //     //         'chrngtrans.refno',
    //     //         'chrngtrans.sid',
    //     //         'chrngtrans.accountname',
    //     //         'chrngtrans.totalamount',
    //     //         'chrngtrans.amountpaid'
    //     //     )
    //     //     ->groupBy('chrngtrans.sid', 'chrngtrans.transdate', 'chrngtrans.ornum')
    //     //     ->get();

    //     // // Adjustments
    //     // $adjustments = DB::table('adjustments')
    //     //     ->select('refnum', 'description', 'amount', 'createddatetime', 'isdebit', 'iscredit')
    //     //     ->get()
    //     //     ->map(function ($item) {
    //     //         $item->debit_amount = $item->isdebit ? $item->amount : 0;
    //     //         $item->credit_amount = $item->iscredit ? $item->amount : 0;
    //     //         return $item;
    //     //     });

    //     $query = DB::table('bk_generalledg')
    //         ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //         ->join('bk_fiscal_year', 'bk_generalledg.active_fiscal_year_id', '=', 'bk_fiscal_year.id')
    //         ->select(
    //             'bk_generalledg.*',
    //             'bk_fiscal_year.description as fiscal_desc',
    //             'chart_of_accounts.classification',
    //             'chart_of_accounts.account_name',
    //             'bk_generalledg.remarks as explanation',
    //             'chart_of_accounts.code',
    //             'chart_of_accounts.account_type',
    //             'bk_generalledg.debit_amount',
    //             'bk_generalledg.credit_amount',
    //         );

    //     if (!empty($request->fiscal_year)) {
    //         $query->where('bk_generalledg.active_fiscal_year_id', $request->fiscal_year);
    //     }

    //     if (!empty($request->account_id)) {
    //         $query->where('bk_generalledg.coaid', $request->account_id);
    //     }

    //     $entries = $query->orderBy('bk_generalledg.date')->get();


    //     $signatories = DB::table('signatory')
    //             ->join('bk_signatory_grp', 'signatory.signatory_grp_id', '=', 'bk_signatory_grp.id')
    //             ->where('signatory.signatory_grp_id', 3)
    //             ->select('signatory.*', 'bk_signatory_grp.description')
    //             ->get();

    //     $fiscal_desc = $entries->first()->fiscal_desc ?? null;

    //     $schoolinfo = DB::table('schoolinfo')->first();

    //     // Generate PDF
    //     $pdf = Pdf::loadView('bookkeeper.pages.printables.SubsidiaryLedger_PDF', [
    //         'entries' => $entries,
    //         'fiscal_year' => $request->fiscal_year,
    //         'signatories' => $signatories,
    //         'fiscal_desc' => $fiscal_desc,
    //         'schoolinfo' => $schoolinfo,
    //         // 'cashtrans' => $cashtrans,          
    //         // 'adjustments' => $adjustments         
    //     ])->setPaper('A4', 'portrait');


    //     return $pdf->stream('SubsidiaryLedger_' . now()->format('Ymd_His') . '.pdf');
    // }

    // public function printSubsidiaryLedger(Request $request)
    // {
        
    //     // $query = DB::table('bk_generalledg')
    //     //     ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //     //     ->join('bk_fiscal_year', 'bk_generalledg.active_fiscal_year_id', '=', 'bk_fiscal_year.id')
    //     //     ->select(
    //     //         'bk_generalledg.*',
    //     //         'bk_fiscal_year.description as fiscal_desc',
    //     //         'chart_of_accounts.classification',
    //     //         'chart_of_accounts.account_name',
    //     //         'bk_generalledg.remarks as explanation',
    //     //         'chart_of_accounts.code',
    //     //         'chart_of_accounts.account_type',
    //     //         'bk_generalledg.debit_amount',
    //     //         'bk_generalledg.credit_amount',
    //     //     );

    //     $accountUnion = DB::table('chart_of_accounts')
    //         ->select(
    //             'id as coaid',
    //             'account_name',
    //             'code',
    //             'classification'
    //         )
    //         ->unionAll(
    //             DB::table('bk_sub_chart_of_accounts')
    //                 ->leftJoin('chart_of_accounts', 'bk_sub_chart_of_accounts.coaid', '=', 'chart_of_accounts.id')
    //                 ->select(
    //                     'bk_sub_chart_of_accounts.id as coaid',
    //                     'sub_account_name as account_name',
    //                     'sub_code as code',
    //                     'chart_of_accounts.classification'

    //                 )
    //         );
      
    //     $entries = DB::table('bk_generalledg')
    //         ->leftJoinSub($accountUnion, 'accounts', function ($join) {
    //             $join->on('bk_generalledg.coaid', '=', 'accounts.coaid');
    //         })
    //         ->join('bk_fiscal_year', 'bk_generalledg.active_fiscal_year_id', '=', 'bk_fiscal_year.id')
    //         ->select(
    //    'bk_generalledg.voucherNo',
    //             'bk_generalledg.date',
    //             'bk_generalledg.coaid',
    //             'bk_generalledg.debit_amount',
    //             'bk_generalledg.credit_amount',
    //             'bk_generalledg.remarks',
    //             'bk_generalledg.sub',
    //             'bk_generalledg.remarks as explanation',
    //             'bk_fiscal_year.description as fiscal_desc',
    //             'accounts.account_name',
    //             'accounts.code',
    //             'accounts.classification'
    //         )
    //         ->where('bk_generalledg.deleted', 0)
    //         // ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
    //         //     $q->whereBetween('bk_generalledg.date', [$startDate, $endDate]);
    //         // })
    //         ->orderBy('bk_generalledg.date', 'desc')
    //         ->get();

    //     // $query = DB::table('bk_generalledg')
    //     //     ->leftJoin('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //     //     ->leftJoin('bk_sub_chart_of_accounts', 'bk_generalledg.coaid', '=', 'bk_sub_chart_of_accounts.id')
    //     //     ->join('bk_fiscal_year', 'bk_generalledg.active_fiscal_year_id', '=', 'bk_fiscal_year.id')
    //     //     ->select(
    //     //     'bk_generalledg.voucherNo',
    //     //     'bk_generalledg.date',
    //     //     'bk_generalledg.coaid',
    //     //     'bk_generalledg.debit_amount',
    //     //     'bk_generalledg.credit_amount',
    //     //     'bk_generalledg.remarks',
    //     //     'bk_generalledg.sub',
    //     //     'bk_generalledg.remarks as explanation',
    //     //     'bk_fiscal_year.description as fiscal_desc',
    //     //     // Always include these from chart_of_accounts
    //     //     'chart_of_accounts.classification',
    //     //     'chart_of_accounts.account_name',
    //     //     'chart_of_accounts.code',
    //     //     'chart_of_accounts.account_type',

    //     //     // Conditionally display proper name and code
    //     //     DB::raw("CASE 
    //     //                 WHEN bk_generalledg.sub = 0 OR bk_generalledg.sub IS NULL 
    //     //                     THEN chart_of_accounts.account_name 
    //     //                     ELSE bk_sub_chart_of_accounts.sub_account_name 
    //     //             END AS account_name"),
    //     //     DB::raw("CASE 
    //     //                 WHEN bk_generalledg.sub = 0 OR bk_generalledg.sub IS NULL 
    //     //                     THEN chart_of_accounts.code 
    //     //                     ELSE bk_sub_chart_of_accounts.sub_code 
    //     //             END AS code")
                    
    //     // );

    //     // if (!empty($request->fiscal_year)) {
    //     //     $query->where('bk_generalledg.active_fiscal_year_id', $request->fiscal_year);
    //     // }

    //     // if (!empty($request->account_id)) {
    //     //     $query->where('bk_generalledg.coaid', $request->account_id);
    //     // }

    //     // $entries = $query->orderBy('bk_generalledg.date')->get();


    //     $signatories = DB::table('signatory')
    //             ->join('bk_signatory_grp', 'signatory.signatory_grp_id', '=', 'bk_signatory_grp.id')
    //             ->where('signatory.signatory_grp_id', 3)
    //             ->select('signatory.*', 'bk_signatory_grp.description')
    //             ->get();

    //     $fiscal_desc = $entries->first()->fiscal_desc ?? null;

    //     $schoolinfo = DB::table('schoolinfo')->first();

    //     // Generate PDF
    //     $pdf = Pdf::loadView('bookkeeper.pages.printables.SubsidiaryLedger_PDF', [
    //         'entries' => $entries,
    //         'fiscal_year' => $request->fiscal_year,
    //         'signatories' => $signatories,
    //         'fiscal_desc' => $fiscal_desc,
    //         'schoolinfo' => $schoolinfo,
    //         // 'cashtrans' => $cashtrans,          
    //         // 'adjustments' => $adjustments         
    //     ])->setPaper('A4', 'portrait');


    //     return $pdf->stream('SubsidiaryLedger_' . now()->format('Ymd_His') . '.pdf');
    // }

    // public function printSubsidiaryLedger(Request $request)
    // {
    //     $fiscalYear = DB::table('bk_fiscal_year')->where('isactive', $request->get('fiscalYear'))->first();

    //     if ($fiscalYear) {
    //         $startDate = \Carbon\Carbon::parse($fiscalYear->stime)->startOfDay();
    //         $endDate = \Carbon\Carbon::parse($fiscalYear->etime)->endOfDay();
    //     }
    //     $accountUnion = DB::table('chart_of_accounts')
    //         ->select(
    //             'id as coaid',
    //             'account_name',
    //             'code',
    //             'classification'
    //         )
    //         ->unionAll(
    //             DB::table('bk_sub_chart_of_accounts')
    //                 ->leftJoin('chart_of_accounts', 'bk_sub_chart_of_accounts.coaid', '=', 'chart_of_accounts.id')
    //                 ->select(
    //                     'bk_sub_chart_of_accounts.id as coaid',
    //                     'sub_account_name as account_name',
    //                     'sub_code as code',
    //                     'chart_of_accounts.classification'
    //                 )
    //         );
    
    //     $entries = DB::table('bk_generalledg')
    //         ->leftJoinSub($accountUnion, 'accounts', function ($join) {
    //             $join->on('bk_generalledg.coaid', '=', 'accounts.coaid');
    //         })
    //         ->join('bk_fiscal_year', 'bk_generalledg.active_fiscal_year_id', '=', 'bk_fiscal_year.id')
    //         ->select(
    //             'bk_generalledg.voucherNo',
    //             'bk_generalledg.date',
    //             'bk_generalledg.coaid',
    //             'bk_generalledg.debit_amount',
    //             'bk_generalledg.credit_amount',
    //             'bk_generalledg.remarks',
    //             'bk_generalledg.sub',
    //             'bk_generalledg.remarks as explanation',
    //             'bk_fiscal_year.description as fiscal_desc',
    //             'accounts.account_name',
    //             'accounts.code',
    //             'accounts.classification'
    //         )
    //         ->where('bk_generalledg.deleted', 0)
    //         ->orderBy('bk_generalledg.date', 'desc')
    //         ->get();

    //     $signatories = DB::table('signatory')
    //             ->join('bk_signatory_grp', 'signatory.signatory_grp_id', '=', 'bk_signatory_grp.id')
    //             ->where('signatory.signatory_grp_id', 3)
    //             ->select('signatory.*', 'bk_signatory_grp.description')
    //             ->get();

    //     $fiscal_desc = $entries->first()->fiscal_desc ?? null;
    //     $schoolinfo = DB::table('schoolinfo')->first();
        
    //     // Return HTML view instead of PDF
    //     return view('bookkeeper.pages.printables.SubsidiaryLedger_PDF', [
    //         'subsidiaryLedger' => $entries,
    //         'fiscal_year' => $request->fiscal_year,
    //         'signatories' => $signatories,
    //         'fiscal_desc' => $fiscal_desc,
    //         'schoolinfo' => $schoolinfo,
    //         'startDate' => $startDate,
    //         'endDate' => $endDate,
    //     ]);
    // }

    public function printSubsidiaryLedger(Request $request)
    {
        // Validate inputs
        $request->validate([
            'fiscal_year' => 'nullable|integer',
            'account_id' => 'nullable|integer'
        ]);
    
        $fiscalYear = DB::table('bk_fiscal_year')->where('id', $request->get('fiscal_year'))->first();

        if ($fiscalYear) {
            $startDate = \Carbon\Carbon::parse($fiscalYear->stime)->startOfDay();
            $endDate = \Carbon\Carbon::parse($fiscalYear->etime)->endOfDay();
        }

        // Get DataTable parameters
        $fiscalYear = $request->input('fiscal_year');
        $accountId = $request->input('account_id');
        $bk_sub_ids = [];

        // Column mappings for ordering
        $columns = [
            'voucherNo',
            'date',
            'remarks',
            'code',
            'account_name',
            'debit_amount',
            'credit_amount',
            DB::raw('debit_amount - credit_amount') // Balance
        ];
    
        // Account Union query
        $accountUnion = DB::table('chart_of_accounts')
            ->select(
                'id as coaid',
                'account_name',
                'code',
                'classification'
            )
            ->unionAll(
                DB::table('bk_sub_chart_of_accounts')
                    ->leftJoin('chart_of_accounts', 'bk_sub_chart_of_accounts.coaid', '=', 'chart_of_accounts.id')
                    ->select(
                        'bk_sub_chart_of_accounts.id as coaid',
                        'sub_account_name as account_name',
                        'sub_code as code',
                        'chart_of_accounts.classification'
                    )
            );
    
        // Base query for data and counts
        $baseQuery = DB::table('bk_generalledg')
            ->leftJoinSub($accountUnion, 'accounts', function ($join) {
                $join->on('bk_generalledg.coaid', '=', 'accounts.coaid');
            })
            ->where('bk_generalledg.deleted', 0);
    
        // Apply fiscal year filter if provided
        if ($fiscalYear) {
            $baseQuery->where('bk_generalledg.active_fiscal_year_id', $fiscalYear);
        }
    
       // Check if selected account is a COA
        $ifcoa = DB::table('chart_of_accounts')->where('id', $accountId)->where('deleted', 0)->first();
  
        if ($ifcoa) {
            $bk_sub_ids = DB::table('bk_sub_chart_of_accounts')
                ->where('coaid', $ifcoa->id)
                ->where('deleted', 0)
                ->pluck('id')
                ->unique()
                ->values();

            // Filter by sub-accounts of the COA
            $baseQuery->where(function ($q) use ($accountId, $bk_sub_ids) {
                $q->where('bk_generalledg.coaid', $accountId)
                  ->orWhereIn('bk_generalledg.coaid', $bk_sub_ids);
            });
        } elseif ($accountId) {
            // It's a BSCOA (child account), filter directly
            $baseQuery->where('bk_generalledg.coaid', (int) $accountId);
        }
    
        // Query for filtered data
        $dataQuery = clone $baseQuery;
        // $dataQuery->distinct()->select(
            $dataQuery->select(
            'bk_generalledg.voucherNo',
            'bk_generalledg.date',
            'bk_generalledg.coaid',
            'bk_generalledg.debit_amount',
            'bk_generalledg.credit_amount',
            'bk_generalledg.remarks',
            'bk_generalledg.sub',
            'accounts.account_name',
            'accounts.code',
            'accounts.classification',
            DB::raw('debit_amount - credit_amount as balance')
        );
    
     
        // Get paginated data
        $data = $dataQuery->get();
    
        $signatories = DB::table('signatory')
            ->join('bk_signatory_grp', 'signatory.signatory_grp_id', '=', 'bk_signatory_grp.id')
            ->where('signatory.signatory_grp_id', 3)
            ->select('signatory.*', 'bk_signatory_grp.description')
            ->get();

        $fiscal_desc = $data->first()->fiscal_desc ?? null;
        $schoolinfo = DB::table('schoolinfo')->first();
        
        // Return HTML view instead of PDF
        return view('bookkeeper.pages.printables.SubsidiaryLedger_PDF', [
            'subsidiaryLedger' => $data,
            'fiscal_year' => $request->fiscal_year,
            'signatories' => $signatories,
            'fiscal_desc' => $fiscal_desc,
            'schoolinfo' => $schoolinfo,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);    
    }

    // public function subsidiary_account_fetch(Request $request)
    // {
    //     $mainAccounts = DB::table('bk_generalledg')
    //         ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //         ->select(
    //             'bk_generalledg.coaid as id',
    //             DB::raw("NULL as fkid"), 
    //             DB::raw("0 as sub"),
    //             DB::raw("CONCAT(chart_of_accounts.code, ' - ', chart_of_accounts.account_name) as text"),
    //             'chart_of_accounts.code',
    //             'chart_of_accounts.account_name'
    //         )
    //         ->where('bk_generalledg.deleted', 0)
    //         ->where('chart_of_accounts.deleted', 0)
    //         ->distinct();

    //     // Sub accounts query - assuming there's a sub_coaid field for sub-accounts
    //     // If your structure is different, adjust the join condition accordingly
    //     $subAccounts = DB::table('bk_generalledg')
    //         ->join('bk_sub_chart_of_accounts', 'bk_generalledg.coaid', '=', 'bk_sub_chart_of_accounts.id')
    //         ->select(
    //             'bk_sub_chart_of_accounts.id as id',
    //             'bk_sub_chart_of_accounts.coaid as fkid',
    //             DB::raw("1 as sub"),
    //             DB::raw("CONCAT(bk_sub_chart_of_accounts.sub_code, ' - ', bk_sub_chart_of_accounts.sub_account_name, ' (Sub)') as text"),
    //             'bk_sub_chart_of_accounts.sub_code as code',
    //             'bk_sub_chart_of_accounts.sub_account_name as account_name'
    //         )
    //         ->where('bk_generalledg.deleted', 0)
    //         ->where('bk_sub_chart_of_accounts.deleted', 0)
    //         ->distinct();

    //     $combined = $mainAccounts->unionAll($subAccounts)->get();

    //     return response()->json(['results' => $combined]);
    // }

    public function subsidiary_account_fetch(Request $request)
    {
        // 1. First fetch all main accounts from the general ledger
        $mainAccounts = DB::table('bk_generalledg')
            ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
            ->select(
                'chart_of_accounts.id as id',
                DB::raw("NULL as fkid"),
                DB::raw("0 as sub"),
                DB::raw("CONCAT(chart_of_accounts.code, ' - ', chart_of_accounts.account_name) as text"),
                'chart_of_accounts.code',
                'chart_of_accounts.account_name'
            )
            ->where('bk_generalledg.deleted', 0)
            ->where('chart_of_accounts.deleted', 0)
            ->distinct()
            ->get();

        // 2. Fetch all sub-accounts
        $subAccounts = DB::table('bk_generalledg')
            ->join('bk_sub_chart_of_accounts', 'bk_generalledg.coaid', '=', 'bk_sub_chart_of_accounts.id')
            ->select(
                'bk_sub_chart_of_accounts.id as id',
                'bk_sub_chart_of_accounts.coaid as fkid',
                DB::raw("1 as sub"),
                DB::raw("CONCAT(bk_sub_chart_of_accounts.sub_code, ' - ', bk_sub_chart_of_accounts.sub_account_name, ' (Sub)') as text"),
                'bk_sub_chart_of_accounts.sub_code as code',
                'bk_sub_chart_of_accounts.sub_account_name as account_name'
            )
            ->where('bk_generalledg.deleted', 0)
            ->where('bk_sub_chart_of_accounts.deleted', 0)
            ->distinct()
            ->get();

        // 3. Identify orphaned sub-accounts (those whose parents aren't in mainAccounts)
        $parentIds = $mainAccounts->pluck('id')->toArray();
        $orphanSubs = $subAccounts->filter(function($sub) use ($parentIds) {
            return !in_array($sub->fkid, $parentIds);
        });

        // 4. Fetch missing parent accounts for orphans
        $missingParentIds = $orphanSubs->pluck('fkid')->unique()->toArray();
        $missingParents = [];
        
        if (!empty($missingParentIds)) {
            $missingParents = DB::table('chart_of_accounts')
                ->whereIn('id', $missingParentIds)
                ->where('deleted', 0)
                ->select(
                    'id',
                    DB::raw("NULL as fkid"),
                    DB::raw("0 as sub"),
                    DB::raw("CONCAT(code, ' - ', account_name) as text"),
                    'code',
                    'account_name'
                )
                ->get();
        }

        // 5. Combine all accounts
        $allAccounts = $mainAccounts->merge($missingParents)->merge($subAccounts);

        return response()->json([
            'results' => $allAccounts,
            'orphans' => $orphanSubs,
            'missing_parents' => $missingParents
        ]);
    }

    public function subsidiary_costcenter_fetch(Request $request){

        $cost_centers = DB::table('acc_costcenters')
            ->select('id','description as text')
            ->where('deleted', 0)
            ->get();

        return $cost_centers;
    }
}