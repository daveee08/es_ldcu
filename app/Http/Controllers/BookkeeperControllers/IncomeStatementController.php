<?php

namespace App\Http\Controllers\BookkeeperControllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use DB;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use PDF;

class IncomeStatementController extends Controller
{
    // public function displayIncomeStatement(Request $request)
    // {
    //     $fiscalYearId = $request->input('fiscal_year_id');
    //     $dateRange = $request->input('date_range');

    //     $startDate = null;
    //     $endDate = null;

    //     // Parse date range
    //     if ($dateRange) {
    //         [$startDate, $endDate] = explode(' - ', $dateRange);
    //         try {
    //             $startDate = \Carbon\Carbon::createFromFormat('Y-m-d', trim($startDate))->startOfDay();
    //             $endDate = \Carbon\Carbon::createFromFormat('Y-m-d', trim($endDate))->endOfDay();
    //         } catch (\Exception $e) {
    //             return response()->json(['error' => 'Invalid date format.'], 400);
    //         }
    //     } elseif ($fiscalYearId) {
    //         $fiscalYear = DB::table('bk_fiscal_year')
    //             ->where('id', $fiscalYearId)
    //             ->where('isactive', 1)
    //             ->where('deleted', 0)
    //             ->first();
        
    //         if (!$fiscalYear) {
    //             return response()->json(['error' => 'Invalid or inactive fiscal year.'], 400);
    //         }
        
    //         $startDate = \Carbon\Carbon::parse($fiscalYear->stime)->startOfDay();
    //         $endDate = \Carbon\Carbon::parse($fiscalYear->etime)->endOfDay();
    //     } else {
    //         // Default to current month
    //         $startDate = \Carbon\Carbon::now()->startOfMonth()->startOfDay();
    //         $endDate = \Carbon\Carbon::now()->endOfMonth()->endOfDay();
    //     }

    //     // Get enrollment dates for all students who are enrolled
    //     $enrolledStudents = DB::table('studinfo')
    //         ->select(
    //             'studinfo.id',
    //             'studinfo.firstname',
    //             'studinfo.lastname',
    //             'studinfo.middlename',
    //             DB::raw('
    //                 COALESCE(
    //                     enrolledstud.dateenrolled,
    //                     sh_enrolledstud.dateenrolled,
    //                     college_enrolledstud.date_enrolled
    //                 ) AS dateenrolled
    //             ')
    //         )
    //         ->leftJoin('enrolledstud', function ($join) {
    //             $join->on('studinfo.id', '=', 'enrolledstud.studid')
    //                 ->where('enrolledstud.deleted', 0);
    //         })
    //         ->leftJoin('sh_enrolledstud', function ($join) {
    //             $join->on('studinfo.id', '=', 'sh_enrolledstud.studid')
    //                 ->where('sh_enrolledstud.deleted', 0);
    //         })
    //         ->leftJoin('college_enrolledstud', function ($join) {
    //             $join->on('studinfo.id', '=', 'college_enrolledstud.studid')
    //                 ->where('college_enrolledstud.deleted', 0);
    //         })
    //         ->where(function ($query) {
    //             $query->whereNotNull('enrolledstud.studid')
    //                 ->orWhereNotNull('sh_enrolledstud.studid')
    //                 ->orWhereNotNull('college_enrolledstud.studid');
    //         })
    //         ->get()
    //         ->keyBy('id');

    //     // 1. General Ledger Data (Revenue and Expenses)
    //     $ledgerQuery = DB::table('bk_generalledg')
    //         ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //         ->where('chart_of_accounts.deleted', 0);

    //     if ($startDate && $endDate) {
    //         $ledgerQuery->whereBetween('bk_generalledg.date', [$startDate, $endDate]);
    //     }

    //     if ($fiscalYearId) {
    //         $ledgerQuery->where('bk_generalledg.active_fiscal_year_id', $fiscalYearId);
    //     }

    //     $ledgerData = $ledgerQuery->select(
    //         'chart_of_accounts.id',
    //         'chart_of_accounts.classification',
    //         'chart_of_accounts.account_name',
    //         'chart_of_accounts.code',
    //         DB::raw('SUM(bk_generalledg.credit_amount - bk_generalledg.debit_amount) as total_amount')
    //     )
    //     ->groupBy(
    //         'chart_of_accounts.id',
    //         'chart_of_accounts.classification',
    //         'chart_of_accounts.account_name',
    //         'chart_of_accounts.code'
    //     )
    //     ->get();

    //     // 2. Student Ledger Data (Revenue from student charges)
    //     $studledgerQuery = DB::table('studledger')
    //         ->join('studinfo', 'studledger.studid', '=', 'studinfo.id')
    //         ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
    //         ->join('bk_classifiedsetup', 'studinfo.levelid', '=', 'bk_classifiedsetup.levelid')
    //         ->leftJoin('chart_of_accounts as coa_credit', 'bk_classifiedsetup.creditaccid', '=', 'coa_credit.id')
    //         ->leftJoin('enrolledstud', function($join) {
    //             $join->on('studledger.studid', '=', 'enrolledstud.studid')
    //                 ->where('enrolledstud.deleted', 0);
    //         })
    //         ->leftJoin('sh_enrolledstud', function($join) {
    //             $join->on('studledger.studid', '=', 'sh_enrolledstud.studid')
    //                 ->where('sh_enrolledstud.deleted', 0);
    //         })
    //         ->leftJoin('college_enrolledstud', function($join) {
    //             $join->on('studledger.studid', '=', 'college_enrolledstud.studid')
    //                 ->where('college_enrolledstud.deleted', 0);
    //         })
    //         ->where('studledger.deleted', 0)
    //         ->where('studledger.payment', 0) // Only charges, not payments
    //         ->where(function($query) {
    //             $query->whereNotNull('enrolledstud.studid')
    //                 ->orWhereNotNull('sh_enrolledstud.studid')
    //                 ->orWhereNotNull('college_enrolledstud.studid');
    //         });

    //     if ($startDate && $endDate) {
    //         $studledgerQuery->whereBetween('studledger.createddatetime', [$startDate, $endDate]);
    //     }

    //     $studledgerData = $studledgerQuery->select(
    //         'coa_credit.id as id',
    //         'coa_credit.classification',
    //         'coa_credit.account_name',
    //         'coa_credit.code',
    //         DB::raw("SUM(studledger.amount) as total_amount")
    //     )
    //     ->groupBy(
    //         'coa_credit.id',
    //         'coa_credit.classification',
    //         'coa_credit.account_name',
    //         'coa_credit.code'
    //     )
    //     ->get();

    //     // 3. Cash Transactions (Revenue from payments)
    //     $cashtransQuery = DB::table('chrngtrans')
    //         ->leftJoin('studinfo', 'chrngtrans.studid', '=', 'studinfo.id')
    //         ->leftJoin('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
    //         ->join('bk_classifiedsetup', 'studinfo.levelid', '=', 'bk_classifiedsetup.levelid')
    //         ->leftJoin('chart_of_accounts as coa_credit', 'bk_classifiedsetup.creditaccid', '=', 'coa_credit.id')
    //         ->where('chrngtrans.cancelled', 0);

    //     if ($startDate && $endDate) {
    //         $cashtransQuery->whereBetween('chrngtrans.transdate', [$startDate, $endDate]);
    //     }

    //     $cashtransData = $cashtransQuery->select(
    //         'coa_credit.id as id',
    //         'coa_credit.classification',
    //         'coa_credit.account_name',
    //         'coa_credit.code',
    //         DB::raw("SUM(chrngtrans.amountpaid) as total_amount")
    //     )
    //     ->groupBy(
    //         'coa_credit.id',
    //         'coa_credit.classification',
    //         'coa_credit.account_name',
    //         'coa_credit.code'
    //     )
    //     ->get();

    //     // 4. Adjustments (Revenue adjustments)
    //     $adjustmentsQuery = DB::table('adjustments')
    //         ->join('adjustmentdetails', 'adjustmentdetails.headerid', '=', 'adjustments.id')
    //         ->join('studinfo', 'adjustmentdetails.studid', '=', 'studinfo.id')
    //         ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
    //         ->leftJoin('bk_classifiedsetup', 'studinfo.levelid', '=', 'bk_classifiedsetup.levelid')
    //         ->leftJoin('chart_of_accounts as coa_credit', 'bk_classifiedsetup.creditaccid', '=', 'coa_credit.id')
    //         ->where('adjustments.deleted', 0)
    //         ->where('adjustmentdetails.deleted', 0);

    //     if ($startDate && $endDate) {
    //         $adjustmentsQuery->whereBetween('adjustments.createddatetime', [$startDate, $endDate]);
    //     }

    //     $adjustmentsData = $adjustmentsQuery->select(
    //         'coa_credit.id as id',
    //         'coa_credit.classification',
    //         'coa_credit.account_name',
    //         'coa_credit.code',
    //         DB::raw("SUM(adjustments.amount) as total_amount")
    //     )
    //     ->groupBy(
    //         'coa_credit.id',
    //         'coa_credit.classification',
    //         'coa_credit.account_name',
    //         'coa_credit.code'
    //     )
    //     ->get();

    //     // 5. Discounts (Revenue reductions)
    //     $discountsQuery = DB::table('studdiscounts')
    //         ->join('studinfo', 'studdiscounts.studid', '=', 'studinfo.id')
    //         ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
    //         ->leftJoin('bk_classifiedsetup', 'studinfo.levelid', '=', 'bk_classifiedsetup.levelid')
    //         ->leftJoin('chart_of_accounts as coa_debit', 'bk_classifiedsetup.debitaccid', '=', 'coa_debit.id')
    //         ->where('studdiscounts.deleted', 0);

    //     if ($startDate && $endDate) {
    //         $discountsQuery->whereBetween('studdiscounts.createddatetime', [$startDate, $endDate]);
    //     }

    //     $discountsData = $discountsQuery->select(
    //         'coa_debit.id as id',
    //         'coa_debit.classification',
    //         'coa_debit.account_name',
    //         'coa_debit.code',
    //         DB::raw("SUM(studdiscounts.discamount) as total_amount")
    //     )
    //     ->groupBy(
    //         'coa_debit.id',
    //         'coa_debit.classification',
    //         'coa_debit.account_name',
    //         'coa_debit.code'
    //     )
    //     ->get();

    //     // Combine all revenue data
    //     $revenueData = collect()
    //         ->merge($ledgerData->where('classification', 'Revenue'))
    //         ->merge($studledgerData)
    //         ->merge($cashtransData)
    //         ->merge($adjustmentsData);

    //     // Combine all expense data
    //     $expenseData = collect()
    //         ->merge($ledgerData->where('classification', 'Expenses'))
    //         ->merge($discountsData);

    //     // Calculate totals
    //     $totalRevenue = $revenueData->sum('total_amount');
    //     $totalExpenses = $expenseData->sum('total_amount');
    //     $netIncome = $totalRevenue - $totalExpenses;

    //     // Format the income statement
    //     // Format the income statement
    //     $incomeStatement = [
    //         'revenues' => $revenueData->groupBy('code')->map(function($group) {
    //             $first = $group->first();
    //             return [
    //                 'code' => $first->code,
    //                 'account_name' => $first->account_name,
    //                 'amount' => $group->sum('total_amount')
    //             ];
    //         })->values(),
    //         'expenses' => $expenseData->groupBy('code')->map(function($group) {
    //             $first = $group->first();
    //             return [
    //                 'code' => $first->code,
    //                 'account_name' => $first->account_name,
    //                 'amount' => $group->sum('total_amount')
    //             ];
    //         })->values(),
    //         'totals' => [
    //             'total_revenue' => $totalRevenue,
    //             'total_expenses' => $totalExpenses,
    //             'net_income' => $netIncome,
    //         ],
    //     ];

    //     return response()->json($incomeStatement);
    // }

    public function displayIncomeStatement(Request $request)
    {
        $fiscalYearId = $request->input('fiscal_year_id');
        $dateRange = $request->input('date_range');

        $startDate = null;
        $endDate = null;

        $incomeStatement = DB::table('bk_statement_type')->where('desc', 'Income Statement')->first();
      
        // $main_accounts = DB::table('chart_of_accounts')
        //     ->select('id', 'account_type')
        //     ->whereIn('account_type', [11, 13])
        //     ->where('deleted', 0)
        //     ->get();

        $main_accounts = DB::table('chart_of_accounts')
            ->select('id', 'account_type', 'classification')
            ->whereIn('classification', ['revenue', 'REVENUE', 'expenses'])
            ->where('deleted', 0)
            ->get();

        // $bk_accounts = DB::table('chart_of_accounts')
        //     ->select('bk_sub_chart_of_accounts.id', 'chart_of_accounts.account_type')
        //     ->join('bk_sub_chart_of_accounts', 'chart_of_accounts.id', '=', 'bk_sub_chart_of_accounts.coaid')
        //     ->where('chart_of_accounts.deleted', 0)
        //     ->where('bk_sub_chart_of_accounts.deleted', 0)
        //     ->whereIn('chart_of_accounts.account_type', [11, 13])
        //     ->get();

        $bk_accounts = DB::table('chart_of_accounts')
            ->select('bk_sub_chart_of_accounts.id', 'chart_of_accounts.account_type')
            ->join('bk_sub_chart_of_accounts', 'chart_of_accounts.id', '=', 'bk_sub_chart_of_accounts.coaid')
            ->where('chart_of_accounts.deleted', 0)
            ->where('bk_sub_chart_of_accounts.deleted', 0)
            ->whereIn('chart_of_accounts.classification', ['revenue', 'REVENUE', 'expenses'])
            ->get();

        // Separate into revenue and expenses
        // $main_accounts_revenue = collect($main_accounts)->where('account_type', 11)->values();
        // $bk_accounts_revenue = collect($bk_accounts)->where('account_type', 11)->values();

        // $main_accounts_expenses = collect($main_accounts)->where('account_type', 13)->values();
        // $bk_accounts_expenses = collect($bk_accounts)->where('account_type', 13)->values();

        $main_accounts_revenue = collect($main_accounts)->whereIn('classification', ['revenue', 'REVENUE'])->values();
        $bk_accounts_revenue = collect($bk_accounts)->whereIn('classification', ['revenue', 'REVENUE'])->values();

        $main_accounts_expenses = collect($main_accounts)->whereIn('classification', ['expenses'])->values();
        $bk_accounts_expenses = collect($bk_accounts)->whereIn('classification', ['expenses'])->values();

        // ✅ Merge and pluck IDs for revenue
        $revenue_ids = $main_accounts_revenue->pluck('id')
            ->merge($bk_accounts_revenue->pluck('id'))
            ->unique()
            ->values();

        // ✅ Merge and pluck IDs for expenses
        $expense_ids = $main_accounts_expenses->pluck('id')
            ->merge($bk_accounts_expenses->pluck('id'))
            ->unique()
            ->values();

        // Parse date range
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

        // Get enrollment dates for all students who are enrolled
        $enrolledStudents = DB::table('studinfo')
            ->select(
                'studinfo.id',
                'studinfo.firstname',
                'studinfo.lastname',
                'studinfo.middlename',
                DB::raw('
                    COALESCE(
                        enrolledstud.dateenrolled,
                        sh_enrolledstud.dateenrolled,
                        college_enrolledstud.date_enrolled
                    ) AS dateenrolled
                ')
            )
            ->leftJoin('enrolledstud', function ($join) {
                $join->on('studinfo.id', '=', 'enrolledstud.studid')
                    ->where('enrolledstud.deleted', 0);
            })
            ->leftJoin('sh_enrolledstud', function ($join) {
                $join->on('studinfo.id', '=', 'sh_enrolledstud.studid')
                    ->where('sh_enrolledstud.deleted', 0);
            })
            ->leftJoin('college_enrolledstud', function ($join) {
                $join->on('studinfo.id', '=', 'college_enrolledstud.studid')
                    ->where('college_enrolledstud.deleted', 0);
            })
            ->where(function ($query) {
                $query->whereNotNull('enrolledstud.studid')
                    ->orWhereNotNull('sh_enrolledstud.studid')
                    ->orWhereNotNull('college_enrolledstud.studid');
            })
            ->get()
            ->keyBy('id');

        // 1. General Ledger Data (Revenue and Expenses)
    
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
        
        $ledgerRevenue = collect($ledgerData)->whereIn('coaid', $revenue_ids)->values();
        $ledgerExpense = collect($ledgerData)->whereIn('coaid', $expense_ids)->values();
        
      
        // Calculate totals
        $totalRevenue = $ledgerRevenue->sum('total_amount');
        $totalExpenses = $ledgerExpense->sum('total_amount');
        $netIncome = $totalRevenue - $totalExpenses;

        // Format the income statement
        // Format the income statement
        $incomeStatement = [
            'revenues' => $ledgerRevenue->groupBy('code')->map(function($group) {
                $first = $group->first();
                return [
                    'code' => $first->code,
                    'account_name' => $first->account_name,
                    'amount' => $group->sum('total_amount')
                ];
            })->values(),
            'expenses' => $ledgerExpense->groupBy('code')->map(function($group) {
                $first = $group->first();
                return [
                    'code' => $first->code,
                    'account_name' => $first->account_name,
                    'amount' => $group->sum('total_amount')
                ];
            })->values(),
            'totals' => [
                'total_revenue' => $totalRevenue,
                'total_expenses' => $totalExpenses,
                'net_income' => $netIncome,
            ],
        ];

        return response()->json($incomeStatement);
    }

    // public function printIncomeStatement(Request $request)
    // {
    //     $fiscalYearId = $request->input('fiscal_year');
    //     $dateRange = $request->input('date_range');
    //     $startDate = null;
    //     $endDate = null;

    //     if ($dateRange) {
    //         [$startDate, $endDate] = explode(' - ', $dateRange);
    //         try {
    //             $startDate = \Carbon\Carbon::createFromFormat('Y-m-d', trim($startDate))->startOfDay();
    //             $endDate = \Carbon\Carbon::createFromFormat('Y-m-d', trim($endDate))->endOfDay();
    //         } catch (\Exception $e) {
    //             return abort(400, 'Invalid date format.');
    //         }
    //     }

    //     // Regular accounts
    //     $ledgerQuery = DB::table('bk_generalledg')
    //         ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //         ->where('chart_of_accounts.deleted', 0);

    //     if ($fiscalYearId) {
    //         $ledgerQuery->where('bk_generalledg.active_fiscal_year_id', $fiscalYearId);
    //     }

    //     if ($startDate && $endDate) {
    //         $ledgerQuery->whereBetween('bk_generalledg.createddatetime', [$startDate, $endDate]);
    //     }

    //     $ledgerData = $ledgerQuery->select(
    //         'chart_of_accounts.id',
    //         'chart_of_accounts.classification',
    //         'chart_of_accounts.account_name',
    //         'chart_of_accounts.code',
    //         DB::raw('SUM(bk_generalledg.credit_amount - bk_generalledg.debit_amount) as total_amount')
    //     )
    //     ->groupBy(
    //         'chart_of_accounts.id',
    //         'chart_of_accounts.classification',
    //         'chart_of_accounts.account_name',
    //         'chart_of_accounts.code'
    //     )
    //     ->get();

    //     // Expenses
    //     $expensesQuery = DB::table('bk_generalledg')
    //         ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //         ->where('chart_of_accounts.deleted', 0)
    //         ->where('chart_of_accounts.classification', 'Expenses');

    //     if ($fiscalYearId) {
    //         $expensesQuery->where('bk_generalledg.active_fiscal_year_id', $fiscalYearId);
    //     }

    //     if ($startDate && $endDate) {
    //         $expensesQuery->whereBetween('bk_generalledg.createddatetime', [$startDate, $endDate]);
    //     }

    //     $expensesData = $expensesQuery->select(
    //         'chart_of_accounts.id',
    //         DB::raw("'Expenses' as classification"),
    //         'chart_of_accounts.account_name',
    //         'chart_of_accounts.code',
    //         DB::raw('SUM(bk_generalledg.debit_amount - bk_generalledg.credit_amount) as total_amount')
    //     )
    //     ->groupBy(
    //         'chart_of_accounts.id',
    //         'chart_of_accounts.account_name',
    //         'chart_of_accounts.code'
    //     )
    //     ->get();

    //     $incomeStatement = $ledgerData->merge($expensesData);

    //     $schoolinfo = DB::table('schoolinfo')->first();

    //     $pdf = Pdf::loadView('bookkeeper.pages.printables.IncomeStatement_PDF', [
    //         'ledgerData' => $incomeStatement,
    //         'schoolinfo' => $schoolinfo,
    //         'dateRange' => $dateRange,
    //     ])->setPaper('A4');

    //     return $pdf->stream('income_statement.pdf');
    // }

    public function printIncomeStatement(Request $request)
    {
    
        $fiscalYearId = $request->input('fiscal_year_id');
        $dateRange = $request->input('date_range');

        $startDate = null;
        $endDate = null;

        $incomeStatement = DB::table('bk_statement_type')->where('desc', 'Income Statement')->first();
        
        $main_accounts = DB::table('chart_of_accounts')
            ->select('id', 'account_type')
            ->whereIn('account_type', [11, 13])
            ->where('deleted', 0)
            ->get();

        $bk_accounts = DB::table('chart_of_accounts')
            ->select('bk_sub_chart_of_accounts.id', 'chart_of_accounts.account_type')
            ->join('bk_sub_chart_of_accounts', 'chart_of_accounts.id', '=', 'bk_sub_chart_of_accounts.coaid')
            ->where('chart_of_accounts.deleted', 0)
            ->where('bk_sub_chart_of_accounts.deleted', 0)
            ->whereIn('chart_of_accounts.account_type', [11, 13])
            ->get();

        // Separate into revenue and expenses
        $main_accounts_revenue = collect($main_accounts)->where('account_type', 11)->values();
        $bk_accounts_revenue = collect($bk_accounts)->where('account_type', 11)->values();

        $main_accounts_expenses = collect($main_accounts)->where('account_type', 13)->values();
        $bk_accounts_expenses = collect($bk_accounts)->where('account_type', 13)->values();

        // ✅ Merge and pluck IDs for revenue
        $revenue_ids = $main_accounts_revenue->pluck('id')
            ->merge($bk_accounts_revenue->pluck('id'))
            ->unique()
            ->values();

        // ✅ Merge and pluck IDs for expenses
        $expense_ids = $main_accounts_expenses->pluck('id')
            ->merge($bk_accounts_expenses->pluck('id'))
            ->unique()
            ->values();

        // Parse date range
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

        // Get enrollment dates for all students who are enrolled
        $enrolledStudents = DB::table('studinfo')
            ->select(
                'studinfo.id',
                'studinfo.firstname',
                'studinfo.lastname',
                'studinfo.middlename',
                DB::raw('
                    COALESCE(
                        enrolledstud.dateenrolled,
                        sh_enrolledstud.dateenrolled,
                        college_enrolledstud.date_enrolled
                    ) AS dateenrolled
                ')
            )
            ->leftJoin('enrolledstud', function ($join) {
                $join->on('studinfo.id', '=', 'enrolledstud.studid')
                    ->where('enrolledstud.deleted', 0);
            })
            ->leftJoin('sh_enrolledstud', function ($join) {
                $join->on('studinfo.id', '=', 'sh_enrolledstud.studid')
                    ->where('sh_enrolledstud.deleted', 0);
            })
            ->leftJoin('college_enrolledstud', function ($join) {
                $join->on('studinfo.id', '=', 'college_enrolledstud.studid')
                    ->where('college_enrolledstud.deleted', 0);
            })
            ->where(function ($query) {
                $query->whereNotNull('enrolledstud.studid')
                    ->orWhereNotNull('sh_enrolledstud.studid')
                    ->orWhereNotNull('college_enrolledstud.studid');
            })
            ->get()
            ->keyBy('id');

        // 1. General Ledger Data (Revenue and Expenses)
    
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
        
        $ledgerRevenue = collect($ledgerData)->whereIn('coaid', $revenue_ids)->values();
        $ledgerExpense = collect($ledgerData)->whereIn('coaid', $expense_ids)->values();
        
        
        // Calculate totals
        $totalRevenue = $ledgerRevenue->sum('total_amount');
        $totalExpenses = $ledgerExpense->sum('total_amount');
        $netIncome = $totalRevenue - $totalExpenses;
        
        // Format the income statement
        $incomeStatement = [
            'revenues' => $ledgerRevenue->groupBy('code')->map(function($group) {
                $first = $group->first();
                return [
                    'code' => $first->code,
                    'account_name' => $first->account_name,
                    'amount' => $group->sum('total_amount')
                ];
            })->values(),
            'expenses' => $ledgerExpense->groupBy('code')->map(function($group) {
                $first = $group->first();
                return [
                    'code' => $first->code,
                    'account_name' => $first->account_name,
                    'amount' => $group->sum('total_amount')
                ];
            })->values(),
            'totals' => [
                'total_revenue' => $totalRevenue,
                'total_expenses' => $totalExpenses,
                'net_income' => $netIncome,
            ],
        ];

        // $incomeStatement = $ledgerData->merge($expensesData);
        // return $incomeStatement['revenues'];

        $schoolinfo = DB::table('schoolinfo')->first();
        
        $pdf = Pdf::loadView('bookkeeper.pages.printables.IncomeStatement_PDF', [
            'ledgerData' => $incomeStatement,
            'schoolinfo' => $schoolinfo,
            'dateRange' => $dateRange,
            'net_income' => $netIncome,
        ])->setPaper('A4');

        return $pdf->stream('income_statement.pdf');
    }
}