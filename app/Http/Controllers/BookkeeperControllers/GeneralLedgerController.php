<?php

namespace App\Http\Controllers\BookkeeperControllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use DB;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use PDF;

class GeneralLedgerController extends Controller
{
    public function saveGeneralLedger(Request $request)
    {
        $validated = $request->validate([
            'voucher_no' => 'required|string|max:100',
            'date' => 'required|date',
            'remarks' => 'nullable|string|max:255',
            'entries' => 'required|array|min:1',
            // 'entries.*.account' => 'required|integer|exists:chart_of_accounts,id', // assuming this is your table
            'entries.*.debit' => 'required|numeric|min:0',
            'entries.*.credit' => 'required|numeric|min:0',
        ]);
    
        $checkExisting = DB::table('bk_generalledg')
            ->where('voucherNo', $validated['voucher_no'])
            // ->where('date', $validated['date'])
            ->first();
    
        if ($checkExisting) {
            return response()->json(['status' => 3, 'message' => 'Voucher number already exists.']);
        }
    
        foreach ($validated['entries'] as $entry) {
            DB::table('bk_generalledg')->insert([
                'voucherNo' => $validated['voucher_no'],
                'active_fiscal_year_id' => $request->active_fiscal_year,
                'date' => $validated['date'],
                'coaid' => $entry['account'],
                'debit_amount' => $entry['debit'],
                'credit_amount' => $entry['credit'],
                'remarks' => $validated['remarks'],
                'createddatetime' => now('Asia/Manila'),
                'createdby' => auth()->id()
            ]);
        }
    
        return response()->json(['status' => 1, 'message' => 'Saved successfully.']);
    }

    protected function generateVoucherNumberJE()
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

    // public function displayGeneralLedger(Request $request)
    // {
    //     $startDate = null;
    //     $endDate = null;

    //     // Parse the date range filter
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

    //     // Get fiscal year date range if fiscal_year_id is provided
    //     if ($request->has('fiscal_year_id') && $request->fiscal_year_id != '') {
    //         $fiscalYear = DB::table('bk_fiscal_year')->where('id', $request->fiscal_year_id)->first();

    //         if ($fiscalYear) {
    //             $startDate = \Carbon\Carbon::parse($fiscalYear->stime)->startOfDay();
    //             $endDate = \Carbon\Carbon::parse($fiscalYear->etime)->endOfDay();
    //         }
    //     }

       
    //     $accountUnion = DB::table('chart_of_accounts')
    //         ->select(
    //             'id as coaid',
    //             'account_name',
    //             'code'
    //         )
    //         ->unionAll(
    //             DB::table('bk_sub_chart_of_accounts')
    //                 ->select(
    //                     'id as coaid',
    //                     'sub_account_name as account_name',
    //                     'sub_code as code'
    //                 )
    //         );
        
    //     $generalLedger = DB::table('bk_generalledg')
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
    //             'accounts.code'
    //         )
    //         ->where('bk_generalledg.deleted', 0)
    //         ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
    //             $q->whereBetween('bk_generalledg.date', [$startDate, $endDate]);
    //         })
    //         ->orderBy('bk_generalledg.date', 'desc')
    //         ->get();
    
    //     $groupedResults = $generalLedger->groupBy(function ($item) {
    //         return $item->voucherNo ?? 'unvouchered_'.$item->date.'_'.$item->remarks;
    //     });

    //     $sortedGroups = $groupedResults->sortByDesc(function ($group) {
    //         return $group->first()->date ?? null;
    //     });

    //     $sortedResults = $sortedGroups->flatMap(function ($group) {
    //         return $group;
    //     });
   
    //     return response()->json([
    //         'mergedAndSorted' => $sortedResults
    //     ]);

    // }

    // 2
    public function displayGeneralLedger(Request $request)
    {
        $fiscalYearId = $request->input('fiscal_year_id');
        $dateRange = $request->input('date_range');
        $searchTerm = $request->input('search.value');

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

        // if ($request->has('date_range') && !empty($request->date_range)) {
        //     $dates = explode(' - ', $request->date_range);
        //     if (count($dates) == 2) {
        //         try {
        //             $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dates[0]))->startOfDay();
        //             $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($dates[1]))->endOfDay();
        //         } catch (\Exception $e) {
        //             return response()->json(['error' => 'Invalid date format'], 400);
        //         }
        //     }
        // }

        // // Get fiscal year dates if selected
        // if ($request->has('fiscal_year_id') && $request->fiscal_year_id != '') {
        //     $fiscalYear = DB::table('bk_fiscal_year')
        //         ->where('id', $request->fiscal_year_id)
        //         ->first();
                
        //     if ($fiscalYear) {
        //         $startDate = \Carbon\Carbon::parse($fiscalYear->stime)->startOfDay();
        //         $endDate = \Carbon\Carbon::parse($fiscalYear->etime)->endOfDay();
        //     }
        // }

        // Base query with account union
        $accountUnion = DB::table('chart_of_accounts')
            ->select('id as coaid', 'account_name', 'code')
            ->unionAll(
                DB::table('bk_sub_chart_of_accounts')
                    ->select('id as coaid', 'sub_account_name as account_name', 'sub_code as code')
            );

        $query = DB::table('bk_generalledg')
            ->leftJoinSub($accountUnion, 'accounts', function($join) {
                $join->on('bk_generalledg.coaid', '=', 'accounts.coaid');
            })
            ->select(
                'bk_generalledg.voucherNo',
                'bk_generalledg.date',
                'bk_generalledg.remarks',
                'accounts.code',
                'accounts.account_name',
                'bk_generalledg.debit_amount',
                'bk_generalledg.credit_amount'
            )
            ->orderBy('bk_generalledg.voucherNo', 'desc')
            ->orderByRaw('CASE WHEN bk_generalledg.debit_amount > 0 THEN 0 ELSE 1 END')
            ->orderBy('accounts.code')
            ->where('bk_generalledg.deleted', 0);

        // Apply date filters
        if ($startDate && $endDate) {
            $query->whereBetween('bk_generalledg.date', [$startDate, $endDate]);
        }

        // Handle search
        if ($request->has('search') && !empty($request->search['value'])) {
            $searchTerm = $request->search['value'];
            $query->where(function($q) use ($searchTerm) {
                $q->where('bk_generalledg.voucherNo', 'like', "%{$searchTerm}%")
                ->orWhere('bk_generalledg.remarks', 'like', "%{$searchTerm}%")
                ->orWhere('accounts.code', 'like', "%{$searchTerm}%")
                ->orWhere('accounts.account_name', 'like', "%{$searchTerm}%")
                ->orWhere('bk_generalledg.date', 'like', "%{$searchTerm}%");
            });
        }

        // Get total records count before filtering
        $totalRecords = DB::table('bk_generalledg')
            ->where('deleted', 0)
            ->when($startDate && $endDate, function($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate]);
            })
            ->count();

        // Apply ordering
        if ($request->has('order')) {
            $orderColumnIndex = $request->order[0]['column'];
            $orderColumn = $request->columns[$orderColumnIndex]['voucherNo'] ?? 'voucherNo';
            $orderDirection = $request->order[0]['dir'] ?? 'date';
            
            // Map column names to database fields
            $dbColumns = [
                'voucherNo' => 'bk_generalledg.voucherNo',
                'date' => 'bk_generalledg.date',
                'remarks' => 'bk_generalledg.remarks',
                'code' => 'accounts.code',
                'account_name' => 'accounts.account_name',
                'debit_amount' => 'bk_generalledg.debit_amount',
                'credit_amount' => 'bk_generalledg.credit_amount'
            ];
            
            if (isset($dbColumns[$orderColumn])) {
                $query->orderBy($dbColumns[$orderColumn], $orderDirection);
            }
        }

        // Get filtered count (after search)
        $filteredCount = $query->count();

        // Apply pagination
        if ($request->has('start') && $request->has('length')) {
            $query->offset($request->start)->limit($request->length);
        }

        // Execute query
        $results = $query->get();
    
        // Process results for grouping
        $processedData = [];
        $currentGroup = null;

        foreach ($results as $entry) {
            $groupKey = $entry->voucherNo ?? 'unvouchered_' . $entry->date . '_' . $entry->remarks;
            $isFirstInGroup = ($groupKey !== $currentGroup);
            $currentGroup = $groupKey;

            $processedData[] = [
                'voucherNo' => $entry->voucherNo,
                'date' => $entry->date,
                'remarks' => $entry->remarks,
                'code' => $entry->code,
                'account_name' => $entry->account_name,
                'debit_amount' => $entry->debit_amount,
                'credit_amount' => $entry->credit_amount,
                'isFirstInGroup' => $isFirstInGroup
            ];
        }

        // Get totals for footer
        // $totals = DB::table('bk_generalledg')
        //     ->select(
        //         DB::raw('COALESCE(SUM(debit_amount), 0) as total_debit'),
        //         DB::raw('COALESCE(SUM(credit_amount), 0) as total_credit')
        //     )
        //     ->where('deleted', 0)
        //     ->whereBetween('bk_generalledg.date', [$startDate, $endDate])
        //     ->when($request->has('search') && !empty($request->search['value']), function($q) use ($request) {
        //         $searchTerm = $request->search['value'];
        //         $q->where(function($query) use ($searchTerm) {
        //             $query->where('voucherNo', 'like', "%{$searchTerm}%")
        //                 ->orWhere('remarks', 'like', "%{$searchTerm}%")
        //                 ->orWhere('date', 'like', "%{$searchTerm}%");
        //         });
        //     })
        //     ->first();
        $totals = DB::table('bk_generalledg')
            ->leftJoinSub($accountUnion, 'accounts', function($join) {
                $join->on('bk_generalledg.coaid', '=', 'accounts.coaid');
            })
            ->select(
                DB::raw('COALESCE(SUM(bk_generalledg.debit_amount), 0) as total_debit'),
                DB::raw('COALESCE(SUM(bk_generalledg.credit_amount), 0) as total_credit')
            )
            ->where('bk_generalledg.deleted', 0)
            ->whereBetween('bk_generalledg.date', [$startDate, $endDate]);

        // Apply same search conditions to totals query
        if ($searchTerm) {
            $totals->where(function($q) use ($searchTerm) {
                $q->where('bk_generalledg.voucherNo', 'like', "%{$searchTerm}%")
                ->orWhere('bk_generalledg.remarks', 'like', "%{$searchTerm}%")
                ->orWhere('accounts.code', 'like', "%{$searchTerm}%")
                ->orWhere('accounts.account_name', 'like', "%{$searchTerm}%")
                ->orWhere('bk_generalledg.date', 'like', "%{$searchTerm}%");
            });
        }

        $totals = $totals->first();
    
        return response()->json([
            'draw' => $request->draw ?? 1,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredCount,
            'data' => $processedData,
            'totalDebit' => $totals->total_debit ?? 0,
            'totalCredit' => $totals->total_credit ?? 0
        ]);
    }


    // public function syncLedger(Request $request){
    //     $fiscal_yearid = $request->get('fiscal_yearid');

    //     $startDate = null;
    //     $endDate = null;

    //     // Get fiscal year date range if fiscal_year_id is provided
    //     if ($request->has('fiscal_yearid') && $request->fiscal_yearid != '') {
    //         $fiscalYear = DB::table('bk_fiscal_year')->where('id', $fiscal_yearid)->first();

    //         if ($fiscalYear) {
    //             $startDate = \Carbon\Carbon::parse($fiscalYear->stime)->startOfDay();
    //             $endDate = \Carbon\Carbon::parse($fiscalYear->etime)->endOfDay();
    //         }
    //     }
        
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

    //     // STUDLEDGER
    //     $studledgerQuery = DB::table('studledger')
    //         ->selectRaw(
    //         'studledger.createddatetime as transdate,
    //         studledger.studid,
    //             studinfo.firstname,
    //             studinfo.middlename,
    //             studinfo.lastname,
    //             SUM(studledger.amount) as total_amount,
    //             coa_debit.code as debit_account_code,
    //             coa_debit.account_name as debit_account,
    //             coa_credit.code as credit_account_code,
    //             coa_credit.account_name as credit_account,
    //             coa_debit.id as debit_coaid,
    //             coa_credit.id as credit_coaid
    //         ')
    //         ->distinct()
    //         ->join('studinfo', 'studledger.studid', '=', 'studinfo.id')
    //         ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
    //         ->leftJoin('bk_classifiedsetup', 'studinfo.levelid', '=', 'bk_classifiedsetup.levelid')
    //         ->leftJoin('chart_of_accounts as coa_debit', 'bk_classifiedsetup.debitaccid', '=', 'coa_debit.id')
    //         ->leftJoin('chart_of_accounts as coa_credit', 'bk_classifiedsetup.creditaccid', '=', 'coa_credit.id')
    //         ->where('studledger.deleted', 0)
    //         ->where('studinfo.deleted', 0)
    //         ->where('studledger.payment', 0);

    //     // Optional date filter
    //     if ($startDate && $endDate) {
    //         $studledgerQuery->whereBetween('studledger.createddatetime', [$startDate, $endDate]);
    //     }

    //     // Group by fields (all non-aggregates in selectRaw)
    //     $studledgerQuery->groupBy(
    //         'studledger.studid',
    //         'studinfo.firstname',
    //         'studinfo.middlename',
    //         'studinfo.lastname',
    //         'coa_debit.code',
    //         'coa_debit.account_name',
    //         'coa_credit.code',
    //         'coa_credit.account_name',
    //         'coa_debit.id',
    //         'coa_credit.id'
    //     );

    //     // Get the results
    //     $studledgerRecords = $studledgerQuery->get();
   
    //     foreach ($studledgerRecords as $transaction) {
    //         $student = $enrolledStudents->get($transaction->studid);
    //         if (!$student) continue;
            
    //         $studentName = trim("{$student->firstname} {$student->middlename} {$student->lastname}");
    //         $now = Carbon::now();
    //         $createdBy = auth()->id();
        
    //         $voucherNo = $this->generateVoucherNumberJE();
    //         $ifexistSl = DB::table('bk_generalledg')
    //             ->where('studledger_studid', $transaction->studid)
    //             ->first();
           
    //         if($ifexistSl)
    //         {
    //             DB::table('bk_generalledg')
    //                 ->where('studledger_studid', $transaction->studid)
    //                 ->where('active_fiscal_year_id', $fiscal_yearid)
    //                 ->where('credit_amount', 0.00)
    //                 ->update([
    //                     'debit_amount' => $transaction->total_amount,
    //                     'credit_amount'         => 0,
    //                     'updateddatetime' => $now,
    //                     'updatedby' => $createdBy,
    //                 ]);
            
    //             DB::table('bk_generalledg')
    //                 ->where('studledger_studid', $transaction->studid)
    //                 ->where('active_fiscal_year_id', $fiscal_yearid)
    //                 ->where('debit_amount', 0.00)
    //                 ->update([
    //                     'credit_amount' => $transaction->total_amount,
    //                     'debit_amount'          => 0,
    //                     'remarks' => "$studentName - LEDGER",
    //                     'updateddatetime' => $now,
    //                     'updatedby' => $createdBy,
    //                 ]);
    //         }
    //         else{
    //             DB::table('bk_generalledg')->insert([
    //                 'voucherNo'             => $voucherNo,
    //                 'date'                  => $transaction->transdate,
    //                 'coaid'                 => $transaction->debit_coaid,
    //                 'debit_amount'          => $transaction->total_amount,
    //                 'credit_amount'         => 0,
    //                 'remarks'               => "$studentName - LEDGER",
    //                 'createddatetime'       => $now,
    //                 'createdby'             => $createdBy,
    //                 'deleted'               => 0,
    //                 'active_fiscal_year_id'=> $fiscal_yearid,
    //                 'studledger_studid' => $transaction->studid
    //             ]);
            
    //             DB::table('bk_generalledg')->insert([
    //                 'voucherNo'             => $voucherNo,
    //                 'date'                  => $transaction->transdate,
    //                 'coaid'                 => $transaction->credit_coaid,
    //                 'debit_amount'          => 0,
    //                 'credit_amount'         => $transaction->total_amount,
    //                 'remarks'               => "$studentName - LEDGER",
    //                 'createddatetime'       => $now,

    //                 'createdby'             => $createdBy,
    //                 'deleted'               => 0,
    //                 'active_fiscal_year_id'=> $fiscal_yearid,
    //                 'studledger_studid' => $transaction->studid
    //             ]);
    //         }
           
    //     }


    //     // CASH TRANS / PAYMENTS
    //     $cashierAccount = DB::table('chart_of_accounts')->where('cashierje_isctive', 1)->where('deleted', 0)->first();
    
    //     $cashtransQuery = DB::table('chrngtrans')
    //         ->select([
    //             'chrngtrans.id',
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
    //             'coa_debit.code as credit_account_code',
    //             'coa_debit.account_name as credit_account',
    //             'coa_debit.id as credit_coaid',
    //         ])
    //         ->distinct()
    //         ->where('chrngtrans.gl_status', 0)
    //         ->where('chrngtrans.cancelled', 0)
    //         ->join('studinfo', 'chrngtrans.studid', '=', 'studinfo.id')
    //         ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
    //         ->leftJoin('bk_classifiedsetup', 'studinfo.levelid', '=', 'bk_classifiedsetup.levelid')
    //         // ->leftJoin('chart_of_accounts as coa_debit', 'bk_classifiedsetup.debitaccid', '=', 'coa_debit.id');
    //         ->leftJoin('chart_of_accounts as coa_debit', 'bk_classifiedsetup.debitaccid', '=', 'coa_debit.id');

    //     if ($cashierAccount) {
    //         // If cashier account is available, hardcode credit values in SELECT
    //         $cashtransQuery->addSelect([
    //             DB::raw("'" . $cashierAccount->code . "' as debit_account_code"),
    //             DB::raw("'" . $cashierAccount->account_name . "' as debit_account"),
    //             DB::raw($cashierAccount->id . ' as debit_coaid'),
    //         ]);
    //     } else {
    //         // Otherwise, join normally to get credit account
    //         $cashtransQuery->leftJoin('chart_of_accounts as coa_debit', 'bk_classifiedsetup.debitaccid', '=', 'coa_debit.id')
    //             ->addSelect([
    //                 'coa_debit.code as credit_account_code',
    //                 'coa_debit.account_name as credit_account',
    //                 'coa_debit.id as credit_coaid',
    //             ]);
    //     }

    //     if ($startDate && $endDate) {
    //         $cashtransQuery->whereBetween('transdate', [$startDate, $endDate]);
    //     }

    //     $cashtrans = $cashtransQuery->get();
      
    //     foreach ($cashtrans as $transaction) {
    //         $student = $enrolledStudents->get($transaction->studid);
          
    //         if (!$student) continue;
           
    //         DB::table('chrngtrans')
    //             ->where('id', $transaction->id)
    //             ->update(['gl_status' => 1]);
        
    //         $studentName = trim("{$student->firstname} {$student->middlename} {$student->lastname}");
    //         $now = Carbon::now();
    //         $createdBy = auth()->id();
    //         $activeFiscalYearId = $activeFiscalYearId ?? null;
    //         $voucherNo = $this->generateVoucherNumberJE();
        
    //         DB::table('bk_generalledg')->insert([
    //             'voucherNo'             => $voucherNo,
    //             'date'                  => $transaction->transdate,
    //             'coaid'                 => $transaction->debit_coaid,
    //             'debit_amount'          => $transaction->amountpaid,
    //             'credit_amount'         => 0,
    //             'remarks'               => "$studentName - Payments",
    //             'createddatetime'       => $now,
    //             'createdby'             => $createdBy,
    //             'deleted'               => 0,
    //             'active_fiscal_year_id'=> $fiscal_yearid,
    //         ]);
        
    //         DB::table('bk_generalledg')->insert([
    //             'voucherNo'             => $voucherNo,
    //             'date'                  => $transaction->transdate,
    //             'coaid'                 => $transaction->credit_coaid,
    //             'debit_amount'          => 0,
    //             'credit_amount'         => $transaction->amountpaid,
    //             'remarks'               => "$studentName - Payments",
    //             'createddatetime'       => $now,
    //             'createdby'             => $createdBy,
    //             'deleted'               => 0,
    //             'active_fiscal_year_id'=> $fiscal_yearid,
    //         ]);
    //     }

    //     // ADJUSTMENTS
    //     $d_debitAccounts = collect();
    //     $d_creditAccounts = collect();
    //     $c_debitAccounts = collect();
    //     $c_creditAccounts = collect();

    //     // DEBITS
    //     // charts of accounts
    //     $d_coa_adj_debit   = DB::table('chart_of_accounts')->where('d_adjustmentje_debactive', 1)->where('deleted', 0)->first();
    //     $d_coa_adj_credit  = DB::table('chart_of_accounts')->where('d_adjustmentje_credactive', 1)->where('deleted', 0)->first();
    //     // sub charts of accounts
    //     $d_subcoa_adj_debit  = DB::table('bk_sub_chart_of_accounts')->where('d_adjustmentje_debactive', 1)->where('deleted', 0)->first();
    //     $d_subcoa_adj_credit = DB::table('bk_sub_chart_of_accounts')->where('d_adjustmentje_credactive', 1)->where('deleted', 0)->first();
        
    //     $d_debitAccounts = $d_debitAccounts->merge($d_coa_adj_debit);
    //     $d_debitAccounts = $d_debitAccounts->merge($d_subcoa_adj_debit);
    //     $d_creditAccounts = $d_creditAccounts->merge($d_coa_adj_credit);
    //     $d_creditAccounts = $d_creditAccounts->merge($d_subcoa_adj_credit);

    //     // CREDITS
    //     // charts of accounts
    //     $c_coa_adj_debit = DB::table('chart_of_accounts')->where('c_adjustmentje_debactive', 1)->where('deleted', 0)->first();
    //     $c_coa_adj_credit = DB::table('chart_of_accounts')->where('c_adjustmentje_credactive', 1)->where('deleted', 0)->first();
    //     // sub charts of accounts
    //     $c_subcoa_adj_debit = DB::table('bk_sub_chart_of_accounts')->where('c_adjustmentje_debactive', 1)->where('deleted', 0)->first();
    //     $c_subcoa_adj_credit = DB::table('bk_sub_chart_of_accounts')->where('c_adjustmentje_credactive', 1)->where('deleted', 0)->first();
        
        
    //     $c_debitAccounts = $c_debitAccounts->merge($c_coa_adj_debit);
    //     $c_debitAccounts = $c_debitAccounts->merge($c_subcoa_adj_debit);
    //     $c_creditAccounts = $c_creditAccounts->merge($c_coa_adj_credit);
    //     $c_creditAccounts = $c_creditAccounts->merge($c_subcoa_adj_credit);


    //     $adjustmentsQuery = DB::table('adjustments')
    //         ->select([
    //             'adjustments.id', 
    //             'adjustments.refnum as voucherNo', 
    //             'adjustments.description as remarks', 
    //             'adjustments.createddatetime as transdate', 
    //             'adjustments.amount', 
    //             'adjustments.isdebit', 
    //             'adjustments.iscredit',
    //             'adjustmentdetails.studid',
    //             'studinfo.firstname',
    //             'studinfo.lastname',
    //             'studinfo.middlename',
    //             // 'coa_debit.code as debit_account_code',
    //             // 'coa_debit.account_name as debit_account',
    //             // 'coa_credit.code as credit_account_code',
    //             // 'coa_credit.account_name as credit_account',
    //             // 'coa_debit.id as debit_coaid',
    //             // 'coa_credit.id as credit_coaid'
    //         ])
    //         ->distinct()
    //         ->where('adjustments.gl_status', 0)
    //         ->where('adjustments.deleted', 0)
    //         ->where('adjustmentdetails.deleted', 0)
    //         ->join('adjustmentdetails', 'adjustmentdetails.headerid', '=', 'adjustments.id')
    //         ->join('studinfo', 'adjustmentdetails.studid', '=', 'studinfo.id')
    //         ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id');
    //         // ->leftJoin('bk_classifiedsetup', 'studinfo.levelid', '=', 'bk_classifiedsetup.levelid')
    //         // ->leftJoin('chart_of_accounts as coa_debit', 'bk_classifiedsetup.debitaccid', '=', 'coa_debit.id')
    //         // ->leftJoin('chart_of_accounts as coa_credit', 'bk_classifiedsetup.creditaccid', '=', 'coa_credit.id');
            
    //     if ($startDate && $endDate) {
    //         $adjustmentsQuery->whereBetween('adjustments.createddatetime', [$startDate, $endDate]);
    //     }

    //     $adjustmentsQuery = $adjustmentsQuery->get();
       
    //     foreach ($adjustmentsQuery as $transaction) {
            
           

    //         $adjustmentType = $transaction->iscredit == 1 ? 'Credit' : ($transaction->isdebit == 1 ? 'Debit' : '');
    //         $student = $enrolledStudents->get($transaction->studid);
          
    //         if (!$student) continue;
    //         DB::table('adjustments')
    //             ->where('id', $transaction->id)
    //             ->update(['gl_status' => 1]);
            
    //         $studentName = trim("{$student->firstname} {$student->middlename} {$student->lastname}");
    //         $now = Carbon::now();
    //         $createdBy = auth()->id();
    //         $activeFiscalYearId = $activeFiscalYearId ?? null;
            
        

    //         if ($transaction->iscredit == 1) {
    //             $voucherNo = $this->generateVoucherNumberJE();
    //             // c_debitAccounts
    //             // c_creditAccounts
            
    //             DB::table('bk_generalledg')->insert([
    //                 'voucherNo'             => $voucherNo,
    //                 'date'                  => $transaction->transdate,
    //                 'coaid'                 => $c_debitAccounts['id'],
    //                 'debit_amount'          => $transaction->amount,
    //                 'credit_amount'         => 0,
    //                 'remarks'               => "$studentName - $adjustmentType ADJUSTMENT",
    //                 'createddatetime'       => $now,
    //                 'createdby'             => $createdBy,
    //                 'deleted'               => 0,
    //                 'active_fiscal_year_id' => $fiscal_yearid,
    //                 'sub'                   => $c_debitAccounts['sub']
    //             ]);
            
    //             DB::table('bk_generalledg')->insert([
    //                 'voucherNo'             => $voucherNo,
    //                 'date'                  => $transaction->transdate,
    //                 'coaid'                 => $c_creditAccounts['id'],
    //                 'debit_amount'          => 0,
    //                 'credit_amount'         => $transaction->amount,
    //                 'remarks'               => "$studentName - $adjustmentType ADJUSTMENT",
    //                 'createddatetime'       => $now,
    //                 'createdby'             => $createdBy,
    //                 'deleted'               => 0,
    //                 'active_fiscal_year_id'=> $fiscal_yearid,
    //                 'sub'                   => $c_creditAccounts['sub'],
    //             ]);
    //         } elseif ($transaction->isdebit == 1) {
    //             $voucherNo = $this->generateVoucherNumberJE();
    //             // d_debitAccounts
    //             // d_creditAccounts
                
    //             DB::table('bk_generalledg')->insert([
    //                 'voucherNo'             => $voucherNo,
    //                 'date'                  => $transaction->transdate,
    //                 'coaid'                 => $d_debitAccounts['id'],
    //                 'debit_amount'          => $transaction->amount,
    //                 'credit_amount'         => 0,
    //                 'remarks'               => "$studentName - $adjustmentType ADJUSTMENT",
    //                 'createddatetime'       => $now,
    //                 'createdby'             => $createdBy,
    //                 'deleted'               => 0,
    //                 'active_fiscal_year_id'=> $fiscal_yearid,
    //                 'sub'                   => $d_debitAccounts['sub'],
                    
    //             ]);
            
    //             DB::table('bk_generalledg')->insert([
    //                 'voucherNo'             => $voucherNo,
    //                 'date'                  => $transaction->transdate,
    //                 'coaid'                 => $d_creditAccounts['id'],
    //                 'debit_amount'          => 0,
    //                 'credit_amount'         => $transaction->amount,
    //                 'remarks'               => "$studentName - $adjustmentType ADJUSTMENT",
    //                 'createddatetime'       => $now,
    //                 'createdby'             => $createdBy,
    //                 'deleted'               => 0,
    //                 'active_fiscal_year_id'=> $fiscal_yearid,
    //                 'sub'                   => $d_creditAccounts['sub'],
    //             ]);
    //         } else {
    //             // $voucherNo = $this->generateVoucherNumberJE();
    //             // $adjustmentType = 'Unknown'; // or handle other cases as needed
              
    //             // DB::table('bk_generalledg')->insert([
    //             //     'voucherNo'             => $voucherNo,
    //             //     'date'                  => $transaction->transdate,
    //             //     'coaid'                 => null,
    //             //     'debit_amount'          => $transaction->amount,
    //             //     'credit_amount'         => 0,
    //             //     'remarks'               => "$studentName - $adjustmentType ADJUSTMENT",
    //             //     'createddatetime'       => $now,
    //             //     'createdby'             => $createdBy,
    //             //     'deleted'               => 0,
    //             //     'active_fiscal_year_id'=> $fiscal_yearid,
    //             // ]);
            
    //             // DB::table('bk_generalledg')->insert([
    //             //     'voucherNo'             => $voucherNo,
    //             //     'date'                  => $transaction->transdate,
    //             //     'coaid'                 => null,
    //             //     'debit_amount'          => 0,
    //             //     'credit_amount'         => $transaction->amount,
    //             //     'remarks'               => "$studentName - $adjustmentType ADJUSTMENT",
    //             //     'createddatetime'       => $now,
    //             //     'createdby'             => $createdBy,
    //             //     'deleted'               => 0,
    //             //     'active_fiscal_year_id'=> $fiscal_yearid,
    //             // ]);
    //         }

    //     }

    //     // DISCOUNTS
    //     $debitDiscounts = collect();
    //     $creditDiscounts = collect();

    //     $coa_disc_debit   = DB::table('chart_of_accounts')->where('discountje_debit_isctive', 1)->where('deleted', 0)->first();
    //     $coa_disc_credit  = DB::table('chart_of_accounts')->where('discountje_credit_isctive', 1)->where('deleted', 0)->first();
    //     $sub_coa_disc_debit   = DB::table('bk_sub_chart_of_accounts')->where('discountje_debit_isctive', 1)->where('deleted', 0)->first();
    //     $sub_coa_disc_credit  = DB::table('bk_sub_chart_of_accounts')->where('discountje_credit_isctive', 1)->where('deleted', 0)->first();

    //     $debitDiscounts = $debitDiscounts->merge($coa_disc_debit);
    //     $debitDiscounts = $debitDiscounts->merge($sub_coa_disc_debit);
    //     $creditDiscounts = $creditDiscounts->merge($coa_disc_credit);
    //     $creditDiscounts = $creditDiscounts->merge($sub_coa_disc_credit);

    //     $discountQuery = DB::table('studdiscounts')
    //         ->select([
    //             'studdiscounts.id', 
    //             'studdiscounts.createddatetime as transdate', 
    //             'studdiscounts.discamount as amount', 
    //             'studdiscounts.studid',
    //             'studinfo.firstname',
    //             'studinfo.lastname',
    //             'studinfo.middlename',
    //             // DB::raw('COALESCE(sub_debit_coa.sub_code, main_debit_coa.code) AS debit_account_code'),
    //             // DB::raw('COALESCE(sub_debit_coa.sub_account_name, main_debit_coa.account_name) AS debit_account'),
    //             // 'coa_credit.code as credit_account_code',
    //             // 'coa_credit.account_name as credit_account',
    //             // 'coa_debit.id as debit_coaid',
    //             // 'coa_credit.id as credit_coaid'
    //         ])
    //         ->distinct()
    //         ->where('studdiscounts.gl_status', 0)
    //         ->where('studdiscounts.deleted', 0)
    //         ->join('studinfo', 'studdiscounts.studid', '=', 'studinfo.id')
    //         ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id');
    //         // ->leftJoin('bk_sub_chart_of_accounts as sub_debit_coa', function($join) {
    //         //     $join->on('sub_debit_coa.id', '=', DB::raw('(SELECT id FROM bk_sub_chart_of_accounts WHERE deleted = 0 AND discountje_isctive = 1 LIMIT 1)'));
    //         // })
    //         // ->leftJoin('chart_of_accounts as main_debit_coa', function($join) {
    //         //     $join->on('main_debit_coa.id', '=', DB::raw('(SELECT id FROM chart_of_accounts WHERE deleted = 0 AND discountje_isctive = 1 LIMIT 1)'));
    //         // })
    //         // ->leftJoin('bk_classifiedsetup', 'studinfo.levelid', '=', 'bk_classifiedsetup.levelid')
    //         // ->leftJoin('chart_of_accounts as coa_debit', 'bk_classifiedsetup.debitaccid', '=', 'coa_debit.id')
    //         // ->leftJoin('chart_of_accounts as coa_credit', 'bk_classifiedsetup.creditaccid', '=', 'coa_credit.id');
            
    //     if ($startDate && $endDate) {
    //         $discountQuery->whereBetween('studdiscounts.createddatetime', [$startDate, $endDate]);
    //     }

    //     $discountQuery = $discountQuery->get();
      
    //     foreach ($discountQuery as $transaction) {
    //         $student = $enrolledStudents->get($transaction->studid);
    //         if (!$student) continue;
           
    //         DB::table('studdiscounts')
    //             ->where('id', $transaction->id)
    //             ->update(['gl_status' => 1]);
        
    //         $studentName = trim("{$student->firstname} {$student->middlename} {$student->lastname}");
    //         $now = Carbon::now();
    //         $createdBy = auth()->id();
    //         $activeFiscalYearId = $activeFiscalYearId ?? null;
    //         $voucherNo = $this->generateVoucherNumberJE();

    //         DB::table('bk_generalledg')->insert([
    //             'voucherNo'             => $voucherNo,
    //             'date'                  => $transaction->transdate,
    //             'coaid'                 => $debitDiscounts['id'],
    //             'debit_amount'          => $transaction->amount,
    //             'credit_amount'         => 0,
    //             'remarks'               => "$studentName - DISCOUNT",
    //             'createddatetime'       => $now,
    //             'createdby'             => $createdBy,
    //             'deleted'               => 0,
    //             'active_fiscal_year_id' => $fiscal_yearid,
    //             'sub'                 => $debitDiscounts['sub'],

    //         ]);
        
    //         DB::table('bk_generalledg')->insert([
    //             'voucherNo'             => $voucherNo,
    //             'date'                  => $transaction->transdate,
    //             'coaid'                 => $creditDiscounts['id'],
    //             'debit_amount'          => 0,
    //             'credit_amount'         => $transaction->amount,
    //             'remarks'               => "$studentName - DISCOUNT",
    //             'createddatetime'       => $now,
    //             'createdby'             => $createdBy,
    //             'deleted'               => 0,
    //             'active_fiscal_year_id' => $fiscal_yearid,
    //             'sub'                   => $creditDiscounts['sub'],
    //         ]);
    //     }


    //     return array((object)[
    //         'status' => 1,
    //         'message' => 'Sync Successfully',
    //     ]);
        
    // }


    public function syncLedger(Request $request){
        //PARA SA VOID TRANSACTION TYPE CASHTRANS = 1, ADJUSTMENTS = 2, DISCOUNTS = 3 
        $fiscal_yearid = $request->get('fiscal_yearid');
    
        $startDate = null;
        $endDate = null;
    
        // Get fiscal year date range if fiscal_year_id is provided
        if ($request->has('fiscal_yearid') && $request->fiscal_yearid != '') {
            $fiscalYear = DB::table('bk_fiscal_year')->where('id', $fiscal_yearid)->first();
    
            if ($fiscalYear) {
                $startDate = \Carbon\Carbon::parse($fiscalYear->stime)->startOfDay();
                $endDate = \Carbon\Carbon::parse($fiscalYear->etime)->endOfDay();
            }
        }
        
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
                '),
                DB::raw('
                    COALESCE(
                        enrolledstud.levelid,
                        sh_enrolledstud.levelid,
                        college_enrolledstud.yearLevel
                    ) AS levelid
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

        // STUDLEDGER

        $ledger_account_je = DB::table('bk_classifiedsetup')->where('deleted', 0)->get();
   
        $studledgerQuery = DB::table('studledger')
            ->selectRaw(
            'studledger.createddatetime as transdate,
            studledger.studid,
                studinfo.firstname,
                studinfo.middlename,
                studinfo.lastname,
                SUM(studledger.amount) as total_amount,
                coa_debit.code as debit_account_code,
                coa_debit.account_name as debit_account,
                coa_credit.code as credit_account_code,
                coa_credit.account_name as credit_account,
                coa_debit.id as debit_coaid,
                coa_credit.id as credit_coaid
            ')
            ->distinct()
            ->join('studinfo', 'studledger.studid', '=', 'studinfo.id')
            ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
            ->leftJoin('bk_classifiedsetup', 'studinfo.levelid', '=', 'bk_classifiedsetup.levelid')
            ->leftJoin('chart_of_accounts as coa_debit', 'bk_classifiedsetup.debitaccid', '=', 'coa_debit.id')
            ->leftJoin('chart_of_accounts as coa_credit', 'bk_classifiedsetup.creditaccid', '=', 'coa_credit.id')
            ->where(function ($query) {
                $query->where('particulars', 'NOT LIKE', '%PAYMENT FOR%')
                    ->where('particulars', 'NOT LIKE', 'ADJ:%')
                    ->where('particulars', 'NOT LIKE', 'DISCOUNT:%')
                    ->where('particulars', 'NOT LIKE', 'Balance forwarded from%')
                    ->where('particulars', 'NOT LIKE', 'Balance forwarded to%');
            })
            ->where('studledger.deleted', 0)
            ->where('studinfo.deleted', 0)
            ->where('studledger.payment', 0);
    
        // Optional date filter
        if ($startDate && $endDate) {
            $studledgerQuery->whereBetween('studledger.createddatetime', [$startDate, $endDate]);
        }
    
        // Group by fields (all non-aggregates in selectRaw)
        $studledgerQuery->groupBy(
            'studledger.studid',
            'studinfo.firstname',
            'studinfo.middlename',
            'studinfo.lastname',
            'coa_debit.code',
            'coa_debit.account_name',
            'coa_credit.code',
            'coa_credit.account_name',
            'coa_debit.id',
            'coa_credit.id'
        );
    
        // Get the results
        $studledgerRecords = $studledgerQuery->get();
      
        foreach ($studledgerRecords as $transaction) {

            $student = $enrolledStudents->get($transaction->studid);

            if (!$student) continue;

            $student_account_je = collect($ledger_account_je)->where('levelid', $student->levelid)->values();
            
            $studentName = trim("{$student->firstname} {$student->middlename} {$student->lastname}");
            $now = Carbon::now();
            $createdBy = auth()->id();
        
            $voucherNo = $this->generateVoucherNumberJE();
            $ifexistSl = DB::table('bk_generalledg')
                ->where('studledger_studid', $transaction->studid)
                ->first();
           
            if($ifexistSl)
            {
                DB::table('bk_generalledg')
                    ->where('studledger_studid', $transaction->studid)
                    ->where('active_fiscal_year_id', $fiscal_yearid)
                    ->where('credit_amount', 0.00)
                    ->update([
                        'debit_amount' => $transaction->total_amount,
                        'credit_amount'         => 0,
                        'updateddatetime' => $now,
                        'updatedby' => $createdBy,
                    ]);
            
                DB::table('bk_generalledg')
                    ->where('studledger_studid', $transaction->studid)
                    ->where('active_fiscal_year_id', $fiscal_yearid)
                    ->where('debit_amount', 0.00)
                    ->update([
                        'credit_amount' => $transaction->total_amount,
                        'debit_amount'          => 0,
                        'remarks' => "$studentName - LEDGER",
                        'updateddatetime' => $now,
                        'updatedby' => $createdBy,
                    ]);
            }
            else{
                DB::table('bk_generalledg')->insert([
                    'voucherNo'             => $voucherNo,
                    'date'                  => $transaction->transdate,
                    'coaid'                 => $student_account_je->first()->debitaccid ?? null,
                    'debit_amount'          => $transaction->total_amount,
                    'credit_amount'         => 0,
                    'remarks'               => "$studentName - LEDGER",
                    'createddatetime'       => $now,
                    'createdby'             => $createdBy,
                    'deleted'               => 0,
                    'active_fiscal_year_id'=> $fiscal_yearid,
                    'studledger_studid' => $transaction->studid
                ]);
            
                DB::table('bk_generalledg')->insert([
                    'voucherNo'             => $voucherNo,
                    'date'                  => $transaction->transdate,
                    'coaid'                 => $student_account_je->first()->creditaccid ?? null,
                    'debit_amount'          => 0,
                    'credit_amount'         => $transaction->total_amount,
                    'remarks'               => "$studentName - LEDGER",
                    'createddatetime'       => $now,
                    'createdby'             => $createdBy,
                    'deleted'               => 0,
                    'active_fiscal_year_id'=> $fiscal_yearid,
                    'studledger_studid' => $transaction->studid
                ]);
            }
           
        }
    
    
        // CASH TRANS / PAYMENTS
        $cashierAccount = DB::table('chart_of_accounts')->where('cashierje_isctive', 1)->where('deleted', 0)->first();
        $payment_account_je = DB::table('bk_classifiedsetup')->where('deleted', 0)->get();
  
        $cashtransQuery = DB::table('chrngtrans')
            ->select([
                'chrngtrans.id',
                'chrngtrans.transdate',
                'chrngtrans.ornum',
                'chrngtrans.studid',
                'chrngtrans.studname',
                'chrngtrans.paytype',
                'chrngtrans.totalamount',
                'chrngtrans.amountpaid',
                'chrngtrans.refno',
                'chrngtrans.sid',
                'chrngtrans.accountname',
                'studinfo.firstname',
                'studinfo.lastname',
                'studinfo.middlename',
                'coa_debit.code as credit_account_code',
                'coa_debit.account_name as credit_account',
                'coa_debit.id as credit_coaid',
            ])
            ->distinct()
            ->where('chrngtrans.gl_status', 0)
            ->where('chrngtrans.cancelled', 0)
            ->join('studinfo', 'chrngtrans.studid', '=', 'studinfo.id')
            ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
            ->leftJoin('bk_classifiedsetup', 'studinfo.levelid', '=', 'bk_classifiedsetup.levelid')
            ->leftJoin('chart_of_accounts as coa_debit', 'bk_classifiedsetup.debitaccid', '=', 'coa_debit.id');
    
        if ($cashierAccount) {
            // If cashier account is available, hardcode credit values in SELECT
            $cashtransQuery->addSelect([
                DB::raw("'" . $cashierAccount->code . "' as debit_account_code"),
                DB::raw("'" . $cashierAccount->account_name . "' as debit_account"),
                DB::raw($cashierAccount->id . ' as debit_coaid'),
            ]);
        } else {
            // Otherwise, join normally to get credit account
            $cashtransQuery->leftJoin('chart_of_accounts as coa_debit', 'bk_classifiedsetup.debitaccid', '=', 'coa_debit.id')
                ->addSelect([
                    'coa_debit.code as credit_account_code',
                    'coa_debit.account_name as credit_account',
                    'coa_debit.id as credit_coaid',
                ]);
        }
    
        if ($startDate && $endDate) {
            $cashtransQuery->whereBetween('transdate', [$startDate, $endDate]);
        }
    
        $cashtrans = $cashtransQuery->get();
        
        foreach ($cashtrans as $transaction) {
            $student = $enrolledStudents->get($transaction->studid);
          
            if (!$student) continue;

            $student_account_je = collect($payment_account_je)->where('levelid', $student->levelid)->values();
  
            DB::table('chrngtrans')
                ->where('id', $transaction->id)
                ->update(['gl_status' => 1]);
        
            $studentName = trim("{$student->firstname} {$student->middlename} {$student->lastname}");
            $now = Carbon::now();
            $createdBy = auth()->id();
            $activeFiscalYearId = $activeFiscalYearId ?? null;
            $voucherNo = $this->generateVoucherNumberJE();
        
            DB::table('bk_generalledg')->insert([
                'voucherNo'             => $voucherNo,
                'date'                  => $transaction->transdate,
                'coaid'                 => $transaction->debit_coaid,
                'debit_amount'          => $transaction->amountpaid,
                'credit_amount'         => 0,
                'remarks'               => "$studentName - Payments",
                'createddatetime'       => $now,
                'createdby'             => $createdBy,
                'deleted'               => 0,
                'active_fiscal_year_id'=> $fiscal_yearid,
            ]);
        
            DB::table('bk_generalledg')->insert([
                'voucherNo'             => $voucherNo,
                'date'                  => $transaction->transdate,
                'coaid'                 => $student_account_je->first()->debitaccid ?? null,
                'debit_amount'          => 0,
                'credit_amount'         => $transaction->amountpaid,
                'remarks'               => "$studentName - Payments",
                'createddatetime'       => $now,
                'createdby'             => $createdBy,
                'deleted'               => 0,
                'active_fiscal_year_id'=> $fiscal_yearid,
            ]);
        }

        // CASH TRANS VOIDED
        $cashtransVoidQuery = DB::table('chrngtrans')
            ->select([
                'chrngtrans.id',
                'chrngtrans.cancelleddatetime as transdate',
                'chrngtrans.ornum',
                'chrngtrans.studid',
                'chrngtrans.studname',
                'chrngtrans.paytype',
                'chrngtrans.totalamount',
                'chrngtrans.amountpaid',
                'chrngtrans.refno',
                'chrngtrans.sid',
                'chrngtrans.accountname',
                'studinfo.firstname',
                'studinfo.lastname',
                'studinfo.middlename',
                'coa_debit.code as credit_account_code',
                'coa_debit.account_name as credit_account',
                'coa_debit.id as credit_coaid',
            ])
            ->distinct()
            ->where('chrngtrans.gl_status', 1)
            ->where('chrngtrans.cancelled', 1)
            ->join('studinfo', 'chrngtrans.studid', '=', 'studinfo.id')
            ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
            ->leftJoin('bk_classifiedsetup', 'studinfo.levelid', '=', 'bk_classifiedsetup.levelid')
            ->leftJoin('chart_of_accounts as coa_debit', 'bk_classifiedsetup.debitaccid', '=', 'coa_debit.id');
    
        if ($cashierAccount) {
            $cashtransVoidQuery->addSelect([
                DB::raw("'" . $cashierAccount->code . "' as debit_account_code"),
                DB::raw("'" . $cashierAccount->account_name . "' as debit_account"),
                DB::raw($cashierAccount->id . ' as debit_coaid'),
            ]);
        } else {
            // Otherwise, join normally to get credit account
            $cashtransVoidQuery->leftJoin('chart_of_accounts as coa_debit', 'bk_classifiedsetup.debitaccid', '=', 'coa_debit.id')
                ->addSelect([
                    'coa_debit.code as credit_account_code',
                    'coa_debit.account_name as credit_account',
                    'coa_debit.id as credit_coaid',
                ]);
        }
    
        if ($startDate && $endDate) {
            $cashtransVoidQuery->whereBetween('transdate', [$startDate, $endDate]);
        }
    
        $cashtransvoid = $cashtransVoidQuery->get();
   
        foreach ($cashtransvoid as $transaction) {
            $student = $enrolledStudents->get($transaction->studid);
         
            if (!$student) continue;
         
            // Check if this transaction already exists in the general ledger
            $existing = DB::table('bk_generalledg')
                ->where('transactiontype', 1)
                ->where('transactionid', $transaction->id)
                ->first();
            
            // If exists, skip to next iteration
            if ($existing) continue;
            $student_account_je = collect($payment_account_je)->where('levelid', $student->levelid)->values();
           
            $studentName = trim("{$student->firstname} {$student->middlename} {$student->lastname}");
            $now = Carbon::now();
            $createdBy = auth()->id();
            $activeFiscalYearId = $activeFiscalYearId ?? null;
            $voucherNo = $this->generateVoucherNumberJE();
        
            DB::table('bk_generalledg')->insert([
                'voucherNo'             => $voucherNo,
                'date'                  => $transaction->transdate,
                'coaid'                 => $student_account_je->first()->debitaccid ?? null,
                'debit_amount'          => $transaction->amountpaid,
                'credit_amount'         => 0,
                'remarks'               => "$studentName - Reversal",
                'createddatetime'       => $now,
                'createdby'             => $createdBy,
                'deleted'               => 0,
                'active_fiscal_year_id'=> $fiscal_yearid,
                'transactionid' => $transaction->id,
                'transactiontype' => 1,
            ]);
        
            DB::table('bk_generalledg')->insert([
                'voucherNo'             => $voucherNo,
                'date'                  => $transaction->transdate,
                'coaid'                 => $transaction->debit_coaid,
                'debit_amount'          => 0,
                'credit_amount'         => $transaction->amountpaid,
                'remarks'               => "$studentName - Reversal",
                'createddatetime'       => $now,
                'createdby'             => $createdBy,
                'deleted'               => 0,
                'active_fiscal_year_id'=> $fiscal_yearid,
                'transactionid' => $transaction->id,
                'transactiontype' => 1,
            ]);
        }

    
        // ADJUSTMENTS
        $d_debitAccounts = null;
        $d_creditAccounts = null;
        $c_debitAccounts = null;
        $c_creditAccounts = null;
    
        // DEBITS
        // charts of accounts
        $d_coa_adj_debit   = DB::table('chart_of_accounts')->where('d_adjustmentje_debactive', 1)->where('deleted', 0)->first();
        $d_coa_adj_credit  = DB::table('chart_of_accounts')->where('d_adjustmentje_credactive', 1)->where('deleted', 0)->first();
        // sub charts of accounts
        $d_subcoa_adj_debit  = DB::table('bk_sub_chart_of_accounts')->where('d_adjustmentje_debactive', 1)->where('deleted', 0)->first();
        $d_subcoa_adj_credit = DB::table('bk_sub_chart_of_accounts')->where('d_adjustmentje_credactive', 1)->where('deleted', 0)->first();
        
        $d_debitAccounts = $d_coa_adj_debit ?: $d_subcoa_adj_debit;
        $d_creditAccounts = $d_coa_adj_credit ?: $d_subcoa_adj_credit;
    
        // CREDITS
        // charts of accounts
        $c_coa_adj_debit = DB::table('chart_of_accounts')->where('c_adjustmentje_debactive', 1)->where('deleted', 0)->first();
        $c_coa_adj_credit = DB::table('chart_of_accounts')->where('c_adjustmentje_credactive', 1)->where('deleted', 0)->first();
        // sub charts of accounts
        $c_subcoa_adj_debit = DB::table('bk_sub_chart_of_accounts')->where('c_adjustmentje_debactive', 1)->where('deleted', 0)->first();
        $c_subcoa_adj_credit = DB::table('bk_sub_chart_of_accounts')->where('c_adjustmentje_credactive', 1)->where('deleted', 0)->first();
        
        $c_debitAccounts = $c_coa_adj_debit ?: $c_subcoa_adj_debit;
        $c_creditAccounts = $c_coa_adj_credit ?: $c_subcoa_adj_credit;
        
        // Additional Other Setup
        $debit_account_je = DB::table('bk_debit_adjustment')->where('deleted', 0)->get();
        $credit_account_je = DB::table('bk_credit_adjustment')->where('deleted', 0)->get();
    
        $adjustmentsQuery = DB::table('adjustments')
            ->select([
                'adjustments.id', 
                'adjustments.refnum as voucherNo', 
                'adjustments.description as remarks', 
                'adjustments.createddatetime as transdate', 
                'adjustments.amount', 
                'adjustments.isdebit', 
                'adjustments.iscredit',
                'adjustmentdetails.studid',
                'studinfo.firstname',
                'studinfo.lastname',
                'studinfo.middlename',
            ])
            ->distinct()
            ->where('adjustments.gl_status', 0)
            ->where('adjustments.deleted', 0)
            ->where('adjustmentdetails.deleted', 0)
            ->join('adjustmentdetails', 'adjustmentdetails.headerid', '=', 'adjustments.id')
            ->join('studinfo', 'adjustmentdetails.studid', '=', 'studinfo.id')
            ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id');
            
        if ($startDate && $endDate) {
            $adjustmentsQuery->whereBetween('adjustments.createddatetime', [$startDate, $endDate]);
        }
    
        $adjustmentsQuery = $adjustmentsQuery->get();
  
        foreach ($adjustmentsQuery as $transaction) {
            $adjustmentType = $transaction->iscredit == 1 ? 'Credit' : ($transaction->isdebit == 1 ? 'Debit' : '');

            $student = $enrolledStudents->get($transaction->studid);
           
            if (!$student) continue;

            $student_debit_account_je = collect($debit_account_je)->where('levelid', $student->levelid)->values();
            $student_credit_account_je = collect($credit_account_je)->where('levelid', $student->levelid)->values();
         
            DB::table('adjustments')
                ->where('id', $transaction->id)
                ->update(['gl_status' => 1]);
            
            $studentName = trim("{$student->firstname} {$student->middlename} {$student->lastname}");
            $now = Carbon::now();
            $createdBy = auth()->id();
            $activeFiscalYearId = $activeFiscalYearId ?? null;
            
            if ($transaction->iscredit == 1) {
                $voucherNo = $this->generateVoucherNumberJE();
                
                DB::table('bk_generalledg')->insert([
                    'voucherNo'             => $voucherNo,
                    'date'                  => $transaction->transdate,
                    'coaid'                 => $student_credit_account_je->first()->cred_adj_debitaccid ?? null,
                    'debit_amount'          => $transaction->amount,
                    'credit_amount'         => 0,
                    'remarks'               => "$studentName - $adjustmentType ADJUSTMENT",
                    'createddatetime'       => $now,
                    'createdby'             => $createdBy,
                    'deleted'               => 0,
                    'active_fiscal_year_id' => $fiscal_yearid,
                    'sub'                   => null,
                ]);
            
                DB::table('bk_generalledg')->insert([
                    'voucherNo'             => $voucherNo,
                    'date'                  => $transaction->transdate,
                    'coaid'                 => $student_credit_account_je->first()->cred_adj_creditaccid ?? null,
                    'debit_amount'          => 0,
                    'credit_amount'         => $transaction->amount,
                    'remarks'               => "$studentName - $adjustmentType ADJUSTMENT",
                    'createddatetime'       => $now,
                    'createdby'             => $createdBy,
                    'deleted'               => 0,
                    'active_fiscal_year_id'=> $fiscal_yearid,
                    'sub'                   => null,
                ]);
            } elseif ($transaction->isdebit == 1) {
                $voucherNo = $this->generateVoucherNumberJE();
                
                DB::table('bk_generalledg')->insert([
                    'voucherNo'             => $voucherNo,
                    'date'                  => $transaction->transdate,
                    'coaid'                 => $student_debit_account_je->first()->deb_adj_debitaccid ?? null,
                    'debit_amount'          => $transaction->amount,
                    'credit_amount'         => 0,
                    'remarks'               => "$studentName - $adjustmentType ADJUSTMENT",
                    'createddatetime'       => $now,
                    'createdby'             => $createdBy,
                    'deleted'               => 0,
                    'active_fiscal_year_id' => $fiscal_yearid,
                    'sub'                   => null,
                ]);
            
                DB::table('bk_generalledg')->insert([
                    'voucherNo'             => $voucherNo,
                    'date'                  => $transaction->transdate,
                    'coaid'                 => $student_debit_account_je->first()->deb_adj_creditaccid ?? null,
                    'debit_amount'          => 0,
                    'credit_amount'         => $transaction->amount,
                    'remarks'               => "$studentName - $adjustmentType ADJUSTMENT",
                    'createddatetime'       => $now,
                    'createdby'             => $createdBy,
                    'deleted'               => 0,
                    'active_fiscal_year_id' => $fiscal_yearid,
                    'sub'                   => null,
                ]);
            }
        }

        // ADJUSTMENT VOID

        $adjustmentsVoidQuery = DB::table('adjustments')
            ->select([
                'adjustments.id', 
                'adjustments.refnum as voucherNo', 
                'adjustments.description as remarks', 
                'adjustments.createddatetime as transdate', 
                'adjustments.amount', 
                'adjustments.isdebit', 
                'adjustments.iscredit',
                'adjustments.deleted',
                'adjustmentdetails.studid',
                'studinfo.firstname',
                'studinfo.lastname',
                'studinfo.middlename',
            ])
            ->distinct()
            ->where('adjustments.gl_status', 1)
            ->where('adjustments.deleted', 1)
            ->where('adjustmentdetails.deleted', 1)
            ->leftJoin('adjustmentdetails', 'adjustmentdetails.headerid', '=', 'adjustments.id')
            ->leftJoin('studinfo', 'adjustmentdetails.studid', '=', 'studinfo.id')
            ->leftJoin('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id');
            
        if ($startDate && $endDate) {
            $adjustmentsVoidQuery->whereBetween('adjustments.createddatetime', [$startDate, $endDate]);
        }
    
        $adjustmentsVoidQuery = $adjustmentsVoidQuery->get();
    
        foreach ($adjustmentsVoidQuery as $transaction) {
            $adjustmentType = $transaction->iscredit == 1 ? 'Credit' : ($transaction->isdebit == 1 ? 'Debit' : '');
            $student = $enrolledStudents->get($transaction->studid);
          
            if (!$student) continue;

            $student_debit_account_je = collect($debit_account_je)->where('levelid', $student->levelid)->values();
            $student_credit_account_je = collect($credit_account_je)->where('levelid', $student->levelid)->values();
         
            // Check if this transaction already exists in the general ledger
            $existing = DB::table('bk_generalledg')
                ->where('transactiontype', 2)
                ->where('transactionid', $transaction->id)
                ->first();

            // If exists, skip to next iteration
            if ($existing) continue;
            
            $studentName = trim("{$student->firstname} {$student->middlename} {$student->lastname}");
            $now = Carbon::now();
            $createdBy = auth()->id();
            $activeFiscalYearId = $activeFiscalYearId ?? null;
            
            if ($transaction->iscredit == 1) {
                $voucherNo = $this->generateVoucherNumberJE();
                
                DB::table('bk_generalledg')->insert([
                    'voucherNo'             => $voucherNo,
                    'date'                  => $transaction->transdate,
                    'coaid'                 => $$student_credit_account_je->first()->deb_adj_creditaccid ?? null,
                    'debit_amount'          => $transaction->amount,
                    'credit_amount'         => 0,
                    'remarks'               => "$studentName - $adjustmentType Reversal",
                    'createddatetime'       => $now,
                    'createdby'             => $createdBy,
                    'deleted'               => 0,
                    'active_fiscal_year_id' => $fiscal_yearid,
                    'sub'                   => null,
                    'transactionid' => $transaction->id,
                    'transactiontype' => 2,
                ]);
            
                DB::table('bk_generalledg')->insert([
                    'voucherNo'             => $voucherNo,
                    'date'                  => $transaction->transdate,
                    'coaid'                 => $student_credit_account_je->first()->deb_adj_debitaccid ?? null,
                    'debit_amount'          => 0,
                    'credit_amount'         => $transaction->amount,
                    'remarks'               => "$studentName - $adjustmentType Reversal",
                    'createddatetime'       => $now,
                    'createdby'             => $createdBy,
                    'deleted'               => 0,
                    'active_fiscal_year_id'=> $fiscal_yearid,
                    'sub'                   => null,
                    'transactionid' => $transaction->id,
                    'transactiontype' => 2,
                ]);
            } elseif ($transaction->isdebit == 1) {
                $voucherNo = $this->generateVoucherNumberJE();
                
                DB::table('bk_generalledg')->insert([
                    'voucherNo'             => $voucherNo,
                    'date'                  => $transaction->transdate,
                    'coaid'                 => $student_debit_account_je->first()->deb_adj_creditaccid ?? null,
                    'debit_amount'          => $transaction->amount,
                    'credit_amount'         => 0,
                    'remarks'               => "$studentName - $adjustmentType Reversal",
                    'createddatetime'       => $now,
                    'createdby'             => $createdBy,
                    'deleted'               => 0,
                    'active_fiscal_year_id'=> $fiscal_yearid,
                    'sub'                   =>  null,
                    'transactionid' => $transaction->id,
                    'transactiontype' => 2,
                ]);
            
                DB::table('bk_generalledg')->insert([
                    'voucherNo'             => $voucherNo,
                    'date'                  => $transaction->transdate,
                    'coaid'                 => $student_debit_account_je->first()->deb_adj_debitaccid ?? null,
                    'debit_amount'          => 0,
                    'credit_amount'         => $transaction->amount,
                    'remarks'               => "$studentName - $adjustmentType Reversal",
                    'createddatetime'       => $now,
                    'createdby'             => $createdBy,
                    'deleted'               => 0,
                    'active_fiscal_year_id'=> $fiscal_yearid,
                    'sub'                   => null,
                    'transactionid' => $transaction->id,
                    'transactiontype' => 2,
                ]);
            }
        }


    
        // DISCOUNTS
        $debitDiscounts = null;
        $creditDiscounts = null;
    
        $coa_disc_debit   = DB::table('chart_of_accounts')->where('discountje_debit_isctive', 1)->where('deleted', 0)->first();
        $coa_disc_credit  = DB::table('chart_of_accounts')->where('discountje_credit_isctive', 1)->where('deleted', 0)->first();
        $sub_coa_disc_debit   = DB::table('bk_sub_chart_of_accounts')->where('discountje_debit_isctive', 1)->where('deleted', 0)->first();
        $sub_coa_disc_credit  = DB::table('bk_sub_chart_of_accounts')->where('discountje_credit_isctive', 1)->where('deleted', 0)->first();
    
        $debitDiscounts = $coa_disc_debit ?: $sub_coa_disc_debit;
        $creditDiscounts = $coa_disc_credit ?: $sub_coa_disc_credit;

        $discount_je = DB::table('bk_discount_setup')->where('deleted', 0)->get();
    
        // return $credit_discount_je;
        
        $discountQuery = DB::table('studdiscounts')
            ->select([
                'studdiscounts.id', 
                'studdiscounts.createddatetime as transdate', 
                'studdiscounts.discamount as amount', 
                'studdiscounts.studid',
                'studinfo.firstname',
                'studinfo.lastname',
                'studinfo.middlename',
            ])
            ->distinct()
            ->where('studdiscounts.gl_status', 0)
            ->where('studdiscounts.deleted', 0)
            ->join('studinfo', 'studdiscounts.studid', '=', 'studinfo.id')
            ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id');
            
        if ($startDate && $endDate) {
            $discountQuery->whereBetween('studdiscounts.createddatetime', [$startDate, $endDate]);
        }
    
        $discountQuery = $discountQuery->get();
        
        foreach ($discountQuery as $transaction) {
            $student = $enrolledStudents->get($transaction->studid);
            
            if (!$student) continue;
           
            $student_account_je = collect($discount_je)->where('levelid', $student->levelid)->values();
           
            DB::table('studdiscounts')
                ->where('id', $transaction->id)
                ->update(['gl_status' => 1]);
        
            $studentName = trim("{$student->firstname} {$student->middlename} {$student->lastname}");
            $now = Carbon::now();
            $createdBy = auth()->id();
            $activeFiscalYearId = $activeFiscalYearId ?? null;
            $voucherNo = $this->generateVoucherNumberJE();
    
            DB::table('bk_generalledg')->insert([
                'voucherNo'             => $voucherNo,
                'date'                  => $transaction->transdate,
                'coaid'                 => $student_account_je->first()->debitaccid ?? null,
                'debit_amount'          => $transaction->amount,
                'credit_amount'         => 0,
                'remarks'               => "$studentName - DISCOUNT",
                'createddatetime'       => $now,
                'createdby'             => $createdBy,
                'deleted'               => 0,
                'active_fiscal_year_id' => $fiscal_yearid,
                'sub'                   => null,
            ]);
        
            DB::table('bk_generalledg')->insert([
                'voucherNo'             => $voucherNo,
                'date'                  => $transaction->transdate,
                'coaid'                 => $student_account_je->first()->creditaccid ?? null,
                'debit_amount'          => 0,
                'credit_amount'         => $transaction->amount,
                'remarks'               => "$studentName - DISCOUNT",
                'createddatetime'       => $now,
                'createdby'             => $createdBy,
                'deleted'               => 0,
                'active_fiscal_year_id' => $fiscal_yearid,
                'sub'                   => null,
            ]);
        }

        // DISCOUNT VOID
        $discountVoidQuery = DB::table('studdiscounts')
            ->select([
                'studdiscounts.id', 
                'studdiscounts.createddatetime as transdate', 
                'studdiscounts.discamount as amount', 
                'studdiscounts.studid',
                'studinfo.firstname',
                'studinfo.lastname',
                'studinfo.middlename',
            ])
            ->distinct()
            ->where('studdiscounts.gl_status', 1)
            ->where('studdiscounts.deleted', 1)
            ->join('studinfo', 'studdiscounts.studid', '=', 'studinfo.id')
            ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id');
            
        if ($startDate && $endDate) {
            $discountVoidQuery->whereBetween('studdiscounts.createddatetime', [$startDate, $endDate]);
        }
    
        $discountVoidQuery = $discountVoidQuery->get();
        
        foreach ($discountVoidQuery as $transaction) {
            $student = $enrolledStudents->get($transaction->studid);

            if (!$student) continue;

            $student_account_je = collect($discount_je)->where('levelid', $student->levelid)->values();
            
            // Check if this transaction already exists in the general ledger
            $existing = DB::table('bk_generalledg')
                ->where('transactiontype', 3)
                ->where('transactionid', $transaction->id)
                ->first();

            // If exists, skip to next iteration
            if ($existing) continue;
        
            $studentName = trim("{$student->firstname} {$student->middlename} {$student->lastname}");
            $now = Carbon::now();
            $createdBy = auth()->id();
            $activeFiscalYearId = $activeFiscalYearId ?? null;
            $voucherNo = $this->generateVoucherNumberJE();
    
            DB::table('bk_generalledg')->insert([
                'voucherNo'             => $voucherNo,
                'date'                  => $transaction->transdate,
                'coaid'                 => $student_account_je->first()->creditaccid ?? null,
                'debit_amount'          => $transaction->amount,
                'credit_amount'         => 0,
                'remarks'               => "$studentName - DISCOUNT Reversal",
                'createddatetime'       => $now,
                'createdby'             => $createdBy,
                'deleted'               => 0,
                'active_fiscal_year_id' => $fiscal_yearid,
                'sub'                   => null,
                'transactionid' => $transaction->id,
                'transactiontype' => 3,
            ]);
        
            DB::table('bk_generalledg')->insert([
                'voucherNo'             => $voucherNo,
                'date'                  => $transaction->transdate,
                'coaid'                 => $student_account_je->first()->debitaccid ?? null,
                'debit_amount'          => 0,
                'credit_amount'         => $transaction->amount,
                'remarks'               => "$studentName - DISCOUNT Reversal",
                'createddatetime'       => $now,
                'createdby'             => $createdBy,
                'deleted'               => 0,
                'active_fiscal_year_id' => $fiscal_yearid,
                'sub'                   => null,
                'transactionid' => $transaction->id,
                'transactiontype' => 3,
            ]);
        }
    
        return array((object)[
            'status' => 1,
            'message' => 'Sync Successfully',
        ]);
        
    }

    
    public function generateVoucherNumber()
    {
        $lastVoucher = DB::table('bk_generalledg')
            ->where('voucherNo', 'like', 'JV - %')
            ->orderByDesc('id')
            ->first();

        if ($lastVoucher) {
            preg_match('/\d+$/', $lastVoucher->voucherNo, $matches);
            $lastNumber = isset($matches[0]) ? (int) $matches[0] : 0;
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        $newVoucher = 'JV - ' . $newNumber;

        return response()->json(['voucher_no' => $newVoucher]);
    }

    public function printGeneralLedger(Request $request)
    {
        $startDate = null;
        $endDate = null;
        $fiscalYearId = $request->input('fiscal_year');
        $dateRange = $request->input('date_range');
        
        // Date range handling (same as before)
        if ($dateRange) {
            [$startDate, $endDate] = explode(' - ', $dateRange);
            try {
                $startDate = \Carbon\Carbon::createFromFormat('Y-m-d', trim($startDate))->startOfDay();
                $endDate = \Carbon\Carbon::createFromFormat('Y-m-d', trim($endDate))->endOfDay();
            } catch (\Exception $e) {
                return back()->with('error', 'Invalid date format.');
            }
        } elseif ($fiscalYearId) {
            $fiscalYear = DB::table('bk_fiscal_year')
                ->where('id', $fiscalYearId)
                ->where('isactive', 1)
                ->where('deleted', 0)
                ->first();
        
            if (!$fiscalYear) {
                return back()->with('error', 'Invalid or inactive fiscal year.');
            }
        
            $startDate = \Carbon\Carbon::parse($fiscalYear->stime)->startOfDay();
            $endDate = \Carbon\Carbon::parse($fiscalYear->etime)->endOfDay();
        } else {
            // Default to current month
            $startDate = \Carbon\Carbon::now()->startOfMonth()->startOfDay();
            $endDate = \Carbon\Carbon::now()->endOfMonth()->endOfDay();
        }

        // Paginated query
        $generalLedger = DB::table('bk_generalledg')
            ->leftJoin('chart_of_accounts', 'bk_generalledg.coaid', '=', 'chart_of_accounts.id')
            ->leftJoin('bk_sub_chart_of_accounts', 'bk_generalledg.coaid', '=', 'bk_sub_chart_of_accounts.id')
            ->select(
                'bk_generalledg.voucherNo',
                'bk_generalledg.date',
                'bk_generalledg.coaid',
                'bk_generalledg.debit_amount',
                'bk_generalledg.credit_amount',
                'bk_generalledg.remarks',
                'bk_generalledg.sub',
                DB::raw("CASE 
                    WHEN bk_generalledg.sub = 0 OR bk_generalledg.sub IS NULL THEN chart_of_accounts.account_name 
                    ELSE bk_sub_chart_of_accounts.sub_account_name 
                    END AS account_name"),
                DB::raw("CASE 
                    WHEN bk_generalledg.sub = 0 OR bk_generalledg.sub IS NULL THEN chart_of_accounts.code 
                    ELSE bk_sub_chart_of_accounts.sub_code 
                    END AS code")
                )
            ->where('bk_generalledg.deleted', 0)
            ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                $q->whereBetween('bk_generalledg.date', [$startDate, $endDate]);
            })
            ->orderBy('bk_generalledg.date', 'desc')
            ->get(); // Adjust per page count as needed

        $schoolinfo = DB::table('schoolinfo')->first();
        $signatories = DB::table('signatory')
            ->join('bk_signatory_grp', 'signatory.signatory_grp_id', '=', 'bk_signatory_grp.id')
            ->where('signatory.signatory_grp_id', 2)
            ->select('signatory.*')
            ->get();

        return view('bookkeeper.pages.printables.GeneralLedger_PDF', compact(
            'generalLedger',
            'schoolinfo',
            'signatories',
            'startDate',
            'endDate',
            'fiscalYearId'
        ));
    }
}
