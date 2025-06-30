<?php

namespace App\Http\Controllers\FinanceControllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\FinanceModel;
use App\DisplayModel;
use App\Models\Finance\FinanceUtilityModel;
use DB;
use NumConvert;
use PDF;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
// use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;


class DiscountController extends Controller
{
    public function discounts()
    {
        return view('finance/discount_v2');
    }

    public function discount_setup()
    {
        $setup = db::table('discounts')
            ->where('deleted', 0)
            ->get();

        return $setup;
    }

    public function discount_setup_create(Request $request)
    {
        $particulars = $request->get('particulars');
        $amount = $request->get('amount');
        $percent = $request->get('percent');
        $applied_to_discount_ammount = $request->get('applied_to_discount_ammount');
        $dataid = $request->get('dataid');

        $check = db::table('discounts')
            ->where('particulars', $particulars)
			->where('deleted', 0)
            ->first();

        if($check)
        {
            return 'exist';
        }
        else{
            db::table('discounts')
                ->insert([
                    'particulars' => $particulars,
                    'amount' => $amount,
                    'percent' => $percent,
                    // 'isapply_disc_amount' => $applied_to_discount_ammount,
                    'deleted' => 0,
                    'createddatetime' => FinanceModel::getServerDateTime(),
                    'createdby' => auth()->user()->id
                ]);

            return 'done';
        }
    }

    public function discount_setup_read(Request $request)
    {
        $dataid = $request->get('dataid');

        $setup = db::table('discounts')
            ->where('id', $dataid)
            ->first();

        if($setup)
        {
            return collect($setup);
        }
    }

    public function discount_setup_update(Request $request)
    {
        $dataid = $request->get('dataid');
        $particulars = $request->get('particulars');
        $amount = $request->get('amount');
        $percent = $request->get('percent');

        $check = db::table('discounts')
            ->where('particulars', $particulars)
            ->where('id', '!=', $dataid)
            ->first();

        if($check)
        {
            return 'exist';
        }
        else{
            db::table('discounts')
                ->where('id', $dataid)
                ->update([
                    'particulars' => $particulars,
                    'amount' => $amount,
                    'percent' => $percent,
                    'updateddatetime' => FinanceModel::getServerDateTime(),
                    'updatedby' => auth()->user()->id
                ]);

            return 'done';
        }

    }

    public function discount_setup_delete(Request $request)
    {
        $dataid = $request->get('dataid');

        $checkdiscount = db::table('studdiscounts')
            ->where('discountid', $dataid)
            ->where('deleted', 0)
            ->first();


        if($checkdiscount)
        {
            return 'exist';
        }
        else{
            db::table('discounts')
                ->where('id', $dataid)
                ->update([
                    'deleted' => 1,
                    'deleteddatetime' => FinanceModel::getServerDateTime(),
                    'deletedby' => auth()->user()->id
                ]);

            return 'done';
        }
    }

    public function discount_getstudents(Request $request)
    {
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $studarray = array();

        $enrolledstud = db::table('enrolledstud')
            ->select(db::raw('CONCAT(lastname, ", ", firstname, " - ", levelname) AS text, studid as id'))
            ->join('studinfo', 'enrolledstud.studid', '=', 'studinfo.id')
            ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
            ->where('enrolledstud.deleted', 0)
            ->where('enrolledstud.studstatus', '>', 0)
            ->where('syid', $syid)
            ->where(function($q) use($semid){
                if($semid == 3)
                {
                    $q->where('ghssemid', 3);
                }
                else{
                    $q->where('ghssemid', '!=', 3);
                }
            })
            ->get();

        // foreach($enrolledstud as $stud)
        // {
        //     array_push($studarray, (object)[
        //         'text' => $stud->text,
        //         'id' => $stud->id
        //     ]);
        // }

        $sh_enrolledstud = db::table('sh_enrolledstud')
            ->select(db::raw('CONCAT(lastname, ", ", firstname, " - ", levelname) AS text, studid as id'))
            ->join('studinfo', 'sh_enrolledstud.studid', '=', 'studinfo.id')
            ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
            ->where('sh_enrolledstud.deleted', 0)
            ->where('sh_enrolledstud.studstatus', '>', 0)
            ->where('syid', $syid)
            ->where(function($q) use($semid){
                if($semid == 3)
                {
                    $q->where('sh_enrolledstud.semid', 3);
                }
                else{
                    if(db::table('schoolinfo')->first()->shssetup == 0)
                    {
                        $q->where('sh_enrolledstud.semid', $semid);
                    }
                    else{
                        $q->where('sh_enrolledstud.semid', '!=', 3);
                    }
                }
            })
			->groupBy('studid')
            ->get();

        $college_enrolledstud = db::table('college_enrolledstud')
            ->select(db::raw('CONCAT(lastname, ", ", firstname, " - ", levelname) AS text, studid as id'))
            ->join('studinfo', 'college_enrolledstud.studid', '=', 'studinfo.id')
            ->join('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
            ->where('college_enrolledstud.deleted', 0)
            ->where('college_enrolledstud.studstatus', '>', 0)
            ->where('college_enrolledstud.syid', $syid)
            ->where('college_enrolledstud.semid', $semid)
            ->get();

        $students = collect();

        $students = $students->merge($enrolledstud);
        $students = $students->merge($sh_enrolledstud);
        $students = $students->merge($college_enrolledstud);
        // $stud = collect($students)->toArray();

        return $students->sortBy('text')->values();
        // return $students->values()->all();


        // $students = $studentssort();

        // return $students;
    }

    public static function charges($studid, $syid, $semid)
    {
        $stud = db::table('studinfo')
            ->select('levelid')
            ->where('id', $studid)
            ->first();

        return db::table('studpayscheddetail')
            ->select(db::raw('id, particulars, SUM(amount) AS amount, SUM(amountpay) AS amountpay, SUM(balance) AS balance, classid'))
            ->where('deleted', 0)
            ->where('studid', $studid)
            ->where('syid', $syid)
            ->where(function($q) use($semid, $stud){
                if($stud->levelid == 14 || $stud->levelid == 15)
                {
                    if($semid == 3)
                    {
                        $q->where('semid', 3);
                    }
                    else{
                        if(db::table('schoolinfo')->first()->shssetup == 0)
                        {
                            $q->where('semid', $semid);
                        }
                        else{
                            $q->where('semid', '!=', 3);
                        }
                    }
                }
                elseif($stud->levelid >=  17 && $stud->levelid <= 25)
                {
                    $q->where('semid', $semid);
                }
            })
            ->groupBy('classid')
            ->get();
    }

    //working v2 code
    public function discount_charges(Request $request)
    {
        $studid = $request->get('studid');
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $charges = array();

        $paysched = self::charges($studid, $syid, $semid);

        // $paysched = db::table('studpayscheddetail')
        //     ->select(db::raw('id, particulars, SUM(amount) AS amount, SUM(amountpay) AS amountpay, SUM(balance) AS balance, classid'))
        //     ->where('deleted', 0)
        //     ->where('studid', $studid)
        //     ->where('syid', $syid)
        //     ->where(function($q) use($semid, $stud){
        //         if($stud->levelid == 14 || $stud->levelid == 15)
        //         {
        //             if($semid == 3)
        //             {
        //                 $q->where('semid', 3);
        //             }
        //             else{
        //                 if(db::table('schoolinfo')->first()->shssetup == 0)
        //                 {
        //                     $q->where('semid', $semid);
        //                 }
        //                 else{
        //                     $q->where('semid', '!=', 3);
        //                 }
        //             }
        //         }
        //         elseif($stud->levelid >=  17 && $stud->levelid <= 21)
        //         {
        //             $q->where('semid', $semid);
        //         }
        //     })
        //     ->groupBy('classid')
        //     ->get();

        foreach($paysched as $ledger)
        {
            array_push($charges, (object)[
                'id' => $ledger->id,
                'classid' => $ledger->classid,
                'particulars' => $ledger->particulars,
                'balance' => number_format($ledger->amount, 2)
            ]);
        }

        return $charges;
    }

    // public function discount_charges(Request $request)
    // {
    //     $studid = $request->get('studid');
    //     $syid = $request->get('syid');
    //     $semid = $request->get('semid');
    //     $charges = array();

    //     // $studdiscount = db::table('studdiscounts')
    //     // ->where('studid', $studid)
    //     // ->where('deleted', 0)
    //     // ->first();

    //     // if($studdiscount->groupid != null && $studdiscount->groupid != '')
    //     // {
    //         $groups = db::table('studdiscounts')
    //             ->where('studid', $studid)
	// 			->where('deleted', 0)
    //             ->get();

    //         $groups = collect($groups);
    //     // }

    //     $paysched = self::charges($studid, $syid, $semid);

    //     // $paysched = db::table('studpayscheddetail')
    //     //     ->select(db::raw('id, particulars, SUM(amount) AS amount, SUM(amountpay) AS amountpay, SUM(balance) AS balance, classid'))
    //     //     ->where('deleted', 0)
    //     //     ->where('studid', $studid)
    //     //     ->where('syid', $syid)
    //     //     ->where(function($q) use($semid, $stud){
    //     //         if($stud->levelid == 14 || $stud->levelid == 15)
    //     //         {
    //     //             if($semid == 3)
    //     //             {
    //     //                 $q->where('semid', 3);
    //     //             }
    //     //             else{
    //     //                 if(db::table('schoolinfo')->first()->shssetup == 0)
    //     //                 {
    //     //                     $q->where('semid', $semid);
    //     //                 }
    //     //                 else{
    //     //                     $q->where('semid', '!=', 3);
    //     //                 }
    //     //             }
    //     //         }
    //     //         elseif($stud->levelid >=  17 && $stud->levelid <= 21)
    //     //         {
    //     //             $q->where('semid', $semid);
    //     //         }
    //     //     })
    //     //     ->groupBy('classid')
    //     //     ->get();

    //     foreach($paysched as $ledger)
    //     {
    //         $group = $groups->where('classid', $ledger->classid)->first();
    //         $disc_amount = 0;
    //         if($group)
    //         {
    //             $disc_amount = $group->discamount;
    //         }
    //         array_push($charges, (object)[
    //             'id' => $ledger->id,
    //             'classid' => $ledger->classid,
    //             'particulars' => $ledger->particulars,
    //             'balance' => number_format($ledger->amount, 2),
    //             'disc_amount' => number_format($disc_amount, 2)
    //         ]);
    //     }

    //     return $charges;
    // }

    // working v2 code
    public function discount_getsetup(Request $request)
    {
        $discountid = $request->get('discountid');

        $discounts = db::table('discounts')
            ->select('id', 'particulars', 'amount', 'percent')
            ->where(function($q) use($discountid){
                if($discountid > 0)
                {
                    $q->where('id', $discountid);
                }
            })
            ->where('deleted', 0)
            ->get();

        return $discounts;
    }

    // public function discount_getsetup(Request $request)
    // {
    //     $discountid = $request->get('discountid');

    //     $discounts = db::table('discounts')
    //         ->select('id', 'particulars', 'amount', 'percent', 'isapply_disc_amount')
    //         ->where(function($q) use($discountid){
    //             if($discountid > 0)
    //             {
    //                 $q->where('id', $discountid);
    //             }
    //         })
    //         ->where('deleted', 0)
    //         ->get();

    //     return $discounts;
    // }

    public function discount_post(Request $request)
    {
        $studid = $request->get('studid');
        $discinfo = $request->get('info');
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $discountid = $request->get('discountid');

        // return 'aaa';

        // return FinanceModel::getServerDateTime();
        $groupid = date_format(date_create(FinanceModel::getServerDateTime()), 'mdYHis');

        $levelid = 0;

        $einfo = db::table('enrolledstud')
            ->where('syid', $syid)
            ->where('studid', $studid)
            ->where('deleted', 0)
            ->first();

        if($einfo)
        {
            $levelid = $einfo->levelid;
        }
        else{
            $einfo = db::table('sh_enrolledstud')
                ->where('syid', $syid)
                ->where(function($q) use($semid){
                    if($semid == 3)
                    {
                        $q->where('semid', 3);
                    }
                    else{
                        if(db::table('schoolinfo')->first()->shssetup == 0)
                        {
                            $q->where('semid', $semid);
                        }
                        else{
                            $q->where('semid', '!=', 3);
                        }
                    }
                })
                ->where('studid', $studid)
                ->where('deleted', 0)
                ->first();

            if($einfo)
            {
                $levelid = $einfo->levelid;
            }
            else{
                $einfo = db::table('college_enrolledstud')
                    ->where('syid', $syid)
                    ->where('semid', $semid)
                    ->where('studid', $studid)
                    ->where('deleted', 0)
                    ->first();

                if($einfo)
                {
                    $levelid = $einfo->yearLevel;
                }
                else{
                    $levelid = db::table('studinfo')
                        ->where('id', $studid)
                        ->first()
                        ->levelid;
                }
            }
        }


        foreach($discinfo as $info)
        {
            $discount = str_replace('%', '', $info['discount']);
            $discount = str_replace(',', '', $info['discount']);
            $percent = 0;
            $discamount = str_replace(',', '', $info['discountamount']);

            if(strpos($info['discount'], '%') !== false)
            {
                $percent = 1;
            }
            else{
                $percent = 0;
            }

            // echo 'studid: ' . $studid .'<br>';
            // echo 'discountid ' . $discountid .'<br>';
            // echo 'syid ' . $syid <br>;
            // echo 'semid ' . $semid .'<br>';
            // echo 'classid ' . $info['classid'] .'<br>';
            // echo 'discount ' . $discount .'<br>';
            // echo 'percent ' . $percent .'<br>';
            // echo 'discamount ' . $info['discountamount'] .'<br> <br>';

            $studdiscountid = DB::table('studdiscounts')
                ->insertGetId([
                    'studid' => $studid,
                    'discountid' => $discountid,
                    'syid' => $syid,
                    'semid' => $semid,
                    'classid' => $info['classid'],
                    'discount' => $discount,
                    'percent' => $percent,
                    'discamount' => $discamount,
                    'groupid' => $groupid,
                    'posted' => 1,
                    'deleted' => 0,
                    'createdby' => auth()->user()->id,
                    'createddatetime' => FinanceModel::getServerDateTime()
                ]);

            FinanceUtilityModel::resetv3_generatediscounts($studid, $levelid, $syid, $semid, $studdiscountid);
        }

        return 'done';

    }

    public function discount_getdiscount(Request $request)
    {
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $filter = $request->get('filter');
        $particulars = $request->get('particulars');
        $discount_list = array();

        $discounts = db::table('studdiscounts')
            ->select(db::raw('sid, lastname, firstname, middlename, discounts.percent, particulars, classid, groupid, discounts.amount as discount, gradelevel.levelname,
                discamount, discounts.id, studid, studdiscounts.syid, studdiscounts.semid, studdiscounts.id as studdiscountid'))
            ->join('discounts', 'studdiscounts.discountid', '=', 'discounts.id')
            ->join('studinfo', 'studdiscounts.studid', '=', 'studinfo.id')
            ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
            ->where('studdiscounts.deleted', 0)
            ->where('studdiscounts.syid', $syid)
            ->where(function($q) use($semid){
                if($semid != 0)
                {
                    $q->where('studdiscounts.semid', $semid);
                }
            })
            ->where(function($q) use($particulars){
                if($particulars != ''){
                    $q->where('particulars', $particulars);
                }
            })
            ->where(function($q) use($filter){
                if($filter != '')
                {
                    $q->where('lastname', 'like', '%'.$filter.'%')
                        ->orWhere('firstname', 'like', '%'.$filter.'%')
                        ->orWhere('particulars', 'like', '%'.$filter.'%')
						->orWhere('sid', 'like', '%'.$filter.'%');
                }
            })
            ->groupBy('discounts.id', 'sid')
            ->get();
        
        $allparticulars = db::table('studdiscounts')
            ->select('particulars')
            ->join('discounts', 'studdiscounts.discountid', '=', 'discounts.id')
            ->join('studinfo', 'studdiscounts.studid', '=', 'studinfo.id')
            ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
            ->where('studdiscounts.deleted', 0)
            ->where('studdiscounts.syid', $syid)
            ->where(function($q) use($semid){
                if($semid != 0)
                {
                    $q->where('studdiscounts.semid', $semid);
                }
            })
            ->where(function($q) use($filter){
                if($filter != '')
                {
                    $q->where('lastname', 'like', '%'.$filter.'%')
                        ->orWhere('firstname', 'like', '%'.$filter.'%')
                        ->orWhere('particulars', 'like', '%'.$filter.'%')
						->orWhere('sid', 'like', '%'.$filter.'%');
                }
            })
            ->groupBy('particulars')
            ->get();
        
        foreach($discounts as $discount)
        {
            $discamount = '';

            if($discount->percent == 1)
            {
                $discamount = $discount->discount . '%';
            }
            else{
                $discamount = number_format($discount->discount, 2);
            }

            array_push($discount_list, (object)[
                'fullname' => $discount->sid . ' - ' . $discount->lastname . ', ' . $discount->firstname . ' ' . $discount->middlename,
                'particulars' => $discount->particulars,
                'discount' => $discamount,
                'groupid' => $discount->groupid,
                'discid' => $discount->id,
                'studid' => $discount->studid,
                'studdiscountid' => $discount->studdiscountid,
                'syid' => $discount->syid,
                'semid' => $discount->semid,
                'levelname' => $discount->levelname,
                'discamount' => $discount->discamount,
                'allparticulars' => $allparticulars
            ]);
        }

        return $discount_list;
    }

    public function history_deleteddiscount(Request $request)
    {
        $syid = $request->get('syid');
        $semid = $request->get('semid');

        $discounts = DB::table('studdiscounts')
            ->select(
                'sid',
                'lastname',
                'firstname',
                'middlename',
                'discounts.percent',
                'particulars',
                'classid',
                'groupid',
                'discounts.amount as discount',
                DB::raw('SUM(discamount) as discamount'),
                'discounts.id',
                'studid',
                'studdiscounts.syid',
                'studdiscounts.semid',
                'studdiscounts.id as studdiscountid'
            )
            ->join('discounts', 'studdiscounts.discountid', '=', 'discounts.id')
            ->join('studinfo', 'studdiscounts.studid', '=', 'studinfo.id')
            ->where('studdiscounts.deleted', 1)
            ->where('studdiscounts.syid', $syid)
            ->where('studdiscounts.semid', $semid)
            ->groupBy('discounts.id', 'sid')
            ->get();

        $discount_list = [];

        foreach ($discounts as $discount) {
            $discamount = $discount->percent == 1
                ? $discount->discount . '%' 
                : number_format($discount->discamount, 2) . ' PHP';

            $discount_list[] = (object) [
                'fullname' => "{$discount->lastname}, {$discount->firstname} " . ($discount->middlename ?? ''),
                'particulars' => $discount->particulars,
                'discount' => $discamount
            ];
        }

        return response()->json($discount_list);
    }

    // working v2 code
    public function discount_read(Request $request)
    {
        $dataid = $request->get('dataid');

        $studdiscount = db::table('studdiscounts')
            ->where('id', $dataid)
			->where('deleted', 0)
            ->first();

        $studid = $studdiscount->studid;
        $syid = $studdiscount->syid;
        $semid = $studdiscount->semid;
        $discountid = $studdiscount->discountid;

        if($studdiscount->groupid != null && $studdiscount->groupid != '')
        {
            $groups = db::table('studdiscounts')
                ->where('groupid', $studdiscount->groupid)
				->where('deleted', 0)
                ->get();

            $groups = collect($groups);
        }


        $charges = self::charges($studid, $studdiscount->syid, $studdiscount->semid);

        $discountlist = '';
        $totalcharges = 0;
        $totaldiscount = 0;

        foreach($charges as $ledger)
        {
            $group = $groups->where('classid', $ledger->classid)->first();
            $totalcharges += $ledger->amount;

            if($group)
            {
                $discountamount = $group->percent == 1 ? $group->discount . '%' : number_format($group->discount, 2);
                $totaldiscount += $group->discamount;

                $discountlist .= '
                    <tr data-id="'.$ledger->id.'" class-id="'.$ledger->classid.'">
                        <td class="text-center"><input type="checkbox" checked class="discount_include" id="'.$ledger->id.'"></td>
                        <td><label for="'.$ledger->id.'">'.$ledger->particulars.'</label></td>
                        <td class="text-right discount_balance">'.number_format($ledger->amount, 2).'</td>
                        <td>
                            <input type="text" class="discount_amount form-control" value="'.$discountamount.'">
                        </td>
                        <td class="text-right discount_discamount">'.number_format($group->discamount, 2).'</td>
                    </tr>
                ';
            }
            else{
                $discountlist .= '
                    <tr data-id="'.$ledger->id.'" class-id="'.$ledger->classid.'">
                        <td class="text-center"><input type="checkbox" class="discount_include" id="'.$ledger->id.'"></td>
                        <td><label for="'.$ledger->id.'">'.$ledger->particulars.'</label></td>
                        <td class="text-right discount_balance">'.number_format($ledger->amount, 2).'</td>
                        <td>
                            <input type="text" class="discount_amount form-control">
                        </td>
                        <td class="text-right discount_discamount">0.00</td>
                    </tr>
                ';
            }
        }

        return array(
            'studid' => $studid,
            'discountid' => $discountid,
            'syid' => $syid,
            'semid' => $semid,
            'discountlist' => $discountlist,
            'totalcharges' => number_format($totalcharges, 2),
            'totaldiscount' => number_format($totaldiscount, 2)
        );

    }

    // public function discount_read(Request $request)
    // {
    //     $dataid = $request->get('dataid');

    //     $studdiscount = db::table('studdiscounts')
    //         ->where('id', $dataid)
	// 		->where('deleted', 0)
    //         ->first();

    //     $studid = $studdiscount->studid;
    //     $syid = $studdiscount->syid;
    //     $semid = $studdiscount->semid;
    //     $discountid = $studdiscount->discountid;

    //     if($studdiscount->groupid != null && $studdiscount->groupid != '')
    //     {
    //         $groups = db::table('studdiscounts')
    //             ->where('groupid', $studdiscount->groupid)
	// 			->where('deleted', 0)
    //             ->get();

    //         $groups = collect($groups);
    //     }


    //     $charges = self::charges($studid, $studdiscount->syid, $studdiscount->semid);

    //     $discountlist = '';
    //     $totalcharges = 0;
    //     $totaldiscount = 0;

    //     foreach($charges as $ledger)
    //     {
    //         $group = $groups->where('classid', $ledger->classid)->first();
    //         $totalcharges += $ledger->amount;

    //         if($group)
    //         {
    //             $discountamount = $group->percent == 1 ? $group->discount . '%' : number_format($group->discount, 2);
    //             $totaldiscount += $group->discamount;

    //             $discountlist .= '
    //                 <tr data-id="'.$ledger->id.'" class-id="'.$ledger->classid.'">
    //                     <td class="text-center"><input type="checkbox" checked class="discount_include" id="'.$ledger->id.'"></td>
    //                     <td><label for="'.$ledger->id.'">'.$ledger->particulars.'</label></td>
    //                     <td class="text-left discount_balance">'.number_format($ledger->amount, 2).'</td>
    //                     <td class="text-left discount_discamount">'.number_format($group->discamount, 2).'</td>
    //                     <td>
    //                         <input type="text" class="discount_amount form-control" value="'.$discountamount.'">
    //                     </td>
    //                     <td class="text-right total_discount_discamount">'.number_format($group->discamount, 2).'</td>
                      
    //                 </tr>
    //             ';
    //         }
    //         else{
    //             $discountlist .= '
    //                 <tr data-id="'.$ledger->id.'" class-id="'.$ledger->classid.'">
    //                     <td class="text-center"><input type="checkbox" class="discount_include" id="'.$ledger->id.'"></td>
    //                     <td><label for="'.$ledger->id.'">'.$ledger->particulars.'</label></td>
    //                     <td class="text-left discount_balance">'.number_format($ledger->amount, 2).'</td>
    //                     <td class="text-left discount_discamount">0.00</td>
    //                     <td>
    //                         <input type="text" class="discount_amount form-control">
    //                     </td>
    //                     <td class="text-right total_discount_discamount">0.00</td>
                      
    //                 </tr>
    //             ';
    //         }
    //     }

    //     return array(
    //         'studid' => $studid,
    //         'discountid' => $discountid,
    //         'syid' => $syid,
    //         'semid' => $semid,
    //         'discountlist' => $discountlist,
    //         'totalcharges' => number_format($totalcharges, 2),
    //         'totaldiscount' => number_format($totaldiscount, 2)
    //     );

    // }

    public function discount_read_1(Request $request)
    {
        $dataid = $request->get('dataid');

        $studdiscount = db::table('studdiscounts')
            ->where('id', $dataid)
			->where('deleted', 0)
            ->first();

        if($studdiscount->groupid != null && $studdiscount->groupid != '')
        {
            $groups = db::table('studdiscounts')
                ->where('groupid', $studdiscount->groupid)
				->where('deleted', 0)
                ->get();
        }

        $grouparray = array();
        $discountinfo = array();
        $studid = $studdiscount->studid;
        $discountid = $studdiscount->discountid;
        // return $discountid;

        if($studdiscount->groupid != null && $studdiscount->groupid != '')
        {
            $groups = db::table('studdiscounts')
                ->where('groupid', $studdiscount->groupid)
				->where('deleted', 0)
                ->get();

            foreach($groups as $group)
            {
                $class = db::table('itemclassification')
                    ->where('id', $group->classid)
                    ->first();

                $sign = '';

                if($group->percent == 1)
                {
                    $sign = '%';
                }
                else{
                    $sign = '';
                }

                $classname = $class->description;

                array_push($grouparray, (object)[
                    'classid' => $group->classid,
                    'classname' => $classname,
                    'discount' => $group->discount . $sign,
                    'discamount' => number_format($group->discamount, 2)
                ]);
            }
        }
        else{

        }

        $data = array(
            'studid' => $studid,
            'discountid' => $discountid,
            'groups' => $grouparray,
            'syid' => $studdiscount->syid,
            'semid' => $studdiscount->semid
        );

        return $data;
    }

    public function exportExcel(Request $request)
    {
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $search = $request->get('search');
        $particulars = $request->get('particulars');

        // Get data using the same function as your table
        $discounts = $this->getDiscountData($syid, $semid, $search, $particulars);
        // dd($discounts);
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator("Your System")
            ->setLastModifiedBy("Your System")
            ->setTitle("Student Discounts Export")
            ->setSubject("Student Discounts")
            ->setDescription("Export of student discount records");

        // Set column headers with styling
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '3490DC']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ];

        $sheet->setCellValue('A1', 'Student ID');
        $sheet->setCellValue('B1', 'Student Name');
        $sheet->setCellValue('C1', 'Level');
        $sheet->setCellValue('D1', 'Particulars');
        $sheet->setCellValue('E1', 'Discount');
        $sheet->setCellValue('F1', 'Discounted Amount');
        
        // Apply header style
        $sheet->getStyle('A1:F1')->applyFromArray($headerStyle);

        // Populate data rows
        $row = 2;
        foreach ($discounts as $discount) {
            $sheet->setCellValue('A' . $row, $discount->sid);
            $sheet->setCellValue('B' . $row, $discount->lastname . ', ' . $discount->firstname . ' ' . $discount->middlename);
            $sheet->setCellValue('C' . $row, $discount->levelname);
            $sheet->setCellValue('D' . $row, $discount->particulars);
            
            $sheet->setCellValue('E' . $row, number_format($discount->discount, 2) . '%');
            $sheet->setCellValue('F' . $row, number_format($discount->discamount, 2));
            
            $row++;
        }

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(20);

        // Apply data styling
        $dataStyle = [
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]
        ];
        $sheet->getStyle('A2:F' . ($row-1))->applyFromArray($dataStyle);

        // Right align numeric columns
        $sheet->getStyle('E2:F' . ($row-1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // Freeze header row
        $sheet->freezePane('A2');

        // Create filename with timestamp
        $filename = 'student_discounts_' . date('Y-m-d_H-i-s') . '.xlsx';

        // Create writer and output
        $writer = new Xlsx($spreadsheet);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }

    private function getDiscountData($syid, $semid, $filter, $particulars)
    {
       return db::table('studdiscounts')
        ->select(db::raw('sid, lastname, firstname, middlename, discounts.percent, particulars, classid, groupid, discounts.amount as discount, gradelevel.levelname,
            discamount, discounts.id, studid, studdiscounts.syid, studdiscounts.semid, studdiscounts.id as studdiscountid'))
        ->join('discounts', 'studdiscounts.discountid', '=', 'discounts.id')
        ->join('studinfo', 'studdiscounts.studid', '=', 'studinfo.id')
        ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
        ->where('studdiscounts.deleted', 0)
        ->where('studdiscounts.syid', $syid)
        ->where(function($q) use($semid){
            if($semid != 0)
            {
                $q->where('studdiscounts.semid', $semid);
            }
        })
        ->where(function($q) use($filter){
            if($filter != '')
            {
                $q->where('lastname', 'like', '%'.$filter.'%')
                    ->orWhere('firstname', 'like', '%'.$filter.'%')
                    ->orWhere('particulars', 'like', '%'.$filter.'%')
                    ->orWhere('sid', 'like', '%'.$filter.'%');
            }
        })
        ->when(isset($particulars) && $particulars != 0 && $particulars != '', function($q) use($particulars){
            $q->where('particulars', $particulars);
        })
        ->groupBy('discounts.id', 'sid')
        ->get();
    
    }

}
