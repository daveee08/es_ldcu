<?php

namespace App\Http\Controllers\BookkeeperControllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use DB;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use PDF;

class BalanceSheetController extends Controller
{
    // public function displayBalanceSheet(Request $request)
    // {
    //     $fiscalYears = (array) $request->input('fiscal_year');
    //     $dateRange = $request->input('date_range');
    //     $results = [];

    //     // Initialize variables for date range
    //     $startDate = null;
    //     $endDate = null;

    //     // Check if date_range is present and not empty
    //     if ($request->has('date_range') && !empty($request->date_range)) {
    //         $dates = explode(' - ', $request->date_range);

    //         if (count($dates) == 2) {
    //             try {
    //                 $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dates[0]))->startOfDay();
    //                 $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dates[1]))->endOfDay();
    //             } catch (\Exception $e) {
    //                 return response()->json(['error' => 'Invalid date format.'], 400);
    //             }
    //         }
    //     }

    //     foreach ($fiscalYears as $yearId) {
    //         $fiscal = DB::table('bk_fiscal_year')->where('id', $yearId)->first();

    //         if (!$fiscal) {
    //             continue;
    //         }

    //         // General Ledger Query
    //         $query = DB::table('bk_generalledg')
    //             ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //             ->select(
    //                 'chart_of_accounts.id',
    //                 'chart_of_accounts.code',
    //                 'chart_of_accounts.classification',
    //                 'chart_of_accounts.account_name',
    //                 DB::raw('SUM(bk_generalledg.debit_amount) as debit_amount'),
    //                 DB::raw('SUM(bk_generalledg.credit_amount) as credit_amount')
    //             )
    //             ->where('bk_generalledg.active_fiscal_year_id', $yearId)
    //             ->groupBy('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.classification');

    //         if (!empty($dateRange)) {
    //             try {
    //                 [$start, $end] = explode(' - ', $dateRange);
    //                 $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($start))->startOfDay();
    //                 $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($end))->endOfDay();
    //                 $query->whereBetween('bk_generalledg.date', [$startDate, $endDate]);
    //             } catch (\Exception $e) {
    //                 return response()->json(['error' => 'Invalid date format.'], 400);
    //             }
    //         }

    //         $data = $query->get()->transform(function ($item) {
    //             $item->ending_balance = $item->debit_amount - $item->credit_amount;
    //             return $item;
    //         });

    //         // Fixed Assets Query
    //         $fixedAssets = DB::table('bk_fixedassets')
    //             ->select('id', 'asset_name', 'purchased_date', 'asset_value')
    //             ->whereBetween('purchased_date', [$fiscal->stime, $fiscal->etime])
    //             ->get();

    //         // Cash Transactions
    //         $cashtransQuery = DB::table('chrngtrans')
    //             ->select([
    //                 'chrngtrans.transdate',
    //                 'chrngtrans.ornum',
    //                 'chrngtrans.studid',
    //                 'chrngtrans.studname',
    //                 'chrngtrans.paytype',
    //                 'chrngtrans.totalamount',
    //                 'chrngtrans.amountpaid',
    //                 'chrngtrans.refno',
    //                 'chrngtrans.sid',
    //                 'chrngtrans.accountname',
    //                 'studinfo.firstname',
    //                 'studinfo.lastname',
    //                 'studinfo.middlename',
    //                 'coa_debit.code as debit_account_code',
    //                 'coa_debit.account_name as debit_account',
    //                 'coa_credit.code as credit_account_code',
    //                 'coa_credit.account_name as credit_account',
    //                 'coa_debit.classification as debit_classification',
    //                 'coa_credit.classification as credit_classification',
    //             ])
    //             ->distinct()
    //             ->where('chrngtrans.cancelled', 0)
    //             ->leftJoin('studinfo', 'chrngtrans.studid', '=', 'studinfo.id')
    //             ->leftJoin('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
    //             ->join('bk_classifiedsetup', 'studinfo.levelid', '=', 'bk_classifiedsetup.levelid')
    //             ->leftJoin('chart_of_accounts as coa_debit', 'bk_classifiedsetup.debitaccid', '=', 'coa_debit.id')
    //             ->leftJoin('chart_of_accounts as coa_credit', 'bk_classifiedsetup.creditaccid', '=', 'coa_credit.id');

    //         if ($startDate && $endDate) {
    //             $cashtransQuery->whereBetween('transdate', [$startDate, $endDate]);
    //         }

    //         $cashtrans = $cashtransQuery->get();
    //         // return $cashtrans;

    //         // Adjustments Query
    //         $adjustmentsQuery = DB::table('adjustments')
    //             ->select('adjustments.refnum as voucherNo', 
    //                 'adjustments.description as remarks', 
    //                 'adjustments.createddatetime as transdate', 
    //                 'adjustments.amount', 
    //                 'adjustments.isdebit', 
    //                 'adjustments.iscredit',
    //                 'adjustmentdetails.studid',
    //                 'studinfo.firstname',
    //                 'studinfo.lastname',
    //                 'studinfo.middlename',
    //                 'coa_debit.code as debit_account_code',
    //                 'coa_debit.account_name as debit_account',
    //                 'coa_credit.code as credit_account_code',
    //                 'coa_credit.account_name as credit_account',
    //                 'coa_debit.classification as debit_classification',
    //                 'coa_credit.classification as credit_classification',
    //             )   
    //             ->distinct()
    //             ->where('adjustments.deleted', 0)
    //             ->join('adjustmentdetails', 'adjustmentdetails.headerid', '=', 'adjustments.id')
    //             ->join('studinfo', 'adjustmentdetails.studid', '=', 'studinfo.id')
    //             ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
    //             ->leftJoin('bk_classifiedsetup', 'studinfo.levelid', '=', 'bk_classifiedsetup.levelid')
    //             ->leftJoin('chart_of_accounts as coa_debit', 'bk_classifiedsetup.debitaccid', '=', 'coa_debit.id')
    //             ->leftJoin('chart_of_accounts as coa_credit', 'bk_classifiedsetup.creditaccid', '=', 'coa_credit.id');

    //         if ($startDate && $endDate) {
    //             $adjustmentsQuery->whereBetween('adjustments.createddatetime', [$startDate, $endDate]);
    //         }

    //         $adjustments = $adjustmentsQuery->get();
            

    //         // Discounts Query
    //         $discountQuery = DB::table('studdiscounts')
    //             ->select(
    //                 'studdiscounts.createddatetime as transdate', 
    //                 'studdiscounts.discamount as amount', 
    //                 'studdiscounts.studid',
    //                 'studinfo.firstname',
    //                 'studinfo.lastname',
    //                 'studinfo.middlename',
    //                 'coa_debit.code as debit_account_code',
    //                 'coa_debit.account_name as debit_account',
    //                 'coa_credit.code as credit_account_code',
    //                 'coa_credit.account_name as credit_account')
    //             ->distinct()
    //             ->where('studdiscounts.deleted', 0)
    //             ->join('studinfo', 'studdiscounts.studid', '=', 'studinfo.id')
    //             ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
    //             ->leftJoin('bk_classifiedsetup', 'studinfo.levelid', '=', 'bk_classifiedsetup.levelid')
    //             ->leftJoin('chart_of_accounts as coa_debit', 'bk_classifiedsetup.debitaccid', '=', 'coa_debit.id')
    //             ->leftJoin('chart_of_accounts as coa_credit', 'bk_classifiedsetup.creditaccid', '=', 'coa_credit.id');

    //         if ($startDate && $endDate) {
    //             $discountQuery->whereBetween('studdiscounts.createddatetime', [$startDate, $endDate]);
    //         }

    //         $discounts = $discountQuery->get();

    //         // Combine all results
    //         $results[] = [
    //             'fiscal_year_id' => $yearId,
    //             'fiscal_year_description' => $fiscal->description ?? '',
    //             'data' => $data,
    //             'fixed_assets' => $fixedAssets,
    //             'cash_transactions' => $cashtrans,
    //             'adjustments' => $adjustments,
    //             'discounts' => $discounts
    //         ];
    //     }

    //     return response()->json($results);
    // }

    public function displayBalanceSheet(Request $request)
    {
        $fiscalYearId = $request->input('fiscal_year');
        $fiscalYears = (array) $request->input('fiscal_year');

        $fiscal = DB::table('bk_fiscal_year')
            ->where('id', $fiscalYearId)
            ->where('isactive', 1)
            ->where('deleted', 0)
            ->first();
        
        $dateRange = $request->input('date_range');
        $results = [];

        // Initialize variables for date range
        $startDate = null;
        $endDate = null;

        // Check if date_range is present and not empty
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

        $balanceSheet = DB::table('bk_statement_type')->where('desc', 'Balance Sheets')->first();
        
        // $main_accounts = DB::table('chart_of_accounts')
        //     ->select('id', 'account_type', 'classification')
        //     ->whereIn('account_type', [6, 7, 8, 9])
        //     ->where('deleted', 0)
        //     ->get();

        $main_accounts = DB::table('chart_of_accounts')
            ->select('id', 'account_type', 'classification')
            ->whereIn('classification', ['assets', 'liabilities', 'equity'])
            ->where('deleted', 0)
            ->get();
       
        // $bk_accounts = DB::table('chart_of_accounts')
        //     ->select('bk_sub_chart_of_accounts.id', 'chart_of_accounts.account_type', 'chart_of_accounts.classification')
        //     ->join('bk_sub_chart_of_accounts', 'chart_of_accounts.id', '=', 'bk_sub_chart_of_accounts.coaid')
        //     ->where('chart_of_accounts.deleted', 0)
        //     ->where('bk_sub_chart_of_accounts.deleted', 0)
        //     ->whereIn('chart_of_accounts.account_type', [6, 7, 8, 9])
        //     ->get();

        $bk_accounts = DB::table('chart_of_accounts')
            ->select('bk_sub_chart_of_accounts.id', 'chart_of_accounts.account_type', 'chart_of_accounts.classification')
            ->join('bk_sub_chart_of_accounts', 'chart_of_accounts.id', '=', 'bk_sub_chart_of_accounts.coaid')
            ->where('chart_of_accounts.deleted', 0)
            ->where('bk_sub_chart_of_accounts.deleted', 0)
            ->whereIn('chart_of_accounts.classification', ['liabilities', 'equity'])
            ->get();

        // Separate into revenue and expenses
        // $main_accounts_assets = collect($main_accounts)->whereIn('account_type', [6, 7])->values();
        // $bk_accounts_assets = collect($bk_accounts)->whereIn('account_type', [6, 7])->values();

        // $main_accounts_liabilities = collect($main_accounts)->whereIn('account_type', [8, 9])->values();
        // $bk_accounts_liabilities = collect($bk_accounts)->whereIn('account_type', [8, 9])->values();

        // Separate into revenue and expenses
        $main_accounts_assets = collect($main_accounts)->whereIn('classification', ['assets'])->values();
        $bk_accounts_assets = collect($bk_accounts)->whereIn('classification', ['assets'])->values();
        $main_accounts_liabilities = collect($main_accounts)->whereIn('classification', ['liabilities', 'equity'])->values();
        $bk_accounts_liabilities = collect($bk_accounts)->whereIn('classification', ['liabilities', 'equity'])->values();
      
        // ✅ Merge and pluck IDs for revenue
        $assets_types = $main_accounts_assets->merge($bk_accounts_assets->values());
        $assets_ids = $main_accounts_assets->pluck('id')
            ->merge($bk_accounts_assets->pluck('id'))
            ->unique()
            ->values();

        // ✅ Merge and pluck IDs for expenses
        $liability_types = $main_accounts_liabilities->merge($bk_accounts_liabilities->values());
   
        $liability_equity_ids = $main_accounts_liabilities->pluck('id')
            ->merge($bk_accounts_liabilities->pluck('id'))
            ->unique()
            ->values();
        
        $accountUnion = DB::table('chart_of_accounts')
            ->select(
                'id as coaid',
                'account_name',
                'code'
            )
            ->unionAll(
                DB::table('bk_sub_chart_of_accounts')
                    ->select(
                        'id as coaid',
                        'sub_account_name as account_name',
                        'sub_code as code'
                    )
            );
        
        $ledgerQuery = DB::table('bk_generalledg')
            ->leftJoinSub($accountUnion, 'accounts', function ($join) {
                $join->on('bk_generalledg.coaid', '=', 'accounts.coaid');
            })
            ->select(
                'bk_generalledg.voucherNo',
                'bk_generalledg.date',
                'bk_generalledg.coaid',
                'bk_generalledg.debit_amount',
                'bk_generalledg.credit_amount',
                'bk_generalledg.remarks',
                'bk_generalledg.sub',
                'accounts.account_name',
                'accounts.code',
            )
            ->where('bk_generalledg.deleted', 0);
       
        // Apply filters if conditions are met
        if ($startDate && $endDate) {
            $ledgerQuery->whereBetween('bk_generalledg.date', [$startDate, $endDate]);
        }
        
        if ($fiscalYearId) {
            $ledgerQuery->where('bk_generalledg.active_fiscal_year_id', $fiscalYearId);
        }
        
        // Finally, order and get the results
        $ledgerData = $ledgerQuery->orderBy('bk_generalledg.date', 'desc')->get()->map(function ($item) {
            $debit = floatval($item->debit_amount);
            $credit = floatval($item->credit_amount);
        
            // Set total_amount based on which is non-zero
            $item->total_amount = $debit != 0.00 ? number_format($debit, 2, '.', '') : number_format($credit, 2, '.', '');
            
            return $item;
        });
    
        $group_assets = collect($ledgerData)
            ->whereIn('coaid', $assets_ids)
            ->groupBy('coaid')
            ->map(function ($items, $coaid) use ($assets_types)  {
                $accountType = collect($assets_types)
                    ->firstWhere('id', $coaid)
                    ->account_type ?? null;
        
                // Add `account_type` to each item in 'data'
                $dataWithType = $items->map(function ($item) use ($accountType) {
                    $item->account_type = $accountType;
                    return $item;
                });

                return [
                    'coaid' => $coaid,
                    'total_amount' => $items->sum('total_amount'),
                    'code' => $items->first()->code,
                    'account_name' => $items->first()->account_name,
                    'data' => $items->values(),
                ];
            })
            ->values();
      
        $group_liablities_equity = collect($ledgerData)
            ->whereIn('coaid', $liability_equity_ids)
            ->groupBy('coaid')
            ->map(function ($items, $coaid) use ($liability_types) {

                $accountType = collect($liability_types)
                    ->firstWhere('id', $coaid)
                    ->account_type ?? null;
        
                // Add `account_type` to each item in 'data'
                $dataWithType = $items->map(function ($item) use ($accountType) {
                    $item->account_type = $accountType;
                    return $item;
                });

                return [
                    'coaid' => $coaid,
                    'total_amount' => $items->sum('total_amount'),
                    'code' => $items->first()->code,
                    'account_name' => $items->first()->account_name,
                    'data' => $items->values(),
                ];
            })
            ->values();
  
        // Fixed Assets Query
        $fixedAssets = DB::table('bk_fixedassets')
            ->select('id', 'asset_name', 'purchased_date', 'asset_value')
            ->whereBetween('purchased_date', [$fiscal->stime, $fiscal->etime])
            ->get();
    
        // Combine all results
        $results[] = [
            'fiscal_year_id' => $fiscalYearId,
            'fiscal_year_description' => $fiscal->description ?? '',
            'data' => $ledgerData,
            'fixed_assets' => $fixedAssets,
            'group_assets' => $group_assets,
            'group_liablities_equity' => $group_liablities_equity,
            'cash_transactions' => [],
            'adjustments' => [],
            'discounts' => []
        ];

        // foreach ($fiscalYears as $yearId) {
        //     $fiscal = DB::table('bk_fiscal_year')->where('id', $yearId)->first();

        //     if (!$fiscal) {
        //         continue;
        //     }

        //     // General Ledger Query
        //     $query = DB::table('bk_generalledg')
        //         ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
        //         ->select(
        //             'chart_of_accounts.id',
        //             'chart_of_accounts.code',
        //             'chart_of_accounts.classification',
        //             'chart_of_accounts.account_name',
        //             DB::raw('SUM(bk_generalledg.debit_amount) as debit_amount'),
        //             DB::raw('SUM(bk_generalledg.credit_amount) as credit_amount')
        //         )
        //         ->where('bk_generalledg.active_fiscal_year_id', $yearId)
        //         ->groupBy('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.classification');

        //     if (!empty($dateRange)) {
        //         try {
        //             [$start, $end] = explode(' - ', $dateRange);
        //             $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($start))->startOfDay();
        //             $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($end))->endOfDay();
        //             $query->whereBetween('bk_generalledg.date', [$startDate, $endDate]);
        //         } catch (\Exception $e) {
        //             return response()->json(['error' => 'Invalid date format.'], 400);
        //         }
        //     }

        //     $data = $query->get()->transform(function ($item) {
        //         $item->ending_balance = $item->debit_amount - $item->credit_amount;
        //         return $item;
        //     });

        //     // Fixed Assets Query
        //     $fixedAssets = DB::table('bk_fixedassets')
        //         ->select('id', 'asset_name', 'purchased_date', 'asset_value')
        //         ->whereBetween('purchased_date', [$fiscal->stime, $fiscal->etime])
        //         ->get();

           
        //     // Combine all results
        //     $results[] = [
        //         'fiscal_year_id' => $yearId,
        //         'fiscal_year_description' => $fiscal->description ?? '',
        //         'data' => $data,
        //         'fixed_assets' => $fixedAssets,
        //         'cash_transactions' => [],
        //         'adjustments' => [],
        //         'discounts' => []
        //     ];
        // }

        return response()->json($results);
    }
    
    public function generateBalanceSheetPDF(Request $request)
    {
        $schoolinfo = DB::table('schoolinfo')->first();
        $fiscalYearId = $request->input('fiscal_year_id');
        $dateRange = $request->input('date_range');
        // Get fiscal year dates
        $fiscalYear = DB::table('bk_fiscal_year')->where('id', $fiscalYearId)->first();

        if (!$fiscalYear) {
            return response()->json(['error' => 'Invalid fiscal year.'], 400);
        }

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

        // Main ledger query
        $query = DB::table('bk_generalledg')
            ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
            ->select(
                'chart_of_accounts.id',
                'chart_of_accounts.code',
                'chart_of_accounts.classification',
                DB::raw('SUM(bk_generalledg.debit_amount) as debit_amount'),
                DB::raw('SUM(bk_generalledg.credit_amount) as credit_amount')
            )
            ->where('chart_of_accounts.deleted', 0)
            ->whereBetween('bk_generalledg.date', [$startDate, $endDate])
            ->groupBy('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.classification');

        $data = $query->get()->transform(function ($item) {
            $item->ending_balance = (float) $item->debit_amount - (float) $item->credit_amount;
            return $item;
        })->groupBy('classification');

        // Fixed assets (optional: filter by purchase date if needed)
        $fixedAssets = DB::table('bk_fixedassets')->get();

        return PDF::loadView('bookkeeper.pages.printables.BalanceSheet_PDF', compact('schoolinfo', 'fixedAssets', 'data'))
            ->stream('BalanceSheet_PDF.pdf');
    }
}


