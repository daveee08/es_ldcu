<?php

namespace App\Http\Controllers\SuperAdminController\College;

use Illuminate\Http\Request;
use DB;
use Session;

class CollegeGradingController extends \App\Http\Controllers\Controller
{


      public static function update_section(){

            $syid = 3;
            $semid = 1;

            $grades = DB::table('college_studentprospectus')
                        ->where('syid',$syid)
                        ->where('semid',$semid)
                        ->get();

            foreach($grades as $item){

                  $p_id = $item->prospectusID;
                  
                  $check_sched = DB::table('college_studsched')
                                    ->join('college_classsched',function($join) use($p_id){
                                          $join->on('college_studsched.schedid','=','college_classsched.id');
                                          $join->where('college_classsched.subjectID',$p_id);
                                    })
                                    ->where('studid',$item->studid)
                                    ->first();

                  if(isset($check_sched->sectionID)){
                        if($item->sectionid != $check_sched->sectionID){
                              DB::table('college_studentprospectus')
                                    ->where('studid',$item->studid)
                                    ->where('prospectusID',$p_id)
                                    ->where('syid',$syid)
                                    ->where('semid',$semid)
                                    ->take(1)
                                    ->update([
                                          'sectionid'=>$check_sched->sectionID
                                    ]);
                        }
                  }
            
            }

            return $grades;

      }


      //grade status
            // 1 - submitted
            // 2 - Dean Approve
            // 3 - Pending
            // 4 - Posted
            // 7 - Program Head Approved
            // 8 - INC
            // 9 - Dropped
            // 10 - Unpost


      public static function unpost_grades_ph(Request $request){
            try{
                  $syid = $request->get('syid');
                  $semid = $request->get('semid');
                  $term = $request->get('term');
                  $selected = $request->get('selected');
                  $termholder = $term;
                  $remarks = $request->get('remarks');
                  
                  if($term == "prelemgrade"){
                        $term = 'prelemstatus';
                  }else if($term == "midtermgrade"){
                        $term = 'midtermstatus';
                  }else if($term == "prefigrade"){
                        $term = 'prefistatus';
                  }else if($term == "finalgrade"){
                        $term = 'finalstatus';
                  }
                  DB::table('college_studentprospectus')
                        ->whereIn('id',$selected)
                        ->where('syid',$syid)
                        ->where('semid',$semid)
                        ->where(function($query) use($term){
                              $query->where($term,4);
                              $query->orWhere($term,7);
                        })
                        ->update([
                              $term => 10,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);


                  $reflist = DB::table('college_studentprospectus')
                              ->whereIn('id',$selected)
                              ->where('syid',$syid)
                              ->where('semid',$semid)
                              ->whereIn($term,[10])
                              ->select([
                                    'id',
                                    'prelimstatref',
                                    'midstatref',
                                    'prefistatref',
                                    'finaltermstatref',
                              ])
                              ->get();


                  foreach($selected as $item){

                        if($termholder == "prelemstatus" || $termholder == "prelemgrade"){
                              $refterm = 'prelimstatref';
                        }else if($termholder == "midtermstatus" || $termholder == "midtermgrade"){
                              $refterm = 'midstatref';
                        }else if($termholder == "prefigrade" || $termholder == "prefigrade"){
                              $refterm = 'prefistatref';
                        }else if($termholder == "finalstatus" || $termholder == "finalgrade"){
                              $refterm = 'finaltermstatref';
                        }

                        $refid = collect($reflist)->where('id',$item)->values();

                        
                        $remarkinfo = collect($remarks)->where('id',$item)->first();
                        $remarks_text = isset( $remarkinfo['remarks']) ? $remarkinfo['remarks'] : '';

                        if(count($refid) > 0){
                              $refid =  $refid[0]->$refterm;
                              if($refid == null){
                                    $refid = self::create_college_studentprosstat($termholder,$item,$syid,$semid,$refterm);
                              }
                              DB::table('college_studentprosstat')
                                    ->where('id',$refid)
                                    ->take(1)
                                    ->update([
                                          'unpoststat'=>1,
                                          'unpostby'=>auth()->user()->id,
                                          'unpoststatdatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                          'updatedby'=>auth()->user()->id,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                              
                  }

                  return array((object)[
                        'status'=>1,
                  ]);
            }catch(\Exception $e){
                  return array((object)[
                        'status'=>0
                  ]);
            }
            
      }

      public static function pending_grades_ph(Request $request){
            try{
                  $syid = $request->get('syid');
                  $semid = $request->get('semid');
                  $term = $request->get('term');
                  $selected = $request->get('selected');
                  $termholder = $term;
                  $remarks = $request->get('remarks');
               
                  if($term == "prelemgrade"){
                        $term = 'prelemstatus';
                  }else if($term == "midtermgrade"){
                        $term = 'midtermstatus';
                  }else if($term == "prefigrade"){
                        $term = 'prefistatus';
                  }else if($term == "finalgrade"){
                        $term = 'finalstatus';
                  }
                  DB::table('college_studentprospectus')
                        ->whereIn('id',$selected)
                        ->where('syid',$syid)
                        ->where('semid',$semid)
                        ->where(function($query) use($term){
                              $query->where($term,1);
                              $query->orWhere($term,7);
                              $query->orWhere($term,2);
                              $query->orWhere($term,4);
                              $query->orWhere($term,10);
                        })
                        ->update([
                              $term => 3,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);


                  $reflist = DB::table('college_studentprospectus')
                              ->whereIn('id',$selected)
                              ->where('syid',$syid)
                              ->where('semid',$semid)
                              ->whereIn($term,[3])
                              ->select([
                                    'id',
                                    'prelimstatref',
                                    'midstatref',
                                    'prefistatref',
                                    'finaltermstatref',
                              ])
                              ->get();


                  foreach($selected as $item){

                        if($termholder == "prelemstatus" || $termholder == "prelemgrade"){
                              $refterm = 'prelimstatref';
                        }else if($termholder == "midtermstatus" || $termholder == "midtermgrade"){
                              $refterm = 'midstatref';
                        }else if($termholder == "prefistatus" || $termholder == "prefigrade"){
                              $refterm = 'prefistatref';
                        }else if($termholder == "finalstatus" || $termholder == "finalgrade"){
                              $refterm = 'finaltermstatref';
                        }

                        $refid = collect($reflist)->where('id',$item)->values();

                       
                        $remarkinfo = collect($remarks)->where('id',$item)->first();
                        $remarks_text = isset( $remarkinfo['remarks']) ? $remarkinfo['remarks'] : '';

                        if(count($refid) > 0){
                              $refid =  $refid[0]->$refterm;
                              if($refid == null){
                                    $refid = self::create_college_studentprosstat($termholder,$item,$syid,$semid,$refterm);
                              }
                              DB::table('college_studentprosstat')
                                    ->where('id',$refid)
                                    ->take(1)
                                    ->update([
                                          'pendcom'=>$remarks_text,
                                          'pendstat'=>1,
                                          'pendby'=>auth()->user()->id,
                                          'pendstatdatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                          'updatedby'=>auth()->user()->id,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                              
                  }

                  return array((object)[
                        'status'=>1,
                  ]);
            }catch(\Exception $e){
                  return array((object)[
                        'status'=>0
                  ]);
            }
            
      }


      public static function approve_grades_ph(Request $request){
            try{
                  $syid = $request->get('syid');
                  $semid = $request->get('semid');
                  $term = $request->get('term');
                  $selected = $request->get('selected');
                  $termholder = $term;
                
                  if($term == "prelemgrade"){
                      $term = 'prelemstatus';
                  }else if($term == "midtermgrade"){
                      $term = 'midtermstatus';
                  }else if($term == "prefigrade"){
                      $term = 'prefistatus';
                  }else if($term == "finalgrade"){
                      $term = 'finalstatus';
                  }

                  if(Session::get('currentPortal') == 14){

                        DB::table('college_studentprospectus')
                        ->whereIn('id',$selected)
                        ->where('syid',$syid)
                        ->where('semid',$semid)
                        ->whereIn($term,[1,7,3,10])
                        ->update([
                              $term => 2,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  }else{
                      
                        if(Session::get('currentPortal') == 3 || Session::get('currentPortal') == 17 ){
                              
                              DB::table('college_studentprospectus')
                                    ->whereIn('id',$selected)
                                    ->where('syid',$syid)
                                    ->where('semid',$semid)
                                    ->where(function($query) use($term){
                                          $query->whereIn($term,[1,7,3,10]);
                                          $query->orWhereNull($term);
                                    })
                                    ->update([
                                          $term => 7,
                                          'updatedby'=>auth()->user()->id,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }else{
                              DB::table('college_studentprospectus')
                                    ->whereIn('id',$selected)
                                    ->where('syid',$syid)
                                    ->where('semid',$semid)
                                    ->whereIn($term,[1,7,3,10])
                                    ->update([
                                          $term => 7,
                                          'updatedby'=>auth()->user()->id,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                              
                  }

                  
                  $reflist = DB::table('college_studentprospectus')
                              ->whereIn('id',$selected)
                              ->where('syid',$syid)
                              ->where('semid',$semid)
                              ->whereIn($term,[2,7])
                              ->select([
                                    'id',
                                    'prelimstatref',
                                    'midstatref',
                                    'prefistatref',
                                    'finaltermstatref',
                              ])
                              ->get();

                  // return $reflist;

                  foreach($selected as $item){

                        if($termholder == "prelemstatus" || $termholder == "prelemgrade"){
                              $refterm = 'prelimstatref';
                        }else if($termholder == "midtermstatus" || $termholder == "midtermgrade"){
                              $refterm = 'midstatref';
                        }else if($termholder == "prefistatus" || $termholder == "prefigrade"){
                              $refterm = 'prefistatref';
                        }else if($termholder == "finalstatus" || $termholder == "finalgrade"){
                              $refterm = 'finaltermstatref';
                        }

                        $refid = collect($reflist)->where('id',$item)->values();

                       

                        if(count($refid) > 0){
                              $refid =  $refid[0]->$refterm;

                              if($refid != null){
                                    $check_status = DB::table('college_studentprosstat')
                                                      ->where('id',$refid)
                                                      ->first();

                                    if($check_status->pendstat == 1){
                                          $refid = null;
                                    }
                              }

                              if($refid == null){
                                    $refid = self::create_college_studentprosstat($termholder,$item,$syid,$semid,$refterm);
                              }

                              DB::table('college_studentprosstat')
                                    ->where('id',$refid)
                                    ->take(1)
                                    ->update([
                                          'appstat'=>1,
                                          'appby'=>auth()->user()->id,
                                          'appstatdatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                          'updatedby'=>auth()->user()->id,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                              
                  }




                  return array((object)[
                      'status'=>1,
                  ]);
            }catch(\Exception $e){
                  return $e;
                  return array((object)[
                        'status'=>0
                  ]);
            }
      }

      public static function approve_grades_dean(Request $request){
            try{
                  $syid = $request->get('syid');
                  $semid = $request->get('semid');
                  $term = $request->get('term');
                  $selected = $request->get('selected');
                  $termholder = $term;

                  if($term == "prelemgrade"){
                      $term = 'prelemstatus';
                  }else if($term == "midtermgrade"){
                      $term = 'midtermstatus';
                  }else if($term == "prefigrade"){
                      $term = 'prefistatus';
                  }else if($term == "finalgrade"){
                      $term = 'finalstatus';
                  }
                  
                  DB::table('college_studentprospectus')
                      ->whereIn('id',$selected)
                      ->where('syid',$syid)
                      ->where('semid',$semid)
                      ->update([
                          $term => 2,
                          'updatedby'=>auth()->user()->id,
                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                      ]);

                  $reflist = DB::table('college_studentprospectus')
                              ->whereIn('id',$selected)
                              ->where('syid',$syid)
                              ->where('semid',$semid)
                              ->whereIn($term,[2])
                              ->select([
                                    'id',
                                    'prelimstatref',
                                    'midstatref',
                                    'prefistatref',
                                    'finaltermstatref',
                              ])
                              ->get();


                  foreach($selected as $item){

                        if($termholder == "prelemstatus" || $termholder == "prelemgrade"){
                              $refterm = 'prelimstatref';
                        }else if($termholder == "midtermstatus" || $termholder == "midtermgrade"){
                              $refterm = 'midstatref';
                        }else if($termholder == "prefistatus" || $termholder == "prefigrade"){
                              $refterm = 'prefistatref';
                        }else if($termholder == "finalstatus" || $termholder == "finalgrade"){
                              $refterm = 'finaltermstatref';
                        }

                        $refid = collect($reflist)->where('id',$item)->values();

                        if(count($refid) > 0){

                              $refid = $refid[0]->$refterm;

                              if($refid != null){
                                    $check_status = DB::table('college_studentprosstat')
                                                      ->where('id',$refid)
                                                      ->first();

                                    if($check_status->pendstat == 1){
                                          $refid = null;
                                    }
                              }


                              if($refid == null){
                                    $refid = self::create_college_studentprosstat($termholder,$item,$syid,$semid,$refterm);
                              }

                              DB::table('college_studentprosstat')
                                    ->where('id',$refid)
                                    ->take(1)
                                    ->update([
                                          'appstat'=>1,
                                          'appby'=>auth()->user()->id,
                                          'appstatdatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                          'updatedby'=>auth()->user()->id,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }

                  }

                      
                  return array((object)[
                      'status'=>1,
                  ]);
            }catch(\Exception $e){
                  return $e;
                  return array((object)[
                        'status'=>0
                  ]);
            }
      }

      public static function post_grades_dean(Request $request){
            try{
                  $syid = $request->get('syid');
                  $semid = $request->get('semid');
                  $term = $request->get('term');
                  $selected = $request->get('selected');
                  $termholder = $term;

                  if($term == "prelemgrade"){
                      $term = 'prelemstatus';
                  }else if($term == "midtermgrade"){
                      $term = 'midtermstatus';
                  }else if($term == "prefigrade"){
                      $term = 'prefistatus';
                  }else if($term == "finalgrade"){
                      $term = 'finalstatus';
                  }
                  
                  DB::table('college_studentprospectus')
                      ->whereIn('id',$selected)
                      ->where('syid',$syid)
                      ->where('semid',$semid)
                      ->update([
                          $term => 4,
                          'updatedby'=>auth()->user()->id,
                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                      ]);

                  $reflist = DB::table('college_studentprospectus')
                              ->whereIn('id',$selected)
                              ->where('syid',$syid)
                              ->where('semid',$semid)
                              ->whereIn($term,[4])
                              ->select([
                                    'id',
                                    'prelimstatref',
                                    'midstatref',
                                    'prefistatref',
                                    'finaltermstatref',
                              ])
                              ->get();


                  foreach($selected as $item){

                        if($termholder == "prelemstatus" || $termholder == "prelemgrade"){
                              $refterm = 'prelimstatref';
                        }else if($termholder == "midtermstatus" || $termholder == "midtermgrade"){
                              $refterm = 'midstatref';
                        }else if($termholder == "prefistatus" || $termholder == "prefigrade"){
                              $refterm = 'prefistatref';
                        }else if($termholder == "finalstatus" || $termholder == "finalgrade"){
                              $refterm = 'finaltermstatref';
                        }



                        $refid = collect($reflist)->where('id',$item)->values();

                        if(count($refid) > 0){

                              $refid = $refid[0]->$refterm;

                              if($refid != null){
                                    $check_status = DB::table('college_studentprosstat')
                                                      ->where('id',$refid)
                                                      ->first();

                                    if($check_status->pendstat == 1){
                                          $refid = null;
                                    }
                              }


                              if($refid == null){
                                    $refid = self::create_college_studentprosstat($termholder,$item,$syid,$semid,$refterm);
                              }

                              DB::table('college_studentprosstat')
                                    ->where('id',$refid)
                                    ->take(1)
                                    ->update([
                                          'poststat'=>1,
                                          'postby'=>auth()->user()->id,
                                          'poststatdatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                                          'updatedby'=>auth()->user()->id,
                                          'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                              
                  }

                  return array((object)[
                      'status'=>1,
                  ]);

            }catch(\Exception $e){
                  return array((object)[
                        'status'=>0
                  ]);
            }
      }

      public static function create_college_studentprosstat($termholder,$selected,$syid,$semid,$refterm){

            $refid = DB::table('college_studentprosstat')
                        ->insertGetId([
                              'term'=>str_replace('grade','',$termholder),
                              'createdby'=>auth()->user()->id,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                              'headerid'=>$selected
                        ]);

            DB::table('college_studentprospectus')
                    ->where('id',$selected)
                    ->where('syid',$syid)
                    ->where('semid',$semid)
                    ->take(1)
                    ->update([
                        $refterm => $refid
                    ]);

            return $refid;
      }

      public static function all_grades(Request $request){

            $syid = 11;
            $semid = 1;
            $courseid = $request->get('courseid');

            $enrolled = DB::table('college_enrolledstud')
                              ->where('college_enrolledstud.syid',$syid)
                              ->where('college_enrolledstud.semid',$semid)
                              ->where('college_enrolledstud.deleted',0)
                              ->select(
                                    'studid'
                              )
                              ->get();

            $courses = DB::table('college_courses')
                              ->where('id',$courseid)
                              ->where('deleted',0)
                              ->get();

            foreach($courses as $course){

                  $enrolled = DB::table('college_enrolledstud')
                        ->where('courseID',$course->id)
                        ->where('college_enrolledstud.syid',$syid)
                        ->where('college_enrolledstud.semid',$semid)
                        ->where('college_enrolledstud.deleted',0)
                        ->select(
                              'studid'
                        )
                        ->get();

                  $students = array();

                  foreach($enrolled as $item){
                        array_push($students,$item->studid);
                  }

                  $student_sched = Db::table('college_loadsubject')
                                          ->join('college_classsched',function($join) use($syid,$semid){
                                                $join->on('college_loadsubject.schedid','=','college_classsched.id');
                                                $join->where('college_classsched.deleted',0);
                                                $join->where('college_classsched.syid',$syid);
                                                $join->where('college_classsched.semid',$semid);
                                          })
                                          ->whereIn('studid',$students)
                                          ->where('college_loadsubject.deleted',0)
                                          ->select(
                                                'college_loadsubject.studid',
                                                'college_classsched.subjectID'      
                                          )
                                          ->get();

                  $student_grade = Db::table('college_studentprospectus')
                                          ->whereIn('studid',$students)
                                          ->where('college_studentprospectus.deleted',0)
                                          ->select(
                                                'college_studentprospectus.id',
                                                'college_studentprospectus.studid',
                                                'college_studentprospectus.prospectusID'
                                          )
                                          ->get();

                  
                  foreach($enrolled as $item){
                        $temp_sched = collect($student_sched)->where('studid',$item->studid)->values();
                  }
                

            }

            return $student_sched;

      }

      public static function enrolled_students(Request $request){

            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $courseid = $request->get('courseid');
            $gradelevel = $request->get('gradelevel');

            $enrolled = DB::table('college_enrolledstud')
                                    ->join('studinfo',function($join) {
                                          $join->on('college_enrolledstud.studid','=','studinfo.id');
                                          $join->where('studinfo.deleted',0);
                                    })
                                    ->join('college_courses',function($join){
                                          $join->on('college_enrolledstud.courseid','=','college_courses.id');
                                          $join->where('college_courses.deleted',0);
                                    })
                                    ->join('college_loadsubject',function($join){
                                          $join->on('college_enrolledstud.studid','=','college_loadsubject.studid');
                                    })
                                    ->when($courseid, function ($query) use ($courseid) {
                                          $query->where('college_enrolledstud.courseid', $courseid);
                                    })
                                    ->when($gradelevel, function ($query) use ($gradelevel) {
                                          $query->where('college_enrolledstud.yearLevel', $gradelevel);
                                    })
                                    ->where('college_enrolledstud.syid',$syid)
                                    ->where('college_enrolledstud.semid',$semid)
                                    ->where('college_enrolledstud.deleted',0)
                                    ->whereIn('college_enrolledstud.studstatus',[1,2,4])
                                    ->select(
                                          'yearLevel as levelid',
                                          'courseabrv',
                                          'gender',
                                          'sid',
                                          'college_enrolledstud.studid',
                                          'lastname',
                                          'firstname',
                                          'middlename',
                                          'college_enrolledstud.sectionid',
                                          DB::raw("CONCAT(studinfo.lastname,', ',studinfo.firstname) as studentname")
                                    )
                                    ->orderBy('studentname')
                                    ->orderBy('studentname')
                                    ->get();

            return $enrolled;

      }

      public static function college_sections(Request $request){

            $teacherid = DB::table('teacher')
                        ->where('tid',auth()->user()->email)
                        ->select('id')
                        ->first()
                        ->id;

            $courseid = $request->get('courseid');
            $gradelevel = $request->get('gradelevel');

            // if(Session::get('currentPortal') == 14){

            //       $cp_course = DB::table('college_colleges')
            //                         ->join('college_courses',function($join){
            //                               $join->on('college_colleges.id','=','college_courses.collegeid');
            //                               $join->where('college_courses.deleted',0);
            //                         })
            //                         ->where('college_colleges.deleted',0)
            //                         ->where('college_colleges.dean',$teacherid)
            //                         ->select(
            //                               'college_courses.id',
            //                               'college_courses.courseDesc',
            //                               'college_courses.courseabrv'
            //                               )
            //                         ->get();
            // }else{
            //       $cp_course = DB::table('college_courses')
            //                         ->where('courseChairman',$teacherid)
            //                         ->select('id','courseDesc','courseabrv')
            //                         ->get();
            // }

            if($courseid == ''){

                  $cp_course = DB::table('college_colleges')
                                    ->join('college_courses',function($join){
                                          $join->on('college_colleges.id','=','college_courses.collegeid');
                                          $join->where('college_courses.deleted',0);
                                    })
                                    ->where('college_colleges.deleted',0)
                                    ->where('college_colleges.dean',$teacherid)
                                    ->select(
                                          'college_courses.id',
                                          'college_courses.courseDesc',
                                          'college_courses.courseabrv'
                                          )
                                    ->get();
            }else{
                  // $cp_course = DB::table('college_courses')
                  //                   ->where('courseChairman',$teacherid)
                  //                   ->select('id','courseDesc','courseabrv')
                  //                   ->get();
                  $cp_course = [(object)['id' => $courseid]];

            }

         

            $syid = $request->get('syid');
            $semid = $request->get('semid');

            $array_course = array();

            foreach($cp_course as $item){
                  array_push($array_course,$item->id);
            }


            $schedule = DB::table('college_sections')
                              ->join('college_classsched',function($join){
                                    $join->on('college_sections.id','=','college_classsched.sectionID');
                                    $join->where('college_classsched.deleted',0);
                              })
                              ->join('college_prospectus',function($join){
                                    $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                    $join->where('college_prospectus.deleted',0);
                              })
                              ->join('college_instructor',function($join){
                                    $join->on('college_classsched.id','=','college_instructor.classschedID');
                                    $join->where('college_instructor.deleted',0);
                              })
                              ->where('college_sections.deleted',0)
                              ->where('college_sections.syID',$syid)
                              ->where('college_sections.semesterID',$semid)
                              ->when($gradelevel, function ($query) use ($gradelevel) {
                                    $query->where('college_sections.yearID', $gradelevel);
                              })
                              ->whereIn('college_sections.courseID',$array_course)
                              ->select(
                                    DB::raw('DISTINCT college_instructor.teacherID'),
                                    'college_classsched.subjectID',
                                    'college_classsched.id',
                                    'college_classsched.sectionID',
                                    'subjDesc',
                                    'subjCode'
                              )
                              ->get();
            
            $sections = DB::table('college_sections')
                              ->join('gradelevel',function($join) use($syid,$semid){
                                    $join->on('college_sections.yearID','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                              })
                              ->where('college_sections.deleted',0)
                              ->where('college_sections.syID',$syid)
                              ->where('college_sections.semesterID',$semid)
                              ->whereIn('college_sections.courseID',$array_course)
                              ->when($gradelevel, function ($query) use ($gradelevel) {
                                    $query->where('college_sections.yearID', $gradelevel);
                              })
                              ->select(
                                    'gradelevel.levelname',
                                    'college_sections.sectionDesc',
                                    'college_sections.id'
                              )
                              ->get();

            return array((object)[
                  'sections'=>$sections,
                  'sectionsched'=> $schedule
            ]);

      }

      public static function college_teachers(Request $request){

            
            $teacher = Db::table('teacher')
                        ->where('teacher.deleted',0)
                        ->select(
                              'id',
                              'tid',
                              'lastname',
                              'firstname',
                              'middlename',
                              DB::raw("CONCAT(teacher.lastname,', ',teacher.firstname) as teachername")
                        )
                        ->get();           


            return $teacher;
      }

      public static function college_subjects(Request $request){

            $teacherid = DB::table('teacher')
                        ->where('tid',auth()->user()->email)
                        ->select('id')
                        ->first()
                        ->id;

            $courseid = $request->get('courseid');
            $gradelevel = $request->get('gradelevel');

            $array_course = array();

            $syid = $request->get('syid');
            $semid = $request->get('semid');


            if($courseid == ''){

                  $cp_course = DB::table('college_colleges')
                                    ->join('college_courses',function($join){
                                          $join->on('college_colleges.id','=','college_courses.collegeid');
                                          $join->where('college_courses.deleted',0);
                                    })
                                    ->where('college_colleges.deleted',0)
                                    ->where('college_colleges.dean',$teacherid)
                                    ->select(
                                          'college_courses.id',
                                          'college_courses.courseDesc',
                                          'college_courses.courseabrv'
                                          )
                                    ->get();
            }else{
                  // $cp_course = DB::table('college_courses')
                  //                   ->where('courseChairman',$teacherid)
                  //                   ->select('id','courseDesc','courseabrv')
                  //                   ->get();
                  $cp_course = [(object)['id' => $courseid]];

            }


           
            // if(Session::get('currentPortal') == 14){
            //       $cp_course = DB::table('college_colleges')
            //                         ->join('college_courses',function($join){
            //                               $join->on('college_colleges.id','=','college_courses.collegeid');
            //                               $join->where('college_courses.deleted',0);
            //                         })
            //                         ->where('college_colleges.deleted',0)
            //                         ->where('college_colleges.dean',$teacherid)
            //                         ->select(
            //                               'college_courses.id',
            //                               'college_courses.courseDesc',
            //                               'college_courses.courseabrv'
            //                               )
            //                         ->get();
            // }else{
            //       $cp_course = DB::table('college_courses')
            //                         ->where('courseChairman',$teacherid)
            //                         ->select('id','courseDesc','courseabrv')
            //                         ->get();
            // }

            foreach($cp_course as $item){
                  array_push($array_course,$item->id);
            }
            

            $prospectus = DB::table('college_prospectus')
                              ->whereIn('courseID',$array_course)
                              ->where('semesterID', $semid)
                              ->join('college_courses',function($join) use($syid,$semid){
                                    $join->on('college_prospectus.courseID','=','college_courses.id');
                                    $join->where('college_courses.deleted',0);
                              })
                              ->join('gradelevel',function($join) use($syid,$semid){
                                    $join->on('college_prospectus.yearID','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                              })
                              ->when($gradelevel, function ($query) use ($gradelevel) {
                                    $query->where('college_prospectus.yearID', $gradelevel);
                              })
                              ->select(
                                    'levelname',
                                    'courseabrv',
                                    'college_prospectus.subjectID',
                                    'college_prospectus.id',
                                    'college_prospectus.subjCode',
                                    'college_prospectus.subjDesc'
                              )
                              ->get();

            return $prospectus;

      }


      public static function college_studsched(Request $request){

            $teacherid = DB::table('teacher')
                        ->where('tid',auth()->user()->email)
                        ->select('id')
                        ->first()
                        ->id;

            $courseid = $request->get('courseid');
            $gradelevel = $request->get('gradelevel');

            if(Session::get('currentPortal') == 14){
                  $cp_course = DB::table('college_colleges')
                                    ->join('college_courses',function($join) use($courseid){
                                          $join->on('college_colleges.id','=','college_courses.collegeid');
                                          $join->where('college_courses.deleted',0);
                                          $join->where('college_courses.id',$courseid);
                                    })
                                    ->where('college_colleges.deleted',0)
                                    ->where('college_colleges.dean',$teacherid)
                                    ->select(
                                          'college_courses.id',
                                          'college_courses.courseDesc',
                                          'college_courses.courseabrv'
                                          )
                                    ->get();
            }else{
                  $cp_course = DB::table('college_courses')
                                    ->where('courseChairman',$teacherid)
                                    ->where('id',$courseid)
                                    ->select('id','courseDesc','courseabrv')
                                    ->get();
            }

           

            $syid = $request->get('syid');
            $semid = $request->get('semid');

            $array_course = array();

            foreach($cp_course as $item){
                  array_push($array_course,$item->id);
            }

            $temp_sched = Db::table('college_sections')
                        ->join('college_classsched',function($join) use($syid,$semid){
                              $join->on('college_sections.id','=','college_classsched.sectionID');
                              $join->where('college_classsched.deleted',0);
                              $join->where('college_classsched.syID',$syid);
                              $join->where('college_classsched.semesterID',$semid);
                        })
                        ->join('college_prospectus',function($join){
                              $join->on('college_classsched.subjectID','=','college_prospectus.id');
                              $join->where('college_prospectus.deleted',0);
                        })
                        ->when($courseid, function ($query) use ($courseid) {
                              $query->where('college_sections.courseID',$courseid);
                        })
                        ->where('college_sections.syID',$syid)
                        ->where('college_sections.semesterID',$semid)
                        ->where('college_sections.deleted',0)
                        ->select('college_classsched.id')
                        ->get();
            $sched_array = array();

            foreach($temp_sched as $item){
            array_push($sched_array,$item->id);
            }

            $student_sched = Db::table('college_loadsubject')
                        ->where('college_loadsubject.deleted',0)
                        ->whereIn('schedid',$sched_array)
                        ->join('college_enrolledstud',function($join)  use($syid,$semid,$gradelevel){
                              $join->on('college_loadsubject.studid','=','college_enrolledstud.studid');
                              $join->where('college_enrolledstud.deleted',0);
                              $join->where('college_enrolledstud.syid',$syid);
                              $join->where('college_enrolledstud.semid',$semid);
                              $join->whereIn('studstatus',[1,2,3]);
                              $join->when($gradelevel, function ($query) use ($gradelevel) {
                                    $query->where('college_enrolledstud.yearLevel', $gradelevel);
                              });
                        })
                        ->join('college_classsched',function($join) use($syid,$semid){
                              $join->on('college_loadsubject.schedid','=','college_classsched.id');
                              $join->where('college_classsched.deleted',0);
                              $join->where('college_classsched.syID',$syid);
                              $join->where('college_classsched.semesterID',$semid);
                              
                        })
                        ->join('college_prospectus',function($join){
                              $join->on('college_classsched.subjectID','=','college_prospectus.id');
                              $join->where('college_prospectus.deleted',0);
                        })
                        ->select(
                              'schedid',
                              'college_loadsubject.studid',
                              'college_classsched.subjectID as subjid',
                        )
                        ->distinct('studid')
                        ->get();

            return  $student_sched;

      }

      public static function student_grades(Request $request){
            
            $teacherid = DB::table('teacher')
                              ->where('tid',auth()->user()->email)
                              ->select('id')
                              ->first()
                              ->id;

            $courseid = $request->get('courseid');
            $gradelevel = $request->get('gradelevel');

            // if(Session::get('currentPortal') == 14){
            //       $cp_course = DB::table('college_colleges')
            //                         ->join('college_courses',function($join) use($courseid){
            //                               $join->on('college_colleges.id','=','college_courses.collegeid');
            //                               $join->where('college_courses.deleted',0);
            //                               $join->where('college_courses.id',$courseid);
            //                         })
            //                         ->where('college_colleges.deleted',0)
            //                         ->where('college_colleges.dean',$teacherid)
            //                         ->select(
            //                               'college_courses.id',
            //                               'college_courses.courseDesc',
            //                               'college_courses.courseabrv'
            //                               )
            //                         ->get();
            // }else{
                  $cp_course = DB::table('college_courses')
                                    // ->where('courseChairman',$teacherid)
                                    ->where('id',$courseid)
                                    ->select('id','courseDesc','courseabrv')
                                    ->get();
            // }

            // $cp_course = DB::table('college_courses')
            //                   ->where('courseChairman',$teacherid)
            //                   ->where('id',$courseid)
            //                   ->select('id','courseDesc','courseabrv')
            //                   ->get();

            $syid = $request->get('syid');
            $semid = $request->get('semid');

            $array_course = array();

            foreach($cp_course as $item){
                  array_push($array_course,$item->id);
            }

            $student_grade = Db::table('college_stud_term_grades')
                                    ->join('studinfo',function($join){
                                          $join->on('studinfo.id','=','college_stud_term_grades.studid');
                                          $join->where('studinfo.deleted',0);
                                    })
                                    ->join('college_loadsubject',function($join) use($syid,$semid){
                                          $join->on('college_loadsubject.studid','=','studinfo.id');
                                          $join->where('college_loadsubject.deleted',0);
                                          $join->where('college_loadsubject.syid',$syid);
                                          $join->where('college_loadsubject.semid',$semid);
                                    })
                                    ->join('college_sections',function($join) use($syid,$semid,$courseid,$gradelevel){
                                          $join->on('college_loadsubject.sectionID','=','college_sections.id');
                                          $join->when($courseid != '', function ($query) use ($courseid) {
                                                $query->where('college_sections.courseID', '=', $courseid);
                                          });
                                          $join->when($gradelevel != '', function ($query) use ($gradelevel) {
                                                $query->where('college_sections.yearID', '=', $gradelevel);
                                          });
                                          $join->where('college_sections.deleted',0);
                                          $join->where('college_sections.syID',$syid);
                                          $join->where('college_sections.semesterID',$semid);
                                    })
                                    ->join('college_prospectus',function($join) use($syid,$semid){
                                          $join->on('college_stud_term_grades.prospectusID','=','college_prospectus.id');
                                          $join->where('college_prospectus.deleted',0);
                                    })
                                    ->where('college_stud_term_grades.deleted',0)
                                    ->where('college_stud_term_grades.syid',$syid)
                                    ->where('college_stud_term_grades.semid',$semid)
                                    ->groupBy('college_stud_term_grades.studid', 'college_stud_term_grades.prospectusID')
                                    ->select(
                                          'college_prospectus.id as subjid',
                                          'college_sections.id as sectionID',
                                          'college_stud_term_grades.id',
                                          'college_loadsubject.studid',
                                          'college_stud_term_grades.prelim_grade',
                                          'college_stud_term_grades.midterm_grade',
                                          'college_stud_term_grades.prefinal_grade',
                                          'college_stud_term_grades.final_grade',
                                          'college_stud_term_grades.prelim_status',
                                          'college_stud_term_grades.midterm_status',
                                          'college_stud_term_grades.prefinal_status',
                                          'college_stud_term_grades.final_status'
                                    )
                                    ->get();
            return $student_grade;

      }



      public static function section_ajax(Request $request){

            $sectionid = $request->get('sectionid');
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $levelid = $request->get('levelid');
            $course = $request->get('course');

            return self::section($sectionid, $syid, $semid, $levelid, $course);
            
      }

      public static function section(
            $sectionid = null,
            $syid = null,
            $semid = null,
            $levelid = null,
            $course = null
      ){

            $temp_courses = null;

            if(auth()->user()->type == 16){
            //chairperson

                  $teacher = DB::table('teacher')
                                    ->where('userid',auth()->user()->id)
                                    ->first();

                  $courses = DB::table('college_courses')
                                    ->join('college_colleges',function($join){
                                          $join->on('college_courses.collegeid','=','college_colleges.id');
                                          $join->where('college_courses.deleted',0);
                                    })
                                    ->where('courseChairman',$teacher->id)
                                    ->where('college_courses.deleted',0)
                                    ->select('college_courses.id','courseDesc','collegeDesc')
                                    ->get();

                  $temp_courses = array();
                  
                  foreach($courses as $item){
                        array_push( $temp_courses, $item->id);
                  }

                  if(count($temp_courses) == 0){
                        return array((object)[
                              'status'=>0,
                              'data'=>'No section found.',
                              'info'=>array()
                        ]);
                  }

            }else if(auth()->user()->type == 14){
            //dean

                  $teacher = DB::table('teacher')
                                    ->where('userid',auth()->user()->id)
                                    ->first();

                  $courses = DB::table('college_colleges')
                                    ->join('college_courses',function($join){
                                          $join->on('college_colleges.id','=','college_courses.collegeid');
                                          $join->where('college_courses.deleted',0);
                                    })
                                    ->where('dean',$teacher->id)
                                    ->where('college_colleges.deleted',0)
                                    ->select('college_courses.*')
                                    ->get();

                  $temp_courses = array();
                  
                  foreach($courses as $item){
                        array_push( $temp_courses, $item->id);
                  }

                  if(count($temp_courses) == 0){
                        return array((object)[
                              'status'=>0,
                              'data'=>'No section found.',
                              'info'=>array()
                        ]);
                  }

            }

            $sections = DB::table('college_sections')
                              ->leftJoin('college_courses',function($join){
                                    $join->on('college_sections.courseID','=','college_courses.id');
                                    $join->where('college_courses.deleted',0);
                              })
                              ->leftJoin('gradelevel',function($join){
                                    $join->on('college_sections.yearID','=','gradelevel.id');
                                    $join->where('gradelevel.deleted',0);
                              })
                              ->where('college_sections.deleted',0);

            if($sectionid != null){
                  $sections = $sections->where('id',$sectionid);
            }

            if($syid != null){
                  $sections = $sections->where('syID',$syid);
            }

            if($semid != null){
                  $sections = $sections->where('semesterID',$semid);
            }

            if($levelid != null){

                  $sections = $sections->where('yearID',$levelid);
            }

            if($course != null){
                  $sections = $sections->where('courseID',$course);
            }

            if($temp_courses != null){
                  $sections = $sections->whereIn('courseID',$temp_courses);
            }

            $sections = $sections
                        ->select(
                              'college_sections.id',
                              'sectionDesc',
                              'college_courses.courseDesc',
                              'college_courses.courseabrv',
                              'levelname'
                        )
                        ->get();

            foreach($sections as $item){


                  $subjects = DB::table('college_classsched')
                                    ->join('college_prospectus',function($join){
                                          $join->on('college_classsched.subjectID','=','college_prospectus.id');
                                          $join->where('college_prospectus.deleted',0);
                                    })
                                    ->leftJoin('teacher',function($join){
                                          $join->on('college_classsched.teacherid','=','teacher.id');
                                          $join->where('teacher.deleted',0);
                                    })
                                    ->where('college_classsched.deleted',0)
                                    ->where('sectionID',$item->id)
                                    ->select(
                                          'college_classsched.id',
                                          'lastname',
                                          'firstname',
                                          'middlename',
                                          'suffix',
                                          'subjDesc',
                                          'subjCode',
                                          'lecunits',
                                          'labunits'
                                    )
                                    ->get();


                  $item->subjects  = $subjects;

            }
      

            return $sections;

      }

      public function grade_status_subject(Request $request){
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $teacher = $request->get('teacher');
            $course = $request->get('courseid');
            $usertype = auth()->user()->type;
            $gradelevel = $request->get('gradelevel');
            // $cp_course = DB::table('college_courses')
            //       ->where('id',$course)
            //       ->select('id','courseDesc','courseabrv')
            //       ->get();

            // $array_course = array();
            // foreach($cp_course as $item){
            //       array_push($array_course,$item->id);
            // }
            $grade_status = DB::table('college_prospectus')
                              ->join('college_classsched',function($join) use($syid, $semid){
                                    $join->on('college_prospectus.id','=','college_classsched.subjectID');
                                    $join->where('college_classsched.syID', $syid);
                                    $join->where('college_classsched.semesterID', $semid);
                                    $join->where('college_classsched.deleted', 0);
                              })
                              ->join('college_sections',function($join) use($syid, $semid, $course, $gradelevel){
                                    $join->on('college_sections.id','=','college_classsched.sectionID');
                                    $join->when($course, function ($query) use ($course) {
                                          $query->where('college_sections.courseID', $course);
                                         
                                    });
                                    $join->when($gradelevel, function ($query) use ($gradelevel) {
                                          $query->where('college_sections.yearID', $gradelevel);
                                         
                                    });
                                    $join->where('college_sections.syID', $syid);
                                    $join->where('college_sections.semesterID', $semid);
                                    $join->where('college_sections.deleted', 0);
                              })
                              ->join('college_instructor',function($join){
                                    $join->on('college_classsched.id','=','college_instructor.classschedid');
                                    $join->where('college_instructor.deleted', 0);
                              })
                              ->join('teacher',function($join){
                                    $join->on('college_instructor.teacherid','=','teacher.id');
                                    $join->where('teacher.deleted', 0);
                              })
                              ->groupBy('college_classsched.sectionID', 'college_classsched.subjectID')
                              ->select(
                                    DB::raw("CONCAT(teacher.firstname,' ',teacher.lastname) as teachername"),
                                    'college_classsched.id as schedid',
                                    'college_prospectus.subjDesc',
                                    'college_prospectus.subjCode',
                                    'college_sections.sectionDesc',
                                    'college_sections.id as sectionid',
                                    'college_prospectus.id as prospectusID',
                                    'college_sections.syID as syid',
                                    'college_sections.semesterID as semid',
                                    'teacher.id as teacherid',
                                    )
                              ->get();
                              
            
            $grade_status = $grade_status->map(function($item){
                  $student_grades = DB::table('college_stud_term_grades')
                              ->leftjoin('college_loadsubject',function($join) use($item){
                                    $join->on('college_stud_term_grades.studid', '=', 'college_loadsubject.studid');
                              })
                              ->leftJoin('users as prelim_user', 'college_stud_term_grades.prelim_updateby', '=', 'prelim_user.id')
                              ->leftJoin('users as midterm_user', 'college_stud_term_grades.midterm_updateby', '=', 'midterm_user.id')
                              ->leftJoin('users as prefinal_user', 'college_stud_term_grades.prefinal_updateby', '=', 'prefinal_user.id')
                              ->leftJoin('users as final_user', 'college_stud_term_grades.final_updateby', '=', 'final_user.id')
                              ->where('college_stud_term_grades.prospectusid',$item->prospectusID)
                              ->where('college_loadsubject.sectionid',$item->sectionid)
                              ->where('college_stud_term_grades.syid',$item->syid)
                              ->where('college_stud_term_grades.semid',$item->semid)
                              ->where('college_stud_term_grades.deleted',0)
                              // ->join('college_grading_scores',function($join) use($item){
                              //       $join->on('college_stud_term_grades.prospectusID','=','college_grading_scores.prospectusid');
                              //       $join->where('college_grading_scores.syid','=',$item->syid);
                              //       $join->where('college_grading_scores.semid','=',$item->semid);
                              // })
                              ->groupBy('college_stud_term_grades.id')
                              ->select(
                                    'college_stud_term_grades.id',
                                    'college_stud_term_grades.is_final_grading',
                                    'prelim_status',
                                    'midterm_status',
                                    'prefinal_status',
                                    'final_status',
                                    'college_stud_term_grades.schedid',
                                    DB::raw("DATE_FORMAT(college_stud_term_grades.prelim_updateddatetime, '%M/%d/%Y %h:%i %p') as prelim_updateddatetime"),
                                    DB::raw("DATE_FORMAT(college_stud_term_grades.midterm_updateddatetime, '%M/%d/%Y %h:%i %p') as midterm_updateddatetime"),
                                    DB::raw("DATE_FORMAT(college_stud_term_grades.prefinal_updateddatetime, '%M/%d/%Y %h:%i %p') as prefinal_updateddatetime"),
                                    DB::raw("DATE_FORMAT(college_stud_term_grades.final_updateddatetime, '%M/%d/%Y %h:%i %p') as final_updateddatetime"),
                                    'prelim_user.name as prelim_updateby',
                                    'midterm_user.name as midterm_updateby',
                                    'prefinal_user.name as prefinal_updateby',
                                    'final_user.name as final_updateby'
                              )
                              ->get();
                              $item->grades = $student_grades;
                              return $item;
            });

                        

            return $grade_status;
      }

      function show_grade_status_subject(Request $request){
            $sectionid = $request->get('sectionid');
            $prospectusID = $request->get('prospectusID');
            
            $schedule = DB::table('college_classsched')
                              ->join('college_sections', 'college_classsched.sectionID', '=', 'college_sections.id')
                              ->join('gradelevel', 'college_sections.yearID', '=', 'gradelevel.id')
                              ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
                              ->join('college_instructor', 'college_classsched.id', '=', 'college_instructor.classschedid')
                              ->join('teacher', 'college_instructor.teacherid', '=', 'teacher.id')
                              ->where('college_sections.id', $sectionid)
                              ->where('college_classsched.deleted', 0)
                              ->where('college_instructor.deleted', 0)
                              ->where('college_prospectus.id', $prospectusID)
                              ->select(
                                    'college_sections.sectionDesc',
                                    'college_prospectus.subjDesc',
                                    'college_prospectus.subjCode',
                                    DB::raw("CONCAT(teacher.firstname,' ',IFNULL(teacher.middlename, ''),' ',teacher.lastname) as teachername"),
                                    'gradelevel.levelname',
                                    'college_sections.sectionDesc',
                                    'teacher.id as teacherid',
                              )
                              ->first();

            return response()->json($schedule);
      }

      function show_grades_grade_status_subject(Request $request){
            $schedid = $request->get('schedid');
            $subjectid = $request->get('subjectid');
            $sectionid = $request->get('sectionid');
            $semid = $request->get('semid');
            $syid = $request->get('syid');
            $grade_info = DB::table('college_enrolledstud')
                  ->join('college_loadsubject', 'college_enrolledstud.studid', '=', 'college_loadsubject.studid')
                  ->join('college_classsched', 'college_loadsubject.schedid', '=', 'college_classsched.id')
                  ->join('studinfo', 'college_loadsubject.studid', '=', 'studinfo.id')
                  ->where('college_loadsubject.subjectID', $subjectid)
                  ->where('college_loadsubject.sectionID', $sectionid)
                  ->where('college_loadsubject.deleted', 0)
                  ->where('college_enrolledstud.studstatus', '<>', 0)
                  ->where('college_enrolledstud.syid', $syid)
                  ->where('college_enrolledstud.semid', $semid)
                  ->groupBy('college_enrolledstud.studid')
                  ->orderBy('studinfo.lastname', 'asc')
                  ->select(
                        'college_loadsubject.studid',
                        DB::raw("CONCAT(studinfo.lastname,', ',studinfo.firstname,' ',IFNULL(SUBSTRING(studinfo.middlename,1,1),''),'.') as studname"),
                        'studinfo.gender'
                        )
                  ->get();


            $ecr_template = DB::table('college_ecr')
                  ->join('college_subject_ecr', function($join) use($subjectid, $sectionid){
                        $join->on('college_ecr.id', '=', 'college_subject_ecr.ecrtemplate_id');
                        $join->where('college_subject_ecr.subject_id', $subjectid);
                        $join->where('college_subject_ecr.section_id', $sectionid);
                        $join->where('college_subject_ecr.deleted', 0);
                  })
                  ->leftJoin('college_component_gradesetup', function($join) {
                        $join->on('college_ecr.id', '=', 'college_component_gradesetup.ecrID')
                        ->where('college_component_gradesetup.deleted', 0);
                  })
                  ->select(
                        'college_ecr.id as ecrid',
                        'college_component_gradesetup.id as componentid',
                        'college_component_gradesetup.descriptionComp as componentname',
                        'college_component_gradesetup.component',
                        'college_component_gradesetup.column_ECR',
                  )
                  ->orderby('college_component_gradesetup.id', 'asc')
                  ->get();


            $component_ids = $ecr_template->pluck('componentid')->filter();

            $subgrading_components = DB::table('college_subgradingcomponent')
                  ->whereIn('componentID', $component_ids)
                  ->where('deleted', 0)
                  ->get()
                  ->groupBy('componentID');

            $final_data = [];

           

            foreach($ecr_template as $ecr){
                  $components = [
                        'componentid' => $ecr->componentid,
                        'componentname' => $ecr->componentname,
                        'component_percentage' => $ecr->component,
                        'component_column' => $ecr->column_ECR,
                        'subgrading' => []
                  ];

                  if(isset($subgrading_components[$ecr->componentid])){
                        foreach($subgrading_components[$ecr->componentid] as $subgrading){
                              $components['subgrading'][] = [
                                    'subcompid' => $subgrading->id,
                                    'subcompname' => $subgrading->subDescComponent,
                                    'subcomp_percentage' => $subgrading->subComponent,
                                    'subcomponent_column' => $subgrading->subColumnECR
                              ];
                        }
                  }

                  if(!isset($final_data[$ecr->ecrid])){
                        $final_data[$ecr->ecrid] = [
                              'ecrid' => $ecr->ecrid,
                              'components' => []
                        ];
                  }

                  $final_data[$ecr->ecrid]['components'][] = $components;
            }
            
            $final_data[$ecr->ecrid]['studinfo'] = $grade_info;
            

            $final_data = array_values($final_data);
            // return $final_data;
            return view ('superadmin.pages.college.ecrtable',[
                  'component' => $final_data[0]['components'],
                  'students' => $final_data[0]['studinfo']
            ]);
      }

      function grade_info(Request $request){
            $grade_info = DB::table('college_loadsubject')
                              ->join('college_classsched', 'college_loadsubject.schedid', '=', 'college_classsched.id')
                              ->join('studinfo', 'college_loadsubject.studid', '=', 'studinfo.id')
                              ->where('college_loadsubject.schedid', $schedid)
                              ->where('college_loadsubject.deleted', 0)
                              ->select(
                                    DB::raw("CONCAT(studinfo.lastname,', ',studinfo.firstname,' ',IFNULL(SUBSTRING(studinfo.middlename,1,1),''),'.') as studname"),
                                    'studinfo.gender'
                                    )
                              ->get();

            $grade_info = $grade_info->toArray();
      }

      public function view_system_grading($subjectid, $syid, $semid, $sectionid){
            // $students = DB::table('college_loadsubject')
            //             ->where('semid', $semid)
            //             ->where('syid', $syid)
            //             ->where('sectionid', $sectionid)
            //             ->where('subjectid', $subjectid)
            //             ->get();
            // $studid = $students->pluck('studid');

            $teacher = auth()->user()->id;

            $existecr = DB::table('college_subject_ecr')
                        ->where('section_id', $sectionid)
                        ->where('subject_id', $subjectid)
                        ->where('deleted', 0)
                        ->select('ecrtemplate_id')
                        ->first();

            $existfinal = DB::table('college_stud_term_grades')
                              // ->where('studid', $studid)
                              ->where('prospectusid', $subjectid)
                              ->where('syid', $syid)
                              ->where('semid', $semid)
                              ->where('createdby', $teacher)
                              ->where('is_final_grading', 1)
                              ->where('deleted', 0)
                              ->count();
            

            if(empty($existecr->ecrtemplate_id)){
                  return 'No Grading Template Selected';
            }

            if($existfinal > 0){
                  return 'Final Grading Already Exists';
            }

            return view('ctportal.pages.systemgrading', compact('subjectid', 'syid', 'semid', 'sectionid'));
      }
    
      public function display_scheddetail(Request $request){

            $subjectid = $request->get('subjectid');
            $sectionid = $request->get('sectionid');
            $syid = $request->get('syid');
            $semid = $request->get('semid');

            $sched = DB::table('college_classsched')
                        ->join('college_prospectus', 'college_classsched.subjectid', '=', 'college_prospectus.id')
                        ->join('college_scheddetail', 'college_classsched.id', '=', 'college_scheddetail.headerid')
                        ->join('college_sections', 'college_classsched.sectionID', '=', 'college_sections.id')
                        ->join('gradelevel', 'college_sections.yearID', '=', 'gradelevel.id')
                        ->join('college_instructor', 'college_classsched.id', '=', 'college_instructor.classschedid')
                        ->join('teacher', 'college_instructor.teacherid', '=', 'teacher.id')
                        ->join('college_subject_ecr', 'college_classsched.subjectID', '=', 'college_subject_ecr.subject_id')
                        ->where('college_classsched.deleted', 0)                    
                        ->where('college_subject_ecr.subject_id', $subjectid)
                        ->where('college_subject_ecr.section_id', $sectionid)
                        // ->where('college_subject_ecr.syid', $syid)
                        // ->where('college_subject_ecr.semid', $semid)
                        ->where('college_classsched.syid', $syid)
                        ->where('college_classsched.semesterid', $semid)
                        ->where('college_instructor.deleted', 0)
                        ->select(
                              'teacher.lastname',
                              'teacher.firstname',
                              'college_prospectus.subjDesc',
                              'college_prospectus.subjCode',
                              'gradelevel.levelname',
                              'college_sections.sectionDesc',
                              'college_subject_ecr.ecrtemplate_id',
                              'college_classsched.id'
                              
                        )
                        ->first();

            return response()->json($sched);
      }

      public function display_ecr_template(Request $request){

            $schedid = $request->get('schedid');
            $subjectid = $request->get('subjectid');
            $sectionid = $request->get('sectionid');
            $semid = $request->get('semid');
            $syid = $request->get('syid');

            $grade_info = DB::table('college_enrolledstud')
                              ->join('college_loadsubject', 'college_enrolledstud.studid', '=', 'college_loadsubject.studid')
                              ->join('college_classsched', 'college_loadsubject.schedid', '=', 'college_classsched.id')
                              ->join('studinfo', 'college_loadsubject.studid', '=', 'studinfo.id')
                              ->where('college_loadsubject.subjectID', $subjectid)
                              ->where('college_loadsubject.sectionID', $sectionid)
                              ->where('college_loadsubject.deleted', 0)
                              ->where('college_enrolledstud.studstatus', '<>', 0)
                              ->where('college_enrolledstud.syid', $syid)
                              ->where('college_enrolledstud.semid', $semid)
                              ->groupBy('college_enrolledstud.studid')
                              ->orderBy('studinfo.lastname', 'asc')
                              ->select(
                                    'college_loadsubject.studid',
                                    DB::raw("CONCAT(studinfo.lastname,', ',studinfo.firstname,' ',IFNULL(SUBSTRING(studinfo.middlename,1,1),''),'.') as studname"),
                                    'studinfo.gender'
                                    )
                              ->get();


            $ecr_template = DB::table('college_ecr')
                        ->join('college_subject_ecr', function($join) use($subjectid, $sectionid){
                              $join->on('college_ecr.id', '=', 'college_subject_ecr.ecrtemplate_id');
                              $join->where('college_subject_ecr.subject_id', $subjectid);
                              $join->where('college_subject_ecr.section_id', $sectionid);
                              $join->where('college_subject_ecr.deleted', 0);
                        })
                        ->leftJoin('college_component_gradesetup', function($join) {
                              $join->on('college_ecr.id', '=', 'college_component_gradesetup.ecrID')
                              ->where('college_component_gradesetup.deleted', 0);
                        })
                        ->select(
                              'college_ecr.id as ecrid',
                              'college_component_gradesetup.id as componentid',
                              'college_component_gradesetup.descriptionComp as componentname',
                              'college_component_gradesetup.component',
                              'college_component_gradesetup.column_ECR',
                        )
                        ->orderby('college_component_gradesetup.id', 'asc')
                        ->get();

            $component_ids = $ecr_template->pluck('componentid')->filter();

            $subgrading_components = DB::table('college_subgradingcomponent')
                  ->whereIn('componentID', $component_ids)
                  ->where('deleted', 0)
                  ->get()
                  ->groupBy('componentID');

            $final_data = [];

            foreach($ecr_template as $ecr){

                  $components = [
                        'componentid' => $ecr->componentid,
                        'componentname' => $ecr->componentname,
                        'component_percentage' => $ecr->component,
                        'component_column' => $ecr->column_ECR,
                        'subgrading' => []
                  ];

                  if(isset($subgrading_components[$ecr->componentid])){
                        foreach($subgrading_components[$ecr->componentid] as $subgrading){
                              $components['subgrading'][] = [
                                    'subcompid' => $subgrading->id,
                                    'subcompname' => $subgrading->subDescComponent,
                                    'subcomp_percentage' => $subgrading->subComponent,
                                    'subcomponent_column' => $subgrading->subColumnECR
                              ];
                        }
                  }

                  if(!isset($final_data[$ecr->ecrid])){
                        $final_data[$ecr->ecrid] = [
                              'ecrid' => $ecr->ecrid,
                              'components' => []
                        ];
                  }

                  $final_data[$ecr->ecrid]['components'][] = $components;
            }
            
            $final_data[$ecr->ecrid]['studinfo'] = $grade_info;
            

            $final_data = array_values($final_data);
            // return $final_data;
            return view ('ctportal.pages.ecrtable',[
                  'component' => $final_data[0]['components'],
                  'students' => $final_data[0]['studinfo']
            ]);
      }

      public function save_system_grades(Request $request){
            $highest_scores = json_decode($request->highest_scores, true);
            $grades = json_decode($request->scores, true);
            $term_averages = json_decode($request->term_averages, true);


            $equivalence = DB::table("college_grade_point_scale")
                                    ->join('college_grade_point_equivalence', 'college_grade_point_scale.grade_point_equivalency', '=', 'college_grade_point_equivalence.id')
                                    ->where('college_grade_point_equivalence.isactive', 1)
                                    ->select(
                                          'college_grade_point_scale.grade_point',
                                          'college_grade_point_scale.is_failed',
                                          'college_grade_point_scale.letter_equivalence',
                                          'college_grade_point_scale.percent_equivalence',
                                          'college_grade_point_scale.grade_remarks'
                                          )
                                    ->get();
            // return $equivalence;
            $existing = DB::table('college_grade_point_scale')
                        ->join('college_grade_point_equivalence', 'college_grade_point_scale.grade_point_equivalency', '=', 'college_grade_point_equivalence.id')
                        ->where('college_grade_point_equivalence.isactive', 1)
                        ->get();
            if(count($existing) == 0){
                  return 'No Grade Point Scale Setup';
            }                              

            foreach($highest_scores as $scores){

                  $exist = DB::table('college_highest_score')
                        ->where('prospectusid', $scores['subjectid'])
                        ->where('sectionid', $scores['sectionid'])
                        ->where('syid', $scores['syid'])
                        ->where('semid', $scores['semid'])
                        ->where('component_id', $scores['component_id'])
                        ->where('subcomponent_id', $scores['subid'])
                        ->where('term', $scores['term'])
                        ->where('column_number', $scores['sort'])
                        ->first();

                  if($exist){
                        DB::table('college_highest_score')
                              ->where('prospectusid', $scores['subjectid'])
                              ->where('sectionid', $scores['sectionid'])
                              ->where('syid', $scores['syid'])
                              ->where('semid', $scores['semid'])
                              ->where('component_id', $scores['component_id'])
                              ->where('subcomponent_id', $scores['subid'])
                              ->where('term', $scores['term'])
                              ->where('column_number', $scores['sort'])
                              ->update([
                                    'score' => $scores['highest_score'],
                                    'date' => $scores['date'],
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }else{
                        DB::table('college_highest_score')
                              ->insert([
                                    'prospectusid'=> $scores['subjectid'],
                                    'sectionid'=> $scores['sectionid'],
                                    'syid' => $scores['syid'],
                                    'semid' => $scores['semid'],
                                    'component_id'=> $scores['component_id'],
                                    'subcomponent_id' => $scores['subid'],
                                    'score' => $scores['highest_score'],
                                    'term' => $scores['term'],
                                    'date' => $scores['date'],
                                    'column_number' => $scores['sort'],
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }
   
            }

            foreach($grades as $grade){

                  $exist = DB::table('college_grading_scores')
                        ->where('prospectusid', $grade['subjectid'])
                        ->where('sectionid', $grade['sectionid'])
                        ->where('syid', $grade['syid'])
                        ->where('semid', $grade['semid'])
                        ->where('studid', $grade['studid'])
                        ->where('componentid', $grade['component_id'])
                        ->where('subcomponent_id', $grade['subid'])
                        ->where('term', $grade['term'])
                        ->where('column_number', $grade['sort'])
                        ->first();
                  
                  if($exist){
                        DB::table('college_grading_scores')
                              ->where('prospectusid', $grade['subjectid'])
                              ->where('sectionid', $grade['sectionid'])
                              ->where('syid', $grade['syid'])
                              ->where('semid', $grade['semid'])
                              ->where('studid', $grade['studid'])
                              ->where('componentid', $grade['component_id'])
                              ->where('subcomponent_id', $grade['subid'])
                              ->where('term', $grade['term'])
                              ->where('column_number', $grade['sort'])
                              ->update([
                                    'score' => $grade['score'],
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }else{
                        DB::table('college_grading_scores')
                              ->insert([
                                    'prospectusid'=> $grade['subjectid'],
                                    'sectionid'=> $grade['sectionid'],
                                    'syid' => $grade['syid'],
                                    'semid' => $grade['semid'],
                                    'studid'=> $grade['studid'],
                                    'componentid'=> $grade['component_id'],
                                    'subcomponent_id' => $grade['subid'],
                                    'score' => $grade['score'],
                                    'term' => $grade['term'],
                                    'column_number' => $grade['sort'],
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);
                  } 
                  
            }   
            // return 12;

            foreach($term_averages as $average){
                  $ave_equivalence = null;
                  foreach ($equivalence as $eq) {
                        if($eq->is_failed == 1 && $average['term_average'] >= $eq->grade_point){
                            $ave_equivalence = $eq->grade_point;
                        }else{
                              $percentRange = array_map('trim', explode('-', str_replace('%', '', $eq->percent_equivalence)));
                              $minPercent = isset($percentRange[0]) ? floatval($percentRange[0]) : null;
                              $maxPercent = isset($percentRange[1]) ? floatval($percentRange[1]) : null;
                              
                              if ($average['term_average'] !== 'INC' && $average['term_average'] !== null) {
                                  $termAverage = round(floatval($average['term_average'])); // Round off to whole number
                                  if (!is_null($minPercent) && !is_null($maxPercent)) {
                                      if ($termAverage >= $minPercent && $termAverage <= $maxPercent) {
                                          $ave_equivalence = $eq->grade_point;
                                          break; 
                                      }
                                  }
                              }else if($average['term_average'] === 'INC'){
                                  $ave_equivalence = 'INC';
                              }        
                        }
                    }
                  $exist = DB::table('college_stud_term_grades')
                  ->where('prospectusID', $average['subjectid'])
                  ->where('studid', $average['studid'])
                  ->where('syid', $average['syid'])
                  ->where('semid', $average['semid'])
                  ->first();

            
                  if($exist){
                        if($average['term'] == 1){
                              DB::table('college_stud_term_grades')
                                    ->where('prospectusID', $average['subjectid'])
                                    ->where('studid', $average['studid'])
                                    ->where('syid', $average['syid'])
                                    ->where('semid', $average['semid'])
                                    ->update([
                                          'prelim_grade' => $average['term_average'],
                                          'prelim_transmuted' => $ave_equivalence,
                                          'updatedby' => auth()->user()->id,
                                          'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                        if($average['term'] == 2){
                              DB::table('college_stud_term_grades')
                                    ->where('prospectusID', $average['subjectid'])
                                    ->where('studid', $average['studid'])
                                    ->where('syid', $average['syid'])
                                    ->where('studid', $average['studid'])
                                    ->update([
                                          'midterm_grade' => $average['term_average'],
                                          'midterm_transmuted' => $ave_equivalence,
                                          'updatedby' => auth()->user()->id,
                                          'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                        if($average['term'] == 3){
                              DB::table('college_stud_term_grades')
                                    ->where('prospectusID', $average['subjectid'])
                                    ->where('studid', $average['studid'])
                                    ->where('syid', $average['syid'])
                                    ->where('studid', $average['studid'])
                                    ->update([
                                          'prefinal_grade' => $average['term_average'],
                                          'prefinal_transmuted' => $ave_equivalence,
                                          'updatedby' => auth()->user()->id,
                                          'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                        if($average['term'] == 4){

                              DB::table('college_stud_term_grades')
                                    ->where('prospectusID', $average['subjectid'])
                                    ->where('studid', $average['studid'])
                                    ->where('syid', $average['syid'])
                                    ->where('studid', $average['studid'])
                                    ->update([
                                          'final_grade' => $average['term_average'],
                                          'final_transmuted' => $ave_equivalence,
                                          'updatedby' => auth()->user()->id,
                                          'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                              
                        }
                        
                  }else{
                        if($average['term'] == 1){
                              DB::table('college_stud_term_grades')
                                    ->insert([
                                          'prospectusID'=> $average['subjectid'],
                                          'syid'=> $average['syid'],
                                          'semid'=> $average['semid'],
                                          'studid'=> $average['studid'],
                                          'prelim_grade' => $average['term_average'],
                                          'prelim_transmuted' => $ave_equivalence,
                                          'prelim_status' => 0,
                                          'createdby' => auth()->user()->id,
                                          'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                        if($average['term'] == 2){
                              DB::table('college_stud_term_grades')
                                    ->insert([
                                          'prospectusID'=> $average['subjectid'],
                                          'syid'=> $average['syid'],
                                          'semid'=> $average['semid'],
                                          'studid'=> $average['studid'],
                                          'midterm_grade' => $average['term_average'],
                                          'midterm_transmuted' => $ave_equivalence,
                                          'midterm_status' => 0,
                                          'createdby' => auth()->user()->id,
                                          'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                        if($average['term'] == 3){
                              DB::table('college_stud_term_grades')
                                    ->insert([
                                          'prospectusID'=> $average['subjectid'],
                                          'syid'=> $average['syid'],
                                          'semid'=> $average['semid'],
                                          'studid'=> $average['studid'],
                                          'prefinal_grade' => $average['term_average'],
                                          'prefinal_transmuted' => $ave_equivalence,
                                          'prefinal_status' => 0,
                                          'createdby' => auth()->user()->id,
                                          'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                        if($average['term'] == 4){
                              DB::table('college_stud_term_grades')
                                    ->insert([
                                          'prospectusID'=> $average['subjectid'],
                                          'syid'=> $average['syid'],
                                          'semid'=> $average['semid'],
                                          'studid'=> $average['studid'],
                                          'final_grade' => $average['term_average'],
                                          'final_transmuted' => $ave_equivalence,
                                          'final_status' => 0,
                                          'createdby' => auth()->user()->id,
                                          'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                  }
            }
      }

      public function display_term_grades(Request $request){
            $term = request()->get('term');
            $subjectid = request()->get('subjectid');
            $sectionid = request()->get('sectionid');
            $status = request()->get('status');

            $highest_scores = DB::table('college_highest_score')
                                    ->where('prospectusid', $subjectid)
                                    ->where('sectionid', $sectionid)
                                    ->where('term', $term)
                                    ->select('component_id', 'subcomponent_id', 'score', 'column_number','date')
                                    ->get();

            $grade_scores = DB::table('college_grading_scores')
                                    ->where('prospectusid', $subjectid)
                                    ->where('sectionid', $sectionid)
                                    ->where('term', $term)
                                    // ->when($status, function ($query, $status) {
                                    //       return $query->where('status_flag', $status);
                                    // })
                                    ->select('componentid', 'subcomponent_id', 'score', 'column_number','studid')
                                    ->get();
            
            $grade_status = DB::table('college_grading_scores')
                                    ->where('prospectusid', $subjectid)
                                    ->where('sectionid', $sectionid)
                                    ->where('term', $term)
                                    ->select('studid', 'status_flag')
                                    ->groupBy('studid')
                                    ->get();
                                    
            
            
            return [
                  'highest_scores' => $highest_scores,
                  'grade_scores' => $grade_scores,
                  'grade_status' => $grade_status
            ];

      }

      public function submit_grades(Request $request){
            $grades =   json_decode(request()->get('grades'),true);
            $students = json_decode(request()->get('students'),true);

            $data = DB::table("college_grade_point_scale")
                        ->join('college_grade_point_equivalence', 'college_grade_point_scale.grade_point_equivalency', '=', 'college_grade_point_equivalence.id')
                        ->where('college_grade_point_equivalence.isactive', 1)
                        ->select(
                              'college_grade_point_scale.grade_point',
                              'college_grade_point_scale.letter_equivalence',
                              'college_grade_point_scale.percent_equivalence',
                              'college_grade_point_scale.grade_remarks',
                              'college_grade_point_scale.is_failed'
                              )
                        ->get();
            // return $data;

            $terms = DB::table('college_ecr_term')
                  ->join('college_termgrading', 'college_ecr_term.termID', '=', 'college_termgrading.id')
                  ->join('college_ecr', 'college_ecr_term.ecrID', '=', 'college_ecr.id')
                  ->join('college_subject_ecr', 'college_ecr.id', '=', 'college_subject_ecr.ecrtemplate_id')
                  ->where('college_subject_ecr.syid', $grades[0]['syid'])
                  ->where('college_subject_ecr.semid', $grades[0]['semid'])
                  ->where('college_subject_ecr.section_id', $grades[0]['sectionid'])
                  ->where('college_ecr_term.deleted', 0)
                  ->select('college_termgrading.description', 'college_termgrading.quarter', 'college_ecr.id', 'college_termgrading.grading_perc')
                  ->get();

            // return $terms;
            // return $students;
            foreach($grades as $grade){
                  DB::table('college_grading_scores')
                        ->where('syid', $grade['syid'])
                        ->where('semid', $grade['semid'])
                        ->where('prospectusid', $grade['subjectid'])
                        ->where('studid', $grade['studid'])
                        ->where('componentid', $grade['component_id'])
                        ->where('subcomponent_id', $grade['subid'])
                        ->where('term', $grade['term'])
                        ->where('column_number', $grade['sort'])
                        ->update([
                              'status_flag' => 1,
                              'updatedby' => auth()->user()->id,
                              'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);
            
            };
            foreach($students as $student){
                  
                  if($student['term'] == 1){
                        if($student['term_average'] == 'INC'){
                              DB::table('college_stud_term_grades')
                                    ->where('studid', $student['studid'])
                                    ->where('prospectusID', $student['subjectid'])
                                    ->where('syid', $student['syid'])
                                    ->where('semid', $student['semid'])
                                    ->update([
                                          'prelim_status' => 7,
                                          'prelim_updateby' => auth()->user()->id,
                                          'prelim_updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                          'updatedby' => auth()->user()->id,
                                          'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);   
                        }else{
                              DB::table('college_stud_term_grades')
                                    ->where('studid', $student['studid'])
                                    ->where('prospectusID', $student['subjectid'])
                                    ->where('syid', $student['syid'])
                                    ->where('semid', $student['semid'])
                                    ->update([
                                          'prelim_status' => 1,
                                          'prelim_updateby' => auth()->user()->id,
                                          'prelim_updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                          'updatedby' => auth()->user()->id,
                                          'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);   
                        }
                  }else if($student['term'] == 2){
                        if($student['term_average'] == 'INC'){
                              DB::table('college_stud_term_grades')
                                    ->where('studid', $student['studid'])
                                    ->where('prospectusID', $student['subjectid'])
                                    ->where('syid', $student['syid'])
                                    ->where('semid', $student['semid'])
                                    ->update([
                                          'midterm_status' => 7,
                                          'midterm_updateby' => auth()->user()->id,
                                          'midterm_updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                          'updatedby' => auth()->user()->id,
                                          'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }else{
                              DB::table('college_stud_term_grades')
                                    ->where('studid', $student['studid'])
                                    ->where('prospectusID', $student['subjectid'])
                                    ->where('syid', $student['syid'])
                                    ->where('semid', $student['semid'])
                                    ->update([
                                          'midterm_status' => 1,
                                          'midterm_updateby' => auth()->user()->id,
                                          'midterm_updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                          'updatedby' => auth()->user()->id,
                                          'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                  }else if($student['term'] == 3){
                        if($student['term_average'] == 'INC'){
                              DB::table('college_stud_term_grades')
                                    ->where('studid', $student['studid'])
                                    ->where('prospectusID', $student['subjectid'])
                                    ->where('syid', $student['syid'])
                                    ->where('semid', $student['semid'])
                                    ->update([
                                          'prefinal_status' => 7,
                                          'prefinal_updateby' => auth()->user()->id,
                                          'prefinal_updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                          'updatedby' => auth()->user()->id,
                                          'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }else{
                              DB::table('college_stud_term_grades')
                                    ->where('studid', $student['studid'])
                                    ->where('prospectusID', $student['subjectid'])
                                    ->where('syid', $student['syid'])
                                    ->where('semid', $student['semid'])
                                    ->update([
                                          'prefinal_status' => 1,
                                          'prefinal_updateby' => auth()->user()->id,
                                          'prefinal_updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                          'updatedby' => auth()->user()->id,
                                          'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
                        
                  }else if($student['term'] == 4){
                        if($student['term_average'] == 'INC'){
                              DB::table('college_stud_term_grades')
                                    ->where('studid', $student['studid'])
                                    ->where('prospectusID', $student['subjectid'])
                                    ->where('syid', $student['syid'])
                                    ->where('semid', $student['semid'])
                                    ->update([
                                          'final_status' => 7,
                                          'final_updateby' => auth()->user()->id,
                                          'final_updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                          'updatedby' => auth()->user()->id,
                                          'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);  
                        }else{
                              DB::table('college_stud_term_grades')
                                    ->where('studid', $student['studid'])
                                    ->where('prospectusID', $student['subjectid'])
                                    ->where('syid', $student['syid'])
                                    ->where('semid', $student['semid'])
                                    ->update([
                                          'final_status' => 1,
                                          'final_updateby' => auth()->user()->id,
                                          'final_updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                          'updatedby' => auth()->user()->id,
                                          'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);     
                        }

                        $final_average = DB::table('college_stud_term_grades')
                                          ->where('prospectusID', $student['subjectid'])
                                          ->where('syid', $student['syid'])
                                          ->where('semid', $student['semid'])
                                          ->where('studid', $student['studid'])
                                          ->first();

                  
                        
                        $selected_quarters = $terms->pluck('quarter')->toArray(); 
                        // Initialize an empty grades array
                        $grades = [];
                        // return $selected_quarters;
                        
                        // Dynamically select grades based on the extracted quarters
                        
                        if (in_array(1, $selected_quarters)) {
                              $grades[] = $final_average->prelim_grade;
                              $grades[] = $terms->where('quarter', 1)->pluck('grading_perc')->first();
                        }
                        if (in_array(2, $selected_quarters)) {
                              $grades[] = $final_average->midterm_grade;
                              $grades[] = $terms->where('quarter', 2)->pluck('grading_perc')->first();
                        }
                        if (in_array(3, $selected_quarters)) {
                              $grades[] = $final_average->prefinal_grade;
                              $grades[] = $terms->where('quarter', 3)->pluck('grading_perc')->first();
                        }
                        if (in_array(4, $selected_quarters)) {
                              $grades[] = $final_average->final_grade;
                              $grades[] = $terms->where('quarter', 4)->pluck('grading_perc')->first();
                        }
                        
                        $total_weighted_score = 0;
                        $total_percentage = 0;
                        for ($i = 0; $i < count($grades); $i += 2) {
                              $grade = $grades[$i];
                              $percent = $grades[$i + 1];
                        
                              if ($grade !== null && $percent !== null) {
                              $total_weighted_score += $grade * ($percent / 100);
                              $total_percentage += $percent;
                              }
                        }
                        // Check if any grade is 'INC'
                        if (!in_array('INC', $grades, true) && count($grades) > 0) {
                              // Compute the average of only the selected terms
                              $average = $total_percentage > 0 ? number_format($total_weighted_score, 2, '.', ',') : '0.00';
                              // Determine grade equivalence
                              foreach ($data as $eq) {
                                    if($eq->is_failed == 1){
                                          $ave_equivalence = $eq->grade_point;
                                          $ave_remarks = $eq->grade_remarks;
                                    }else{
                                          $percentRange = array_map('trim', explode('-', str_replace('%', '', $eq->percent_equivalence)));
                                          $minPercent = isset($percentRange[0]) ? floatval($percentRange[0]) : null;
                                          $maxPercent = isset($percentRange[1]) ? floatval($percentRange[1]) : null;
                                          $termAverage = round(floatval($average)); // Round to whole number
                              
                                          if (!is_null($minPercent) && !is_null($maxPercent) && $termAverage >= $minPercent && $termAverage <= $maxPercent) {
                                          $ave_equivalence = $eq->grade_point;
                                          $ave_remarks = $eq->grade_remarks;
                                          break;
                                          }
                                    }
                                    
                              }
                              
                              // Update the database with the calculated values
                              DB::table('college_stud_term_grades')
                                    ->where('prospectusID', $student['subjectid'])
                                    ->where('syid', $student['syid'])
                                    ->where('semid', $student['semid'])
                                    ->where('studid', $student['studid'])
                                    ->update([
                                    'final_grade_average' => $average,
                                    'final_grade_transmuted' => $ave_equivalence,
                                    'final_remarks' => $ave_remarks,
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        
                        } else {
                              // If any grade is INC, update all values to 'INC'
                              DB::table('college_stud_term_grades')
                                    ->where('prospectusID', $student['subjectid'])
                                    ->where('syid', $student['syid'])
                                    ->where('semid', $student['semid'])
                                    ->where('studid', $student['studid'])
                                    ->update([
                                    'final_grade_average' => 'INC',
                                    'final_grade_transmuted' => 'INC',
                                    'final_remarks' => 'INC',
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);
                        }
      
                  }
            };


      }

      public function update_grade_status(){

            $grades = json_decode(request()->get('grades'), true);
            $students = json_decode(request()->get('students'), true);

            $data = DB::table('college_grade_point_scale')
            ->join('college_grade_point_equivalence', 'college_grade_point_scale.grade_point_equivalency', '=', 'college_grade_point_equivalence.id')
            ->where('college_grade_point_equivalence.isactive', 1)
            ->select(
                  'college_grade_point_scale.grade_point',
                  'college_grade_point_scale.letter_equivalence',
                  'college_grade_point_scale.percent_equivalence',
                  )
            ->get();
            foreach($grades as $grade){
                  
                  if($grade['term'] == 'Prelim'){
                        $grade['term'] = 1;
                  }else if($grade['term'] == 'Midterm'){
                        $grade['term'] = 2;
                  }else if($grade['term'] == 'Pre-Final'){
                        $grade['term'] = 3;
                  }else if($grade['term'] == 'Final'){
                        $grade['term'] = 4;
                  }     
                  DB::table('college_grading_scores')
                        ->where('syid', $grade['syid'])
                        ->where('semid', $grade['semid'])
                        ->where('prospectusid', $grade['subjectid'])
                        ->where('studid', $grade['studid'])
                        ->where('componentid', $grade['component_id'])
                        ->where('subcomponent_id', $grade['subid'])
                        ->where('term', $grade['term'])
                        ->where('column_number', $grade['sort'])
                        ->update([
                              'status_flag' => $grade['status_id'],
                              'updatedby' => auth()->user()->id,
                              'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);
            
            };

            foreach($students as $student){
                  if($student['term'] == 'Prelim'){
                        DB::table('college_stud_term_grades')
                              ->where('studid', $student['studid'])
                              ->where('syid', $student['syid'])
                              ->where('semid', $student['semid'])
                              ->where('prospectusID', $student['subjectid'])
                              ->update([
                                    'prelim_status' => $student['status_id'],
                                    'prelim_updateby' => auth()->user()->id,
                                    'prelim_updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }else if($student['term'] == 'Midterm'){
                        DB::table('college_stud_term_grades')
                              ->where('studid', $student['studid'])
                              ->where('syid', $student['syid'])
                              ->where('semid', $student['semid'])
                              ->where('prospectusID', $student['subjectid'])
                              ->update([
                                    'midterm_status' => $student['status_id'],
                                    'midterm_updateby' => auth()->user()->id,
                                    'midterm_updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }else if($student['term'] == 'Pre-Final'){
                        DB::table('college_stud_term_grades')
                              ->where('studid', $student['studid'])
                              ->where('syid', $student['syid'])
                              ->where('semid', $student['semid'])
                              ->where('prospectusID', $student['subjectid'])
                              ->update([
                                    'prefinal_status' => $student['status_id'],
                                    'prefinal_updateby' => auth()->user()->id,
                                    'prefinal_updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }else if($student['term'] == 'Final'){
                        DB::table('college_stud_term_grades')
                              ->where('studid', $student['studid'])
                              ->where('syid', $student['syid'])
                              ->where('semid', $student['semid'])
                              ->where('prospectusID', $student['subjectid'])
                              ->update([
                                    'final_status' => $student['status_id'],
                                    'final_updateby' => auth()->user()->id,
                                    'final_updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }
            };


      }

      public function get_active_equivalency(){
            $data = DB::table('college_grade_point_scale')
            ->join('college_grade_point_equivalence', 'college_grade_point_scale.grade_point_equivalency', '=', 'college_grade_point_equivalence.id')
            ->where('college_grade_point_equivalence.isactive', 1)
            ->select(
                  'college_grade_point_scale.grade_point',
                  'college_grade_point_scale.letter_equivalence',
                  'college_grade_point_scale.percent_equivalence',
                  'college_grade_point_scale.grade_remarks',
                  'college_grade_point_scale.is_failed'
                  )
            ->get();

            return $data;

      }

      public function display_submitted_grades(Request $request){
            $term = request()->get('term');
            $sectionid = request()->get('sectionid');
            $subjectid = request()->get('subjectid');
            $status = request()->get('status');
            $syid = request()->get('syid');
            $semid = request()->get('semid');

            $highest_scores = DB::table('college_highest_score')
                                    ->where('sectionid', $sectionid)
                                    ->where('syid', $syid)
                                    ->where('semid', $semid)
                                    ->where('prospectusid', $subjectid)
                                    ->where('term', $term)
                                    ->select('component_id', 'subcomponent_id', 'score', 'column_number','date')
                                    ->get();

            $grade_scores = DB::table('college_grading_scores')
                                    ->where('sectionid', $sectionid)
                                    ->where('syid', $syid)
                                    ->where('semid', $semid)
                                    ->where('prospectusid', $subjectid)
                                    ->where('term', $term)
                                    // ->when($status, function ($query, $status) {
                                    //       return $query->where('status_flag', $status);
                                    // })
                                    ->where('status_flag', '!=', 0)
                                    ->select('componentid', 'subcomponent_id', 'score', 'column_number','studid')
                                    ->get();
            
            $grade_status = DB::table('college_grading_scores')
                                    ->where('sectionid', $sectionid)
                                    ->where('syid', $syid)
                                    ->where('semid', $semid)
                                    ->where('prospectusid', $subjectid)
                                    ->where('term', $term)
                                    ->select('studid', 'status_flag')
                                    ->groupBy('studid')
                                    ->get();                       
            
            return [
                  'highest_scores' => $highest_scores,
                  'grade_scores' => (count($grade_scores) == 0) ? 0 : $grade_scores,
                  'grade_status' =>(count($grade_status) == 0) ? 0 : $grade_status
            ];

      }

      public function get_status_sections(Request $request){
            $term = request()->get('term');
            $status = request()->get('status');
            $syid = request()->get('syid');
            $semid = request()->get('semid');
            $gradelevel = request()->get('gradelevel');
            $courseid = request()->get('courseid');
            

            $sections = Db::table('college_stud_term_grades')
                        ->join('studinfo',function($join){
                              $join->on('studinfo.id','=','college_stud_term_grades.studid');
                              $join->where('studinfo.deleted',0);
                        })
                        ->join('college_loadsubject',function($join) use($syid,$semid){
                              $join->on('college_loadsubject.studid','=','studinfo.id');
                              // $join->where('college_loadsubject.deleted',0);
                        })
                        ->join('college_sections',function($join) use($syid,$semid,$courseid,$gradelevel){
                              $join->on('college_loadsubject.sectionID','=','college_sections.id');
                              $join->when($courseid != '', function ($query) use ($courseid) {
                                    $query->where('college_sections.courseID', '=', $courseid);
                              });
                              $join->when($gradelevel != '', function ($query) use ($gradelevel) {
                                    $query->where('college_sections.yearID', '=', $gradelevel);
                              });
                              $join->where('college_sections.deleted',0);
                              $join->where('college_sections.syID',$syid);
                              $join->where('college_sections.semesterID',$semid);
                        })
                        ->join('college_prospectus',function($join) use($syid,$semid){
                              $join->on('college_stud_term_grades.prospectusID','=','college_prospectus.id');
                              $join->where('college_prospectus.deleted',0);
                        })
                        ->join('college_classsched', function($join) {
                              $join->on('college_sections.id', '=', 'college_classsched.sectionID');
                              $join->on('college_stud_term_grades.prospectusID', '=', 'college_classsched.subjectID');
                        })
                        ->join('college_instructor', 'college_classsched.id', '=', 'college_instructor.classschedid')
                        ->join('teacher', 'college_instructor.teacherid', '=', 'teacher.id')
                        ->join('gradelevel', 'college_sections.yearID', '=', 'gradelevel.id')
                        ->where('college_stud_term_grades.deleted',0)
                        ->where('college_stud_term_grades.syid', $syid)
                        ->where('college_stud_term_grades.semid', $semid)
                        ->where('college_instructor.deleted',0)
                        ->when($term == 1, function ($query) use($status){
                              $query->where('college_stud_term_grades.prelim_status', '=', $status);
                        })
                        ->when($term == 2, function ($query) use($status){
                              $query->where('college_stud_term_grades.midterm_status', '=', $status);
                        })
                        ->when($term == 3, function ($query) use($status){
                              $query->where('college_stud_term_grades.prefinal_status', '=', $status);
                        })
                        ->when($term == 4, function ($query) use($status){
                              $query->where('college_stud_term_grades.final_status', '=', $status);
                        })
                        ->groupBy('college_classsched.subjectID', 'college_classsched.sectionID')
                        ->select(
                              'gradelevel.levelname',
                              'college_sections.sectionDesc',
                              'college_sections.id as sectionid',
                              DB::raw("CONCAT(teacher.firstname, ' ', IFNULL(teacher.middlename, ''), ' ', teacher.lastname) AS teachername"),
                              'teacher.tid',
                              'college_prospectus.subjDesc',
                              'college_prospectus.subjCode',
                              'college_prospectus.id as subjectid',
                              'college_classsched.id as schedid',
                              'college_stud_term_grades.is_final_grading',
                        )
                        ->get();
            if(count($sections) == 0 && $status == 0){
                  $sections = DB::table('college_sections')
                        ->join('gradelevel', 'college_sections.yearID', '=', 'gradelevel.id')
                        ->join('college_classsched', 'college_sections.id', '=', 'college_classsched.sectionID')
                        ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
                        ->leftJoin('college_stud_term_grades', function($join) use($syid,$semid){
                              $join->on('college_prospectus.id', '=', 'college_stud_term_grades.prospectusID');
                              $join->where('college_stud_term_grades.deleted',0);
                              $join->where('college_stud_term_grades.syid',$syid);
                              $join->where('college_stud_term_grades.semid',$semid);
                        })
                        ->join('college_instructor', 'college_classsched.id', '=', 'college_instructor.classschedid')
                        ->join('teacher', 'college_instructor.teacherid', '=', 'teacher.id')
                        ->where('college_sections.deleted',0)
                        ->where('college_sections.syID', $syid)
                        ->where('college_sections.semesterID', $semid)
                        ->where('college_instructor.deleted',0)
                        ->groupBy('college_classsched.subjectID')
                        ->select(
                              'gradelevel.levelname',
                              'college_sections.sectionDesc',
                              'college_sections.id as sectionid',
                              DB::raw("CONCAT(teacher.firstname, ' ', IFNULL(teacher.middlename, ''), ' ', teacher.lastname) AS teachername"),
                              'teacher.tid',
                              'college_prospectus.subjDesc',
                              'college_prospectus.subjCode',
                              'college_prospectus.id as subjectid',
                              'college_classsched.id as schedid',
                              'college_stud_term_grades.prelim_status',
                              'college_stud_term_grades.midterm_status',
                              'college_stud_term_grades.prefinal_status',
                              'college_stud_term_grades.final_status',
                              'college_stud_term_grades.is_final_grading',

                        )
                        ->get();
                        // return $sections;
                        if ($term == 1) {
                              $sections = $sections->filter(function ($section) {
                                  return is_null($section->prelim_status);
                              })->values();
                          }
                        if ($term == 2) {
                              $sections = $sections->filter(function ($section) {
                                    return is_null($section->midterm_status);
                              })->values();
                        }
                        if ($term == 3) {
                              $sections = $sections->filter(function ($section) {
                                    return is_null($section->prefinal_status);
                              })->values();
                        }
                        if ($term == 4) {
                              $sections = $sections->filter(function ($section) {
                                    return is_null($section->final_status);
                              })->values();
                        }

            }
           


            return $sections;
      }

      public function get_status_students(Request $request){
            $term = request()->get('term');
            $status = request()->get('status');
            $syid = request()->get('syid');
            $semid = request()->get('semid');
            $gradelevel = request()->get('gradelevel');
            $courseid = request()->get('courseid');

            $term = (int) $term;
            $status = (int) $status;

            

            $students = Db::table('college_stud_term_grades')
                        ->join('studinfo',function($join){
                              $join->on('studinfo.id','=','college_stud_term_grades.studid');
                              $join->where('studinfo.deleted',0);
                        })
                        ->join('college_loadsubject',function($join) use($syid,$semid){
                              $join->on('college_loadsubject.studid','=','studinfo.id');
                              $join->where('college_loadsubject.deleted',0);
                              $join->where('college_loadsubject.syid',$syid);
                              $join->where('college_loadsubject.semid',$semid);
                        })
                        ->join('college_sections',function($join) use($syid,$semid,$courseid,$gradelevel){
                              $join->on('college_loadsubject.sectionID','=','college_sections.id');
                              $join->when($courseid != '', function ($query) use ($courseid) {
                                    $query->where('college_sections.courseID', '=', $courseid);
                              });
                              $join->when($gradelevel != '', function ($query) use ($gradelevel) {
                                    $query->where('college_sections.yearID', '=', $gradelevel);
                              });
                              $join->where('college_sections.deleted',0);
                              $join->where('college_sections.syID',$syid);
                              $join->where('college_sections.semesterID',$semid);
                        })
                        ->join('college_prospectus',function($join) use($syid,$semid){
                              $join->on('college_stud_term_grades.prospectusID','=','college_prospectus.id');
                              $join->where('college_prospectus.deleted',0);
                        })
                        ->join('gradelevel', 'college_sections.yearID', '=', 'gradelevel.id')
                        ->where('college_stud_term_grades.deleted',0)
                        ->where('college_stud_term_grades.syid',$syid)
                        ->where('college_stud_term_grades.semid',$semid)
                        ->when($term == 1, function ($query) use($status){
                              $query->where('college_stud_term_grades.prelim_status', '=', $status);
                        })
                        ->when($term == 2, function ($query) use($status){
                              $query->where('college_stud_term_grades.midterm_status', '=', $status);
                        })
                        ->when($term == 3, function ($query) use($status){
                              $query->where('college_stud_term_grades.prefinal_status', '=', $status);
                        })
                        ->when($term == 4, function ($query) use($status){
                              $query->where('college_stud_term_grades.final_status', '=', $status);
                        })
                        ->groupBy('college_stud_term_grades.studid')
                        ->select(
                              'gradelevel.levelname',
                              DB::raw("CONCAT(studinfo.lastname, ', ', IFNULL(studinfo.firstname, ''), ' ', IFNULL(studinfo.middlename, '')) AS studname"),
                              'studinfo.id as studid',
                              'studinfo.sid',
                              'college_sections.sectionDesc',
                              'college_sections.id as sectionid',
                        )
                        ->get();
            
            if(count($students) == 0 && $status == 0){
                  $students = DB::table('college_prospectus')
                              ->join('college_classsched', 'college_prospectus.id', '=', 'college_classsched.subjectID') 
                              ->leftjoin('college_sections', 'college_classsched.sectionID', '=', 'college_sections.id') 
                              ->join('college_loadsubject', function ($join) {
                                    $join->on('college_classsched.id', '=', 'college_loadsubject.schedid');
                                    $join->where('college_loadsubject.deleted', 0);
                              })
                              ->join('college_enrolledstud', function ($join) {
                                    $join->on('college_loadsubject.studid', '=', 'college_enrolledstud.studid');
                                    $join->where('college_enrolledstud.deleted', 0);
                              })
                              ->join('studinfo', 'college_enrolledstud.studid', '=', 'studinfo.id')
                              ->join('college_stud_term_grades', function ($join) use ($syid, $semid) {
                                    $join->on('studinfo.id', '=', 'college_stud_term_grades.studid');
                                    $join->on('college_prospectus.id', '=', 'college_stud_term_grades.prospectusID');
                                    $join->where('college_stud_term_grades.deleted', 0);
                                    $join->where('college_stud_term_grades.syid', $syid);
                                    $join->where('college_stud_term_grades.semid', $semid);
                              }) // Changed from LEFT JOIN to INNER JOIN
                              ->leftJoin('college_courses', 'college_sections.courseid', '=', 'college_courses.id')
                              ->leftJoin('gradelevel', 'college_sections.yearID', '=', 'gradelevel.id')
                              ->leftJoin('college_instructor', 'college_classsched.id', '=', 'college_instructor.classschedid')
                              ->leftJoin('teacher', 'college_instructor.teacherid', '=', 'teacher.id')
                              ->where('gradelevel.deleted', 0)
                              ->where('college_sections.deleted', 0)
                              ->where('college_sections.syid', $syid)
                              ->where('college_sections.semesterid', $semid)
                              ->where('college_classsched.deleted', 0)
                              ->when($courseid != 0, function ($query) use ($courseid) {
                                    $query->where('college_sections.courseid', $courseid);
                              })
                              ->when($gradelevel != 0, function ($query) use ($gradelevel) {
                                    $query->where('college_sections.yearID', $gradelevel);
                              })
                              ->select(
                                    'gradelevel.levelname',
                                    DB::raw("CONCAT(studinfo.lastname, ', ', IFNULL(studinfo.firstname, ''), ' ', IFNULL(studinfo.middlename, '')) AS studname"),
                                    'studinfo.id as studid',
                                    'studinfo.sid',
                                    'college_sections.sectionDesc',
                                    'college_sections.id as sectionid',
                                    'college_stud_term_grades.prelim_status',
                                    'college_stud_term_grades.midterm_status',
                                    'college_stud_term_grades.prefinal_status',
                                    'college_stud_term_grades.final_status'
                              )
                              ->groupBy(
                                    'studinfo.id',
                                    'college_prospectus.id',
                                    'college_sections.id',
                                    'college_classsched.id'
                              )
                              ->get();


                        if ($term == 1) {
                              $students = $students->filter(function ($students) {
                                  return is_null($students->prelim_status);
                              })->values();
                          }
                        if ($term == 2) {
                              $students = $students->filter(function ($students) {
                                    return is_null($students->midterm_status);
                              })->values();
                        }
                        if ($term == 3) {
                              $students = $students->filter(function ($students) {
                                    return is_null($students->prefinal_status);
                              })->values();
                        }
                        if ($term == 4) {
                              $students = $students->filter(function ($students) {
                                    return is_null($students->final_status);
                              })->values();
                        }

                              
            }

            return $students;
      }

      public function get_status_students_grades(Request $request){
            $sectionid = request()->get('sectionid');
            $term = request()->get('term');
            $status = request()->get('status');
            $studid = request()->get('studid');
            $syid = request()->get('syid');
            $semid = request()->get('semid');

            $student_grades = DB::table('college_stud_term_grades')
                              // ->join('college_classsched', 'college_stud_term_grades.schedid', '=', 'college_classsched.id')
                              ->join('college_loadsubject', 'college_stud_term_grades.studid', '=', 'college_loadsubject.studid')
                              ->join('college_prospectus', 'college_stud_term_grades.prospectusID', '=', 'college_prospectus.id')
                              ->join('college_sections', function($join) use($sectionid){
                                    $join->on('college_loadsubject.sectionID', '=', 'college_sections.id');
                                    $join->where('college_sections.deleted', 0);
                                    $join->where('college_sections.id', $sectionid);
                              })
                              ->where('college_stud_term_grades.deleted', 0)
                              ->where('college_stud_term_grades.studid', $studid)
                              ->join('gradelevel', 'college_sections.yearID', '=', 'gradelevel.id')
                              ->where('college_stud_term_grades.studid', $studid)
                              ->where('college_stud_term_grades.syid', $syid)
                              ->where('college_stud_term_grades.semid', $semid)
                              ->when($term == 1, function ($query) use($status){
                                    $query->where('college_stud_term_grades.prelim_status', '=', $status);
                              })
                              ->when($term == 2, function ($query) use($status){
                                    $query->where('college_stud_term_grades.midterm_status', '=', $status);
                              })
                              ->when($term == 3, function ($query) use($status){
                                    $query->where('college_stud_term_grades.prefinal_status', '=', $status);
                              })
                              ->when($term == 4, function ($query) use($status){
                                    $query->where('college_stud_term_grades.final_status', '=', $status);
                              })
                              ->groupBy('college_stud_term_grades.prospectusid')
                              ->select(
                                    'college_prospectus.subjCode',
                                    'college_prospectus.subjDesc',
                                    'college_prospectus.id as subjectID',
                                    'college_sections.id as sectionid',
                                    'college_stud_term_grades.prelim_transmuted',
                                    'college_stud_term_grades.midterm_transmuted',
                                    'college_stud_term_grades.prefinal_transmuted',
                                    'college_stud_term_grades.final_transmuted',
                                    'college_sections.sectionDesc',
                                    'gradelevel.levelname',
                                    'college_stud_term_grades.studid',
                                    'college_stud_term_grades.schedid',
                              )
                              ->get();

            $equivalence = DB::table("college_grade_point_scale")
                                    ->join('college_grade_point_equivalence', 'college_grade_point_scale.grade_point_equivalency', '=', 'college_grade_point_equivalence.id')
                                    ->where('college_grade_point_equivalence.isactive', 1)
                                    ->select(
                                          'college_grade_point_scale.grade_point',
                                          'college_grade_point_scale.letter_equivalence',
                                          'college_grade_point_scale.percent_equivalence',
                                          'college_grade_point_scale.grade_remarks'
                                          )
                                    ->get();
            foreach($student_grades as $grades){
                  if($grades->prelim_transmuted == null || $grades->prelim_transmuted == 0){
                        $grades->prelim_remarks = 0;
                  }else{
                        $grades->prelim_remarks = optional($equivalence->firstWhere('grade_point', $grades->prelim_transmuted))->grade_remarks;
                  }

                  if($grades->midterm_transmuted == null || $grades->midterm_transmuted == 0){
                        $grades->midterm_remarks = 0;
                  }else{
                        $grades->midterm_remarks = optional($equivalence->firstWhere('grade_point', $grades->midterm_transmuted))->grade_remarks;
                  }

                  if($grades->prefinal_transmuted == null || $grades->prefinal_transmuted == 0){
                        $grades->prefinal_remarks = 0;
                  }else{
                        $grades->prefinal_remarks = optional($equivalence->firstWhere('grade_point', $grades->prefinal_transmuted))->grade_remarks;
                  }

                  if($grades->final_transmuted == null || $grades->final_transmuted == 0){
                        $grades->final_remarks = 0;
                  }else{
                        $grades->final_remarks = optional($equivalence->firstWhere('grade_point', $grades->final_transmuted))->grade_remarks;
                  }
            }
            
            return $student_grades;
      }

      public function change_status_students_grades(Request $request){
            $subjectid = request()->get('subjectid');
            $sectionid = request()->get('sectionid');
            $syid = request()->get('syid');
            $semid = request()->get('semid');
            $studid = request()->get('studid');
            $stud_status = request()->get('stud_status');
            $term = request()->get('term_students');
            
            if($term == 1){
                  
                  DB::table('college_stud_term_grades')
                        ->where('studid', $studid)
                        ->where('syid', $syid)
                        ->where('semid', $semid)
                        ->where('prospectusID', $subjectid)
                        ->update([
                              'prelim_status' => $stud_status
                        ]);
                  
                  DB::table('college_grading_scores')
                        ->where('studid', $studid)
                        ->where('sectionid', $sectionid)
                        ->where('prospectusid', $subjectid)
                        ->where('syid', $syid)
                        ->where('semid', $semid)
                        ->where('term', '=', '1')
                        ->update([
                              'status_flag' => $stud_status
                        ]);
            }else if($term == 2){
                  DB::table('college_stud_term_grades')
                        ->where('studid', $studid)
                        ->where('syid', $syid)
                        ->where('semid', $semid)
                        ->where('prospectusID', $subjectid)
                        ->update([
                              'midterm_status' => $stud_status
                        ]);
                  
                  DB::table('college_grading_scores')
                        ->where('studid', $studid)
                        ->where('sectionid', $sectionid)
                        ->where('prospectusid', $subjectid)
                        ->where('syid', $syid)
                        ->where('semid', $semid)
                        ->where('term', '=', '2')
                        ->update([
                              'status_flag' => $stud_status
                        ]);
                  
            }else if($term == 3){
                  DB::table('college_stud_term_grades')
                        ->where('studid', $studid)
                        ->where('syid', $syid)
                        ->where('semid', $semid)
                        ->where('prospectusID', $subjectid)
                        ->update([
                              'prefinal_status' => $stud_status
                        ]);
                  
                  DB::table('college_grading_scores')
                        ->where('studid', $studid)
                        ->where('sectionid', $sectionid)
                        ->where('prospectusid', $subjectid)
                        ->where('syid', $syid)
                        ->where('semid', $semid)
                        ->where('term', '=', '3')
                        ->update([
                              'status_flag' => $stud_status
                        ]);
                  
            }else if($term == 4){
                  DB::table('college_stud_term_grades')
                        ->where('studid', $studid)
                        ->where('syid', $syid)
                        ->where('semid', $semid)
                        ->where('prospectusID', $subjectid)
                        ->update([
                              'final_status' => $stud_status
                        ]);
                  
                  DB::table('college_grading_scores')
                        ->where('studid', $studid)
                        ->where('sectionid', $sectionid)
                        ->where('prospectusid', $subjectid)
                        ->where('syid', $syid)
                        ->where('semid', $semid)
                        ->where('term', '=', '4')
                        ->update([
                              'status_flag' => $stud_status
                        ]);
                  
            }
           
      }

      public function get_terms(Request $request){
            $subjectid = request()->get('subjectid');
            $sectionid = request()->get('sectionid');
            $syid = request()->get('syid');
            $semid = request()->get('semid');

            $terms = DB::table('college_ecr_term')
                        ->join('college_termgrading', 'college_ecr_term.termID', '=', 'college_termgrading.id')
                        ->join('college_ecr', 'college_ecr_term.ecrID', '=', 'college_ecr.id')
                        ->join('college_subject_ecr', 'college_ecr_term.ecrID', '=', 'college_subject_ecr.ecrtemplate_id')
                        ->where('college_ecr_term.deleted', 0)
                        ->where('college_subject_ecr.subject_id', $subjectid)
                        ->where('college_subject_ecr.syid', $syid)
                        ->where('college_subject_ecr.semid', $semid)
                        ->where('college_subject_ecr.section_id', $sectionid)
                        ->select('college_termgrading.description', 'college_termgrading.quarter',)
                        ->groupBy('college_termgrading.id')
                        ->orderBy('college_termgrading.quarter', 'asc')
                        ->get();

            return $terms;
      }

      public function drop_grades(Request $request){
            $studid = request()->get('studid');
            $sectionid = request()->get('sectionid');
            $subjectid = request()->get('subjectid');
            $syid = request()->get('syid');
            $semid = request()->get('semid');
            $termid = request()->get('term');
            $scores = request()->get('scores');

          

            $terms = DB::table('college_ecr_term')
                  ->join('college_termgrading', 'college_ecr_term.termID', '=', 'college_termgrading.id')
                  ->join('college_ecr', 'college_ecr_term.ecrID', '=', 'college_ecr.id')
                  ->join('college_subject_ecr', 'college_ecr.id', '=', 'college_subject_ecr.ecrtemplate_id')
                  ->where('college_subject_ecr.syid', $scores[0]['syid'])
                  ->where('college_subject_ecr.semid', $scores[0]['semid'])
                  ->where('college_subject_ecr.section_id', $scores[0]['sectionid'])
                  ->where('college_ecr_term.deleted', 0)
                  ->select('college_termgrading.description', 'college_termgrading.quarter', 'college_ecr.id')
                  ->get();

            $score_term = [];
            foreach($terms as $term){
                  array_push($score_term, $term->quarter);
            }
            foreach($scores as $score){
                  
                  $exist = DB::table('college_grading_scores')
                        ->where('prospectusid', $score['subjectid'])
                        ->where('sectionid', $score['sectionid'])
                        ->where('syid', $score['syid'])
                        ->where('semid', $score['semid'])
                        ->where('studid', $score['studid'])
                        ->where('componentid', $score['component_id'])
                        ->where('subcomponent_id', $score['subid'])
                        ->where('term', $score['term'])
                        ->where('column_number', $score['sort'])
                        ->first();

                  if($score['score'] == 'd'){
                        $score['score'] = 'DRP';
                  }
                  
                  if($exist){
                        if($score['term'] == 1){                              
                              DB::table('college_grading_scores')
                                    ->where('prospectusid', $score['subjectid'])
                                    ->where('sectionid', $score['sectionid'])
                                    ->where('syid', $score['syid'])
                                    ->where('semid', $score['semid'])
                                    ->where('studid', $score['studid'])
                                    ->where('componentid', $score['component_id'])
                                    ->where('subcomponent_id', $score['subid'])
                                    ->whereIn('term', $score_term)
                                    ->where('column_number', $score['sort'])
                                    ->update([
                                          'score' => $score['score'],
                                          'status_flag' => 8
                                    ]);
                        }else if($score['term'] == 2){
                              $score_term_filtered = array_diff($score_term, [1]);
                              DB::table('college_grading_scores')
                                    ->where('prospectusid', $score['subjectid'])
                                    ->where('sectionid', $score['sectionid'])
                                    ->where('syid', $score['syid'])
                                    ->where('semid', $score['semid'])
                                    ->where('studid', $score['studid'])
                                    ->where('componentid', $score['component_id'])
                                    ->where('subcomponent_id', $score['subid'])
                                    ->whereIn('term', $score_term_filtered)
                                    ->where('column_number', $score['sort'])
                                    ->update([
                                          'score' => $score['score'],
                                          'status_flag' => 8
                                    ]);
                        }else if($score['term'] == 3){
                              $score_term_filtered = array_diff($score_term, [1, 2]);
                              DB::table('college_grading_scores')
                                    ->where('prospectusid', $score['subjectid'])
                                    ->where('sectionid', $score['sectionid'])
                                    ->where('syid', $score['syid'])
                                    ->where('semid', $score['semid'])
                                    ->where('studid', $score['studid'])
                                    ->where('componentid', $score['component_id'])
                                    ->where('subcomponent_id', $score['subid'])
                                    ->whereIn('term', $score_term_filtered)
                                    ->where('column_number', $score['sort'])
                                    ->update([
                                          'score' => $score['score'],
                                          'status_flag' => 8
                                    ]);
                        }else if($score['term'] == 4){
                              $score_term_filtered = array_diff($score_term, [1, 2, 3]);
                              DB::table('college_grading_scores')
                                    ->where('prospectusid', $score['subjectid'])
                                    ->where('sectionid', $score['sectionid'])
                                    ->where('syid', $score['syid'])
                                    ->where('semid', $score['semid'])
                                    ->where('studid', $score['studid'])
                                    ->where('componentid', $score['component_id'])
                                    ->where('subcomponent_id', $score['subid'])
                                    ->whereIn('term', $score_term_filtered)
                                    ->where('column_number', $score['sort'])
                                    ->update([
                                          'score' => $score['score'],
                                          'status_flag' => 8
                                    ]);
                        }
                        
                  }else{
                        foreach(array_diff($score_term, [$score['term']-1]) as $i){
                              DB::table('college_grading_scores')
                                    ->insert([
                                          'studid' => $score['studid'],
                                          'prospectusid' => $score['subjectid'],
                                          'sectionid' => $score['sectionid'],
                                          'syid' => $score['syid'],
                                          'semid' => $score['semid'],
                                          'componentid' => $score['component_id'],
                                          'subcomponent_id' => $score['subid'],
                                          'score' => $score['score'],
                                          'status_flag' => 8,
                                          'term' => $i,
                                          'column_number' => $score['sort']
                                    ]);
                        }

                        DB::table('college_grading_scores')
                                    ->where('prospectusid', $score['subjectid'])
                                    ->where('sectionid', $score['sectionid'])
                                    ->where('syid', $score['syid'])
                                    ->where('semid', $score['semid'])
                                    ->where('studid', $score['studid'])
                                    ->where('componentid', $score['component_id'])
                                    ->where('subcomponent_id', $score['subid'])
                                    ->where('term', $score['term']-1)
                                    ->where('column_number', $score['sort'])
                                    ->update([
                                          'status_flag' => 8
                                    ]);
                        
                        
                  }
            }

            $exists = DB::table('college_stud_term_grades')
                  ->where('prospectusID', $subjectid)
                  ->where('studid', $studid)
                  ->where('syid', $syid)
                  ->where('semid', $semid)
                  ->first();

            if(isset($exists->id)){
                  if($termid == 1){
                        DB::table('college_stud_term_grades')
                              ->where('prospectusID', $subjectid)
                              ->where('studid', $studid)
                              ->where('syid', $syid)
                              ->where('semid', $semid)
                              ->update([
                                    'prelim_status' => $terms->where('quarter', 1)->count() > 0 ? 8 : null,
                                    'midterm_status' => $terms->where('quarter', 2)->count() > 0 ? 8 : null,
                                    'prefinal_status' => $terms->where('quarter', 3)->count() > 0 ? 8 : null,
                                    'final_status' => $terms->where('quarter', 4)->count() > 0 ? 8 : null,
                                    'prelim_grade' => 'DRP',
                                    'midterm_grade' => 'DRP',
                                    'prefinal_grade' => 'DRP',
                                    'final_grade' => 'DRP'
                              ]);

                  }else if($termid == 2){

                        DB::table('college_stud_term_grades')
                              ->where('prospectusID', $subjectid)
                              ->where('studid', $studid)
                              ->where('syid', $syid)
                              ->where('semid', $semid)
                              ->update([
                                    'prelim_status' => $terms->where('quarter', 1)->count() > 0 ? 8 : null,
                                    'midterm_status' => $terms->where('quarter', 2)->count() > 0 ? 8 : null,
                                    'prefinal_status' => $terms->where('quarter', 3)->count() > 0 ? 8 : null,
                                    'final_status' => $terms->where('quarter', 4)->count() > 0 ? 8 : null,
                                    'prefinal_grade' => 'DRP',
                                    'final_grade' => 'DRP'
                              ]);

                  }else if($termid == 3){
                        DB::table('college_stud_term_grades')
                              ->where('prospectusID', $subjectid)
                              ->where('studid', $studid)
                              ->where('syid', $syid)
                              ->where('semid', $semid)
                              ->update([
                                    'prelim_status' => $terms->where('quarter', 1)->count() > 0 ? 8 : null,
                                    'midterm_status' => $terms->where('quarter', 2)->count() > 0 ? 8 : null,
                                    'prefinal_status' => $terms->where('quarter', 3)->count() > 0 ? 8 : null,
                                    'final_status' => $terms->where('quarter', 4)->count() > 0 ? 8 : null,
                                    'prefinal_grade' => 'DRP',
                                    'final_grade' => 'DRP'
                              ]);

                  }else if($termid == 4){
                        DB::table('college_stud_term_grades')
                              ->where('prospectusID', $subjectid)
                              ->where('studid', $studid)
                              ->where('syid', $syid)
                              ->where('semid', $semid)
                              ->update([
                                    'prelim_status' => $terms->where('quarter', 1)->count() > 0 ? 8 : null,
                                    'midterm_status' => $terms->where('quarter', 2)->count() > 0 ? 8 : null,
                                    'prefinal_status' => $terms->where('quarter', 3)->count() > 0 ? 8 : null,
                                    'final_status' => $terms->where('quarter', 4)->count() > 0 ? 8 : null,
                                    'final_grade' => 'DRP'
                              ]);
                  }
                 
            } else{
                  DB::table('college_stud_term_grades')
                        ->insert([
                              'studid' => $studid,
                              'prospectusID' => $subjectid,
                              'syid' => $syid,
                              'semid' => $semid,
                              'prelim_status' => $terms->where('quarter', 1)->count() > 0 ? 8 : null,
                              'midterm_status' => $terms->where('quarter', 2)->count() > 0 ? 8 : null,
                              'prefinal_status' => $terms->where('quarter', 3)->count() > 0 ? 8 : null,
                              'final_status' => $terms->where('quarter', 4)->count() > 0 ? 8 : null,
                              'prelim_grade' => 'DRP',
                              'midterm_grade' => 'DRP',
                              'prefinal_grade' => 'DRP',
                              'final_grade' => 'DRP'
                              
                        ]);
            }

           
            
            
                  

      }

      public function check_dropped(Request $request){

            $studid = $request->get('studid');
            $subjectid = $request->get('subjectid');
            $sectionid = $request->get('sectionid');
            $term = $request->get('term');
            $syid = $request->get('syid');
            $semid = $request->get('semid');

            $dropped = DB::table('college_stud_term_grades')
                  ->where('studid', $studid)
                  ->where('prospectusID', $subjectid)
                  ->where('syid', $request->syid)
                  ->where('semid', $request->semid)
                  ->where('prelim_status', 8)
                  ->first();

            if($dropped){
                  return 'dropped';
            }

            $dropped = DB::table('college_stud_term_grades')
                  ->where('studid', $studid)
                  ->where('prospectusID', $subjectid)
                  ->where('syid', $request->syid)
                  ->where('semid', $request->semid)
                  ->where('midterm_status', 8)
                  ->first();

            if($dropped){
                  return 'dropped';
            }

            $dropped = DB::table('college_stud_term_grades')
                  ->where('studid', $studid)
                  ->where('prospectusID', $subjectid)
                  ->where('syid', $request->syid)
                  ->where('semid', $request->semid)
                  ->where('prefinal_status', 8)
                  ->first();

            if($dropped){
                  return 'dropped';
            }

            $dropped = DB::table('college_stud_term_grades')
                  ->where('studid', $studid)
                  ->where('prospectusID', $subjectid)
                  ->where('syid', $request->syid)
                  ->where('semid', $request->semid)
                  ->where('final_status', 8)
                  ->first();

            if($dropped){
                  return 'dropped';
            }
                       
            return 'not dropped';
      }

      public function get_all_subjects(Request $request){

            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $courseid = $request->get('courseid');
            $gradelevel = $request->get('gradelevel');

            $subjects = DB::table('college_prospectus')
                        ->join('college_classsched', 'college_prospectus.id', '=', 'college_classsched.subjectID') 
                        ->join('college_sections', 'college_classsched.sectionID', '=', 'college_sections.id') 
                        ->leftJoin('college_loadsubject', function ($join) {
                              $join->on('college_classsched.id', '=', 'college_loadsubject.schedid');
                              $join->where('college_loadsubject.deleted', 0);
                        })
                        ->leftJoin('college_enrolledstud', function ($join) {
                              $join->on('college_loadsubject.studid', '=', 'college_enrolledstud.studid');
                              $join->where('college_enrolledstud.deleted', 0);
                        })
                        ->leftJoin('college_courses', 'college_sections.courseid', '=', 'college_courses.id')
                        ->leftJoin('gradelevel', 'college_sections.yearID', '=', 'gradelevel.id')
                        ->leftJoin('college_stud_term_grades', function ($join) use ($syid, $semid) {
                              $join->on('college_prospectus.id', '=', 'college_stud_term_grades.prospectusID');
                              $join->on('college_stud_term_grades.studid', '=', 'college_loadsubject.studid'); // Ensure student match
                              $join->on('college_loadsubject.sectionID', '=', 'college_sections.id'); // Ensure section match
                              $join->where('college_stud_term_grades.deleted', 0);
                              $join->where('college_stud_term_grades.syid', $syid);
                              $join->where('college_stud_term_grades.semid', $semid);
                          })
                        ->join('college_instructor', 'college_classsched.id', '=', 'college_instructor.classschedid')
                        ->leftJoin('teacher', 'college_instructor.teacherid', '=', 'teacher.id')
                        ->where('gradelevel.deleted', 0)
                        ->where('college_sections.deleted', 0)
                        ->where('college_sections.syid', $syid)
                        ->where('college_sections.semesterid', $semid)
                        ->where('college_classsched.deleted', 0)
                        ->when($courseid != 0, function ($query) use ($courseid) {
                              $query->where('college_sections.courseid', $courseid);
                        })
                        ->when($gradelevel != 0, function ($query) use ($gradelevel) {
                              $query->where('college_sections.yearID', $gradelevel);
                        })
                        ->select(
                              'college_instructor.teacherid',
                              'teacher.lastname',
                              'teacher.firstname',
                              'college_prospectus.subjDesc',
                              'college_prospectus.subjCode',
                              'college_prospectus.id as subjid',
                              'college_sections.id as sectionid',
                              'college_classsched.id as schedid',
                              'college_stud_term_grades.studid',
                              'college_stud_term_grades.prelim_status',
                              'college_stud_term_grades.midterm_status',
                              'college_stud_term_grades.prefinal_status',
                              'college_stud_term_grades.final_status',
                              'college_stud_term_grades.is_final_grading',
                        )
                        ->groupBy('college_prospectus.id', 'college_sections.id', 'college_classsched.id', 'college_stud_term_grades.studid')
                        ->get();

                        // ->select(
                        //       'college_instructor.teacherid',
                        //       'teacher.lastname',
                        //       'teacher.firstname',
                        //       'college_prospectus.subjDesc',
                        //       'college_prospectus.subjCode',
                        //       'college_prospectus.id as subjid',
                        //       'college_sections.id as sectionid',
                        //       'college_classsched.id as schedid',
                        //       'college_stud_term_grades.studid',
                          
                        //       DB::raw('COALESCE(college_stud_term_grades.prelim_status, "Pending") as prelim_status'),
                        //       DB::raw('COALESCE(college_stud_term_grades.midterm_status, "Pending") as midterm_status'),
                        //       DB::raw('COALESCE(college_stud_term_grades.prefinal_status, "Pending") as prefinal_status'),
                        //       DB::raw('COALESCE(college_stud_term_grades.final_status, "Pending") as final_status'),
                        //       DB::raw('COALESCE(college_stud_term_grades.is_final_grading, 0) as is_final_grading')
                        //   )


            
            return $subjects;

      }

      public function final_grade_get_students(Request $request){
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $sectionid = $request->get('sectionid');
            $subjectid = $request->get('subjectid');
            $term = $request->get('term');
            $status = $request->get('status');

            $students = DB::table('college_sections')
                        ->join('college_classsched', 'college_sections.id', '=', 'college_classsched.sectionID')
                        ->join('college_prospectus', 'college_classsched.subjectID', '=', 'college_prospectus.id')
                        ->join('college_stud_term_grades', 'college_prospectus.id', '=', 'college_stud_term_grades.prospectusid')
                        ->join('studinfo', 'studinfo.id', '=', 'college_stud_term_grades.studid')
                        ->where('college_sections.id', $sectionid)
                        ->where('college_sections.deleted', 0)
                        ->where('college_stud_term_grades.deleted', 0)
                        ->where('college_stud_term_grades.syid', $syid)
                        ->where('college_stud_term_grades.semid', $semid)
                        ->where('college_stud_term_grades.prospectusID', $subjectid)
                        ->when($term == 1, function ($query) use ($status) {
                              $query->where('college_stud_term_grades.prelim_status', $status);
                        })
                        ->when($term == 2, function ($query) use ($status) {
                              $query->where('college_stud_term_grades.midterm_status', $status);
                        })
                        ->when($term == 3, function ($query) use ($status) {
                              $query->where('college_stud_term_grades.prefinal_status', $status);

                        })
                        ->when($term == 4, function ($query) use ($status) {
                              $query->where('college_stud_term_grades.final_status', $status);
                        })
                        ->orderBy('studinfo.lastname', 'asc')
                        ->groupBy('college_stud_term_grades.studid')
                        ->select(
                              'studinfo.id',
                              DB::raw("CONCAT(studinfo.lastname, ', ', studinfo.firstname, ' ', IFNULL(CONCAT(SUBSTRING(studinfo.middlename, 1, 1), '.'), '')) AS full_name")

                        )
                        ->get();

            


            $student_ids = $students->pluck('id');
            
            $student_grades = DB::table('college_stud_term_grades')
                              ->where('syid', $syid)
                              ->where('semid', $semid)
                              ->whereIn('studid', $student_ids)
                              ->where('prospectusid', $subjectid)
                              ->groupBy('prospectusid', 'studid')
                              ->select(
                                    'studid',
                                    'prospectusid',
                                    'prelim_transmuted',
                                    'midterm_transmuted',
                                    'prefinal_transmuted',
                                    'final_transmuted',
                              )
                              ->get()
                              ->keyBy('studid');

            $filtered_students = $students->map(function ($student) use ($student_grades, $term) {
                  $student_grade = $student_grades->get($student->id); // Use keyBy lookup instead of firstWhere
            
                  if ($student_grade) {
                        $grades = [
                        1 => $student_grade->prelim_transmuted,
                        2 => $student_grade->midterm_transmuted,
                        3 => $student_grade->prefinal_transmuted,
                        4 => $student_grade->final_transmuted
                        ];
                        
                        $student->grade = $grades[$term] ?? null;
                        $student->prospectusid = $student_grade->prospectusid;
                  } else {
                        $student->grade = null;
                  }
            
                  return $student;
            })->filter(fn($student) => !is_null($student->grade))->values();
            

            return $students;
      }

      public function final_grade_update_status(Request $request){
            $syid = $request->get('syid');
            $semid = $request->get('semid');
            $final_grades = $request->get('checked_final_grades');
            $term = $request->get('term');
            $status = $request->get('status');

            foreach($final_grades as $final_grade){
                  if($term == 1){
                        DB::table('college_stud_term_grades')
                              ->where('studid', $final_grade['studid'])
                              ->where('prospectusid', $final_grade['subjectid'])
                              ->where('syid', $syid)
                              ->where('semid', $semid)
                              ->update([
                                    'prelim_updateby' => auth()->user()->id,
                                    'prelim_updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                    'prelim_status' => $status
                              ]);
                  }else if($term == 2){
                        DB::table('college_stud_term_grades')
                              ->where('studid', $final_grade['studid'])
                              ->where('prospectusid', $final_grade['subjectid'])
                              ->where('syid', $syid)
                              ->where('semid', $semid)
                              ->update([
                                    'midterm_updateby' => auth()->user()->id,
                                    'midterm_updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                    'midterm_status' => $status
                              ]);
                  }else if($term == 3){
                        DB::table('college_stud_term_grades')
                              ->where('studid', $final_grade['studid'])
                              ->where('prospectusid', $final_grade['subjectid'])
                              ->where('syid', $syid)
                              ->where('semid', $semid)
                              ->update([
                                    'prefinal_updateby' => auth()->user()->id,
                                    'prefinal_updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                    'prefinal_status' => $status
                              ]);
                  }else if($term == 4){
                        DB::table('college_stud_term_grades')
                              ->where('studid', $final_grade['studid'])
                              ->where('prospectusid', $final_grade['subjectid'])
                              ->where('syid', $syid)
                              ->where('semid', $semid)
                              ->update([
                                    'final_updateby' => auth()->user()->id,
                                    'final_updateddatetime' => \Carbon\Carbon::now('Asia/Manila'),
                                    'final_status' => $status
                              ]);
                  }


                  $exists = DB::table('college_grading_scores')
                        ->where('studid', $final_grade['studid'])
                        ->where('prospectusid', $final_grade['subjectid'])
                        ->where('sectionid', $final_grade['sectionid'])
                        ->where('term', $term)
                        ->where('syid', $syid)
                        ->where('semid', $semid)
                        ->get();

                  if($exists){
                        DB::table('college_grading_scores')
                              ->where('studid', $final_grade['studid'])
                              ->where('prospectusid', $final_grade['subjectid'])
                              ->where('sectionid', $final_grade['sectionid'])
                              ->where('term', $term)
                              ->where('syid', $syid)
                              ->where('semid', $semid)
                              ->update([
                                    'status_flag' => $status
                              ]);
                  }
                  
                  

            }
      }
      

}
