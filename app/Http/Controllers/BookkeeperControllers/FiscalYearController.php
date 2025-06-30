<?php

namespace App\Http\Controllers\BookkeeperControllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use DB;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class FiscalYearController extends Controller
{

    public function getFiscalYear()
    {
        // Get active fiscal year (if exists)
        $fiscalYearActive = DB::table('bk_fiscal_year')
            ->where('isactive', 1)
            ->where('deleted', 0)
            ->first(['stime', 'etime']);

        // Get all fiscal years
        $fiscalYears = DB::table('bk_fiscal_year')
            ->where('deleted', 0)
            ->get(['id', 'description', 'stime', 'etime', 'isactive', 'ended']);

        // Check if any active year exists
        $hasActive = $fiscalYearActive ? 1 : 0;

        // Add `withactive` to each item
        $fiscalYears = $fiscalYears->map(function ($item) use ($hasActive) {
            $item->withactive = $hasActive;
            return $item;
        });

        return response()->json($fiscalYears);
    }

    // public function saveFiscalYear(Request $request)
    // {
    //     $saveFiscalYear = DB::table('bk_fiscal_year')->insert([
    //         'description' => $request->fiscalYearDescription,
    //         'stime' => $request->startDateFiscal,
    //         'etime' => $request->endDateFiscal,
    //         'isactive' => $request->isActive,
    //         'createddatetime' => \Carbon\Carbon::now('Asia/Manila'),
    //         'createdby' => auth()->user()->id,
    //     ]);
    
    //     if ($saveFiscalYear) {
    //         return response()->json(['success' => true, 'message' => 'Fiscal year saved successfully.']);
    //     } else {
    //         return response()->json(['success' => false, 'message' => 'Failed to save fiscal year.']);
    //     }
    // }

    public function saveFiscalYear(Request $request)
    {
        $existingActive = DB::table('bk_fiscal_year')->where('isactive', 1)->first();
        if ($existingActive && $request->isActive == 1) {
            return response()->json(['status' => 3, 'message' => 'Only one active fiscal year is allowed.']);
        }

        $existingDescription = DB::table('bk_fiscal_year')
            ->where('description', $request->fiscalYearDescription)
            ->where('deleted', 0)
            ->first();
        if ($existingDescription) {
            return response()->json(['status' => 3, 'message' => 'Fiscal year description already exists.']);
        }

        $start = \Carbon\Carbon::parse($request->startDateFiscal);
        $end = \Carbon\Carbon::parse($request->endDateFiscal);
        $diffInDays = $start->diffInDays($end) + 1;

        // Check range: must be 365 or 366 (for leap year)
        if (!in_array($diffInDays, [365, 366])) {
            return response()->json([
                'status' => 3,
                'message' => 'Fiscal year range must be exactly 365 days, or 366 days if it includes a leap year.'
            ]);
        }

        $saveFiscalYear = DB::table('bk_fiscal_year')->insert([
            'description' => $request->fiscalYearDescription,
            'stime' => $start,
            'etime' => $end,
            'isactive' => $request->isActive,
            'createddatetime' => \Carbon\Carbon::now('Asia/Manila'),
            'createdby' => auth()->user()->id,
        ]);

        if ($saveFiscalYear) {
            return response()->json(['status' => 1, 'message' => 'Fiscal year saved successfully.']);
        } else {
            return response()->json(['status' => 2, 'message' => 'Failed to save fiscal year.']);
        }
    }

    public function deletedFiscalYear(Request $request)
    {
        $deletedFiscalYear = DB::table('bk_fiscal_year')
            ->where('id', $request->id)
            ->update([
                'deleted' => 1,
            ]);
    
        if ($deletedFiscalYear) {
            return response()->json(['success' => true, 'message' => 'Fiscal year deleted successfully.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to delete fiscal year.']);
        }
    }

    public function updateFiscalYear(Request $request)
    {
        // Ensure only one active fiscal year
        $existingActive = DB::table('bk_fiscal_year')
            ->where('isactive', 1)
            ->where('id', '!=', $request->fiscalYearId) // Exclude the current one
            ->first();

        if ($existingActive && $request->isActive == 1) {
            return response()->json(['status' => 3, 'message' => 'Only one active fiscal year is allowed.']);
        }

        // Check for duplicate description (excluding self)
        $existingDescription = DB::table('bk_fiscal_year')
            ->where('description', $request->fiscalYearDescription)
            ->where('deleted', 0)
            ->where('id', '!=', $request->fiscalYearId)
            ->first();

        if ($existingDescription) {
            return response()->json(['status' => 3, 'message' => 'Fiscal year description already exists.']);
        }

        // Validate date range (must be 365 or 366 days)
        $start = \Carbon\Carbon::parse($request->startDateFiscal);
        $end = \Carbon\Carbon::parse($request->endDateFiscal);
        $diffInDays = $start->diffInDays($end) + 1;

        if (!in_array($diffInDays, [365, 366])) {
            return response()->json([
                'status' => 3,
                'message' => 'Fiscal year range must be exactly 365 days, or 366 days if it includes a leap day.'
            ]);
        }

        $editFiscalYear = DB::table('bk_fiscal_year')
            ->where('id', $request->fiscalYearId)
            ->update([
                'description' => $request->fiscalYearDescription,
                'stime' => $start,
                'etime' => $end,
                'isactive' => $request->isActive,
                'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                'updatedby' => auth()->user()->id,
            ]);

        if ($editFiscalYear) {
            return response()->json(['status' => 1, 'message' => 'Fiscal year updated successfully.']);
        } else {
            return response()->json(['status' => 2, 'message' => 'Failed to update fiscal year.']);
        }
    }

    public function getFiscalYearEdit(Request $request)
    {
        $fiscalYear = DB::table('bk_fiscal_year')
            ->where('id', $request->id)
            ->where('deleted', 0)
            ->first();

        return response()->json($fiscalYear);
    }

    public function getClosedInformation($id)
    {
        $fiscalYear = DB::table('bk_fiscal_year')
            ->where('id', $id)
            ->where('deleted', 0)
            ->first();

        if (!$fiscalYear) {
            return response()->json(['error' => 'Fiscal year not found'], 404);
        }

        $incomeStatement = $this->getIncomeStatementByFiscalYear($id);
        
        $balanceSheet = $this->getBalanceSheetByFiscalYear($id);

        return response()->json([
            'fiscal_year' => $fiscalYear,
            'income_statement' => $incomeStatement,
            'balance_sheet' => $balanceSheet,
        ]);
    }

    private function getIncomeStatementByFiscalYear($fiscalYearId)
    {
        $fiscalYear = DB::table('bk_fiscal_year')->where('id', $fiscalYearId)->first();

        if (!$fiscalYear) {
            return null;
        }

        $startDate = \Carbon\Carbon::parse($fiscalYear->stime)->startOfDay();
        $endDate = \Carbon\Carbon::parse($fiscalYear->etime)->endOfDay();

        $ledgerQuery = DB::table('bk_generalledg')
            ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
            ->where('chart_of_accounts.deleted', 0);

        $ledgerData = $ledgerQuery->select(
            'chart_of_accounts.id',
            'chart_of_accounts.classification',
            'chart_of_accounts.account_name',
            'chart_of_accounts.code',
            DB::raw('SUM(bk_generalledg.credit_amount - bk_generalledg.debit_amount) as total_amount')
        )
        ->groupBy(
            'chart_of_accounts.id',
            'chart_of_accounts.classification',
            'chart_of_accounts.account_name',
            'chart_of_accounts.code'
        )
        ->get();
        

        return $ledgerData;
    }

    private function getBalanceSheetByFiscalYear($fiscalYearId)
    {
        $fiscal = DB::table('bk_fiscal_year')->where('id', $fiscalYearId)->first();

        if (!$fiscal) {
            return null;
        }

        // General Ledger Data
        $data = DB::table('bk_generalledg')
            ->join('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
            ->select(
                'chart_of_accounts.id',
                'chart_of_accounts.code',
                'chart_of_accounts.classification',
                DB::raw('SUM(bk_generalledg.debit_amount) as debit_amount'),
                DB::raw('SUM(bk_generalledg.credit_amount) as credit_amount')
            )
            ->where('bk_generalledg.active_fiscal_year_id', $fiscalYearId)
            // ->whereBetween('bk_generalledg.date', [$fiscal->stime, $fiscal->etime])
            ->groupBy('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.classification')
            ->get()
            ->transform(function ($item) {
                $item->ending_balance = $item->debit_amount - $item->credit_amount;
                return $item;
            });

        // Fixed Assets
        $fixedAssets = DB::table('bk_fixedassets')
            ->select('id', 'asset_name', 'purchased_date', 'asset_value')
            ->whereBetween('purchased_date', [$fiscal->stime, $fiscal->etime])
            ->get();

        // Student Ledger Data
        $studledgerData = DB::table('studledger')
            ->join('studinfo', 'studledger.studid', '=', 'studinfo.id')
            ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
            ->join('bk_classifiedsetup', 'studinfo.levelid', '=', 'bk_classifiedsetup.levelid')
            ->leftJoin('chart_of_accounts as coa_credit', 'bk_classifiedsetup.creditaccid', '=', 'coa_credit.id')
            ->leftJoin('enrolledstud', function($join) {
                $join->on('studledger.studid', '=', 'enrolledstud.studid')
                    ->where('enrolledstud.deleted', 0);
            })
            ->leftJoin('sh_enrolledstud', function($join) {
                $join->on('studledger.studid', '=', 'sh_enrolledstud.studid')
                    ->where('sh_enrolledstud.deleted', 0);
            })
            ->leftJoin('college_enrolledstud', function($join) {
                $join->on('studledger.studid', '=', 'college_enrolledstud.studid')
                    ->where('college_enrolledstud.deleted', 0);
            })
            ->where('studledger.deleted', 0)
            ->where(function($query) {
                $query->whereNotNull('enrolledstud.studid')
                    ->orWhereNotNull('sh_enrolledstud.studid')
                    ->orWhereNotNull('college_enrolledstud.studid');
            })
            ->whereBetween('studledger.createddatetime', [$fiscal->stime, $fiscal->etime])
            ->select(
                'coa_credit.id as id',
                'coa_credit.code',
                'coa_credit.classification',
                'coa_credit.account_name',
                DB::raw('SUM(studledger.amount) as total_amount')
            )
            ->groupBy(
                'coa_credit.id',
                'coa_credit.code',
                'coa_credit.classification',
                'coa_credit.account_name'
            )
            ->get();

            return [
                'fiscal_year_id' => $fiscalYearId,
                'fiscal_year_description' => $fiscal->description ?? '',
                'data' => $data,
                'fixed_assets' => $fixedAssets,
                'show_fixed_assets' => $fixedAssets->isNotEmpty(),
                'assets_details' => $fixedAssets->map(function ($asset) {
                    return [
                        'name' => $asset->asset_name,
                        'purchased_date' => $asset->purchased_date,
                        'value' => $asset->asset_value
                    ];
                }),
                'studledger_data' => $studledgerData, // ← Add this line
            ];
    }


    public function updateCloseFiscalYear(Request $request)
    {
        $fiscalYearId = $request->input('fiscalYearId');
        
        $editFiscalYear = DB::table('bk_fiscal_year')
            ->where('id', $request->fiscalYearId)
            ->update([
                'isactive' => $request->isActive,
                'ended' => 1 , // # 1 to end 
                'updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                'updatedby' => auth()->user()->id,
            ]);
    
        return response()->json([
            'status' => $editFiscalYear ? 1 : 0,
            'message' => $editFiscalYear ? 'Fiscal year updated successfully.' : 'Failed to update fiscal year.'
        ]);
    }

    public function activateCloseFiscalYear(Request $request)
    {
        $id = $request->get('id');
    
        // First deactivate all fiscal years
        DB::table('bk_fiscal_year')
            ->update([
                'isactive' => 0,
                'updateddatetime' => now('Asia/Manila'),
                'updatedby' => auth()->id()
            ]);

        // Then activate the selected one
        DB::table('bk_fiscal_year')
            ->where('id', $request->id)
            ->update([
                'isactive' => 1,
                'updateddatetime' => now('Asia/Manila'),
                'updatedby' => auth()->id()
            ]);
    
        return response()->json([
            'status' => 1,
            'message' => 'Fiscal year activated successfully'
        ]);
    }
}