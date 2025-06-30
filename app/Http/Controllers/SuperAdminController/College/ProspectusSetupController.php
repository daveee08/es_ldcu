<?php

namespace App\Http\Controllers\SuperAdminController\College;

use Illuminate\Http\Request;
use DB;
use Session;
use PDF;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ProspectusSetupController extends \App\Http\Controllers\Controller
{

      public static function collegesubject_select(Request $request){


            

            $search = $request->get('search');
    
            $all_subjects = DB::table('college_subjects')
                              ->join('college_prospectus',function($join){
                                  $join->on('college_subjects.id','=','college_prospectus.subjectID');
                                  $join->where('college_prospectus.deleted',0);
                              })
                              ->where('college_subjects.deleted',0)
                              ->where(function($query) use($search){
                                    if($search != null && $search != ""){
                                          $query->orWhere('college_subjects.subjCode','like','%'.$search.'%');
                                          $query->orWhere('college_subjects.subjDesc','like','%'.$search.'%');
                                    }
                              })
                              ->take(10)
                              ->skip($request->get('page')*10)
                              ->select(
                                    'college_subjects.id',
                                    'college_subjects.subjCode',
                                    'college_subjects.subjDesc',
                                    'college_prospectus.lecunits',
                                    'college_prospectus.labunits',
                                    'college_prospectus.credunits',
                                   
                                    DB::raw("CONCAT(college_subjects.subjCode,' - ',college_subjects.subjDesc) as text")
                              )
                        
                              ->get();
    
    
            $all_subjects_count = DB::table('college_subjects')
                      ->join('college_prospectus',function($join){
                            $join->on('college_subjects.id','=','college_prospectus.subjectID');
                            $join->where('college_prospectus.deleted',0);
                      })
                      ->where('college_subjects.deleted',0)
                      ->where(function($query) use($search){
                            if($search != null && $search != ""){
                                  $query->orWhere('college_subjects.subjCode','like','%'.$search.'%');
                                  $query->orWhere('college_subjects.subjDesc','like','%'.$search.'%');
                            }
                      })
                      ->select(
                            'college_subjects.id',
                            'college_subjects.subjCode',
                            'college_subjectssubjDesc',
                            DB::raw("CONCAT(college_subjects.subjCode,' - ',college_subjects.subjDesc) as text")
                      )
                      ->count();       
               
            return @json_encode((object)[
                  "results"=>$all_subjects,
                  "pagination"=>(object)[
                        "more"=>$all_subjects_count > 10  ? true :false
                  ],
                  "count_filtered"=>$all_subjects_count
            ]);
        
    
      }


      public static function printable(Request $request){
            
            $subjects = self::curriculum_subjects($request);
            $schoolinfo = DB::table('schoolinfo')->first();
            $semester = DB::table('semester')->get();

            $yearlevel = DB::table('gradelevel')
                              ->whereIn('acadprogid',[6,8])
                              ->where('deleted',0)
                              ->select(
                                    'id',
                                    'levelname as text'
                              )
                              ->get();

            $courseinfo = DB::table('college_courses')->where('id',$request->get('courseid'))->first();

            $curriculuminfo = DB::table('college_curriculum')->where('id',$request->get('curriculumid'))->first();


            // return $subjects[0]['subjects'];

            $pdf = PDF::loadView('superadmin.pages.setup.prospectus_pdf',compact('courseinfo','curriculuminfo','schoolinfo','subjects','semester','yearlevel'))->setPaper('legal');
            $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);
            return $pdf->stream();


      }


      public static function curriculum_subjects(Request $request){

            $courseid = $request->get('courseid');
            $curriculumid = $request->get('curriculumid');

            $subjects = Db::table('college_prospectus')
                              ->where('deleted',0)
                              ->where('courseID',$courseid)
                              ->where('curriculumID',$curriculumid)
                              ->select(
                                    'id',
                                    'courseID',
                                    'subjectID',
                                    'semesterID',
                                    'yearID',
                                    'lecunits',
                                    'labunits',
                                    'subjDesc',
                                    'subjCode',
                                    'subjClass',
                                    'curriculumID',
                                    'psubjsort',
                                    DB::raw("CONCAT(college_prospectus.subjCode,' - ',college_prospectus.subjDesc) as text")
                              )
                              ->get();

            
            $prereq = Db::table('college_prospectus')
                              ->where('college_prospectus.deleted',0)
                              ->where('college_prospectus.courseID',$courseid)
                              ->where('college_prospectus.curriculumID',$curriculumid)
                              ->join('college_subjprereq',function($join){
                                    $join->on('college_prospectus.id','=','college_subjprereq.subjID');
                                    $join->where('college_subjprereq.deleted',0);
                              })
                              ->selecT(
                                    'subjID',
                                    'prereqsubjID'
                              )
                              ->get();



            return array([
                  'subjects'=>$subjects,
                  'prereq'=>$prereq
            ]);

      }

      // public static function courses(Request $request){

      //       //Fetch Courses for a Teacher
      //       // if(Session::get('currentPortal') == 16){

                  
      //       //       $teacher = DB::table('teacher')
      //       //                         ->where('userid',auth()->user()->id)
      //       //                         ->first();

      //       //       $courses = DB::table('teacherprogramhead')
      //       //                         ->where('teacherprogramhead.deleted',0)
      //       //                         ->where('teacherid',$teacher->id)
      //       //                         ->join('college_courses',function($join){
      //       //                               $join->on('teacherprogramhead.courseid','=','college_courses.id');
      //       //                               $join->where('college_courses.deleted',0);
      //       //                         })
      //       //                         ->distinct('teacherprogramhead.courseid')
      //       //                         ->select(
      //       //                               'college_courses.id',
      //       //                               'college_courses.courseDesc',
      //       //                               'college_courses.courseabrv'
      //       //                         )
      //       //                         ->get();

      //       // //Fetch Courses via College Dean
      //       // }else if(Session::get('currentPortal') == 14){

      //             $teacher = DB::table('teacher')
      //                               ->where('userid',auth()->user()->id)
      //                               ->first();

      //             $courses = DB::table('teacherdean')
      //                         ->where('teacherdean.deleted',0)
      //                         ->where('teacherid',$teacher->id)
      //                         ->join('college_colleges',function($join){
      //                               $join->on('teacherdean.collegeid','=','college_colleges.id');
      //                               $join->where('college_colleges.deleted',0);
      //                         })
      //                         ->join('college_courses',function($join){
      //                               $join->on('college_colleges.id','=','college_courses.collegeid');
      //                               $join->where('college_courses.deleted',0);
      //                         })
      //                         // ->distinct('teacherdean.collegeid')
      //                         ->select(
      //                               'college_courses.id',
      //                               'college_courses.courseDesc',
      //                               'college_courses.courseabrv',
      //                               'college_colleges.collegeDesc'
      //                         )
      //                         ->get();
      //       // }else{

      //       // return $courses;

      //             if ($courses->isEmpty()) {
      //                   $courses = DB::table('college_courses')
      //                         ->where('college_courses.deleted',0)
      //                         ->where('college_colleges.deleted',0)
      //                         ->join('college_colleges', 'college_courses.collegeid', '=', 'college_colleges.id')
      //                         ->select(
      //                               'college_courses.id',
      //                               'college_courses.courseDesc',
      //                               'college_courses.courseabrv',
      //                               'college_colleges.collegeDesc'
      //                               )
      //                         ->get();
      //             }

      //       // }

      //       foreach($courses as $item){
      //             $cirriculum = DB::table('college_curriculum')
      //                               ->where('courseID',$item->id)
      //                               ->where('deleted', 0) //exclude deleted curriculum entries
      //                               ->count();

      //             $item->curriculumcount = $cirriculum;
      //       }

      //       return $courses;

      // }

      public static function courses(Request $request){
            // return auth()->user()->id;
            // return Session::get('currentPortal');
            if(Session::get('currentPortal') == 16 ){
                  
                  // $teacher = DB::table('teacher')
                  //                   ->where('userid',auth()->user()->id)
                  //                   ->first();

                              // dd($teacher) ;

                        $courses = DB::table('teacherprogramhead')
                        ->where('teacherprogramhead.deleted', 0)
                        //->where('teacherprogramhead.semid',$semid)
                        ->join('teacher', function ($join) {
                              $join->on('teacherprogramhead.teacherid', '=', 'teacher.id');
                              $join->where('teacher.deleted', 0);
                              $join->where('teacher.userid', auth()->user()->id);
                        })
                        ->join('college_courses',function($join){
                              $join->on('teacherprogramhead.courseid','=','college_courses.id');
                              $join->where('college_courses.deleted',0);
                        })
                        ->join('college_colleges', 'college_courses.collegeid', '=', 'college_colleges.id')
                        ->where('college_colleges.deleted', 0)
                        ->select(
                              'teacher.id',
                              'firstname',
                              'lastname',
                              'middlename',
                              'acadtitle',
                              'suffix',
                              'type',
                              'college_courses.id',
                              'college_courses.courseDesc',
                              'college_courses.courseabrv',
                              'college_colleges.collegeDesc',
                              'college_colleges.acadprogid'

                        )
                        ->get();

                  // $courses = DB::table('teacherdean')
                  //             ->where('teacherdean.deleted',0)
                  //             ->where('teacherid',$teacher->id)
                  //             ->join('college_colleges',function($join){
                  //                   $join->on('teacherdean.collegeid','=','college_colleges.id');
                  //                   $join->where('college_colleges.deleted',0);
                  //             })
                  //             ->join('college_courses',function($join){
                  //                   $join->on('college_colleges.id','=','college_courses.collegeid');
                  //                   $join->where('college_courses.deleted',0);
                  //             })
                  //             // ->distinct('teacherdean.collegeid')
                  //             ->select(
                  //                   'college_courses.id',
                  //                   'college_courses.courseDesc',
                  //                   'college_courses.courseabrv',
                  //                   'college_colleges.collegeDesc'
                  //             )
                  //             ->get();

                              foreach($courses as $item){
                                    $cirriculum = DB::table('college_curriculum')
                                                      ->where('courseID',$item->id)
                                                      ->where('deleted', 0) //exclude deleted curriculum entries
                                                      ->count();
                  
                                    $item->curriculumcount = $cirriculum;
                              }
                  
                              return $courses;
            }



          else if(Session::get('currentPortal') == 14 ){

          
                  $teacher = DB::table('teacher')
                                    ->where('userid',auth()->user()->id)
                                    ->first();

                              // dd($teacher) ;

                  $courses = DB::table('teacherdean')
                              ->where('teacherdean.deleted',0)
                              ->where('teacherid',$teacher->id)
                              ->join('college_colleges',function($join){
                                    $join->on('teacherdean.collegeid','=','college_colleges.id');
                                    $join->where('college_colleges.deleted',0);
                              })
                              ->join('college_courses',function($join){
                                    $join->on('college_colleges.id','=','college_courses.collegeid');
                                    $join->where('college_courses.deleted',0);
                              })
                              // ->distinct('teacherdean.collegeid')
                              ->groupBy('college_courses.id')
                              ->select(
                                    'college_courses.id',
                                    'college_courses.courseDesc',
                                    'college_courses.courseabrv',
                                    'college_colleges.collegeDesc',
                                    'college_colleges.acadprogid'
                              )
                              ->get();

                              foreach($courses as $item){
                                    $cirriculum = DB::table('college_curriculum')
                                                      ->where('courseID',$item->id)
                                                      ->where('deleted', 0) //exclude deleted curriculum entries
                                                      ->count();
                  
                                    $item->curriculumcount = $cirriculum;
                              }
                  
                              return $courses;
            }
            
            else if(Session::get('currentPortal') == 3){

            // return $courses;
                  $teacher = DB::table('teacher')
                                    ->where('userid',auth()->user()->id)
                                    ->first();

                  $courses = DB::table('teacherdean')
                              ->where('teacherdean.deleted',0)
                              // ->where('teacherid',$teacher->id)
                              ->join('college_colleges',function($join){
                                    $join->on('teacherdean.collegeid','=','college_colleges.id');
                                    $join->where('college_colleges.deleted',0);
                              })
                              ->join('college_courses',function($join){
                                    $join->on('college_colleges.id','=','college_courses.collegeid');
                                    $join->where('college_courses.deleted',0);
                              })
                              ->groupBy('college_courses.id')
                              // ->distinct('teacherdean.collegeid')
                              ->select(
                                    'college_courses.id',
                                    'college_courses.courseDesc',
                                    'college_courses.courseabrv',
                                    'college_colleges.collegeDesc',
                                    'college_colleges.acadprogid'

                              )
                              ->get();

                  if ($courses->isEmpty()) {
                        $courses = DB::table('college_courses')
                              ->where('college_courses.deleted',0)
                              ->where('college_colleges.deleted',0)
                              ->join('college_colleges', 'college_courses.collegeid', '=', 'college_colleges.id')
                              ->groupBy('college_courses.id')
                              ->select(
                                    'college_courses.id',
                                    'college_courses.courseDesc',
                                    'college_courses.courseabrv',
                                    'college_colleges.collegeDesc',
                                    'college_colleges.acadprogid'

                                    )
                              ->get();
                  }

            

                              foreach($courses as $item){
                                    $cirriculum = DB::table('college_curriculum')
                                                      ->where('courseID',$item->id)
                                                      ->where('deleted', 0) //exclude deleted curriculum entries
                                                      ->count();

                                    $item->curriculumcount = $cirriculum;
                              }

                              return $courses;
            }

           

      }

    

      public static function course_curriculum(Request $request){

            $courseid = $request->get('courseid');


            $curriculum = DB::table('college_curriculum')
                        ->where('deleted',0)
                        ->where('courseID',$courseid)
                        ->where('deleted',0)
                        ->select(
                              'id',
                              'curriculumname as text'
                        )
                        ->get();

                        return response()->json($curriculum);

            return $curriculum;
      }

      public static function course_curriculum2(Request $request){

            $courseid = $request->get('courseid');


            $curriculum = DB::table('college_curriculum')
                        ->where('deleted',0)
                        ->where('courseID',$courseid)
                        ->where('deleted',0)
                        ->select(
                              'id',
                              'curriculumname as text'
                        )
                        ->get();

                        return response()->json($curriculum);

            return $curriculum;
      }



      //curiculum
   
      

      

      public static function update_subjectgroup(Request $request){

            $subjid = $request->get('subjid');
            $subjgroup = $request->get('subjgroup');

            try{

                  DB::table('college_subjects')
                        ->where('id',$subjid)
                        ->take(1)
                        ->update([
                              'subjgroup'=>$subjgroup,
                              'updatedby'=>auth()->user()->id,
                              'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Subject Group Updated'
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }

      }

      public static function update_subject(Request $request){

            try{
                  $pid = $request->get('pid');
                  $subjdesc = $request->get('subjdesc');
                  $subjcode = $request->get('subjcode');
                  $labunit = $request->get('labunit');
                  $lecunit = $request->get('lecunit');
                  $credunit = $request->get('credunit');
                  $courseid = $request->get('courseid');
                  $curriculumid = $request->get('curriculumid');
                  $semesterID = $request->get('semid');
                  $levelid = $request->get('levelid');
                  $sort = $request->get('sort');
                  $perreq = $request->get('prereq');
                  $subjgroup = $request->get('subjgroup');


                  $check_sched = DB::table('college_classsched')
                                    ->where('subjectID',$pid)
                                    ->where('deleted',0)
                                    ->count();

                  
                  if($check_sched > 0){
                        return array((object)[
                              'status'=>0,
                              'message'=>'Unable to update. Subject is already used.'
                        ]);


                  }



                  if($labunit == ""){
                        $labunit = 0;
                  }
                  if($lecunit == ""){
                        $lecunit = 0;
                  }

                  if($perreq != "" &&  $perreq != null){
            
                        DB::table('college_subjprereq')
                                    ->where('subjid',$pid)
                                    ->whereNotIn('prereqsubjID',$perreq)
                                    ->update([
                                          'deleted'=>1,
                                          'deletedby'=>auth()->user()->id,
                                          'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);

                        foreach($perreq as $item){

                              $check = DB::table('college_subjprereq')
                                          ->where('deleted',0)
                                          ->where('subjid',$pid)
                                          ->where('prereqsubjID',$item)
                                          ->count();

                              if($check == 0 && $pid != $item){
                                    DB::table('college_subjprereq')
                                          ->insert([
                                                'subjID'=>$pid,
                                                'prereqsubjID'=>$item,
                                                'createdby'=>auth()->user()->id,
                                                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                          ]);

                              }

                        }
            
                  }else{
                        DB::table('college_subjprereq')
                              ->where('subjid',$pid)
                              ->update([
                                    'deleted'=>1,
                                    'deletedby'=>auth()->user()->id,
                                    'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }

                  

                  // $pid_subj = DB::table('college_prospectus')
                  //                   ->where('id',$pid)
                  //                   ->where('courseID',$courseid)
                  //                   ->select('subjectID')
                  //                   ->first()
                  //                   ->subjectID;
                                   

                  // $check = DB::table('college_subjects')
                  //             ->where('subjDesc',$subjdesc)
                  //             ->where('subjCode',$subjcode)
                  //             ->where('labunits',$labunit)
                  //             ->where('lecunits',$lecunit)
                  //             ->where('deleted',0)
                  //             ->count();

                  // if($check > 0){

                  //       $subjectId = DB::table('college_subjects')
                  //                         ->where('subjDesc',$subjdesc)
                  //                         ->where('subjCode',$subjcode)
                  //                         ->where('labunits',$labunit)
                  //                         ->where('lecunits',$lecunit)
                  //                         ->where('deleted',0)
                  //                         ->select('id')
                  //                         ->first()
                  //                         ->id;

                        DB::table('college_prospectus')
                              ->where('id',$pid)
                              ->where('courseID',$courseid)
                              ->take(1)
                              ->update([
                                    'psubjsort'=>$sort,
                                    // 'subjectID'=>$subjectId,
                                    'subjgroup'=>$subjgroup,
                                    'lecunits'=>$lecunit,
                                    'labunits'=>$labunit,
                                    'credunits'=>$credunit,
                                    // 'subjDesc'=>$subjdesc,
                                    // 'subjCode'=>$subjcode,
                                    'updatedby'=>auth()->user()->id,
                                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                              ]);

                        //remove subject if not used
                  //       $check_if_used = DB::table('college_prospectus')
                  //                         ->where('subjectID',$pid_subj)
                  //                         ->where('deleted',0)
                  //                         ->count();

                  //       if($check_if_used == 0){
                  //             DB::table('college_subjects')
                  //                   ->where('id',$pid_subj)
                  //                   ->take(1)
                  //                   ->update([
                  //                         'deleted'=>1,
                  //                         'deletedby'=>auth()->user()->id,
                  //                         'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                  //                   ]);
                  //       }

                  //       return array((object)[
                  //             'status'=>1,
                  //             'message'=>'Subject Updated.'
                  //       ]);

                  // }          

                  // $subjectId = DB::table('college_subjects')
                  //                   ->insertGetId([
                  //                         'subjDesc'=>$subjdesc,
                  //                         'subjCode'=>$subjcode,
                  //                         'labunits'=>$labunit,
                  //                         'lecunits'=>$lecunit,
                  //                         'subjClass'=>1,
                  //                         'deleted'=>0,
                  //                         'subjgroup'=>$subjgroup,
                  //                         'createdby'=>auth()->user()->id,
                  //                         'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                  //                   ]);

                  // DB::table('college_prospectus')
                  //       ->where('id',$pid)
                  //       ->where('courseID',$courseid)
                  //       ->update([
                  //             'subjectID'=>$subjectId,
                  //             'lecunits'=>$lecunit,
                  //             'labunits'=>$labunit,
                  //             'subjDesc'=>$subjdesc,
                  //             'subjCode'=>$subjcode,
                  //             'updatedby'=>auth()->user()->id,
                  //             'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                  //       ]);


                  // //remove subject if not used
                  // $check_if_used = DB::table('college_prospectus')
                  //                   ->where('subjectID',$pid_subj)
                  //                   ->where('deleted',0)
                  //                   ->count();

                  // if($check_if_used == 0){
                  //       DB::table('college_subjects')
                  //             ->where('id',$pid_subj)
                  //             ->take(1)
                  //             ->update([
                  //                   'deleted'=>1,
                  //                   'deletedby'=>auth()->user()->id,
                  //                   'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                  //             ]);
                  // }


                  // DB::table('college_subjects')
                  //       ->where('id',$pid_subj)
                  //       ->take(1)
                  //       ->update([
                  //             'deleted'=>1,
                  //             'deletedby'=>auth()->user()->id,
                  //             'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                  //       ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Subject Updated.'
                  ]);
            

            }catch(\Exception $e){
                  return self::store_error($e);
            }


      }

      public static function add_new_subject(Request $request){
            try{
                  $subjdesc = $request->get('subjdesc');
                  $subjcode = $request->get('subjcode');
                  $labunit = $request->get('labunit');
                  $lecunit = $request->get('lecunit');
                  $courseid = $request->get('courseid');
                  $curriculumid = $request->get('curriculumid');
                  $semesterID = $request->get('semid');
                  $levelid = $request->get('levelid');
                  $subjgroup = $request->get('subjgroup');

                  if($labunit == ""){
                        $labunit = 0;
                  }
                  if($lecunit == ""){
                        $lecunit = 0;
                  }

                  $check = DB::table('college_subjects')
                              ->where('subjDesc',$subjdesc)
                              ->where('subjCode',$subjcode)
                              // ->where('labunits',$labunit)
                              // ->where('lecunits',$lecunit)
                              ->where('deleted',0);
                  if($labunit == 1){
                        $check = $check->where('labunits','1.0')->count();
                  }else{
                        $check = $check->where('labunits','0.0')->count();
                  }
                  
                  if($check > 0){
                        return array((object)[
                              'status'=>2,
                              'message'=>'Subject already exist.'
                        ]);
                  }          

                  $subjectId = DB::table('college_subjects')
                                    ->insertGetId([
                                          'subjgroup'=>$subjgroup,
                                          'subjDesc'=>$subjdesc,
                                          'subjCode'=>$subjcode,
                                          'labunits'=>$labunit,
                                          'lecunits'=>$lecunit,
                                          'subjClass'=>1,
                                          'deleted'=>0,
                                          'createdby'=>auth()->user()->id,
                                          'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                                    ]);

                  if($levelid == null || $levelid == "" || 
                        $semesterID == null || $semesterID == "" || 
                              $courseid == null || $courseid == ""){
                                    return array((object)[
                                          'status'=>1,
                                          'message'=>'Subject added.'
                                    ]); 
                        
                  }


                  DB::table('college_prospectus')
                        ->insert([
                              'courseID'=>$courseid,
                              'subjectID'=>$subjectId,
                              'semesterID'=>$semesterID,
                              'yearID'=>$levelid,
                              'deleted'=>0,
                              'lecunits'=>$lecunit,
                              'labunits'=>$labunit,
                              'subjDesc'=>$subjdesc,
                              'subjCode'=>$subjcode,
                              'subjClass'=>1,
                              'curriculumID'=>$curriculumid,
                              'createdby'=>auth()->user()->id,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  return array((object)[
                        'status'=>1,
                        'message'=>'Subject added to prospectus.'
                  ]);
            

            }catch(\Exception $e){
                  return self::store_error($e);
            }
           

      }

      public static function add_subject_to_prospectus(Request $request){
            try{
                  $subjectid = $request->get('subjid');
                  $courseid = $request->get('courseid');
                  $curriculumid = $request->get('curriculumid');
                  $semesterID = $request->get('semid');
                  $levelid = $request->get('levelid');
                  $subjDesc = $request->get('subjDesc');
                  $subjCode = $request->get('subjCode');
                  $lecUnits = $request->get('lecUnits');
                  $labUnits = $request->get('labUnits');
                  $credUnits = $request->get('credUnits');
                  $subjGroup = $request->get('subjGroup');
                  $preReq = $request->get('prereq');

                  if($courseid == "" || $courseid == null || $curriculumid == "" || $curriculumid == null || $semesterID == "" || $semesterID == null || $levelid == "" || $levelid == null){
                        return array((object)[
                              'status'=>2,
                              'message'=>'No course selected!'
                        ]);
                  }

                  $check = DB::table('college_prospectus')
                              ->where('subjectID',$subjectid)
                              ->where('deleted',0)
                              ->where('courseID',$courseid)
                              ->where('curriculumID',$curriculumid)
                              ->count();

                  if($check > 0){
                        return array((object)[
                              'status'=>2,
                              'message'=>'Subject exists in prospectus.'
                        ]);
                  }

                  // $subject_info = DB::table('college_subjects')
                  //                   ->where('id',$subjectid)
                  //                   ->first();

                  $temp_id = DB::table('college_prospectus')
                        ->insertGetId([
                              'courseID'=>$courseid,
                              'subjectID'=>$subjectid,
                              'semesterID'=>$semesterID,
                              'yearID'=>$levelid,
                              'deleted'=>0,
                              'subjDesc'=>$subjDesc,
                              'subjCode'=>$subjCode,
                              'lecunits'=>$lecUnits,
                              'labunits'=>$labUnits,
                              'credunits'=>$credUnits,
                              'subjgroup'=>$subjGroup,
                              // 'lecunits'=>$subject_info->lecunits,
                              // 'labunits'=>$subject_info->labunits,
                              // 'subjDesc'=>$subject_info->subjDesc,
                              // 'subjCode'=>$subject_info->subjCode,
                              // 'subjClass'=>$subject_info->subjClass,
                              'curriculumID'=>$curriculumid,
                              'createdby'=>auth()->user()->id,
                              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

                  // Add prerequisites //new code
                  if ($preReq && is_array($preReq)) {
                        foreach ($preReq as $prereqId) {
                              DB::table('college_subjprereq')->insert([
                                    'subjid' => $temp_id,
                                    'prereqsubjID' => $prereqId,
                                    'deleted' => 0,
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);
                        }
                  }

                  $temp_subj_info = DB::table('college_prospectus')
                        ->where('id',$temp_id)
                        ->select(
                              'id',
                              'courseID',
                              'subjectID',
                              'semesterID',
                              'yearID',
                              'lecunits',
                              'labunits',
                              'subjDesc',
                              'subjCode',
                              'subjClass',
                              'curriculumID',
                              'psubjsort',
                              DB::raw("CONCAT(college_prospectus.subjCode,' - ',college_prospectus.subjDesc) as text")
                        )
                        ->first();

                  return array((object)[
                        'status'=>1,
                        'message'=>'Subject added to prospectus.',
                        'data'=>$temp_subj_info
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }
      }

      public static function delete_prospectus(Request $request){

            try{

                  $id = $request->get('id');
                  $curriculumid = $request->get('curriculumid');


                  $check = DB::table('college_prospectus')
                              ->join('college_classsched',function($join){
                                    $join->on('college_prospectus.id','=','college_classsched.subjectID');
                                    $join->where('college_classsched.deleted',0);
                              })
                              ->join('college_loadsubject',function($join){
                                    $join->on('college_classsched.id','=','college_loadsubject.schedid');
                                    $join->where('college_loadsubject.deleted',0);
                              })
                              ->where('college_prospectus.id',$id)
                              ->where('college_prospectus.curriculumID',$curriculumid)
                              ->where('college_prospectus.deleted',0)
                              ->count();

                  if($check > 0){
                        return array((object)[
                              'status'=>2,
                              'message'=>'Contains Enrolled students'
                        ]);
                  }

                  
                  DB::table('college_prospectus')
                        ->where('id',$id)
                        ->where('curriculumID',$curriculumid)
                        ->take(1)
                        ->update([
                              'deleted'=>1,
                              'deletedby'=>auth()->user()->id,
                              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);

              
                  return array((object)[
                        'status'=>1,
                        'message'=>'Subject Deleted'
                  ]);

            }catch(\Exception $e){
                  return self::store_error($e);
            }

      }

      public static function subjectremove(Request $request){

            try{

                  $subjid = $request->get('subjid');

                  $check_usage = DB::table('college_prospectus')
                                    ->where('subjectID',$subjid)
                                    ->where('deleted',0)
                                    ->count();

                  if($check_usage > 0){
                        return array((object)[
                              'status'=>0,
                              'message'=>'Subject is already used in prospectus!'
                        ]);
                  }



                  DB::table('college_subjects')
                        ->where('id',$subjid)
                        ->take(1)
                        ->update([
                              'deleted'=>1,
                              'deletedby'=>auth()->user()->id,
                              'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);
                
                  return array((object)[
                        'status'=>1,
                        'message'=>'Deleted Successfully!'
                  ]);
                  

            }catch(\Exception $e){
                  return self::store_error($e);
            }
            
      }
//working v2
//       public function updateSubject(Request $request) {     
          
//             try {
//                   $check_sched = DB::table('college_classsched')
//                   ->where('subjectID',$request['id'])
//                   ->where('deleted',0)
//                   ->count();


//             if($check_sched > 0){
//                   return array((object)[
//                         'status'=>0,
//                         'message'=>'Unable to update. Subject is already used.'
//                   ]);


// }

//             DB::table('college_subjects')
//                     ->where('id', $request['id'])
//                     ->update([
//                         'subjDesc' => $request['subj_desc'],
//                         'subjCode' => $request['subj_code'],
//                         'labunits' => $request['labunit']
//             ]);
        
//                 return response()->json(['success' => true]);
//             } catch (\Exception $e) {
//                 return response()->json(['success' => false, 'message' => 'Update failed: ' . $e->getMessage()], 500);
//             }
//         }

        public function updateSubject(Request $request) {
            $labunit = $request['labunit'];
            $subjdesc = $request['subj_desc'];
            $subjcode = $request['subj_code'];

            $check_sched = DB::table('college_classsched')
                  ->where('subjectID', $request['id'])
                  ->where('deleted', 0)
                  ->count();



            if ($check_sched > 0) {
                  // return array((object)[
                  //       'status' => 0,
                  //       'message' => 'Unable to update. Subject is already used.'
                  // ]);
                  return response()->json(['success' => false, 'message' => 'Subject is already used.']);
            }

            $check = DB::table('college_subjects')
                        ->where('subjDesc',$subjdesc)
                        ->where('subjCode',$subjcode)
                        ->where('id','!=',$request['id'])
                        
                        // ->where('labunits',$labunit)
                        // ->where('lecunits',$lecunit)
                        ->where('deleted',0);
            if($labunit == 1){
                  $check = $check->where('labunits','1.0')->count();
            }else{
                  $check = $check->where('labunits','0.0')->count();
            }

            if($check > 0){
                  return array((object)[
                        'status'=>2,
                        'message'=>'Subject already exist.'
                  ]);
            }       
            DB::table('college_subjects')
                  ->where('id', $request['id'])
                  ->update([
                        'subjDesc' => $request['subj_desc'],
                        'subjCode' => $request['subj_code'],
                        'labunits' => $request['labunit']
                  ]);

            return response()->json(['success' => true]);
        }



        public function getSubjectById(Request $request) {
            return response()->json(
                DB::table('college_subjects')
                    ->where('id', $request->route('id'))
                    ->where('deleted', 0)
                    ->first()
            );
        }


        public function importSubjects(Request $request)
        {
            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
        
            $rows = $sheet->toArray(null, true, true, true); // A, B, C...
        
            // Get courseID and curriculumID
            $courseId = $sheet->getCell('D1')->getValue();
            $curriculumId = $sheet->getCell('D2')->getValue();
        
            // Mapping
            $yearMap = [
                '1ST YEAR' => 17,
                '2ND YEAR' => 18,
                '3RD YEAR' => 19,
                '4TH YEAR' => 20,
                '5TH YEAR' => 21,
            ];
        
            $semMap = [
                '1ST SEMESTER' => 1,
                '2ND SEMESTER' => 2,
                'SUMMER SEMESTER' => 3,
            ];
        
            $currentYearID = null;
            $currentSemID = null;
            $startParsing = false;
        
            foreach ($rows as $rowNumber => $row) {
                if ($rowNumber < 5) continue;
        
                $firstCell = trim($row['A'] ?? '');
        
                // Detect semester section headers
                if (preg_match('/(\d+(ST|ND|RD|TH)) YEAR\s*-\s*(.*?)\s*SEMESTER/i', strtoupper($firstCell), $matches)) {
                    $yearKey = strtoupper($matches[1]) . ' YEAR';
                    $semKey = strtoupper(trim($matches[3])) . ' SEMESTER';
        
                    $currentYearID = $yearMap[$yearKey] ?? null;
                    $currentSemID = $semMap[$semKey] ?? null;
        
                    $startParsing = false;
                    continue;
                }
        
                // Detect column headers
                if (strtoupper($row['A'] ?? '') === 'SORT' && strtoupper($row['B'] ?? '') === 'CODE') {
                    $startParsing = true;
                    continue;
                }
        
                // Stop on total
                if (strtoupper($firstCell) === 'TOTAL') {
                    $startParsing = false;
                    continue;
                }
        
                // Process subject row
                if ($startParsing && !empty($row['B'])) {
                    $subjCode = trim($row['B']);
                    $subjDesc = trim($row['C']);
        
                    // ✅ Check for existing subject
                    $existingSubject = DB::table('college_subjects')
                                        ->where('subjCode', $subjCode)
                                        ->where('subjDesc', $subjDesc)
                                        ->where('deleted', 0)
                                        ->first();
        
                    if ($existingSubject) {
                        $subjectId = $existingSubject->id;
                    } else {
                        // Insert new subject
                        $subjectId = DB::table('college_subjects')->insertGetId([
                            'subjCode' => $subjCode,
                            'subjDesc' => $row['C'] ?? null,
                            'lecunits' => $row['E'] ?? 0,
                            'labunits' => $row['F'] ?? 0,
                        ]);
                    }
                    // Check if subject already exists in prospectus
                    $existingProspectus = DB::table('college_prospectus')
                                        ->where('courseID', $courseId)
                                        ->where('curriculumID', $curriculumId)
                                        ->where('yearID', $currentYearID)
                                        ->where('semesterID', $currentSemID)
                                        ->where('subjectID', $subjectId)
                                        ->first();

                    if (!$existingProspectus) {
                        // Insert into prospectus
                        DB::table('college_prospectus')->insert([
                            'courseID'     => $courseId,
                            'curriculumID' => $curriculumId,
                            'yearID'       => $currentYearID,
                            'semesterID'   => $currentSemID,
                            'subjectID'    => $subjectId,
                            'subjCode'     => $subjCode,
                            'subjDesc'     => $row['C'] ?? null,
                            'lecunits'     => $row['E'] ?? 0,
                            'labunits'     => $row['F'] ?? 0,
                            'credunits'    => $row['G'] ?? 0,
                        ]);
                    }
        
                }
            }
        
            return response()->json(['message' => 'Curriculum subjects imported successfully!']);
        }
        

      
        public function export(Request $request)
        {
            $acadprogid = $request->get('acadprogid');
            $courseid = $request->get('courseid');
            $curriculumid = $request->get('curriculumid');
        
            // Get subjects data
            $subjectsData = $this->curriculum_subjects_201($courseid, $curriculumid);
            $subject_list = $subjectsData[0]['subjects'];
        
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Set document properties
            $spreadsheet->getProperties()
                ->setCreator("Your System")
                ->setTitle("Academic Prospectus")
                ->setDescription("Curriculum subjects by year level and semester");
        
            // Get academic structure data
            $yearlevel = DB::table('gradelevel')
                ->where('acadprogid', $acadprogid)
                ->where('deleted', 0)
                ->select('id', 'levelname as text')
                ->get();
            
            $semester = DB::table('semester')
                ->where('deleted', 0)
                ->select('id', 'semester as text')
                ->get();
        
            // Write course and curriculum on first row
            $course = DB::table('college_courses')
                ->where('id', $courseid)
                ->value('courseDesc');
            
            $curriculum = DB::table('college_curriculum')
                ->where('id', $curriculumid)
                ->value('curriculumname');
        
            $sheet->mergeCells('A1:C1');
            $sheet->getCell('A1')->setValue('COURSE : '.$course);
            $sheet->getCell('D1')->setValue($courseid);
            $style = $sheet->getStyle('A1:C1');
            $style->getFont()->setBold(true);
        
            $sheet->mergeCells('A2:C2');
            $sheet->getCell('A2')->setValue('CURRICULUM : '.$curriculum);
            $sheet->getCell('D2')->setValue($curriculumid);
            $sheet->getStyle('A2:C2')->getFont()->setBold(true);
            $style = $sheet->getStyle('A2:C2');
            $style->getFont()->setBold(true);
                
            // Starting row
            $currentRow = 5;
            
            // Set column widths for main structure (A-G)
            $sheet->getColumnDimension('A')->setWidth(5);  // Sort
            $sheet->getColumnDimension('B')->setWidth(10); // Code
            $sheet->getColumnDimension('C')->setWidth(50); // Subject Description
            $sheet->getColumnDimension('D')->setWidth(15); // Prerequisite
            $sheet->getColumnDimension('E')->setWidth(5);  // Lect.
            $sheet->getColumnDimension('F')->setWidth(5);  // Lab.
            $sheet->getColumnDimension('G')->setWidth(5);  // Total
        
            // Set column widths for all subjects list (H-J)
            // $sheet->getColumnDimension('J')->setWidth(10); // Subject Code
            // $sheet->getColumnDimension('K')->setWidth(50); // Subject Description
        
            // Add headers for all subjects list
            // $sheet->setCellValue('J4', 'ALL SUBJECTS');
            // $sheet->mergeCells('J4:L4');
            // $sheet->getStyle('J4')->getFont()->setBold(true);
            
            // $sheet->setCellValue('J5', 'Code');
            // $sheet->setCellValue('K5', 'Description');
            // $sheet->setCellValue('L5', 'Lab Units');
            // $sheet->setCellValue('M5', 'Lec Units');
        
            // $style = $sheet->getStyle('J5:K5');
            // $style->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            $sheet->getStyle('J5:K5')->getFont()->setBold(true);

            foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G', 'J', 'K', 'L', 'M'] as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
      
        
            // Get all available subjects
            // $allSubjects = $this->available_subject2($request);
        
            // Process each year level and semester for main structure (A-G)
            foreach ($yearlevel as $yl) {
                $yl->text = str_replace(' COLLEGE', '', $yl->text);
                
                foreach ($semester as $sem) {
                    // Filter subjects for this year level and semester
                    $temp_subjects = collect($subject_list)->where('yearID', $yl->id)->where('semesterID', $sem->id)->values()->all();
        
                    // Calculate totals
                    $lecunits = 0;
                    $labunits = 0;
                    foreach ($temp_subjects as $subject) {
                        $lecunits += floatval($subject->lecunits);
                        $labunits += floatval($subject->labunits);
                    }
        
                    // Add header for year level and semester
                    $headerText = ($acadprogid == 8) 
                        ? str_replace('HE', '', $yl->text) . ' - ' . $sem->text
                        : $yl->text . ' - ' . $sem->text;
                    
                    $sheet->setCellValue('A'.$currentRow, strtoupper(trim($headerText)) . ' Semester');
                    $sheet->getStyle('A'.$currentRow)->getFont()->setBold(true)->setSize(12);
                    $sheet->mergeCells('A'.$currentRow.':G'.$currentRow);
                    $currentRow++;
                    
                    // Add table headers
                    $headers = ['Sort', 'Code', 'Subject Description', 'Prerequisite', 'Lect.', 'Lab.', 'Total'];
                    $sheet->fromArray($headers, null, 'A'.$currentRow);
                    
                    // Style headers
                    $headerStyle = [
                        'font' => ['bold' => true],
                        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 
                                  'color' => ['rgb' => 'D9D9D9']],
                        'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
                    ];
                    $sheet->getStyle('A'.$currentRow.':G'.$currentRow)->applyFromArray($headerStyle);
                    $currentRow++;
        
                    if (empty($temp_subjects)) {
                        // Add 10 empty rows when no subjects available
                        for ($i = 0; $i < 10; $i++) {
                            $rowData = ['', '', '', '', '', '', ''];
                            $sheet->fromArray($rowData, null, 'A'.$currentRow);
                            $currentRow++;
                        }
                    } else {
                        // Add subjects if available
                        foreach ($temp_subjects as $subject) {
                            $totalUnits = $subject->lecunits + $subject->labunits;
                            
                            $rowData = [
                                $subject->psubjsort ?? '',
                                $subject->subjCode,
                                $subject->subjDesc,
                                $prereq = collect($subjectsData[0]['prereq'])->where('subjID', $subject->id)->pluck('prereqsubjID')->toArray(),
                                $subject->lecunits,
                                $subject->labunits,
                                $totalUnits
                            ];
                            
                            $sheet->fromArray($rowData, null, 'A'.$currentRow);
                            $currentRow++;
                        }
                    }
                    
                    // Add total row
                    $sheet->setCellValue('D'.$currentRow, 'Total');
                    $sheet->setCellValue('E'.$currentRow, $lecunits);
                    $sheet->setCellValue('F'.$currentRow, $labunits);
                    $sheet->setCellValue('G'.$currentRow, $lecunits + $labunits);
                    
                    // Style total row
                    $totalStyle = [
                        'font' => ['bold' => true],
                        'borders' => ['top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE]]
                    ];
                    $sheet->getStyle('A'.$currentRow.':G'.$currentRow)->applyFromArray($totalStyle);
                    
                    $currentRow += 2; // Add space between sections
                }
            }
        
            // Add all subjects list starting at column H, row 5
            // $subjectListRow = 5;
            // foreach ($allSubjects as $subject) {
            //     $subjectListRow++;
            //     $sheet->setCellValue('J'.$subjectListRow, $subject->subjCode);
            //     $sheet->setCellValue('K'.$subjectListRow, $subject->subjDesc);
            //     $style = $sheet->getStyle('J'.$subjectListRow.':K'.$subjectListRow);
            //     $style->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            //     $sheet->setCellValue('L'.$subjectListRow, $subject->labunits);
            //     $sheet->setCellValue('M'.$subjectListRow, $subject->lecunits);
            // }

        
            // Style the all subjects list
            // $sheet->getStyle('H5:J'.$subjectListRow)->getBorders()
            //     ->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            
            // Create writer and output
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $filename = 'curriculum_subjects_'.date('Ymd_His').'.xlsx';
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            
            $writer->save('php://output');
            exit;
        }
        
      
      public function available_subject2(Request $request)
      {

            $all_subjects = DB::table('college_subjects')
            ->leftJoin('setup_subjgroups',function($join){
                  $join->on('college_subjects.subjgroup','=','setup_subjgroups.id');
                  $join->where('setup_subjgroups.deleted',0);
            })
            ->orderBy('college_subjects.subjDesc', 'asc')
            ->select(
                  'college_subjects.subjDesc',
                  'college_subjects.subjCode',
                  'college_subjects.labunits',
                  'college_subjects.lecunits',
                  'subjgroup',
                  'college_subjects.id',
                  'subjgroup',
                  'setup_subjgroups.description'
            )
            ->where('college_subjects.deleted',0)
             
            ->get();

            foreach($all_subjects as $item){
                  $usageinfo = DB::table('college_prospectus')
                                    ->where('deleted',0)
                                    ->where('subjectID',$item->id)
                                    ->select(
                                          'courseid'
                                    )
                                    ->get();

                  $item->usageinfo = $usageinfo;
            }

            return $all_subjects;
      }

      

        
        

      public static function available_subject(Request $request){

            $search = $request->get('search');
            $search = isset($search['value']) ? $search['value'] : '';
            $checkusage = $request->get('checkusage');
            $filter_usage = $request->get('filter_usage');
            


            if($checkusage){
                  $subjectsinpros = DB::table('college_subjects')
                                    ->join('college_prospectus',function($join){
                                          $join->on('college_subjects.id','=','college_prospectus.subjectID');
                                          $join->where('college_prospectus.deleted',0);
                                    })
                                    ->where('college_subjects.deleted',0)
                                    ->select(
                                          'college_subjects.id'
                                    )
                                    ->get();

                  $subjnotused = DB::table('college_subjects')
                                    ->where('college_subjects.deleted',0)
                                    ->whereNotIn('id',collect( $subjectsinpros)->pluck('id'))
                                    ->select(
                                          'college_subjects.id'
                                    )
                                    ->get();
            }




            $all_subjects = DB::table('college_subjects')
                              ->when($search != '', function($query) use ($search) {
                                    $query->where(function($q) use ($search) {
                                    $q->orWhere('subjDesc', 'like', '%' . $search . '%')
                                          ->orWhere('subjCode', 'like', '%' . $search . '%');
                                    });
                              })
                              ->leftJoin('setup_subjgroups',function($join){
                                    $join->on('college_subjects.subjgroup','=','setup_subjgroups.id');
                                    $join->where('setup_subjgroups.deleted',0);
                              })
                              ->orderBy('college_subjects.subjDesc', 'asc')
                              ->take($request->get('length'))
                              ->skip($request->get('start'));

            if($checkusage){
                  if($filter_usage == 1){
                        $all_subjects = $all_subjects->whereIn('college_subjects.id',collect($subjectsinpros)->pluck('id'));
                  }elseif($filter_usage == 2){
                        $all_subjects = $all_subjects->whereIn('college_subjects.id',collect($subjnotused)->pluck('id'));
                  }
            }
            

            $all_subjects = $all_subjects->select(
                                    'college_subjects.subjDesc',
                                    'college_subjects.subjCode',
                                    'college_subjects.labunits',
                                    'college_subjects.lecunits',
                                    'subjgroup',
                                    'college_subjects.id',
                                    'subjgroup',
                                    'setup_subjgroups.description'
                              )
                              ->where('college_subjects.deleted',0)
                              ->get();

            

            $all_subjects_count = DB::table('college_subjects')
                              ->where(function($query) use($search){
                                    $query->orWhere('subjDesc','like','%'.$search.'%');
                                    $query->orWhere('subjCode','like','%'.$search.'%');
                              })
                              ->leftJoin('setup_subjgroups',function($join){
                                    $join->on('college_subjects.subjgroup','=','setup_subjgroups.id');
                                    $join->where('setup_subjgroups.deleted',0);
                              });

            if($checkusage){
                  if($filter_usage == 1){
                        $all_subjects_count = $all_subjects_count->whereIn('college_subjects.id',collect($subjectsinpros)->pluck('id'));
                  }elseif($filter_usage == 2){
                        $all_subjects_count = $all_subjects_count->whereIn('college_subjects.id',collect($subjnotused)->pluck('id'));
                  }
            }

            $all_subjects_count = $all_subjects_count->select('college_subjects.id')
                              ->where('college_subjects.deleted',0)
                              ->count();

            if($checkusage){
                  foreach($all_subjects as $item){
                        $usageinfo = DB::table('college_prospectus')
                                          ->where('deleted',0)
                                          ->where('subjectID',$item->id)
                                          ->select(
                                                'courseid'
                                          )
                                          ->get();

                        $item->usageinfo = $usageinfo;
                  }
                
            }


            return @json_encode((object)[
                  'data'=>$all_subjects,
                  'recordsTotal'=>$all_subjects_count,
                  'recordsFiltered'=>$all_subjects_count
            ]);

            // return $all_subjects;



      }


      //curiculum
     
      public static function store_error($e){
            DB::table('zerrorlogs')
            ->insert([
                        'error'=>$e,
                        'createdby'=>auth()->user()->id,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);
            return array((object)[
                  'status'=>0,
                  'message'=>'Something went wrong!'
            ]);
      }

      public static function subjects(Request $request){

            $subj = DB::table('college_subjects')
                              ->where('deleted',0)
                              ->select(
                                    'subjDesc as desc',
                                    'id as id',
                                    'subjCode as code',
                                    'labunits',
                                    'lecunits'
                              )
                              ->get();
                 
                  return response()->json($subj);                
                  
      }

  

      public static function curriculum_subjects_201($courseid = '', $curriculumid = '', $schedule = ''){

            // Fetch subject groups
            $subjectGroups = DB::table('setup_subjgroups')
                              ->where('deleted', 0)
                              ->select('id', 'description as subject_group_name')
                              ->get();

            $subjects = Db::table('college_prospectus')
                              ->where('college_prospectus.deleted',0)
                              ->where('college_prospectus.courseID',$courseid)
                              ->where('college_prospectus.curriculumID',$curriculumid)
                              ->leftJoin('setup_subjgroups', 'college_prospectus.subjgroup', '=', 'setup_subjgroups.id')
                              ->select(
                                    'college_prospectus.id',
                                    'college_prospectus.courseID',
                                    'college_prospectus.subjectID',
                                    'college_prospectus.semesterID',
                                    'college_prospectus.yearID',
                                    'college_prospectus.lecunits',
                                    'college_prospectus.labunits',
                                    'college_prospectus.credunits',
                                    'college_prospectus.subjDesc',
                                    'college_prospectus.subjCode',
                                    'college_prospectus.subjgroup',
                                    'college_prospectus.subjClass',
                                    'college_prospectus.curriculumID',
                                    'college_prospectus.psubjsort',
                                    'setup_subjgroups.description as subject_group_name',
                                    DB::raw("CONCAT(college_prospectus.subjCode,' - ',college_prospectus.subjDesc) as text")
                              )
                              ->get();

            
            $prereq = Db::table('college_prospectus')
                              ->where('college_prospectus.deleted',0)
                              ->where('college_prospectus.courseID',$courseid)
                              ->where('college_prospectus.curriculumID',$curriculumid)
                              ->join('college_subjprereq',function($join){
                                    $join->on('college_prospectus.id','=','college_subjprereq.subjID');
                                    $join->where('college_subjprereq.deleted',0);
                              })
                              ->selecT(
                                    'subjID',
                                    'prereqsubjID'
                              )
                              ->get();



            return array([
                  'subjects'=>$subjects,
                  'prereq'=>$prereq,
                  'subjectGroups'=>$subjectGroups
            ]);

      }


      // public static function curriculum_subjects_201($courseid = '', $curriculumid = '', $schedule = ''){

      //       // $courseid = $request->get('courseid');
      //       // $curriculumid = $request->get('curriculumid');

      //       $subjects = Db::table('college_prospectus')
      //                         ->where('deleted',0)
      //                         ->where('courseID',$courseid)
      //                         ->where('curriculumID',$curriculumid)
      //                         ->select(
      //                               'id',
      //                               'courseID',
      //                               'subjectID',
      //                               'semesterID',
      //                               'yearID',
      //                               'lecunits',
      //                               'labunits',
      //                               'credunits',
      //                               'subjDesc',
      //                               'subjCode',
      //                               'subjgroup',
      //                               'subjClass',
      //                               'curriculumID',
      //                               'psubjsort',
      //                               DB::raw("CONCAT(college_prospectus.subjCode,' - ',college_prospectus.subjDesc) as text")
      //                         )
      //                         ->get();

            
      //       $prereq = Db::table('college_prospectus')
      //                         ->where('college_prospectus.deleted',0)
      //                         ->where('college_prospectus.courseID',$courseid)
      //                         ->where('college_prospectus.curriculumID',$curriculumid)
      //                         ->join('college_subjprereq',function($join){
      //                               $join->on('college_prospectus.id','=','college_subjprereq.subjID');
      //                               $join->where('college_subjprereq.deleted',0);
      //                         })
      //                         ->selecT(
      //                               'subjID',
      //                               'prereqsubjID'
      //                         )
      //                         ->get();



      //       return array([
      //             'subjects'=>$subjects,
      //             'prereq'=>$prereq
      //       ]);

      // }


   
    
}
