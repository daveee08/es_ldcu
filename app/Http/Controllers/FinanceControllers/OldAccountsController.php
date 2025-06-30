<?php

namespace App\Http\Controllers\FinanceControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Session;
use TCPDF;
use App\FinanceModel;
use App\Models\Finance\FinanceUtilityModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PDF;
class OldAccountsController extends Controller
{
    public function oldaccounts(Request $request)
    {
        return view('finance..oldaccounts');
    }

    public function oa_loadsy(Request $request)
    {
        $levelid = $request->get('levelid');
        $sylist ='';
        $semlist ='';

        if($levelid >= 17 && $levelid <= 25)
        {
            $schoolyear = db::table('sy')
                ->orderBy('sydesc')
                ->get();

            if(FinanceModel::getSemID() == 2)
            {
                foreach($schoolyear as $sy)
                {
                    if($sy->isactive == 1)
                    {
                        $sylist .='
                            <option value="'.$sy->id.'">'.$sy->sydesc.'</option>
                        ';    
                        break;
                    }
                    else
                    {
                        $sylist .='
                            <option value="'.$sy->id.'">'.$sy->sydesc.'</option>
                        ';    
                    }
                    
                }
            }
            else
            {
                foreach($schoolyear as $sy)
                {
                    // echo $sy->isactive . ' ' . $sy->sydesc . '<br>';
                    if($sy->isactive == 1)
                    {
                        break;
                    }
                    else
                    {
                        $sylist .='
                            <option value="'.$sy->id.'">'.$sy->sydesc.'</option>
                        ';
                    }
                }   
            }
        }
        elseif($levelid == 14 || $levelid == 15)
        {
            $schoolyear = db::table('sy')
                ->orderBy('sydesc')
                ->get();

            if(db::table('schoolinfo')->first()->shssetup == 0)
            {
                if(FinanceModel::getSemID() == 2)
                {
                    foreach($schoolyear as $sy)
                    {
                        if($sy->isactive == 1)
                        {
                            $sylist .='
                                <option value="'.$sy->id.'">'.$sy->sydesc.'</option>
                            ';    
                            break;
                        }
                        else
                        {
                            $sylist .='
                                <option value="'.$sy->id.'">'.$sy->sydesc.'</option>
                            ';    
                        }
                        
                    }
                }
                else
                {
                    foreach($schoolyear as $sy)
                    {
                        // echo $sy->isactive . ' ' . $sy->sydesc . '<br>';
                        if($sy->isactive == 1)
                        {
                            break;
                        }
                        else
                        {
                            $sylist .='
                                <option value="'.$sy->id.'">'.$sy->sydesc.'</option>
                            ';
                        }
                    }   
                }                
            }
            else
            {
                foreach($schoolyear as $sy)
                {
                    if($sy->isactive == 1)
                    {
                        break;
                    }
                    else
                    {
                        $sylist .='
                            <option value="'.$sy->id.'">'.$sy->sydesc.'</option>
                        ';
                    }
                }
            }
        }
        else
        {
            $schoolyear = db::table('sy')
                ->orderBy('sydesc')
                ->get();

                foreach($schoolyear as $sy)
                {
                    if($sy->isactive == 1)
                    {
                        break;
                    }
                    else
                    {
                        $sylist .='
                            <option value="'.$sy->id.'">'.$sy->sydesc.'</option>
                        ';
                    }
                }
        }

        $shssetup = db::table('schoolinfo')->first()->shssetup;

        $data = array(
            'shssetup' => $shssetup,
            'sylist'=> $sylist,
            'semactive' => FinanceModel::getSemID(),
            'syactive' => FinanceModel::getSYID()
        );

        echo json_encode($data);
    }

    public function oa_load(Request $request)
    {
        $levelid = $request->get('levelid');
        $syid = $request->get('syid');
        $semid = $request->get('semid');
        $filter = $request->get('filter');

        $oaclassid = db::table('balforwardsetup')->first()->classid;
        $old_list = '';

        $oldaccounts = db::table('studledger')
            ->select(db::raw('CONCAT(lastname, ", ", firstname) AS fullname, SUM(amount) AS amount, SUM(payment) AS payment, SUM(amount) - SUM(payment) AS balance, studid, sid'))
            ->join('studinfo', 'studledger.studid', '=', 'studinfo.id')
            ->where('studledger.deleted', 0)
            ->where('syid', $syid)
            ->where('levelid', $levelid)
            ->where(function($q) use($levelid, $semid){
                if($levelid == 14 || $levelid ==15)
                {
                    if(db::table('schoolinfo')->first()->shssetup == 0)
                    {
                        $q->where('semid', $semid);
                    }
                }
                if($levelid >= 17 && $levelid <= 25)
                {
                    $q->where('studledger.semid', $semid);
                }
            })
            ->groupBy('studid')
            ->having('balance', '>', 0)
            ->having('fullname', 'like', '%'.$filter.'%')
            ->get();

        foreach($oldaccounts as $old)
        {
            $old_list .='
                <tr>
                    <td class="fullname">'.$old->sid . ' - ' . $old->fullname.'</td>
                    <td class="text-right">'.number_format($old->amount, 2).'</td>
                    <td class="text-right">'.number_format($old->payment, 2).'</td>
                    <td class="text-right">'.number_format($old->balance, 2).'</td>
                    <td class="text-center">
                        <button id="" class="btn btn-primary btn-sm oa_forward" data-toggle="tooltip" title="Forward Old Account" data-id="'.$old->studid.'" data-amount="'.$old->balance.'">
                            <i class="fas fa-external-link-alt"></i>
                        </button>
                        <button class="btn btn-success btn-sm v-ledger" data-toggle="tooltip" title="View Ledger" data-id="'.$old->studid.'">
                            <i class="fas fa-file-invoice"></i>
                        </button>
                    </td>
                </t>
            ';
        }

        // return $old_list;

        $data = array(
            'list' => $old_list
        );

        echo json_encode($data);
    }

    // public function oa_forward(Request $request)
    // {
    //     $studid = $request->get('studid');
    //     $syfrom = $request->get('syfrom');
    //     $semfrom = $request->get('semfrom');
    //     $amount = $request->get('amount');
    //     $action = $request->get('action');
    //     $tempamount = 0;

    //     if($semfrom == null)
    //     {
    //         $semfrom = 1;
    //     }

    //     $sy = db::table('sy')
    //         ->where('id', $syfrom)
    //         ->first();

    //     $sem = db::table('semester')
    //         ->where('id', $semfrom)
    //         ->first();

    //     $syid = FinanceModel::getSYID();
    //     $semid = FinanceModel::getSemID();

    //     $studinfo = db::table('studinfo')
    //         ->select('id', 'levelid')
    //         ->where('id', $studid)
    //         ->first();

    //     $levelid = $studinfo->levelid;

    //     if($syfrom == $syid)
    //     {
    //         if($semfrom == $semid)
    //         {
    //             return 'error';
    //         }
    //     }

    //     $balclassid = db::table('balforwardsetup')->first()->classid;

    //     $particulars = 'Balance forwarded from SY ' . $sy->sydesc . ' ' . $sem->semester;
    //     $reverse_particulars = 'Balance forwarded to ' . FinanceModel::getSYDesc() . ' ' . FinanceModel::getSemDesc();

    //     $studledger = db::table('studledger')
    //         ->where('studid', $studid)
    //         ->where('syid', FinanceModel::getSYID())
    //         ->where('semid', FinanceModel::getSemID())
    //         ->where('particulars', 'like',  '%'.$particulars.'%')
    //         ->where('deleted', 0)
    //         ->first();

    //     if(!$studledger)
    //     {
    //         $oldledger = db::table('studledger')
    //             ->select(db::raw('SUM(amount) - SUM(payment) AS balance'))
    //             ->where('studid', $studid)
    //             ->where('syid', $syfrom)
    //             ->where(function($q)use($levelid, $semfrom){
    //                 if($levelid == 14 || $levelid == 15)
    //                 {
    //                     if(db::table('schoolinfo')->first()->shssetup == 0)
    //                     {
    //                         $q->where('semid', $semfrom);
    //                     }
    //                 }
    //                 if($levelid >= 17 && $levelid <= 20)
    //                 {
    //                     $q->where('semid', $semfrom);
    //                 }
    //             })
    //             ->where('deleted', 0)
    //             ->where('void', 0)
    //             ->first();

    //         if($oldledger)
    //         {
    //             if($oldledger->balance > 0)
    //             {
    //                 if($action == 'create')
    //                 {
    //                     $tempamount = $amount;
    //                     $reverse_particulars = $reverse_particulars . ' - Amount: ' . $amount;
    //                     $amount = $oldledger->balance;
    //                 }
    //                 else{
    //                     $tempamount = $amount;
    //                 }

    //                 db::table('studledger')
    //                     ->insert([
    //                         'studid' => $studid,
    //                         'syid' => $syfrom,
    //                         'semid' => $semfrom,
    //                         'classid' => $balclassid,
    //                         'particulars' =>$reverse_particulars,
    //                         'payment' => $amount,
    //                         'createddatetime' => FinanceModel::getServerDateTime(),
    //                         'deleted' => 0,
    //                         'void' => 0
    //                     ]);

    //                 $itemized = db::table('studledgeritemized')
    //                     ->where('studid', $studid)
    //                     ->where('syid', $syfrom)
    //                     ->where(function($q)use($levelid, $semfrom){
    //                         if($levelid == 14 || $levelid == 15)
    //                         {
    //                             if(db::table('schoolinfo')->first()->shssetup == 0)
    //                             {
    //                                 $q->where('semid', $semfrom);
    //                             }
    //                         }
    //                         if($levelid >= 17 && $levelid <= 20)
    //                         {
    //                             $q->where('semid', $semfrom);
    //                         }
    //                     })
    //                     ->where('deleted', 0)
    //                     ->whereColumn('totalamount', '!=', 'itemamount')
    //                     ->get();

    //                 foreach($itemized as $item)
    //                 {
    //                     db::table('studledgeritemized')   
    //                         ->where('id', $item->id)
    //                         ->update([
    //                             'totalamount' => $item->itemamount
    //                         ]);
    //                 }

    //                 $payscheddetail = db::table('studpayscheddetail')
    //                     ->where('studid', $studid)
    //                     ->where('syid', $syfrom)
    //                     ->where(function($q)use($levelid, $semfrom){
    //                         if($levelid == 14 || $levelid == 15)
    //                         {
    //                             if(db::table('schoolinfo')->first()->shssetup == 0)
    //                             {
    //                                 $q->where('semid', $semfrom);
    //                             }
    //                         }
    //                         if($levelid >= 17 && $levelid <= 20)
    //                         {
    //                             $q->where('semid', $semfrom);
    //                         }
    //                     })
    //                     ->where('deleted', 0)
    //                     ->where('balance', '>', 0)
    //                     ->get();

    //                 foreach($payscheddetail as $detail)
    //                 {
    //                     db::table('studpayscheddetail')
    //                         ->where('id', $detail->id)
    //                         ->update([
    //                             'amountpay' => $detail->amountpay + $detail->balance,
    //                             'balance' => 0,
    //                             'updateddatetime' => FinanceModel::getServerDateTime()
    //                         ]);
    //                 }

    //             }
	// 			else{
    //                 $tempamount = $amount;
    //             }
    //         }

    //         db::table('studledger')
    //             ->insert([
    //                 'studid' => $studid,
    //                 'syid' => FinanceModel::getSYID(),
    //                 'semid' => FinanceModel::getSemID(),
    //                 'classid' => $balclassid,
    //                 'particulars' =>$particulars,
    //                 'amount' => $tempamount,
    //                 'createddatetime' => FinanceModel::getServerDateTime(),
    //                 'deleted' => 0,
    //                 'void' => 0
    //             ]);

    //         FinanceUtilityModel::resetv3_generateoldaccounts($studid, $levelid, $syid, $semid);

    //         return 'done';
    //     }
    //     else
    //     {
    //         return 'exist';
    //     }
    // }

    public function oa_forward(Request $request)
    {
        $studid = $request->get('studid');
        $syfrom = $request->get('syfrom');
        $semfrom = $request->get('semfrom');
        $amount = $request->get('amount');
        $action = $request->get('action');
        $tempamount = 0;
        $levelid = 0;

        if($semfrom == null)
        {
            $semfrom = 1;
        }

        $sy = db::table('sy')
            ->where('id', $syfrom)
            ->first();

        $sem = db::table('semester')
            ->where('id', $semfrom)
            ->first();

        $syid = FinanceModel::getSYID();
        $semid = FinanceModel::getSemID();

        $einfo = db::table('enrolledstud')
            ->select(db::raw('levelid'))
            ->where('studid', $studid)
            ->where('syid', $syfrom)
            ->where('deleted', 0)
            ->first();
        
        if($einfo)
        {
            $levelid = $einfo->levelid;
        }
        else{
            $einfo = db::table('sh_enrolledstud')
                ->select(db::raw('levelid'))
                ->where('studid', $studid)
                ->where('syid', $syfrom)
                ->where('deleted', 0)
                ->first();

            if($einfo)
            {
                $levelid = $einfo->levelid;
            }
            else{
                $einfo = db::table('college_enrolledstud')
                    ->select(db::raw('yearLevel as levelid'))
                    ->where('studid', $studid)
                    ->where('syid', $syfrom)
                    ->where('semid', $semfrom)
                    ->where('deleted', 0)
                    ->first();
                
                if($einfo)
                {
                    $levelid = $einfo->levelid;
                }
                else{
                    $einfo = db::table('studinfo')
                        ->select('levelid')
                        ->where('id', $studid)
                        ->first();

                    if($einfo)
                    {
                        $levelid = $einfo->levelid;
                    }
                }
            }
        }

        $payscheddetail = db::table('studpayscheddetail')
            ->select(db::raw('studid, classid, particulars, SUM(balance) AS amount'))
            ->where('studid', $studid)
            ->where('syid', $syfrom)
            ->where(function($q) use($levelid, $semfrom){
                if($levelid >= 17 && $levelid <= 25)
                {
                    $q->where('semid', $semfrom);
                }
            })
            ->where('deleted', 0)
            ->where('balance', '>', 0)
            ->groupBy('classid')
            ->get();

        $payscheddetail = collect($payscheddetail);

        $balsetup = db::table('balforwardsetup')->first();

        $oa_check = db::table('oldaccounts')
            ->where('studid', $studid)
            ->where('syfrom', $syfrom)
            ->where(function($q) use($levelid, $semfrom){
                if($levelid >= 17 && $levelid <= 25)
                {
                    $q->where('semid', $semfrom);
                }
            })
            ->where('deleted', 0)
            ->count();

        if($oa_check > 0)
        {
            return 'exist';
        }
        else{
            if($balsetup->classified == 0)
            {
                $totalamount = 0;

                foreach($payscheddetail as $detail)
                {
                    $totalamount += $detail->amount;
                }

                $oa_header = db::table('oldaccounts')
                    ->insertGetID([
                        'studid' => $studid,
                        'syid' => $syid,
                        'semid' => $semid,
                        'syfrom' => $syfrom,
                        'semfrom' => $semfrom,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => FinanceModel::getServerDateTime()
                    ]);
                
                db::table('oldaccountdetails')
                    ->insert([
                        'headerid' => $oa_header,
                        'amount' => $totalamount,
                        'classid' => 0,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => FinanceModel::getServerDateTime()
                    ]);


                FinanceUtilityModel::resetv3_generateoa_v2($studid, $levelid, $syid, $semid, $oa_header);
                return 'done';
            }
            else{
                $oa_header = db::table('oldaccounts')
                    ->insertGetID([
                        'studid' => $studid,
                        'syid' => $syid,
                        'semid' => $semid,
                        'syfrom' => $syfrom,
                        'semfrom' => $semfrom,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => FinanceModel::getServerDateTime()
                    ]);

                foreach($payscheddetail as $detail)
                {
                    db::table('oldaccountdetails')
                        ->insert([
                            'headerid' => $oa_header,
                            'amount' => $detail->amount,
                            'classid' => $detail->classid,
                            'createdby' => auth()->user()->id,
                            'createddatetime' => FinanceModel::getServerDateTime()
                        ]);
                }

                FinanceUtilityModel::resetv3_generateoa_v2($studid, $levelid, $syid, $semid, $oa_header);
                return 'done';
            }
        }

        
    }
	
    public function oa_setup(Request $request)
    {
        $setup = db::table('balforwardsetup')
            ->first();



        if($setup)
        {
            $data = array(
                'classid' => $setup->classid,
                'mopid' => $setup->mopid,
                'classified' => $setup->classified
            );

            return $data;
        }
    }

    public function oa_setupsave(Request $request)
    {
        $classid = $request->get('classid');
        $mop = $request->get('mop');
        $classified = $request->get('classified');

        db::table('balforwardsetup')
            ->where('id', 1)
            ->update([
                'classid' => $classid,
                'mopid' => $mop,
                'classified' => $classified
            ]);
    }

    public function old_add_studlist(Request $request)
    {
        if($request->ajax())
        {
            $studid = $request->get('studid');
            $studlist = '<option value="0">NAME</option>';
            $sylist = '<option value="0">School Year</option>';
            $semlist = '<option value="0">Semester</option>';
            if($studid > 0)
            {
                $stud = db::table('studinfo')
                    ->select('studinfo.id', 'levelname', 'levelid', 'sectionid', 'courseid', 'grantee.description as grantee')
                    ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
                    ->join('grantee', 'studinfo.grantee', '=', 'grantee.id')
                    ->where('studinfo.id', $studid)
                    ->first();

                if($stud)
                {
                    $section = '';

                    if($stud->levelid >= 17 && $stud->levelid <= 25)
                    {
                        $collegecourse = db::table('college_courses')
                            ->where('id', $stud->courseid)
                            ->first();

                        if($collegecourse)
                        {
                            $section = $collegecourse->courseabrv;
                        }

                        $sem = db::table('semester')
                            ->where('isactive', 1)
                            ->first();

                        if($sem->id == 1)
                        {
                            $schoolyear = db::table('sy')
                                ->where('sydesc', '<', FinanceModel::getSYDesc())
                                ->get();

                            foreach($schoolyear as $sy)
                            {
                                $sylist .='
                                    <option value="'.$sy->id.'">'.$sy->sydesc.'</option>
                                ';    
                            }

                            $_semester = db::table('semester')
                                ->get();

                            foreach($_semester as $_sem)
                            {
                                $semlist .='
                                    <option value="'.$_sem->id.'">'.$_sem->semester.'</option>
                                ';       
                            }
                        }
                        elseif($sem->id == 2)
                        {
                            $schoolyear = db::table('sy')
                                ->where('sydesc', '<=', FinanceModel::getSYDesc())
                                ->get();

                            foreach($schoolyear as $sy)
                            {
                                $sylist .='
                                    <option value="'.$sy->id.'">'.$sy->sydesc.'</option>
                                ';    
                            }
                        }

                        
                    }
                    else
                    {
                        $_section = db::table('sections')
                            ->where('id', $stud->sectionid)
                            ->first();

                        if($_section)
                        {
                            $section = $_section->sectionname;
                        }

                        $schoolyear = db::table('sy')
                            ->where('sydesc', '<', FinanceModel::getSYDesc())
                            ->get();

                        foreach($schoolyear as $sy)
                        {
                            $sylist .='
                                <option value="'.$sy->id.'">'.$sy->sydesc.'</option>
                            ';    
                        }
                    }

                    // return $section;

                    $data = array(
                        'levelname' => $stud->levelname,
                        'grantee' => $stud->grantee,
                        'section' => $section,
                        'levelid' => $stud->levelid,
                        'semlist' => $semlist,
                        'sylist' => $sylist
                    );
                }
            }
            else
            {
                $studinfo = db::table('studinfo')
                    ->select('id', 'sid', 'lastname', 'firstname')
                    ->where('deleted', 0)
                    ->orderBy('lastname')
                    ->orderBy('firstname')
                    ->get();

                foreach($studinfo as $stud)
                {
                    $studlist .='
                        <option value="'.$stud->id.'">'.$stud->sid.' - '.$stud->lastname.', '.$stud->firstname.' </option>
                    ';
                }

                $data = array(
                    'studlist' => $studlist
                );
            }

            echo json_encode($data);
        }
    }

    public function old_getsem(Request $request)
    {
        $syid = $request->get('syid');
        $semlist = '';

        if($syid == FinanceModel::getSYID())
        {
            $semlist .='
                <option value="0">Semester</option>
                <option value="1">1st Semester</option>
            ';
        }
        else
        {
            $semlist .='
                <option value="0">Semester</option>
                <option value="1">1st Semester</option>
                <option value="2">2nd Semester</option>
            ';   
        }

        return $semlist;
    }

    // public function get_old_acounts_from_sy(Request $request)
    // {

    //     $syid = $request->get('syid');
    //     $studstat = $request->get('studstat');
    //     $paystat = $request->get('paystat');
    //     $activestatus = $request->get('activestatus');
    //     $fillevelid = $request->get('fillevelid');
    //     $fillsectionid = $request->get('fillsectionid');

    //     $semid = $request->get('semid');
    //     $ghssemid = $semid == 3 ? [$semid] : [1,2];
    //     $all_acad = self::get_acad($syid);
    //     $schoolinfo = DB::table('schoolinfo')->first();
    //     $all_enrolledstudents = array();

    //     $search = $request->get('search');
    //     // $search = $search['value'];
    //     $search = isset($search['value']) ? $search['value'] : null;

    //     $transdate = $request->get('transdate');
    //     $processtype = $request->get('procctype');
    //     $enrollmentdate = $request->get('enrollmentdate');

    //     $levelacad = null;
    //     if($fillevelid != 0 && $fillevelid != ''){
    //           $levelacad = DB::table('gradelevel')
    //                             ->where('id',$fillevelid)
    //                             ->first()
    //                             ->acadprogid;
    //     }

    //     $all_students = DB::table('studinfo')
    //     ->where('studinfo.deleted',0)
    //     ->join('gradelevel',function($join) use($all_acad){
    //           $join->on('studinfo.levelid','=','gradelevel.id');
    //           $join->where('gradelevel.deleted',0);
    //           $join->whereIn('gradelevel.acadprogid',$all_acad);
    //     })
    //     ->leftJoin('sh_strand',function($join){
    //           $join->on('studinfo.strandid','=','sh_strand.id');
    //           $join->where('sh_strand.deleted',0);
    //     })
    //     ->leftJoin('college_courses',function($join){
    //           $join->on('studinfo.courseid','=','college_courses.id');
    //           $join->where('college_courses.deleted',0);
    //     })->leftJoin('studledger',function($join) use($syid,$semid){
    //           $join->on('studinfo.id','=','studledger.studid');
    //           $join->where('studledger.deleted',0);
    //           $join->where('studledger.syid',$syid);
    //           $join->where('studledger.semid',$semid);
    //     })
    //     ->leftJoin('sy',function($join){
    //     $join->on('studledger.syid','=','sy.id');
    //     })
        
    //     ;

    //     $all_students = $all_students->take($request->get('length'))
    //     ->skip($request->get('start'))
    //     ->orderBy('lastname')
    //     ->select(
    //           'collegeid',
    //           'contactno',
    //           'ismothernum',
    //           'isfathernum',
    //           'isguardannum',
    //           'mcontactno',
    //           'fcontactno',
    //           'gcontactno',
    //           'mothername',
    //           'fathername',
    //           'guardianname',
    //           'gradelevel.levelname',
    //           'gradelevel.levelname as curlevelname',
    //           'studinfo.levelid as curlevelid',
    //           'studinfo.levelid',
    //           'studinfo.sectionid',
    //           DB::raw("CONCAT(studinfo.lastname, ', ', studinfo.firstname, IFNULL(studinfo.suffix, ''), IFNULL(studinfo.middlename, '')) AS student"),
    //           'studinfo.grantee',
    //           'studinfo.pantawid',
    //           'studinfo.mol',
    //           'lastname',
    //           'firstname',
    //           'middlename',
    //           'suffix',
    //           'sid',
    //           'strandname',
    //           'strandid',
    //           'gradelevel.sortid',
    //           'studstatus',
    //           'courseid',
    //           'studinfo.id as studid',
    //           'studisactive',
    //           'studledger.id',
    //           DB::raw('COALESCE(SUM(studledger.amount),0) AS ledgeramount'),
    //           DB::raw('COALESCE(SUM(studledger.payment),0) AS ledgerpayment'),
    //           DB::raw('COALESCE(SUM(studledger.amount),0) - COALESCE(SUM(studledger.payment),0) as ledgerbalance'),
    //           'sy.sydesc'
    //     )
    //     ->groupBy('studledger.studid')
    //     ->get();

        
    //     $student_count = DB::table('studinfo')
    //     ->join('gradelevel',function($join) use($all_acad){
    //           $join->on('studinfo.levelid','=','gradelevel.id');
    //           $join->where('gradelevel.deleted',0);
    //           $join->whereIn('gradelevel.acadprogid',$all_acad);
    //     })
    //     ->where('studinfo.deleted',0);

    //     if($search != null){
    //     $student_count = $student_count->where(function($query) use($search){
    //             $query->orWhere('firstname','like','%'.$search.'%');
    //             $query->orWhere('lastname','like','%'.$search.'%');
    //             $query->orWhere('sid','like','%'.$search.'%');
    //     });
    //     }

    //     if($activestatus != null && $activestatus != ""){
    //     $student_count  =  $student_count->where('studinfo.studisactive',$activestatus);
    //     }

    //     if($studstat != 0 && $studstat != ''){
    //     $student_count = $student_count->whereIn('studinfo.id',$student_array);
    //     }else if($studstat == 0 && $studstat != ''){
    //     $student_count = $student_count->whereNotIn('studinfo.id',$student_array);
    //     }

    //     if($processtype != ""){
    //     $student_count  =  $student_count->whereIn('studinfo.id',collect($preenrolled)->pluck('studid'));
    //     }

    //     if($paystat != "" && $paystat != null && ($studstat == 0 || $studstat == '')){
    //     if($paystat == 1){
    //     $student_count  =  $student_count->whereIn('studinfo.id',collect($cashtrans)->pluck('studid'));
    //     }else if($paystat == 2){
    //     $student_count  =  $student_count->whereNotIn('studinfo.id',collect($cashtrans)->pluck('studid'));
    //     }
    //     }

    //     if($fillevelid != 0 && $fillevelid != '' &&  ($studstat == 0 || $studstat == '')){
    //     $student_count  =  $student_count->where('studinfo.levelid',$fillevelid);
    //     }

    //     $student_count = $student_count->count();

    //     // return $all_students;
    //     return @json_encode((object)[
    //         'data'=>$all_students,
    //         'recordsTotal'=>$student_count,
    //         'recordsFiltered'=>$student_count
    //   ]);
    // }


    public static function get_old_acounts_from_sy(Request $request){
        $syid = $request->get('syid');
        $studstat = $request->get('studstat');
        $paystat = $request->get('paystat');
        $activestatus = $request->get('activestatus');
        $fillevelid = $request->get('fillevelid');
        $fillsectionid = $request->get('fillsectionid');

        $semid = $request->get('semid');
        $ghssemid = $semid == 3 ? [$semid] : [1,2];
        $all_acad = self::get_acad($syid);
        $schoolinfo = DB::table('schoolinfo')->first();
        $all_enrolledstudents = array();

        $search = $request->get('search');
        // $search = $search['value'];
        $search = isset($search['value']) ? $search['value'] : null;

        $transdate = $request->get('transdate');
        $processtype = $request->get('procctype');
        $enrollmentdate = $request->get('enrollmentdate');

        $levelacad = null;
        if($fillevelid != 0 && $fillevelid != ''){
              $levelacad = DB::table('gradelevel')
                                ->where('id',$fillevelid)
                                ->first()
                                ->acadprogid;
        }



        if($processtype != ""){

              $basiced = DB::table('student_pregistration')
                                ->where('student_pregistration.syid',$syid)
                                ->join('gradelevel',function($join){
                                      $join->on('student_pregistration.gradelvl_to_enroll','=','gradelevel.id');
                                      $join->where('gradelevel.deleted',0);
                                      $join->whereIn('acadprogid',[2,3,4]);
                                })
                                ->join('early_enrollment_setup',function($join) use($processtype){
                                      $join->on('student_pregistration.admission_type','=','early_enrollment_setup.id');
                                      $join->where('early_enrollment_setup.deleted',0);
                                      $join->where('early_enrollment_setup.type',$processtype);
                                })
                                ->select('studid')
                                ->where('student_pregistration.deleted',0)
                                ->get();


              $withsem = DB::table('student_pregistration')
                                ->where('student_pregistration.syid',$syid)
                                ->where('student_pregistration.semid',$semid)
                                ->join('gradelevel',function($join){
                                      $join->on('student_pregistration.gradelvl_to_enroll','=','gradelevel.id');
                                      $join->where('gradelevel.deleted',0);
                                      $join->whereIn('acadprogid',[5,6]);
                                })
                                ->join('early_enrollment_setup',function($join) use($processtype){
                                      $join->on('student_pregistration.admission_type','=','early_enrollment_setup.id');
                                      $join->where('early_enrollment_setup.deleted',0);
                                      $join->where('early_enrollment_setup.type',$processtype);
                                })
                                ->select('studid')
                                ->where('student_pregistration.deleted',0)
                                ->get();

              $preenrolled = $basiced->merge($withsem);

        }

        $all_gradelevel = DB::table('gradelevel')
                                ->whereIn('acadprogid',$all_acad)
                                ->where('deleted',0)
                                ->select(
                                      'acadprogid',
                                      'levelname',
                                      'id',
                                      'nodp'
                                )
                                ->get();

        $enrolled = DB::table('enrolledstud')
                          ->join('studentstatus',function($join){
                                $join->on('enrolledstud.studstatus','=','studentstatus.id');
                          })
                          ->join('sections',function($join){
                                $join->on('enrolledstud.sectionid','=','sections.id');
                                $join->where('sections.deleted',0);
                          });

        if($studstat != 0 && $studstat != ''){
              $enrolled  =  $enrolled->where('studstatus',$studstat);

              if($transdate != 0 && $transdate != ''){
                    $temptransdate = explode(' - ',$transdate);
                    $startdate = \Carbon\Carbon::create($temptransdate[0].' 00:00')->isoFormat('YYYY-MM-DD');
                    $enddate = \Carbon\Carbon::create($temptransdate[1].' 24:00')->isoFormat('YYYY-MM-DD');
                    $enrolled  =  $enrolled->whereBetween('enrolledstud.createddatetime', [$startdate,$enddate]);
              }

              if($enrollmentdate != 0 && $enrollmentdate != ''){
                    $temptransdate = explode(' - ',$enrollmentdate);
                    $startdate = \Carbon\Carbon::create($temptransdate[0])->isoFormat('YYYY-MM-DD');
                    $enddate = \Carbon\Carbon::create($temptransdate[1])->isoFormat('YYYY-MM-DD');
                    $enrolled  =  $enrolled->whereBetween('enrolledstud.dateenrolled', [$startdate,$enddate]);
              }


        }

        if($fillevelid != 0 && $fillevelid != '' ){
              $enrolled  =  $enrolled->where('enrolledstud.levelid',$fillevelid);
        }

        if($processtype != ""){
              $enrolled  =  $enrolled->whereIn('enrolledstud.studid',collect($preenrolled)->pluck('studid'));
        }

        if($fillsectionid != 0 && $fillsectionid != ''){
              if($levelacad == 2 || $levelacad == 3 || $levelacad == 4){
                    $enrolled  =  $enrolled->where('enrolledstud.sectionid',$fillsectionid);
              }
        }

        $enrolled = $enrolled->join('gradelevel',function($join) use($all_acad){
                                $join->on('enrolledstud.levelid','=','gradelevel.id');
                                $join->where('gradelevel.deleted',0);
                                $join->whereIn('gradelevel.acadprogid',$all_acad);
                          })
                          ->leftJoin('early_enrollment_setup',function($join){
                                $join->on('enrolledstud.admissiontype','=','early_enrollment_setup.id');
                                $join->where('early_enrollment_setup.deleted',0);
                          })

                          ->leftJoin('studledger',function($join){
                                $join->on('enrolledstud.studid','=','studledger.studid');
                                $join->where('studledger.deleted',0);
                          })

                          ->where('enrolledstud.syid',$syid)
                          ->whereIn('ghssemid',$ghssemid)
                          ->where('enrolledstud.deleted',0)
                          ->select(
                                'dateenrolled',
                                'enrolledstud.levelid',
                                'gradelevel.levelname',
                                'enrolledstud.studid',
                                'sectionid',
                                'description',
                                'studstatus',
                                'studstatdate',
                                'sectionname',
                                // 'isearly',
                                'sortid',
                                'remarks',
                                'promotionstatus',
                                'studmol',
                                'early_enrollment_setup.type',
                                'studledger.studid',
                                'studledger.amount',
                                'studledger.payment'

                          )
                          // ->distinct('studid')
                          ->groupBy('enrolledstud.studid')
                          ->get();

        foreach($enrolled as $item){
              array_push($all_enrolledstudents,$item);
        }


        $enrolled = DB::table('sh_enrolledstud')
                          ->join('studentstatus',function($join){
                                $join->on('sh_enrolledstud.studstatus','=','studentstatus.id');
                          });

        if($studstat != 0 && $studstat != ''){
              $enrolled  =  $enrolled->where('studstatus',$studstat);

              if($transdate != 0 && $transdate != ''){
                    $temptransdate = explode(' - ',$transdate);
                    $startdate = \Carbon\Carbon::create($temptransdate[0].' 00:00')->isoFormat('YYYY-MM-DD');
                    $enddate = \Carbon\Carbon::create($temptransdate[1].' 24:00')->isoFormat('YYYY-MM-DD');
                    $enrolled  =  $enrolled->whereBetween('sh_enrolledstud.createddatetime', [$startdate,$enddate]);
              }

              if($enrollmentdate != 0 && $enrollmentdate != ''){
                    $temptransdate = explode(' - ',$enrollmentdate);
                    $startdate = \Carbon\Carbon::create($temptransdate[0])->isoFormat('YYYY-MM-DD');
                    $enddate = \Carbon\Carbon::create($temptransdate[1])->isoFormat('YYYY-MM-DD');
                    $enrolled  =  $enrolled->whereBetween('sh_enrolledstud.dateenrolled', [$startdate,$enddate]);
              }
        }

        if($fillevelid != 0 && $fillevelid != '' ){
              $enrolled  =  $enrolled->where('sh_enrolledstud.levelid',$fillevelid);
        }

        if($processtype != ""){
              $enrolled  =  $enrolled->whereIn('sh_enrolledstud.studid',collect($preenrolled)->pluck('studid'));
        }

        if($fillsectionid != 0 && $fillsectionid != ''){
              if($levelacad == 5){
                    $enrolled  =  $enrolled->where('sh_enrolledstud.sectionid',$fillsectionid);
              }
        }


        $enrolled = $enrolled->join('sections',function($join){
                                $join->on('sh_enrolledstud.sectionid','=','sections.id');
                                $join->where('sections.deleted',0);
                          })
                          ->join('gradelevel',function($join) use($all_acad){
                                $join->on('sh_enrolledstud.levelid','=','gradelevel.id');
                                $join->where('gradelevel.deleted',0);
                                $join->whereIn('gradelevel.acadprogid',$all_acad);
                          })
                          ->leftJoin('early_enrollment_setup',function($join){
                                $join->on('sh_enrolledstud.admissiontype','=','early_enrollment_setup.id');
                                $join->where('early_enrollment_setup.deleted',0);
                          })
                          ->leftJoin('sh_strand',function($join) use($all_acad){
                                $join->on('sh_enrolledstud.strandid','=','sh_strand.id');
                                $join->where('sh_strand.deleted',0);
                          })

                          ->leftJoin('studledger',function($join){
                                $join->on('sh_enrolledstud.studid','=','studledger.studid');
                                $join->where('studledger.deleted',0);
                          })

                          ->where('sh_enrolledstud.syid',$syid)
                          ->where('sh_enrolledstud.semid',$semid)
                          ->where('sh_enrolledstud.deleted',0)
                          ->select(
                                'dateenrolled',
                                'sh_enrolledstud.levelid',
                                'gradelevel.levelname',
                                'sectionid',
                                'sh_enrolledstud.studid',
                                'description',
                                'studstatus',
                                'sectionname',
                                'studstatdate',
                                // 'isearly',
                                'sortid',
                                'strandid',
                                'promotionstatus',
                                'studmol',
                                'remarks',
                                'early_enrollment_setup.type',
                                'strandcode',
                                'studledger.studid',
                                'studledger.amount',
                                'studledger.payment'
                          )
                          // ->distinct('studid')
                          ->groupBy('sh_enrolledstud.studid')
                          ->get();

        foreach($enrolled as $item){
              array_push($all_enrolledstudents,$item);
        }

        //mao nani dapit college sections

        $enrolled = DB::table('college_enrolledstud')
        ->when(isset($fillsectionid) && $fillsectionid != 0 && $fillsectionid != '', function($query) use($fillsectionid){
              return $query->where('college_enrolledstud.sectionID',$fillsectionid);

        })
        ->join('studentstatus',function($join){
              $join->on('college_enrolledstud.studstatus','=','studentstatus.id');
        })
        ->leftJoin('college_sections',function($join){
              $join->on('college_enrolledstud.sectionID','=','college_sections.id');
              $join->where('college_sections.deleted',0);
        })
        ->leftJoin('college_classsched',function($join){
              $join->on('college_sections.id','=','college_classsched.sectionid');
              $join->where('college_classsched.deleted',0);
        });

        if($studstat != 0 && $studstat != ''){
              $enrolled  =  $enrolled->where('studstatus',$studstat);
              if($transdate != 0 && $transdate != ''){
                    $temptransdate = explode(' - ',$transdate);
                    $startdate = \Carbon\Carbon::create($temptransdate[0].' 00:00')->isoFormat('YYYY-MM-DD');
                    $enddate = \Carbon\Carbon::create($temptransdate[1].' 24:00')->isoFormat('YYYY-MM-DD');
                    $enrolled  =  $enrolled->whereBetween('college_enrolledstud.createddatetime', [$startdate,$enddate]);
              }

              if($enrollmentdate != 0 && $enrollmentdate != ''){
                    $temptransdate = explode(' - ',$enrollmentdate);
                    $startdate = \Carbon\Carbon::create($temptransdate[0])->isoFormat('YYYY-MM-DD');
                    $enddate = \Carbon\Carbon::create($temptransdate[1])->isoFormat('YYYY-MM-DD');
                    $enrolled  =  $enrolled->whereBetween('college_enrolledstud.date_enrolled', [$startdate,$enddate]);
              }
        }

        if($fillevelid != 0 && $fillevelid != '' ){
              $enrolled  =  $enrolled->where('college_enrolledstud.yearLevel',$fillevelid);
        }

        if($processtype != ""){
              $enrolled  =  $enrolled->whereIn('college_enrolledstud.studid',collect($preenrolled)->pluck('studid'));
        }

        if($fillsectionid != 0 && $fillsectionid != ''){
              if($levelacad == 5 || $levelacad == 6){
                    $enrolled  =  $enrolled->where('college_enrolledstud.sectionID',$fillsectionid);
              }
        }

        $enrolled  =  $enrolled->join('gradelevel',function($join) use($all_acad){
                                $join->on('college_enrolledstud.yearLevel','=','gradelevel.id');
                                $join->where('gradelevel.deleted',0);
                                $join->whereIn('gradelevel.acadprogid',$all_acad);
                          })
                          ->leftJoin('early_enrollment_setup',function($join){
                                $join->on('college_enrolledstud.admissiontype','=','early_enrollment_setup.id');
                                $join->where('early_enrollment_setup.deleted',0);
                          })

                          
                          ->leftJoin('studledger',function($join){
                                $join->on('college_enrolledstud.studid','=','studledger.studid');
                                $join->where('studledger.deleted',0);
                          })


                          ->where('college_enrolledstud.syid',$syid)
                          ->where('college_enrolledstud.semid',$semid)
                          ->where('college_enrolledstud.deleted',0)
                          ->select(
                                'college_enrolledstud.date_enrolled as dateenrolled',
                                'college_enrolledstud.yearLevel as levelid',
                                'college_enrolledstud.sectionID as sectionid',
                                'gradelevel.levelname',
                                'college_enrolledstud.studid',
                                'description',
                                'studstatus',
                                'sectionDesc as sectionname',
                                // 'studstatdate',
                                // 'isearly',
                                'remarks',
                                'sortid',
                                'promotionstatus',
                                'studmol',
                                'early_enrollment_setup.type',
                                'college_enrolledstud.courseid',
                                'regStatus',
                                'college_classsched.id as schedid',
                                'studledger.studid',
                                'studledger.amount',
                                'studledger.payment'
                          )
                          // ->distinct('studid')
                          ->groupBy('college_enrolledstud.studid')
                          ->get();
                          

        if(count($enrolled) > 0){

              $collegesection = DB::table('college_schedgroup_detail')
                                ->where('college_schedgroup_detail.deleted',0)
                                ->whereIn('schedid',collect($enrolled)->pluck('schedid'))
                                ->join('college_schedgroup',function($join){
                                      $join->on('college_schedgroup_detail.groupid','=','college_schedgroup.id');
                                      $join->where('college_schedgroup.deleted',0);
                                })
                                ->leftJoin('college_courses',function($join){
                                      $join->on('college_schedgroup.courseid','=','college_courses.id');
                                      $join->where('college_courses.deleted',0);
                                })
                                ->leftJoin('gradelevel',function($join){
                                      $join->on('college_schedgroup.levelid','=','gradelevel.id');
                                      $join->where('gradelevel.deleted',0);
                                })
                                ->leftJoin('college_colleges',function($join){
                                      $join->on('college_schedgroup.collegeid','=','college_colleges.id');
                                      $join->where('college_colleges.deleted',0);
                                })
                                
                                ->select(
                                      'college_schedgroup.courseid',
                                      'college_schedgroup.levelid',
                                      'college_schedgroup.collegeid',
                                      'courseDesc',
                                      'collegeDesc',
                                      'levelname',
                                      'courseabrv',
                                      'collegeabrv',
                                      'college_schedgroup.id',
                                      'college_schedgroup.schedgroupdesc',
                                      'schedgroupdesc as text',
                                      'schedid'
                                )
                                ->get();

                    foreach($enrolled as $item){
                          $courseid = $item->courseid;
                          $checkcoursegroup = collect($collegesection)->where('schedid',$item->schedid)->where('courseid',$courseid)->values();
                          if(count($checkcoursegroup) > 0){
                                $text = $checkcoursegroup[0]->courseabrv;
                                $text .= '-'.$checkcoursegroup[0]->levelname[0] . ' '.$checkcoursegroup[0]->schedgroupdesc;
                                $item->sectionname = $text;
                          }else{
                                $collegeid = DB::table('college_courses')
                                                  ->where('id',$courseid)
                                                  ->select('collegeid')
                                                  ->first();
                                if(isset($collegeid)){
                                      $checkcoursegroup = collect($collegesection)->where('schedid',$item->schedid)->where('collegeid',$collegeid->collegeid)->values();
                                      if(count($checkcoursegroup) > 0){
                                            $text = $checkcoursegroup[0]->collegeabrv;
                                            $text .= '-'.$checkcoursegroup[0]->levelname[0] . ' '.$checkcoursegroup[0]->schedgroupdesc;
                                            $item->sectionname = $text;
                                      }else{
                                            $item->sectionname = 'Section Not Found';
                                      }
                                }else{
                                      $item->sectionname = 'Course Not Found';
                                }
                          }
                    }
        }


        // Iterate through each enrolled student
        foreach($enrolled as $item){
              // If the section is not found, default to 'Section Information Unavailable'
              $item->sectionname = DB::table('college_sections')
                                      ->where('id', $item->sectionid)
                                      ->value('sectionDesc') ?? 'Section Information Unavailable';

        }

        // Fallback for section name if it's still not set
        // foreach($enrolled as $item){
        //       if(!isset($item->sectionname) || in_array($item->sectionname, ['Section Not Found', 'Course Not Found'])){
        //             $item->sectionname = DB::table('college_sections')
        //                                    ->where('id', $item->sectionid)
        //                                    ->value('sectionDesc') ?? 'Section Information Unavailable';
        //       }
        // }

        // foreach($enrolled as $item){
        //       if(!isset($item->sectionname) || $item->sectionname == 'Section Not Found' || $item->sectionname == 'Course Not Found'){
        //             // Fetch section information directly from college_sections table
        //             $sectionInfo = DB::table('college_sections')
        //                              ->where('id', $item->sectionid)
        //                              ->select('sectionDesc')
        //                              ->first();
        //             // Set sectionname to sectionDesc if available, otherwise use a default message
        //             $item->sectionname = $sectionInfo ? $sectionInfo->sectionDesc : 'Section Information Unavailable';
        //       }
        // }

        foreach($enrolled as $item){
              array_push($all_enrolledstudents,$item);
        }

        $student_array = collect($all_enrolledstudents)->pluck('studid');


        $gshs_levelid = collect($all_gradelevel)->whereIn('acadprogid',[2,3,4])->values();
        $sh_levelid = collect($all_gradelevel)->whereIn('acadprogid',[5])->values();
        $college_levelid = collect($all_gradelevel)->whereIn('acadprogid',[6])->values();
        $shs_semid = [$semid];

        if($schoolinfo->shssetup == 1){
              $shs_semid = $semid != 3 ? [1,2] : [$semid];
        }

        $chrng_students = array();


        if($paystat != "" && $paystat != null && ($studstat == 0 || $studstat == '')){

              $gshs_chrngtrans = DB::table('chrngtrans');

              if($studstat != 0 && $studstat != ''){
                    $gshs_chrngtrans = $gshs_chrngtrans->whereIn('chrngtrans.studid',$student_array);
              }else if($studstat == 0 && $studstat != ''){
                    $gshs_chrngtrans = $gshs_chrngtrans->whereNotIn('chrngtrans.studid',$student_array);
              }

              $gshs_chrngtrans = $gshs_chrngtrans->join('studinfo',function($join){
                                      $join->on('chrngtrans.studid','=','studinfo.id');
                                      $join->where('studinfo.deleted',0);
                                })
                                ->join('tuitionheader',function($join) use($gshs_levelid){
                                      $join->on('studinfo.feesid','=','tuitionheader.id');
                                      $join->where('tuitionheader.deleted',0);
                                      $join->whereIn('tuitionheader.levelid',collect($gshs_levelid)->pluck('id'));
                                })
                                ->where('chrngtrans.syid',$syid)
                                ->where('cancelled',0)
                                ->whereIn('chrngtrans.semid',$ghssemid)
                                ->select(
                                      'transno'
                                )
                                ->get();



              $college_chrngtrans = DB::table('chrngtrans');

              if($studstat != 0 && $studstat != ''){
                    $college_chrngtrans = $college_chrngtrans->whereIn('chrngtrans.studid',$student_array);
              }else if($studstat == 0 && $studstat != ''){
                    $college_chrngtrans = $college_chrngtrans->whereNotIn('chrngtrans.studid',$student_array);
              }

              $college_chrngtrans = $college_chrngtrans->join('studinfo',function($join){
                                            $join->on('chrngtrans.studid','=','studinfo.id');
                                            $join->where('studinfo.deleted',0);
                                      })
                                      ->join('tuitionheader',function($join) use($college_levelid){
                                            $join->on('studinfo.feesid','=','tuitionheader.id');
                                            $join->where('tuitionheader.deleted',0);
                                            $join->whereIn('tuitionheader.levelid',collect($college_levelid)->pluck('id'));
                                      })
                                      ->where('chrngtrans.syid',$syid)
                                      ->where('cancelled',0)
                                      ->where('chrngtrans.semid',$semid)
                                      ->select(
                                            'studid',
                                            'transno'
                                      )
                                      ->get();



              $sh_chrngtrans = DB::table('chrngtrans')
                                      ->join('studinfo',function($join){
                                            $join->on('chrngtrans.studid','=','studinfo.id');
                                            $join->where('studinfo.deleted',0);
                                      });

              if($studstat != 0 && $studstat != ''){
                    $sh_chrngtrans = $sh_chrngtrans->whereIn('chrngtrans.studid',$student_array);
              }else if($studstat == 0 && $studstat != ''){
                    $sh_chrngtrans = $sh_chrngtrans->whereNotIn('chrngtrans.studid',$student_array);
              }

              $sh_chrngtrans = $sh_chrngtrans->join('tuitionheader',function($join) use($sh_levelid){
                                            $join->on('studinfo.feesid','=','tuitionheader.id');
                                            $join->where('tuitionheader.deleted',0);
                                            $join->whereIn('tuitionheader.levelid',collect($sh_levelid)->pluck('id'));
                                      })
                                      ->where('chrngtrans.syid',$syid)
                                      ->where('cancelled',0)
                                      ->where('chrngtrans.semid',$shs_semid)
                                      ->select(
                                            'transno'
                                      )
                                      ->get();

              $data = $gshs_chrngtrans->merge($sh_chrngtrans);
              $data = $data->merge($college_chrngtrans);

              $cashtrans = DB::table('chrngcashtrans')
                                ->where('syid',$syid)
                                ->WhereIn ('transno',collect($data)->pluck('transno'))
                                ->where('deleted',0)
                                ->where(function($query){
                                      $query->where('kind','!=','item');
                                      $query->where('kind','!=','old');
                                })
                                ->groupBy('studid')
                                ->select(
                                      'studid'
                                )
                                ->get();


              //filter no dp
              $no_dp_gshs = DB::table('student_allownodp')
                                ->where('syid',$syid)
                                ->where('semid',$ghssemid)
                                ->select(
                                      'studid'
                                )
                                ->where('deleted',0)
                                ->get();

              $no_dp_sh = DB::table('student_allownodp')
                                ->where('syid',$syid)
                                ->where('semid',$shs_semid)
                                ->where('deleted',0)
                                ->select(
                                      'studid'
                                )
                                ->get();

              $no_dp_college = DB::table('student_allownodp')
                                ->where('syid',$syid)
                                ->where('semid',$semid)
                                ->where('deleted',0)
                                ->select(
                                      'studid'
                                )
                                ->get();

              $no_dp_data = $no_dp_gshs->merge($no_dp_sh);
              $no_dp_data = $no_dp_data->merge($no_dp_college);


              //merge cashtrans and no dp
              $cashtrans = $cashtrans->merge($no_dp_data);

        }


        $all_students = DB::table('studinfo')
                          ->where('studinfo.deleted',0)
                          ->join('gradelevel',function($join) use($all_acad){
                                $join->on('studinfo.levelid','=','gradelevel.id');
                                $join->where('gradelevel.deleted',0);
                                $join->whereIn('gradelevel.acadprogid',$all_acad);
                          })
                          ->leftJoin('sh_strand',function($join){
                                $join->on('studinfo.strandid','=','sh_strand.id');
                                $join->where('sh_strand.deleted',0);
                          })->leftJoin('college_courses',function($join){
                                $join->on('studinfo.courseid','=','college_courses.id');
                                $join->where('college_courses.deleted',0);
                          })

                          ->leftJoin('studledger',function($join) use($syid,$semid){
                                $join->on('studinfo.id','=','studledger.studid');
                                $join->where('studledger.deleted',0);
                                $join->where('studledger.syid',$syid);
                                $join->where('studledger.semid',$semid);
                          })
                          ->leftJoin('sy',function($join){
                                $join->on('studledger.syid','=','sy.id');
                                });


        if($search != null){
              $all_students = $all_students->where(function($query) use($search){
                                $query->orWhere('firstname','like','%'.$search.'%');
                                $query->orWhere('lastname','like','%'.$search.'%');
                                $query->orWhere('sid','like','%'.$search.'%');
                          });
        }



        if($studstat != 0 && $studstat != ''){
              $all_students = $all_students->whereIn('studinfo.id',$student_array);
        }else if($studstat == 0 && $studstat != ''){
              $all_students = $all_students->whereNotIn('studinfo.id',$student_array);
        }

        if($fillevelid != 0 && $fillevelid != '' &&  ($studstat == 0 || $studstat == '')){
              $all_students  =  $all_students->where('studinfo.levelid',$fillevelid);
        }

        if($processtype != ""){
              $all_students  =  $all_students->whereIn('studinfo.id',collect($preenrolled)->pluck('studid'));
        }

        if($activestatus != null && $activestatus != ""){
              $all_students  =  $all_students->where('studinfo.studisactive',$activestatus);
        }

        if($paystat != "" && $paystat != null && ($studstat == 0 || $studstat == '') ){
              if($paystat == 1){
                    $all_students  =  $all_students->whereIn('studinfo.id',collect($cashtrans)->pluck('studid'));
              }else if($paystat == 2){
                    $all_students  =  $all_students->whereNotIn('studinfo.id',collect($cashtrans)->pluck('studid'));
              }
        }

        if ($request->get('actiontype') == "exportidprint" || $request->get('exporttype') == "exportOldAccounts") {
              $all_students = $all_students
                    ->orderBy('lastname')
                    ->select(
                          'collegeid',
                          'contactno',
                          'ismothernum',
                          'isfathernum',
                          'isguardannum',
                          'mcontactno',
                          'fcontactno',
                          'gcontactno',
                          'mothername',
                          'fathername',
                          'guardianname',
                          'gradelevel.levelname',
                          'gradelevel.levelname as curlevelname',
                          'studinfo.levelid as curlevelid',
                          'studinfo.levelid',
                          'studinfo.sectionid',
                          'studinfo.grantee',
                          'studinfo.pantawid',
                          'studinfo.mol',
                          'lastname',
                          'firstname',
                          'middlename',
                          'suffix',
                          'sid',
                          'strandname',
                          'strandid',
                          'gradelevel.sortid',
                          'studstatus',
                          'courseid',
                          'studinfo.id as studid',
                          'studisactive',
                          DB::raw('COALESCE(SUM(studledger.amount),0) AS ledgeramount'),
                          DB::raw('COALESCE(SUM(studledger.payment),0) AS ledgerpayment'),
                          DB::raw('COALESCE(SUM(studledger.amount),0) - COALESCE(SUM(studledger.payment),0) as ledgerbalance'),
                          'sy.sydesc'
                          
                    )
                    ->having('ledgerbalance', '>', 0)
                    ->groupBy('studinfo.id')
                    ->get();

        }
        

        else {

            try {
                //code...
                $all_students = $all_students->take($request->get('length'))
                  ->skip($request->get('start'))
                  ->orderBy('lastname')
                  ->select([
                        'collegeid',
                        'contactno',
                        'ismothernum',
                        'isfathernum',
                        'isguardannum',
                        'mcontactno',
                        'fcontactno',
                        'gcontactno',
                        'mothername',
                        'fathername',
                        'guardianname',
                        'gradelevel.levelname',
                        'gradelevel.levelname as curlevelname',
                        'studinfo.levelid as curlevelid',
                        'studinfo.levelid',
                        'studinfo.sectionid',
                        'studinfo.grantee',
                        'studinfo.pantawid',
                        'studinfo.mol',
                        'lastname',
                        'firstname',
                        'middlename',
                        'suffix',
                        'sid',
                        'strandname',
                        'strandid',
                        'gradelevel.sortid',
                        'studstatus',
                        'courseid',
                        'studinfo.id as studid',
                        'studisactive',
                        'sy.sydesc'
                  ])
                  ->groupBy([
                      'collegeid',
                      'contactno',
                      'ismothernum',
                      'isfathernum',
                      'isguardannum',
                      'mcontactno',
                      'fcontactno',
                      'gcontactno',
                      'mothername',
                      'fathername',
                      'guardianname',
                      'gradelevel.levelname',
                      'curlevelname',
                      'curlevelid',
                      'studinfo.levelid',
                      'studinfo.sectionid',
                      'studinfo.grantee',
                      'studinfo.pantawid',
                      'studinfo.mol',
                      'lastname',
                      'firstname',
                      'middlename',
                      'suffix',
                      'sid',
                      'strandname',
                      'strandid',
                      'gradelevel.sortid',
                      'studstatus',
                      'courseid',
                      'studid',
                      'studisactive',
                      'sy.sydesc'
                  ]);
    
    
                $all_students = $all_students
                  ->selectRaw('COALESCE(SUM(studledger.amount),0) as ledgeramount')
                  ->selectRaw('COALESCE(SUM(studledger.payment),0) as ledgerpayment')
                  ->selectRaw('COALESCE(SUM(studledger.amount),0) - COALESCE(SUM(studledger.payment),0) as ledgerbalance')
                  ->having('ledgerbalance', '>', 0)
                  ->get();
            } catch (\Throwable $th) {
                //throw $th;
                dd($th);
            }
                
      }






        $student_count = DB::table('studinfo')
                                ->join('gradelevel',function($join) use($all_acad){
                                      $join->on('studinfo.levelid','=','gradelevel.id');
                                      $join->where('gradelevel.deleted',0);
                                      $join->whereIn('gradelevel.acadprogid',$all_acad);
                                })
                                ->where('studinfo.deleted',0);

        if($search != null){
              $student_count = $student_count->where(function($query) use($search){
                                $query->orWhere('firstname','like','%'.$search.'%');
                                $query->orWhere('lastname','like','%'.$search.'%');
                                $query->orWhere('sid','like','%'.$search.'%');
                          });
        }

        if($activestatus != null && $activestatus != ""){
              $student_count  =  $student_count->where('studinfo.studisactive',$activestatus);
        }

        if($studstat != 0 && $studstat != ''){
              $student_count = $student_count->whereIn('studinfo.id',$student_array);
        }else if($studstat == 0 && $studstat != ''){
              $student_count = $student_count->whereNotIn('studinfo.id',$student_array);
        }

        if($processtype != ""){
              $student_count  =  $student_count->whereIn('studinfo.id',collect($preenrolled)->pluck('studid'));
        }

        if($paystat != "" && $paystat != null && ($studstat == 0 || $studstat == '')){
              if($paystat == 1){
                    $student_count  =  $student_count->whereIn('studinfo.id',collect($cashtrans)->pluck('studid'));
              }else if($paystat == 2){
                    $student_count  =  $student_count->whereNotIn('studinfo.id',collect($cashtrans)->pluck('studid'));
              }
        }

        if($fillevelid != 0 && $fillevelid != '' &&  ($studstat == 0 || $studstat == '')){
              $student_count  =  $student_count->where('studinfo.levelid',$fillevelid);
        }

        $student_count = $student_count->count();

        $students = DB::table('student_pregistration')
                          ->join('early_enrollment_setup',function($join){
                                $join->on('student_pregistration.admission_type','=','early_enrollment_setup.id');
                                $join->where('early_enrollment_setup.deleted',0);
                          })
                          ->join('early_enrollment_setup_type',function($join){
                                $join->on('early_enrollment_setup.type','=','early_enrollment_setup_type.id');
                                $join->where('early_enrollment_setup_type.deleted',0);
                          })
                          ->whereNotNull('student_pregistration.admission_type')
                          ->join('gradelevel as lvltoenroll',function($join) use($all_acad){
                                $join->on('student_pregistration.gradelvl_to_enroll','=','lvltoenroll.id');
                                $join->whereIn('lvltoenroll.acadprogid',$all_acad);
                          })
                          ->whereIn('studid',collect($all_students)->pluck('studid'))
                          ->select(
                                'gradelvl_to_enroll',
                                'student_pregistration.studid',
                                'student_pregistration.status',
                                'admission_type',
                                'description as admission_type_desc',
                                'type',
                                'student_pregistration.id',
                                'lvltoenroll.levelname as lvltoenrollname',
                                'student_pregistration.id',
                                'student_pregistration.syid',
                                'student_pregistration.semid',
                                'student_pregistration.createddatetime',
                                'finance_status',
                                'finance_statusdatetime',
                                'admission_status',
                                'admission_statusdatetime',
                                'admission_strand',
                                'admission_course',
                                'allownodp',
                                'transtype'
                          )
                          ->distinct('studid')
                          ->where('student_pregistration.syid',$syid)
                          ->where('student_pregistration.semid',$semid)
                          ->where('student_pregistration.deleted',0)
                          ->get();





        $modeoflearning = DB::table('modeoflearning_student')
                                ->where('deleted',0)
                                ->whereIn('studid',collect($all_students)->pluck('studid'))
                                ->where('syid',$syid)
                                ->select(
                                      'studid',
                                      'mol'
                                )
                                ->get();




        $school_info = DB::table('schoolinfo')->select('abbreviation')->first();

        if($school_info->abbreviation == 'BCT'){
              $payment_info = db::table('chrngtrans')
                                ->select('chrngtrans.id', 'chrngtransdetail.amount', 'items','studid','semid')
                                ->join('chrngtransdetail', 'chrngtrans.id', '=', 'chrngtransdetail.chrngtransid')
                                ->where('cancelled', 0)
                                ->where('classid', 106)
                                ->get();
        }else if($school_info->abbreviation == 'VNBC' || $school_info->abbreviation == 'MAC' || $school_info->abbreviation == 'HCB' || $school_info->abbreviation == 'FMC MA SCH'){


              $chngtrans = DB::table('chrngtransdetail')
                                ->select('studid', 'chrngtrans.syid', 'chrngtrans.semid', 'itemkind', 'payschedid', 'items.isdp',db::raw('SUM(chrngtrans.`amountpaid`) AS amount'))
                                ->join('chrngtrans', 'chrngtransdetail.chrngtransid', '=', 'chrngtrans.id')
                                ->join('items', 'chrngtransdetail.payschedid', '=', 'items.id')
                                ->where('chrngtrans.syid', $syid)
                                ->where('chrngtrans.semid', $semid)
                                ->where('itemkind', 1)
                                ->where('items.isdp', 1)
                                ->where('cancelled', 0)
                                ->groupBy('studid')
                                ->get();




        }else if($school_info->abbreviation == 'SAIT'){

              $payment_info_sh = [];
        $payment_info_gshs = [];
        $payment_info_college = [];
              $chngtrans = [];

        }
        else if($school_info->abbreviation == 'HCCSI' ){
              $chngtrans = [];
              $cashtrans = [];




        }else{

              // if($paystat == "" || $paystat == null){

                    $chngtrans = DB::table('chrngtrans')
                                      ->where('syid',$syid)
                                      ->whereIn('studid',collect($all_students)->pluck('studid'))
                                      ->where('cancelled',0)
                                      ->select(
                                            'transno',
                                            'syid',
                                            'studid',
                                            'semid'
                                      )
                                      ->get();



                    $cashtrans = DB::table('chrngcashtrans')
                                      ->where('syid',$syid)
                                      ->where('deleted',0)
                                      ->whereIn('transno',collect($chngtrans)->pluck('transno'))
                                      ->where(function($query){
                                            $query->where('kind','!=','item');
                                            $query->where('kind','!=','old');
                                      })
                                      ->select(
                                            'transno',
                                            'syid',
                                            'studid',
                                            'amount',
                                            'semid'
                                      )
                                      ->get();


              // }

        }

        $enrollment_approval = DB::table('student_prereginfo')
                          ->whereIn('studid',collect($all_students)->pluck('studid'))
                          ->where('syid',$syid)
                          ->where('semid',$semid)
                          ->where('deleted',0)
                          ->select(
                                'finance_app',
                                'finance_appdatetime',
                                'admission_appdatetime',
                                'dean_appdatetime',
                                'dean_app',
                                'admission_app',
                                'studid',
                                'syid',
                                'semid'
                          )
                          ->get();

        $no_dp = DB::table('student_allownodp')
                          ->whereIn('studid',collect($all_students)->pluck('studid'))
                          ->where('syid',$syid)
                          ->where('deleted',0)
                          ->get();

        foreach($all_students as $item){
              $check_approval =  collect($enrollment_approval)->where('studid',$item->studid)->first();
              $enrollment_approval = collect($enrollment_approval)->where('studid','!=',$item->studid);
              if(isset($check_approval->studid)){
                    $item->finance_status = $check_approval->finance_app == 'PENDING' ? 'SUBMITTED' : 'APPROVED' ;
                    $item->finance_statusdatetime = $check_approval->finance_app == 'PENDING' ? null : $check_approval->finance_appdatetime;
                    $item->admission_status = $check_approval->admission_app == 'PENDING' ? 'SUBMITTED' : 'APPROVED';
                    $item->admission_statusdatetime = $check_approval->admission_app == 'PENDING' ? null : $check_approval->admission_appdatetime;
              }else{
                    $item->finance_status = 'SUBMITTED';
                    $item->finance_statusdatetime = null;
                    $item->admission_status = 'SUBMITTED';
                    $item->admission_statusdatetime = null;
              }


              $check_preg = collect($students)->where('studid',$item->studid)->first();

              if(isset($check_preg->studid)){
                    $item->withprereg = 1;
                    $item->gradelvl_to_enroll = $check_preg->gradelvl_to_enroll;
                    $item->status = $check_preg->status ;
                    $item->admission_type = $check_preg->admission_type;
                    $item->admission_type_desc = $check_preg->admission_type_desc;
                    $item->type = $check_preg->type;
                    $item->id = $check_preg->id;
                    $item->lvltoenrollname = $check_preg->lvltoenrollname;
                    $item->syid = $check_preg->syid;
                    $item->semid = $check_preg->semid;
                    $item->createddatetime = $check_preg->createddatetime;
                    $item->admission_strand = $check_preg->admission_strand;
                    $item->admission_course = $check_preg->admission_course;
                    $item->transtype = $check_preg->transtype;
              }else{
                    $item->withprereg = 0;
                    $item->gradelvl_to_enroll = null;
                    $item->status = 'SUBMITTED';
                    $item->admission_type = null;
                    $item->admission_type_desc = null;
                    $item->type = null;
                    $item->id = null;
                    $item->lvltoenrollname = null;
                    $item->syid = $syid;
                    $item->semid = $semid;
                    $item->createddatetime = null;
                    $item->admission_strand = $item->strandid;
                    $item->admission_course = $item->courseid;
                    $item->transtype = 'WALK IN';
              }

              $temp_middle = '';
              $temp_suffix = '';

              if(isset($item->middlename)){
                    // if(strlen($item->middlename) > 0){
                    //       $temp_middle = ' '.$item->middlename[0].'.';

                    // }
                    $temp_middle = ' '.$item->middlename;
              }
              if(isset($item->suffix)){
                    $temp_suffix = ' '.$item->suffix.' ';
              }

              $item->student = $item->lastname.', '.$item->firstname.$temp_suffix.$temp_middle;

              $item->submission = \Carbon\Carbon::create($item->createddatetime)->isoFormat('MMM DD, YYYY');

              //check enrollment
              $check = collect($all_enrolledstudents)->where('studid',$item->studid)->first();
              $item->strandcode = null;

              if(isset($check->studid)){

                    if($check->dateenrolled == null){
                          $item->dateenrolled = '';
                          $item->enrollment = '';
                    }else{
                          $item->dateenrolled = \Carbon\Carbon::create($check->dateenrolled)->isoFormat('YYYY-MM-DD');
                          $item->enrollment = \Carbon\Carbon::create($check->dateenrolled)->isoFormat('MMM DD, YYYY');
                    }
                    $item->mol = $check->studmol;
                    $item->ensectionid = $check->sectionid;
                    $item->enlevelid = $check->levelid;
                    $item->promotionstatus = $check->promotionstatus;
                    $item->description = $check->description;
                    $item->studstatus = $check->studstatus;

                    $item->sectionname = $check->sectionname;
                    $item->levelid = $check->levelid;
                    $item->levelname = $check->levelname;
                    $item->remarks = $check->remarks;
                    if($check->levelid == 14 || $check->levelid == 15){
                          $item->strandid = $check->strandid;
                          $item->strandcode = $check->strandcode;
                    }else{
                          $item->strandid = null;
                    }

                    if($check->levelid >= 17){
                          $item->courseid = $check->courseid;
                          $item->regStatus = $check->regStatus;
                          $item->studstatdate = null;
                    }else{
                          $item->courseid = null;
                          $item->regStatus = null;
                          $item->studstatdate = $check->studstatdate;
                    }
                    $item->type = $check->type;
                    $all_enrolledstudents = collect($all_enrolledstudents)
                                                  ->where('studid','!=',$check->studid)
                                                  ->values();

              }else{

                    $item->promotionstatus = 0;
                    $item->enrollment = '';
                    $item->description = 'NOT ENROLLED';
                    $item->sectionname = '--';
                    $item->studstatus = 0;
                    $item->remarks = null;
                    if($item->gradelvl_to_enroll != null && $item->gradelvl_to_enroll != ""){
                          $item->levelid = $item->gradelvl_to_enroll;
                          $item->levelname = $item->lvltoenrollname;
                    }
                    $check_mol = collect($modeoflearning)->where('studid',$item->studid)->first();
                    if(isset($check_mol->mol)){
                          $item->mol = $check_mol->mol;
                    }else{
                          $item->mol = $item->mol;
                    }
              }


              $item->levelname = str_replace(' COLLEGE','',$item->levelname);
              $item->curlevelname = str_replace(' COLLEGE','',$item->curlevelname);
              $item->search =  $item->sid.' - '.$item->student.' - '.$item->levelname.' - '.$item->sectionname;

              if(( $item->levelid >= 17 && $item->levelid <= 21 )){
                    // $check_payment = collect($payment_info_college)->where('studid',$item->studid)->where('semid',$semid)->values();
                    $temp_chrngrans = collect($chngtrans)
                                            ->where('studid',$item->studid)
                                            ->where('semid',$semid)
                                            ->values();

                    $check_nodp = collect($no_dp)->where('studid',$item->studid)->where('semid',$semid)->count();
                    $item->semid = $semid;
              }
              else if($item->levelid == 14 || $item->levelid == 15){
                    if($schoolinfo->shssetup == 1){
                          $temp_chrngrans = collect($chngtrans)
                                                  ->where('studid',$item->studid)
                                                  ->whereIn('semid',$shs_semid)
                                                  ->values();
                    }else{

                          if($school_info->abbreviation == 'VNBC' || $school_info->abbreviation == 'MAC' || $school_info->abbreviation == 'HCB' || $school_info->abbreviation == 'FMC MA SCH'){
                                $temp_chrngrans = collect($chngtrans)
                                                        ->where('studid',$item->studid)
                                                        ->where('semid',$semid)
                                                        ->values();
                          }else{
                                $temp_chrngrans = collect($chngtrans)
                                                        ->where('studid',$item->studid)
                                                        ->where('semid',$semid)
                                                        ->values();
                          }
                    }

                    $check_nodp = collect($no_dp)->where('studid',$item->studid)->where('semid',$semid)->count();
                    $item->semid = $semid;
              }else{
                    if($school_info->abbreviation == 'VNBC' || $school_info->abbreviation == 'MAC' || $school_info->abbreviation == 'HCB' || $school_info->abbreviation == 'FMC MA SCH'){
                          if($semid == 3){
                                $item->semid = 3;
                                $temp_chrngrans = collect($chngtrans)
                                                        ->where('studid',$item->studid)
                                                        ->whereIn('semid',[3])
                                                        ->values();
                          }else{
                                $item->semid = 1;
                                $temp_chrngrans = collect($chngtrans)
                                                  ->where('studid',$item->studid)
                                                  ->whereIn('semid',[1,2])
                                                  ->values();
                          }

                    }else{
                          if($semid == 3){
                                $item->semid = 3;
                                $temp_chrngrans = collect($chngtrans)
                                                        ->where('studid',$item->studid)
                                                        ->whereIn('semid',[3])
                                                        ->values();
                          }else{
                                $item->semid = 1;
                                $temp_chrngrans = collect($chngtrans)
                                                  ->where('studid',$item->studid)
                                                  ->whereIn('semid',[1,2])
                                                  ->values();
                          }
                    }

                    $check_nodp = collect($no_dp)->where('studid',$item->studid)->count();
              }

              //no_dp
              if($check_nodp == 0){
                    $item->nodp = 0;
              }else{
                    $item->nodp = 1;
              }

              //grade level no dp
              $no_dp_gradelevel = collect($all_gradelevel)->where('id',$item->levelid)->first();
              if($no_dp_gradelevel->nodp == 1){
                    $item->nodp = 1;
              }

              if($school_info->abbreviation == 'SAIT'){
                    $item->nodp = 1;
              }

              if(count($temp_chrngrans) > 0){
                    $item->withpayment = 1;

                    if($school_info->abbreviation == 'VNBC' && $school_info->abbreviation == 'MAC' || $school_info->abbreviation == 'HCB' || $school_info->abbreviation == 'FMC MA SCH'){
                          $item->payment  = collect($chngtrans)
                                                  ->where('studid',$item->studid)
                                                  ->sum('amount');
                    }else{
                          $temp_cashtrans = collect($cashtrans)
                                                  ->whereIn('transno', collect($temp_chrngrans)->pluck('transno'))
                                                  ->where('studid',$item->studid)
                                                  ->count();

                          if($temp_cashtrans > 0){
                                $item->payment  = collect($cashtrans)
                                                        ->whereIn('transno', collect($temp_chrngrans)->pluck('transno'))
                                                        ->where('studid',$item->studid)
                                                        ->sum('amount');
                          }else{
                                $item->withpayment = 0;
                                $item->payment = 0;
                          }

                    }

                    if($school_info->abbreviation == 'VNBC' || $school_info->abbreviation == 'MAC' || $school_info->abbreviation == 'HCB' || $school_info->abbreviation == 'FMC MA SCH'){
                          $chngtrans = collect($chngtrans)->where('studid','!=' , $item->studid)->values();
                    }else{
                          $chngtrans = collect($chngtrans)->whereNotIn('transno', collect($temp_chrngrans)->pluck('transno'))->values();
                          $cashtrans = collect($cashtrans)->whereNotIn('transno', collect($temp_chrngrans)->pluck('transno'))->values();
                    }

              }else{
                     $item->withpayment = 0;
                     $item->payment = 0;
              }

              if( $item->withpayment ==  1 || $item->nodp == 1){
                    $item->can_enroll = 1;
              }else{
                    $item->can_enroll = 0;
              }

        }

        // return $all_students;
        if ($request->get('action') == "export") {
              $pdf = PDF::loadView('superadmin.pages.student.studentinfoprint', compact('all_students', 'student_count'));
              return $pdf->stream('Student Information.pdf');
        }else if($request->get('exporttype') == "exportOldAccounts"){
            $html = '<style>
                        table, th, td {
                            border: 1px solid black;
                            border-collapse: collapse;
                            font-size: 10px;
                        }
                        th, td {
                            padding: 5px;
                        }
                        th {
                            text-align: left;
                        }
                    </style>
                    <h2 style="text-align: center; font-weight: bold; font-size: 14px;">Old Accounts Report - ' .DB::table('schoolinfo')->first()->schoolname  . ' SY - ' . $item->sydesc . '</h1>
                    <table class="table-hover table table-striped table-sm table-bordered" id="update_info_request_table" width="100%">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%"></th>
                                <th width="10%" class="align-middle prereg_head" data-id="0">ID #</th>
                                <th width="25%" class="align-middle prereg_head" data-id="1">Students</th>
                                <th width="10%" class="align-middle prereg_head" data-id="3">School Year</th>
                                <th width="15%" class="align-middle prereg_head" data-id="1">Charges</th>
                                <th width="15%" class="align-middle prereg_head" data-id="1">Payment</th>
                                <th width="20%" class="align-middle prereg_head" data-id="1">Balance</th>
                            </tr>
                        </thead>
                        <tbody>';
            foreach($all_students as $key => $item){
                $html .= '<tr>
                            <td>'.($key+1).'</td>
                            <td>'.$item->sid.'</td>
                            <td>'.strtoupper($item->student).'</td>
                            <td>'.$item->sydesc.'</td>
                            <td>'.number_format($item->ledgeramount,2).'</td>
                            <td>'.number_format($item->ledgerpayment,2).'</td>
                            <td>'.number_format($item->ledgerbalance,2).'</td>
                        </tr>';
            }

            $html .= '</tbody>
                    </table>';
            $pdf = PDF::loadHtml($html,'UTF-8')
                ->setPaper('a4', 'landscape');
            return $pdf->stream('Export Old Accounts.pdf');
        }

        return @json_encode((object)[
              'data'=>$all_students,
              'recordsTotal'=>$student_count,
              'recordsFiltered'=>$student_count
        ]);
       
    }

    public static function get_acad($syid = null){

        $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();
        $refid = $check_refid->refid;

        if(Session::get('currentPortal') == 4 || Session::get('currentPortal') == 15 || Session::get('currentPortal') == 17 ||  $refid == 28 || auth()->user()->type == 6 || Session::get('currentPortal') == 6){
              $acadprog = DB::table('academicprogram')
                                      ->select('id')
                                      ->get();
        }elseif(auth()->user()->type == 14 || Session::get('currentPortal') == 14 ){

              $user = auth()->user()->id;
              $teacher = DB::table('teacher')
                          ->where('userid',$user)     
                          ->select('id')
                          ->first();
              

              $acadprog = DB::table('college_colleges')
                                ->where('dean',$teacher->id)
                                ->where('deleted',0)
                                ->select('acadprogid as id')
                                ->get();
              
              // $acadprog = DB::table('academicprogram')
              //                   ->whereIn('id',6)
              //                   ->select('id')
              //                   ->get();
        }
        else{

              $teacherid = DB::table('teacher')
                                ->where('tid',auth()->user()->email)
                                ->select('id')
                                ->first()
                                ->id;

              if(Session::get('currentPortal') == 2){

                    $acadprog = DB::table('academicprogram')
                                      ->where('principalid',$teacherid)
                                      ->get();

              }else{

                    $acadprog = DB::table('teacheracadprog')
                                ->where('teacherid',$teacherid)
                                ->where('deleted',0)
                                ->where('syid',$syid)
                                ->where('acadprogutype',Session::get('currentPortal'))
                                ->select('acadprogid as id')
                                ->distinct('acadprogid')
                                ->get();
              }
        }


        $acadprog_list = array();
        foreach($acadprog as $item){
              array_push($acadprog_list,$item->id);
        }

        return $acadprog_list;

  }

// working code v4
//   public function oldAccount_getStudLedger(Request $request)
//   {
//       // if($request->ajax())
//       // {
//           $syid = $request->get('syid');
//           $studid = $request->get('studid');
//           $semid = $request->get('semid');

//           //  return $request->all();

//           $levelid = 0;
//           $levelname = '';
//           // $studstatus = 0;
//           $strand = '';
//           $section = '';
//           $grantee = '';
//           $studstat = '';
//           $studstatus = 0;
//           $list = '';
//           $feesname = '';
//           $feesid = 0;

//           if($studid == 0)
//           {
//               goto end;
//           }

//           $info = db::table('studinfo')
//                   ->where('id', $studid)
//                   ->first();
                  

//           // $tuitionheader = db::table('tuitionheader')
//           // 	->where('id', $info->feesid)
//           // 	->first();

//           // if($tuitionheader)
//           // {
//           // 	$feesname = $tuitionheader->description;
//           // 	// $feesid = $tuitionheader->id;
//           // }

//           $grntee = db::table('grantee')
//               ->where('id', $info->grantee)
//               ->first();

//           if($grntee)
//           {
//               $grantee = $grntee->description;
//           }


//           $enrolled = db::table('enrolledstud')
//               ->where('studid', $studid)
//               ->where('syid', $syid)
//               ->where(function($q) use($semid){
//                   if($semid == 3)
//                   {
//                       $q->where('ghssemid', 3);
//                   }
//                   else
//                   {
//                       $q->where('ghssemid', '!=', 3);
//                   }
//               })
//               ->where('deleted', 0)
//               ->first();
              

//           if($enrolled)
//           {
//               $levelid = $enrolled->levelid;
//               $levelname = db::table('gradelevel')->where('id', $levelid)->first()->levelname;
//               $studstatus = $enrolled->studstatus;
//               $feesid = $enrolled->feesid;

//               $sec = db::table('sections')
//                   ->where('id', $enrolled->sectionid)
//                   ->first();

//               if($sec)
//               {
//                   $section = $sec->sectionname;
//               }
//           }
//           else
//           {
//               $enrolled = db::table('sh_enrolledstud')
//                   ->where('studid', $studid)
//                   ->where('syid', $syid)
//                   ->where('semid', $semid)
//                   // ->where(function($q) use($semid){
//                   // 	if($semid == 3)
//                   // 	{
//                   // 		$q->where('semid', 3);
//                   // 	}
//                   // 	else
//                   // 	{
//                   // 		if(db::table('schoolinfo')->first()->shssetup == 0)
//                   // 		{
//                   // 			$q->where('semid', $semid);
//                   // 		}
//                   // 	}
//                   // })
//                   ->where('deleted', 0)
//                   ->first();

//               if($enrolled)
//               {
//                   $levelid = $enrolled->levelid;
//                   $levelname = db::table('gradelevel')->where('id', $levelid)->first()->levelname;
//                   $studstatus = $enrolled->studstatus;

//                   $sec = db::table('sections')
//                       ->where('id', $enrolled->sectionid)
//                       ->first();

//                   if($sec)
//                   {
//                       $section = $sec->sectionname;
//                   }

//                   $strnd = db::table('sh_strand')
//                       ->where('id', $enrolled->strandid)
//                       ->first();

//                   if($strnd)
//                   {
//                       $strand = $strnd->strandcode;
//                   }

//                   $feesid = $enrolled->feesid;
//               }
//               else
//               {
//                   $enrolled = db::table('college_enrolledstud')
//                       ->where('studid', $studid)
//                       ->where('syid', $syid)
//                       ->where('semid', $semid)
//                       ->where('deleted', 0)
//                       ->first();

//                   if($enrolled)
//                   {
//                       $levelid = $enrolled->yearLevel;
//                       $levelname = db::table('gradelevel')->where('id', $levelid)->first()->levelname;
//                       $studstatus = $enrolled->studstatus;

//                       $course = db::table('college_courses')
//                           ->where('id', $enrolled->courseid)
//                           ->first();

//                       if($course)
//                       {
//                           $section = $course->courseabrv;
//                       }

//                       $feesid = $enrolled->feesid;
//                   }
//                   else
//                   {
//                       $levelid = $info->levelid;
//                       $levelname = db::table('gradelevel')->where('id', $levelid)->first()->levelname;
//                       $studstatus = 0;
//                   }
//               }
//           }

//           $tuitionheader = db::table('tuitionheader')
//               ->where('id', $feesid)
//               ->first();

//           if($tuitionheader)
//           {
//               $feesname = $tuitionheader->description;
//               // $feesid = $tuitionheader->id;
//           }



//           $stat = db::table('studentstatus')
//               ->where('id', $studstatus)
//               ->first();

//           if($stat)
//           {
//               $studstat = $stat->description;
//           }

//           // $levelid = $info->levelid;
//           // $studstatus = $info->studstatus;


//           if($levelid == 14 || $levelid == 15)
//           {
//               $getLedger = db::table('studledger')
//                   ->where('studid', $studid)
//                   ->where('syid', $syid)
//                   ->where(function($q) use($semid){
//                       if(db::table('schoolinfo')->first()->shssetup == 0)
//                       {
//                           $q->where('semid', $semid);
//                       }
//                   })
//                   ->where('deleted', 0)
//                   ->orderBy('createddatetime', 'asc')
//                   ->get();
//           }
//           elseif($levelid >= 17 && $levelid <= 25)
//           {
//               $getLedger = db::table('studledger')
//                   ->where('studid', $studid)
//                   ->where('syid', $syid)
//                   ->where('semid', $semid)
//                   ->where('deleted', 0)
//                   ->orderBy('createddatetime', 'asc')
//                   ->get();
//           }
//           else
//           {
//               $getLedger = db::table('studledger')
//                   ->where('studid', $studid)
//                   ->where('syid', $syid)
//                   ->where('deleted', 0)
//                   ->orderBy('createddatetime', 'asc')
//                   ->get();
//           }

//           // return $getLedger;



//           $bal = 0;
//           $debit = 0;
//           $credit = 0;

//           foreach($getLedger as $led)
//           {

//               if($led->void == 0)
//               {
//                   $debit += $led->amount;
//                   $credit += $led->payment;
//               }


//               $lDate = date_create($led->createddatetime);
//               $lDate = date_format($lDate, 'm-d-Y');

//               if($led->amount > 0)
//               {
//                 $amount = number_format($led->amount,2);
//               }
//               else
//               {
//                 $amount = '';
//               }

//               if($led->payment > 0)
//               {
//                 $payment = number_format($led->payment,2);
//               }
//               else
//               {
//                 $payment = '';
//               }

//               if($led->void == 0)
//               {
//                   $bal += $led->amount - $led->payment;

//                   if(strpos($led->particulars, 'ADJ:') !== false)
//                   {
//                       $list .='
//                           <tr data-id="'.$led->id.'">
                        
//                             <td>
//                                 '.$led->particulars.'
//                                 <span class="text-sm text-danger adj_delete" style="cursor:pointer" data-id="'.$led->ornum.'">
//                                     <i class="far fa-trash-alt"></i>
//                                 </span>
//                             </td>
//                             <td class="text-right">'.$amount.'</td>
//                             <td class="text-right">'.$payment.'</td>
//                             <td class="text-right">'.number_format($bal, 2).'</td>
//                           </tr>
//                       ';
//                   }
//                   elseif(strpos($led->particulars, 'DISCOUNT:') !== false)
//                   {
//                       $list .='
//                           <tr data-id="'.$led->id.'">
                           
//                             <td>
//                                 '.$led->particulars.'
//                                 <span class="text-sm text-danger discount_delete" style="cursor:pointer" data-id="'.$led->ornum.'">
//                                     <i class="far fa-trash-alt"></i>
//                                 </span>
//                             </td>
//                             <td class="text-right">'.$amount.'</td>
//                             <td class="text-right">'.$payment.'</td>
//                             <td class="text-right">'.number_format($bal, 2).'</td>
//                           </tr>
//                       ';
//                   }
//                   elseif(strpos($led->particulars, 'Balance forwarded from') !== false)
//                   {
//                       $list .='
//                           <tr data-id="'.$led->id.'">
                           
//                             <td>
//                                 '.$led->particulars.'
//                                 <span class="text-sm text-danger ledgeroa_delete" style="cursor:pointer" data-id="'.$led->id.'">
//                                     <i class="far fa-trash-alt"></i>
//                                 </span>
//                             </td>
//                             <td class="text-right">'.$amount.'</td>
//                             <td class="text-right">'.$payment.'</td>
//                             <td class="text-right">'.number_format($bal, 2).'</td>
//                           </tr>
//                       ';
//                   }
//                   else
//                   {
//                       $list .='
//                           <tr class="">
                          
//                             <td>'.$led->particulars.'</td>
//                             <td class="text-right">'.$amount.'</td>
//                             <td class="text-right">'.$payment.'</td>
//                             <td class="text-right">'.number_format($bal, 2).'</td>
//                           </tr>
//                       ';
//                   }


//               }
//               else
//               {
//                   $list .='
//                       <tr class="">
//                         <td class="text-danger"><del>' .$lDate.' </del></td>
//                         <td class="text-danger"><del>'.$led->particulars.'</del></td>
//                         <td class="text-right text-danger"><del>'.$amount.'</del></td>
//                         <td class="text-right text-danger"><del>'.$payment.'</del></td>
//                         <td class="text-right text-danger"><del>'.number_format($bal, 2).'</del></td>
//                       </tr>
//                       ';
//               }




//               //     		$bal += $led->amount - $led->payment;

//               // $list .='
//               // <tr class="">
//               // 	<td class="">' .$lDate.' </td>
//               // 	<td>'.$led->particulars.'</td>
//               // 	<td class="text-right">'.$amount.'</td>
//               // 	<td class="text-right">'.$payment.'</td>
//               // 	<td class="text-right">'.number_format($bal, 2).'</td>
//                 //       </tr>
//               // 	';
//           }

//           $list .= '
//               <tr class="bg-primary">
            
//                   <th style="text-align:right">
//                       <h5>
//                         TOTAL:
//                       </h5>
//                   </th>
//                   <th class="text-right">
//                       <h5>
//                         <u>'.number_format($debit, 2).'</u>
//                       </h5>
//                   </th>
//                   <th class="text-right">
//                       <h5>
//                         <u>'.number_format($credit, 2).'</u>
//                       </h5>
//                   </th>
//                   <th class="text-right">
//                       <h5>
//                             <u>'.number_format($bal, 2).'</u>
//                       </h5>
//                   </th>
//               </tr>
//               ';

//           end:

//           $data = array(
//               'list' => $list,
//               'levelid' => $levelid,
//               'levelname' => $levelname,
//               'studstatus' => $studstatus,
//               'strand' => $strand,
//               'section' => $section,
//               'grantee' => $grantee,
//               'studstatus' => $studstat,
//               'feesname' => $feesname,
//               'feesid' => $feesid
//           );

//           echo json_encode($data);
//       // }
//   }

// WORKING LATEST V4 CODE
// public function oldAccount_getStudLedger(Request $request)
// {
//     // if($request->ajax())
//     // {
//         $syid = $request->get('syid');
//         $studid = $request->get('studid');
//         $semid = $request->get('semid');

//         //  return $request->all();

//         $levelid = 0;
//         $levelname = '';
//         // $studstatus = 0;
//         $strand = '';
//         $section = '';
//         $grantee = '';
//         $studstat = '';
//         $studstatus = 0;
//         $list = '';
//         $feesname = '';
//         $feesid = 0;

//         if($studid == 0)
//         {
//             goto end;
//         }

//         $info = db::table('studinfo')
//                 ->select('sid', 'lastname', 'firstname', 'middlename','grantee')
//                 ->where('id', $studid)
//                 ->first();
                
//         $studname = $info->sid . ' - ' . $info->lastname . ', ' . $info->firstname . ' ' . $info->middlename;
                

//         // $tuitionheader = db::table('tuitionheader')
//         // 	->where('id', $info->feesid)
//         // 	->first();

//         // if($tuitionheader)
//         // {
//         // 	$feesname = $tuitionheader->description;
//         // 	// $feesid = $tuitionheader->id;
//         // }

//         $grntee = db::table('grantee')
//             ->where('id', $info->grantee)
//             ->first();

//         if($grntee)
//         {
//             $grantee = $grntee->description;
//         }


//         $enrolled = db::table('enrolledstud')
//             ->where('studid', $studid)
//             ->where('syid', $syid)
//             ->where(function($q) use($semid){
//                 if($semid == 3)
//                 {
//                     $q->where('ghssemid', 3);
//                 }
//                 else
//                 {
//                     $q->where('ghssemid', '!=', 3);
//                 }
//             })
//             ->where('deleted', 0)
//             ->first();
            

//         if($enrolled)
//         {
//             $levelid = $enrolled->levelid ?? 0;
//             $levelname = db::table('gradelevel')->where('id', $levelid)->first()->levelname ?? '';
//             $studstatus = $enrolled->studstatus ?? 0;
//             $feesid = $enrolled->feesid ?? 0;

//             $sec = db::table('sections')
//                 ->where('id', $enrolled->sectionid ?? 0)
//                 ->first();

//             if($sec)
//             {
//                 $section = $sec->sectionname ?? '';
//             }
//         }
//         else
//         {
//             $enrolled = db::table('sh_enrolledstud')
//                 ->where('studid', $studid)
//                 ->where('syid', $syid)
//                 ->where('semid', $semid)
//                 // ->where(function($q) use($semid){
//                 // 	if($semid == 3)
//                 // 	{
//                 // 		$q->where('semid', 3);
//                 // 	}
//                 // 	else
//                 // 	{
//                 // 		if(db::table('schoolinfo')->first()->shssetup == 0)
//                 // 		{
//                 // 			$q->where('semid', $semid);
//                 // 		}
//                 // 	}
//                 // })
//                 ->where('deleted', 0)
//                 ->first();

//             if($enrolled)
//             {
//                 $levelid = $enrolled->levelid ?? 0;
//                 $levelname = db::table('gradelevel')->where('id', $levelid)->first()->levelname ?? '';
//                 $studstatus = $enrolled->studstatus ?? 0;

//                 $sec = db::table('sections')
//                     ->where('id', $enrolled->sectionid ?? 0)
//                     ->first();

//                 if($sec)
//                 {
//                     $section = $sec->sectionname ?? '';
//                 }

//                 $strnd = db::table('sh_strand')
//                     ->where('id', $enrolled->strandid ?? 0)
//                     ->first();

//                 if($strnd)
//                 {
//                     $strand = $strnd->strandcode ?? '';
//                 }

//                 $feesid = $enrolled->feesid ?? 0;
//             }
//             else
//             {
//                 $enrolled = db::table('college_enrolledstud')
//                     ->where('studid', $studid)
//                     ->where('syid', $syid)
//                     ->where('semid', $semid)
//                     ->where('deleted', 0)
//                     ->first();

//                 if($enrolled)
//                 {
//                     $levelid = $enrolled->yearLevel ?? 0;
//                     $levelname = db::table('gradelevel')->where('id', $levelid)->first()->levelname ?? '';
//                     $studstatus = $enrolled->studstatus ?? 0;

//                     $course = db::table('college_courses')
//                         ->where('id', $enrolled->courseid ?? 0)
//                         ->first();

//                     if($course)
//                     {
//                         $section = $course->courseabrv ?? '';
//                     }

//                     $feesid = $enrolled->feesid ?? 0;
//                 }
//                 else
//                 {
//                     $levelid = $info->levelid ?? 0;
//                     $levelname = db::table('gradelevel')->where('id', $levelid)->first()->levelname ?? '';
//                     $studstatus = 0;
//                 }
//             }
//         }

//         $tuitionheader = db::table('tuitionheader')
//             ->where('id', $feesid)
//             ->first();

//         if($tuitionheader)
//         {
//             $feesname = $tuitionheader->description;
//             // $feesid = $tuitionheader->id;
//         }



//         $stat = db::table('studentstatus')
//             ->where('id', $studstatus)
//             ->first();

//         if($stat)
//         {
//             $studstat = $stat->description;
//         }

//         // $levelid = $info->levelid;
//         // $studstatus = $info->studstatus;


//         if($levelid == 14 || $levelid == 15)
//         {
//             $getLedger = db::table('studledger')
//                 ->where('studid', $studid)
//                 ->where('syid', $syid)
//                 ->where(function($q) use($semid){
//                     if(db::table('schoolinfo')->first()->shssetup == 0)
//                     {
//                         $q->where('semid', $semid);
//                     }
//                 })
//                 ->where('deleted', 0)
//                 ->orderBy('createddatetime', 'asc')
//                 ->get();
//         }
//         elseif($levelid >= 17 && $levelid <= 25)
//         {
//             $getLedger = db::table('studledger')
//                 ->where('studid', $studid)
//                 ->where('syid', $syid)
//                 ->where('semid', $semid)
//                 ->where('deleted', 0)
//                 ->orderBy('createddatetime', 'asc')
//                 ->get();
//         }
//         else
//         {
//             $getLedger = db::table('studledger')
//                 ->where('studid', $studid)
//                 ->where('syid', $syid)
//                 ->where('deleted', 0)
//                 ->orderBy('createddatetime', 'asc')
//                 ->get();
//         }

//         // return $getLedger;



//         $bal = 0;
//         $debit = 0;
//         $credit = 0;

//         foreach($getLedger as $led)
//         {

//             if($led->void == 0)
//             {
//                 $debit += $led->amount;
//                 $credit += $led->payment;
//             }


//             $lDate = date_create($led->createddatetime);
//             $lDate = date_format($lDate, 'm-d-Y');

//             if($led->amount > 0)
//             {
//               $amount = number_format($led->amount,2);
//             }
//             else
//             {
//               $amount = '';
//             }

//             if($led->payment > 0)
//             {
//               $payment = number_format($led->payment,2);
//             }
//             else
//             {
//               $payment = '';
//             }

//             if($led->void == 0)
//             {
//                 $bal += $led->amount - $led->payment;

//                 if(strpos($led->particulars, 'ADJ:') !== false)
//                 {
//                     $list .='
//                         <tr data-id="'.$led->id.'">
                      
//                           <td>
//                               '.$led->particulars.'
//                               <span class="text-sm text-danger adj_delete" style="cursor:pointer" data-id="'.$led->ornum.'">
//                                   <i class="far fa-trash-alt"></i>
//                               </span>
//                           </td>
//                           <td class="text-left">'.$amount.'</td>
//                           <td class="text-left">'.$payment.'</td>
//                           <td class="text-left">'.number_format($bal, 2).'</td>
//                         </tr>
//                     ';
//                 }
//                 elseif(strpos($led->particulars, 'DISCOUNT:') !== false)
//                 {
//                     $list .='
//                         <tr data-id="'.$led->id.'">
                         
//                           <td>
//                               '.$led->particulars.'
//                               <span class="text-sm text-danger discount_delete" style="cursor:pointer" data-id="'.$led->ornum.'">
//                                   <i class="far fa-trash-alt"></i>
//                               </span>
//                           </td>
//                           <td class="text-left">'.$amount.'</td>
//                           <td class="text-left">'.$payment.'</td>
//                           <td class="text-left">'.number_format($bal, 2).'</td>
//                         </tr>
//                     ';
//                 }
//                 elseif(strpos($led->particulars, 'Balance forwarded from') !== false)
//                 {
//                     $list .='
//                         <tr data-id="'.$led->id.'">
                         
//                           <td>
//                               '.$led->particulars.'
//                               <span class="text-sm text-danger ledgeroa_delete" style="cursor:pointer" data-id="'.$led->id.'">
//                                   <i class="far fa-trash-alt"></i>
//                               </span>
//                           </td>
//                           <td class="text-left">'.$amount.'</td>
//                           <td class="text-left">'.$payment.'</td>
//                           <td class="text-left">'.number_format($bal, 2).'</td>
//                         </tr>
//                     ';
//                 }
//                 else
//                 {
//                     $list .='
//                         <tr class="">
                        
//                           <td>'.$led->particulars.'</td>
//                           <td class="text-left">'.$amount.'</td>
//                           <td class="text-left">'.$payment.'</td>
//                           <td class="text-left">'.number_format($bal, 2).'</td>
//                         </tr>
//                     ';
//                 }


//             }
//             else
//             {
//                 $list .='
//                     <tr class="">
//                       <td class="text-danger"><del>'.$led->particulars.'</del></td>
//                       <td class="text-right text-danger"><del>'.$amount.'</del></td>
//                       <td class="text-right text-danger"><del>'.$payment.'</del></td>
//                       <td class="text-right text-danger"><del>'.number_format($bal, 2).'</del></td>
//                     </tr>
//                     ';
//             }




//             //     		$bal += $led->amount - $led->payment;

//             // $list .='
//             // <tr class="">
//             // 	<td class="">' .$lDate.' </td>
//             // 	<td>'.$led->particulars.'</td>
//             // 	<td class="text-right">'.$amount.'</td>
//             // 	<td class="text-right">'.$payment.'</td>
//             // 	<td class="text-right">'.number_format($bal, 2).'</td>
//               //       </tr>
//             // 	';
//         }

//         $list .= '
//             <tr class="bg-primary">
          
//                 <th style="text-align:right;">
//                     <h5 style="margin-right:33px">
//                       TOTAL:
//                     </h5>
//                 </th>
//                 <th class="text-left">
//                     <h5>
//                       <u>'.number_format($debit, 2).'</u>
//                     </h5>
//                 </th>
//                 <th class="text-left">
//                     <h5>
//                       <u>'.number_format($credit, 2).'</u>
//                     </h5>
//                 </th>
//                 <th class="text-left">
//                     <h5>
//                           <u>'.number_format($bal, 2).'</u>
//                     </h5>
//                 </th>
//             </tr>
//             ';

//         end:

//         $data = array(
//             'list' => $list,
//             'levelid' => $levelid,
//             'levelname' => $levelname,
//             'studstatus' => $studstatus,
//             'strand' => $strand,
//             'section' => $section,
//             'grantee' => $grantee,
//             'studstatus' => $studstat,
//             'feesname' => $feesname,
//             'feesid' => $feesid,
//             'studname' => $studname
//         );

//         echo json_encode($data);
//     // }
// }

    public function oldAccount_getStudLedger(Request $request)
    {
        // if($request->ajax())
        // {
            $syid = $request->get('syid');
            $studid = $request->get('studid');
            $semid = $request->get('semid');

            //  return $request->all();

            $levelid = 0;
            $levelname = '';
            // $studstatus = 0;
            $strand = '';
            $section = '';
            $grantee = '';
            $studstat = '';
            $studstatus = 0;
            $list = '';
            $feesname = '';
            $feesid = 0;

            if($studid == 0)
            {
                goto end;
            }

            $info = db::table('studinfo')
                    ->select('sid', 'lastname', 'firstname', 'middlename','grantee')
                    ->where('id', $studid)
                    ->first();
                    
            $studname = $info->sid . ' - ' . $info->lastname . ', ' . $info->firstname . ' ' . $info->middlename;
                    

            // $tuitionheader = db::table('tuitionheader')
            // 	->where('id', $info->feesid)
            // 	->first();

            // if($tuitionheader)
            // {
            // 	$feesname = $tuitionheader->description;
            // 	// $feesid = $tuitionheader->id;
            // }

            $grntee = db::table('grantee')
                ->where('id', $info->grantee)
                ->first();

            if($grntee)
            {
                $grantee = $grntee->description;
            }


            $enrolled = db::table('enrolledstud')
                ->where('studid', $studid)
                ->where('syid', $syid)
                ->where(function($q) use($semid){
                    if($semid == 3)
                    {
                        $q->where('ghssemid', 3);
                    }
                    else
                    {
                        $q->where('ghssemid', '!=', 3);
                    }
                })
                ->where('deleted', 0)
                ->first();
                

            if($enrolled)
            {
                $levelid = $enrolled->levelid ?? 0;
                $levelname = db::table('gradelevel')->where('id', $levelid)->first()->levelname ?? '';
                $studstatus = $enrolled->studstatus ?? 0;
                $feesid = $enrolled->feesid ?? 0;

                $sec = db::table('sections')
                    ->where('id', $enrolled->sectionid ?? 0)
                    ->first();

                if($sec)
                {
                    $section = $sec->sectionname ?? '';
                }
            }
            else
            {
                $enrolled = db::table('sh_enrolledstud')
                    ->where('studid', $studid)
                    ->where('syid', $syid)
                    ->where('semid', $semid)
                    // ->where(function($q) use($semid){
                    // 	if($semid == 3)
                    // 	{
                    // 		$q->where('semid', 3);
                    // 	}
                    // 	else
                    // 	{
                    // 		if(db::table('schoolinfo')->first()->shssetup == 0)
                    // 		{
                    // 			$q->where('semid', $semid);
                    // 		}
                    // 	}
                    // })
                    ->where('deleted', 0)
                    ->first();

                if($enrolled)
                {
                    $levelid = $enrolled->levelid ?? 0;
                    $levelname = db::table('gradelevel')->where('id', $levelid)->first()->levelname ?? '';
                    $studstatus = $enrolled->studstatus ?? 0;

                    $sec = db::table('sections')
                        ->where('id', $enrolled->sectionid ?? 0)
                        ->first();

                    if($sec)
                    {
                        $section = $sec->sectionname ?? '';
                    }

                    $strnd = db::table('sh_strand')
                        ->where('id', $enrolled->strandid ?? 0)
                        ->first();

                    if($strnd)
                    {
                        $strand = $strnd->strandcode ?? '';
                    }

                    $feesid = $enrolled->feesid ?? 0;
                }
                else
                {
                    $enrolled = db::table('college_enrolledstud')
                        ->where('studid', $studid)
                        ->where('syid', $syid)
                        ->where('semid', $semid)
                        ->where('deleted', 0)
                        ->first();

                    if($enrolled)
                    {
                        $levelid = $enrolled->yearLevel ?? 0;
                        $levelname = db::table('gradelevel')->where('id', $levelid)->first()->levelname ?? '';
                        $studstatus = $enrolled->studstatus ?? 0;

                        $course = db::table('college_courses')
                            ->where('id', $enrolled->courseid ?? 0)
                            ->first();

                        if($course)
                        {
                            $section = $course->courseabrv ?? '';
                        }

                        $feesid = $enrolled->feesid ?? 0;
                    }
                    else
                    {
                        $levelid = $info->levelid ?? 0;
                        $levelname = db::table('gradelevel')->where('id', $levelid)->first()->levelname ?? '';
                        $studstatus = 0;
                    }
                }
            }

            $tuitionheader = db::table('tuitionheader')
                ->where('id', $feesid)
                ->first();

            if($tuitionheader)
            {
                $feesname = $tuitionheader->description;
                // $feesid = $tuitionheader->id;
            }



            $stat = db::table('studentstatus')
                ->where('id', $studstatus)
                ->first();

            if($stat)
            {
                $studstat = $stat->description;
            }

            // $levelid = $info->levelid;
            // $studstatus = $info->studstatus;


            if($levelid == 14 || $levelid == 15)
            {
                $studledger = db::table('studledger')
                        ->where('studid', $studid)
                        ->where('syid', $syid)
                        ->where('semid', $semid)
                        ->where('deleted', 0)
                        ->get();

                $balance = 0;
                $debit = 0;
                $credit = 0;
                $tdebit = 0;
                $tcredit = 0;

                foreach($studledger as $ledger)
                {
                    if($ledger->amount > 0)
                    {
                        $debit = number_format($ledger->amount, 2);
                    }
                    else
                    {
                        $debit = '';
                    }

                    if($ledger->payment > 0)
                    {
                        $credit = number_format($ledger->payment, 2);
                    }
                    else
                    {
                        $credit = '';
                    }

                    if($ledger->void == 0)
                    {
                        $balance += $ledger->amount - $ledger->payment;
                        $tdebit += $ledger->amount;
                        $tcredit += $ledger->payment;

                        $ldate = date_create($ledger->createddatetime);
                        $ldate = date_format($ldate, 'm-d-Y');

                        $list .='
                            <tr>
            
                <td>'.$ledger->particulars.'</td>
                <td class="mr-5">'.$debit.'</td>
                <td class="mr-5">'.$credit.'</td>
                <td class="mr-5">'.number_format($balance, 2).'</td>
            </tr>
                        ';
                    }
                    else
                    {
                        $list .='
                            <tr>
            
                <td class="text-danger"><del>'.$ledger->particulars.'</del></td>
                <td class="text-danger mr-5"><del>'.$debit.'</del></td>
                <td class="text-danger mr-5"><del>'.$credit.'</del></td>
                <td class="text-danger mr-5"><del>'.number_format($balance, 2).'</del></td>
            </tr>
                        ';
                    }
                }

                $list .= '
                <tr class="bg-warning">

                <th style="text-align:right">
                <span style="font-size: 0.9rem;padding-right: 60px;">
                TOTAL:
                </span>
            </th>
            <th class="text-left">
                <span style="font-size: 0.9rem">
                '.number_format($tdebit, 2).'
                </span>
            </th>
            <th class="text-left">
                <span style="font-size: 0.9rem">
                '.number_format($tcredit, 2).'
                </span>
            </th>
            <th class="text-left">
                <span style="font-size: 0.9rem">
                '.number_format($balance, 2).'
                </span>
            </th>
            </tr>
            ';

            }
            else
            {
                $studledger = db::table('studledger')
                        ->where('studid', $studid)
                        ->where('syid', $syid)
                        ->where('deleted', 0)
                        ->get();

                $balance = 0;
                $tdebit = 0;
                $tcredit = 0;
                $debit = 0;
                $credit = 0;

                foreach($studledger as $ledger)
                {

                    if($ledger->amount > 0)
                    {
                        $debit = number_format($ledger->amount, 2);
                    }
                    else
                    {
                        $debit = '';
                    }

                    if($ledger->payment > 0)
                    {
                        $credit = number_format($ledger->payment, 2);
                    }
                    else
                    {
                        $credit = '';
                    }


                    if($ledger->void == 0)
                    {
                        $balance += $ledger->amount - $ledger->payment;
                        $tdebit += $ledger->amount;
                        $tcredit += $ledger->payment;

                        $ldate = date_create($ledger->createddatetime);
                        $ldate = date_format($ldate, 'm-d-Y');

                        $list .='
                            <tr>
            
                <td>'.$ledger->particulars.'</td>
                <td class="mr-5">'.$debit.'</td>
                <td class="mr-5">'.$credit.'</td>
                <td class="mr-5">'.number_format($balance, 2).'</td>
            </tr>
                        ';
                    }
                    else
                    {
                        $list .='
                            <tr>
            
                <td class="text-danger"><del>'.$ledger->particulars.'</del></td>
                <td class="text-danger mr-5"><del>'.$debit.'</del></td>
                <td class="text-danger mr-5"><del>'.$credit.'</del></td>
                <td class="text-danger mr-5"><del>'.number_format($balance, 2).'</del></td>
            </tr>
                        ';
                    }
                }
                $list .= '
                    <tr class="bg-warning">
    
        <th style="text-align:right">
            <span style="font-size: 0.9rem;padding-right: 60px;">
            TOTAL:
            </span>
        </th>
        <th class="text-left">
            <span style="font-size: 0.9rem">
            '.number_format($tdebit, 2).'
            </span>
        </th>
        <th class="text-left">
            <span style="font-size: 0.9rem">
            '.number_format($tcredit, 2).'
            </span>
        </th>
        <th class="text-left">
            <span style="font-size: 0.9rem">
            '.number_format($balance, 2).'
            </span>
        </th>
        </tr>
                ';
            }

            end:

            $data = array(
                'list' => $list,
                'levelid' => $levelid,
                'levelname' => $levelname,
                'studstatus' => $studstatus,
                'strand' => $strand,
                'section' => $section,
                'grantee' => $grantee,
                'studstatus' => $studstat,
                'feesname' => $feesname,
                'feesid' => $feesid,
                'studname' => $studname
            );

            echo json_encode($data);
        // }
    }

    public function getNextSchoolYear(Request $request)
    {
        $nextSyDesc = $request->input('nextSyDesc'); // Get the next school year description from the request

        // Query the database for the next school year
        $nextSy = DB::table('sy')
            ->where('sydesc', $nextSyDesc)
            ->first();

        // Return the next school year as a JSON response
        return response()->json(['nextSy' => $nextSy]);
    }

    // public function OldAccount_SchoolYear(Request $request)
    // {
    //     $current_sydesc = $request->input('current_sydesc'); 


    //     $Sy = DB::table('sy')
    //         ->where('sydesc', $current_sydesc)
    //         ->first();

    //     $AllSy = DB::table('sy')
    //         ->get();
      
    //     return response()->json([
    //         'Sy' => $Sy,
    //         'AllSy' => $AllSy
    //     ]);
    // }

    // public function OldAccount_SchoolYear(Request $request)
    // {
    //     $current_sydesc = $request->input('current_sydesc'); 
    
    //     // Validate input
    //     if (empty($current_sydesc)) {
    //         return response()->json(['error' => 'School year description is required'], 400);
    //     }
    
    //     $Sy = DB::table('sy')
    //         ->where('sydesc', $current_sydesc)
    //         ->first();
    
    //     // Get all SY ordered by id desc (newest first)
    //     $AllSy = DB::table('sy')
    //         ->orderBy('id', 'desc')
    //         ->get();
    
    //     // Find active SY (where isactive = 1)
    //     $ActiveSy = DB::table('sy')
    //         ->where('isactive', 1)
    //         ->first();
      
    //     return response()->json([
    //         'Sy' => $Sy,
    //         'AllSy' => $AllSy,
    //         'ActiveSy' => $ActiveSy
    //     ]);
    // }

    public function OldAccount_SchoolYear(Request $request)
    {
        $current_sydesc = $request->input('current_sydesc'); 
    
        // Validate input
        if (empty($current_sydesc)) {
            return response()->json(['error' => 'School year description is required'], 400);
        }
    
        $Sy = DB::table('sy')
            ->where('sydesc', $current_sydesc)
            ->first();
    
        // Find active SY (where isactive = 1)
        $ActiveSy = DB::table('sy')
            ->where('isactive', 1)
            ->first();
      
        return response()->json([
            'Sy' => $Sy,
            'ActiveSy' => $ActiveSy
        ]);
    }

    public function old_edit_balance(Request $request)
    {
        $studid = $request->input('studid');
        $balance = $request->input('old_balance');
        $syid = $request->input('syid');
        $semid = $request->input('semid');

        // Validate input
        if (empty($studid) || empty($balance)) {
            return response()->json(['error' => 'Required fields are missing', 'status' => 'failed'], 400);
        }

        // Update the balance in the database
        $studledger = DB::table('studledger')
            ->where('studid', $studid)
            ->where('syid', $syid)
            ->where('semid', $semid)
            ->where('deleted', 0)
            ->where('particulars', 'like', '%OLD ACCOUNTS FORWARDED FROM%')
            ->first();

        $studscheddetail = DB::table('studpayscheddetail')
            ->where('studid', $studid)
            ->where('syid', $syid)
            ->where('semid', $semid)
            ->where('deleted', 0)
            ->where('particulars', 'like', '%OLD ACCOUNTS FORWARDED FROM%')
            ->first();

            
        if ($studledger) {
            // dd($studledger);
            DB::table('studledger')
                ->where('id', $studledger->id)
                ->update([
                    'amount' => number_format((float)$balance, 2, '.', ''),
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => now()
                ]);
        }

        if( $studscheddetail) {
            DB::table('studpayscheddetail')
                ->where('id', $studscheddetail->id)
                ->update([
                    'amount' => number_format((float)$balance, 2, '.', ''),
                    'balance' => number_format((float)($balance - $studscheddetail->amountpay), 2, '.', ''),
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => now()
                ]);
        }



        return response()->json(['message' => 'Balance updated successfully', 'status' => 'success']);
    }

    //hapit na pero dili working
//     public function forwardBalanceToNextSY(Request $request)
// {
//     if ($request->has('ledgerListValues')) {
//         $ledgerAccounts = [];
//         foreach ($request->input('ledgerListValues') as $ledger) {
//             $studid = $ledger[0];
//             $balance = $ledger[3];

//             DB::table('studledger')->insert([
//                 'studid' => $studid,
//                 'syid' => DB::table('sy')->where('sydesc', $ledger[1])->first()->id,
//                 'semid' => DB::table('sem')->where('sem', $ledger[2])->first()->id,
//                 'levelid' => DB::table('level')->where('levelname', $ledger[4])->first()->id,
//                 'feesid' => DB::table('fees')->where('feesname', $ledger[5])->first()->id,
//                 'amount' => $balance,
//                 'payment' => 0,
//                 'balance' => $balance,
//                 'created_at' => now(),
//                 'updated_at' => now(),
//             ]);

//             $ledgerAccounts[] = [
//                 'ledgername' => $ledger[5],
//                 'charges' => '',
//                 'payments' => number_format($balance, 2),
//             ];
//         }

//         return response()->json(['ledger_accounts' => $ledgerAccounts]);
//     }
// }

// medyo final
// public function forwardBalanceToNextSY(Request $request)
// {
//     $ledgerAccounts = $request->input('ledger_accounts', []);

//     foreach ($ledgerAccounts as $ledger) {
//         DB::table('studledger')->insert([
//             'studid' => $ledger['studid'],
//             'syid' => DB::table('sy')->where('sydesc', $ledger['syid'])->first()->id,
//             'semid' => DB::table('sem')->where('sem', $ledger['sem'])->first()->id,
//             'feesid' => DB::table('fees')->where('feesname', $ledger['ledgername'])->first()->id,
//             'amount' => $ledger['payments'],
//             'payment' => 0,
//             'balance' => $ledger['payments'],
//             'created_at' => now(),
//             'updated_at' => now(),
//         ]);
//     }

//     return response()->json(['message' => 'Forwarded balances to next school year.']);
// }

//medyo final
// public function forwardBalanceToNextSY(Request $request)
// {
//     $ledgerAccounts = $request->input('ledger_accounts', []);

//     foreach ($ledgerAccounts as $ledger) {
//         DB::table('studledger')->insert([
//             'studid' => $ledger['studid'],
//             'syid' => $ledger['syid'],
//             'semid' => $ledger['sem'],
//             'particulars' => $ledger['ledgername'],
//             'amount' => $ledger['charges'],
//             'payment' => $ledger['payments'],
//             'created_at' => now()

//         ]);
//     }

//     return response()->json(['message' => 'Forwarded balances to next school year.']);
// }
// murag final
// public function forwardBalanceToNextSY(Request $request)
// {
//     $studid = $request->input('studid');
//     $syid = $request->input('syid');
//     $sem = $request->input('sem');
//     $ledgerAccounts = $request->input('ledger_accounts', []);

//     foreach ($ledgerAccounts as $ledger) {
//         DB::table('studledger')->insert([
//             'studid' => $studid,
//             'syid' => $syid,
//             'semid' => $sem,
//             'particulars' => $ledger['ledgername'],
//             'amount' => $ledger['charges'],
//             'payment' => $ledger['payments'],
//             'createdby' => auth()->user()->id,
//             'createddatetime' => now()

//         ]);
//     }

//     return response()->json(['message' => 'Forwarded balances to next school year.']);
// }

// public function forwardBalanceToNextSY(Request $request)
// {
//     $studid = $request->input('studid');
//     $syid = $request->input('syid');
//     $sem = $request->input('sem');
//     $ledgerAccounts = $request->input('ledger_accounts', []);

//     if ($request->has('ledger_accounts')) {
//         foreach ($request->input('ledger_accounts') as $ledger) {
//             if (isset($ledger['ledgername']) && $ledger['ledgername'] != '' && $ledger['ledgername'] != ' ') {
//                 $existingLedger = DB::table('studledger')->where('studid', $studid)->where('syid', $syid)->where('semid', $sem)->where('particulars', $ledger['ledgername'])->first();
//                 if ($existingLedger) {
//                     DB::table('studledger')->where('id', $existingLedger->id)->update([
//                         'amount' => $ledger['charges'],
//                         'payment' => $ledger['payments'],
//                         'updatedby' => auth()->user()->id,
//                         'updateddatetime' => now()
//                     ]);
//                 } else {
//                     DB::table('studledger')->insert([
//                         'studid' => $studid,
//                         'syid' => $syid,
//                         'semid' => $sem,
//                         'particulars' => $ledger['ledgername'],
//                         'amount' => $ledger['charges'],
//                         'payment' => $ledger['payments'],
//                         'createdby' => auth()->user()->id,
//                         'createddatetime' => now()
//                     ]);
//                 }
//             }
//         }
//     }

//     return response()->json([
//         'status' => 1,
//         'message' => 'Forwarded balances to next school year.',
//     ]);
// }

//final working na ni
// public function forwardBalanceToNextSY(Request $request)
// {
//     $studid = $request->input('studid');
//     $syid = $request->input('syid');
//     $sem = $request->input('sem');
//     $ledgerAccounts = $request->input('ledger_accounts', []);

    
//     foreach ($ledgerAccounts as $ledger) {
//         if (isset($ledger['ledgername']) && $ledger['ledgername'] != '' && $ledger['ledgername'] != ' ') {
//             DB::table('studledger')->insert([
//                 'studid' => $studid,
//                 'syid' => $syid,
//                 'semid' => $sem,
//                 'particulars' => $ledger['ledgername'],
//                 'amount' => $ledger['charges'],
//                 'payment' => $ledger['payments'],
//                 'createdby' => auth()->user()->id,
//                 'createddatetime' => now(),
//                 'deleted' => 0

//             ]);
//         }
//     }

//     $balance = $request->input('balance');
//     $nextsydesc = $request->input('nextsydesc');
//     $semdesc = $request->input('semdesc');

//     $currentsy = $request->input('currentsy');
//     $currentsydesc = $request->input('currentsydesc');
//     $currentsem = $request->input('currentsem');
//     $currentsemdesc = $request->input('currentsemdesc');

//     DB::table('studledger')->insert([
//         'studid' => $studid,
//         'syid' => $currentsy,
//         'semid' => $currentsem,
//         'particulars' => 'OLD ACCOUNTS FORWARDED TO SY'. ' ' . $nextsydesc . '-' . $semdesc,
//         'payment' => $balance,
//         'createdby' => auth()->user()->id,
//         'createddatetime' => now(),
//         'deleted' => 0

//         // OLD ACCOUNTS FORWARDED TO SY 2026-2027 - 1ST SEMESTER

//     ]);

//     DB::table('studledger')->insert([
//         'studid' => $studid,
//         'syid' => $syid,
//         'semid' => $sem,
//         'particulars' => 'OLD ACCOUNTS FORWARDED FROM SY'. ' ' . $currentsydesc . '-' . $currentsemdesc,
//         'amount' => $balance,
//         'createdby' => auth()->user()->id,
//         'createddatetime' => now(),
//         'deleted' => 0

//         // OLD ACCOUNTS FORWARDED TO SY 2026-2027 - 1ST SEMESTER

//     ]);



//     return response()->json([
//                 'status' => 1,
//                 'message' => 'Forwarded balances to next school year.',
//             ]);
// }

//working final v4 code
// public function forwardBalanceToNextSY(Request $request)
// {
//     $studid = $request->input('studid');
//     $syid = $request->input('syid');
//     $sem = $request->input('sem');
//     $ledgerAccounts = $request->input('ledger_accounts', []);

    
//     foreach ($ledgerAccounts as $ledger) {
//         if (isset($ledger['ledgername']) && $ledger['ledgername'] != '' && $ledger['ledgername'] != ' ') {
//             DB::table('studledger')->insert([
//                 'studid' => $studid,
//                 'syid' => $syid,
//                 'semid' => $sem,
//                 'particulars' => $ledger['ledgername'],
//                 'amount' => $ledger['charges'],
//                 'payment' => $ledger['payments'],
//                 'createdby' => auth()->user()->id,
//                 'createddatetime' => now(),
//                 'deleted' => 0

//             ]);
//         }
//     }

//     $balance = $request->input('balance');
//     $nextsydesc = $request->input('nextsydesc');
//     $semdesc = $request->input('semdesc');

//     $currentsy = $request->input('currentsy');
//     $currentsydesc = $request->input('currentsydesc');
//     $currentsem = $request->input('currentsem');
//     $currentsemdesc = $request->input('currentsemdesc');

//     DB::table('studledger')->insert([
//         'studid' => $studid,
//         'syid' => $currentsy,
//         'semid' => $currentsem,
//         'particulars' => 'OLD ACCOUNTS FORWARDED TO SY'. ' ' . $nextsydesc . '-' . $semdesc,
//         'payment' => $balance,
//         'createdby' => auth()->user()->id,
//         'createddatetime' => now(),
//         'deleted' => 0

//         // OLD ACCOUNTS FORWARDED TO SY 2026-2027 - 1ST SEMESTER

//     ]);

//     DB::table('studledger')->insert([
//         'studid' => $studid,
//         'syid' => $syid,
//         'semid' => $sem,
//         'particulars' => 'OLD ACCOUNTS FORWARDED FROM SY'. ' ' . $currentsydesc . '-' . $currentsemdesc,
//         'amount' => $balance,
//         'createdby' => auth()->user()->id,
//         'createddatetime' => now(),
//         'deleted' => 0

//         // OLD ACCOUNTS FORWARDED TO SY 2026-2027 - 1ST SEMESTER

//     ]);



//     return response()->json([
//                 'status' => 1,
//                 'message' => 'Forwarded balances to next school year.',
//             ]);
// }

//working v2 code
// public function forwardBalanceToNextSY(Request $request)
// {
//     $studid = $request->input('studid');
//     $syid = $request->input('syid');
//     $sem = $request->input('sem');
//     // $ledgerAccounts = $request->input('ledger_accounts', []);

//     // foreach ($ledgerAccounts as $ledger) {
//     //     if (isset($ledger['ledgername']) && $ledger['ledgername'] != '' && $ledger['ledgername'] != ' ') {

//     //         $check = db::table('studledger')
//     //             ->where('studid', $studid)
//     //             ->where('syid', $syid)
//     //             ->where('semid', $sem)
//     //             ->where('particulars', $ledger['ledgername'])
//     //             ->where('createdby', auth()->user()->id)
             
//     //             ->first();

//     //         if ($check != null) {
//     //             return response()->json([
//     //                 'status' => 2,
//     //                  'message' => 'Already Exist'
//     //             ]);
//     //         }

//     //         DB::table('studledger')->insert([
//     //             'studid' => $studid,
//     //             'syid' => $syid,
//     //             'semid' => $sem,
//     //             'particulars' => $ledger['ledgername'],
//     //             'amount' => $ledger['charges'],
//     //             'payment' => $ledger['payments'],
//     //             'createdby' => auth()->user()->id,
//     //             'createddatetime' => now(),
//     //             'deleted' => 0

//     //         ]);
//     //     }
//     // }

//     $balance = $request->input('balance');
//     $nextsydesc = $request->input('nextsydesc');
//     $semdesc = $request->input('semdesc');

//     $currentsy = $request->input('currentsy');
//     $currentsydesc = $request->input('currentsydesc');
//     $currentsem = $request->input('currentsem');
//     $currentsemdesc = $request->input('currentsemdesc');

//     $balclass_id= db::table('balforwardsetup')
//     ->where('studid', $studid)
//     ->get();

//     $check = db::table('studledger')
//         ->where('studid', $studid)
//         ->where('syid', $currentsy)
//         ->where('semid', $currentsem)
//         ->where('particulars', 'LIKE', '%OLD ACCOUNTS FORWARDED TO SY%'. $nextsydesc . '-' . $semdesc.'%')
//         ->where('createdby', auth()->user()->id)
//         ->where('deleted', 0)
//         ->first();

//     if ($check != null) {
//         return response()->json([
//             'status' => 2,
//              'message' => 'Already Exist'
//         ]);
//     }

//     DB::table('studledger')->insert([
//         'studid' => $studid,
//         'syid' => $currentsy,
//         'semid' => $currentsem,
//         'particulars' => 'OLD ACCOUNTS FORWARDED TO SY'. ' ' . $nextsydesc . '-' . $semdesc,
//         'payment' => $balance,
//         'createdby' => auth()->user()->id,
//         'createddatetime' => now(),
//         'deleted' => 0

//         // OLD ACCOUNTS FORWARDED TO SY 2026-2027 - 1ST SEMESTER

//     ]);

//     $check = db::table('studledger')
//         ->where('studid', $studid)
//         ->where('syid', $syid)
//         ->where('semid', $sem)
//         ->where('particulars', 'LIKE', '%OLD ACCOUNTS FORWARDED FROM SY%'. $currentsydesc . '-' . $currentsemdesc.'%')
//         ->where('createdby', auth()->user()->id)
//         ->where('deleted', 0)
//         ->first();

//     if ($check != null) {
//         return response()->json([
//             'status' => 2,
//              'message' => 'Already Exist'
//         ]);
//     }

//     DB::table('studledger')->insert([
//         'studid' => $studid,
//         'syid' => $syid,
//         'semid' => $sem,
//         'particulars' => 'OLD ACCOUNTS FORWARDED FROM SY'. ' ' . $currentsydesc . '-' . $currentsemdesc,
//         'amount' => $balance,
//         'createdby' => auth()->user()->id,
//         'createddatetime' => now(),
//         'deleted' => 0

//         // OLD ACCOUNTS FORWARDED TO SY 2026-2027 - 1ST SEMESTER

//     ]);



//     return response()->json([
//                 'status' => 1,
//                 'message' => 'Forwarded balances to next school year.',
//             ]);
// }

// public function forwardBalanceToNextSY(Request $request)
// {
//     $studid = $request->input('studid');
//     $syid = $request->input('syid');
//     $sem = $request->input('sem');
   

//     $balance = $request->input('balance');
//     $nextsydesc = $request->input('nextsydesc');
//     $semdesc = $request->input('semdesc');

//     $currentsy = $request->input('currentsy');
//     $currentsydesc = $request->input('currentsydesc');
//     $currentsem = $request->input('currentsem');
//     $currentsemdesc = $request->input('currentsemdesc');

//     $balclass_id = db::table('balforwardsetup')
//     ->first();

//     $check = db::table('studledger')
//         ->where('studid', $studid)
//         ->where('syid', $currentsy)
//         ->where('semid', $currentsem)
//         ->where('particulars', 'LIKE', '%OLD ACCOUNTS FORWARDED TO SY%'. $nextsydesc . '-' . $semdesc.'%')
//         ->where('createdby', auth()->user()->id)
//         ->where('deleted', 0)
//         ->first();

//     if ($check != null) {
//         return response()->json([
//             'status' => 2,
//              'message' => 'Already Exist'
//         ]);
//     }

//     DB::table('studledger')->insert([
//         'studid' => $studid,
//         'syid' => $currentsy,
//         'semid' => $currentsem,
//         'particulars' => 'OLD ACCOUNTS FORWARDED TO SY'. ' ' . $nextsydesc . '-' . $semdesc,
//         'classid' => $balclass_id->classid ?? null,
//         'payment' => $balance,
//         'createdby' => auth()->user()->id,
//         'createddatetime' => now(),
//         'deleted' => 0

//         // OLD ACCOUNTS FORWARDED TO SY 2026-2027 - 1ST SEMESTER

//     ]);

//     $check = db::table('studledger')
//         ->where('studid', $studid)
//         ->where('syid', $syid)
//         ->where('semid', $sem)
//         ->where('particulars', 'LIKE', '%OLD ACCOUNTS FORWARDED FROM SY%'. $currentsydesc . '-' . $currentsemdesc.'%')
//         ->where('createdby', auth()->user()->id)
//         ->where('deleted', 0)
//         ->first();

//     if ($check != null) {
//         return response()->json([
//             'status' => 2,
//              'message' => 'Already Exist'
//         ]);
//     }

//     DB::table('studledger')->insert([
//         'studid' => $studid,
//         'syid' => $syid,
//         'semid' => $sem,
//         'particulars' => 'OLD ACCOUNTS FORWARDED FROM SY'. ' ' . $currentsydesc . '-' . $currentsemdesc,
//         'classid' => $balclass_id->classid ?? null,
//         'amount' => $balance,
//         'createdby' => auth()->user()->id,
//         'createddatetime' => now(),
//         'deleted' => 0

//         // OLD ACCOUNTS FORWARDED TO SY 2026-2027 - 1ST SEMESTER

//     ]);



//     return response()->json([
//                 'status' => 1,
//                 'message' => 'Forwarded balances to next school year.',
//             ]);
// }


public function forwardBalanceToNextSY(Request $request)
{
    $studid = $request->input('studid');
    $syid = $request->input('syid');
    $sem = $request->input('sem');

    $stud = DB::table('studinfo')
        ->where('id', $studid)
        ->first();

    $levelid = 0;

    // First check enrolledstud
    $einfo = DB::table('enrolledstud')
        ->select('id', 'studstatus', 'levelid')
        ->where('syid', $syid)
        ->where(function($q) use($sem){
            if($sem == 3) {
                $q->where('ghssemid', 3);
            } else {
                $q->where('ghssemid', '!=', 3);
            }
        })
        ->where('studid', $studid)
        ->where('deleted', 0)
        ->first();

    if ($einfo) {
        $levelid = $einfo->levelid;
    } else {
        // Check sh_enrolledstud
        $einfo = DB::table('sh_enrolledstud')
            ->select('id', 'studstatus', 'levelid')
            ->where('syid', $syid)
            ->where(function($q) use($sem) {
                if ($sem == 3) {
                    $q->where('semid', 3);
                } else {
                    if (DB::table('schoolinfo')->first()->shssetup == 0) {
                        $q->where('semid', $sem);
                    } else {
                        $q->where('semid', '!=', 3);
                    }
                }
            })
            ->where('studid', $studid)
            ->where('deleted', 0)
            ->first();

        if ($einfo) {
            $levelid = $einfo->levelid;
        } else {
            // Check college_enrolledstud
            $einfo = DB::table('college_enrolledstud')
                ->select('id', 'studstatus', 'yearlevel as levelid')
                ->where('syid', $syid)
                ->where('semid', $sem)
                ->where('studid', $studid)
                ->where('deleted', 0)
                ->first();

            if ($einfo) {
                $levelid = $einfo->levelid;
            } else {
                // Check tesda_enrolledstud
                $einfo = DB::table('tesda_enrolledstud')
                    ->select('id', 'studstatus', 'batchid as levelid')
                    ->where('studid', $studid)
                    ->where('deleted', 0)
                    ->first();

                if ($einfo) {
                    $levelid = 26; // TESDA fallback level ID
                }
            }
        }
    }

    $balance = $request->input('balance');
    $nextsydesc = $request->input('nextsydesc');
    $semdesc = $request->input('semdesc');
    $currentsy = $request->input('currentsy');
    $currentsydesc = $request->input('currentsydesc');
    $currentsem = $request->input('currentsem');
    $currentsemdesc = $request->input('currentsemdesc');

    $balclass_id = DB::table('balforwardsetup')->first();

    // Check if already forwarded (TO next SY)
    $check = DB::table('studledger')
        ->where('studid', $studid)
        ->where('syid', $currentsy)
        ->where('semid', $currentsem)
        ->where('particulars', 'LIKE', '%OLD ACCOUNTS FORWARDED TO SY%'. $nextsydesc . '-' . $semdesc.'%')
        ->where('createdby', auth()->user()->id)
        ->where('deleted', 0)
        ->first();

    if ($check) {
        return response()->json([
            'status' => 2,
            'message' => 'Already Exist'
        ]);
    }

    // Insert forwarded TO SY
    DB::table('studledger')->insert([
        'studid' => $studid,
        'syid' => $currentsy,
        'semid' => $currentsem,
        'particulars' => 'OLD ACCOUNTS FORWARDED TO SY '. $nextsydesc . '-' . $semdesc,
        'classid' => $balclass_id->classid ?? null,
        'payment' => $balance,
        'createdby' => auth()->user()->id,
        'createddatetime' => now(),
        'deleted' => 0
    ]);

    // Check if already forwarded (FROM current SY)
    $check = DB::table('studledger')
        ->where('studid', $studid)
        ->where('syid', $syid)
        ->where('semid', $sem)
        ->where('particulars', 'LIKE', '%OLD ACCOUNTS FORWARDED FROM SY%'. $currentsydesc . '-' . $currentsemdesc.'%')
        ->where('createdby', auth()->user()->id)
        ->where('deleted', 0)
        ->first();

    if ($check) {
        return response()->json([
            'status' => 2,
            'message' => 'Already Exist'
        ]);
    }

    // Insert forwarded FROM SY
    DB::table('studledger')->insert([
        'studid' => $studid,
        'syid' => $syid,
        'semid' => $sem,
        'particulars' => 'OLD ACCOUNTS FORWARDED FROM SY '. $currentsydesc . '-' . $currentsemdesc,
        'classid' => $balclass_id->classid ?? null,
        'amount' => $balance,
        'createdby' => auth()->user()->id,
        'createddatetime' => now(),
        'deleted' => 0
    ]);

    $balclassid = $balclass_id->classid ?? null;

    $studledger = DB::table('studledger')
        ->where('studid', $studid)
        ->where('classid', $balclassid)
        ->where('syid', $syid)
        ->where('deleted', 0)
        ->where(function ($q) use ($levelid, $sem) {
            if (in_array($levelid, [14, 15])) {
                if (DB::table('schoolinfo')->first()->shssetup == 0) {
                    $q->where('semid', $sem);
                }
            }
            if ($levelid >= 17 && $levelid <= 25) {
                $q->where('semid', $sem);
            }
        })
        ->get();

    foreach ($studledger as $ledger) {
        $studpaysched = DB::table('studpayscheddetail')
            ->where('studid', $studid)
            ->where('particulars', $ledger->particulars)
            ->where('syid', $syid)
            ->where('deleted', 0)
            ->where(function ($q) use ($levelid, $sem) {
                if (in_array($levelid, [14, 15])) {
                    if (DB::table('schoolinfo')->first()->shssetup == 0) {
                        $q->where('semid', $sem);
                    }
                }
                if ($levelid >= 17 && $levelid <= 25) {
                    $q->where('semid', $sem);
                }
            })
            ->first();

        if (!$studpaysched) {
            DB::table('studpayscheddetail')->insert([
                'studid' => $studid,
                'semid' => $sem,
                'syid' => $syid,
                'classid' => $balclassid,
                'paymentno' => 1,
                'particulars' => $ledger->particulars,
                'amount' => $ledger->amount,
                'amountpay' => 0,
                'balance' => $ledger->amount,
                'createddatetime' => now(),
            ]);

            DB::table('studledgeritemized')->insert([
                'studid' => $studid,
                'semid' => $sem,
                'syid' => $syid,
                'classificationid' => $balclassid,
                'itemamount' => $ledger->amount,
                'createddatetime' => now(),
            ]);
        }
    }

    return response()->json([
        'status' => 1,
        'message' => 'Forwarded balances to next school year.',
    ]);
}


public function oldAccounts_getStudData(Request $request)
{
    $student = db::table('studinfo')
        ->select('studinfo.id', 'studinfo.sid', 'studinfo.lastname', 'studinfo.firstname', 'studinfo.middlename', 'gradelevel.levelname', 'studinfo.levelid')
        ->join('gradelevel', 'studinfo.levelid', '=', 'gradelevel.id')
        ->where('studinfo.id', $request->input('studentId'))
        ->where('studinfo.deleted', 0)
        ->orderBy('studinfo.lastname')
        ->orderBy('studinfo.firstname')
        ->get();

    return $student;
}

// public static function create_student_information(Request $request){

//     try{
//           $allowduplicate = $request->get('allowduplicate');
//           $userid = $request->get('userid');
//           $syid = $request->get('syid');
//           $semid = $request->get('semid');
//           $courseid = $request->get('courseid');
//           $strandid = $request->get('strandid');
//           $firstname = $request->get('firstname');
//           $lastname = $request->get('lastname');
//           $middlename = $request->get('middlename');
//           $suffix = $request->get('suffix');
//           $dob = $request->get('dob');
//           $semail = $request->get('semail');
//           $gender = $request->get('gender');
//           $levelid = $request->get('levelid');
//           $admissiontype = $request->get('admissiontype');
//           $mol = $request->get('mol');
//           $contactno = $request->get('contactno');
//           $gcontactno = $request->get('gcontactno');
//           $fcontactno = $request->get('fcontactno');
//           $mcontactno = $request->get('mcontactno');
//           $ismothernum = $request->get('ismothernum');
//           $isfathernum = $request->get('isfathernum');
//           $isguardiannum = $request->get('isguardiannum');
//           $altsid = $request->get('altsid');
//           $maidenname = $request->get('maidenname');

//           $acadprogid = DB::table('gradelevel')
//                             ->where('id',$levelid)
//                             ->where('deleted',0)
//                             ->select('acadprogid')
//                             ->first()
//                             ->acadprogid;

//           if($acadprogid != 5 && $acadprogid != 6){
//                 if($semid == 2){
//                       $semid = 1;
//                 }
//           }

//           if($userid == ''){
//                 $userid = auth()->user()->id;
//           }

//           $check = DB::table('studinfo')
//                             ->join('studinfo_more','studinfo.id','studinfo_more.studid')
//                             ->where('studinfo.lastname',$lastname)
//                             ->where('studinfo.firstname',$firstname)
//                             ->where( function($query) use ($middlename){
//                                   if($middlename != ''){
//                                         $query->where('studinfo.middlename',$middlename);
//                                   }
//                             })
//                             ->where('studinfo.dob',$dob)
//                             ->where( function($query) use ($maidenname) {
//                                   if ($maidenname != '') {
//                                         $query->where('studinfo_more.mothermaiden', $maidenname);
//                                   }
//                             })
//                             ->where('studinfo.deleted',0)
//                             ->count();

//           if($check > 0 && ($allowduplicate == false || $allowduplicate == 'false') ){
//                 return array((object)[
//                       'status'=>2,
//                       'message'=>'Student Already Exist!'
//                 ]);
//           }else{

//                 $fathername = '';

//                 $fathername .= $request->get('flname') != '' ?  $request->get('flname') : '';
//                 $fathername .= $request->get('ffname') != '' ?   $request->get('flname') != '' ? ', '.$request->get('ffname') : $request->get('ffname') : '';
//                 $fathername .= $request->get('fsuffix') != '' ?  ', '.$request->get('fsuffix') : '';
//                 $fathername .= $request->get('fmname') != '' ?  ', '.$request->get('fmname') : '';

//                 $mothername = '';
//                 $mothername .= $request->get('mlname') != '' ?  $request->get('mlname') : '';
//                 $mothername .= $request->get('mfname') != '' ?   $request->get('mlname') != '' ? ', '.$request->get('mfname') : $request->get('mfname') : '';
//                 $mothername .= $request->get('msuffix') != '' ?  ', '.$request->get('msuffix') : '';
//                 $mothername .= $request->get('mmname') != '' ?  ', '.$request->get('mmname') : '';

//                 $guardianname = '';
//                 $guardianname .= $request->get('glname') != '' ?  $request->get('glname') : '';
//                 $guardianname .= $request->get('gfname') != '' ?   $request->get('glname') != '' ? ', '.$request->get('gfname') : $request->get('gfname') : '';
//                 $guardianname .= $request->get('gsuffix') != '' ?  ', '.$request->get('gsuffix') : '';
//                 $guardianname .= $request->get('gmname') != '' ?  ', '.$request->get('gmname') : '';

//                 $studid = DB::table('studinfo')
//                             ->insertGetId([
//                                   'firstname'=>strtoupper($firstname) == '' ? null : strtoupper($firstname),
//                                   'lastname'=>strtoupper($lastname) == '' ? null : strtoupper($lastname),
//                                   'middlename'=>strtoupper($middlename) == '' ? null : strtoupper($middlename),
//                                   'suffix'=>$suffix,
//                                   'dob'=>$dob,
//                                   'semail'=>$semail,
//                                   'lrn'=>$request->get('lrn'),
//                                   'gender'=>$gender,
//                                   'levelid'=>$levelid,
//                                   'strandid'=>$strandid,
//                                   'courseid'=>$courseid,
//                                   'street'=>strtoupper($request->get('street')),

//                                   'fathername'=>strtoupper($fathername),
//                                   'mothername'=>strtoupper($mothername),
//                                   'guardianname'=>strtoupper($guardianname),

//                                   'foccupation'=>strtoupper($request->get('foccupation')),
//                                   'moccupation'=>strtoupper($request->get('moccupation')),
//                                   'goccupation'=>strtoupper($request->get('goccupation')),
//                                   'guardianrelation'=>strtoupper($request->get('relation')),

//                                   'mtname'=>strtoupper($request->get('mtname')),
//                                   'egname'=>strtoupper($request->get('egname')),
//                                   'religionname'=>strtoupper($request->get('religionname')),

//                                   'religionid'=>$request->get('religionid'),
//                                   'egid'=>$request->get('egid'),
//                                   'mtid'=>$request->get('mtid'),
//                                   'bloodtype'=>$request->get('bloodtype'),
//                                   'barangay'=>strtoupper($request->get('barangay')),
//                                   'city'=>strtoupper($request->get('city')),
//                                   'province'=>strtoupper($request->get('province')),
//                                   'district'=>strtoupper($request->get('district')),
//                                   'region'=>strtoupper($request->get('region')),
//                                   'nationality'=>strtoupper($request->get('nationality')),
//                                   'studtype'=>$request->get('studtype'),
//                                   'grantee'=>$request->get('studgrantee'),
//                                   'pantawid'=>$request->get('pantawid'),
//                                   'deleted'=>0,
//                                   'createdby'=>$userid,
//                                   'contactno'=>str_replace('-','',$contactno),
//                                   'gcontactno'=>str_replace('-','',$gcontactno),
//                                   'fcontactno'=>str_replace('-','',$fcontactno),
//                                   'mcontactno'=>str_replace('-','',$mcontactno),
//                                   'ismothernum'=>$ismothernum,
//                                   'isfathernum'=>$isfathernum,
//                                   'isguardannum'=>$isguardiannum,
//                                   'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
//                                   'lastschoolatt'=>$request->get('lastschoolatt'),
//                                   'pob'=>$request->get('pob'),
//                                   'civilstatus'=>$request->get('maritalstatus'),
//                                   'altsid'=>$altsid,
//                                   'mol'=>$mol
//                             ]);

//                       DB::table('studinfo_more')
//                             ->insert([
//                                   'studid'=>$studid,
//                                   'ffname'=>strtoupper($request->get('ffname')),
//                                   'fmname'=>strtoupper($request->get('fmname')),
//                                   'flname'=>strtoupper($request->get('flname')),
//                                   'fsuffix'=>strtoupper($request->get('fsuffix')),

//                                   'mfname'=>strtoupper($request->get('mfname')),
//                                   'mmname'=>strtoupper($request->get('mmname')),
//                                   'mlname'=>strtoupper($request->get('mlname')),
//                                   'msuffix'=>strtoupper($request->get('msuffix')),

//                                   'gfname'=>strtoupper($request->get('gfname')),
//                                   'gmname'=>strtoupper($request->get('gmname')),
//                                   'glname'=>strtoupper($request->get('glname')),
//                                   'gsuffix'=>strtoupper($request->get('gsuffix')),

//                                   'psschoolname'=>strtoupper($request->get('psschoolname')),
//                                   'pssy'=>$request->get('pssy'),
//                                   'gsschoolname'=>strtoupper($request->get('gsschoolname')),
//                                   'gssy'=>$request->get('gssy'),
//                                   'jhsschoolname'=>strtoupper($request->get('jhsschoolname')),
//                                   'jhssy'=>$request->get('jhssy'),
//                                   'shsschoolname'=>strtoupper($request->get('shsschoolname')),
//                                   'shsstrand'=>$request->get('shsstrand'),
//                                   'shssy'=>$request->get('shssy'),
//                                   'collegeschoolname'=>strtoupper($request->get('collegeschoolname')),
//                                   'collegesy'=>$request->get('collegesy'),

//                                   'nocitf'=>$request->get('nocitf'),
//                                   'noce'=>$request->get('noce'),
//                                   'oitfitf'=>$request->get('oitf'),
//                                   'glits'=>$request->get('glits'),
//                                   'scn'=>$request->get('scn'),
//                                   'cmaosla'=>$request->get('cmaosla'),
//                                   'lsah'=>$request->get('lsah'),

//                                   'fea'=>$request->get('fea'),
//                                   'mea'=>$request->get('mea'),
//                                   'gea'=>$request->get('gea'),

//                                   'fha'=>$request->get('fha'),
//                                   'mha'=>$request->get('mha'),
//                                   'gha'=>$request->get('gha'),

//                                   'fmi'=>$request->get('fmi'),
//                                   'mmi'=>$request->get('mmi'),
//                                   'gmi'=>$request->get('gmi'),

//                                   'fosoi'=>$request->get('fosoi'),
//                                   'mosoi'=>$request->get('mosoi'),
//                                   'gosoi'=>$request->get('gosoi'),

//                                   'fethnicity'=>$request->get('fethnicity'),
//                                   'methnicity'=>$request->get('methnicity'),
//                                   'gethnicity'=>$request->get('gethnicity'),

//                                   'bec_cell'=>$request->get('bec_cell'),
//                                   'chapelzone'=>$request->get('chapelzone'),

//                                   'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
//                                   'mothermaiden'=>strtoupper($request->get('maidenname')),

//                                   'mfi'=>$request->get('mfi'),
//                                   'psschooltype'=>$request->get('psschooltype'),
//                                   'gsschooltype'=>$request->get('gsschooltype'),
//                                   'jhsschooltype'=>$request->get('jhsschooltype'),
//                                   'shsschooltype'=>$request->get('shsschooltype'),
//                                   'collegeschooltype'=>$request->get('collegeschooltype')

//                             ]);


//                       DB::table('apmc_midinfo')
//                             ->insert([
//                                   'studid'=>$studid,
//                                   'vacc'=>$request->get('vacc'),
//                                   'vacc_type_id'=>$request->get('vacc_type_1st'),
//                                   'vacc_type_2nd_id'=>$request->get('vacc_type_2nd'),
//                                   'vacc_type_booster'=>$request->get('vacc_type_booster') != null ?  $request->get('vacc_type_text_booster') : null,
//                                   'vacc_type'=>$request->get('vacc_type_1st') != null ? $request->get('vacc_type_text_1st') : null,
//                                   'vacc_type_2nd'=>$request->get('vacc_type_2nd') != null ?  $request->get('vacc_type_text_2nd') : null,
//                                   'vacc_card_id'=>$request->get('vacc_card_id'),
//                                   'dose_date_1st'=>$request->get('dose_date_1st'),
//                                   'dose_date_2nd'=>$request->get('dose_date_2nd'),
//                                   'philhealth'=>$request->get('philhealth'),
//                                   'bloodtype'=>$request->get('bloodtype'),
//                                   'allergy_to_med'=>$request->get('allergy_to_med'),
//                                   'med_his'=>$request->get('med_his'),
//                                   'other_med_info'=>$request->get('other_med_info'),


//                                   'createdby'=>$userid,
//                                   'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
//                             ]);



//                 $acadprog = DB::table('gradelevel')
//                                   ->where('id',$levelid)
//                                   ->where('deleted',0)
//                                   ->first()
//                                   ->acadprogid;

//                 if($acadprogid == 6 || $acadprogid == 8){

//                       $curriculum = $request->get('curriculum');

//                       DB::table('college_studentcurriculum')
//                             ->where('studid',$studid)
//                             ->where('deleted',0)
//                             ->take(1)
//                             ->update([
//                                   'deleted'=>1,
//                                   'deletedby'=>$userid,
//                                   'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
//                             ]);

//                       DB::table('college_studentcurriculum')
//                             ->insert([
//                                   'studid'=>$studid,
//                                   'curriculumid'=>$curriculum,
//                                   'createdby'=>$userid,
//                                   'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
//                             ]);
//                 }

//                 $sid = \App\RegistrarModel::idprefix($acadprog,$studid);

//                 DB::table('studinfo')
//                       ->where('id', $studid)
//                       ->take(1)
//                       ->update([
//                             'sid' => $sid,
//                             'picurl'=>'storage/STUDENT/'.$sid.'.jpg',
//                       ]);

//                 // DB::table('student_pregistration')
//                 //       ->insert([
//                 //             'transtype'=>'WALK-IN',
//                 //             'admission_type'=>$admissiontype,
//                 //             'semid'=>$semid,
//                 //             'studid'=>$studid,
//                 //             'syid'=>$syid,
//                 //             'deleted'=>0,
//                 //             'status'=>'SUBMITTED',
//                 //             'gradelvl_to_enroll'=>$levelid,
//                 //             'createdby'=>$userid,
//                 //             'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
//                 //       ]);


//                 return array((object)[
//                       'status'=>1,
//                       'message'=>'Student Created!'
//                 ]);
//           }

//     }catch(\Exception $e){
//           return self::store_error($e);
//     }

// }


public static function create_student_information(Request $request){

    try{
          $allowduplicate = $request->get('allowduplicate');
          $userid = $request->get('userid');
          $syid = $request->get('syid');
          $semid = $request->get('semid');
          $courseid = $request->get('courseid');
          $strandid = $request->get('strandid');
          $firstname = $request->get('firstname');
          $lastname = $request->get('lastname');
          $middlename = $request->get('middlename');
          $suffix = $request->get('suffix');
          $dob = $request->get('dob');
          $semail = $request->get('semail');
          $gender = $request->get('gender');
          $levelid = $request->get('levelid');
          $levelname = $request->get('levelname');
          $admissiontype = $request->get('admissiontype');
          $mol = $request->get('mol');
          $contactno = $request->get('contactno');
          $gcontactno = $request->get('gcontactno');
          $fcontactno = $request->get('fcontactno');
          $mcontactno = $request->get('mcontactno');
          $ismothernum = $request->get('ismothernum');
          $isfathernum = $request->get('isfathernum');
          $isguardiannum = $request->get('isguardiannum');
          $altsid = $request->get('altsid');
          $maidenname = $request->get('maidenname');

          $acadprogid = DB::table('gradelevel')
                            ->where('id',$levelid)
                            ->where('deleted',0)
                            ->select('acadprogid')
                            ->first()
                            ->acadprogid;

          if($acadprogid != 5 && $acadprogid != 6){
                if($semid == 2){
                      $semid = 1;
                }
          }

          if($userid == ''){
                $userid = auth()->user()->id;
          }

          $check = DB::table('studinfo')
                            ->join('studinfo_more','studinfo.id','studinfo_more.studid')
                            ->where('studinfo.lastname',$lastname)
                            ->where('studinfo.firstname',$firstname)
                            ->where( function($query) use ($middlename){
                                  if($middlename != ''){
                                        $query->where('studinfo.middlename',$middlename);
                                  }
                            })
                            ->where('studinfo.dob',$dob)
                            ->where( function($query) use ($maidenname) {
                                  if ($maidenname != '') {
                                        $query->where('studinfo_more.mothermaiden', $maidenname);
                                  }
                            })
                            ->where('studinfo.deleted',0)
                            ->count();

          if($check > 0 && ($allowduplicate == false || $allowduplicate == 'false') ){
                return array((object)[
                      'status'=>2,
                      'message'=>'Student Already Exist!'
                ]);
          }else{

                $fathername = '';

                $fathername .= $request->get('flname') != '' ?  $request->get('flname') : '';
                $fathername .= $request->get('ffname') != '' ?   $request->get('flname') != '' ? ', '.$request->get('ffname') : $request->get('ffname') : '';
                $fathername .= $request->get('fsuffix') != '' ?  ', '.$request->get('fsuffix') : '';
                $fathername .= $request->get('fmname') != '' ?  ', '.$request->get('fmname') : '';

                $mothername = '';
                $mothername .= $request->get('mlname') != '' ?  $request->get('mlname') : '';
                $mothername .= $request->get('mfname') != '' ?   $request->get('mlname') != '' ? ', '.$request->get('mfname') : $request->get('mfname') : '';
                $mothername .= $request->get('msuffix') != '' ?  ', '.$request->get('msuffix') : '';
                $mothername .= $request->get('mmname') != '' ?  ', '.$request->get('mmname') : '';

                $guardianname = '';
                $guardianname .= $request->get('glname') != '' ?  $request->get('glname') : '';
                $guardianname .= $request->get('gfname') != '' ?   $request->get('glname') != '' ? ', '.$request->get('gfname') : $request->get('gfname') : '';
                $guardianname .= $request->get('gsuffix') != '' ?  ', '.$request->get('gsuffix') : '';
                $guardianname .= $request->get('gmname') != '' ?  ', '.$request->get('gmname') : '';

                $studid = DB::table('studinfo')
                            ->insertGetId([
                                  'firstname'=>strtoupper($firstname) == '' ? null : strtoupper($firstname),
                                  'lastname'=>strtoupper($lastname) == '' ? null : strtoupper($lastname),
                                  'middlename'=>strtoupper($middlename) == '' ? null : strtoupper($middlename),
                                  'suffix'=>$suffix,
                                  'dob'=>$dob,
                                  'semail'=>$semail,
                                  'lrn'=>$request->get('lrn'),
                                  'gender'=>$gender,
                                  'levelid'=>$levelid,
                                  'strandid'=>$strandid,
                                  'courseid'=>$courseid,
                                  'street'=>strtoupper($request->get('street')),

                                  'fathername'=>strtoupper($fathername),
                                  'mothername'=>strtoupper($mothername),
                                  'guardianname'=>strtoupper($guardianname),

                                  'foccupation'=>strtoupper($request->get('foccupation')),
                                  'moccupation'=>strtoupper($request->get('moccupation')),
                                  'goccupation'=>strtoupper($request->get('goccupation')),
                                  'guardianrelation'=>strtoupper($request->get('relation')),

                                  'mtname'=>strtoupper($request->get('mtname')),
                                  'egname'=>strtoupper($request->get('egname')),
                                  'religionname'=>strtoupper($request->get('religionname')),

                                  'religionid'=>$request->get('religionid'),
                                  'egid'=>$request->get('egid'),
                                  'mtid'=>$request->get('mtid'),
                                  'bloodtype'=>$request->get('bloodtype'),
                                  'barangay'=>strtoupper($request->get('barangay')),
                                  'city'=>strtoupper($request->get('city')),
                                  'province'=>strtoupper($request->get('province')),
                                  'district'=>strtoupper($request->get('district')),
                                  'region'=>strtoupper($request->get('region')),
                                  'nationality'=>strtoupper($request->get('nationality')),
                                  'studtype'=>$request->get('studtype'),
                                  'grantee'=>$request->get('studgrantee'),
                                  'pantawid'=>$request->get('pantawid'),
                                  'deleted'=>0,
                                  'createdby'=>$userid,
                                  'contactno'=>str_replace('-','',$contactno),
                                  'gcontactno'=>str_replace('-','',$gcontactno),
                                  'fcontactno'=>str_replace('-','',$fcontactno),
                                  'mcontactno'=>str_replace('-','',$mcontactno),
                                  'ismothernum'=>$ismothernum,
                                  'isfathernum'=>$isfathernum,
                                  'isguardannum'=>$isguardiannum,
                                  'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                  'lastschoolatt'=>$request->get('lastschoolatt'),
                                  'pob'=>$request->get('pob'),
                                  'civilstatus'=>$request->get('maritalstatus'),
                                  'altsid'=>$altsid,
                                  'mol'=>$mol
                            ]);

                      DB::table('studinfo_more')
                            ->insert([
                                  'studid'=>$studid,
                                  'ffname'=>strtoupper($request->get('ffname')),
                                  'fmname'=>strtoupper($request->get('fmname')),
                                  'flname'=>strtoupper($request->get('flname')),
                                  'fsuffix'=>strtoupper($request->get('fsuffix')),

                                  'mfname'=>strtoupper($request->get('mfname')),
                                  'mmname'=>strtoupper($request->get('mmname')),
                                  'mlname'=>strtoupper($request->get('mlname')),
                                  'msuffix'=>strtoupper($request->get('msuffix')),

                                  'gfname'=>strtoupper($request->get('gfname')),
                                  'gmname'=>strtoupper($request->get('gmname')),
                                  'glname'=>strtoupper($request->get('glname')),
                                  'gsuffix'=>strtoupper($request->get('gsuffix')),

                                  'psschoolname'=>strtoupper($request->get('psschoolname')),
                                  'pssy'=>$request->get('pssy'),
                                  'gsschoolname'=>strtoupper($request->get('gsschoolname')),
                                  'gssy'=>$request->get('gssy'),
                                  'jhsschoolname'=>strtoupper($request->get('jhsschoolname')),
                                  'jhssy'=>$request->get('jhssy'),
                                  'shsschoolname'=>strtoupper($request->get('shsschoolname')),
                                  'shsstrand'=>$request->get('shsstrand'),
                                  'shssy'=>$request->get('shssy'),
                                  'collegeschoolname'=>strtoupper($request->get('collegeschoolname')),
                                  'collegesy'=>$request->get('collegesy'),

                                  'nocitf'=>$request->get('nocitf'),
                                  'noce'=>$request->get('noce'),
                                  'oitfitf'=>$request->get('oitf'),
                                  'glits'=>$request->get('glits'),
                                  'scn'=>$request->get('scn'),
                                  'cmaosla'=>$request->get('cmaosla'),
                                  'lsah'=>$request->get('lsah'),

                                  'fea'=>$request->get('fea'),
                                  'mea'=>$request->get('mea'),
                                  'gea'=>$request->get('gea'),

                                  'fha'=>$request->get('fha'),
                                  'mha'=>$request->get('mha'),
                                  'gha'=>$request->get('gha'),

                                  'fmi'=>$request->get('fmi'),
                                  'mmi'=>$request->get('mmi'),
                                  'gmi'=>$request->get('gmi'),

                                  'fosoi'=>$request->get('fosoi'),
                                  'mosoi'=>$request->get('mosoi'),
                                  'gosoi'=>$request->get('gosoi'),

                                  'fethnicity'=>$request->get('fethnicity'),
                                  'methnicity'=>$request->get('methnicity'),
                                  'gethnicity'=>$request->get('gethnicity'),

                                  'bec_cell'=>$request->get('bec_cell'),
                                  'chapelzone'=>$request->get('chapelzone'),

                                  'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                  'mothermaiden'=>strtoupper($request->get('maidenname')),

                                  'mfi'=>$request->get('mfi'),
                                  'psschooltype'=>$request->get('psschooltype'),
                                  'gsschooltype'=>$request->get('gsschooltype'),
                                  'jhsschooltype'=>$request->get('jhsschooltype'),
                                  'shsschooltype'=>$request->get('shsschooltype'),
                                  'collegeschooltype'=>$request->get('collegeschooltype')

                            ]);


                      DB::table('apmc_midinfo')
                            ->insert([
                                  'studid'=>$studid,
                                  'vacc'=>$request->get('vacc'),
                                  'vacc_type_id'=>$request->get('vacc_type_1st'),
                                  'vacc_type_2nd_id'=>$request->get('vacc_type_2nd'),
                                  'vacc_type_booster'=>$request->get('vacc_type_booster') != null ?  $request->get('vacc_type_text_booster') : null,
                                  'vacc_type'=>$request->get('vacc_type_1st') != null ? $request->get('vacc_type_text_1st') : null,
                                  'vacc_type_2nd'=>$request->get('vacc_type_2nd') != null ?  $request->get('vacc_type_text_2nd') : null,
                                  'vacc_card_id'=>$request->get('vacc_card_id'),
                                  'dose_date_1st'=>$request->get('dose_date_1st'),
                                  'dose_date_2nd'=>$request->get('dose_date_2nd'),
                                  'philhealth'=>$request->get('philhealth'),
                                  'bloodtype'=>$request->get('bloodtype'),
                                  'allergy_to_med'=>$request->get('allergy_to_med'),
                                  'med_his'=>$request->get('med_his'),
                                  'other_med_info'=>$request->get('other_med_info'),


                                  'createdby'=>$userid,
                                  'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                            ]);



                $acadprog = DB::table('gradelevel')
                                  ->where('id',$levelid)
                                  ->where('deleted',0)
                                  ->first()
                                  ->acadprogid;

                if($acadprogid == 6 || $acadprogid == 8){

                      $curriculum = $request->get('curriculum');

                      DB::table('college_studentcurriculum')
                            ->where('studid',$studid)
                            ->where('deleted',0)
                            ->take(1)
                            ->update([
                                  'deleted'=>1,
                                  'deletedby'=>$userid,
                                  'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                            ]);

                      DB::table('college_studentcurriculum')
                            ->insert([
                                  'studid'=>$studid,
                                  'curriculumid'=>$curriculum,
                                  'createdby'=>$userid,
                                  'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                            ]);
                }

                $sid = \App\RegistrarModel::idprefix($acadprog,$studid);

                DB::table('studinfo')
                      ->where('id', $studid)
                      ->take(1)
                      ->update([
                            'sid' => $sid,
                            'picurl'=>'storage/STUDENT/'.$sid.'.jpg',
                      ]);

                // DB::table('student_pregistration')
                //       ->insert([
                //             'transtype'=>'WALK-IN',
                //             'admission_type'=>$admissiontype,
                //             'semid'=>$semid,
                //             'studid'=>$studid,
                //             'syid'=>$syid,
                //             'deleted'=>0,
                //             'status'=>'SUBMITTED',
                //             'gradelvl_to_enroll'=>$levelid,
                //             'createdby'=>$userid,
                //             'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                //       ]);


                return array((object)[
                      'status'=>1,
                      'message'=>'Student Created!',
                      'id'=>$studid,
                      'sid'=>$sid,
                      'levelid'=>$levelid,
                      'levelname'=>$levelname,
                      'studid'=>[
                            'firstname'=>DB::table('studinfo')->where('id',$studid)->first()->firstname,
                            'lastname'=>DB::table('studinfo')->where('id',$studid)->first()->lastname,
                            'middlename'=>DB::table('studinfo')->where('id',$studid)->first()->middlename,
                      ]

                ]);
          }

    }catch(\Exception $e){
          return self::store_error($e);
    }

}

public function newoldAccounts_saving(Request $request)
{
    // dd($request->all());
    $studid = $request->input('studid');
    $syid = $request->input('syid');
    $sydesc = $request->input('sydesc');
    $sem = $request->input('sem');
    $semdesc = $request->input('semdesc');
    $balance = $request->input('balance');
   
    $particulars = 'OLD ACCOUNTS FORWARDED FROM SY'. ' ' . $sydesc . '-' . $semdesc;
    $balclassid = db::table('balforwardsetup')->first()->classid;

    $exist = DB::table('studledger')
        ->where('studid', $studid)
        ->where('syid', $syid)
        ->where('semid', $sem)
        ->where('deleted', 0)
        ->where('particulars', $particulars)
        ->first();

    if ($exist) {
        DB::table('studledger')
            ->where('id', $exist->id)
            ->update([
                'amount' => $balance,
                'updatedby' => auth()->user()->id,
                'updateddatetime' => now()
        ]);

        $studpaysched = DB::table('studpayscheddetail')
            ->where('studid', $studid)
            ->where('syid', $syid)
            ->where('semid', $sem)
            ->where('deleted', 0)
            ->where('particulars', $particulars)
            ->first();

            if ($studpaysched) {
                DB::table('studpayscheddetail')
                    ->where('id', $studpaysched->id)
                    ->update([
                        'amount' => $balance,
                        'balance' => $balance - $studpaysched->amountpay,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => now()
                ]);
            }

    }else{
        DB::table('studledger')->insert([
            'studid' => $studid,
            'syid' => $syid,
            'semid' => $sem,
            'classid' => $balclassid,
            'particulars' => $particulars,
            'amount' => $balance,
            'createdby' => auth()->user()->id,
            'createddatetime' => now(),
            'deleted' => 0
        ]);

        DB::table('studpayscheddetail')->insert([
            'studid' => $studid,
            'semid' => $sem,
            'syid' => $syid,
            'classid' => $balclassid,
            'paymentno' => 1,
            'particulars' => $particulars,
            'amount' => $balance,
            'amountpay' => 0,
            'balance' => $balance,
            'createddatetime' => now(),
        ]);
    }





    return response()->json([
        'status'=>1,
        'message'=>'Old Account Created!',
    ]);
}

function importStudentBalanceAccounts()
{
    // Path to the Excel file in the public directory
    // $filePath = public_path('exceloldaccount/college account_receivable.xls');
    // $filePath = public_path('exceloldaccount/sh_ar.xls');
    // $filePath = public_path('exceloldaccount/v_ar.xls');
    $filePath = public_path('exceloldaccount/STII-COLLEGE RUNNING BALANCE.xlsx.xlsx');
    
    try {
            // Load the Excel file
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            
            // Get the highest row and column
            $highestRow = $worksheet->getHighestRow() ;
            $highestColumn = $worksheet->getHighestColumn();
            // dd($highestRow);
            
            // Start from row 15 (where the data begins based on your sample)
            for ($row = 15; $row <= $highestRow; $row++) {
                $rowData = $worksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false)[0];
                dd($rowData);
                // Skip empty rows
                if (empty($rowData[0]) || empty($rowData[1])) {
                continue;
                }
                
                $remBal = 0;
                if($rowData[13] == null || $rowData[13] == '0.00' || !$rowData[13]){
                        $remBal = $rowData[10] - $rowData[12];
                }

                // Prepare the data for insertion
                $data = [
                        'student_id' => $rowData[0] ?? null,
                        'student_name' => $rowData[1] ?? null,
                        'course' => $rowData[4] ?? null,
                        'level' => $rowData[5] ?? null,
                        'total_assessment' => $rowData[6] ? floatval(str_replace(['$', ','], '', $rowData[6])) : 0,
                        'total_discounts' => $rowData[8] ? floatval(str_replace(['$', ','], '', $rowData[8])) : 0,
                        'total_adjustment' => $rowData[9] ? floatval(str_replace(['$', ','], '', $rowData[9])) : 0,
                        'actual_assessment' => $rowData[10] ? floatval(str_replace(['$', ','], '', $rowData[10])) : 0,
                        'total_amount_paid' => $rowData[12] ? floatval(str_replace(['$', ','], '', $rowData[12])) : 0,
                        'summer_balance' => $rowData[13] ? floatval(str_replace(['$', ','], '', $rowData[13])) : $remBal,
                        'created_at' => now(),
                        'updated_at' => now(),
                ];
                
                // Insert into database
                $result = DB::table('student_balance_accounts')->insertGetId($data);

                if ($result) {

                        $studinfo = DB::table('studinfo')->whereRaw("concat(lastname, ', ', firstname, ' ', middlename) like '%".$data['student_name']."%'")->first();
                        if ($studinfo) {
                            $studid =  $studinfo->id;
                            $syid = 2;
                            $sydesc = '2024-2025';
                            
                            $sem = 1;
                            $semdesc = '2ND SEMESTER';
                            $balance =  $rowData[13] ? floatval(str_replace(['$', ','], '', $rowData[13])) : $remBal;
                            
                            
                            DB::table('studledger')->insert([
                                    
                                    'studid' => $studid,
                                    'syid' => $syid,
                                    'semid' => $sem,
                                    'particulars' => 'OLD ACCOUNTS FORWARDED FROM SY'. ' ' . $sydesc . '-' . $semdesc,
                                    'amount' => $balance,
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => now(),
                                    'deleted' => 0
                            
                                    // OLD ACCOUNTS FORWARDED TO SY 2026-2027 - 1ST SEMESTER
                            
                            ]);
                        }  
                }
                        

            }
            
            return true;
            
    } catch (\Exception $e) {
            // Log the error
            dd($e->getMessage());
            \Log::error('Excel Import Error: ' . $e->getMessage());
            return false;
    }
}

function importStudentBalanceAccounts2()
{
    // Path to the Excel file in the public directory
    // $filePath = public_path('exceloldaccount/college account_receivable.xls');
    // $filePath = public_path('exceloldaccount/sh_ar.xls');
    // $filePath = public_path('exceloldaccount/v_ar.xls');
    $filePath = public_path('exceloldaccount/STII-COLLEGE RUNNING BALANCE.xlsx');
    
    try {
            // Load the Excel file
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            
            // Get the highest row and column
            $highestRow = $worksheet->getHighestRow() ;
            $highestColumn = $worksheet->getHighestColumn();
            // dd($highestRow);
            $counter = 0;
            // Start from row 15 (where the data begins based on your sample)
            for ($row = 6; $row <= $highestRow; $row++) {
                $rowData = $worksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false)[0];
                // dd($rowData);
                // Skip empty rows
                if (empty($rowData[0]) || empty($rowData[1])) {
                        continue;
                }
                
                $data = [
                        'student_id' => $rowData[0] ?? null,
                        'student_name' => $rowData[1] ?? null,
                        'course' => $rowData[2] ?? null,
                        'level' => $rowData[3] ?? null,
                        'balance' => $rowData[12]
                ];
                // dd($data);
                
                $studinfo = DB::table('studinfo')->whereRaw("concat(lastname, ', ', firstname, ' ', middlename) like '%".$data['student_name']."%'")->first();

                // dd($studinfo);
                
                if ($studinfo) { 
                        $counter++;
                        $studid =  $studinfo->id;
                        $syid = 1;
                        $sydesc = '2024-2025';
                        
                        $sem = 2;
                        $semdesc = '2ND SEMESTER';
                        $balance = $data['balance'];
                        
                        echo $counter . ' - ' . $studid . ' - ' . $data['student_name'] . ' - ' . $balance . '<br>';

                        // OLD ACCOUNTS FORWARDING
                        $exist = DB::table('studledger')->where('studid', $studid)->where('syid', $syid)->where('semid', $sem)->where('particulars', 'OLD ACCOUNTS FORWARDED FROM SY'. ' ' . $sydesc . '-' . $semdesc)->first();
                        
                        if ($exist) {
                            // dd($exist);
                            DB::table('studledger')->where('id', $exist->id)->update([
                                    'amount' => $balance
                            ]);
                        } else {
                            DB::table('studledger')->insert([
                                    'studid' => $studid,
                                    'syid' => $syid,
                                    'semid' => $sem,
                                    'particulars' => 'OLD ACCOUNTS FORWARDED FROM SY'. ' ' . $sydesc . '-' . $semdesc,
                                    'amount' => $balance,
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => now(),
                                    'deleted' => 0
                            ]);
                        }
                }  
                    
                        

            }
            
            return true;
            
    } catch (\Exception $e) {
            // Log the error
            dd($e->getMessage());
            \Log::error('Excel Import Error: ' . $e->getMessage());
            return false;
    }
}

public function importExcel()
{
    set_time_limit(3600); // 1 hour timeout

    $particulars = 'OLD ACCOUNTS FORWARDED FROM SY 2024-2025-2ND SEMESTER';
    $studpayscheds = DB::table('studpayscheddetail')
        // ->where('studid', $studid)
        ->where('particulars', $particulars)
        ->where('syid', 2)
        ->where('deleted', 0)
        ->select('studid', 'particulars', 'amount', 'balance')
        ->get();

    $studentIds = $studpayscheds->pluck('studid');
    // dd($studentIds);

   $oa = DB::table('studledger')
    ->where('particulars', 'like', '%OLD ACCOUNTS FORWARDED FROM%')
    ->where('syid',  2)
    ->where('semid',  1)
    // ->where('classid',  null)
    ->where('deleted', 0)
    // ->where('studid', 2901)
    // ->where('id', '>', 3200)
    // ->take(1000)
    ->whereNotIn('studid', $studentIds)

    ->orderBy('id', 'asc')
    ->get();


    dd($oa);


    $balclassid = db::table('balforwardsetup')->first()->classid;
    // $particulars = 'OLD ACCOUNTS FORWARDED FROM SY 2024-2025 2ND SEMESTER'; ;
    // $reverse_particulars = 'OLD ACCOUNTS FORWARDED TO SY ' . FinanceModel::getSYDesc() . ' ' . FinanceModel::getSemDesc();

    // dd($oa);
    if ($oa->count() > 0) {
        // dd($oa);

        foreach ($oa as $stud) {
            # code...
            // db::table('studledger')
            //     ->where('id', $stud->id)
            //     ->update([
            //         // 'studid' => $stud->studid,
            //         'syid' => FinanceModel::getSYID(),
            //         'semid' => FinanceModel::getSemID(),
            //         'classid' => $balclassid,
            //         // 'particulars' =>$particulars,
            //         // 'amount' => $amount,
            //         // 'createddatetime' => FinanceModel::getServerDateTime(),
            //         'deleted' => $stud->amount < 0 ? 1 : 0,
            //         // 'void' => 0
            //     ]);

            if($stud->amount > 0){
                // db::table('studledger')
                //     ->insert([
                //         'studid' => $stud->studid,
                //         'syid' => 1,
                //         'semid' => 2,
                //         'classid' => $balclassid,
                //         'particulars' =>$reverse_particulars,
                //         'payment' => $stud->amount,
                //         'createddatetime' => FinanceModel::getServerDateTime(),
                //         'deleted' => 0,
                //         'void' => 0
                //     ]);

                $studid = $stud->studid;
                $syid = 2; // Assuming 2 is the current SY ID
                $sem = 1; // Assuming 1 is the current Sem ID

                 $studledger = DB::table('studledger')
                    ->where('studid', $studid)
                    ->where('classid', $balclassid)
                    ->where('syid', 2)
                    ->where('deleted', 0)
                    // ->where(function ($q) use ($levelid, $sem) {
                    //     if (in_array($levelid, [14, 15])) {
                    //         if (DB::table('schoolinfo')->first()->shssetup == 0) {
                    //             $q->where('semid', $sem);
                    //         }
                    //     }
                    //     if ($levelid >= 17 && $levelid <= 25) {
                    //         $q->where('semid', $sem);
                    //     }
                    // })
                    ->get();
                    // dd($studledger);

                    foreach ($studledger as $ledger) {
                        $studpaysched = DB::table('studpayscheddetail')
                            ->where('studid', $studid)
                            ->where('particulars', $ledger->particulars)
                            ->where('syid', 2)
                            ->where('deleted', 0)
                            // ->where(function ($q) use ($levelid, $sem) {
                            //     if (in_array($levelid, [14, 15])) {
                            //         if (DB::table('schoolinfo')->first()->shssetup == 0) {
                            //             $q->where('semid', $sem);
                            //         }
                            //     }
                            //     if ($levelid >= 17 && $levelid <= 25) {
                            //         $q->where('semid', $sem);
                            //     }
                            // })
                            ->first();
                            // dd($studpaysched);

                        if (!$studpaysched) {
                            DB::table('studpayscheddetail')->insert([
                                'studid' => $studid,
                                'semid' => $sem,
                                'syid' => $syid,
                                'classid' => $balclassid,
                                'paymentno' => 1,
                                'particulars' => $ledger->particulars,
                                'amount' => $ledger->amount,
                                'amountpay' => 0,
                                'balance' => $ledger->amount,
                                'createddatetime' => now(),
                            ]);

                            DB::table('studledgeritemized')->insert([
                                'studid' => $studid,
                                'semid' => $sem,
                                'syid' => $syid,
                                'classificationid' => $balclassid,
                                'itemamount' => $ledger->amount,
                                'createddatetime' => now(),
                            ]);

                            echo 'Inserted: ' . $ledger->particulars . ' for student ID: ' . $stud->id . '<br>';
                        }else{
                            echo 'exist! ' . $stud->id . '<br>'; 

                        }

                    }

            }
        }

        return 'done';
    }




    // $result = $this->importStudentBalanceAccounts2();
    
    // if ($result) {


    //         // forward old accounts
            



    //         return 'Excel file imported successfully!';
    //         // return back()->with('success', 'Excel file imported successfully!');
    // }

    // return 'There was an error importing the Excel file.';
    // // return back()->with('error', 'There was an error importing the Excel file.');
}

    
}
class DCPR extends TCPDF {

    // //Page header
    // public function Header() {
    //     // Logo
    //     // $this->Image('@'.file_get_contents('/home/xxxxxx/public_html/xxxxxxxx/uploads/logo/logo.png'),10,6,0,13);
    //     $schoollogo = DB::table('schoolinfo')->first();
    //     $image_file = public_path().'/'.$schoollogo->picurl;
    //     $extension = explode('.', $schoollogo->picurl);
    //     $this->Image('@'.file_get_contents($image_file),20,9,17,17);

    //     if(strtolower($schoollogo->abbreviation) == 'msmi')
    //     {
    //         $this->Cell(0, 15, 'Page '.$this->getAliasNumPage(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    //         $this->Cell(0, 25, date('m/d/Y'), 0, false, 'R', 0, '', 0, false, 'T', 'M');   
    //     }
        
    //     $schoolname = $this->writeHTMLCell(false, 50, 40, 10, '<span style="font-weight: bold">'.$schoollogo->schoolname.'</span>', false, false, false, $reseth=true, $align='L', $autopadding=true);
    //     $schooladdress = $this->writeHTMLCell(false, 50, 40, 15, '<span style="font-weight: bold; font-size: 10px;">'.$schoollogo->address.'</span>', false, false, false, $reseth=true, $align='L', $autopadding=true);
    //     $title = $this->writeHTMLCell(false, 50, 40, 20, 'Cash Receipt Summary', false, false, false, $reseth=true, $align='L', $autopadding=true);
    //     // Ln();
    // }

    // Page footer
    public function Footer() {
        $schoollogo = DB::table('schoolinfo')->first();
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        // $this->Cell(0, 15, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        // $this->Cell(0, 5, date('m/d/Y'), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        
        if(strtolower($schoollogo->abbreviation) != 'msmi')
        {
            $this->Cell(0, 10, date('l, F d, Y'), 0, false, 'L', 0, '', 0, false, 'T', 'M');
            $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
            // $this->Cell(0, 15, date('m/d/Y'), 0, false, 'R', 0, '', 0, false, 'T', 'M');   
        }
    }



 

}
