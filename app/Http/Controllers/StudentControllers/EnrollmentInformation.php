<?php

namespace App\Http\Controllers\StudentControllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use File;
use Image;
use Session;
use Validator;
use App\Http\Controllers\FinanceControllers\UtilityController;

class EnrollmentInformation extends \App\Http\Controllers\Controller
{

    public static function billingassesment(Request $request){

        $month = $request->get('month');
        $syid = $request->get('syid');
        $levelid = $request->get('levelid');

        if($syid == null){
            $syid = DB::table('sy')->where('isactive',1)->first()->id;
        }

        // $studendinfo = Session::get('studentInfo');
        if(auth()->user()->type == 9){
            $studid = DB::table('studinfo')->where('sid',str_replace('P','',auth()->user()->email))->first()->id;
        }else{
            $studid = DB::table('studinfo')->where('sid',str_replace('S','',auth()->user()->email))->first()->id;
        }
        
      

        $billdet = DB::table('studpayscheddetail')
                ->where('studid',$studid)
                ->where('studpayscheddetail.deleted','0')
                ->where('syid',$syid)
                ->select(
                    'studpayscheddetail.particulars',
                    'studpayscheddetail.paymentno',
                    'amount as amountdue',
                    'duedate',
                    DB::raw("MONTH(duedate) as duemonth"),
                    'balance',
                    'amountpay'
                )
                ->orderBy('duedate','asc')
                ->get();
                
        $in_range = true;   
        $month_found = false;
        $total_assesment = 0;
        $all_assesment = array();

        if($month != null){
            foreach(collect($billdet)->where('duedate',null)->values() as $key=>$item){
                if($item->balance > 0){
                    array_push($all_assesment,(object)[
                        'particulars'=>$item->particulars,
                        'balance'=>number_format($item->balance,2),
                        'amountdue'=>number_format($item->amountdue,2),
                        'duedate'=>$item->duedate
                    ]);
                }
            }
            foreach(collect($billdet)->where('duedate','!=',null)->groupBy('duemonth')->values() as $key=>$item){
                $particulars = '';
                $balance = 0;
                $amountdue = 0;
                $duedate = null;
                if($in_range){
                    foreach($item as $month_item){
                        if($month_item->balance > 0){
                            $duemonth = strtoupper(\Carbon\Carbon::create($month_item->duedate)->isoFormat('MMMM'));
                            $particulars .= $month_item->particulars.' / ';
                            $balance += $month_item->balance;
                            $amountdue += $month_item->amountdue;
                        }
                        $duedate = $month_item->duedate;
                    }
                    if($balance > 0){
                        $particulars = substr($particulars,0,-3);
                        array_push($all_assesment,(object)[
                            'particulars'=>$particulars .' '. $duemonth,
                            'balance'=>number_format($balance,2),
                            'amountdue'=>number_format($amountdue,2),
                            'duedate'=>$duedate
                        ]);
                    }
                    
                }
                if(\Carbon\Carbon::create($duedate)->isoFormat('MMM') == \Carbon\Carbon::create(null,$month)->isoFormat('MMM')){
                    $in_range = false;
                }
            }
        }else{
           
            foreach(collect($billdet)->where('duedate',null) as $key=>$item){
                array_push($all_assesment,(object)[
                    'particulars'=>$item->particulars,
                    'balance'=>number_format($item->balance,2),
                    'amountdue'=>number_format($item->amountdue,2),
                    'duedate'=>$item->duedate
                ]);
            }
            
            foreach(collect($billdet)->where('duedate','!=',null)->groupBy('duemonth')->values() as $key=>$item){
                $particulars = '';
                $balance = 0;
                $amountdue = 0;
                $duedate = null;
                
                foreach($item as $month_item){
                    $duemonth = strtoupper(\Carbon\Carbon::create($month_item->duedate)->isoFormat('MMMM'));
                    $particulars .= $month_item->particulars.' / ';
                    $balance += $month_item->balance;
                    $amountdue += $month_item->amountdue;
                    $duedate = $month_item->duedate;
                }
                $particulars = substr($particulars,0,-3);
                array_push($all_assesment,(object)[
                    'particulars'=>$particulars .' '. $duemonth,
                    'balance'=>number_format($balance,2),
                    'amountdue'=>number_format($amountdue,2),
                    'duedate'=>$duedate
                ]);
            }
        }

        return $all_assesment;

    }    
    
    public static function schedule(Request $request){

        $month = $request->get('month');
        $syid = $request->get('syid');
        $levelid = $request->get('levelid');

        // return $request->all();
        // $studendinfo = Session::get('studentInfo');
        // $studid = $studendinfo->id;

        if(auth()->user()->type == 9){
            $studid = DB::table('studinfo')->where('sid',str_replace('P','',auth()->user()->email))->first()->id;
        }else{
            $studid = DB::table('studinfo')->where('sid',str_replace('S','',auth()->user()->email))->first()->id;
        }

        if($syid == null){
            $syid = DB::table('sy')->where('isactive',1)->first()->id;
        }

        $day = \Carbon\Carbon::now('Asia/Manila')->dayOfWeekIso ;
        $enrollment =  \App\Models\SuperAdmin\SuperAdminData::enrollment_record($syid, null, $studid);
        $levelid = $enrollment[0]->levelid;
        $acadprog = $enrollment[0]->acadprogid;
        $sectionid = $enrollment[0]->sectionid;


        if($acadprog == 5){
            $strandid = $enrollment[0]->strandid;
            $semid = DB::table('semester')->where('isactive',1)->first()->id;
            $schedule = \App\Http\Controllers\PrincipalControllers\ScheduleController::get_schedule($levelid,$syid,$sectionid,$semid,$strandid);
        }else if($acadprog == 6 || $acadprog == 8){
            $semid = DB::table('semester')->where('isactive',1)->first()->id;
            $schedule = \App\Http\Controllers\SuperAdminController\StudentLoading::collegestudentsched_plot($studid,$syid,$semid);
                if($schedule[0]->status == 1){
                $schedule = $schedule[0]->info;
                $schedule = collect($schedule)->where('schedstatus','!=','DROPPED')->values();
                foreach($schedule as $item){
                    $item->subjdesc = $item->subjDesc;
                    $item->teacher = $item->teacher;
                    // foreach($item->schedule as $sched_item){
                    //     $sched_item->teacher = $item->teacher;
                    // }
                }
            }else{
                $schedule = array();
            }
        }
        else{

            $schedule = \App\Http\Controllers\PrincipalControllers\ScheduleController::get_schedule($levelid,$syid,$sectionid);

        }

        // return $schedule;

        $today_sched = array();
        foreach($schedule as $item){
            $daysArray = explode(',', $item->days);
            $contains = in_array($day, $daysArray) ? 1 : 0;
            if($contains == 1){
                array_push($today_sched,(object)[
                    'subject'=>$item->subjdesc,
                    'teacher'=>$item->teacher,
                    'time'=>$item->schedtime,
                    'sort'=>\Carbon\Carbon::create($item->start)->isoFormat('HH'),
                    'start'=>\Carbon\Carbon::create($item->start)->isoFormat('HH:mm'),
                    'end'=>\Carbon\Carbon::create($item->end)->isoFormat('HH:mm')
                ]);
            }
            
        }
        // foreach($schedule as $item){
        //     if(isset($item->schedule)){
        //         foreach($item->schedule as $sched_item){
                    
        //             $contains = collect($sched_item->days)->contains(function ($value, $key) use($day){
        //                         return $value == $day;
        //                     });

                            
        //             if($contains){
        //                 array_push($today_sched,(object)[
        //                     'subject'=>$item->subjdesc,
        //                     'teacher'=>$sched_item->teacher,
        //                     'time'=>$sched_item->time,
        //                     'sort'=>\Carbon\Carbon::create($sched_item->start)->isoFormat('HH'),
        //                     'start'=>\Carbon\Carbon::create($sched_item->start)->isoFormat('HH:mm'),
        //                     'end'=>\Carbon\Carbon::create($sched_item->end)->isoFormat('HH:mm')
        //                 ]);
        //             }
        //         }
        //     }else{
        //         return collect($item);
        //     }
           
        // }


        // $time = \Carbon\Carbon::now('Asia/Manila')->isoFormat('HH:mm');
        $time = Carbon::now('Asia/Manila');
        
        // return $time;
        $current_sched = true;
        $next_sched = true;        
        $cn_sched = array();

        // foreach(collect($today_sched)->sortBy('sort')->values() as $item){
        //     if(!$current_sched && $next_sched){
        //         $item->sched="next";
        //         array_push($cn_sched,$item);
        //         $next_sched = false;
        //     }
        //     if($time >= $item->start && $time <= $item->end){

        //         if($current_sched){
        //             $item->sched="current";
        //             array_push($cn_sched,$item);
        //             $current_sched = false;
        //         }
        //     }
        // }

        // if(count($cn_sched) == 0){
        //     $next_shed = collect($today_sched)->sortBy('sort')->where('start','>',$time)->values();
        //     if(count($next_shed) > 0){
        //         $next_shed[0]->sched ="next";
        //         array_push($cn_sched,$next_shed[0]);
        //     }
        // }

        foreach (collect($today_sched)->sortBy('sort')->values() as $item) {
            $start_time = Carbon::createFromFormat('H:i', $item->start, 'Asia/Manila');
            $end_time = Carbon::createFromFormat('H:i', $item->end, 'Asia/Manila');
        
            if ($time->between($start_time, $end_time, true)) {
                $item->sched = 'current';
            } elseif ($time->greaterThan($end_time)) {
                $item->sched = 'previous';
            } elseif ($time->lessThan($start_time)) {
                $item->sched = 'next';
            }
        
            $cn_sched[] = $item; // Add the updated schedule to the $cn_sched array
        }
        

        return $cn_sched;

    }

    public static function enrollment(Request $request){

        if(auth()->user()->type == 9){
            $studid = DB::table('studinfo')->where('sid',str_replace('P','',auth()->user()->email))->first()->id;
        }else{
            $studid = DB::table('studinfo')->where('sid',str_replace('S','',auth()->user()->email))->first()->id;
        }

        if($request->get('all') == "all"){
            $syid = null;
            $semid = null;
        }else{
            $syid = DB::table('sy')->where('isactive',1)->first()->id;
            $semid = DB::table('semester')
                            ->where('isactive',1)
                            ->first()
                            ->id;
        }

        
                        
        $enrollment =  \App\Models\SuperAdmin\SuperAdminData::enrollment_record($syid, $semid, $studid);
        foreach($enrollment as $item){
            $middlename = explode(" ",$item->middlename);
            $temp_middle = '';
            if($middlename != null){
                foreach ($middlename as $middlename_item) {
                    if(strlen($middlename_item) > 0){
                        $temp_middle .= $middlename_item[0].'.';
                    } 
                }
            }

            $item->student=$item->lastname.', '.$item->firstname.' '.$item->suffix.' '.$temp_middle;

            if($item->acadprogid != 6){
                $temp_adviser = DB::table('sectiondetail')
                                ->where('sectionid',$item->sectionid)
                                ->where('syid',$item->syid)
                                ->where('sectiondetail.deleted',0)
                                ->join('teacher',function($join){
                                    $join->on('sectiondetail.teacherid','=','teacher.id');
                                    $join->where('teacher.deleted',0);
                                })
                                ->select(
                                    'lastname',
                                    'firstname',
                                    'middlename',
                                    'suffix',
                                    'teacherid'
                                )
                                ->first();

                $adviser = '';
                if(isset($temp_adviser->lastname)){
                    $middlename = explode(" ",$temp_adviser->middlename);
                    $temp_middle = '';
                    if($middlename != null){
                        foreach ($middlename as $middlename_item) {
                            if(strlen($middlename_item) > 0){
                                $temp_middle .= $middlename_item[0].'.';
                            } 
                        }
                    }
                    $adviser = $temp_adviser->firstname.' '.$temp_middle.' '.$temp_adviser->lastname.' '.$temp_adviser->suffix;
                }
                $item->adviser = $adviser;

            }else{
                $item->adviser = null;
            }

           
        }

        return $enrollment;
       

    }

    public static function cashier_transactions(){

        if(auth()->user()->type == 9){
            $studid = DB::table('studinfo')->where('sid',str_replace('P','',auth()->user()->email))->first()->id;
        }else{
            $studid = DB::table('studinfo')->where('sid',str_replace('S','',auth()->user()->email))->first()->id;
        }

        $chrng_trans = DB::table('chrngtrans')
                            ->where('cancelled',0)
                            ->where('studid',$studid)
                            ->leftJoin('onlinepayments',function($join){
                                $join->on('chrngtrans.id','=','onlinepayments.chrngtransid');
                            })
                            ->select(
                                'chrngtrans.amountpaid',
                                'chrngtrans.paytype',
                                'chrngtrans.transdate',
                                'chrngtrans.ornum',
                                'onlinepayments.id as oid'
                            )
                            ->orderBy('transdate','desc')
                            ->get();

        foreach($chrng_trans as $item){
            $item->transdate = \Carbon\Carbon::create($item->transdate)->isoFormat('MMM DD, YYYY hh:mm a');
            $item->amountpaid = number_format($item->amountpaid,2);
        }

        return $chrng_trans;

    }   

    public static function student_ledger(Request $request){

        $syid = $request->get('syid');
        $semid = $request->get('semid');

        // $studendinfo = Session::get('studentInfo');
        // $studid = $studendinfo->id;

        if(auth()->user()->type == 9){
            $studid = DB::table('studinfo')->where('sid',str_replace('P','',auth()->user()->email))->first()->id;
        }else{
            $studid = DB::table('studinfo')->where('sid',str_replace('S','',auth()->user()->email))->first()->id;
        }

        $schoolinfo = DB::table('schoolinfo')->first();

        if( $schoolinfo->abbreviation == 'GBBC'){
            if($syid == 2){
                return array();
            }
        }

        //0 - naay second
        //1 - whole year

        $gradelevelinfo = DB::table('gradelevel')
                            ->where('id',$request->get('levelid'))
                            ->first();

        $schoolinfo = DB::table('schoolinfo')->first();

        if($gradelevelinfo->acadprogid == 6){
            $semid = [$request->get('semid')];
        }elseif($gradelevelinfo->acadprogid == 4){
            if($schoolinfo->shssetup == 1){
                $semid = [1,2];
            }else{
                $semid = [$request->get('semid')];
            }
        }else{
            $semid = [1,2];
        }

        $levelid = $request->get('levelid');
        if($levelid == 14 || $levelid == 15){
            $feesid = DB::table('sh_enrolledstud')
                    ->where('studid',$studid)
                    ->where('syid',$syid)
                    ->where('semid',$semid)
                    ->select('feesid')
                    ->first();
        }else if($levelid >= 16 && $levelid <= 17){
            $feesid = DB::table('college_enrolledstud')
                    ->where('studid',$studid)
                    ->where('syid',$syid)
                    ->where('semid',$semid)
                    ->select('feesid')
                    ->first();
        }else{
            $feesid = DB::table('enrolledstud')
                    ->where('studid',$studid)
                    ->where('syid',$syid)
                    ->select('feesid')
                    ->first();
        }

        if($feesid){
            $request->merge(['feesid' => $feesid->feesid]);
            $request->merge(['studid' => $studid]);
			UtilityController::resetpayment_v3($request);
        }


        $ledger = DB::table('studledger')
                        ->where('studid',$studid)
                        ->where('syid',$syid)
                        ->whereIn('semid',$semid)
                        ->where('deleted',0)
                        ->where('void',0)
                        ->select('particulars','amount','payment','ornum','classid','studid')
                        ->orderBy('amount','desc')
                        ->orderBy('createddatetime')
                        ->get();


        return $ledger;
        
    }

    public static function uploaded_payments(){

        if(auth()->user()->type == 9){
            $student = DB::table('studinfo')->where('sid',str_replace('P','',auth()->user()->email))->first();
        }else{
            $student = DB::table('studinfo')->where('sid',str_replace('S','',auth()->user()->email))->first();
        }

       

        $studid = $student->id;

        $student = DB::table('studinfo')
                    ->where('deleted',0)
                    ->where('id',$studid)
                    ->first();

        

        $onlinepayments = DB::table('onlinepayments')
                            ->join('paymenttype',function($join){
                                $join->on('onlinepayments.paymentType','=','paymenttype.id');
                                $join->where('paymenttype.deleted',0);
                            })
                            ->leftJoin('chrngtrans',function($join){
                                $join->on('onlinepayments.chrngtransid','=','chrngtrans.id');
                                $join->where('chrngtrans.cancelled',0);
                            })
                            ->where(function($query) use($student){
                                $query->where('queingcode',$student->sid);
                                $query->orWhere('queingcode',$student->lrn);
                                $query->orWhere('queingcode',$student->qcode);
                            })
                            ->where('onlinepayments.isapproved','!=',6)
                            ->select(
                                'onlinepayments.*',
                                'description',
                                'ornum',
                                'chrngtrans.transdate as cashtransdate'
                            )
                            ->orderBy('paymentDate','desc')
                            ->get();

        foreach($onlinepayments as $item){
            $item->TransDate = \Carbon\Carbon::create($item->TransDate)->isoFormat('MMM DD, YYYY');
            $item->paymentDate = \Carbon\Carbon::create($item->paymentDate)->isoFormat('MMM DD, YYYY');
        }

        return $onlinepayments;


    }   
    

    public static function send_payment(Request $request){

        $payment_type = $request->get('paymentType');
        $amount = str_replace(',','',$request->get('amount'));
        $transDate = $request->get('transDate');
        $refNum = $request->get('refNum');
        $contact = $request->get('input_number');
        $syid = $request->get('syid');
        $semid = $request->get('semid');



        $time = \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss');
        $file = $request->file('recieptImage');
        $extension = $file->getClientOriginalExtension();

        if(auth()->user()->type == 9){
            $studid = DB::table('studinfo')->where('sid',str_replace('P','',auth()->user()->email))->first()->id;
        }else{
            $studid = DB::table('studinfo')->where('sid',str_replace('S','',auth()->user()->email))->first()->id;
        }

        $studsid = DB::table('studinfo')
                    ->where('deleted',0)
                    ->where('id',$studid)
                    ->first()
                    ->sid;

        $check_refnum = DB::table('onlinepayments')
                            ->where('refNum',$refNum)
                            ->count();

        if($check_refnum > 0){
            return array((object)[
                'status'=>0,
                'message'=>'Reference Number already exist'
            ]);
        }

        DB::table('onlinepayments')
                    ->insert([
                        'queingcode'=>$studsid,
                        'paymentType'=>$payment_type,
                        'amount'=>$amount,
                        'isapproved'=>0,
                        'TransDate'=>$transDate,
                        'refNum'=>$refNum,
                        'paymentDate'=>\Carbon\Carbon::now('Asia/Manila'),
                        'semid'=>$semid,
                        'syid'=>$syid,
                        'picUrl'=>'onlinepayments/'.$studsid.'/'.$studsid.'-payment-'.$time.'.'.$extension,
                        'opcontact'=>str_replace('-','',$contact)
                    ]);

        $urlFolder = str_replace('http://','',$request->root());
        $urlFolder = str_replace('https://','',$urlFolder);
        
        if (! File::exists(public_path().'onlinepayments/'.$studsid)) {

            $path = public_path('onlinepayments/'.$studsid);

            if(!File::isDirectory($path)){

                File::makeDirectory($path, 0777, true, true);
            }
            
        }
    
        if (! File::exists(dirname(base_path(), 1).'/'.$urlFolder.'/onlinepayments/'.$studsid)) {
            $cloudpath = dirname(base_path(), 1).'/'.$urlFolder.'/onlinepayments/'.$studsid;
            if(!File::isDirectory($cloudpath)){
                File::makeDirectory($cloudpath, 0777, true, true);
            }
        }
        

        $img = Image::make($file->path());

        $clouddestinationPath = dirname(base_path(), 1).'/'.$urlFolder.'/onlinepayments/'.$studsid.'/'.$studsid.'-payment-'.$time.'.'.$extension;

        $img->resize(1000, 1000, function ($constraint) {
            $constraint->aspectRatio();
        })->save($clouddestinationPath);

        $destinationPath = public_path('onlinepayments/'.$studsid.'/'.$studsid.'-payment-'.$time.'.'.$extension);

        $img->save($destinationPath);

        return array((object)[
            'status'=>1,
        ]);
     

    }

    public static function school_calendar(){

        $syid = DB::table('sy')
                    ->where('isactive',1)
                    ->first()
                    ->id;

        $year = \Carbon\Carbon::now('Asia/Manila')->isoFormat('YYYY');
        $month = \Carbon\Carbon::now('Asia/Manila')->isoFormat('MM');
        $day = \Carbon\Carbon::now('Asia/Manila')->isoFormat('DD');

        $schoolcalendar = DB::table('schoolcal')
                                ->whereMonth('datefrom', $month)
                                ->whereYear('datefrom', $year)
                                ->where('deleted','0')
                                ->where('syid',$syid)
                                ->select('description','datefrom','dateto')
                                ->orderBy('datefrom')
                                ->get();

        foreach($schoolcalendar as $item){

            if($item->datefrom == $item->dateto){
                $item->date = \Carbon\Carbon::create($item->datefrom)->isoFormat('DD');
                if(\Carbon\Carbon::create($item->datefrom)->isoFormat('DD') == $day){
                    $item->current = 1;
                }else{
                    $item->current = 0;
                }
            }else{
                $item->date = \Carbon\Carbon::create($item->datefrom)->isoFormat('DD') . ' - ' .\Carbon\Carbon::create($item->dateto)->isoFormat('DD') ;
                if(\Carbon\Carbon::create($item->datefrom)->isoFormat('DD') <= $day && \Carbon\Carbon::create($item->dateto)->isoFormat('DD') >= $day){
                    $item->current = 1;
                }else{
                    $item->current = 0;
                }
            }

            
        }

        return $schoolcalendar;

    }


    public static function grade_level_section(Request $request){

        $syid = $request->get('syid');
        $semid = $request->get('semid');

        // $studendinfo = Session::get('studentInfo');
        // $studid = $studendinfo->id;
        if(auth()->user()->type == 9){
            $studid = DB::table('studinfo')->where('sid',str_replace('P','',auth()->user()->email))->first()->id;
        }else{
            $studid = DB::table('studinfo')->where('sid',str_replace('S','',auth()->user()->email))->first()->id;
        }

        $check_enrollment = DB::table('enrolledstud')   
                ->join('sections',function($join){
                    $join->on('enrolledstud.sectionid','=','sections.id');
                    $join->where('sections.deleted',0);
                })
                ->join('gradelevel',function($join){
                    $join->on('enrolledstud.levelid','=','gradelevel.id');
                    $join->where('gradelevel.deleted',0);
                })
                ->where('enrolledstud.studid',$studid)
                ->where('enrolledstud.syid',$syid)
                ->where('enrolledstud.deleted',0)
                ->select(
                    'levelname',
                    'sectionname',
                    'enrolledstud.levelid'
                )
                ->get();

        if(count($check_enrollment) == 0){
            $check_enrollment = DB::table('sh_enrolledstud')   
                    ->join('sections',function($join){
                        $join->on('sh_enrolledstud.sectionid','=','sections.id');
                        $join->where('sections.deleted',0);
                    })
                    ->join('gradelevel',function($join){
                        $join->on('sh_enrolledstud.levelid','=','gradelevel.id');
                        $join->where('gradelevel.deleted',0);
                    })
                    ->join('sh_strand',function($join){
                        $join->on('sh_enrolledstud.strandid','=','sh_strand.id');
                        $join->where('sh_strand.deleted',0);
                    })
                    ->where('sh_enrolledstud.studid',$studid)
                    ->where('sh_enrolledstud.syid',$syid)
                    ->where('sh_enrolledstud.semid',1)
                    ->where('sh_enrolledstud.deleted',0)
                    ->select(
                        'levelname',
                        'sectionname',
                        'sh_enrolledstud.levelid',
                        'strandname'
                    )
                    ->get();

        }

        if(count($check_enrollment) == 0){

            $check_enrollment = DB::table('college_enrolledstud')   
                    ->join('college_sections',function($join){
                        $join->on('college_enrolledstud.sectionid','=','college_sections.id');
                        $join->where('college_sections.deleted',0);
                    })
                    ->join('college_courses','college_sections.courseID', '=', 'college_courses.id')
                    ->join('gradelevel',function($join){
                        $join->on('college_enrolledstud.yearLevel','=','gradelevel.id');
                        $join->where('gradelevel.deleted',0);
                    })
                    ->leftJoin('college_classsched',function($join){
                        $join->on('college_sections.id','=','college_classsched.sectionid');
                        $join->where('college_classsched.deleted',0);
                    })
                    ->where('college_enrolledstud.studid',$studid)
                    ->where('college_enrolledstud.syid',$syid)
                    ->where('college_enrolledstud.semid',$semid)
                    ->where('college_enrolledstud.deleted',0)
                    ->select(
                        'levelname',
                        'sectionDesc as sectionname',
                        'college_enrolledstud.yearLevel as levelid',
                        'college_classsched.id as schedid',
                        'college_enrolledstud.courseid',
                        'college_courses.courseabrv'
                    )
                    ->get();
                    return $check_enrollment;
                    if(count($check_enrollment) > 0){

                        $collegesection = DB::table('college_schedgroup_detail')
                                            ->where('college_schedgroup_detail.deleted',0)
                                            ->whereIn('schedid',collect($check_enrollment)->pluck('schedid'))
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
            
                        foreach($check_enrollment as $item){
                                    $courseid = $item->courseid;
                                    $checkcoursegroup = collect($collegesection)->where('schedid',$item->schedid)->where('courseid',$courseid)->values();
                                    if(count($checkcoursegroup) != 0){
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
                                                if(count($checkcoursegroup) != 0){
                                                        $text = $checkcoursegroup[0]->collegeabrv;
                                                        $text .= '-'.$checkcoursegroup[0]->levelname[0] . ' '.$checkcoursegroup[0]->schedgroupdesc;
                                                        $item->sectionname = $text;  
                                                }else{
                                                        $item->sectionname = 'Not Found';
                                                }
                                            }else{
                                                $item->sectionname = null;
                                            }
                                    }
                                }
                    } 

        }

       return $check_enrollment;


    }

    public static function class_schedule(Request $request){

        $syid = $request->get('syid');
        $type = $request->get('type');
        $semid = $request->get('semid');
        $strandid = null;

        if(auth()->user()->type == 9){
            $studid = DB::table('studinfo')->where('sid',str_replace('P','',auth()->user()->email))->first()->id;
        }else{
            $studid = DB::table('studinfo')->where('sid',str_replace('S','',auth()->user()->email))->first()->id;
        }


        $check_enrollment = DB::table('enrolledstud')   
                                ->where('studid',$studid)
                                ->where('syid',$syid)
                                ->where('deleted',0)
                                ->first();

        if(!isset($check_enrollment->id)){

            $check_enrollment = DB::table('sh_enrolledstud')   
                                    ->where('studid',$studid)
                                    ->where('syid',$syid)
                                    ->where('deleted',0)
                                    ->first();

            if(isset($check_enrollment->id)){
                $semid = $request->get('semid');
                $strandid = $check_enrollment->strandid;
            }
        }

        if(!isset($check_enrollment->id)){
            
            $check_enrollment = DB::table('college_enrolledstud')   
                                    ->where('studid',$studid)
                                    ->where('syid',$syid)
                                    ->where('deleted',0)
                                    ->first();

            if(isset($check_enrollment->id)){
                $semid = $request->get('semid');
                $check_enrollment->levelid = $check_enrollment->yearLevel;
                $check_enrollment->sectionid = $check_enrollment->sectionID;
            }
            
        }

        if(!isset($check_enrollment->id)){
            if($type == 'all'){
                return view('studentPortal.pages.schedplot')->with('schedule',array());
            }else{
                return array();
            }
        }

        $levelid = $check_enrollment->levelid;
        $sectionid = $check_enrollment->sectionid;
        
        if($levelid == 14 || $levelid == 15){
            $schedule = \App\Http\Controllers\PrincipalControllers\ScheduleController::get_schedule($levelid,$syid,$sectionid,$semid,$strandid);
        }
        elseif($levelid >= 17){
            $schedule = \App\Http\Controllers\SuperAdminController\StudentLoading::collegestudentsched_plot($studid,$syid,$semid);
            if($schedule[0]->status == 1){
                $schedule = $schedule[0]->info;
                $schedule = collect($schedule)->where('schedstatus','!=','DROPPED')->values();
                foreach($schedule as $item){
                    $item->subjdesc = $item->subjDesc;
                    $item->subjcode = $item->subjCode;
                    // foreach($item->schedule as $sched_item){
                    //     $sched_item->teacher = $item->teacher;
                    // }
                    $item->datatype = "college";
                }
            }else{
                $schedule = array();
            }
        }
        else{
            $schedule = \App\Http\Controllers\PrincipalControllers\ScheduleController::get_schedule($levelid,$syid,$sectionid,$semid,$strandid);
        
			$temp_sched = array();
            foreach($schedule as $item){
                if($item->isSP == 1){
                    $check_subject = DB::table('subjects_studspec')
                                        ->where('studid',$studid)
                                        ->where('deleted',0)
                                        ->where('syid',$syid)
                                        ->where('subjid',$item->subjid)
                                        ->first();

                    if(isset($check_subject)){
                        array_push($temp_sched,$item);
                    }
                }else{
                    array_push($temp_sched,$item);
                }
            }
			
			$schedule = $temp_sched;
		}

        if($type == 'today'){
            $day = \Carbon\Carbon::now('Asia/Manila')->dayOfWeekIso ;
            return $schedule;
        }else if($type == 'all'){
            return view('studentPortal.pages.schedplot')->with('schedule',$schedule);
        }
        else if($type == 'current'){
            $time = \Carbon\Carbon::now('Asia/Manila')->isoFormat('hh:mm A');
            $current_sched = true;
            $next_sched = true;        
            $cn_sched = array();
            $today_sched = self::get_today_sched($schedule);
            foreach(collect($today_sched)->sortBy('sort')->values() as $item){
                if(!$current_sched && $next_sched){
                    $item->sched="next";
                    array_push($cn_sched,$item);
                    $next_sched = false;
                }
                if($time >= $item->start && $time <= $item->end){
                    if($current_sched){
                        $item->sched="current";
                        array_push($cn_sched,$item);
                        $current_sched = false;
                    }
                }
            }
            return $today_sched;
        }else{
            return $schedule;
        }
        
        
    }

    public static function get_today_sched($schedule){
        $day = \Carbon\Carbon::now('Asia/Manila')->dayOfWeekIso ;
        $today_sched = array();
        foreach($schedule as $item){
            $daysArray = explode(',', $item->days);
            $contains = in_array($day, $daysArray) ? 1 : 0;
            if($contains == 1){
                array_push($today_sched,(object)[
                    'subject'=>$item->subjdesc,
                    'teacher'=>$item->teacher,
                    'time'=>$item->schedtime,
                    'sort'=>\Carbon\Carbon::create($item->start)->isoFormat('HH'),
                    'start'=>\Carbon\Carbon::create($item->start)->isoFormat('HH:mm'),
                    'end'=>\Carbon\Carbon::create($item->end)->isoFormat('HH:mm')
                ]);
            }
            
        }
        return $today_sched;
    }


    public static function college_grade($studid = null,$syid = null,$semid = null){

        $grades = DB::table('college_stud_term_grades')
                    ->join('college_classsched','college_stud_term_grades.schedid','=','college_classsched.id')
                    ->join('college_prospectus',function($join){
                        $join->on('college_classsched.subjectID','=','college_prospectus.id');
                        $join->where('college_prospectus.deleted',0);
                    })
                    ->join('studinfo',function($join) use($studid){
                        $join->on('studinfo.id','=','college_stud_term_grades.studid');
                        $join->where('studinfo.id',$studid);
                    })
                    ->where('college_stud_term_grades.deleted',0)
                    ->where('college_classsched.syID',$syid)
                    ->where('college_classsched.semesterID',$semid)
                    ->select(
                            'college_stud_term_grades.*',
                            'college_classsched.subjectID',
                        )
                    ->get();
        $schedule = \App\Http\Controllers\SuperAdminController\StudentLoading::collegestudentsched_plot($studid,$syid,$semid);

        $schedule = collect($schedule[0]->info)->where('schedstatus','!=','DROPPED')->values();
    
        $temp_grades = array();

        foreach($schedule as $item){
            $check = collect($grades)->where('subjectID',$item->subjectID)->first();
            $item->finalgrade = null;
            if(isset($check->id)){

                if($check->prelim_status != 5){
                    $check->prelim_transmuted = null;
                }
                if($check->midterm_status != 5){
                    $check->midterm_transmuted = null;
                }
                if($check->prefinal_status != 5){
                    $check->prefinal_transmuted = null;
                }
                if($check->final_status != 5){
                    $check->final_transmuted = null;
                    $check->final_grade_transmuted = null;
                    $check->final_remarks = null;
                }

                $check->subjDesc = $item->subjDesc;
                $check->subjCode = $item->subjCode;
                array_push($temp_grades,$check);
            }else{
                $check = collect($grades)->where('subjectID',$item->subjectID)->first();
                if(isset($check->id)){
    
                    if($check->prelim_status != 5){
                        $check->prelim_transmuted = null;
                    }
                    if($check->midterm_status != 5){
                        $check->midterm_transmuted = null;
                    }
                    if($check->prefinal_status != 5){
                        $check->prefinal_transmuted = null;
                    }
                    if($check->final_status != 5){
                        $check->final_transmuted = null;
                        $check->final_remarks = null;
                    }
    
                    $check->subjDesc = $item->subjDesc;
                    $check->subjCode = $item->subjCode;
                    array_push($temp_grades,$check);
                }else{
                    array_push($temp_grades,$item);
                }
            }
        }

        return $temp_grades;

    }
    

    public static function enrollment_reportcard(Request $request){

        $syid = $request->get('syid');
        $purpose = $request->get('purpose');

        $semid = null;
        $strandid = null;
        $studgrades = array();
        $setup = array();
        $sumsetup = array();
        $clsetup = array();
        $ageevaldate = array();
        $remarks_setup = array();
        $user = null;
        
        if( isset($purpose) && $purpose == 'mobile' ){
            $user = DB::table('users')->where('id', $request->studid)->get()->first();

            if(auth()->user()->type == 9){
                $studid = DB::table('studinfo')->where('sid',str_replace('P','',$user->email))->first()->id;
            }else{
                $studid = DB::table('studinfo')->where('sid',str_replace('S','',$user->email))->first()->id;
            }
            
        }else {
            if(auth()->user()->type == 9){
                $studid = DB::table('studinfo')->where('sid',str_replace('P','',auth()->user()->email))->first()->id;
            }else{
                $studid = DB::table('studinfo')->where('sid',str_replace('S','',auth()->user()->email))->first()->id;
            }
        }

        $school = DB::table('schoolinfo')->first()->abbreviation;
        
        $check_enrollment = DB::table('enrolledstud')   
                                ->where('studid',$studid)
                                ->where('syid',$syid)
                                ->where('deleted',0)
                                ->first();

        if(!isset($check_enrollment->id)){
            $check_enrollment = DB::table('sh_enrolledstud')   
                                    ->where('studid',$studid)
                                    ->where('syid',$syid)
                                    ->where('deleted',0)
                                    ->first();

            if(isset($check_enrollment->id)){
                $semid = $check_enrollment->semid;
                $strandid = $check_enrollment->strandid;
            }
        }
        if(!isset($check_enrollment->id)){
            $check_enrollment = DB::table('college_enrolledstud')   
                ->where('studid',$studid)
                ->where('syid',$syid)
                ->where('deleted',0)
                ->select(
                    '*',
                    'yearLevel as levelid',
                    'sectionID as sectionid'
                )
                ->first();

            if(isset($check_enrollment->id)){
                $semid = $check_enrollment->semid;
            }
        }

        if(!isset($check_enrollment->id)){
            return array((object)[
                'levelid'=>null,
                'grades'=>array()
            ]);
        }

        $levelid = $check_enrollment->levelid;
        $sectionid = $check_enrollment->sectionid;

        

        if($levelid == 14 || $levelid == 15){
			
			
					$temp_grades = array();
					$finalgrade = array();
					
					$temp_sect = DB::table('sh_enrolledstud')->where('studid',$studid)->where('semid',1)->where('syid',$syid)->where('deleted',0)->first();
		
					if(isset($temp_sect)){
						$studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $levelid,$studid,$syid,$temp_sect->strandid,1,$temp_sect->sectionid);
						
						foreach($studgrades as $item){
							if($item->id == 'G1'){
								if($item->semid == 1){
									 array_push($finalgrade,$item);
								}
							}else{
								if($item->strandid == $temp_sect->strandid){
									array_push($temp_grades,$item);
								}
								if($item->strandid == null){
									array_push($temp_grades,$item);
								}
							}
						}
					}
					
					$temp_sect = DB::table('sh_enrolledstud')->where('studid',$studid)->where('semid',2)->where('syid',$syid)->where('deleted',0)->first();
				   
					if(!isset($temp_sect)){
						$temp_sect = DB::table('sh_enrolledstud')->where('studid',$studid)->where('semid',1)->where('syid',$syid)->where('deleted',0)->first();
					}
				
					
				
				    if(isset($temp_sect)){
						
						
						$studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $levelid,$studid,$syid,$temp_sect->strandid,2,$temp_sect->sectionid);
						foreach($studgrades as $item){
							if($item->id == 'G1'){
								if($item->semid == 2){
									 array_push($finalgrade,$item);
								}
							}else{
								if($item->strandid == $temp_sect->strandid){
									array_push($temp_grades,$item);
								}
								if($item->strandid == null){
									array_push($temp_grades,$item);
								}
							}
						}
					}
					$studgrades = $temp_grades;
			
            ///$studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $levelid,$studid,$syid,$strandid,null,$sectionid);
			
			
			
			
			
            $studgrades = collect($studgrades)->where('isVisible','1')->sortBy('sortid')->values();
            if(strtoupper($school) == 'BCT'){
                foreach($studgrades as $item){
                    $item->quarter1  = $item->quarter1 < 75 &&  $item->q1status == 4 ? 'NG' :$item->quarter1;
                    $item->quarter2  = $item->quarter2 < 75 &&  $item->q2status == 4 ? 'NG' :$item->quarter2;
                    $item->quarter3  = $item->quarter3 < 75 &&  $item->q3status == 4 ? 'NG' :$item->quarter3;
                    $item->quarter4  = $item->quarter4 < 75 &&  $item->q4status == 4 ? 'NG' :$item->quarter4;
                    $item->q1  = $item->q1 < 75 &&  $item->q1status == 4  ? 'NG' :$item->q1;
                    $item->q2  = $item->q2 < 75 &&  $item->q2status == 4  ? 'NG' :$item->q2;
                    $item->q3  = $item->q3 < 75 &&  $item->q3status == 4  ? 'NG' :$item->q3;
                    $item->q4  = $item->q4 < 75 &&  $item->q4status == 4  ? 'NG' :$item->q4;
                }
            }elseif(strtoupper($school) == 'GBBC'){
                foreach($studgrades as $item){
                    $item->quarter1  = $item->quarter1 < 75 &&  $item->q1status == 4 ? '' :$item->quarter1;
                    $item->quarter2  = $item->quarter2 < 75 &&  $item->q2status == 4 ? '' :$item->quarter2;
                    $item->quarter3  = $item->quarter3 < 75 &&  $item->q3status == 4 ? '' :$item->quarter3;
                    $item->quarter4  = $item->quarter4 < 75 &&  $item->q4status == 4 ? '' :$item->quarter4;
                    $item->q1  = $item->q1 < 75 &&  $item->q1status == 4  ? '' :$item->q1;
                    $item->q2  = $item->q2 < 75 &&  $item->q2status == 4  ? '' :$item->q2;
                    $item->q3  = $item->q3 < 75 &&  $item->q3status == 4  ? '' :$item->q3;
                    $item->q4  = $item->q4 < 75 &&  $item->q4status == 4  ? '' :$item->q4;
                }
            }
        }elseif ($levelid >= 17) {
            $semid = $request->get('semid');
            $studgrades = self::college_grade($studid,$syid,$semid);
            $setup = DB::table('semester_setup')
                        ->where('sy',$syid)
                        ->where('semester',$semid)
                        ->where('activestatus',1)
                        ->where('deleted',0)
                        ->get();

            return array((object)[
                'levelid'=>$levelid,
                'grades'=>$studgrades,
                'setup'=>$setup,
            ]);



        }
        else{

            if(strtoupper($school) == 'SPCT' && $levelid == 2){

                $studgrades = \App\Http\Controllers\SuperAdminController\PreKinderGradingController::get_student_grades_data($studid,$syid);
                $setup = \App\Http\Controllers\SuperAdminController\PreKinderGradingController::get_preschool_setup($syid);
                $sumsetup = \App\Http\Controllers\SuperAdminController\PreKinderGradingController::get_preschool_summary_setup($syid,$request);
                $clsetup = \App\Http\Controllers\SuperAdminController\PreKinderGradingController::get_preschool_cl_setup($syid,$request);
                $ageevaldate = \App\Http\Controllers\SuperAdminController\PreKinderGradingController::get_preschool_ageevaldate_setup($syid,$request);
    
                $section = Db::table('enrolledstud')
                                  ->where('studid',$studid)
                                  ->join('sections',function($join){
                                        $join->on('enrolledstud.sectionid','=','sections.id');
                                        $join->where('sections.deleted',0);
                                  })
                                  ->where('enrolledstud.deleted',0)
                                  ->select('sectionname')
                                  ->first();
    
                foreach($setup as $item){
                      $item->q1grade=0;
                      $item->q2grade=0;
                      $item->q3grade=0;
                      $item->q4grade=0;
                      $row_grade = collect($studgrades)->where('gsdid',$item->id)->values();
                      if(count($row_grade) > 0){
                            $item->q1grade= $row_grade[0]->q1evaltext == 0 || $row_grade[0]->q1evaltext == null ? 0 : $row_grade[0]->q1evaltext;
                            // $item->q2grade= $row_grade[0]->q2evaltext == 0 || $row_grade[0]->q2evaltext == null ? 0 : $row_grade[0]->q2evaltext;
                            // $item->q3grade= $row_grade[0]->q3evaltext == 0 || $row_grade[0]->q3evaltext == null ? 0 : $row_grade[0]->q3evaltext;
                            // $item->q4grade= $row_grade[0]->q4evaltext == 0 || $row_grade[0]->q4evaltext == null ? 0 : $row_grade[0]->q4evaltextt;
                      }
                }
    
                foreach($sumsetup as $item){
                      $item->q1grade=0;
                      $item->q2grade=0;
                      $item->q3grade=0;
                      $item->q4grade=0;
                      $row_grade = collect($studgrades)->where('gsdid',$item->id)->values();
                      if(count($row_grade) > 0){
                            $item->q1grade= $row_grade[0]->q1evaltext == null ? 0 : $row_grade[0]->q1evaltext;
                            // $item->q2grade= $row_grade[0]->q2evaltext == null ? 0 : $row_grade[0]->q2evaltext;
                            // $item->q3grade= $row_grade[0]->q3evaltext == null ? 0 : $row_grade[0]->q3evaltext;
                            // $item->q4grade= $row_grade[0]->q4evaltext == null ? 0 : $row_grade[0]->q4evaltextt;
                      }
                }
    
                foreach($ageevaldate as $item){
                      $item->q1grade=null;
                      $item->q2grade=null;
                      $item->q3grade=null;
                      $item->q4grade=null;
                      $row_grade = collect($studgrades)->where('gsdid',$item->id)->values();
                      if(count($row_grade) > 0){
    
                            if($item->group == 'B'){
                                  $item->q1grade= $row_grade[0]->q1evaltext == null ? null : \Carbon\Carbon::create($row_grade[0]->q1evaltext)->isoFormat('MMMM DD, YYYY');
                                //   $item->q2grade= $row_grade[0]->q2evaltext == null ? null : \Carbon\Carbon::create($row_grade[0]->q2evaltext)->isoFormat('MMMM DD, YYYY');
                                //   $item->q3grade= $row_grade[0]->q3evaltext == null ? null : \Carbon\Carbon::create($row_grade[0]->q3evaltext)->isoFormat('MMMM DD, YYYY');
                                //   $item->q4grade= $row_grade[0]->q4evaltext == null ? null : \Carbon\Carbon::create($row_grade[0]->q4evaltext)->isoFormat('MMMM DD, YYYY');
                            }else{
                                  $item->q1grade= $row_grade[0]->q1evaltext == null ? null : $row_grade[0]->q1evaltext;
                                //   $item->q2grade= $row_grade[0]->q2evaltext == null ? null : $row_grade[0]->q2evaltext;
                                //   $item->q3grade= $row_grade[0]->q3evaltext == null ? null : $row_grade[0]->q3evaltext;
                                //   $item->q4grade= $row_grade[0]->q4evaltext == null ? null : $row_grade[0]->q4evaltextt;
                            }
                      }
                }
    
                foreach($clsetup as $item){
                      $item->q1grade=null;
                      $item->q2grade=null;
                      $item->q3grade=null;
                      $item->q4grade=null;
                      $row_grade = collect($studgrades)->where('gsdid',$item->id)->values();
                      if(count($row_grade) > 0){
                            $item->q1grade= $row_grade[0]->q1evaltext == null ? null : $row_grade[0]->q1evaltext;
                            // $item->q2grade= $row_grade[0]->q2evaltext == null ? null : $row_grade[0]->q2evaltext;
                            // $item->q3grade= $row_grade[0]->q3evaltext == null ? null : $row_grade[0]->q3evaltext;
                            // $item->q4grade= $row_grade[0]->q4evaltext == null ? null : $row_grade[0]->q4evaltextt;
                      }
                }

            }else if(strtoupper($school) == 'SPCT' && $levelid == 3){

                $studgrades =  \App\Http\Controllers\SuperAdminController\PreSchoolGradingController::get_student_grades_data($studid,$syid);
                $setup = \App\Http\Controllers\SuperAdminController\PreSchoolGradingController::get_preschool_setup($syid);
                $ageevaldate = \App\Http\Controllers\SuperAdminController\PreSchoolGradingController::get_preschool_setup_age_ajax($syid,$request);
                $remarks_setup = \App\Http\Controllers\SuperAdminController\PreSchoolGradingController::get_preschool_setup_remarks_ajax($syid,$request);

                foreach($setup as $item){
                    $item->q1grade = '';
                    $item->q2grade = '';
                    $item->q3grade = '';
                    $item->q4grade = '';
                    $row_grade = collect($studgrades)->where('gsdid',$item->id)->values();
                    if(count($row_grade) > 0){
                        $item->q1grade= $row_grade[0]->q1evaltext == null ? '' : $row_grade[0]->q1evaltext;
                        // $item->q2grade= $row_grade[0]->q2evaltext == null ? '' : $row_grade[0]->q2evaltext;
                        // $item->q3grade= $row_grade[0]->q3evaltext == null ? '' : $row_grade[0]->q3evaltext;
                        // $item->q4grade= $row_grade[0]->q4evaltext == null ? '' : $row_grade[0]->q4evaltext;
                    }
              }
  
              foreach($ageevaldate as $item){
                    $item->q1grade='';
                    $item->q2grade='';
                    $item->q3grade='';
                    $item->q4grade='';
                    $row_grade = collect($studgrades)->where('gsdid',$item->id)->values();
                    if(count($row_grade) > 0){
                          $item->q1grade= $row_grade[0]->q1evaltext == null ? '' : $row_grade[0]->q1evaltext;
                        //   $item->q2grade= $row_grade[0]->q2evaltext == null ? '' : $row_grade[0]->q2evaltext;
                        //   $item->q3grade= $row_grade[0]->q3evaltext == null ? '' : $row_grade[0]->q3evaltext;
                        //   $item->q4grade= $row_grade[0]->q4evaltext == null ? '' : $row_grade[0]->q4evaltext;
                    }
              }
  
              foreach($remarks_setup as $item){
                    $item->q1grade='';
                    $item->q2grade='';
                    $item->q3grade='';
                    $item->q4grade='';
                    $row_grade = collect($studgrades)->where('gsdid',$item->id)->values();
                    if(count($row_grade) > 0){
                          $item->q1grade= $row_grade[0]->q1evaltext == null ? '' : $row_grade[0]->q1evaltext;
                        //   $item->q2grade= $row_grade[0]->q2evaltext == null ? '' : $row_grade[0]->q2evaltext;
                        //   $item->q3grade= $row_grade[0]->q3evaltext == null ? '' : $row_grade[0]->q3evaltext;
                        //   $item->q4grade= $row_grade[0]->q4evaltext == null ? '' : $row_grade[0]->q4evaltext;
                    }
              }

            }
            else if( ( strtoupper($school) == 'BCT' && $levelid == 3 ) || ( strtoupper($school) == 'BCT' && $levelid == 2 )  || ( strtoupper($school) == 'BCT' && $levelid == 4 )){

                $studgrades =  \App\Http\Controllers\SuperAdminController\PreSchoolGradingController::get_student_grades_data($studid,$syid);
                $setup = \App\Http\Controllers\SuperAdminController\PreSchoolGradingController::get_preschool_setup($syid);
                // $ageevaldate = \App\Http\Controllers\SuperAdminController\PreSchoolGradingController::get_preschool_setup_age_ajax($syid,$request);
                // $remarks_setup = \App\Http\Controllers\SuperAdminController\PreSchoolGradingController::get_preschool_setup_remarks_ajax($syid,$request);

                foreach($setup as $item){
                    $item->q1grade = '';
                    $item->q2grade = '';
                    $item->q3grade = '';
                    $item->q4grade = '';
                    $row_grade = collect($studgrades)->where('gsdid',$item->id)->values();
                    if(count($row_grade) > 0){
                        $item->q1grade= $row_grade[0]->q1evaltext == null ? '' : $row_grade[0]->q1evaltext;
                        // $item->q2grade= $row_grade[0]->q2evaltext == null ? '' : $row_grade[0]->q2evaltext;
                        // $item->q3grade= $row_grade[0]->q3evaltext == null ? '' : $row_grade[0]->q3evaltext;
                        // $item->q4grade= $row_grade[0]->q4evaltext == null ? '' : $row_grade[0]->q4evaltext;
                    }
              }
            }
            else{
                $grading_version = DB::table('zversion_control')->where('module',1)->where('isactive',1)->first();
                if($grading_version->version == 'v2' && $syid == 2){
                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades_gv2( $levelid,$studid,$syid,null,null,$sectionid);
                }else{
                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades( $levelid,$studid,$syid,null,null,$sectionid);
                }
                $studgrades = collect($studgrades)->where('isVisible','1')->sortBy('sortid')->values();
                if(strtoupper($school) == 'BCT'){
                    foreach($studgrades as $item){
                        $item->quarter1  = $item->quarter1 < 75 &&  $item->q1status == 4 ? 'NG' :$item->quarter1;
                        $item->quarter2  = $item->quarter2 < 75 &&  $item->q2status == 4 ? 'NG' :$item->quarter2;
                        $item->quarter3  = $item->quarter3 < 75 &&  $item->q3status == 4 ? 'NG' :$item->quarter3;
                        $item->quarter4  = $item->quarter4 < 75 &&  $item->q4status == 4 ? 'NG' :$item->quarter4;
                        $item->q1  = $item->q1 < 75 &&  $item->q1status == 4  ? 'NG' :$item->q1;
                        $item->q2  = $item->q2 < 75 &&  $item->q2status == 4  ? 'NG' :$item->q2;
                        $item->q3  = $item->q3 < 75 &&  $item->q3status == 4  ? 'NG' :$item->q3;
                        $item->q4  = $item->q4 < 75 &&  $item->q4status == 4  ? 'NG' :$item->q4;
                    }
                }elseif(strtoupper($school) == 'GBBC'){
                    foreach($studgrades as $item){
                        $item->quarter1  = $item->quarter1 < 75 &&  $item->q1status == 4 ? '' :$item->quarter1;
                        $item->quarter2  = $item->quarter2 < 75 &&  $item->q2status == 4 ? '' :$item->quarter2;
                        $item->quarter3  = $item->quarter3 < 75 &&  $item->q3status == 4 ? '' :$item->quarter3;
                        $item->quarter4  = $item->quarter4 < 75 &&  $item->q4status == 4 ? '' :$item->quarter4;
                        $item->q1  = $item->q1 < 75 &&  $item->q1status == 4  ? '' :$item->q1;
                        $item->q2  = $item->q2 < 75 &&  $item->q2status == 4  ? '' :$item->q2;
                        $item->q3  = $item->q3 < 75 &&  $item->q3status == 4  ? '' :$item->q3;
                        $item->q4  = $item->q4 < 75 &&  $item->q4status == 4  ? '' :$item->q4;
                    }
                }
            }
          
        } 

        return array((object)[
            'levelid'=>$levelid,
            'grades'=>$studgrades,
            'setup'=>$setup,
            'sumsetup'=>$sumsetup,
            'clsetup'=>$clsetup,
            'agevaldate'=>$ageevaldate,
            'remarks_setup'=>$remarks_setup
        ]);

    }

    public static function observedvalues(Request $request){

        if(auth()->user()->type == 9){
            $studid = DB::table('studinfo')->where('sid',str_replace('P','',auth()->user()->email))->first()->id;
        }else{
            $studid = DB::table('studinfo')->where('sid',str_replace('S','',auth()->user()->email))->first()->id;
        }
        $syid = $request->get('syid');

        $check_enrollment = DB::table('enrolledstud')   
                                ->where('studid',$studid)
                                ->where('syid',$syid)
                                ->where('deleted',0)
                                ->first();

        if(!isset($check_enrollment->id)){
            $check_enrollment = DB::table('sh_enrolledstud')   
                                    ->where('studid',$studid)
                                    ->where('syid',$syid)
                                    ->where('deleted',0)
                                    ->first();
        }

        if(!isset($check_enrollment->id)){
            return array((object)[
                 'ob_rv'=>array(),
                 'ob_setup'=>array(),
                 'student_ob'=>array(),
             ]);
         }


        $temp_schoolinfo = DB::table('schoolinfo')->first();
        $student_ob = [];
        $ob_rv = [];
        $ob_setup = [];
         
        if($syid == 2){
            if( strtoupper($temp_schoolinfo->abbreviation) == 'MCS' ||
                strtoupper($temp_schoolinfo->abbreviation) == 'FMC MA SCH' ||
                strtoupper($temp_schoolinfo->abbreviation) == 'VNBC'
            ){
                 $ob_setup = \App\Http\Controllers\SuperAdminController\ObservedValuesController::observedvalues_list_v1();
            }else{
                $ob_setup = \App\Http\Controllers\SuperAdminController\ObservedValuesController::observedvalues_list( null,null,null,$syid,$check_enrollment->levelid);
            }
        }else{
            $ob_setup = \App\Http\Controllers\SuperAdminController\ObservedValuesController::observedvalues_list( null,null,null,$syid,$check_enrollment->levelid);
        }

        $student_ob = DB::table('grading_system_grades_cv')
            ->where('grading_system_grades_cv.deleted',0)
            ->where('grading_system_grades_cv.studid',$studid)
            ->where('grading_system_grades_cv.syid',$syid)
            ->select(
                'grading_system_grades_cv.gsdid',
                'grading_system_grades_cv.q1eval',
                'grading_system_grades_cv.q2eval',
                'grading_system_grades_cv.q3eval',
                'grading_system_grades_cv.q4eval'
            )
            ->get();
   
        if(count($student_ob) > 0){
            $ob_rv = DB::table('grading_system_ratingvalue')
                ->where('deleted',0)
                ->where('gsid',$ob_setup[0]->headerid)
                ->orderBy('sort')
                ->get();
        }


        return array((object)[
            'ob_rv'=>$ob_rv,
            'ob_setup'=>$ob_setup,
            'student_ob'=>$student_ob,
        ]);

    }

    public static function attendance(Request $request){

        if(auth()->user()->type == 9){
            $studid = DB::table('studinfo')->where('sid',str_replace('P','',auth()->user()->email))->first()->id;
        }else{
            $studid = DB::table('studinfo')->where('sid',str_replace('S','',auth()->user()->email))->first()->id;
        }
        $syid = $request->get('syid');
        $sectionid = null;

        $check_enrollment = DB::table('enrolledstud')   
                                ->where('studid',$studid)
                                ->where('syid',$syid)
                                ->where('deleted',0)
                                ->first();

        if(!isset($check_enrollment->id)){

            $check_enrollment = DB::table('sh_enrolledstud')   
                                    ->where('studid',$studid)
                                    ->where('syid',$syid)
                                    ->where('deleted',0)
                                    ->first();
          
        }

        if(!isset($check_enrollment->sectionid)){
            return array((object)[
                'att_setup'=>array(),
            ]);
        }

        $sectionid = $check_enrollment->sectionid;

        $sectioninfo = DB::table('sectiondetail')
                            ->where('sectionid',$sectionid)
                            ->where('syid',$syid)
                            ->join('teacher',function($join){
                                $join->on('sectiondetail.teacherid','=','teacher.id');
                                $join->where('teacher.deleted',0);
                            })
                            ->select(
                                'teacherid'
                            )
                            ->first();

        $teacherid  = null;
        if(isset($sectioninfo->teacherid)){
            $teacherid = $sectioninfo->teacherid;
        }

        $attendance_setup = \App\Http\Controllers\SuperAdminController\SchoolDaysController::attendance_setup_list($syid,$check_enrollment->levelid);
        $temp_schoolinfo = DB::table('schoolinfo')->first();
        
        if($syid == 2 && (
            strtoupper($temp_schoolinfo->abbreviation) == 'FMC MA SCH' ||
            strtoupper($temp_schoolinfo->abbreviation) == 'VNBC'
        )){
            if(
                strtoupper($temp_schoolinfo->abbreviation) == 'FMC MA SCH' ||
                strtoupper($temp_schoolinfo->abbreviation) == 'VNBC' ||
                strtoupper($temp_schoolinfo->abbreviation) == 'ZPS'
            ){
                foreach( $attendance_setup as $item){
                    $month_count = \App\Models\Attendance\AttendanceData::monthly_attendance_count($syid,$item->month,$studid,$item->year);
                    $item->present = collect($month_count)->where('present',1)->count() + collect($month_count)->where('tardy',1)->count() + collect($month_count)->where('cc',1)->count();
                    $item->absent = $item->days - $item->present;
                    if($item->present >= $item->days){
                        $item->present = $item->days;
                        $item->absent = 0;
                    }
                }
            }
        }else{
            foreach( $attendance_setup as $item){
    
                $sf2_setup = DB::table('sf2_lact')
                                    ->where('month',$item->month)
                                    ->where('year',$item->year)
                                    ->where('lact',3)
                                    ->where('sectionid',$sectionid)
                                    ->where('sf2_lact.deleted',0)
                                    ->join('sf2_lact3detail',function($join) use($studid){
                                        $join->on('sf2_lact.id','=','sf2_lact3detail.headerid');
                                        $join->where('sf2_lact3detail.deleted',0);
                                        $join->where('sf2_lact3detail.studid',$studid);
                                    })
                                    ->get();
                                    
                if(count($sf2_setup) > 0){
                    $item->present = $sf2_setup[0]->dayspresent >= $item->days ? $item->days :  $sf2_setup[0]->dayspresent;
                    $item->absent = $sf2_setup[0]->daysabsent ;
                }else{
                    
                    $sf2_setup = DB::table('sf2_setup')
                            ->where('month',$item->month)
                            ->where('year',$item->year)
                            ->where('sectionid',$sectionid)
                            ->where('sf2_setup.deleted',0)
                            ->join('sf2_setupdates',function($join){
                                $join->on('sf2_setup.id','=','sf2_setupdates.setupid');
                                $join->where('sf2_setupdates.deleted',0);
                            })
                            ->groupBy('dates')
                            ->select('dates')
                            ->get();
                            
                           
    
                    $temp_days = array();
    
                    foreach($sf2_setup as $sf2_setup_item){
                        array_push($temp_days,$sf2_setup_item->dates);
                    }
                    
                    $student_attendance = DB::table('studattendance')
                                    ->where('studid',$studid)
                                    ->where('deleted',0)
                                    ->whereIn('tdate',$temp_days)
                                    // ->where('syid',$syid)
                                    ->distinct('tdate')
                                    ->distinct()
                                    ->select([
                                        'present',
                                        'absent',
                                        'tardy',
                                        'cc',
                                        'tdate'
                                    ])
                                    ->get();

                    $student_attendance = collect($student_attendance)->unique('tdate')->values();
    
                    $item->present = collect($student_attendance)->where('present',1)->count() + collect($student_attendance)->where('tardy',1)->count() + collect($student_attendance)->where('cc',1)->count();
                    $item->absent = collect($student_attendance)->where('absent',1)->count();
                    
                }
             
            }
        }

        return array((object)[
            'att_setup'=>$attendance_setup,
        ]);

    }

    public static function enrollment_student_information(){

        if(auth()->user()->type == 9){
            $studid = DB::table('studinfo')->where('sid',str_replace('P','',auth()->user()->email))->first()->id;
        }else{
            $studid = DB::table('studinfo')->where('sid',str_replace('S','',auth()->user()->email))->first()->id;
        }
      
        $student = DB::table('studinfo')
                    ->join('gradelevel',function($join){
                            $join->on('studinfo.levelid','=','gradelevel.id');
                            $join->where('gradelevel.deleted','0');
                    })
                    ->leftJoin('sh_strand',function($join){
                        $join->on('studinfo.strandid','=','sh_strand.id');
                        $join->where('sh_strand.deleted','0');
                    })
                    ->leftJoin('nationality',function($join){
                        $join->on('studinfo.nationality','=','nationality.id');
                    })
                    ->leftJoin('college_courses',function($join){
                        $join->on('studinfo.courseid','=','college_courses.id');
                        $join->where('college_courses.deleted',0);
                    })
                    ->where('studinfo.deleted',0)
                    ->where('studinfo.id',$studid)
                    ->select(
                        'courseDesc',
                        'nationality.nationality as nationalitytext',
                        'strandname',
                        'studinfo.*',
                        'gradelevel.levelname'
                    )
                    ->get();

        foreach($student as $item){
                $middlename = explode(" ",$item->middlename);
                $temp_middle = '';
                if($middlename != null){
                    foreach ($middlename as $middlename_item) {
                            if(strlen($middlename_item) > 0){
                            $temp_middle .= $middlename_item[0].'.';
                            } 
                    }
                }
                $item->dob = \Carbon\Carbon::create($item->dob)->isoFormat('MMMM DD, YYYY');
                $item->student=$item->lastname.', '.$item->firstname.' '.$item->suffix.' '.$temp_middle;
                $item->text = $item->sid.' - '.$item->student;

        }

        return $student;
      
    }

    public static function upload_photo(Request $request){

        $message = [
            'image.required'=>'Student Picture is required',
        ];

        $validator = Validator::make($request->all(), [
            'image' => ['required']
        ], $message);

        if ($validator->fails()) {

            toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');

            $data = array(
                (object)
              [
                'status'=>'0',
                'message'=>'Error',
                'errors'=>$validator->errors(),
                'inputs'=>$request->all()
            ]);

            return $data;
            
        }
        else{

            $session_student = Session::get('studentInfo');

            $studendinfo = DB::table('studinfo')
                        ->where('deleted',0)
                        ->where('id',$session_student->id)
                        ->select('sid','id')
                        ->first();

            $link = DB::table('schoolinfo')
                            ->select('essentiellink')
                            ->first()
                            ->essentiellink;

            if($link == null){
                return array( (object)[
                    'status'=>'0',
                    'message'=>'Error',
                    'errors'=>array(),
                    'inputs'=>$request->all()
                ]);
            }

            $urlFolder = str_replace('http://','',$link);
			$urlFolder = str_replace('https://','',$urlFolder);

                if (! File::exists(public_path().'storage/STUDENT')) {
                    $path = public_path('storage/STUDENT');
                    if(!File::isDirectory($path)){
                        File::makeDirectory($path, 0777, true, true);
                    }
                }

                if (! File::exists(dirname(base_path(), 1).'/'.$urlFolder.'storage/STUDENT')) {
                    $cloudpath = dirname(base_path(), 1).'/'.$urlFolder.'/storage/STUDENT';
                    if(!File::isDirectory($cloudpath)){
                        File::makeDirectory($cloudpath, 0777, true, true);
                    }
                }

                $date = \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYYYHHmmss');
                $data = $request->image;
                list($type, $data) = explode(';', $data);
                list(, $data)      = explode(',', $data);
                $data = base64_decode($data);
                $extension = 'png';
                $destinationPath = public_path('storage/STUDENT/'.$studendinfo->sid.$date.'.'.$extension);
                $clouddestinationPath = dirname(base_path(), 1).'/'.$urlFolder.'/storage/STUDENT/'.$studendinfo->sid.$date.'.'.$extension;
                file_put_contents($clouddestinationPath, $data);
                file_put_contents($destinationPath, $data);

                DB::table('studinfo')
                        ->where('id',$studendinfo->id)
                        ->take(1)
                        ->update(['picurl'=>'storage/STUDENT/'.$studendinfo->sid.$date.'.'.$extension ]);

                $session_student->picurl = 'storage/STUDENT/'.$studendinfo->sid.$date.'.'.$extension;
                Session::put('studentInfo', $session_student);

                $data = array(
                    (object)
                  [
                    'status'=>'1',
                ]);
    
                return $data;

            }

    }

    public static function remedial_class(Request $request){

        
        $month = $request->get('month');
        $syid = $request->get('syid');
        $levelid = $request->get('levelid');

        if($syid == null){
            $syid = DB::table('sy')->where('isactive',1)->first()->id;
        }

        // $studendinfo = Session::get('studentInfo');
        if(auth()->user()->type == 9){
            $studid = DB::table('studinfo')->where('sid',str_replace('P','',auth()->user()->email))->first()->id;
        }else{
            $studid = DB::table('studinfo')->where('sid',str_replace('S','',auth()->user()->email))->first()->id;
        }

        $check_enrollment = DB::table('enrolledstud')   
                ->where('studid',$studid)
                ->where('syid',$syid)
                ->where('deleted',0)
                ->first();

        if(!isset($check_enrollment->id)){

            $check_enrollment = DB::table('sh_enrolledstud')   
                        ->where('studid',$studid)
                        ->where('syid',$syid)
                        ->where('deleted',0)
                        ->first();

            if(isset($check_enrollment->id)){
                $semid = $request->get('semid');
                $strandid = $check_enrollment->strandid;
            }
        }
        
        $remedial_class = DB::table('student_specsubj')
                            ->join('sections',function($join){
                                $join->on('student_specsubj.sectionid','=','sections.id');
                                $join->where('sections.deleted',0);
                            })
                            ->join('gradelevel',function($join){
                                $join->on('student_specsubj.levelid','=','gradelevel.id');
                                $join->where('gradelevel.deleted',0);
                            })
                            ->where('student_specsubj.studid',$studid)
                            ->where('student_specsubj.syid',$syid)
                            ->where('student_specsubj.deleted',0)
                            ->get();

        
        foreach($remedial_class as $item){

            $levelid = $item->levelid;
            $sectionid = $item->sectionid;
            $subjid = $item->subjid;
            

            if($levelid == 14 || $levelid == 15){

                $subject = DB::table('sh_subjects')
                                ->where('id',$item->subjid)
                                ->where('deleted',0)
                                ->select(
                                    'subjtitle as subjdesc',
                                    'subjcode'
                                )
                                ->first();

                $schedule = \App\Http\Controllers\PrincipalControllers\ScheduleController::get_schedule($levelid,$syid,$sectionid,null,null);
            }
            else{

                $subject = DB::table('subjects')
                            ->where('id',$item->subjid)
                            ->where('deleted',0)
                            ->select(
                                'subjdesc',
                                'subjcode'
                            )
                            ->first();

                $schedule = \App\Http\Controllers\PrincipalControllers\ScheduleController::get_schedule($levelid,$syid,$sectionid,null,null,$subjid);
            }


            $item->subjdesc = $subject->subjdesc;
            $item->subjcode = $subject->subjcode;
            $item->sched = $schedule[0]->schedule;

            $grades = DB::table('grades')
                        ->join('gradesdetail',function($join) use($studid){
                            $join->on('grades.id','=','gradesdetail.headerid');
                            $join->where('gradesdetail.studid',$studid);
                            $join->where('gradesdetail.gdstatus',4);
                        })
                        ->where('syid',$item->syid)
                        ->where('sectionid',$sectionid)
                        ->where('subjid',$subjid)
                        ->where('levelid',$levelid)
                        ->select(
                            'qg',
                            'quarter'
                        )->get();

            $item->q1 = '';
            $item->q2 = '';
            $item->q3 = '';
            $item->q4 = '';

            foreach($grades as $gradeitem){
                if($gradeitem->quarter == 1 ){
                    $item->q1 = $gradeitem->qg;
                }
                else if($gradeitem->quarter == 2 ){
                    $item->q2 = $gradeitem->qg;
                }
                else if($gradeitem->quarter == 3 ){
                    $item->q3 = $gradeitem->qg;
                }
                else if($gradeitem->quarter == 4 ){
                    $item->q4 = $gradeitem->qg;
                }
            }

          
            
        }

      
                    


        return $remedial_class;


    }

    public function history_list(Request $request)
	{
		// if($request->ajax())
		// {
			$syid = $request->get('syid');
			$studid = $request->get('studid');
			$semid = $request->get('semid');
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

			$info = db::table('studinfo')
					->where('id', $studid)
					->first();

			$tuitionheader = db::table('tuitionheader')
				->where('id', $info->feesid)
				->first();

			if($tuitionheader)
			{
				$feesname = $tuitionheader->description;
				$feesid = $tuitionheader->id;
			}

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
				$levelid = $enrolled->levelid;
				$levelname = db::table('gradelevel')->where('id', $levelid)->first()->levelname;
				$studstatus = $enrolled->studstatus;

				$sec = db::table('sections')
					->where('id', $enrolled->sectionid)
					->first();

				if($sec)
				{
					$section = $sec->sectionname;
				}
			}
			else
			{
				$enrolled = db::table('sh_enrolledstud')
					->where('studid', $studid)
					->where('syid', $syid)
					->where(function($q) use($semid){
						if($semid == 3)
						{
							$q->where('semid', 3);
						}
						else
						{
							if(db::table('schoolinfo')->first()->shssetup == 0)
							{
								$q->where('semid', $semid);
							}
						}
					})
					->where('deleted', 0)
					->first();

				if($enrolled)
				{
					$levelid = $enrolled->levelid;
					$levelname = db::table('gradelevel')->where('id', $levelid)->first()->levelname;
					$studstatus = $enrolled->studstatus;

					$sec = db::table('sections')
					->where('id', $enrolled->sectionid)
					->first();

					if($sec)
					{
						$section = $sec->sectionname;
					}

					$strnd = db::table('sh_strand')
						->where('id', $enrolled->strandid)
						->first();

					if($strnd)
					{
						$strand = $strnd->strandcode;
					}
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
						$levelid = $enrolled->yearLevel;
						$levelname = db::table('gradelevel')->where('id', $levelid)->first()->levelname;
						$studstatus = $enrolled->studstatus;

						$sec = db::table('college_sections')
						->where('id', $enrolled->sectionID)
						->first();

						if($sec)
						{
							$section = $sec->sectionDesc;
						}
					}
					else
					{
						$levelid = $info->levelid;
						$levelname = db::table('gradelevel')->where('id', $levelid)->first()->levelname;
						$studstatus = 0;
					}
				}
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
				$getLedger = db::table('studledger')
					->where('studid', $studid)
					->where('syid', $syid)
					->where(function($q) use($semid) {
						if (db::table('schoolinfo')->first()->shssetup == 0) {
							$q->where('semid', $semid);
						}
					})
					->where(function ($query) {
						$query->whereNull('classid')
							  ->orWhereNull('enrollid');
					})
					->where(function ($query) {
						$query->where('particulars', 'LIKE', '%PAYMENT FOR%')
							->orWhere('particulars', 'LIKE', 'ADJ:%')
							->orWhere('particulars', 'LIKE', 'DISCOUNT:%')
							->orWhere('particulars', 'LIKE', 'Balance forwarded from%');
					})
					->where('deleted', 0)
					->orderBy('createddatetime', 'asc')
					->get();
			}
			elseif($levelid >= 17 && $levelid <= 21)
			{
				$getLedger = db::table('studledger')
					->where('studid', $studid)
					->where('syid', $syid)
					->where('semid', $semid)
					->where(function ($query) {
						$query->whereNull('classid')
							  ->orWhereNull('enrollid');
					})
					->where(function ($query) {
						$query->where('particulars', 'LIKE', '%PAYMENT FOR%')
							->orWhere('particulars', 'LIKE', 'ADJ:%')
							->orWhere('particulars', 'LIKE', 'DISCOUNT:%')
							->orWhere('particulars', 'LIKE', 'Balance forwarded from%');
					})
					->where('deleted', 0)
					->orderBy('createddatetime', 'asc')
					->get();
			}
			else
			{
				$getLedger = db::table('studledger')
					->where('studid', $studid)
					->where('syid', $syid)
					->where(function ($query) {
						$query->whereNull('classid')
							  ->orWhereNull('enrollid');
					})
					->where(function ($query) {
						$query->where('particulars', 'LIKE', '%PAYMENT FOR%')
							->orWhere('particulars', 'LIKE', 'ADJ:%')
							->orWhere('particulars', 'LIKE', 'DISCOUNT:%')
							->orWhere('particulars', 'LIKE', 'Balance forwarded from%');
					})
					->where('deleted', 0)
					->orderBy('createddatetime', 'asc')
					->get();
			}

			return $getLedger;


			echo json_encode($data);
		// }
	}

    public function getStudLedgerV2(Request $request)
	{
		// if($request->ajax())
		// {
			$syid = $request->get('syid');
			$studid = $request->get('studid');
			$semid = $request->get('semid');
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
			$payment_details = [];
			$adjWithItemsClassid = [];
			
			$info = db::table('studinfo')
					->where('id', $studid)
					->first();
			
			$tuitionheader = db::table('tuitionheader')
				->where('id', $info->feesid)
				->first();

			$itemclassifications = db::table('itemclassification')
				->where('deleted', 0)
				->get();
	
			if($tuitionheader)
			{
				$feesname = $tuitionheader->description;
				$feesid = $tuitionheader->id;
			}

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
				$levelid = $enrolled->levelid;
				$levelname = db::table('gradelevel')->where('id', $levelid)->first()->levelname;
				$studstatus = $enrolled->studstatus;

				$sec = db::table('sections')
					->where('id', $enrolled->sectionid)
					->first();

				if($sec)
				{
					$section = $sec->sectionname;
				}
			}
			else
			{
				$enrolled = db::table('sh_enrolledstud')
					->where('studid', $studid)
					->where('syid', $syid)
					->where(function($q) use($semid){
						if($semid == 3)
						{
							$q->where('semid', 3);
						}
						else
						{
							if(db::table('schoolinfo')->first()->shssetup == 0)
							{
								$q->where('semid', $semid);
							}
						}
					})
					->where('deleted', 0)
					->first();

				if($enrolled)
				{
					$levelid = $enrolled->levelid;
					$levelname = db::table('gradelevel')->where('id', $levelid)->first()->levelname;
					$studstatus = $enrolled->studstatus;

					$sec = db::table('sections')
					->where('id', $enrolled->sectionid)
					->first();

					if($sec)
					{
						$section = $sec->sectionname;
					}

					$strnd = db::table('sh_strand')
						->where('id', $enrolled->strandid)
						->first();

					if($strnd)
					{
						$strand = $strnd->strandcode;
					}
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
						$levelid = $enrolled->yearLevel;
						$levelname = db::table('gradelevel')->where('id', $levelid)->first()->levelname;
						$studstatus = $enrolled->studstatus;

						$sec = db::table('college_sections')
						->where('id', $enrolled->sectionID)
						->first();

						if($sec)
						{
							$section = $sec->sectionDesc;
						}
					}
					else
					{
						$levelid = $info->levelid;
						$levelname = db::table('gradelevel')->where('id', $levelid)->first()->levelname;
						$studstatus = 0;
					}
				}
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

			$shsSetup = DB::table('schoolinfo')->first()->shssetup;

			$getLedgerQuery = DB::table('studledger')
				->where('studid', $studid)
				->where('syid', $syid)
				// ->where('semid', $semid)
				->where('deleted', 0)
				->where(function ($query) {
					$query->where(function ($q) {
						$q->whereNotNull('enrollid')
						->whereNotNull('classid');
					})
					->orWhere('classid', 34)
					// ->orWhere('particulars', 'LIKE', 'Balance forwarded from%');
					->orWhere('particulars', 'LIKE', '%OLD ACCOUNTS FORWARDED FROM SY%')
					->orWhere('particulars', 'LIKE', '%OLD ACCOUNTS FORWARDED TO SY%');
				})
				->where(function ($query) {
					$query->where('particulars', 'NOT LIKE', 'ADJ:%');
				})
				->orderBy('createddatetime', 'asc');
			if ($levelid == 14 || $levelid == 15) {
				if ($shsSetup == 0) {
					$getLedgerQuery->where('semid', $semid);
				}
			} elseif ($levelid >= 17 && $levelid <= 21) {
				$getLedgerQuery->where('semid', $semid);
			}

			$getLedger = $getLedgerQuery->get();
			// added by clydev
			// get old accounts history
			foreach ($getLedger as  $lg) {
				if (strpos($lg->particulars, 'OLD ACCOUNTS FORWARDED FROM') !== false) {
					$history = DB::table('studledger')
						->where('studid', $lg->studid)
						->where('id', '!=', $lg->id)
						->where('amount', '>', 0)
						->where('payment', 0)
						->where('deleted', 0)
						->orderBy('createddatetime', 'asc')
						->get();
					$lg->history = $history;
				}

			}

			$ledgerClassId = $getLedger->pluck('classid');
			
			// additional payment by adjustment debit
			$adjustmentDebitItemQuery = DB::table('studledger')
				->where('studid', $studid)
				->where('syid', $syid)
				->where('deleted', 0)
				->whereNotIn('classid', $ledgerClassId)
				->where('particulars', 'LIKE', 'ADJ:%')
				->where('payment', 0.00)
				->orderBy('createddatetime', 'asc');

			if ($levelid == 14 || $levelid == 15) {
				if ($shsSetup == 0) {
					$adjustmentDebitItemQuery->where('semid', $semid);
				}
			} elseif ($levelid >= 17 && $levelid <= 21) {
				$adjustmentDebitItemQuery->where('semid', $semid);
			}

			$getDebitAdditional = $adjustmentDebitItemQuery->get();

			$getDebitAdditional = $getDebitAdditional->map(function ($item) use ($studid) {
				$item->particulars = str_replace('ADJ: ', '', $item->particulars);

				$adjustment_items = DB::table('adjustmentitems')
					->where('detailid', $item->ornum)
					->where('deleted', 0)
					// ->where('studid', $studid)
					->get();

				$item->adjustment = 1;
				$item->adjustmentcount = count($adjustment_items);
				$item->adjustmentitemsclassid = $adjustment_items->pluck('classid')->unique()->values();
				$item->adjustmentitems = $adjustment_items;
				
				return $item;
			});


			// change particular name if naay classid sa adjustment 
			foreach ($itemclassifications as $itemclass) {
				foreach ($getDebitAdditional as $getDebit) {
					if ($itemclass->id == $getDebit->classid) {
						$getDebit->particulars = $itemclass->description;
					}
				}
			}
			
			// Add Adjustment if its in ledgerClassId
			$adjustmentDebitItemInledgerClassIdQuery = DB::table('studledger')
				->where('studid', $studid)
				->where('syid', $syid)
				->where('deleted', 0)
				->whereIn('classid', $ledgerClassId)
				->where('particulars', 'LIKE', 'ADJ:%')
				->where('payment', 0.00)
				->orderBy('createddatetime', 'asc');

			if ($levelid == 14 || $levelid == 15) {
				if ($shsSetup == 0) {
					$adjustmentDebitItemInledgerClassIdQuery->where('semid', $semid);
				}
			} elseif ($levelid >= 17 && $levelid <= 21) {
				$adjustmentDebitItemInledgerClassIdQuery->where('semid', $semid);
			}
			$getDebitAdditionalInledgerClassId = $adjustmentDebitItemInledgerClassIdQuery->get();
			
			foreach ($getLedger as $ledger) {
				foreach ($getDebitAdditionalInledgerClassId as $adjustment) {
					$adjustment->debitadjust = 1;
					$adjustment->totalamount = $adjustment->payment;
					$adjustment->itemamount = $adjustment->amount;
					$adjustment->particulars = str_replace('ADJ: ', '', $adjustment->particulars);
					if ($ledger->classid == $adjustment->classid) {
						$ledger->amount += $adjustment->amount;
					}
				}
			}
			
			$finalLedger = $getLedger->merge($getDebitAdditional);

			// $finalLedger = collect($finalLedger)->where('particulars', 'DAMAGES')->values();

			$ors = $getLedger->pluck('ornum');


			$ordetails = DB::table('studledger')
				->where('studid', $studid)
				->where('syid', $syid)
				->when(in_array($levelid, [14, 15]), function ($query) use ($semid) {
					if (DB::table('schoolinfo')->first()->shssetup == 0) {
						$query->where('semid', $semid);
					}
				})
				->when($levelid >= 17 && $levelid <= 21, function ($query) use ($semid) {
					$query->where('semid', $semid);
				})
				->where(function ($query) {
					$query->whereNull('classid')->orWhereNull('enrollid');
				})
				->where(function ($query) {
					$query->where('particulars', 'LIKE', '%PAYMENT FOR%');
				})
				->where('deleted', 0)
				->orderBy('createddatetime', 'asc')
				->get();
				
			$ornumbers = $ordetails->pluck('ornum');

			// get discounts
			$stud_discounts = db::table('studdiscounts')
				->select(db::raw('sid, lastname, firstname, middlename, discounts.percent, particulars, classid, groupid, discounts.amount as discount,
					sum(discamount) as discamount, discounts.id, studid, studdiscounts.syid, studdiscounts.semid, studdiscounts.id as studdiscountid'))
				->join('discounts', 'studdiscounts.discountid', '=', 'discounts.id')
				->join('studinfo', 'studdiscounts.studid', '=', 'studinfo.id')
				->where('studdiscounts.deleted', 0)
				->where('studdiscounts.studid', $studid)
				->where('studdiscounts.syid', $syid)
				->when(in_array($levelid, [14, 15]), function ($query) use ($semid) {
					if (DB::table('schoolinfo')->first()->shssetup == 0) {
						$query->where('studdiscounts.semid', $semid);
					}
				})
				->when($levelid >= 17 && $levelid <= 21, function ($query) use ($semid) {
					$query->where('studdiscounts.semid', $semid);
				})
				->get();
			
			// Step 1: Get all payment transactions for the student
			$payments = DB::table('chrngtrans')
				->whereIn('ornum', $ornumbers)
				->where('studid', $studid)
				->where('syid', $syid)
				->where('cancelled', 0)
				->pluck('transno');
			
			// Step 2: Get additional payments from adjustments (ADJ: items)
			$adjustmentCreditQuery = DB::table('studledger')
				->select('classid', DB::raw('SUM(payment) as totalamount'))
				->where('studid', $studid)
				->where('syid', $syid)
				->where('deleted', 0)
				->where('particulars', 'LIKE', 'ADJ:%')
				->where('payment', '!=', 0.00)
				->groupBy('classid');
			
			// Apply semester conditions
			if (($levelid == 14 || $levelid == 15) && $shsSetup == 0) {
				$adjustmentCreditQuery->where('semid', $semid);
			} elseif ($levelid >= 17 && $levelid <= 21) {
				$adjustmentCreditQuery->where('semid', $semid);
			}

			// Execute query and get adjustment payments
			$adjustmentPayments = $adjustmentCreditQuery->pluck('totalamount', 'classid');
			
			// Step 3: Get direct payments from `chrngcashtrans`
			$directPayments = collect();
			if ($payments->isNotEmpty()) {
				$directPayments = DB::table('chrngcashtrans')
					->select('classid', DB::raw('SUM(amount) as totalamount'))
					->whereIn('transno', $payments)
					->where('deleted', 0)
					->groupBy('classid')
					->pluck('totalamount', 'classid');
				
			}
			
			$adjWithItemsClassid = $getDebitAdditional->where('adjustmentcount', '>', 0)->values();


			if ($adjWithItemsClassid->isNotEmpty()) {
				foreach ($adjWithItemsClassid as $adjustment) {
			
					$adjustmentClassIds = $adjustment->adjustmentitemsclassid; // This is a collection of class IDs like [1, 2]
					
					// Only proceed if the adjustment classid is one of the adjustmentitems class IDs
					if ($adjustmentClassIds->contains($adjustment->classid)) {
						$totalAdjustmentAmount = collect($adjustmentClassIds)->sum(function ($classid) use ($directPayments) {
							return $directPayments[$classid] ?? 0;
						});
			
						$directPayments[$adjustment->classid] = $totalAdjustmentAmount;
					}
				}
			}

			$directPayments = collect($directPayments);
			
			foreach ($directPayments as $classid => $amount) {
				
				foreach ($stud_discounts as $discount) {
					if ($classid == $discount->classid) {
						$amount += $discount->discamount;
					}
				}
				$directPayments[$classid] = $amount;
			}
			
			// Step 4: Merge payments correctly (sum instead of overwrite)
			$mergedPayments = collect($directPayments)
				->union($adjustmentPayments)
				->mapWithKeys(function ($amount, $classid) use ($directPayments, $adjustmentPayments) {
					return [
						$classid => ((float) ($directPayments[$classid] ?? 0)) + ((float) ($adjustmentPayments[$classid] ?? 0))
					];
				});
				
		
			foreach ($finalLedger as $ledger) {
				$ledger->totalpaid = $mergedPayments[$ledger->classid] ?? 0;
			}

			$particular_items = [];

			foreach ($finalLedger as $ledger) {
				$ledger->totalpaid = $mergedPayments[$ledger->classid] ?? 0;

				if($ledger->classid == 1){
					$tuitiondetails = DB::table('studpayscheddetail')
						->where('studpayscheddetail.studid', $studid)
						->where('studpayscheddetail.syid', $syid)
						->where(function($q) use($levelid, $semid) {
							if ($levelid == 14 || $levelid == 15) {
								if (DB::table('schoolinfo')->first()->shssetup == 0) {
									$q->where('studpayscheddetail.semid', $semid);
								}
							}

							if ($levelid >= 17 && $levelid <= 21) {
								$q->where('studpayscheddetail.semid', $semid);
							}
						})
						->where('studpayscheddetail.deleted', 0)
						->where('studpayscheddetail.classid', $ledger->classid)
						->get()
						->map(function ($item) {
							// Extract month and convert to uppercase
							$month = strtoupper(date('F', strtotime($item->duedate)));
							$item->tuition_month = "TUITION $month";
							return $item;
						});

					foreach ($tuitiondetails as $tuitiondetail) {
						$tuitiondetail->ledger_classid = $ledger->classid;
					}

					$ledger->particular_items_count = count($tuitiondetails);
					$ledger->particular_items = $tuitiondetails;
				} else {
		

					$studledgeritemized = DB::table('studledgeritemized')
						->join('items', 'studledgeritemized.itemid', 'items.id')
						->where('studledgeritemized.studid', $studid)
						->where('studledgeritemized.syid', $syid)
						->where(function($q) use($levelid, $semid){
							if($levelid == 14 || $levelid == 15)
							{
								if(db::table('schoolinfo')->first()->shssetup == 0)
								{
									$q->where('studledgeritemized.semid', $semid);
								}
							}

							if($levelid >= 17 && $levelid <= 21)
							{
								$q->where('studledgeritemized.semid', $semid);
							}
						})
						->where('items.deleted', 0)
						->where('studledgeritemized.deleted', 0)
						->where('studledgeritemized.classificationid', $ledger->classid)
						->get();
					
					foreach ($studledgeritemized as $studitemized) {
						$studitemized->ledger_classid = $ledger->classid;
					}
				
					// $debitadjustment = collect($getDebitAdditionalInledgerClassId)->where('classid', $ledger->classid)->values();
				
					$ledger->particular_items_count = count($studledgeritemized);
					$ledger->particular_items = $studledgeritemized;
				}
			}
		
			return $finalLedger;
			// Return the final result
			

			echo json_encode($data);
		// }
	}

    

}
