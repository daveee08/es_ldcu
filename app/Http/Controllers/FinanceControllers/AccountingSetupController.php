<?php

namespace App\Http\Controllers\FinanceControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\FinanceModel;
use PDF;
use Dompdf\Dompdf;
use Session;
use Auth;
use Hash;

class AccountingSetupController extends Controller
{
    public function accsetup(Request $request)
    {
        return view('finance.accounting.accsetup');
    }

    public function accsetup_loadlevel(Request $request)
    {
        $query = DB::table('gradelevel')
            ->select(DB::raw('gradelevel.id, levelname, coa1.code as code1, coa1.account as account1, coa2.code as code2, coa2.account as account2, coa3.code as code3, coa3.account as account3'))
            ->leftJoin('acc_coa as coa1', 'gradelevel.gl_1', '=', 'coa1.id')
            ->leftJoin('acc_coa as coa2', 'gradelevel.gl_2', '=', 'coa2.id')
            ->leftJoin('acc_coa as coa3', 'gradelevel.gl_3', '=', 'coa3.id')
            ->where('gradelevel.deleted', 0);

        // Apply filtering if a grade level is selected
        if ($request->filled('gradelevel')) {
            $query->where('gradelevel.id', $request->gradelevel);
        }

        $levels = $query->orderBy('sortid', 'ASC')->get();

        return response()->json($levels);
    }

    public function accsetup_getlevel(Request $request)
    {
        $levelid = $request->get('levelid');

        $level = DB::table('gradelevel')
            ->where('id', $levelid)
            ->first();

        $levelname = $level->levelname;
        $gl_1 = $level->gl_1;
        $gl_2 = $level->gl_2;
        $gl_3 = $level->gl_3;
        $gl_credit = $level->gl_credit;

        $data = array(
            'levelname' => $levelname,
            'gl_1' => $gl_1,
            'gl_2' => $gl_2,
            'gl_3' => $gl_3,
            'gl_credit' => $gl_credit
        );

        return json_encode($data);
    }

    public function accsetup_update(Request $request)
    {
        $dataid = $request->get('dataid');
        $gl_1 = $request->get('gl_1');
        $gl_2 = $request->get('gl_2');
        $gl_3 = $request->get('gl_3');
        $gl_credit = $request->get('gl_credit');

        // return $dataid;

        DB::table('gradelevel')
            ->where('id', $dataid)
            ->update([
                'gl_1' => $gl_1,
                'gl_2' => $gl_2,
                'gl_3' => $gl_3,
                'gl_credit' => $gl_credit
            ]);
    }

    public function accsetup_oaload()
    {
        $glid = DB::table('balforwardsetup')
            ->first()->glid;

        return $glid;
    }

    public function accsetup_oaupdate(Request $request)
    {
        $glid = $request->get('glid');

        DB::table('balforwardsetup')
            ->update([
                'glid' => $glid
            ]);
    }

    public function issetup_add(Request $request)
    {
        $dataid = $request->get('dataid');
        $type = $request->get('type');
        $mapid = $request->get('mapid');
        $sortid = $request->get('sortid');
        $header = $request->get('header');
        $title = $request->get('title');

        if ($dataid == 0) {
            // $check = DB::table('acc_reportsetup_is')
            //     ->where('deleted', 0)
            //     ->where('glid', $glid)
            //     ->where('rpttype', $type)
            //     ->get();

            // if(count($check) > 0)
            // {
            //     return 'exist';
            // }

            DB::table('acc_reportsetup_is')
                ->insert([
                    'mapid' => $mapid,
                    'rpttype' => $type,
                    'description' => $title,
                    'header' => $header,
                    'sortid' => $sortid,
                    'createddatetime' => FinanceModel::getServerDateTime(),
                    'createdby' => auth()->user()->id
                ]);

            return 'done';
        } else {
            if ($header == 0) {
                $check = DB::table('acc_reportsetup_is')
                    ->where('deleted', 0)
                    ->where('mapid', $mapid)
                    ->where('rpttype', $type)
                    ->where('id', '!=', $dataid)
                    ->get();
            } else {
                $check = DB::table('acc_reportsetup_is')
                    ->where('deleted', 0)
                    ->where('description', $title)
                    ->where('rpttype', $type)
                    ->where('id', '!=', $dataid)
                    ->get();
            }

            if (count($check) > 0) {
                return 'exist';
            } else {
                DB::table('acc_reportsetup_is')
                    ->where('id', $dataid)
                    ->update([
                        'mapid' => $mapid,
                        'sortid' => $sortid,
                        'header' => $header,
                        'description' => $title,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => FinanceModel::getServerDateTime()
                    ]);

                return 'done';
            }
        }
    }

    public function issetup_load()
    {
        $is = DB::table('acc_reportsetup_is')
            ->select(db::raw('acc_reportsetup_is.id, mapname, rpttype, description as title, header'))
            ->leftjoin('acc_map', 'acc_reportsetup_is.mapid', '=', 'acc_map.id')
            ->where('acc_reportsetup_is.deleted', 0)
            ->orderBy('sortid')
            ->get();

        return $is;
    }

    public function issetup_read(Request $request)
    {
        $dataid = $request->get('dataid');

        $is = DB::table('acc_reportsetup_is')
            ->where('id', $dataid)
            ->first();

        return $is->glid;

    }

    public function issetup_delete(Request $request)
    {
        $dataid = $request->get('dataid');

        DB::table('acc_reportsetup_is')
            ->where('id', $dataid)
            ->update([
                'deleted' => 1,
                'deleteddatetime' => FinanceModel::getServerDateTime(),
                'deletedby' => auth()->user()->id
            ]);

        return 'done';

    }

    public function accsetup_cashiertrans(Request $request)
    {
        $glid = $request->get('glid');
        $transtype = $request->get('transtype');

        $trans = DB::table('acc_transsetup')
            ->where('transname', $transtype)
            ->where('deleted', 0)
            ->first();

        if ($trans) {
            DB::table('acc_transsetup')
                ->where('id', $trans->id)
                ->update([
                    'glid' => $glid,
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => FinanceModel::getServerDateTime()
                ]);
        } else {
            DB::table('acc_transsetup')
                ->insert([
                    'glid' => $glid,
                    'transname' => $transtype
                ]);
        }


    }

    public function accsetup_cashiertrans_load(Request $request)
    {
        $trans = DB::table('acc_transsetup')
            ->where('transname', 'cashier')
            ->where('deleted', 0)
            ->where('transname', 'cashier')
            ->first();

        $glid = 0;

        if ($trans) {
            $glid = $trans->glid;
        }

        return $glid;
    }

    public function issetup_revenue_read(Request $request)
    {
        $dataid = $request->get('dataid');

        $issetup = DB::table('acc_reportsetup_is')
            ->where('id', $dataid)
            ->first();

        $data = array(
            'rpttype' => $issetup->rpttype,
            'mapid' => $issetup->mapid,
            'title' => $issetup->description,
            'header' => $issetup->header,
            'sortid' => $issetup->sortid
        );

        return $data;
    }

    public function bssetup_load()
    {
        $is = DB::table('acc_reportsetup_bs')
            ->select(db::raw('acc_reportsetup_bs.id, mapname, rpttype, description as title, header'))
            ->leftjoin('acc_map', 'acc_reportsetup_bs.mapid', '=', 'acc_map.id')
            ->where('acc_reportsetup_bs.deleted', 0)
            ->orderBy('sortid')
            ->get();

        return $is;
    }

    public function bssetup_add(Request $request)
    {
        $dataid = $request->get('dataid');
        $type = $request->get('type');
        $mapid = $request->get('mapid');
        $sortid = $request->get('sortid');
        $header = $request->get('header');
        $title = $request->get('title');

        if ($dataid == 0) {
            // $check = DB::table('acc_reportsetup_is')
            //     ->where('deleted', 0)
            //     ->where('glid', $glid)
            //     ->where('rpttype', $type)
            //     ->get();

            // if(count($check) > 0)
            // {
            //     return 'exist';
            // }

            DB::table('acc_reportsetup_bs')
                ->insert([
                    'mapid' => $mapid,
                    'rpttype' => $type,
                    'description' => $title,
                    'header' => $header,
                    'sortid' => $sortid,
                    'createddatetime' => FinanceModel::getServerDateTime(),
                    'createdby' => auth()->user()->id
                ]);

            return 'done';
        } else {
            if ($header == 0) {
                $check = DB::table('acc_reportsetup_is')
                    ->where('deleted', 0)
                    ->where('mapid', $mapid)
                    ->where('rpttype', $type)
                    ->where('id', '!=', $dataid)
                    ->where('header', 0)
                    ->get();
            } else {
                $check = DB::table('acc_reportsetup_bs')
                    ->where('deleted', 0)
                    ->where('description', $title)
                    ->where('rpttype', $type)
                    ->where('id', '!=', $dataid)
                    ->where('header', 1)
                    ->get();
            }

            if (count($check) > 0) {
                return 'exist';
            } else {
                DB::table('acc_reportsetup_bs')
                    ->where('id', $dataid)
                    ->update([
                        'mapid' => $mapid,
                        'sortid' => $sortid,
                        'header' => $header,
                        'description' => $title,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => FinanceModel::getServerDateTime()
                    ]);

                return 'done';
            }
        }
    }

    public function bssetup_read(Request $request)
    {
        $dataid = $request->get('dataid');

        $setup = DB::table('acc_reportsetup_bs')
            ->where('id', $dataid)
            ->first();

        $data = array(
            'id' => $setup->id,
            'rpttype' => $setup->rpttype,
            'mapid' => $setup->mapid,
            'title' => $setup->description,
            'header' => $setup->header,
            'sortid' => $setup->sortid
        );

        return $data;
    }

    public function accsetup_bank_load()
    {
        $bank = DB::table('acc_bank')
            ->where('deleted', 0)
            ->orderBy('bankname')
            ->get();

        return $bank;
    }

    public function accsetup_bank_create(Request $request)
    {
        $bankname = $request->get('bankname');
        $accountno = $request->get('accountno');
        $branch = $request->get('branch');
        $address = $request->get('address');
        $id = $request->get('id');

        if ($id == 0) {
            $chck = DB::table('acc_bank')
                ->where('accountno', $accountno)
                ->first();

            if ($chck) {
                return 'exist';
            } else {
                DB::table('acc_bank')
                    ->insert([
                        'accountno' => $accountno,
                        'bankname' => $bankname,
                        'branch' => $branch,
                        'address' => $address,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => FinanceModel::getServerDateTime()
                    ]);

                return 'done';
            }
        } else {
            $chck = DB::table('acc_bank')
                ->where('accountno', $accountno)
                ->where('id', '!=', $id)
                ->first();

            if ($chck) {
                return 'exist';
            } else {
                DB::table('acc_bank')
                    ->where('id', $id)
                    ->update([
                        'accountno' => $accountno,
                        'bankname' => $bankname,
                        'branch' => $branch,
                        'address' => $address,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => FinanceModel::getServerDateTime()
                    ]);

                return 'done';
            }
        }
    }

    public function accsetup_bank_read(Request $request)
    {
        $id = $request->get('id');

        $bank = DB::table('acc_bank')
            ->where('id', $id)
            ->first();

        return collect($bank);
    }

    public function accsetup_bank_delete(Request $request)
    {
        $id = $request->get('id');

        DB::table('acc_bank')
            ->where('id', $id)
            ->update([
                'deleted' => 1,
                'deletedby' => auth()->user()->id,
                'deleteddatetime' => FinanceModel::getServerDateTime()
            ]);

        return 'done';
    }

    public function accsetup_payroll_load()
    {
        $glid = DB::table('acc_transsetup')
            ->where('transname', 'payroll')
            ->first();

        return collect($glid);
    }

    public function accsetup_payroll_save(Request $request)
    {
        $gldebit = $request->get('gldebit');
        $glcredit = $request->get('glcredit');

        DB::table('acc_transsetup')
            ->where('transname', 'payroll')
            ->update([
                'debitgl' => $gldebit,
                'creditgl' => $glcredit
            ]);
    }

    public function bssetup_delete(Request $request)
    {
        $id = $request->get('id');

        DB::table('acc_reportsetup_bs')
            ->where('id', $id)
            ->update([
                'deleted' => 1,
                'deleteddatetime' => FinanceModel::getServerDateTime(),
                'deletedby' => auth()->user()->id
            ]);
    }

    public function accsetup_expense_save(Request $request)
    {
        $gl_credit = $request->get('glcredit');

        $setup = DB::table('acc_transsetup')
            ->where('transname', 'expense')
            ->first();

        if ($setup) {
            DB::table('acc_transsetup')
                ->where('id', $setup->id)
                ->update([
                    'creditgl' => $gl_credit
                ]);
        } else {
            DB::table('acc_transsetup')
                ->insert([
                    'transname' => 'expense',
                    'creditgl' => $gl_credit
                ]);
        }
    }

    public function accsetup_expense_load()
    {
        $setup = DB::table('acc_transsetup')
            ->where('transname', 'expense')
            ->where('deleted', 0)
            ->first();

        if ($setup) {
            return $setup->creditgl;
        }
    }

    public function expenses_items_create(Request $request)
    {
        $dataid = $request->get('dataid');
        $code = $request->get('code');
        $description = $request->get('description');
        $cost = $request->get('cost');
        $classid = $request->get('classid');
        $coa = $request->get('coa');
        $qty = $request->get('qty');
        $minimum_qty = $request->get('min_qty');
        $itemType = $request->get('itemType');

        if ($itemType == 1) {

            $itemtype = 'inventory';

        } else {

            $itemtype = 'non_inventotry';

        }

        if ($dataid == 0) {
            $check = DB::table('items')
                ->where('description', $description)
                ->where('deleted', 0)
                ->first();

            if ($check) {
                return 'exist';
            } else {



                $id = DB::table('items')
                    ->insertGetID([
                        'itemcode' => $code,
                        'qty' => $qty,
                        'minimum_qty' => $minimum_qty,
                        'itemtype' => $itemtype,
                        'description' => $description,
                        'cost' => $cost,
                        'classid' => $classid,
                        'glid' => $coa,
                        'isexpense' => 1
                    ]);

                if ($itemType == 1) {

                    $remarks = 'Added to Item inventory list';

                    DB::table('stock_card')->insert([
                        'initial_onhand' => 0,
                        'onhand' => $qty ?? 0,
                        'itemid' => $id,
                        'remarks' => $remarks,
                        'transacted_by' => auth()->user()->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);


                }


                return 'done';
            }
        } else {
            $check = DB::table('items')
                ->where('description', $description)
                ->where('id', '!=', $dataid)
                ->where('deleted', 0)
                ->first();

            if ($check) {
                return 'exist';
            } else {



                if ($itemType == 1) {

                    $itemtype = 'inventory';


                    $data = DB::table('items')
                        ->where('id', $dataid)
                        ->select('qty')
                        ->first();

                    if ($data->qty != $qty) {

                        $remarks = 'Changes from Item inventory list from ' . ($data->qty ?? 0) . ' to ' . $qty;


                        DB::table('stock_card')->insert(
                            [
                                'initial_onhand' => $data->onhand ?? 0,
                                'onhand' => $qty,
                                'itemid' => $dataid,
                                'remarks' => $remarks,
                                'transacted_by' => auth()->user()->id,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]
                        );

                    }
                }

                DB::table('items')
                    ->where('id', $dataid)
                    ->update([
                        'itemcode' => $code,
                        'description' => $description,
                        'minimum_qty' => $minimum_qty,
                        'qty' => $qty,
                        'itemtype' => $itemtype,
                        'cost' => $cost,
                        'classid' => $classid,
                        'glid' => $coa
                    ]);

                return 'done';
            }
        }
    }

    public function expenses_items_read(Request $request)
    {
        $dataid = $request->get('dataid');

        $items = DB::table('items')
            ->where('id', $dataid)
            ->first();

        $data = array(
            'code' => $items->itemcode,
            'description' => $items->description,
            'cost' => $items->cost,
            'classid' => $items->classid,
            'glid' => $items->glid,
            'itemtype' => $items->itemtype,
            'qty' => $items->qty,
            'minimum_qty' => $items->minimum_qty,
        );

        return $data;
    }
    public function expenses_items_delete(Request $request)
    {
        $id = $request->get('id');

        DB::table('items')
            ->where('id', $id)
            ->update([
                'deleted' => 1,
                'deletedby' => auth()->user()->id,
                'deleteddatetime' => FinanceModel::getServerDateTime()
            ]);
            
        return 'done';
    }

    public function expenses_supplier_create(Request $request)
    {
        $company = $request->get('company');
        $address = $request->get('address');
        $contactno = $request->get('contactno');
        $glid = $request->get('glid');
        $dataid = $request->get('dataid');

        $check = DB::table('expense_company')
            ->where('companyname', $company)
            ->where('deleted', 0)
            ->count();

        if ($check == 0) {
            DB::table('expense_company')
                ->insert([
                    'companyname' => $company,
                    'address' => $address,
                    'contactno' => $contactno,
                    'glid' => $glid,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => FinanceModel::getServerDateTime()
                ]);

            return 'done';
        } else {
            return 'exist';
        }

    }

    public function expenses_supplier(Request $request)
    {
        $filter = $request->get('filter');

        $supplier = DB::table('expense_company')
            ->select('id', 'companyname', 'address', 'contactno')
            ->where('deleted', 0)
            ->where('companyname', 'like', '%' . $filter . '%')
            ->get();

        return $supplier;
    }

    public function expenses_supplier_read(Request $request)
    {
        $id = $request->get('dataid');

        $supplier = DB::table('expense_company')
            ->where('id', $id)
            ->first();

        $name = $supplier->companyname;
        $address = $supplier->address;
        $contactno = $supplier->contactno;
        $glid = $supplier->glid;

        $data = array(
            'name' => $name,
            'address' => $address,
            'contactno' => $contactno,
            'glid' => $glid
        );

        return $data;
    }

    public function expenses_supplier_update(Request $request)
    {
        $dataid = $request->get('dataid');
        $name = $request->get('company');
        $address = $request->get('address');
        $contactno = $request->get('contactno');
        $glid = $request->get('glid');

        $check = DB::table('expense_company')
            ->where('companyname', $name)
            ->where('id', '!=', $dataid)
            ->count();

        if ($check > 0) {
            return 'exist';
        } else {
            DB::table('expense_company')
                ->where('id', $dataid)
                ->update([
                    'companyname' => $name,
                    'address' => $address,
                    'contactno' => $contactno,
                    'glid' => $glid,
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => FinanceModel::getServerDateTime()
                ]);

            return 'done';
        }
    }

    public function expenses_supplier_delete(Request $request)
    {
        $id = $request->get('id');
        DB::table('expense_company')
            ->where('id', $id)
            ->update([
                'deleted' => 1,
                'deletedby' => auth()->user()->id,
                'deleteddatetime' => FinanceModel::getServerDateTime()
            ]);
    }

    public function loadclassifiedsetup(Request $request)
    {
        $academicprogram = DB::table('academicprogram')
            ->get();
        
        $classSetup = '';

        foreach($academicprogram as $acadprog)
        {
            $classSetup .='
                <tr>
                    <td colspan="5" class="text-bold">
                        '.$acadprog->progname.' <span data-id="'.$acadprog->id.'" class="classified_addsetup badge badge-primary" style="cursor: pointer">Add Setup</span>
                    </td>
                </tr>
            ';
            
            $acc_classifiedsetup = DB::table('acc_classifiedsetup')
                ->select(db::raw('acc_classifiedsetup.id, itemclassification.`description` AS classification, debit.code AS debitcode, debit.`account` AS debitaccount,	credit.code AS creditcode, credit.account AS creditaccount'))
                ->join('itemclassification', 'acc_classifiedsetup.classid', '=', 'itemclassification.id')
                ->join('acc_coa as debit', 'acc_classifiedsetup.debitaccid', '=', 'debit.id')
                ->join('acc_coa as credit', 'acc_classifiedsetup.creditaccid', '=', 'credit.id')
                ->where('acadprogid', $acadprog->id)
                ->where('acc_classifiedsetup.deleted', 0)
                ->get();
            
            foreach($acc_classifiedsetup as $detail)
            {
                $classSetup .='
                    <tr>
                        <td></td>
                        <td>'.$detail->classification.'</td>
                        <td>'.$detail->debitcode.' - '.$detail->debitaccount.'</td>
                        <td>'.$detail->creditcode.' - '.$detail->creditaccount.'</td>
                        <td class="">
                            <button class="btn btn-sm btn-danger classified_delete" data-id="'.$detail->id.'"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                ';
            }

        }
        
        return $classSetup;
    }

    public function loadclassifiedsave(Request $request)
    {
        $acadprogid = $request->get('acadprogid');
        $id = $request->get('id');
        $classid = $request->get('classid');
        $debitid = $request->get('debitid');
        $creditid = $request->get('creditid');
        $payment_debit = $request->get('payment_debit');
        $payment_credit = $request->get('payment_credit');

        if($id == 0)
        {
            $check = DB::table('acc_classifiedsetup')
                ->where('acadprogid', $acadprogid)
                ->where('classid', $classid)
                ->where('deleted', 0)
                ->first();

            if($check)
            {
                return 'exist';
            }
            else{
                DB::table('acc_classifiedsetup')
                    ->insert([
                        'acadprogid' => $acadprogid,
                        'classid' => $classid,
                        'debitaccid' => $debitid,
                        'creditaccid' => $creditid,
                        'payment_debitaccid' => $payment_debit,
                        'payment_creditaccid' => $payment_credit,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => FinanceModel::getServerDateTime()
                    ]);
            }
        }
    }

    public function loadclassifieddelete(Request $request)
    {
        $id = $request->get('id');

        DB::table('acc_classifiedsetup')
            ->where('id', $id)
            ->update([
                'deleted' => 1,
                'deletedby' => auth()->user()->id,
                'deleteddatetime' => FinanceModel::getServerDateTime()
            ]);
    }


    public function costcenter_create(Request $request)
    {
        $description = $request->get('description');
        $code = $request->get('code');
        $id = $request->get('id');

        if($id == 0)
        {
            $check = DB::table('acc_costcenters')
                ->where('deleted', 0)
                ->where('acc_code', $code)
                ->first();

            if($check)
            {
                return 'exist';
            }

            DB::table('acc_costcenters')
                ->insert([
                    'acc_code' => $code,
                    'description' => $description,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => FinanceModel::getServerDateTime()
                ]);

            return 'done';
        }
    }

    public function costcenter_read(Request $request)
    {
        return DB::table('acc_costcenters')
            ->where('deleted', 0)
            ->orderBy('acc_code')
            ->get();
    }

    // public function bookkeeper_costcenter_read(Request $request)
    // {
    //     $query = DB::table('acc_costcenters')
    //         ->where('deleted', 0);

    //     // Get total records count (before filtering)
    //     $totalRecords = $query->count();

    //     // Handle search parameter
    //     if ($request->has('search') && !empty($request->search['value'])) {
    //         $search = $request->search['value'];
    //         $query->where(function($q) use ($search) {
    //             $q->where('acc_code', 'like', '%' . $search . '%')
    //             ->orWhere('description', 'like', '%' . $search . '%');
    //         });
    //     }

    //     // Get filtered count (after searching)
    //     $filteredRecords = $query->count();

    //     // Handle ordering
    //     if ($request->has('order') && count($request->order)) {
    //         $columns = ['acc_code', 'description', 'id']; // Must match your DataTables columns
    //         $orderColumn = $columns[$request->order[0]['column']] ?? 'acc_code';
    //         $orderDirection = $request->order[0]['dir'] ?? 'asc';
    //         $query->orderBy($orderColumn, $orderDirection);
    //     } else {
    //         $query->orderBy('acc_code');
    //     }

    //     // Handle pagination
    //     if ($request->has('start') && $request->has('length')) {
    //         $query->offset($request->start)
    //             ->limit($request->length);
    //     }

    //     $data = $query->get();

    //     return response()->json([
    //         'draw' => intval($request->draw), // Cast to int for security
    //         'recordsTotal' => $totalRecords,
    //         'recordsFiltered' => $filteredRecords, // Use filtered count here
    //         'data' => $data
    //     ]);
    // }

    public function bookkeeper_costcenter_read(Request $request)
    {
        // Base query (for accurate total count)
        $baseQuery = DB::table('acc_costcenters')->where('deleted', 0)->orderBy('createddatetime', 'asc');
        $totalRecords = $baseQuery->count();

        // Cloned query for filtering, ordering, and pagination
        $query = clone $baseQuery;

        // Search
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('acc_code', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Filtered count (after search)
        $filteredRecords = $query->count();

        // Order
        if ($request->has('order') && count($request->order)) {
            $columns = ['description', 'acc_code', 'id']; // match JS order
            $orderColumnIndex = $request->order[0]['column'];
            $orderColumn = $columns[$orderColumnIndex] ?? 'description';
            $orderDirection = $request->order[0]['dir'] ?? 'asc';
            $query->orderBy($orderColumn, $orderDirection);
        } else {
            $query->orderBy('description');
        }

        // Pagination
        if ($request->has('start') && $request->has('length')) {
            $query->offset($request->start)->limit($request->length);
        }

        // Select only needed columns
        $data = $query->select('id', 'acc_code', 'description')->get();

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ]);
    }


    public function costcenter_edit(Request $request)
    {
        $id = $request->get('id');

        $data = DB::table('acc_costcenters')
            ->where('id', $id)
            ->where('deleted', 0)
            ->first();

        return response()->json($data);
    }

    public function costcenter_update(Request $request)
    {
        $description = $request->get('description');
        $code = $request->get('code');
        $id = $request->get('id');
        
        $check = DB::table('acc_costcenters')
            ->where('deleted', 0)
            ->where('id', $id)
            ->first();
       
        if($check)
        {
            DB::table('acc_costcenters')
                ->where('id', $id)
                ->where('deleted', 0)
                ->update([
                    'acc_code' => $code,
                    'description' => $description,
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => FinanceModel::getServerDateTime()
                ]);
        }

        return array(
            (object) [
                'status' => 1,
                'message' => 'Updated Successfully!',
            ]
        );
    }

    public function costcenter_delete(Request $request)
    {
        $id = $request->get('id');

        DB::table('acc_costcenters')
            ->where('id', $id)
            ->update([
                'deleted' => 1,
                'deletedby' => auth()->user()->id,
                'deleteddatetime' => FinanceModel::getServerDateTime()
            ]);

        
        return array(
            (object) [
                'status' => 1,
                'message' => 'Deleted Successfully!',
            ]
        );
    }


}
