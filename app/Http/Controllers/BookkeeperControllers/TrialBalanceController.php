<?php

namespace App\Http\Controllers\BookkeeperControllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use DB;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use PDF;

class TrialBalanceController extends Controller
{
    protected function generateVoucherNumberJE()
    {
        static $counter = 1;
        return 'JE-' . str_pad($counter++, 6, '0', STR_PAD_LEFT);
    }
    // public function displayTrialBalance(Request $request)
    // {
    //     $fiscalYears = $request->input('fiscal_year');
    //     $dateRange = $request->input('date_range');

    //     // Main ledger query
    //     $query = DB::table('bk_generalledg')
    //         ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //         ->select(
    //             'chart_of_accounts.id as coaid',
    //             'chart_of_accounts.code',
    //             'chart_of_accounts.classification',
    //             DB::raw('SUM(bk_generalledg.debit_amount) as debit_amount'),
    //             DB::raw('SUM(bk_generalledg.credit_amount) as credit_amount')
    //         )
    //         ->groupBy(
    //             'chart_of_accounts.id',
    //             'chart_of_accounts.code',
    //             'chart_of_accounts.classification'
    //         );

    //     // Filter by fiscal year
    //     if (!empty($fiscalYears)) {
    //         $query->whereIn('bk_generalledg.active_fiscal_year_id', $fiscalYears);
    //     }

    //     // Filter by date range
    //     $start = $end = null;
    //     if (!empty($dateRange)) {
    //         [$startDate, $endDate] = explode(' - ', $dateRange);
    //         $start = \Carbon\Carbon::parse($startDate)->startOfDay();
    //         $end = \Carbon\Carbon::parse($endDate)->endOfDay();

    //         $query->whereBetween('bk_generalledg.date', [$start, $end]);
    //     }

    //     $mainLedger = $query->get();

    //     // Prepare Adjustments
    //     $adjustments = DB::table('adjustments')
    //         ->select(
    //             'adjustments.createddatetime as transdate',
    //             'adjustments.amount',
    //             'adjustments.isdebit',
    //             'adjustments.iscredit',
    //             'adjustmentdetails.studid',
    //             'studinfo.firstname',
    //             'studinfo.lastname',
    //             'studinfo.middlename',
    //             'coa_debit.id as debit_coaid',
    //             'coa_debit.code as debit_code',
    //             'coa_debit.account_name as debit_account',
    //             'coa_debit.classification as debit_class',
    //             'coa_credit.id as credit_coaid',
    //             'coa_credit.code as credit_code',
    //             'coa_credit.account_name as credit_account',
    //             'coa_credit.classification as credit_class'
    //         )
    //         ->distinct()
    //         ->where('adjustments.deleted', 0)
    //         ->where('adjustmentdetails.deleted', 0)
    //         ->join('adjustmentdetails', 'adjustmentdetails.headerid', '=', 'adjustments.id')
    //         ->join('studinfo', 'adjustmentdetails.studid', '=', 'studinfo.id')
    //         ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
    //         ->leftJoin('bk_classifiedsetup', 'studinfo.levelid', '=', 'bk_classifiedsetup.levelid')
    //         ->leftJoin('chart_of_accounts as coa_debit', 'bk_classifiedsetup.debitaccid', '=', 'coa_debit.id')
    //         ->leftJoin('chart_of_accounts as coa_credit', 'bk_classifiedsetup.creditaccid', '=', 'coa_credit.id');

    //     if ($start && $end) {
    //         $adjustments->whereBetween('adjustments.createddatetime', [$start, $end]);
    //     }

    //     $adjustments = $adjustments->get();

    //     // Map adjustments into similar structure
    //     $adjustmentMapped = collect();

    //     foreach ($adjustments as $adj) {
    //         if ($adj->debit_coaid) {
    //             $adjustmentMapped->push([
    //                 'coaid' => $adj->debit_coaid,
    //                 'code' => $adj->debit_code,
    //                 'classification' => $adj->debit_class,
    //                 'debit_amount' => $adj->amount,
    //                 'credit_amount' => 0,
    //             ]);
    //         }

    //         if ($adj->credit_coaid) {
    //             $adjustmentMapped->push([
    //                 'coaid' => $adj->credit_coaid,
    //                 'code' => $adj->credit_code,
    //                 'classification' => $adj->credit_class,
    //                 'debit_amount' => 0,
    //                 'credit_amount' => $adj->amount,
    //             ]);
    //         }
    //     }

    //     // Cash transactions query
    //     $cashtransQuery = DB::table('chrngtrans')
    //         ->select([
    //             'chrngtrans.transdate',
    //             'chrngtrans.ornum',
    //             'chrngtrans.studid',
    //             'chrngtrans.studname',
    //             'chrngtrans.paytype',
    //             'chrngtrans.totalamount',
    //             'chrngtrans.amountpaid',
    //             'chrngtrans.refno',
    //             'chrngtrans.sid',
    //             'chrngtrans.accountname',
    //             'studinfo.firstname',
    //             'studinfo.lastname',
    //             'studinfo.middlename',
    //             'coa_debit.code as debit_account_code',
    //             'coa_debit.account_name as debit_account',
    //             'coa_credit.code as credit_account_code',
    //             'coa_credit.account_name as credit_account'
    //         ])
    //         ->distinct()
    //         ->where('chrngtrans.cancelled', 0)
    //         ->leftJoin('studinfo', 'chrngtrans.studid', '=', 'studinfo.id')
    //         ->leftJoin('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
    //         ->join('bk_classifiedsetup', 'studinfo.levelid', '=', 'bk_classifiedsetup.levelid')
    //         ->leftJoin('chart_of_accounts as coa_debit', 'bk_classifiedsetup.debitaccid', '=', 'coa_debit.id')
    //         ->leftJoin('chart_of_accounts as coa_credit', 'bk_classifiedsetup.creditaccid', '=', 'coa_credit.id');

    //     if ($start && $end) {
    //         $cashtransQuery->whereBetween('transdate', [$start, $end]);
    //     }

    //     $cashtrans = $cashtransQuery->get();

    //     // Map cash transactions into similar structure
    //     $cashMapped = collect();

    //     foreach ($cashtrans as $transaction) {
    //         $studentName = trim("{$transaction->firstname} {$transaction->middlename} {$transaction->lastname}");
    //         $voucherNo = $this->generateVoucherNumberJE();

    //         // Debit entry (cash received)
    //         $cashMapped->push([
    //             'coaid' => $transaction->debit_account_code,
    //             'code' => $transaction->debit_account_code,
    //             'classification' => 'Cash', // Adjust classification as needed
    //             'debit_amount' => $transaction->amountpaid,
    //             'credit_amount' => 0,
    //         ]);

    //         // Credit entry (revenue)
    //         $cashMapped->push([
    //             'coaid' => $transaction->credit_account_code,
    //             'code' => $transaction->credit_account_code,
    //             'classification' => 'Revenue', // Adjust classification as needed
    //             'debit_amount' => 0,
    //             'credit_amount' => $transaction->amountpaid,
    //         ]);
    //     }

    //     // Student ledger query
    //     $studledgerQuery = DB::table('studledger')
    //         ->select([
    //             'studledger.id',
    //             'studledger.studid',
    //             'studledger.particulars',
    //             'studledger.amount',
    //             'studledger.createddatetime',
    //             'studinfo.firstname',
    //             'studinfo.lastname',
    //             'studinfo.middlename',
    //             'coa_debit.code as debit_account_code',
    //             'coa_debit.account_name as debit_account',
    //             'coa_credit.code as credit_account_code',
    //             'coa_credit.account_name as credit_account'
    //         ])
    //         ->distinct()
    //         ->leftJoin('studinfo', 'studledger.studid', '=', 'studinfo.id')
    //         ->leftJoin('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
    //         ->join('bk_classifiedsetup', 'studinfo.levelid', '=', 'bk_classifiedsetup.levelid')
    //         ->leftJoin('chart_of_accounts as coa_debit', 'bk_classifiedsetup.debitaccid', '=', 'coa_debit.id')
    //         ->leftJoin('chart_of_accounts as coa_credit', 'bk_classifiedsetup.creditaccid', '=', 'coa_credit.id')
    //         ->where('studledger.deleted', 0)
    //         ->where('studledger.payment', 0);

    //     if ($start && $end) {
    //         $studledgerQuery->whereBetween('studledger.createddatetime', [$start, $end]);
    //     }

    //     $studledgerRecords = $studledgerQuery->get();

    //     // Map student ledger into similar structure
    //     $studledgerMapped = collect();

    //     foreach ($studledgerRecords as $record) {
    //         $studledgerMapped->push([
    //             'coaid' => $record->debit_account_code,
    //             'code' => $record->debit_account_code,
    //             'classification' => 'Accounts Receivable', // Adjust classification as needed
    //             'debit_amount' => $record->amount,
    //             'credit_amount' => 0,
    //         ]);

    //         $studledgerMapped->push([
    //             'coaid' => $record->credit_account_code,
    //             'code' => $record->credit_account_code,
    //             'classification' => 'Revenue', // Adjust classification as needed
    //             'debit_amount' => 0,
    //             'credit_amount' => $record->amount,
    //         ]);
    //     }

    //     // Combine all datasets
    //     $combined = $mainLedger->map(function ($row) {
    //         return [
    //             'coaid' => $row->coaid,
    //             'code' => $row->code,
    //             'classification' => $row->classification,
    //             'debit_amount' => $row->debit_amount,
    //             'credit_amount' => $row->credit_amount,
    //         ];
    //     })->concat($adjustmentMapped)
    //     ->concat($cashMapped)
    //     ->concat($studledgerMapped);

    //     // Group by account code
    //     $final = $combined->groupBy('code')->map(function ($group) {
    //         $first = $group->first();
    //         $debitSum = $group->sum('debit_amount');
    //         $creditSum = $group->sum('credit_amount');

    //         return (object)[
    //             'code' => $first['code'],
    //             'classification' => $first['classification'],
    //             'debit_amount' => $debitSum,
    //             'credit_amount' => $creditSum,
    //             'ending_balance' => $debitSum - $creditSum,
    //         ];
    //     })->values();

    //     return response()->json($final);
    // }

    public function displayTrialBalance(Request $request)
    {
        $fiscalYears = $request->input('fiscal_year');
        $dateRange = $request->input('date_range');

        // Parse date range if provided
        $startDate = null;
        $endDate = null;
        if (!empty($dateRange)) {
            [$start, $end] = explode(' - ', $dateRange);
            $startDate = \Carbon\Carbon::parse($start)->startOfDay();
            $endDate = \Carbon\Carbon::parse($end)->endOfDay();
        }

        $accountUnion = DB::table('chart_of_accounts')
            ->select(
                'id as coaid',
                'account_name',
                'code',
                'account_name as classification'
            )
            ->unionAll(
                DB::table('bk_sub_chart_of_accounts')
                    ->leftJoin('chart_of_accounts', 'bk_sub_chart_of_accounts.coaid', '=', 'chart_of_accounts.id')
                    ->where('chart_of_accounts.deleted', 0)
                    ->where('bk_sub_chart_of_accounts.deleted', 0)
                    ->select(
                        'bk_sub_chart_of_accounts.id as coaid',
                        'sub_account_name as account_name',
                        'sub_code as code',
                        'sub_account_name as classification'
                    )
            );
     
        $entries = DB::table('bk_generalledg')
            ->leftJoinSub($accountUnion, 'accounts', function ($join) {
                $join->on('bk_generalledg.coaid', '=', 'accounts.coaid');
            })
            ->join('bk_fiscal_year', 'bk_generalledg.active_fiscal_year_id', '=', 'bk_fiscal_year.id')
            ->select(
                'bk_generalledg.voucherNo',
                'bk_generalledg.date',
                'bk_generalledg.coaid',
                'bk_generalledg.debit_amount',
                'bk_generalledg.credit_amount',
                'bk_generalledg.remarks',
                'bk_generalledg.sub',
                'bk_generalledg.remarks as explanation',
                'bk_fiscal_year.description as fiscal_desc',
                'accounts.account_name',
                'accounts.code',
                'accounts.classification'
            )
            ->where('bk_generalledg.deleted', 0)
            ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                $q->whereBetween('bk_generalledg.date', [$startDate, $endDate]);
            })
            ->when(!empty($fiscalYears), function ($q) use ($fiscalYears) {
                $q->whereIn('bk_generalledg.active_fiscal_year_id', $fiscalYears);
            })
            ->orderBy('bk_generalledg.date', 'desc')
            ->get();
     
        // Combine all datasets
        $combined = $entries->map(function ($row) {
            return [
                'coaid' => $row->coaid,
                'code' => $row->code,
                'classification' => $row->classification,
                'debit_amount' => $row->debit_amount,
                'credit_amount' => $row->credit_amount,
            ];
        });
     
        // Group by account code
        $final = $combined->groupBy('code')->map(function ($group) {
            $first = $group->first();
            $debitSum = $group->sum('debit_amount');
            $creditSum = $group->sum('credit_amount');

            return (object)[
                'code' => $first['code'],
                'classification' => $first['classification'],
                'debit_amount' => $debitSum,
                'credit_amount' => $creditSum,
                'ending_balance' => $debitSum - $creditSum,
            ];
        })->values();

        return response()->json($final);
    }


    public function displayTrialBalanceSummary(Request $request)
    {
        $fiscalYears = $request->input('fiscal_year');

        if (empty($fiscalYears) || count($fiscalYears) < 2) {
            return response()->json([], 200);
        }

        $results = DB::table('bk_generalledg')
            ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
            ->select(
                'bk_generalledg.active_fiscal_year_id',
                'chart_of_accounts.code',
                'chart_of_accounts.classification',
                DB::raw('SUM(bk_generalledg.debit_amount) as debit_amount'),
                DB::raw('SUM(bk_generalledg.credit_amount) as credit_amount'),
                DB::raw('SUM(bk_generalledg.debit_amount - bk_generalledg.credit_amount) as ending_balance')
            )
            ->whereIn('bk_generalledg.active_fiscal_year_id', $fiscalYears)
            ->groupBy('bk_generalledg.active_fiscal_year_id', 'bk_generalledg.coaid')
            ->get();

        return response()->json($results);
    }

    // public function printTrialBalance(Request $request)
    // {
    //     $fiscalYears = $request->input('fiscal_year'); // Can be null or array

    //     $query = DB::table('bk_generalledg')
    //         ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //         ->join('bk_fiscal_year', 'bk_generalledg.active_fiscal_year_id', '=', 'bk_fiscal_year.id')
    //         ->select(
    //             'bk_fiscal_year.description as fiscal_year_name',
    //             'chart_of_accounts.code',
    //             'chart_of_accounts.classification',
    //             DB::raw('SUM(bk_generalledg.debit_amount) as debit_amount'),
    //             DB::raw('SUM(bk_generalledg.credit_amount) as credit_amount'),
    //             DB::raw('SUM(bk_generalledg.debit_amount - bk_generalledg.credit_amount) as ending_balance')
    //         )
    //         ->groupBy(
    //             'bk_fiscal_year.description',
    //             'chart_of_accounts.code',
    //             'chart_of_accounts.classification',
    //             'bk_generalledg.coaid'
    //         );

    //     if ($fiscalYears && is_array($fiscalYears)) {
    //         $query->whereIn('bk_generalledg.active_fiscal_year_id', $fiscalYears);
    //     }

    //     $results = $query->get();

    //     $results = $results->sortBy(['code']); // optional
    //     $grouped = collect([$results]);
                
    //     $schoolinfo = DB::table('schoolinfo')->first();

    //     $signatories = DB::table('signatory')
    //             ->join('bk_signatory_grp', 'signatory.signatory_grp_id', '=', 'bk_signatory_grp.id')
    //             ->where('signatory.signatory_grp_id', 4)
    //             ->select('signatory.*')
    //             ->get();

    //     return Pdf::loadView('bookkeeper.pages.printables.TrialBalance_PDF', [
    //         'groupedDisplay' => $grouped,
    //         'signatories' => $signatories,
    //         'schoolinfo' => $schoolinfo,
    //     ])->stream('TrialBalance_' . now()->format('Ymd_His') . '.pdf');
    // }

    // public function printTrialBalance(Request $request)
    // {
    //     $fiscalYears = $request->input('fiscal_year'); // Can be null or array
    //     $dateRange = $request->input('date_range');

    //     $query = DB::table('bk_generalledg')
    //         ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
    //         ->join('bk_fiscal_year', 'bk_generalledg.active_fiscal_year_id', '=', 'bk_fiscal_year.id')
    //         ->select(
    //             'bk_fiscal_year.description as fiscal_year_name',
    //             'chart_of_accounts.code',
    //             'chart_of_accounts.classification',
    //             DB::raw('SUM(bk_generalledg.debit_amount) as debit_amount'),
    //             DB::raw('SUM(bk_generalledg.credit_amount) as credit_amount'),
    //             DB::raw('SUM(bk_generalledg.debit_amount - bk_generalledg.credit_amount) as ending_balance')
    //         )
    //         ->groupBy(
    //             'bk_fiscal_year.description',
    //             'chart_of_accounts.code',
    //             'chart_of_accounts.classification',
    //             'bk_generalledg.coaid'
    //         );

    //     if ($fiscalYears && is_array($fiscalYears)) {
    //         $query->whereIn('bk_generalledg.active_fiscal_year_id', $fiscalYears);
    //     }

    //     if (!empty($dateRange)) {
    //         [$startDate, $endDate] = explode(' - ', $dateRange);
    //         $start = \Carbon\Carbon::parse($startDate)->startOfDay();
    //         $end = \Carbon\Carbon::parse($endDate)->endOfDay();

    //         $query->whereBetween('bk_generalledg.date', [$start, $end]);
    //     }

    //     $results = $query->get();

    //     $results = $results->sortBy(['code']); // optional
    //     $grouped = collect([$results]);
                
    //     $schoolinfo = DB::table('schoolinfo')->first();

    //     $signatories = DB::table('signatory')
    //             ->join('bk_signatory_grp', 'signatory.signatory_grp_id', '=', 'bk_signatory_grp.id')
    //             ->where('signatory.signatory_grp_id', 4)
    //             ->select('signatory.*')
    //             ->get();

    //     return Pdf::loadView('bookkeeper.pages.printables.TrialBalance_PDF', [
    //         'groupedDisplay' => $grouped,
    //         'signatories' => $signatories,
    //         'schoolinfo' => $schoolinfo,
    //     ])->stream('TrialBalance_' . now()->format('Ymd_His') . '.pdf');
    // }

    public function printTrialBalance(Request $request)
    {
        $fiscalYears = $request->input('fiscal_year'); // Can be null or array
        $dateRange = $request->input('date_range');

        $query = DB::table('bk_generalledg')
            ->join('bk_fiscal_year', 'bk_generalledg.active_fiscal_year_id', '=', 'bk_fiscal_year.id')
            ->leftJoin('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
            ->leftJoin('bk_sub_chart_of_accounts', 'bk_generalledg.coaid', '=', 'bk_sub_chart_of_accounts.id')
            ->select(
                'bk_generalledg.coaid',
                'bk_generalledg.sub',
                'bk_fiscal_year.description as fiscal_year_name',
                DB::raw("CASE 
                    WHEN bk_generalledg.sub = 0 OR bk_generalledg.sub IS NULL THEN chart_of_accounts.account_name 
                    ELSE bk_sub_chart_of_accounts.sub_account_name 
                END AS classification"),
                DB::raw("CASE 
                    WHEN bk_generalledg.sub = 0 OR bk_generalledg.sub IS NULL THEN chart_of_accounts.code 
                    ELSE bk_sub_chart_of_accounts.sub_code 
                END AS code"),
                DB::raw('SUM(bk_generalledg.debit_amount) as debit_amount'),
                DB::raw('SUM(bk_generalledg.credit_amount) as credit_amount'),
                DB::raw('SUM(bk_generalledg.debit_amount - bk_generalledg.credit_amount) as ending_balance')
            )
            ->where('bk_generalledg.deleted', 0)
            ->groupBy(
                'bk_generalledg.coaid',
                'bk_generalledg.sub',
                'code',
                'classification'
            );

        // Filter by fiscal year
        if (!empty($fiscalYears)) {
            $query->whereIn('bk_generalledg.active_fiscal_year_id', $fiscalYears);
        }

        if (!empty($dateRange)) {
            [$startDate, $endDate] = explode(' - ', $dateRange);
            $start = \Carbon\Carbon::parse($startDate)->startOfDay();
            $end = \Carbon\Carbon::parse($endDate)->endOfDay();

            $query->whereBetween('bk_generalledg.date', [$start, $end]);
        }

        $results = $query->get();
  
        $results = $results->sortBy(['code']); // optional
        $grouped = collect([$results]);
                
        $schoolinfo = DB::table('schoolinfo')->first();

        $signatories = DB::table('signatory')
                ->join('bk_signatory_grp', 'signatory.signatory_grp_id', '=', 'bk_signatory_grp.id')
                ->where('signatory.signatory_grp_id', 4)
                ->select('signatory.*')
                ->get();

        return Pdf::loadView('bookkeeper.pages.printables.TrialBalance_PDF', [
            'groupedDisplay' => $grouped,
            'signatories' => $signatories,
            'schoolinfo' => $schoolinfo,
        ])->stream('TrialBalance_' . now()->format('Ymd_His') . '.pdf');
    }


}