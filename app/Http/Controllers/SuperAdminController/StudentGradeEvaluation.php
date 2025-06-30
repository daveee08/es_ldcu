<?php

namespace App\Http\Controllers\SuperAdminController;

use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

class StudentGradeEvaluation extends \App\Http\Controllers\Controller
{
      public static function schoolinfo()
      {
            return DB::table('schoolinfo')->first()->abbreviation;
      }

      public static function grades_detail(Request $request)
      {

            $studid = $request->get('studid');
            $levelid = $request->get('levelid');
            $syid = $request->get('syid');

            $grades_detail = DB::table('gradesdetail')
                  ->where('studid', $studid)
                  ->join('grades', function ($join) {
                        $join->on('gradesdetail.headerid', '=', 'grades.id');
                        $join->where('grades.deleted', 0);
                  });

            if ($levelid == 14 || $levelid == 15) {
                  $grades_detail = $grades_detail->join('sh_subjects', function ($join) {
                        $join->on('grades.subjid', '=', 'sh_subjects.id');
                        $join->where('sh_subjects.deleted', 0);
                  })
                        ->select('subjtitle as subjdesc', 'sh_subj_sortid as subjsor');
            } else {
                  $grades_detail = $grades_detail->join('subjects', function ($join) {
                        $join->on('grades.subjid', '=', 'subjects.id');
                        $join->where('subjects.deleted', 0);
                  })
                        ->select('subjdesc', 'subj_sortid as subjsor');
            }

            $grades_detail = $grades_detail->join('teacher', function ($join) {
                  $join->on('grades.createdby', '=', 'teacher.id');
                  $join->where('subjects.deleted', 0);
            });

            $grades_detail = $grades_detail->addSelect(
                  'quarter',
                  'gradesdetail.*',
                  'grades.semid'
            )
                  ->orderBy('subjsor')
                  ->get();

            return $grades_detail;

      }


      public static function students(Request $request)
      {

            $syid = $request->get('syid');
            $semid = $request->get('syid');
            return \App\Models\SuperAdmin\SuperAdminData::enrollment_record($syid, $semid);

      }

      public static function sf9(Request $request)
      {

            $studid = $request->get('studid');
            $syid = $request->get('syid');

            $studentInfo = \App\Models\Principal\SPP_EnrolledStudent::getStudent(null, null, $studid, null);
            $studentInfo = $studentInfo[0]->count == 0 ? SPP_EnrolledStudent::getStudent(null, null, $studid, null, 5) : $studentInfo;
            $student = $studentInfo[0]->data[0];
            $acad = $studentInfo[0]->data[0]->acadprogid;
            $strand = $studentInfo[0]->data[0]->strandid;


            $subjects = \App\Models\Principal\SPP_Subject::getSubject(null, null, null, $student->ensectid, null, null, null, null, 'sf9', $syid)[0]->data;

            //mcs
            if ($acad != 5) {

                  $temp_subject = array();

                  foreach ($subjects as $item) {
                        array_push($temp_subject, $item);
                  }

                  array_push($temp_subject, (object) [
                        'id' => 'TLE1',
                        'subjdesc' => 'COMPUTER / HELE',
                        "inMAPEH" => 0,
                        "teacherid" => 14,
                        "inSF9" => 1,
                        "mapeh" => 0,
                        "inTLE" => 0,
                        "semid" => 1,
                        "subj_per" => 0,
                        "subj_sortid" => "3T0"
                  ]);

                  array_push($temp_subject, (object) [
                        'id' => 'M1',
                        'subjdesc' => 'MAPEH',
                        "inMAPEH" => 0,
                        "teacherid" => 14,
                        "inSF9" => 1,
                        "mapeh" => 0,
                        "inTLE" => 0,
                        "semid" => 1,
                        "subj_per" => 0,
                        "subj_sortid" => "2M0"
                  ]);

                  $subjects = $temp_subject;

            }



            $studgrades = \App\Models\Grades\GradesData::student_grades_detail($syid, null, $student->ensectid, null, $studid, $student->levelid, $strand, null, $subjects);
            $studgrades = \App\Models\Grades\GradesData::get_finalrating($studgrades, $acad);
            $finalgrade = \App\Models\Grades\GradesData::general_average($studgrades);
            $finalgrade = \App\Models\Grades\GradesData::get_finalrating($finalgrade, $acad);


            return $studgrades;

      }

      public static function subjsetup(Request $request)
      {

            $studid = $request->get('studid');
            $syid = $request->get('syid');

            $studentInfo = \App\Models\Principal\SPP_EnrolledStudent::getStudent(null, null, $studid, null);
            $studentInfo = $studentInfo[0]->count == 0 ? App\Models\Principal\SPP_EnrolledStudent::getStudent(null, null, $studid, null, 5) : $studentInfo;
            $student = $studentInfo[0]->data[0];
            $acad = $studentInfo[0]->data[0]->acadprogid;
            $strand = $studentInfo[0]->data[0]->strandid;

            $subjects_setup = \App\Models\Principal\SPP_Subject::getSubject(null, null, null, $student->ensectid, null, null, null, null, 'sf9', $syid)[0]->data;

            foreach ($subjects_setup as $item) {
                  $setup = Db::table('gradessetup')
                        ->where('subjid', $item->id)
                        ->where('levelid', $student->levelid)
                        ->get();

                  $item->setup = $setup;

            }

            return $subjects_setup;



      }

      public static function sf9_subjects($levelid = null, $syid = null, $isforsp = true)
      {

            $schoolinfo = self::schoolinfo();
            if ($syid == 2 && $schoolinfo == 'SMA') {
                  $subjects = DB::table('subjects')
                        ->where('subjects.deleted', 0)
                        ->join('gradessetup', function ($join) use ($levelid) {
                              $join->on('subjects.id', '=', 'gradessetup.subjid');
                              $join->where('gradessetup.deleted', 0);
                              $join->where('gradessetup.levelid', $levelid);
                        })
                        ->where('syid', $syid)
                        ->where('inSF9', 1)
                        ->select(
                              'subjects.id as subjid',
                              'subjects.id',
                              'subjdesc',
                              'subjcode',
                              'first',
                              'second',
                              'third',
                              'fourth',
                              'subjCom',
                              'subj_per',
                              'subj_sortid as sortid',
                              'isVisible',
                              'isCon',
                              'isSP',
                              'inSF9'
                        )
                        ->distinct('subjid')
                        ->orderBy('subj_sortid')
                        ->get();


                  foreach ($subjects as $item) {
                        if ($item->subjCom != null) {
                              $item->mapeh = 1;
                              $item->inTLE = 1;
                              $item->inMAPEH = 1;
                        } else {
                              $item->mapeh = 0;
                              $item->inTLE = 0;
                              $item->inMAPEH = 0;
                        }
                  }

                  return collect($subjects)->unique('subjid')->values();

            } else {
                  $subjects = \App\Http\Controllers\SuperAdminController\SubjectPlotController::list(null, null, $levelid, null, $syid, null, null, array(), $isforsp);

                  $final_subject = array();

                  foreach ($subjects as $item) {
                        if ($item->inSF9 == 1) {
                              $item->first = 1;
                              $item->second = 1;
                              ;
                              $item->third = 1;
                              $item->fourth = 1;

                              $item->id = $item->subjid;

                              if ($item->subjCom != null) {
                                    $item->mapeh = 1;
                                    $item->inTLE = 1;
                                    $item->inMAPEH = 1;
                              } else {
                                    $item->mapeh = 0;
                                    $item->inTLE = 0;
                                    $item->inMAPEH = 0;
                              }

                              array_push($final_subject, $item);
                        } else {
                              $item->first = 1;
                              $item->second = 1;
                              ;
                              $item->third = 1;
                              $item->fourth = 1;

                              $item->id = $item->subjid;

                              if ($item->subjCom != null) {
                                    $item->mapeh = 1;
                                    $item->inTLE = 1;
                                    $item->inMAPEH = 1;
                              } else {
                                    $item->mapeh = 0;
                                    $item->inTLE = 0;
                                    $item->inMAPEH = 0;
                              }

                              array_push($final_subject, $item);
                        }
                  }

                  return $final_subject;
            }


      }

      public static function sf9_subjects_sh($levelid = null, $strand = null, $semid = null, $syid = null)
      {

            $schoolinfo = self::schoolinfo();

            if ($syid == 2 && $schoolinfo == 'SMA') {

                  $core = DB::table('sh_subjects')
                        ->where('sh_subjects.deleted', 0)
                        ->join('gradessetup', function ($join) use ($levelid) {
                              $join->on('sh_subjects.id', '=', 'gradessetup.subjid');
                              $join->where('gradessetup.deleted', 0);
                              $join->where('gradessetup.levelid', $levelid);
                        })
                        ->leftJoin('sh_subjstrand', function ($join) {
                              $join->on('sh_subjects.id', '=', 'sh_subjstrand.subjid');
                              $join->where('sh_subjstrand.deleted', 0);
                        });

                  if ($semid != null) {
                        $core = $core->where('sh_subjects.semid', $semid);
                  }

                  $core = $core->where('type', 1)
                        ->where('inSF9', 1)
                        ->select(
                              'sh_subjects.id as subjid',
                              'sh_subjects.id',
                              'subjtitle as subjdesc',
                              'subjcode',
                              'first',
                              'second',
                              'third',
                              'fourth',
                              'type',
                              'sh_subj_sortid as sortid',
                              'sh_subjstrand.strandid',
                              'sh_subjects.semid',
                              'sh_isSP as isSP',
                              'sh_isCon as isCon',
                              'sh_subjCom as subjCom',
                              'sh_isVisible as isVisible',
                              'inSF9'
                        )
                        ->distinct('subjdesc')
                        ->get();




                  foreach ($core as $key => $item) {
                        if ($item->strandid != null) {
                              unset($core[$key]);
                        }
                  }

                  $strand_subj = DB::table('sh_subjects')
                        ->where('sh_subjects.deleted', 0)
                        ->join('gradessetup', function ($join) use ($levelid) {
                              $join->on('sh_subjects.id', '=', 'gradessetup.subjid');
                              $join->where('gradessetup.deleted', 0);
                              $join->where('gradessetup.levelid', $levelid);
                        })
                        ->join('sh_subjstrand', function ($join) use ($strand) {
                              $join->on('sh_subjects.id', '=', 'sh_subjstrand.subjid');
                              $join->where('sh_subjstrand.deleted', 0);
                              $join->where('sh_subjstrand.strandid', $strand);
                        });

                  if ($semid != null) {
                        $strand_subj = $strand_subj->where('sh_subjects.semid', $semid);
                  }

                  $strand_subj = $strand_subj->where('inSF9', 1)
                        ->select(
                              'sh_subjects.id as subjid',
                              'sh_subjects.id',
                              'subjtitle as subjdesc',
                              'subjcode',
                              'first',
                              'second',
                              'third',
                              'type',
                              'fourth',
                              'sh_subj_sortid as sortid',
                              'sh_subjstrand.strandid',
                              'sh_subjects.semid',
                              'sh_isSP as isSP',
                              'sh_isCon as isCon',
                              'sh_isVisible as isVisible',
                              'sh_subjCom as subjCom',
                              'inSF9'
                        )
                        ->distinct('subjdesc')
                        ->get();

                  $subjects = [];
                  foreach ($core as $item) {
                        array_push($subjects, $item);
                  }

                  foreach ($strand_subj as $item) {
                        array_push($subjects, $item);
                  }
            } else {
                  $subjects = \App\Http\Controllers\SuperAdminController\SubjectPlotController::list(null, null, $levelid, null, $syid, $semid, $strand);


                  $final_subject = array();

                  foreach ($subjects as $item) {
                        $item->first = 1;
                        $item->second = 1;
                        $item->third = 1;
                        $item->fourth = 1;
                        $item->id = $item->subjid;
                        $item->isVisible = 1;
                        array_push($final_subject, $item);
                  }

                  return $subjects;
            }




            return $subjects;

      }


      public static function sf9_grades_request(Request $request)
      {

            $levelid = $request->get('levelid');
            $studid = $request->get('studid');
            $syid = $request->get('syid');
            $strand = $request->get('strandid');
            $semid = $request->get('semid');
            $sectionid = $request->get('section');

            $grading_version = \App\Models\Grading\GradingSystem::checkVersion();

            if ($grading_version->version == 'v2' && $syid == 2) {
                  return self::sf9_grades_gv2($levelid, $studid, $syid, $strand, $semid, $sectionid);
            } else {

                  return self::sf9_grades($levelid, $studid, $syid, $strand, $semid, $sectionid);
            }


      }

      public static function sf9_grades($levelid = null, $studid = null, $syid = null, $strand = null, $semid = null, $sectionid = null, $sf9 = false, $quarter = null)
      {

            // $grades = DB::table('gradesdetail')
            //                   ->where('studid',$studid)
            //                   ->where('syid',$syid)
            //                   ->join('grades',function($join) use ($semid,$sectionid,$sf9){
            //                         $join->on('gradesdetail.headerid','=','grades.id');
            //                         $join->where('grades.deleted',0);
            //                         $join->where('grades.sectionid',$sectionid);
            //                         if(auth()->user()->type == 7 || auth()->user()->type == 9){
            //                              $join->where('gradesdetail.gdstatus',4);
            //                         }else{
            //                               if($sf9){
            //                                     $join->where(function($join_query){
            //                                           $join_query->where('gradesdetail.gdstatus',2);
            //                                           $join_query->orWhere('gradesdetail.gdstatus',4);
            //                                     });
            //                               }else{
            //                                     $join->where(function($join_query){
            //                                           $join_query->orWhere('gradesdetail.gdstatus',3);
            //                                           $join_query->orWhere('gradesdetail.gdstatus',2);
            //                                           $join_query->orWhere('gradesdetail.gdstatus',4);
            //                                           $join_query->orWhere('gradesdetail.gdstatus',1);
            //                                     });
            //                               }
            //                         }
            //                         if($semid != null){
            //                             $join->where('grades.semid',$semid);
            //                         }
            //                   })
            //                   ->select(
            //                         'subjid',
            //                         'qg',
            //                         'quarter',
            //                         'gradesdetail.gdstatus as status',
            //                         'gradesdetail.id'
            //                   )
            //                   ->get();




            $grades = DB::table('grades')
                  ->select(
                        'subjid',
                        'qg',
                        'quarter',
                        'gradesdetail.gdstatus as status',
                        'gradesdetail.id',
                        'semid'
                  )
                  ->where('syid', $syid)
                  ->where('sectionid', $sectionid)
                  ->where('grades.deleted', 0)
                  ->join('gradesdetail', function ($join) use ($studid, $sf9) {
                        $join->on('grades.id', '=', 'gradesdetail.headerid');
                        $join->where('gradesdetail.studid', $studid);
                        if (auth()->user()->type == 7 || auth()->user()->type == 9) {
                              $join->where('gradesdetail.gdstatus', 4);
                        } else {
                              if ($sf9) {
                                    $join->where(function ($join_query) {
                                          $join_query->whereIn('gradesdetail.gdstatus', [2, 4]);
                                    });
                              } else {
                                    $join->where(function ($join_query) {
                                          $join_query->whereIn('gradesdetail.gdstatus', [1, 2, 3, 4]);
                                    });
                              }
                        }
                  });

            if ($semid != null) {
                  $grades = $grades->where('grades.semid', $semid);
            }
            if ($quarter != null) {
                  $grades = $grades->where('grades.quarter', $quarter);
            }

            $grades = $grades->get();

            $temp_grades = array();


            $student_special_class = DB::table('student_specsubj')
                  ->where('deleted', 0)
                  ->where('levelid', $levelid)
                  ->where('studid', $studid)
                  ->get();

            $student_exclsubj = DB::table('student_exclsubj')
                  ->where('deleted', 0)
                  ->where('levelid', $levelid)
                  ->where('studid', $studid)
                  ->get();

            $student_quarter = DB::table('student_quarter')
                  ->where('syid', $syid)
                  ->where('studid', $studid)
                  ->first();


            if (isset($student_quarter->id) > 0) {
                  if ($levelid == 14 || $levelid == 15) {
                        $check_enrollmentstatus = DB::table('sh_enrolledstud')
                              ->where('syid', $syid)
                              ->where('deleted', 0)
                              ->where('studid', $studid)
                              ->whereIn('studstatus', [3, 5, 6])
                              ->count();
                  } else {
                        $check_enrollmentstatus = DB::table('enrolledstud')
                              ->where('syid', $syid)
                              ->where('deleted', 0)
                              ->where('studid', $studid)
                              ->whereIn('studstatus', [3, 5, 6])
                              ->count();
                  }

                  if ($check_enrollmentstatus > 0) {
                        $temp_grades = array();
                        foreach ($grades as $item) {
                              if ($student_quarter->quarter1 == 1 && $item->quarter == 1) {
                                    array_push($temp_grades, $item);
                              } elseif ($student_quarter->quarter2 == 1 && $item->quarter == 2) {
                                    array_push($temp_grades, $item);
                              } elseif ($student_quarter->quarter3 == 1 && $item->quarter == 3) {
                                    array_push($temp_grades, $item);
                              } elseif ($student_quarter->quarter4 == 1 && $item->quarter == 4) {
                                    array_push($temp_grades, $item);
                              }
                        }

                        $grades = $temp_grades;
                  }
            }

            if (count($student_special_class) > 0) {

                  $student_special_grades = DB::table('grades')
                        ->select(
                              'semid',
                              'subjid',
                              'qg',
                              'quarter',
                              'gradesdetail.gdstatus as status',
                              'gradesdetail.id'
                        )
                        ->where('syid', $syid)
                        ->whereIn('levelid', collect($student_special_class)->pluck('levelid'))
                        ->where('grades.deleted', 0)
                        ->join('gradesdetail', function ($join) use ($studid, $sf9) {
                              $join->on('grades.id', '=', 'gradesdetail.headerid');
                              $join->where('gradesdetail.studid', $studid);
                              if (auth()->user()->type == 7 || auth()->user()->type == 9) {
                                    $join->where('gradesdetail.gdstatus', 4);
                              } else {
                                    if ($sf9) {
                                          $join->where(function ($join_query) {
                                                $join_query->whereIn('gradesdetail.gdstatus', [2, 4]);
                                          });
                                    } else {
                                          $join->where(function ($join_query) {
                                                $join_query->whereIn('gradesdetail.gdstatus', [1, 2, 3, 4]);
                                          });
                                    }
                              }
                        });

                  if ($semid != null) {
                        $student_special_grades = $student_special_grades->where('grades.semid', $semid);
                  }
                  if ($quarter != null) {
                        $student_special_grades = $student_special_grades->where('grades.quarter', $quarter);
                  }

                  $student_special_grades = $student_special_grades->get();

            } else {
                  $student_special_grades = array();
            }

            foreach ($grades as $item) {
                  if ($item->qg == 0 || $item->qg == 60) {
                        $item->qg = 60;
                  }
                  array_push($temp_grades, $item);
            }

            $grades = DB::table('grades_tranf')
                  ->where('studid', $studid)
                  ->where('syid', $syid)
                  ->where('sectionid', $sectionid)
                  ->where('deleted', 0)
                  ->select(
                        'semid',
                        'subjid',
                        'qg',
                        'quarter',
                        'grades_tranf.gdstatus as status'
                  )
                  ->get();

            foreach ($grades as $item) {
                  $check = collect($temp_grades)->where('subjid', $item->subjid)->where('quarter', $item->quarter)->count();
                  $item->id = null;
                  if ($check > 0) {
                        $temp_grades = collect($temp_grades)->values()->map(function ($map_grade, $key) use ($item) {
                              if ($map_grade->subjid == $item->subjid && $map_grade->quarter == $item->quarter) {
                                    $map_grade->qg = $item->qg;
                              }
                              return $map_grade;
                        })->toArray();
                  } else {
                        array_push($temp_grades, $item);
                  }

            }

            foreach ($temp_grades as $item) {
                  $check_sp_grades = collect($student_special_grades)
                        ->where('levelid', $levelid)
                        ->where('subjid', $item->subjid)
                        ->where('quarter', $item->quarter)
                        ->first();

                  if (isset($check_sp_grades->qg)) {
                        $item->qg = $check_sp_grades->qg;
                  }
            }


            foreach ($student_special_grades as $item) {

                  $check_sp_grades = collect($temp_grades)
                        ->where('levelid', $levelid)
                        ->where('subjid', $item->subjid)
                        ->where('quarter', $item->quarter)
                        ->first();

                  if (!isset($check_sp_grades->qg)) {
                        array_push($temp_grades, $item);
                  }
            }

            $grades = $temp_grades;

            if ($levelid == 14 || $levelid == 15) {
                  $subjects = self::sf9_subjects_sh($levelid, $strand, $semid, $syid);


                  if (collect($student_exclsubj)->count() > 0) {
                        $temp_subj = array();
                        foreach ($subjects as $item) {
                              $check = collect($student_exclsubj)->where('subjid', $item->subjid)->count();

                              if ($check == 0) {
                                    array_push($temp_subj, $item);
                              }
                        }
                        $subjects = $temp_subj;
                  }

                  if (collect($student_special_class)->count() > 0) {

                        $additional_subj = DB::table('sh_subjects')
                              ->where('id', collect($student_special_class)->pluck('subjid'))
                              ->select(
                                    'sh_subjects.id',
                                    'subjtitle as subjdesc',
                                    'subjcode',
                                    'inSF9',
                                    'type',
                                    'sh_subjCom as subjCom',
                                    'sh_subjects.sh_isVisible as isVisble'

                              )
                              ->get();

                        $subjects = collect($subjects)->toArray();

                        foreach ($additional_subj as $item) {
                              $item->semid = collect($student_special_class)->where('subjid', $item->id)->first()->semid;
                              $item->first = 1;
                              $item->second = 1;
                              $item->third = 1;
                              $item->fourth = 1;
                              $item->isVisible = 1;
                              $item->strandid = $strand;
                              array_push($subjects, $item);
                        }
                  }

                  $enrollment = DB::table('sh_enrolledstud')
                        ->where('syid', $syid)
                        ->where('deleted', 0)
                        ->where('studid', $studid)
                        ->whereIn('studstatus', [1, 2, 4])
                        ->select(
                              'semid'
                        )
                        ->get();

                  // return $enrollment;

                  $enroll_sem1 = collect($enrollment)->where('semid', 1)->count() > 0 ? true : false;
                  $enroll_sem2 = collect($enrollment)->where('semid', 2)->count() > 0 ? true : false;

                  foreach ($grades as $item) {
                        if ($item->semid == 1 && !$enroll_sem1) {
                              $item->qg = null;
                              $item->quarter = null;
                        } else if ($item->semid == 2 && !$enroll_sem2) {
                              $item->qg = null;
                              $item->quarter = null;
                        }
                  }

                  return self::generate_sh_grade($subjects, $grades, $studid, $syid, $semid);
            } else {

                  $isforsp = false;

                  $sectioninfo = DB::table('sectiondetail')
                        ->where('syid', $syid)
                        ->where('sectionid', $sectionid)
                        ->where('deleted', 0)
                        ->first();

                  if ($sectioninfo->sd_issp == 1) {
                        $isforsp = true;
                  }


                  $subjects = self::sf9_subjects($levelid, $syid, $isforsp);

                  if (collect($student_exclsubj)->count() > 0) {
                        $temp_subj = array();
                        foreach ($subjects as $item) {
                              $check = collect($student_exclsubj)->where('subjid', $item->subjid)->count();

                              if ($check == 0) {
                                    array_push($temp_subj, $item);
                              }
                        }
                        $subjects = $temp_subj;
                  }


                  $schoolinfo = DB::table('schoolinfo')->select('abbreviation')->first();

                  if (collect($student_special_class)->count() > 0) {

                        $additional_subj = DB::table('subjects')
                              ->where('id', collect($student_special_class)->pluck('subjid'))
                              ->select(
                                    'id',
                                    'subjdesc',
                                    'subjcode',
                                    'isSP',
                                    'isCon',
                                    'subjCom',
                                    'isVisible',
                                    'inSF9',
                                    'subj_per'

                              )
                              ->get();

                        $subjects = collect($subjects)->toArray();

                        foreach ($additional_subj as $item) {
                              $item->semid = collect($student_special_class)->where('subjid', $item->id)->first()->semid;
                              $item->first = 1;
                              $item->second = 1;
                              $item->third = 1;
                              $item->fourth = 1;
                              $item->isVisible = 1;
                              $item->strandid = $strand;
                              array_push($subjects, $item);
                        }
                  }

                  if ($schoolinfo->abbreviation == strtoupper('HCHS CP')) {
                        $final_subject = array();
                        foreach ($subjects as $item) {
                              if ($sectionid == 4 || $sectionid == 6 || $sectionid == 10 || $sectionid == 4) {
                                    array_push($final_subject, $item);
                              } else {
                                    if (
                                          $item->id == 10 || //grade 7
                                          $item->id == 11 || //grade 7
                                          $item->id == 12 || //grade 7
                                          $item->id == 48 || //grade 7
                                          $item->id == 22 || //grade 8
                                          $item->id == 24 || //grade 8
                                          $item->id == 47 || //grade 8
                                          $item->id == 34 || //grade 9
                                          $item->id == 35 || //grade 9
                                          $item->id == 36 || //grade 9
                                          $item->id == 46    //grade 9
                                    ) {

                                    } else {
                                          array_push($final_subject, $item);
                                    }

                              }
                        }
                        $subjects = $final_subject;

                  }

                  return self::generate_grade($subjects, $grades, $studid, $syid, $levelid);
            }

      }


      public static function sf9_grades_gv2($levelid = null, $studid = null, $syid = null, $strand = null, $semid = null, $sectionid = null)
      {

            $fgrades = array();

            $acad = DB::table('gradelevel')->where('id', $levelid)->select('acadprogid')->first()->acadprogid;

            if ($acad == 4) {
                  $temp_grades = DB::table('grading_system_grades_hs')
                        ->where('grading_system_grades_hs.syid', $syid)
                        ->where('grading_system_grades_hs.studid', $studid)
                        ->where('grading_system_grades_hs.deleted', 0)
                        ->join('grading_sytem_gradestatus', function ($join) {
                              $join->on('grading_system_grades_hs.sectionid', '=', 'grading_sytem_gradestatus.sectionid');
                              $join->on('grading_system_grades_hs.syid', '=', 'grading_sytem_gradestatus.syid');
                              $join->on('grading_system_grades_hs.subjid', '=', 'grading_sytem_gradestatus.subjid');
                              $join->where('grading_sytem_gradestatus.deleted', 0);

                        })
                        ->select(
                              'qgq1',
                              'qgq2',
                              'qgq3',
                              'qgq4',
                              'grading_system_grades_hs.subjid',
                              'grading_system_grades_hs.id',
                              'q1status',
                              'q2status',
                              'q3status',
                              'q4status'
                        )
                        ->get();
            } elseif ($acad == 2) {

                  $temp_grades = DB::table('grading_system_grades_psper')
                        ->where('grading_system_grades_psper.syid', $syid)
                        ->where('grading_system_grades_psper.studid', $studid)
                        ->where('grading_system_grades_psper.deleted', 0)
                        ->join('grading_sytem_gradestatus', function ($join) {
                              $join->on('grading_system_grades_psper.sectionid', '=', 'grading_sytem_gradestatus.sectionid');
                              $join->on('grading_system_grades_psper.syid', '=', 'grading_sytem_gradestatus.syid');
                              $join->on('grading_system_grades_psper.subjid', '=', 'grading_sytem_gradestatus.subjid');
                              $join->where('grading_sytem_gradestatus.deleted', 0);

                        })
                        ->select(
                              'qgq1',
                              'qgq2',
                              'qgq3',
                              'qgq4',
                              'grading_system_grades_psper.subjid',
                              'grading_system_grades_psper.id',
                              'q1status',
                              'q2status',
                              'q3status',
                              'q4status'
                        )
                        ->get();
            } elseif ($acad == 3) {
                  $temp_grades = DB::table('grading_system_gsgrades')
                        ->where('grading_system_gsgrades.syid', $syid)
                        ->where('grading_system_gsgrades.studid', $studid)
                        ->where('grading_system_gsgrades.deleted', 0)
                        ->join('grading_sytem_gradestatus', function ($join) {
                              $join->on('grading_system_gsgrades.sectionid', '=', 'grading_sytem_gradestatus.sectionid');
                              $join->on('grading_system_gsgrades.syid', '=', 'grading_sytem_gradestatus.syid');
                              $join->on('grading_system_gsgrades.subjid', '=', 'grading_sytem_gradestatus.subjid');
                              $join->where('grading_sytem_gradestatus.deleted', 0);

                        })
                        ->select(
                              'qgq1',
                              'qgq2',
                              'qgq3',
                              'qgq4',
                              'grading_system_gsgrades.subjid',
                              'grading_system_gsgrades.id',
                              'q1status',
                              'q2status',
                              'q3status',
                              'q4status'
                        )
                        ->get();
            } elseif ($acad == 5) {
                  $temp_grades = DB::table('grading_system_grades_sh')
                        ->where('grading_system_grades_sh.syid', $syid)
                        ->where('grading_system_grades_sh.studid', $studid)
                        ->where('grading_system_grades_sh.deleted', 0)
                        ->join('grading_sytem_gradestatus', function ($join) {
                              $join->on('grading_system_grades_sh.sectionid', '=', 'grading_sytem_gradestatus.sectionid');
                              $join->on('grading_system_grades_sh.syid', '=', 'grading_sytem_gradestatus.syid');
                              $join->on('grading_system_grades_sh.subjid', '=', 'grading_sytem_gradestatus.subjid');
                              $join->where('grading_sytem_gradestatus.deleted', 0);

                        })
                        ->select(
                              'grading_system_grades_sh.semid',
                              'qgq1',
                              'qgq2',
                              'grading_system_grades_sh.subjid',
                              'grading_system_grades_sh.id',
                              'q1status',
                              'q2status',
                              'q3status',
                              'q4status'
                        )
                        ->get();

                  foreach ($temp_grades as $item) {
                        if ($item->semid == 2) {
                              $item->qgq3 = $item->qgq1;
                              $item->qgq4 = $item->qgq2;
                              $item->q3status = $item->q1status;
                              $item->q4status = $item->q2status;
                        } else {
                              $item->qgq3 = null;
                              $item->qgq4 = null;
                        }
                  }

            }

            foreach ($temp_grades as $item) {
                  if ($item->q1status == 3) {
                        $item->q1status = 4;
                  } else if ($item->q1status == 4) {
                        $item->q1status = 3;
                  }

                  if ($item->q2status == 3) {
                        $item->q2status = 4;
                  } else if ($item->q2status == 4) {
                        $item->q2status = 3;
                  }

                  if ($item->q3status == 3) {
                        $item->q3status = 4;
                  } else if ($item->q3status == 4) {
                        $item->q3status = 3;
                  }

                  if ($item->q4status == 3) {
                        $item->q4status = 4;
                  } else if ($item->q4status == 4) {
                        $item->q4status = 3;
                  }
                  array_push($fgrades, (object) [
                        'id' => $item->id,
                        'subjid' => $item->subjid,
                        'quarter' => 1,
                        'status' => $item->q1status,
                        'qg' => $item->q1status != null ? number_format($item->qgq1) : null,
                  ]);
                  array_push($fgrades, (object) [
                        'id' => $item->id,
                        'subjid' => $item->subjid,
                        'quarter' => 2,
                        'status' => $item->q2status,
                        'qg' => $item->q2status != null ? number_format($item->qgq2) : null,
                  ]);
                  array_push($fgrades, (object) [
                        'id' => $item->id,
                        'subjid' => $item->subjid,
                        'quarter' => 3,
                        'status' => $item->q3status,
                        'qg' => $item->q3status != null ? number_format($item->qgq3) : null,
                  ]);
                  array_push($fgrades, (object) [
                        'id' => $item->id,
                        'subjid' => $item->subjid,
                        'quarter' => 4,
                        'status' => $item->q4status,
                        'qg' => $item->q4status != null ? number_format($item->qgq4) : null,
                  ]);
            }

            $grades = $fgrades;

            if ($levelid == 14 || $levelid == 15) {
                  $subjects = self::sf9_subjects_sh($levelid, $strand, $semid, $syid);
                  return self::generate_sh_grade($subjects, $grades, $studid, $syid);
            } else {
                  $subjects = self::sf9_subjects($levelid, $syid);
                  return self::generate_grade($subjects, $grades, $studid, $syid);
            }

      }


      public static function check_award(
            $grade = null,
            $quarter = null,
            $lowest = null,
            $syid = null,
            $levelid = null
      ) {
            $award = '';

            $schoolinfo = DB::table('schoolinfo')->first();

            $award_setup_all = DB::table('grades_ranking_setup')
                  ->where('deleted', 0)
                  ->where('syid', $syid)
                  ->where('levelid', $levelid)
                  ->select(
                        'id',
                        'award',
                        'gto',
                        'gfrom'
                  )
                  ->get();

            if (count($award_setup_all) == 0) {
                  return response()->json([
                        'status' => 'warning',
                        'message' => 'No Setup Available'
                  ]);
            }
            $award_setup = collect($award_setup_all)->where('award', '!=', 'lowest grade')->values();
            $award_setup = collect($award_setup)->where('award', '!=', 'base grade')->values();
            $lowest_setup = collect($award_setup_all)->where('award', 'lowest grade')->first();
            $base_setup = collect($award_setup_all)->where('award', 'base grade')->first();

            foreach ($award_setup as $item) {
                  if (isset($base_setup->id)) {
                        if ($base_setup->gto == 1) {
                              if (number_format($grade, 3) >= number_format($item->gfrom, 2) && number_format($grade, 3) <= number_format($item->gto, 2)) {
                                    $award = $item->award;
                              }
                        } else if ($base_setup->gfrom == 1) {
                              if (number_format($grade) >= number_format($item->gfrom, 2) && number_format($grade) <= number_format($item->gto, 2)) {
                                    $award = $item->award;
                              }
                        } else {
                              if (number_format($grade, 3) >= number_format($item->gfrom, 2) && number_format($grade, 3) <= number_format($item->gto, 2)) {
                                    $award = $item->award;
                              }
                        }
                  }
            }

            if (isset($lowest_setup->gto) && $quarter != null) {
                  if ($lowest < $lowest_setup->gto) {
                        $award = '';
                  }
            }
            return $award;
            // if($schoolinfo->abbreviation == 'zps'){
            //       if( number_format($grade,3) >= 90 && number_format($grade,3) <= 94.999){
            //             $award = 'With Honors';
            //       }
            //       else if( number_format($grade,3) >= 95 && number_format($grade,3) <= 97.999){
            //             $award = 'With High Honors';
            //       }
            //       else if( number_format($grade,3) >= 98 && number_format($grade,3) <= 100){
            //             $award = 'With Highest Honors';
            //       }
            // }
            // else if(strtoupper($schoolinfo->abbreviation) == 'HCB'){
            //       if( number_format($grade,3) >= 90 && number_format($grade,3) <= 94.999){
            //             $award = 'With Honors';
            //       }
            //       else if( number_format($grade,3) >= 95 && number_format($grade,3) <= 97.999){
            //             $award = 'With High Honors';
            //       }
            //       else if( number_format($grade,3) >= 98 && number_format($grade,3) <= 100){
            //             $award = 'With Highest Honors';
            //       }
            //       else if( number_format($grade,3) >= 85 && number_format($grade,3) <= 89.99){
            //             $award = 'Commendable';
            //       }
            //       if($quarter == 1 && $lowest < 85){
            //             $award = '';
            //       }elseif($quarter == 2 && $lowest < 86){
            //             $award = '';
            //       }elseif($quarter == 3 && $lowest <= 87){
            //             $award = '';
            //       }elseif($quarter == 4 && $lowest <= 88){
            //             $award = '';
            //       }
            // }
            // else if(strtoupper($schoolinfo->abbreviation) == 'SPCT'){
            //       if( number_format($grade,3) >= 90 && number_format($grade,3) <= 94.999){
            //             $award = 'With Honors';
            //       }
            //       else if( number_format($grade,3) >= 95 && number_format($grade,3) <= 97.999){
            //             $award = 'With High Honors';
            //       }
            //       else if( number_format($grade,3) >= 98 && number_format($grade,3) <= 100){
            //             $award = 'With Highest Honors';
            //       }
            // }
            // else if(strtoupper($schoolinfo->abbreviation) == 'GBBC'){
            //       if( number_format($grade) >= 90 && number_format($grade) <= 94.999){
            //             $award = 'With Honor';
            //       }
            //       else if( number_format($grade) >= 95 && number_format($grade) <= 97.999){
            //             $award = 'With High Honor';
            //       }
            //       else if( number_format($grade) >= 98 && number_format($grade) <= 100){
            //             $award = 'With Highest Honor';
            //       }
            // }else if( strtoupper($schoolinfo->abbreviation) == 'HCHS CP'){
            //       if( ( number_format($grade) >= 90 && number_format($grade) <= 94.999 )){
            //             $award = 'With Honor';
            //       }
            //       else if( ( number_format($grade) >= 95 && number_format($grade) <= 97.999 )){
            //             $award = 'With High Honor';
            //       }
            //       else if( ( number_format($grade) >= 98 && number_format($grade) <= 100 )){
            //             $award = 'With Highest Honor';
            //       }
            //       if($lowest < 85){
            //           $award = '';
            //       }
            // }
            // else{
            //       if( number_format($grade,3) >= 90 && number_format($grade,3) <= 92.999){
            //             $award = 'With Honors';
            //       }
            //       if( number_format($grade,3) >= 93 && number_format($grade,3) <= 94.999){
            //             $award = 'With High Honors';
            //       }
            //       if( number_format($grade,3) >= 95 && number_format($grade,3) <= 100){
            //             $award = 'With Highest Honors';
            //       }
            //       if(  number_format($grade,3) >= $min_dist && number_format($grade,3) <= $max_dist){
            //             $award = 'With Distinction';
            //       }
            //       if(  number_format($grade,3) >= $min_sp && number_format($grade,3) <= $max_sp){
            //             $award = 'Special Recognition';
            //       }



            // }

            // return $award;
      }


      public static function generate_grade($subjects = [], $grades = [], $studid = null, $syid = null, $levelid = null)
      {

            $schoolinfo = DB::table('schoolinfo')->first();

            foreach (collect($subjects)->where('isCon', 0) as $item) {

                  $tem_grades = collect($grades)->where('subjid', $item->id);
                  $item->q1status = $item->first == 1 ? isset($tem_grades->where('quarter', 1)->first()->status) ? $tem_grades->where('quarter', 1)->first()->status : null : null;
                  $item->q2status = $item->second == 1 ? isset($tem_grades->where('quarter', 2)->first()->status) ? $tem_grades->where('quarter', 2)->first()->status : null : null;
                  $item->q3status = $item->third == 1 ? isset($tem_grades->where('quarter', 3)->first()->status) ? $tem_grades->where('quarter', 3)->first()->status : null : null;
                  $item->q4status = $item->fourth == 1 ? isset($tem_grades->where('quarter', 4)->first()->status) ? $tem_grades->where('quarter', 4)->first()->status : null : null;

                  $item->q1gid = $item->first == 1 ? isset($tem_grades->where('quarter', 1)->first()->id) ? $tem_grades->where('quarter', 1)->first()->id : null : null;
                  $item->q2gid = $item->second == 1 ? isset($tem_grades->where('quarter', 2)->first()->id) ? $tem_grades->where('quarter', 2)->first()->id : null : null;
                  $item->q3gid = $item->third == 1 ? isset($tem_grades->where('quarter', 3)->first()->id) ? $tem_grades->where('quarter', 3)->first()->id : null : null;
                  $item->q4gid = $item->fourth == 1 ? isset($tem_grades->where('quarter', 4)->first()->id) ? $tem_grades->where('quarter', 4)->first()->id : null : null;

                  $item->q1 = $item->first == 1 ? isset($tem_grades->where('quarter', 1)->first()->qg) ? $tem_grades->where('quarter', 1)->first()->qg : null : null;
                  $item->q2 = $item->second == 1 ? isset($tem_grades->where('quarter', 2)->first()->qg) ? $tem_grades->where('quarter', 2)->first()->qg : null : null;
                  $item->q3 = $item->third == 1 ? isset($tem_grades->where('quarter', 3)->first()->qg) ? $tem_grades->where('quarter', 3)->first()->qg : null : null;
                  $item->q4 = $item->fourth == 1 ? isset($tem_grades->where('quarter', 4)->first()->qg) ? $tem_grades->where('quarter', 4)->first()->qg : null : null;
                  $item->quarter1 = $item->first == 1 ? isset($tem_grades->where('quarter', 1)->first()->qg) ? $tem_grades->where('quarter', 1)->first()->qg : null : null;
                  $item->quarter2 = $item->second == 1 ? isset($tem_grades->where('quarter', 2)->first()->qg) ? $tem_grades->where('quarter', 2)->first()->qg : null : null;
                  $item->quarter3 = $item->third == 1 ? isset($tem_grades->where('quarter', 3)->first()->qg) ? $tem_grades->where('quarter', 3)->first()->qg : null : null;
                  $item->quarter4 = $item->fourth == 1 ? isset($tem_grades->where('quarter', 4)->first()->qg) ? $tem_grades->where('quarter', 4)->first()->qg : null : null;

                  if (strtoupper($schoolinfo->abbreviation) == 'GBBC') {
                        if ($syid == 3) {

                              if ($item->id == 30 || $item->id == 31 || $item->id == 32) {
                                    $tem_grades = collect($grades)->where('subjid', 29);
                                    $item->q1status = $item->first == 1 ? isset($tem_grades->where('quarter', 1)->first()->status) ? $tem_grades->where('quarter', 1)->first()->status : null : null;
                                    $item->q2status = $item->second == 1 ? isset($tem_grades->where('quarter', 2)->first()->status) ? $tem_grades->where('quarter', 2)->first()->status : null : null;
                                    $item->q3status = $item->third == 1 ? isset($tem_grades->where('quarter', 3)->first()->status) ? $tem_grades->where('quarter', 3)->first()->status : null : null;
                                    $item->q4status = $item->fourth == 1 ? isset($tem_grades->where('quarter', 4)->first()->status) ? $tem_grades->where('quarter', 4)->first()->status : null : null;

                                    $item->q1gid = $item->first == 1 ? isset($tem_grades->where('quarter', 1)->first()->id) ? $tem_grades->where('quarter', 1)->first()->id : null : null;
                                    $item->q2gid = $item->second == 1 ? isset($tem_grades->where('quarter', 2)->first()->id) ? $tem_grades->where('quarter', 2)->first()->id : null : null;
                                    $item->q3gid = $item->third == 1 ? isset($tem_grades->where('quarter', 3)->first()->id) ? $tem_grades->where('quarter', 3)->first()->id : null : null;
                                    $item->q4gid = $item->fourth == 1 ? isset($tem_grades->where('quarter', 4)->first()->id) ? $tem_grades->where('quarter', 4)->first()->id : null : null;

                                    $item->q1 = $item->first == 1 ? isset($tem_grades->where('quarter', 1)->first()->qg) ? $tem_grades->where('quarter', 1)->first()->qg : null : null;
                                    $item->q2 = $item->second == 1 ? isset($tem_grades->where('quarter', 2)->first()->qg) ? $tem_grades->where('quarter', 2)->first()->qg : null : null;
                                    $item->q3 = $item->third == 1 ? isset($tem_grades->where('quarter', 3)->first()->qg) ? $tem_grades->where('quarter', 3)->first()->qg : null : null;
                                    $item->q4 = $item->fourth == 1 ? isset($tem_grades->where('quarter', 4)->first()->qg) ? $tem_grades->where('quarter', 4)->first()->qg : null : null;
                                    $item->quarter1 = $item->first == 1 ? isset($tem_grades->where('quarter', 1)->first()->qg) ? $tem_grades->where('quarter', 1)->first()->qg : null : null;
                                    $item->quarter2 = $item->second == 1 ? isset($tem_grades->where('quarter', 2)->first()->qg) ? $tem_grades->where('quarter', 2)->first()->qg : null : null;
                                    $item->quarter3 = $item->third == 1 ? isset($tem_grades->where('quarter', 3)->first()->qg) ? $tem_grades->where('quarter', 3)->first()->qg : null : null;
                                    $item->quarter4 = $item->fourth == 1 ? isset($tem_grades->where('quarter', 4)->first()->qg) ? $tem_grades->where('quarter', 4)->first()->qg : null : null;

                              }
                        }
                  }

            }

            foreach (collect($subjects)->where('isCon', 1) as $item) {

                  $schoolinfo = DB::table('schoolinfo')
                        ->select('abbreviation')
                        ->first();

                  if ($schoolinfo->abbreviation == 'SPCT' && $item->id == 9) {
                        $components = collect($subjects)->where('subjCom', $item->id)->values();
                        $item->spcon = 0;
                        $item->q1status = 4;
                        $item->q2status = 4;
                        $item->q3status = 4;
                        $item->q4status = 4;

                        $item->q1gid = 0;
                        $item->q2gid = 0;
                        $item->q3gid = 0;
                        $item->q4gid = 0;

                        $item->q1 = null;
                        $item->q2 = null;
                        $item->q3 = null;
                        $item->q4 = null;
                        $item->quarter1 = null;
                        $item->quarter2 = null;
                        $item->quarter3 = null;
                        $item->quarter4 = null;

                        //calculation
                        if (isset(collect($grades)->where('subjid', 7)->where('quarter', 1)->first()->qg)) {
                              $item->q1 = number_format(((collect($grades)->where('subjid', 7)->where('quarter', 1)->first()->qg * 2) + collect($grades)->where('subjid', 8)->where('quarter', 1)->first()->qg) / 3);
                              $item->quarter1 = number_format(((collect($grades)->where('subjid', 7)->where('quarter', 1)->first()->qg * 2) + collect($grades)->where('subjid', 8)->where('quarter', 1)->first()->qg) / 3);
                        }

                        if (isset(collect($grades)->where('subjid', 7)->where('quarter', 2)->first()->qg)) {
                              $item->q2 = number_format(((collect($grades)->where('subjid', 7)->where('quarter', 2)->first()->qg * 2) + collect($grades)->where('subjid', 8)->where('quarter', 2)->first()->qg) / 3);
                              $item->quarter2 = number_format(((collect($grades)->where('subjid', 7)->where('quarter', 2)->first()->qg * 2) + collect($grades)->where('subjid', 8)->where('quarter', 2)->first()->qg) / 3);
                        }

                        if (isset(collect($grades)->where('subjid', 7)->where('quarter', 3)->first()->qg)) {
                              $item->q3 = number_format(((collect($grades)->where('subjid', 7)->where('quarter', 3)->first()->qg * 2) + collect($grades)->where('subjid', 8)->where('quarter', 3)->first()->qg) / 3);
                              $item->quarter3 = number_format(((collect($grades)->where('subjid', 7)->where('quarter', 3)->first()->qg * 2) + collect($grades)->where('subjid', 8)->where('quarter', 3)->first()->qg) / 3);
                        }

                        if (isset(collect($grades)->where('subjid', 7)->where('quarter', 3)->first()->qg)) {
                              $item->q4 = number_format(((collect($grades)->where('subjid', 7)->where('quarter', 4)->first()->qg * 2) + collect($grades)->where('subjid', 8)->where('quarter', 4)->first()->qg) / 3);
                              $item->quarter4 = number_format(((collect($grades)->where('subjid', 7)->where('quarter', 4)->first()->qg * 2) + collect($grades)->where('subjid', 8)->where('quarter', 4)->first()->qg) / 3);
                        }


                  } else {

                        $components = collect($subjects)->where('subjCom', $item->id)->values();
                        $item->spcon = 0;
                        $with_percentage = collect($subjects)->where('subjCom', $item->id)->where('subj_per', '!=', 0)->count() > 0 ? true : false;
                        $with_sp = collect($subjects)->where('subjCom', $item->id)->where('isSP', 1)->count() > 0 ? true : false;

                        $tem_grades = collect($grades)->where('subjid', $item->id);

                        $item->q1status = 4;
                        $item->q2status = 4;
                        $item->q3status = 4;
                        $item->q4status = 4;

                        $item->q1gid = 0;
                        $item->q2gid = 0;
                        $item->q3gid = 0;
                        $item->q4gid = 0;

                        $item->q1 = null;
                        $item->q2 = null;
                        $item->q3 = null;
                        $item->q4 = null;
                        $item->quarter1 = null;
                        $item->quarter2 = null;
                        $item->quarter3 = null;
                        $item->quarter4 = null;

                        if ($with_percentage) {
                              $tle1 = 0;
                              $tle2 = 0;
                              $tle3 = 0;
                              $tle4 = 0;
                              $with_grade1 = true;
                              $with_grade2 = true;
                              $with_grade3 = true;
                              $with_grade4 = true;
                              $mapehcount = 0;

                              foreach ($components as $component_item) {
                                    $tle1 += $component_item->q1 * ($component_item->subj_per / 100);
                                    $tle2 += $component_item->q2 * ($component_item->subj_per / 100);
                                    $tle3 += $component_item->q3 * ($component_item->subj_per / 100);
                                    $tle4 += $component_item->q4 * ($component_item->subj_per / 100);
                              }

                              $item->q1 = number_format($tle1) != 0 ? number_format($tle1) : null;
                              $item->q2 = number_format($tle2) != 0 ? number_format($tle2) : null;
                              $item->q3 = number_format($tle3) != 0 ? number_format($tle3) : null;
                              $item->q4 = number_format($tle4) != 0 ? number_format($tle4) : null;
                              $item->quarter1 = number_format($tle1) != 0 ? number_format($tle1) : null;
                              $item->quarter2 = number_format($tle2) != 0 ? number_format($tle2) : null;
                              $item->quarter3 = number_format($tle3) != 0 ? number_format($tle3) : null;
                              $item->quarter4 = number_format($tle4) != 0 ? number_format($tle4) : null;


                        } elseif ($with_sp) {
                              $item->spcon = 1;
                              $specialized_subjects = DB::table('subjects_studspec')
                                    ->where('deleted', 0)
                                    ->where('studid', $studid)
                                    ->where('syid', $syid)
                                    ->get();

                              foreach ($specialized_subjects as $component_item) {

                                    $isSP = collect($subjects)->where('id', $component_item->subjid)->where('isSP', 1)->count() > 0 ? true : false;
                                    $gradeinfo = collect($grades)->where('subjid', $component_item->subjid);

                                    if ($component_item->q1 == 1 && $isSP) {
                                          if (isset(collect($gradeinfo)->where('quarter', 1)->first()->qg)) {
                                                $item->q1 = collect($gradeinfo)->where('quarter', 1)->first()->qg != null ? collect($gradeinfo)->where('quarter', 1)->first()->qg : null;
                                                $item->quarter1 = collect($gradeinfo)->where('quarter', 1)->first()->qg != null ? collect($gradeinfo)->where('quarter', 1)->first()->qg : null;
                                          }
                                    }

                                    if ($component_item->q2 == 1 && $isSP) {
                                          if (isset($gradeinfo->where('quarter', 2)->first()->qg)) {
                                                $item->q2 = collect($gradeinfo)->where('quarter', 2)->first()->qg != null ? collect($gradeinfo)->where('quarter', 2)->first()->qg : null;
                                                $item->quarter2 = collect($gradeinfo)->where('quarter', 2)->first()->qg != null ? collect($gradeinfo)->where('quarter', 2)->first()->qg : null;
                                          }
                                    }

                                    if ($component_item->q3 == 1 && $isSP) {
                                          if (isset(collect($gradeinfo)->where('quarter', 3)->first()->qg)) {
                                                $item->q3 = collect($gradeinfo)->where('quarter', 3)->first()->qg != null ? collect($gradeinfo)->where('quarter', 3)->first()->qg : null;
                                                $item->quarter3 = collect($gradeinfo)->where('quarter', 3)->first()->qg != null ? collect($gradeinfo)->where('quarter', 3)->first()->qg : null;
                                          }
                                    }


                                    if ($component_item->q4 == 1 && $isSP) {
                                          if (isset(collect($gradeinfo)->where('quarter', 4)->first()->qg)) {
                                                $item->q4 = collect($gradeinfo)->where('quarter', 4)->first()->qg != null ? collect($gradeinfo)->where('quarter', 4)->first()->qg : null;
                                                $item->quarter4 = collect($gradeinfo)->where('quarter', 4)->first()->qg != null ? collect($gradeinfo)->where('quarter', 4)->first()->qg : null;
                                          }
                                    }



                              }
                        } else {

                              $item->q1 = collect($components)->where('first', 1)->where('q1', null)->count() == 0 ? number_format(collect($components)->avg('q1')) : null;
                              $item->q2 = collect($components)->where('second', 1)->where('q2', null)->count() == 0 ? number_format(collect($components)->avg('q2')) : null;
                              $item->q3 = collect($components)->where('third', 1)->where('q3', null)->count() == 0 ? number_format(collect($components)->avg('q3')) : null;
                              $item->q4 = collect($components)->where('fourth', 1)->where('q4', null)->count() == 0 ? number_format(collect($components)->avg('q4')) : null;
                              $item->quarter1 = collect($components)->where('first', 1)->where('q1', null)->count() == 0 ? number_format(collect($components)->avg('q1')) : null;
                              $item->quarter2 = collect($components)->where('second', 1)->where('q2', null)->count() == 0 ? number_format(collect($components)->avg('q2')) : null;
                              $item->quarter3 = collect($components)->where('third', 1)->where('q3', null)->count() == 0 ? number_format(collect($components)->avg('q3')) : null;
                              $item->quarter4 = collect($components)->where('fourth', 1)->where('q4', null)->count() == 0 ? number_format(collect($components)->avg('q4')) : null;

                        }

                  }


            }

            foreach (collect($subjects)->where('isSP', 1) as $item) {
                  $specialized_subjects = DB::table('subjects_studspec')
                        ->where('deleted', 0)
                        ->where('subjid', $item->subjid)
                        ->where('studid', $studid)
                        ->where('syid', $syid)
                        ->first();

                  if (!isset($specialized_subjects)) {
                        $item->q1 = null;
                        $item->q2 = null;
                        $item->q3 = null;
                        $item->q4 = null;
                        $item->quarter1 = null;
                        $item->quarter2 = null;
                        $item->quarter3 = null;
                        $item->quarter4 = null;
                        $item->q1status = null;
                        $item->q2status = null;
                        $item->q3status = null;
                        $item->q4status = null;
                  }
            }


            $with_genave1 = true;
            $with_genave2 = true;
            $with_genave3 = true;
            $with_genave4 = true;

            foreach ($subjects as $item) {

                  $with_finalrating = true;
                  $subjcount = 0;
                  $temp_genave = 0;

                  if ($item->first == 1 && $item->subjCom == null) {
                        if ($item->q1 != null) {
                              $temp_genave += $item->q1;
                              $subjcount += 1;
                        } else {
                              if ($item->inSF9 == 1) {
                                    $with_finalrating = false;
                                    $with_genave1 = false;
                              }
                        }
                  }
                  if ($item->second == 1 && $item->subjCom == null) {
                        if ($item->q2 != null) {
                              $temp_genave += $item->q2;
                              $subjcount += 1;
                        } else {
                              if ($item->inSF9 == 1) {
                                    $with_finalrating = false;
                                    $with_genave2 = false;
                              }
                        }
                  }
                  if ($item->third == 1 && $item->subjCom == null) {
                        if ($item->q3 != null) {
                              $temp_genave += $item->q3;
                              $subjcount += 1;
                        } else {
                              if ($item->inSF9 == 1) {
                                    $with_finalrating = false;
                                    $with_genave3 = false;
                              }
                        }
                  }
                  if ($item->fourth == 1 && $item->subjCom == null) {
                        if ($item->q4 != null) {
                              $temp_genave += $item->q4;
                              $subjcount += 1;
                        } else {
                              if ($item->inSF9 == 1) {
                                    $with_finalrating = false;
                                    $with_genave4 = false;
                              }
                        }
                  }

                  if ($subjcount != 0) {
                        $genave = number_format($temp_genave / $subjcount);
                  } else {
                        $with_finalrating = false;
                  }



                  $item->finalrating = $with_finalrating ? $genave : null;
                  $item->actiontaken = $with_finalrating ? $genave >= 75 ? 'PASSED' : 'FAILED' : null;

                  if ($item->isSP == 1) {
                        //   return collect($item);
                        $with_finalrating = true;
                        $subjcount = 0;
                        if ($item->first == 1) {
                              if ($item->q1 != null) {
                                    $temp_genave += $item->q1;
                                    $subjcount += 1;
                              } else {
                                    $with_finalrating = false;
                              }
                        }
                        if ($item->second == 1) {
                              if ($item->q2 != null) {
                                    $temp_genave += $item->q2;
                                    $subjcount += 1;
                              } else {
                                    $with_finalrating = false;
                              }
                        }
                        if ($item->third == 1) {
                              if ($item->q3 != null) {
                                    $temp_genave += $item->q3;
                                    $subjcount += 1;
                              } else {
                                    $with_finalrating = false;
                              }
                        }
                        if ($item->fourth == 1) {
                              if ($item->q4 != null) {
                                    $temp_genave += $item->q4;
                                    $subjcount += 1;
                              } else {
                                    $with_finalrating = false;
                              }
                        }

                        if ($subjcount != 0) {
                              $genave = number_format($temp_genave / $subjcount);
                        } else {
                              $with_finalrating = false;
                        }

                        $item->finalrating = $with_finalrating ? $genave : null;
                        $item->actiontaken = $with_finalrating ? $genave >= 75 ? 'PASSED' : 'FAILED' : null;

                  }

            }


            // foreach(collect($subjects)->whereNotNull('subjCom',null)->values() as $item){
            //       $item->finalrating =  null;
            //       $item->actiontaken =  null;
            // }

            $with_finalrating = collect($subjects)->where('subjCom', null)->where('finalrating', null)->count() > 0 ? false : true;



            $temp_subj = array();

            $q1 = $with_genave1 ? collect($subjects)->where('inSF9', 1)->where('first', 1)->where('subjCom', null)->avg('q1') : null;
            $q2 = $with_genave2 ? collect($subjects)->where('inSF9', 1)->where('second', 1)->where('subjCom', null)->avg('q2') : null;
            $q3 = $with_genave3 ? collect($subjects)->where('inSF9', 1)->where('third', 1)->where('subjCom', null)->avg('q3') : null;
            $q4 = $with_genave4 ? collect($subjects)->where('inSF9', 1)->where('fourth', 1)->where('subjCom', null)->avg('q4') : null;

            $q1award = $with_genave1 ? self::check_award($q1, 1, collect($subjects)->where('inSF9', 1)->where('subjCom', null)->min('q1'), $syid, $levelid) : null;
            $q2award = $with_genave2 ? self::check_award($q2, 2, collect($subjects)->where('inSF9', 1)->where('subjCom', null)->min('q2'), $syid, $levelid) : null;
            $q3award = $with_genave3 ? self::check_award($q3, 3, collect($subjects)->where('inSF9', 1)->where('subjCom', null)->min('q3'), $syid, $levelid) : null;
            $q4award = $with_genave4 ? self::check_award($q4, 4, collect($subjects)->where('inSF9', 1)->where('subjCom', null)->min('q4'), $syid, $levelid) : null;

            $fr = $with_finalrating ? collect($subjects)->where('subjCom', null)->where('inSF9', 1)->avg('finalrating') : null;
            $fraward = $with_finalrating ? self::check_award(number_format($fr, 3), null, 100, $syid, $levelid) : null;

            $genave = (object) [
                  'subjdesc' => 'GENERAL AVERAGE',
                  'sortid' => 'ZZ',
                  'isVisible' => 1,
                  'subjCom' => null,
                  'id' => 'G1',
                  'subjid' => 'G1',
                  'q1status' => 1,
                  'q2status' => 1,
                  'q3status' => 1,
                  'q4status' => 1,
                  'q1gid' => 0,
                  'q2gid' => 0,
                  'q3gid' => 0,
                  'q4gid' => 0,
                  'q1' => $q1 != null ? number_format($q1) : null,
                  'q2' => $q2 != null ? number_format($q2) : null,
                  'q3' => $q3 != null ? number_format($q3) : null,
                  'q4' => $q4 != null ? number_format($q4) : null,
                  'q1award' => $q1 != null ? $q1award : null,
                  'q2award' => $q2 != null ? $q2award : null,
                  'q3award' => $q3 != null ? $q3award : null,
                  'q4award' => $q4 != null ? $q4award : null,
                  'q1comp' => $q1 != null ? number_format($q1, 3) : null,
                  'q2comp' => $q2 != null ? number_format($q2, 3) : null,
                  'q3comp' => $q3 != null ? number_format($q3, 3) : null,
                  'q4comp' => $q4 != null ? number_format($q4, 3) : null,
                  'quarter1' => $q1 != null ? number_format($q1) : null,
                  'quarter2' => $q2 != null ? number_format($q2) : null,
                  'quarter3' => $q3 != null ? number_format($q3) : null,
                  'quarter4' => $q4 != null ? number_format($q4) : null,
                  'finalrating' => $fr != null ? number_format($fr) : null,
                  'fcomp' => $fr != null ? number_format($fr, 3) : null,
                  'fraward' => $fraward,
                  'actiontaken' => $with_finalrating ? $fr != null ? $fr >= 75 ? 'PASSED' : 'FAILED' : null : null,
                  'lq1' => $q1 != null ? collect($subjects)->where('subjCom', null)->min('q1') : null,
                  'lq2' => $q2 != null ? collect($subjects)->where('subjCom', null)->min('q2') : null,
                  'lq3' => $q3 != null ? collect($subjects)->where('subjCom', null)->min('q3') : null,
                  'lq4' => $q4 != null ? collect($subjects)->where('subjCom', null)->min('q4') : null,
                  'lfr' => collect($subjects)->min('finalrating'),
                  'vr' => 5
            ];

            array_push($temp_subj, $genave);

            foreach ($subjects as $item) {
                  $item->ver = 'v5';
                  if ($item->isCon == 1 && $item->spcon == 0) {
                        $item->q1status = collect($subjects)->where('subjCom', $item->id)->where('q1status', 0)->count() > 0 ? 0 : 4;
                        $item->q2status = collect($subjects)->where('subjCom', $item->id)->where('q2status', 0)->count() > 0 ? 0 : 4;
                        $item->q3status = collect($subjects)->where('subjCom', $item->id)->where('q3status', 0)->count() > 0 ? 0 : 4;
                        $item->q4status = collect($subjects)->where('subjCom', $item->id)->where('q4status', 0)->count() > 0 ? 0 : 4;
                  }
                  array_push($temp_subj, $item);
            }

            $subjects = $temp_subj;

            return $subjects;

      }

      public static function generate_sh_grade($subjects = [], $grades = [], $studid = null, $syid = null, $semid = null)
      {

            $schoolinfo = DB::table('schoolinfo')->first();

            foreach (collect($subjects)->where('isCon', 0)->values() as $item) {

                  $tem_grades = collect($grades)->where('subjid', $item->id);
                  $item->q1status = $item->first == 1 ? isset($tem_grades->where('quarter', 1)->first()->status) ? $tem_grades->where('quarter', 1)->first()->status : null : null;
                  $item->q2status = $item->second == 1 ? isset($tem_grades->where('quarter', 2)->first()->status) ? $tem_grades->where('quarter', 2)->first()->status : null : null;
                  $item->q3status = $item->third == 1 ? isset($tem_grades->where('quarter', 3)->first()->status) ? $tem_grades->where('quarter', 3)->first()->status : null : null;
                  $item->q4status = $item->fourth == 1 ? isset($tem_grades->where('quarter', 4)->first()->status) ? $tem_grades->where('quarter', 4)->first()->status : null : null;

                  $item->q1gid = $item->first == 1 ? isset($tem_grades->where('quarter', 1)->first()->id) ? $tem_grades->where('quarter', 1)->first()->id : null : null;
                  $item->q2gid = $item->second == 1 ? isset($tem_grades->where('quarter', 2)->first()->id) ? $tem_grades->where('quarter', 2)->first()->id : null : null;
                  $item->q3gid = $item->third == 1 ? isset($tem_grades->where('quarter', 3)->first()->id) ? $tem_grades->where('quarter', 3)->first()->id : null : null;
                  $item->q4gid = $item->fourth == 1 ? isset($tem_grades->where('quarter', 4)->first()->id) ? $tem_grades->where('quarter', 4)->first()->id : null : null;

                  $item->q1 = $item->first == 1 ? isset($tem_grades->where('quarter', 1)->first()->qg) ? $tem_grades->where('quarter', 1)->first()->qg : null : null;
                  $item->q2 = $item->second == 1 ? isset($tem_grades->where('quarter', 2)->first()->qg) ? $tem_grades->where('quarter', 2)->first()->qg : null : null;
                  $item->q3 = $item->third == 1 ? isset($tem_grades->where('quarter', 3)->first()->qg) ? $tem_grades->where('quarter', 3)->first()->qg : null : null;
                  $item->q4 = $item->fourth == 1 ? isset($tem_grades->where('quarter', 4)->first()->qg) ? $tem_grades->where('quarter', 4)->first()->qg : null : null;
                  $item->quarter1 = $item->first == 1 ? isset($tem_grades->where('quarter', 1)->first()->qg) ? $tem_grades->where('quarter', 1)->first()->qg : null : null;
                  $item->quarter2 = $item->second == 1 ? isset($tem_grades->where('quarter', 2)->first()->qg) ? $tem_grades->where('quarter', 2)->first()->qg : null : null;
                  $item->quarter3 = $item->third == 1 ? isset($tem_grades->where('quarter', 3)->first()->qg) ? $tem_grades->where('quarter', 3)->first()->qg : null : null;
                  $item->quarter4 = $item->fourth == 1 ? isset($tem_grades->where('quarter', 4)->first()->qg) ? $tem_grades->where('quarter', 4)->first()->qg : null : null;

            }

            foreach (collect($subjects)->where('isCon', 1)->values() as $item) {

                  $components = collect($subjects)->where('subjCom', $item->id)->values();
                  $item->spcon = 0;
                  $with_percentage = collect($subjects)->where('subjCom', $item->id)->where('subj_per', '!=', 0)->count() > 0 ? true : false;
                  $with_sp = collect($subjects)->where('subjCom', $item->id)->where('isSP', 1)->count() > 0 ? true : false;

                  $tem_grades = collect($grades)->where('subjid', $item->id);

                  $item->q1status = 4;
                  $item->q2status = 4;
                  $item->q3status = 4;
                  $item->q4status = 4;

                  $item->q1gid = 0;
                  $item->q2gid = 0;
                  $item->q3gid = 0;
                  $item->q4gid = 0;

                  $item->q1 = null;
                  $item->q2 = null;
                  $item->q3 = null;
                  $item->q4 = null;
                  $item->quarter1 = null;
                  $item->quarter2 = null;
                  $item->quarter3 = null;
                  $item->quarter4 = null;

                  if ($with_percentage) {
                        $tle1 = 0;
                        $tle2 = 0;
                        $tle3 = 0;
                        $tle4 = 0;
                        $with_grade1 = true;
                        $with_grade2 = true;
                        $with_grade3 = true;
                        $with_grade4 = true;
                        $mapehcount = 0;

                        foreach ($components as $component_item) {
                              $tle1 += $component_item->q1 * ($component_item->subj_per / 100);
                              $tle2 += $component_item->q2 * ($component_item->subj_per / 100);
                              $tle3 += $component_item->q3 * ($component_item->subj_per / 100);
                              $tle4 += $component_item->q4 * ($component_item->subj_per / 100);
                        }

                        $item->q1 = number_format($tle1);
                        $item->q2 = number_format($tle2);
                        $item->q3 = number_format($tle3);
                        $item->q4 = number_format($tle4);
                        $item->quarter1 = number_format($tle1);
                        $item->quarter2 = number_format($tle2);
                        $item->quarter3 = number_format($tle3);
                        $item->quarter4 = number_format($tle4);


                  } elseif ($with_sp) {

                        $item->spcon = 1;
                        $specialized_subjects = DB::table('subjects_studspec')
                              ->where('deleted', 0)
                              ->where('studid', $studid)
                              ->where('syid', $syid)
                              ->get();

                        foreach ($specialized_subjects as $component_item) {

                              $isSP = collect($subjects)->where('id', $component_item->subjid)->where('isSP', 1)->count() > 0 ? true : false;
                              $gradeinfo = collect($grades)->where('subjid', $component_item->subjid);

                              if ($component_item->q1 == 1 && $isSP) {
                                    if (isset(collect($gradeinfo)->where('quarter', 1)->first()->qg)) {
                                          $item->q1 = collect($gradeinfo)->where('quarter', 1)->first()->qg != null ? collect($gradeinfo)->where('quarter', 1)->first()->qg : null;
                                          $item->quarter1 = collect($gradeinfo)->where('quarter', 1)->first()->qg != null ? collect($gradeinfo)->where('quarter', 1)->first()->qg : null;
                                    }
                              }

                              if ($component_item->q2 == 1 && $isSP) {
                                    if (isset($gradeinfo->where('quarter', 2)->first()->qg)) {
                                          $item->q2 = collect($gradeinfo)->where('quarter', 2)->first()->qg != null ? collect($gradeinfo)->where('quarter', 2)->first()->qg : null;
                                          $item->quarter2 = collect($gradeinfo)->where('quarter', 2)->first()->qg != null ? collect($gradeinfo)->where('quarter', 2)->first()->qg : null;
                                    }
                              }

                              if ($component_item->q3 == 1 && $isSP) {
                                    if (isset(collect($gradeinfo)->where('quarter', 3)->first()->qg)) {
                                          $item->q3 = collect($gradeinfo)->where('quarter', 3)->first()->qg != null ? collect($gradeinfo)->where('quarter', 3)->first()->qg : null;
                                          $item->quarter3 = collect($gradeinfo)->where('quarter', 3)->first()->qg != null ? collect($gradeinfo)->where('quarter', 3)->first()->qg : null;
                                    }
                              }


                              if ($component_item->q4 == 1 && $isSP) {
                                    if (isset(collect($gradeinfo)->where('quarter', 4)->first()->qg)) {
                                          $item->q4 = collect($gradeinfo)->where('quarter', 4)->first()->qg != null ? collect($gradeinfo)->where('quarter', 4)->first()->qg : null;
                                          $item->quarter4 = collect($gradeinfo)->where('quarter', 4)->first()->qg != null ? collect($gradeinfo)->where('quarter', 4)->first()->qg : null;
                                    }
                              }



                        }
                  } else {

                        $item->q1 = collect($components)->where('first', 1)->where('q1', null)->count() == 0 ? number_format(collect($components)->avg('q1')) : null;
                        $item->q2 = collect($components)->where('second', 1)->where('q2', null)->count() == 0 ? number_format(collect($components)->avg('q2')) : null;
                        $item->q3 = collect($components)->where('third', 1)->where('q3', null)->count() == 0 ? number_format(collect($components)->avg('q3')) : null;
                        $item->q4 = collect($components)->where('fourth', 1)->where('q4', null)->count() == 0 ? number_format(collect($components)->avg('q4')) : null;
                        $item->quarter1 = collect($components)->where('first', 1)->where('q1', null)->count() == 0 ? number_format(collect($components)->avg('q1')) : null;
                        $item->quarter2 = collect($components)->where('second', 1)->where('q2', null)->count() == 0 ? number_format(collect($components)->avg('q2')) : null;
                        $item->quarter3 = collect($components)->where('third', 1)->where('q3', null)->count() == 0 ? number_format(collect($components)->avg('q3')) : null;
                        $item->quarter4 = collect($components)->where('fourth', 1)->where('q4', null)->count() == 0 ? number_format(collect($components)->avg('q4')) : null;

                  }



            }

            $with_finalrating = true;

            $with_genave1 = true;
            $with_genave2 = true;
            $with_genave3 = true;
            $with_genave4 = true;

            foreach (collect($subjects)->where('subjCom', null) as $item) {
                  $with_finalrating = true;
                  $subjcount = 0;
                  $temp_genave = 0;

                  if ($item->semid == 1) {
                        if ($item->first == 1) {
                              if ($item->q1 != null || $item->q1 != 0) {
                                    $temp_genave += $item->q1;
                                    $subjcount += 1;
                              } else {
                                    if ($item->inSF9 == 1) {
                                          $with_finalrating = false;
                                          $with_genave1 = false;
                                    }
                              }
                        }
                        if ($item->second == 1) {
                              if ($item->q2 != null || $item->q2 != 0) {
                                    $temp_genave += $item->q2;
                                    $subjcount += 1;
                              } else {
                                    if ($item->inSF9 == 1) {
                                          $with_finalrating = false;
                                          $with_genave2 = false;
                                    }
                              }
                        }
                  } else {
                        if ($item->third == 1) {
                              if ($item->q3 != null) {
                                    $temp_genave += $item->q3;
                                    $subjcount += 1;
                              } else {
                                    if ($item->inSF9 == 1) {
                                          $with_finalrating = false;
                                          $with_genave3 = false;
                                    }
                              }
                        }
                        if ($item->fourth == 1) {
                              if ($item->q4 != null) {
                                    $temp_genave += $item->q4;
                                    $subjcount += 1;
                              } else {
                                    if ($item->inSF9 == 1) {
                                          $with_finalrating = false;
                                          $with_genave4 = false;
                                    }
                              }
                        }

                  }


                  if ($subjcount != 0) {
                        $genave = number_format($temp_genave / $subjcount);
                  } else {
                        $with_finalrating = false;
                  }

                  $item->finalrating = $with_finalrating ? $genave : null;
                  $item->actiontaken = $with_finalrating ? $genave >= 75 ? 'PASSED' : 'FAILED' : null;

            }



            $q1 = $with_genave1 ? collect($subjects)->where('inSF9', 1)->where('subjCom', null)->where('first', 1)->where('semid', 1)->avg('q1') : null;
            $q2 = $with_genave2 ? collect($subjects)->where('inSF9', 1)->where('subjCom', null)->where('second', 1)->where('semid', 1)->avg('q2') : null;
            $q3 = $with_genave3 ? collect($subjects)->where('inSF9', 1)->where('subjCom', null)->where('third', 1)->where('semid', 2)->avg('q3') : null;
            $q4 = $with_genave4 ? collect($subjects)->where('inSF9', 1)->where('subjCom', null)->where('fourth', 1)->where('semid', 2)->avg('q4') : null;

            $q1award = $with_genave1 ? self::check_award($q1, 1, collect($subjects)->where('subjCom', null)->where('inSF9', 1)->min('q1'), $syid) : null;
            $q2award = $with_genave2 ? self::check_award($q2, 2, collect($subjects)->where('subjCom', null)->where('inSF9', 1)->min('q2'), $syid) : null;
            $q3award = $with_genave3 ? self::check_award($q3, 3, collect($subjects)->where('subjCom', null)->where('inSF9', 1)->min('q3'), $syid) : null;
            $q4award = $with_genave4 ? self::check_award($q4, 4, collect($subjects)->where('subjCom', null)->where('inSF9', 1)->min('q4'), $syid) : null;

            $with_finalrating = true;
            if (collect($subjects)->where('inSF9', 1)->where('subjCom', null)->where('semid', 1)->where('finalrating', null)->count() > 0 || collect($subjects)->where('inSF9', 1)->where('subjCom', null)->where('semid', 1)->where('finalrating', 0)->count() > 0) {
                  $with_finalrating = false;
            }

            $fr = null;
            if ($schoolinfo->abbreviation == 'SJAES') {
                  if (collect($subjects)->whereIn('subjid', [7, 14, 21, 43, 44, 88, 89, 98, 99])->where('subjCom', null)->where('semid', 2)->count() > 0) {
                        if ($with_finalrating) {
                              $final_rating = 0;
                              $subj_count = 0;
                              foreach (collect($subjects)->where('inSF9', 1)->where('subjCom', null)->where('semid', 1)->values() as $subjitem) {
                                    if ($subjitem->subjdesc == 'PHYSICAL EDUCATION') {
                                          $final_rating += $subjitem->finalrating * .25;
                                          $subj_count += .25;
                                    } else {
                                          $final_rating += $subjitem->finalrating;
                                          $subj_count += 1;
                                    }
                              }
                              $fr = $final_rating / $subj_count;
                        }
                  } else {
                        $fr = $with_finalrating ? collect($subjects)->where('inSF9', 1)->where('subjCom', null)->where('semid', 1)->avg('finalrating') : null;
                  }

            } else {
                  $fr = $with_finalrating ? collect($subjects)->where('inSF9', 1)->where('subjCom', null)->where('semid', 1)->avg('finalrating') : null;
            }

            $fraward = $with_finalrating ? self::check_award($fr, null, 100, $syid) : null;

            $temp_subj = array();

            foreach (collect($subjects)->where('subjCom', null) as $item) {
                  if ($item->isVisible == 1) {
                        array_push($temp_subj, $item);
                  }
            }

            $fr_1st = $fr;

            $genave = (object) [
                  'subjdesc' => 'GENERAL AVERAGE',
                  'sortid' => 'ZZ',
                  'isVisible' => 1,
                  'subjCom' => null,
                  'id' => 'G1',
                  'subjid' => 'G1',
                  'q1status' => 1,
                  'q2status' => 1,
                  'q3status' => null,
                  'q4status' => null,
                  'q1gid' => 0,
                  'q2gid' => 0,
                  'q3gid' => null,
                  'q4gid' => null,
                  'q1' => $q1 != null ? number_format($q1) : null,
                  'q2' => $q2 != null ? number_format($q2) : null,
                  'q3' => null,
                  'q4' => null,
                  'q1award' => $q1award,
                  'q2award' => $q2award,
                  'q3award' => null,
                  'q4award' => null,
                  'q1comp' => $q1 != null ? number_format($q1, 3) : null,
                  'q2comp' => $q2 != null ? number_format($q2, 3) : null,
                  'q3comp' => null,
                  'q4comp' => null,
                  'quarter1' => $q1 != null ? number_format($q1) : null,
                  'quarter2' => $q2 != null ? number_format($q2) : null,
                  'quarter3' => null,
                  'quarter4' => null,
                  'finalrating' => $fr != null ? number_format($fr) : null,
                  'fcomp' => number_format($fr, 3),
                  'fraward' => $fraward,
                  'actiontaken' => $with_finalrating ? $fr != null ? $fr >= 75 ? 'PASSED' : 'FAILED' : null : null,
                  'semid' => 1,
                  'lq1' => $q1 != null ? collect($subjects)->where('subjCom', null)->where('inSF9', 1)->min('q1') : null,
                  'lq2' => $q2 != null ? collect($subjects)->where('subjCom', null)->where('inSF9', 1)->min('q2') : null,
                  'lq3' => $q3 != null ? collect($subjects)->where('subjCom', null)->where('inSF9', 1)->min('q3') : null,
                  'lq4' => $q4 != null ? collect($subjects)->where('subjCom', null)->where('inSF9', 1)->min('q4') : null,
                  'lfr' => collect($subjects)->where('inSF9', 1)->min('finalrating'),
                  'vr' => 5
            ];
            array_push($temp_subj, $genave);

            $with_finalrating = true;
            if (collect($subjects)->where('inSF9', 1)->where('subjCom', null)->where('semid', 2)->where('finalrating', null)->count() > 0 || collect($subjects)->where('inSF9', 1)->where('subjCom', null)->where('semid', 2)->where('finalrating', 0)->count() > 0) {
                  $with_finalrating = false;
            }

            $fr = null;
            if ($schoolinfo->abbreviation == 'SJAES') {
                  if (collect($subjects)->whereIn('subjid', [7, 14, 21, 43, 44, 88, 89, 98, 99])->where('subjCom', null)->where('semid', 2)->count() > 0) {
                        if ($with_finalrating) {
                              $final_rating = 0;
                              $subj_count = 0;
                              foreach (collect($subjects)->where('inSF9', 1)->where('subjCom', null)->where('semid', 2)->values() as $subjitem) {
                                    if ($subjitem->subjdesc == 'PHYSICAL EDUCATION') {
                                          $final_rating += $subjitem->finalrating * .25;
                                          $subj_count += .25;
                                    } else {
                                          $final_rating += $subjitem->finalrating;
                                          $subj_count += 1;
                                    }
                              }
                              $fr = $final_rating / $subj_count;
                        }
                  } else {
                        $fr = $with_finalrating ? collect($subjects)->where('inSF9', 1)->where('subjCom', null)->where('semid', 2)->avg('finalrating') : null;
                  }

            } else {
                  $fr = $with_finalrating ? collect($subjects)->where('inSF9', 1)->where('subjCom', null)->where('semid', 2)->avg('finalrating') : null;
            }

            $fr_2nd = $fr;
            $fraward = $with_finalrating ? self::check_award(number_format($fr, 3), null, 100, $syid) : null;

            $genave = (object) [
                  'subjdesc' => 'GENERAL AVERAGE',
                  'sortid' => 'ZZ',
                  'isVisible' => 1,
                  'subjCom' => null,
                  'id' => 'G1',
                  'subjid' => 'G1',
                  'q1status' => null,
                  'q2status' => null,
                  'q3status' => 1,
                  'q4status' => 1,
                  'q1gid' => null,
                  'q2gid' => null,
                  'q3gid' => 0,
                  'q4gid' => 0,
                  'q1' => null,
                  'q2' => null,
                  'q3' => $q3 != null ? number_format($q3) : null,
                  'q4' => $q4 != null ? number_format($q4) : null,
                  'q1award' => null,
                  'q2award' => null,
                  'q3award' => $q3 != null ? $q3award : null,
                  'q4award' => $q4 != null ? $q4award : null,
                  'q1comp' => null,
                  'q2comp' => null,
                  'q3comp' => $q3 != null ? number_format($q3, 3) : null,
                  'q4comp' => $q4 != null ? number_format($q4, 3) : null,
                  'quarter1' => null,
                  'quarter2' => null,
                  'quarter3' => $q3 != null ? number_format($q3) : null,
                  'quarter4' => $q4 != null ? number_format($q4) : null,
                  'finalrating' => $fr != null ? number_format($fr) : null,
                  'fcomp' => $fr != null ? number_format($fr, 3) : null,
                  'fraward' => $fr != null ? $fraward : null,
                  'actiontaken' => $with_finalrating ? $fr != null ? $fr >= 75 ? 'PASSED' : 'FAILED' : null : null,
                  'semid' => 2,
                  'lq1' => $q1 != null ? collect($subjects)->where('subjCom', null)->where('inSF9', 1)->min('q1') : null,
                  'lq2' => $q2 != null ? collect($subjects)->where('subjCom', null)->where('inSF9', 1)->min('q2') : null,
                  'lq3' => $q3 != null ? collect($subjects)->where('subjCom', null)->where('inSF9', 1)->min('q3') : null,
                  'lq4' => $q4 != null ? collect($subjects)->where('subjCom', null)->where('inSF9', 1)->min('q4') : null,
                  'lfr' => collect($subjects)->where('inSF9', 1)->min('finalrating'),
                  'vr' => 5

            ];
            array_push($temp_subj, $genave);


            $fr_1st_2nd = $fr_1st != null && $fr_2nd != null ? ($fr_1st + $fr_2nd) / 2 : null;
            $fr_1st_2nd_award = $fr_1st_2nd != null ? self::check_award($fr_1st_2nd, null, 100, $syid) : null;

            $fr_1st_2nd_genave = (object) [
                  'subjdesc' => 'GENERAL AVERAGE',
                  'sortid' => 'ZZGENAVE',
                  'isVisible' => 1,
                  'subjCom' => null,
                  'id' => 'FRBOTH',
                  'subjid' => 'FRBOTH',
                  'q1status' => null,
                  'q2status' => null,
                  'q3status' => 1,
                  'q4status' => 1,
                  'q1gid' => null,
                  'q2gid' => null,
                  'q3gid' => null,
                  'q4gid' => null,
                  'q1' => null,
                  'q2' => null,
                  'q3' => null,
                  'q4' => null,
                  'q1award' => null,
                  'q2award' => null,
                  'q3award' => null,
                  'q4award' => null,
                  'q1comp' => null,
                  'q2comp' => null,
                  'q3comp' => null,
                  'q4comp' => null,
                  'quarter1' => null,
                  'quarter2' => null,
                  'quarter3' => null,
                  'quarter4' => null,
                  'finalrating' => $fr_1st_2nd != null ? number_format($fr_1st_2nd) : null,
                  'fcomp' => $fr_1st_2nd != null ? number_format($fr_1st_2nd, 3) : null,
                  'fraward' => $fr_1st_2nd != null ? $fr_1st_2nd_award : null,
                  'actiontaken' => $fr_1st_2nd != null ? number_format($fr_1st_2nd) >= 75 ? 'PASSED' : 'FAILED' : null,
                  'semid' => null,
                  'lq1' => null,
                  'lq2' => null,
                  'lq3' => null,
                  'lq4' => null,
                  'lfr' => null,
                  'vr' => 5,
                  'strandid' => null
            ];

            array_push($temp_subj, $fr_1st_2nd_genave);



            $subjects = $temp_subj;

            return $subjects;


      }

      function college_grades_eval(Request $request)
      {
            $student = $request->studid;
            $schedule = DB::table('college_loadsubject')
                  ->join('college_enrolledstud', function ($join) use ($student) {
                        $join->on('college_loadsubject.studid', '=', 'college_enrolledstud.studid');
                        $join->where('college_enrolledstud.studid', $student);
                        $join->where('college_enrolledstud.deleted', 0);
                  })
                  ->join('studinfo', function ($join) {
                        $join->on('college_enrolledstud.studid', '=', 'studinfo.id');
                        $join->where('studinfo.deleted', 0);
                  })

                  ->join('college_sections', function ($join) {
                        $join->on('college_loadsubject.sectionID', '=', 'college_sections.id');
                        $join->where('college_sections.deleted', 0);
                  })
                  ->join('college_prospectus', 'college_loadsubject.subjectID', '=', 'college_prospectus.id')
                  ->leftJoin('college_stud_term_grades', function ($join) use ($student) {
                        $join->on('studinfo.id', '=', 'college_stud_term_grades.studid')
                              ->where('college_stud_term_grades.studid', $student)
                              ->on('college_prospectus.id', '=', 'college_stud_term_grades.prospectusID')
                              ->where('college_stud_term_grades.deleted', 0);
                  })
                  ->where('college_loadsubject.deleted', 0)
                  ->select(
                        'college_prospectus.subjDesc',
                        'college_prospectus.subjCode',
                        'college_prospectus.id as subjID',
                        'college_loadsubject.schedid',
                        'college_loadsubject.syid as yearID',
                        'college_loadsubject.semid as semID',
                        'college_stud_term_grades.*',
                  )
                  ->groupBy('college_loadsubject.subjectID')
                  ->get();

            return $schedule;
      }

      function college_grades_eval_2(Request $request)
      {
            $student = $request->studid;
            $syid = $request->syid;
            $semid = $request->semid;
            $currid = $request->curr;


            $student_course = DB::table('studinfo')
                  ->join('college_studentcurriculum', function ($join) use ($student) {
                        $join->on('studinfo.id', '=', 'college_studentcurriculum.studid');
                        $join->where('college_studentcurriculum.deleted', 0);
                  })
                  ->where('studinfo.id', $student)
                  ->where('studinfo.deleted', 0)
                  ->select(
                        'college_studentcurriculum.curriculumid',
                        'studinfo.levelid',
                        'studinfo.id',
                        'studinfo.courseid'
                  )
                  ->first();

            // $student_grades_prospectus = DB::table('college_prospectus')



            $student_grades_prospectus = DB::table('college_prospectus')
                  ->leftJoin('college_stud_term_grades', function ($join) use ($student_course) {
                        $join->on('college_prospectus.id', '=', 'college_stud_term_grades.prospectusID')
                              ->where('college_stud_term_grades.studid', $student_course->id)
                              ->where('college_stud_term_grades.deleted', 0);
                  })
                  ->where('college_prospectus.curriculumID', $student_course->curriculumid)
                  ->where('college_prospectus.courseid', $student_course->courseid)
                  ->where('college_prospectus.deleted', 0)
                  ->select(
                        'college_prospectus.id',
                        'college_prospectus.subjCode',
                        'college_prospectus.subjDesc',
                        'college_prospectus.yearID',
                        'college_prospectus.semesterID',
                        'college_prospectus.curriculumID',
                        'college_prospectus.lecunits',
                        'college_prospectus.labunits',
                        'college_prospectus.credunits',
                        'college_stud_term_grades.id as gradesid',
                        'college_stud_term_grades.final_grade_transmuted',
                        'college_stud_term_grades.final_transmuted',
                        'college_stud_term_grades.prelim_status',
                        'college_stud_term_grades.midterm_status',
                        'college_stud_term_grades.prefinal_status',
                        'college_stud_term_grades.final_status',
                        'college_stud_term_grades.final_remarks',
                  )
                  ->groupBy('college_prospectus.id')
                  ->get()
                  ->map(function ($item) {
                        $item->grades = (object) [
                              'gpa' => $item->final_grade_transmuted,
                              'final_transmuted' => $item->final_transmuted
                        ];
                        unset($item->prelemgrade, $item->midtermgrade, $item->prefigrade, $item->finalgrade);
                        return $item;
                  });

            $student_grades_prospectus = $student_grades_prospectus->map(function ($item) {
                  $prereq = DB::table('college_subjprereq')
                        ->join('college_prospectus as original', function ($join) use ($item) {
                              $join->on('college_subjprereq.subjID', '=', 'original.id')
                                    ->where('original.deleted', 0)
                                    ->where('original.id', $item->id);
                        })
                        ->join('college_prospectus as prereq', function ($join) {
                              $join->on('college_subjprereq.prereqsubjID', '=', 'prereq.id')
                                    ->where('prereq.deleted', 0);
                        })
                        ->where('college_subjprereq.deleted', 0)
                        ->select(
                              'college_subjprereq.prereqsubjID',
                              'prereq.subjCode',
                              'prereq.subjDesc',
                        )
                        ->get();
                  $item->prereq = $prereq;
                  return $item;
            });

            return $student_grades_prospectus;


      }


      function college_grades_eval_2_print(Request $request)
      {
            $student = $request->studid;
            $syid = $request->syid;
            $semid = $request->semid;
            $currid = $request->curr;


            $schoolinfo = DB::table('schoolinfo')->first();

            $student_course = DB::table('studinfo')
                  ->join('college_studentcurriculum', function ($join) use ($student) {
                        $join->on('studinfo.id', '=', 'college_studentcurriculum.studid');
                        $join->where('college_studentcurriculum.deleted', 0);
                  })
                  ->join('gradelevel', function ($join) use ($student) {
                        $join->on('studinfo.levelid', '=', 'gradelevel.id');
                  })
                  ->leftjoin('college_curriculum', function ($join) use ($currid) {
                        $join->on('college_curriculum.id', '=', 'college_studentcurriculum.curriculumid');
                  })
                  ->where('studinfo.id', $student)
                  ->where('studinfo.deleted', 0)
                  ->select(
                        'college_studentcurriculum.curriculumid',
                        'studinfo.levelid',
                        'studinfo.sid',
                        'studinfo.courseid',
                        'gradelevel.levelname',
                        'college_curriculum.curriculumname',

                  )
                  ->first();

            $course = DB::table('college_courses')
                  ->join('college_colleges', 'college_courses.collegeid', '=', 'college_colleges.id')
                  ->where('college_courses.id', $student_course->courseid)
                  ->select(
                        'college_courses.id',
                        'college_courses.courseDesc',
                        'college_colleges.collegeDesc',
                        'college_colleges.acadprogid'
                  )
                  ->first();


            $gradelevel = DB::table('gradelevel')
                  ->where('acadprogid', $course->acadprogid)
                  ->where('deleted', 0)
                  ->select('id', 'levelname')
                  ->get();

            foreach ($gradelevel as $level) {
                  $level->semesters = DB::table('semester')
                        ->get();
                  foreach ($level->semesters as $sem) {
                        $student_grades_prospectus = DB::table('college_prospectus')
                              ->leftjoin('college_classsched', 'college_prospectus.id', '=', 'college_classsched.subjectID')
                              ->leftjoin('college_studentprospectus', function ($join) use ($student_course) {
                                    $join->on('college_classsched.id', '=', 'college_studentprospectus.schedid')
                                          ->where('college_studentprospectus.studid', $student_course->sid)
                                          ->where('college_studentprospectus.deleted', 0);
                                    $join->where('college_classsched.deleted', 0);
                              })
                              ->where('college_prospectus.curriculumID', $student_course->curriculumid)
                              ->where('college_prospectus.yearID', $level->id)
                              ->where('college_prospectus.semesterID', $sem->id)
                              ->where('college_prospectus.deleted', 0)
                              ->select(
                                    'college_prospectus.id',
                                    'college_prospectus.subjCode',
                                    'college_prospectus.subjDesc',
                                    'college_prospectus.yearID',
                                    'college_prospectus.semesterID',
                                    'college_prospectus.curriculumID',
                                    // 'college_curriculum.curriculumname',
                                    'college_prospectus.lecunits',
                                    'college_prospectus.labunits',
                                    'college_prospectus.credunits',
                                    'college_studentprospectus.id as gradesid',
                                    'college_studentprospectus.fg',
                                    'college_studentprospectus.prelemstatus',
                                    'college_studentprospectus.midtermstatus',
                                    'college_studentprospectus.prefistatus',
                                    'college_studentprospectus.finalstatus',
                                    'college_studentprospectus.remarks',
                              )
                              ->groupBy('college_prospectus.id')
                              ->get();

                        $student_grades_prospectus = $student_grades_prospectus->map(function ($item) {
                              $prereq = DB::table('college_subjprereq')
                                    ->join('college_prospectus as original', function ($join) use ($item) {
                                          $join->on('college_subjprereq.subjID', '=', 'original.id')
                                                ->where('original.deleted', 0)
                                                ->where('original.id', $item->id);
                                    })
                                    ->join('college_prospectus as prereq', function ($join) {
                                          $join->on('college_subjprereq.prereqsubjID', '=', 'prereq.id')
                                                ->where('prereq.deleted', 0);
                                    })
                                    ->where('college_subjprereq.deleted', 0)
                                    ->select(
                                          'college_subjprereq.prereqsubjID',
                                          'prereq.subjCode',
                                          'prereq.subjDesc',
                                    )
                                    ->get();
                              $item->prereq = $prereq;
                              return $item;
                        });
                        $sem->student_grades_prospectus = $student_grades_prospectus;
                  }
            }



            // $student_grades_prospectus = DB::table('college_prospectus')
            //             ->leftjoin('college_classsched','college_prospectus.id','=','college_classsched.subjectID')
            //             ->leftjoin('college_studentprospectus', function($join) use ($student_course) {
            //                   $join->on('college_classsched.id', '=', 'college_studentprospectus.schedid')
            //                         ->where('college_studentprospectus.studid', $student_course->sid)
            //                         ->where('college_studentprospectus.deleted', 0);
            //                   $join->where('college_classsched.deleted', 0);
            //             })
            //             ->join('college_curriculum', function($join) use ($student_course) {
            //                   $join->on('college_prospectus.curriculumID', '=', 'college_curriculum.id')
            //                         ->where('college_curriculum.id', $student_course->curriculumid)
            //                         ->where('college_curriculum.deleted', 0);
            //             })
            //             ->where('college_prospectus.curriculumID', $student_course->curriculumid)
            //             ->where('college_prospectus.courseid', $student_course->courseid)
            //             ->where('college_prospectus.deleted', 0)
            //             ->select(
            //                   'college_prospectus.id',
            //                   'college_prospectus.subjCode',
            //                   'college_prospectus.subjDesc',
            //                   'college_prospectus.yearID',
            //                   'college_prospectus.semesterID',
            //                   'college_prospectus.curriculumID',
            //                   'college_curriculum.curriculumname',
            //                   'college_prospectus.lecunits',
            //                   'college_prospectus.labunits',
            //                   'college_prospectus.credunits',
            //                   'college_studentprospectus.id as gradesid',
            //                   'college_studentprospectus.fg',
            //                   'college_studentprospectus.prelemstatus',
            //                   'college_studentprospectus.midtermstatus',
            //                   'college_studentprospectus.prefistatus',
            //                   'college_studentprospectus.finalstatus',
            //                   'college_studentprospectus.remarks',
            //             )
            //             ->distinct('college_prospectus.id')
            //             ->get()
            //             ->map(function($item) {
            //                   $item->grades = (object)[
            //                         'gpa' => $item->fg
            //                   ];
            //                   unset($item->prelemgrade, $item->midtermgrade, $item->prefigrade, $item->finalgrade);
            //                   return $item;
            //             });

            // $student_grades_prospectus = $student_grades_prospectus->map(function($item) {
            //       $prereq = DB::table('college_subjprereq')
            //             ->join('college_prospectus as original', function($join) use($item) {
            //                   $join->on('college_subjprereq.subjID', '=', 'original.id')
            //                   ->where('original.deleted', 0)
            //                   ->where('original.id', $item->id);
            //             })
            //             ->join('college_prospectus as prereq', function($join) {
            //                   $join->on('college_subjprereq.prereqsubjID', '=', 'prereq.id')
            //                   ->where('prereq.deleted', 0);
            //             })
            //             ->where('college_subjprereq.deleted', 0)
            //             ->select(
            //                   'college_subjprereq.prereqsubjID',
            //                   'prereq.subjCode',
            //                   'prereq.subjDesc',
            //             )
            //             ->get();
            //       $item->prereq = $prereq;
            //       return $item;
            // });

            $studinfo = DB::table('studinfo')
                  ->where('id', $student)
                  ->select(
                        'lastname',
                        'firstname',
                        'middlename',
                        'dob',
                        DB::raw("CONCAT(barangay, ', ', city, ', ', province) as full_address"),
                        'picurl',
                        'sid'
                  )
                  ->first();
            return view('superadmin.pages.student.gradeevalprint', compact('gradelevel', 'schoolinfo', 'studinfo', 'course', 'student_course'));


      }

      function add_credited_subj(Request $request)
      {
            $levelid = $request->level;
            $semid = $request->sem;
            $schoolyear = $request->schoolyear;
            $studid = $request->studid;
            $schoolname = $request->schoolname;
            $schooladdress = $request->schooladdress;
            $credsubj = $request->credsubj;
            $headerid = $request->headerid;
            $semesterid = $request->semesterid;
            $syid = $request->syid;
            $courseid = $request->courseid;


            if (!$headerid) {
                  $credschool = DB::table('college_credschool')
                        ->insertGetId([
                              'schoolname' => $schoolname,
                              'schooladdress' => $schooladdress,
                              'sydesc' => $schoolyear,
                              'studid' => $studid,
                              'semid' => $semesterid,
                              'createdby' => auth()->user()->id,
                              'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);
            } else {
                  $credschool = $headerid;
            }



            $checkifexists = DB::table('college_tor')
                  ->where('studid', $studid)
                  ->where('syid', $syid)
                  ->where('sydesc', 'like', '%' . $schoolyear . '%')
                  ->where('semid', $semesterid)
                  ->where('deleted', '0')
                  ->count();

            if ($checkifexists == 0) {
                  $torid = DB::table('college_tor')
                        ->insertGetId([
                              'studid' => $studid,
                              'syid' => $syid,
                              'sydesc' => $schoolyear,
                              'semid' => $semesterid,
                              //   'schoolid' => $schoolid,
                              'schoolname' => $schoolname,
                              'schooladdress' => $schooladdress,
                              'createdby' => auth()->user()->id,
                              'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);
            }

            foreach ($credsubj as $cred) {
                  $prospectustable = DB::table('college_prospectus')
                        ->where('id', $cred['prospectusid'])
                        ->select(
                              'subjDesc',
                              'subjCode',
                              'credunits',
                              'lecunits',
                              'labunits'
                        )
                        ->get();



                  DB::table('college_credsubj')
                        ->insert([
                              'schoolID' => $credschool,
                              'studID' => $studid,
                              'levelID' => $levelid,
                              'semid' => $semesterid,
                              'prospectusID' => $cred['prospectusid'],
                              'lecunits' => $prospectustable[0]->lecunits,
                              'labunits' => $prospectustable[0]->labunits,
                              'credunits' => $prospectustable[0]->credunits,
                              'subjDesc' => $prospectustable[0]->subjDesc,
                              'subjCode' => $prospectustable[0]->subjCode,
                              'gpa' => $cred['gpa'],
                              'createdby' => auth()->user()->id,
                              'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);

                  $checkifexists = DB::table('college_torgrades')
                        ->where('torid', $torid)
                        ->where('subjdesc', 'like', '%' . $prospectustable[0]->subjDesc . '%')
                        ->where('deleted', '0')
                        ->count();


                  if ($checkifexists == 0) {
                        $subjdataid = Db::table('college_torgrades')
                              ->insertGetId([
                                    'torid' => $torid,
                                    'subjid' => $cred['prospectusid'],
                                    'subjcode' => $prospectustable[0]->subjCode,
                                    'subjdesc' => $prospectustable[0]->subjDesc,
                                    'subjgrade' => $cred['gpa'],
                                    // 'subjreex' => $subjreex,
                                    // 'subjunit' => $subjunit,
                                    'subjcredit' => $prospectustable[0]->credunits,
                                    'createdby' => auth()->user()->id,
                                    'createddatetime' => date('Y-m-d H:i:s')
                              ]);
                  }

            }





      }

      function get_credited_subj(Request $request)
      {
            $studid = $request->studid;



            $credschool = DB::table('college_credschool')
                  ->join('semester', 'college_credschool.semID', '=', 'semester.id')
                  ->where('studid', $studid)
                  ->where('college_credschool.deleted', 0)
                  ->select(
                        'schoolname',
                        'schooladdress',
                        'sydesc',
                        'studid',
                        'college_credschool.id as headerid',
                        'semester.semester',
                        'semester.id as semid'
                  )
                  ->get();

            foreach ($credschool as $school) {
                  $school->credsubj = DB::table('college_credsubj')
                        ->leftjoin('college_prospectus', 'college_credsubj.prospectusID', '=', 'college_prospectus.id')
                        ->where('college_credsubj.schoolID', $school->headerid)
                        ->where('college_credsubj.deleted', 0)
                        ->select(
                              'college_credsubj.prospectusID',
                              'college_credsubj.id',
                              'college_credsubj.subjDesc',
                              'college_credsubj.subjCode',
                              'college_credsubj.gpa',
                              'college_credsubj.semid',
                              'college_credsubj.levelID',
                              'college_credsubj.schoolID',
                              'college_credsubj.status',
                              'college_credsubj.lecunits',
                              'college_credsubj.labunits',
                              'college_credsubj.credunits',
                              'college_prospectus.id as prospectusid',

                        )
                        ->get();

                  foreach ($school->credsubj as $subj) {
                        $subj->credpreq = DB::table('college_subjprereq')
                              ->leftjoin('college_prospectus as original', function ($join) use ($subj) {
                                    $join->on('college_subjprereq.subjID', '=', 'original.id')
                                          ->where('original.deleted', 0)
                                          ->where('original.id', $subj->prospectusid);
                              })
                              ->join('college_prospectus as prereq', function ($join) {
                                    $join->on('college_subjprereq.prereqsubjID', '=', 'prereq.id')
                                          ->where('prereq.deleted', 0);
                              })
                              ->where('college_subjprereq.deleted', 0)
                              ->where('college_subjprereq.subjID', $subj->prospectusid)
                              ->select(
                                    'college_subjprereq.prereqsubjID',
                                    'prereq.subjCode',
                                    'prereq.subjDesc',
                                    'college_subjprereq.subjID',

                              )
                              ->get();
                  }
            }

            return $credschool;
      }

      function additional_credit(Request $request)
      {
            $headerid = $request->headerid;
            $semid = $request->semid;
            $subjCode = $request->subjCode;
            $subjDesc = $request->subjDesc;
            $studid = $request->studid;
            $levelid = $request->levelid;
            $gpa = $request->gpa;
            $prospectusid = $request->prospectusID;
            $credunits = $request->credunits;
            $lecunits = $request->lecunits;
            $labunits = $request->labunits;


            DB::table('college_credsubj')
                  ->insert([
                        'schoolID' => $headerid,
                        'studID' => $studid,
                        'levelID' => $levelid,
                        'semID' => $semid,
                        'lecunits' => $lecunits,
                        'labunits' => $labunits,
                        'credunits' => $credunits,
                        'prospectusID' => $prospectusid,
                        'subjDesc' => $subjDesc,
                        'subjCode' => $subjCode,
                        'gpa' => $gpa,
                        'status' => 1,
                        'createdby' => auth()->user()->id,
                        'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                  ]);

            $checkifexists = DB::table('college_torgrades')
                  ->where('torid', $headerid)
                  ->where('subjdesc', 'like', '%' . $subjDesc . '%')
                  ->where('deleted', '0')
                  ->count();


            if ($checkifexists == 0) {
                  $subjdataid = Db::table('college_torgrades')
                        ->insertGetId([
                              'torid' => $headerid,
                              'subjid' => $prospectusid,
                              'subjcode' => $subjCode,
                              'subjdesc' => $subjDesc,
                              'subjgrade' => $gpa,
                              // 'subjreex' => $subjreex,
                              // 'subjunit' => $subjunit,
                              'subjcredit' => $credunits,
                              'createdby' => auth()->user()->id,
                              'createddatetime' => date('Y-m-d H:i:s')
                        ]);
            }
      }

      function delete_additional_credit(Request $request)
      {
            $id = $request->id;

            $checkifexists = DB::table('college_torgrades')
                  ->where('subjid', $id)
                  ->where('deleted', '0')
                  ->count();

            $credsubj = DB::table('college_credsubj')
                  ->where('id', $id)
                  ->select(
                        'prospectusID',
                        'studID',
                        'subjDesc',
                        'subjCode',
                        'gpa',
                  )
                  ->first();

            DB::table('college_torgrades')
                  ->join('college_tor', 'college_tor.id', '=', 'college_torgrades.torid')
                  ->where('college_tor.studid', $credsubj->studID)
                  ->where('subjid', $credsubj->prospectusID)
                  ->where('subjcode', $credsubj->subjCode)
                  ->where('subjdesc', $credsubj->subjDesc)
                  ->where('subjgrade', $credsubj->gpa)
                  ->update([
                        'college_torgrades.deleted' => 1,
                        'college_torgrades.deletedby' => auth()->user()->id,
                        'college_torgrades.deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                  ]);

            DB::table('college_credsubj')
                  ->where('id', $id)
                  ->update([
                        'deleted' => 1,
                        'deletedby' => auth()->user()->id,
                        'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                  ]);


      }

      function delete_subj_credit(Request $request)
      {
            $id = $request->id;
            $semid = $request->semid;

            $credsubj = DB::table('college_credsubj')
                  ->where('schoolID', $id)
                  ->where('semid', $semid)
                  ->select(
                        'prospectusID',
                        'studID',
                        'subjDesc',
                        'subjCode',
                        'gpa',
                  )
                  ->get();

            foreach ($credsubj as $cs) {
                  DB::table('college_torgrades')
                        ->join('college_tor', 'college_tor.id', '=', 'college_torgrades.torid')
                        ->where('college_tor.studid', $cs->studID)
                        ->where('subjid', $cs->prospectusID)
                        ->where('subjcode', $cs->subjCode)
                        ->where('subjdesc', $cs->subjDesc)
                        ->where('subjgrade', $cs->gpa)
                        ->update([
                              'college_torgrades.deleted' => 1,
                              'college_torgrades.deletedby' => auth()->user()->id,
                              'college_torgrades.deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);
            }

            DB::table('college_credsubj')
                  ->where('schoolID', $id)
                  ->where('semid', $semid)
                  ->update([
                        'deleted' => 1,
                        'deletedby' => auth()->user()->id,
                        'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                  ]);
      }

      function update_additional_credit(Request $request)
      {
            $id = $request->id;
            $subjCode = $request->subjCode;
            $subjDesc = $request->subjDesc;
            $gpa = $request->gpa;
            $prospectusid = $request->prospectusID;
            $credunits = $request->credunits;
            $lecunits = $request->lecunits;
            $labunits = $request->labunits;

            $credsubj = DB::table('college_credsubj')
                  ->where('id', $id)
                  ->select(
                        'prospectusID',
                        'studID',
                        'subjDesc',
                        'subjCode',
                        'gpa',
                  )
                  ->first();


            DB::table('college_credsubj')
                  ->where('id', $id)
                  ->update([
                        'subjDesc' => $subjDesc,
                        'subjCode' => $subjCode,
                        'gpa' => $gpa,
                        'lecunits' => $lecunits,
                        'labunits' => $labunits,
                        'credunits' => $credunits,
                        'prospectusID' => $prospectusid,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                  ]);

            DB::table('college_torgrades')
                  ->join('college_tor', 'college_tor.id', '=', 'college_torgrades.torid')
                  ->where('college_tor.studid', $credsubj->studID)
                  ->where('subjid', $credsubj->prospectusID)
                  ->where('subjcode', $credsubj->subjCode)
                  ->where('subjdesc', $credsubj->subjDesc)
                  ->where('subjgrade', $credsubj->gpa)
                  ->update([
                        'college_torgrades.subjcode' => $subjCode,
                        'college_torgrades.subjdesc' => $subjDesc,
                        'college_torgrades.subjgrade' => $gpa,
                        'college_torgrades.subjcredit' => $credunits,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                  ]);

      }

      function get_specific_credited_subj(Request $request)
      {
            $id = $request->id;

            $credschool = DB::table('college_credschool')
                  ->where('id', $id)
                  ->where('deleted', 0)
                  ->select(
                        'schoolname',
                        'schooladdress',
                        'sydesc',
                        'studid',
                        'id as headerid'
                  )
                  ->get();

            $credschool = $credschool->map(function ($item) {
                  $credsubj = DB::table('college_credsubj')
                        ->join('college_prospectus', 'college_credsubj.prospectusID', '=', 'college_prospectus.id')
                        ->where('college_credsubj.schoolID', $item->headerid)
                        ->where('college_credsubj.deleted', 0)
                        ->where('college_credsubj.status', 0)
                        ->select(
                              'college_credsubj.prospectusID',
                              'college_credsubj.id',
                              'college_credsubj.subjDesc',
                              'college_credsubj.subjCode',
                              'college_credsubj.gpa',
                              'college_credsubj.levelID',
                              'college_credsubj.semID',
                              'college_credsubj.schoolID',
                              'college_credsubj.status',
                              'college_prospectus.lecunits',
                              'college_prospectus.labunits',
                              'college_prospectus.credunits',
                              'college_prospectus.id as prospectusid',

                        )
                        ->get();
                  $credsubj = $credsubj->map(function ($item) {
                        $credpreq = DB::table('college_subjprereq')
                              ->join('college_prospectus as original', function ($join) use ($item) {
                                    $join->on('college_subjprereq.subjID', '=', 'original.id')
                                          ->where('original.deleted', 0)
                                          ->where('original.id', $item->prospectusid);
                              })
                              ->join('college_prospectus as prereq', function ($join) {
                                    $join->on('college_subjprereq.prereqsubjID', '=', 'prereq.id')
                                          ->where('prereq.deleted', 0);
                              })
                              ->where('college_subjprereq.deleted', 0)
                              ->select(
                                    'college_subjprereq.prereqsubjID',
                                    'prereq.subjCode',
                                    'prereq.subjDesc',
                              )
                              ->get();
                        $item->credpreq = $credpreq;
                        return $item;
                  });
                  $item->credsubj = $credsubj;
                  return $item;
            });

            return $credschool;
      }

      function update_school_credit(Request $request)
      {
            $id = $request->id;
            $schoolname = $request->schoolname;
            $schoolyear = $request->schoolyear;
            $schooladdress = $request->schooladdress;
            $syid = $request->syid;


            $credschool = DB::table('college_credschool')
                  ->where('id', $id)
                  ->select(
                        'studid',
                        'sydesc',
                        'semid',
                        'schoolname',
                        'schooladdress'
                  )
                  ->first();



            DB::table('college_credschool')
                  ->where('id', $id)
                  ->update([
                        'schoolname' => $schoolname,
                        'schooladdress' => $schooladdress,
                        'sydesc' => $schoolyear,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                  ]);

            $tor = DB::table('college_tor')
                  ->where('studid', $credschool->studid)
                  ->where('syid', $syid)
                  ->where('sydesc', 'like', '%' . $credschool->sydesc . '%')
                  ->where('schoolname', 'like', '%' . $credschool->schoolname . '%')
                  ->where('schooladdress', 'like', '%' . $credschool->schooladdress . '%')
                  ->where('semid', $credschool->semid)
                  ->where('deleted', 0)
                  ->select('id')
                  ->first();

            DB::table('college_tor')
                  ->where('id', $tor->id)
                  ->update([
                        'schoolname' => $schoolname,
                        'schooladdress' => $schooladdress,
                        'sydesc' => $schoolyear,
                        'updatedby' => auth()->user()->id,
                        'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                  ]);
      }

      function update_credit(Request $request)
      {
            $levelid = $request->levelid;
            $semid = $request->sem;
            $studid = $request->studid;
            $headerid = $request->id;
            $credsubj = $request->credsubj;
            $credprospectus = [];
            $existingsubj = array();
            $semidstud = $request->semidstud;
            $syid = $request->syid;


            $creditedsubj = DB::table('college_credsubj')
                  ->where('schoolID', $headerid)
                  ->where('levelID', $levelid)
                  ->where('semID', $semid)
                  ->where('deleted', 0)
                  ->where('status', 0)
                  ->select('prospectusID')
                  ->get();

            $equivalence = DB::table("college_grade_point_scale")
                  ->join('college_grade_point_equivalence', 'college_grade_point_scale.grade_point_equivalency', '=', 'college_grade_point_equivalence.id')
                  ->where('college_grade_point_equivalence.isactive', 1)
                  ->select(
                        'college_grade_point_scale.grade_point',
                        'college_grade_point_scale.is_failed',
                        'college_grade_point_scale.letter_equivalence',
                        'college_grade_point_scale.grade_point',
                        'college_grade_point_scale.grade_remarks'
                  )
                  ->get();

            $already_credited_subject = [];
            $credited_subject = [];


            foreach ($creditedsubj as $item) {
                  array_push($existingsubj, $item->prospectusID);
            }

            if ($credsubj) {
                  foreach ($credsubj as $item) {
                        $credprospectus[] = ['prospectusid' => $item['prospectusid'], 'gpa' => $item['gpa']];
                  }

                  $subjToAdd = array_diff(array_column($credprospectus, 'prospectusid'), $existingsubj);

                  $result = [];

                  foreach ($subjToAdd as $prospectusID) {

                        $gpa = array_values(array_filter($credprospectus, function ($item) use ($prospectusID) {
                              return $item['prospectusid'] == $prospectusID;
                        }))[0]['gpa'] ?? null;

                        $result[] = ['prospectusid' => $prospectusID, 'gpa' => $gpa];

                  }

                  foreach ($result as $item) {
                        $exist = DB::table('college_stud_term_grades')
                              ->where('studid', $studid)
                              ->where('semid', $semidstud)
                              ->where('syid', $syid)
                              ->where('prospectusid', $item['prospectusid'])
                              ->where('deleted', 0)
                              ->count();

                        $prospectustable = DB::table('college_prospectus')
                              ->where('id', $item['prospectusid'])
                              ->select(
                                    'subjDesc',
                                    'subjCode',
                                    'credunits',
                                    'lecunits',
                                    'labunits'
                              )
                              ->get();
                        if ($exist == 0) {
                              DB::table('college_credsubj')
                                    ->insert([
                                          'schoolID' => $headerid,
                                          'levelID' => $levelid,
                                          'semID' => $semid,
                                          'prospectusID' => $item['prospectusid'],
                                          'studID' => $studid,
                                          'lecunits' => $prospectustable[0]->lecunits,
                                          'labunits' => $prospectustable[0]->labunits,
                                          'credunits' => $prospectustable[0]->credunits,
                                          'subjDesc' => $prospectustable[0]->subjDesc,
                                          'subjCode' => $prospectustable[0]->subjCode,
                                          'gpa' => $item['gpa'],
                                          'status' => 0,
                                          'createdby' => auth()->user()->id,
                                          'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);

                              $sorted_equivalence = collect($equivalence)->sortBy('grade_point');

                              $matching_equivalence = $sorted_equivalence->last(function ($eq) use ($item) {
                                    return (float) $eq->grade_point <= $item['gpa'];
                              });

                              $remarks = $matching_equivalence ? $matching_equivalence->grade_remarks : "No equivalence found";

                              DB::table('college_stud_term_grades')
                                    ->insert([
                                          'studid' => $studid,
                                          'semid' => $semidstud,
                                          'syid' => $syid,
                                          'prospectusid' => $item['prospectusid'],
                                          'final_grade_transmuted' => $item['gpa'],
                                          'final_remarks' => $remarks,
                                          'createdby' => auth()->user()->id,
                                          'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                                    ]);

                              array_push($credited_subject, [
                                    'subjDesc' => $prospectustable[0]->subjDesc,
                                    'subjCode' => $prospectustable[0]->subjCode
                              ]);

                        } else {
                              array_push($already_credited_subject, [
                                    'subjDesc' => $prospectustable[0]->subjDesc,
                                    'subjCode' => $prospectustable[0]->subjCode
                              ]);
                        }

                  }
                  $existing = array_intersect(array_column($credprospectus, 'prospectusid'), $existingsubj);

                  $existingResult = [];

                  foreach ($existing as $prospectusID) {
                        $gpa = array_values(array_filter($credprospectus, function ($item) use ($prospectusID) {
                              return $item['prospectusid'] == $prospectusID;
                        }))[0]['gpa'] ?? null;

                        $existingResult[] = ['prospectusid' => $prospectusID, 'gpa' => $gpa];
                  }

                  foreach ($existingResult as $item) {
                        DB::table('college_credsubj')
                              ->where('schoolID', $headerid)
                              ->where('levelID', $levelid)
                              ->where('semID', $semid)
                              ->where('prospectusID', $item['prospectusid'])
                              ->where('studID', $studid)
                              ->update([
                                    'gpa' => $item['gpa'],
                                    'updatedby' => auth()->user()->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }
            } else {
                  $credprospectus = [['prospectusID' => 0]];
            }

            $subjToDelete = array_diff($existingsubj, array_column($credprospectus, 'prospectusid'));
            $result = [];
            foreach ($subjToDelete as $item) {
                  $result[] = ['prospectusid' => $item];
            }
            foreach ($result as $item) {
                  DB::table('college_credsubj')
                        ->where('schoolID', $headerid)
                        ->where('levelID', $levelid)
                        ->where('semID', $semid)
                        ->where('prospectusID', $item['prospectusid'])
                        ->where('studID', $studid)
                        ->where('status', 0)
                        ->update([
                              'deleted' => 1,
                              'deletedby' => auth()->user()->id,
                              'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);
            }

            $data = [
                  'credited_subject' => $credited_subject,
                  'already_credited_subject' => $already_credited_subject
            ];

            return $data;
      }

      function delete_school_credit(Request $request)
      {
            $id = $request->id;
            $syid = $request->syid;
            $semid = $request->semid;
            $student = DB::table('college_credsubj')
                  ->where('schoolid', $id)
                  ->select(
                        'studid',
                        'prospectusid',
                  )
                  ->get();
            $prospectusIds = $student->pluck('prospectusid')->toArray();
            $studid = $student->first()->studid;



            DB::table('college_credsubj')
                  ->where('schoolid', $id)
                  ->update([
                        'deleted' => 1,
                        'deletedby' => auth()->user()->id,
                        'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                  ]);

            DB::table('college_credschool')
                  ->where('id', $id)
                  ->update([
                        'deleted' => 1,
                        'deletedby' => auth()->user()->id,
                        'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                  ]);

            $credschool = DB::table('college_credschool')
                  ->where('id', $id)
                  ->select(
                        'studid',
                        'sydesc',
                        'semid',
                        'schoolname',
                        'schooladdress'
                  )
                  ->first();

            $tor = DB::table('college_tor')
                  ->where('studid', $credschool->studid)
                  ->where('syid', $syid)
                  ->where('sydesc', 'like', '%' . $credschool->sydesc . '%')
                  ->where('schoolname', 'like', '%' . $credschool->schoolname . '%')
                  ->where('schooladdress', 'like', '%' . $credschool->schooladdress . '%')
                  ->where('semid', $credschool->semid)
                  ->select('id')
                  ->first();

            DB::table('college_tor')
                  ->where('id', $tor->id)
                  ->update([
                        'deleted' => 1,
                        'deletedby' => auth()->user()->id,
                        'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                  ]);

            DB::table('college_torgrades')
                  ->where('torid', $tor->id)
                  ->update([
                        'deleted' => 1,
                        'deletedby' => auth()->user()->id,
                        'deleteddatetime' => \Carbon\Carbon::now('Asia/Manila')
                  ]);
      }

      public function get_subj_credit(Request $request)
      {
            $studid = $request->studid;

            $credited_subject = DB::table('college_credsubj')
                  ->where('studid', $studid)
                  ->where('status', 0)
                  ->where('deleted', 0)
                  ->select(
                        'studid',
                        'prospectusid',
                        'gpa',
                  )
                  ->get();

            return $credited_subject;
      }

      public function loaded_subjects(Request $request)
      {
            $studid = $request->studid;


            $school = DB::table('schoolinfo')
                  ->first();



            $subjects = DB::table('college_loadsubject')
                  ->join('college_prospectus', 'college_loadsubject.subjectID', '=', 'college_prospectus.id')
                  ->join('college_stud_term_grades', 'college_prospectus.id', '=', 'college_stud_term_grades.prospectusid')
                  ->where('college_loadsubject.studid', $studid)
                  ->where('college_stud_term_grades.studid', $studid)
                  ->where('college_stud_term_grades.deleted', 0)
                  ->where(function ($query) {
                        $query->where('college_loadsubject.deleted', 0)
                              ->orWhere(function ($query2) {
                                    $query2->where('college_loadsubject.deleted', 1)
                                          ->where('college_loadsubject.isdropped', 1);
                              });
                  })
                  ->select(
                        'college_loadsubject.studid',
                        'college_loadsubject.syid',
                        'college_loadsubject.semid',
                        'college_loadsubject.subjectID',
                        'college_loadsubject.isDropped',
                        'college_prospectus.subjDesc',
                        'college_prospectus.subjCode',
                        'college_stud_term_grades.prelim_status',
                        'college_stud_term_grades.midterm_status',
                        'college_stud_term_grades.prefinal_status',
                        'college_stud_term_grades.final_status',

                        'college_stud_term_grades.final_remarks',
                  );



            if ($school->abbreviation == "APMC") {

                  $subjects = $subjects->addSelect(
                        'college_stud_term_grades.prelim_grade as prelim_transmuted',
                        'college_stud_term_grades.midterm_grade as midterm_transmuted',
                        'college_stud_term_grades.prefinal_grade as prefinal_transmuted',
                        'college_stud_term_grades.final_grade as final_transmuted',
                        'college_stud_term_grades.final_grade_average as final_grade_transmuted',
                  );
            } else {
                  $subjects = $subjects->addSelect(
                        'college_stud_term_grades.prelim_transmuted',
                        'college_stud_term_grades.midterm_transmuted',
                        'college_stud_term_grades.prefinal_transmuted',
                        'college_stud_term_grades.final_transmuted',
                        'college_stud_term_grades.final_grade_transmuted',
                  );
            }

            $subjects = $subjects->get();


            return $subjects;
      }


      public static function transfer_loading(Request $request)
      {
            // Get data from college_studsched
            $records = DB::table('college_studsched')
                  ->join('college_classsched', function ($join) {
                        $join->on('college_studsched.schedid', '=', 'college_classsched.id');
                        $join->where('college_classsched.deleted', 0);
                  })
                  ->select(
                        'college_studsched.*',
                        'college_classsched.subjectID',
                        'college_classsched.sectionID',
                        'college_classsched.syid',
                        'college_classsched.semesterID as semid',
                  )
                  ->get();


            foreach ($records as $record) {
                  DB::table('college_loadsubject')->insert([
                        'studid' => $record->studid,
                        'schedid' => $record->schedid,
                        'isDropped' => $record->dropped,
                        'deleted' => $record->deleted,
                        'createddatetime' => $record->createddatetime ?? \Carbon\Carbon::now('Asia/Manila'),// Use Carbon arbon::now(),
                        'deleteddatetime' => $record->droppeddatetime,
                        'updateddatetime' => $record->updateddatetime,
                        'createdby' => $record->createdby,
                        'updatedby' => $record->updatedby,
                        'deletedby' => $record->deletedby,
                        // Set NULL for fields not present in the source
                        'syid' => $record->syid,
                        'semid' => $record->semid,
                        'subjectID' => $record->subjectID,
                        'sectionID' => $record->sectionID,
                        'is_active' => 1, // or 0 depending on your logic
                  ]);
            }

            return response()->json(['message' => 'Data transfer complete.']);


      }


      public static function transfer_grades(Request $request)
      {

            $records = DB::table('college_studentprospectus')
                  ->where('deleted', 0)
                  ->get();


            foreach ($records as $record) {

                  if ($record->prelemstatus == 3) {
                        $record->prelemstatus = 6;
                  } else if ($record->prelemstatus == 4) {
                        $record->prelemstatus = 5;
                  } else if ($record->prelemstatus == 7) {
                        $record->prelemstatus = 2;
                  } else if ($record->prelemstatus == 8) {
                        $record->prelemstatus = 7;
                  } else if ($record->prelemstatus == 9) {
                        $record->prelemstatus = 8;
                  } else if ($record->prelemstatus == 2) {
                        $record->prelemstatus = 3;
                  }

                  if ($record->midtermstatus == 3) {
                        $record->midtermstatus = 6;
                  } else if ($record->midtermstatus == 4) {
                        $record->midtermstatus = 5;
                  } else if ($record->midtermstatus == 7) {
                        $record->midtermstatus = 2;
                  } else if ($record->midtermstatus == 8) {
                        $record->midtermstatus = 7;
                  } else if ($record->midtermstatus == 9) {
                        $record->midtermstatus = 8;
                  } else if ($record->midtermstatus == 2) {
                        $record->midtermstatus = 3;
                  }

                  if ($record->prefistatus == 3) {
                        $record->prefistatus = 6;
                  } else if ($record->prefistatus == 4) {
                        $record->prefistatus = 5;
                  } else if ($record->prefistatus == 7) {
                        $record->prefistatus = 2;
                  } else if ($record->prefistatus == 8) {
                        $record->prefistatus = 7;
                  } else if ($record->prefistatus == 9) {
                        $record->prefistatus = 8;
                  } else if ($record->prefistatus == 2) {
                        $record->prefistatus = 3;
                  }


                  if ($record->finalstatus == 3) {
                        $record->finalstatus = 6;
                  } else if ($record->finalstatus == 4) {
                        $record->finalstatus = 5;
                  } else if ($record->finalstatus == 7) {
                        $record->finalstatus = 2;
                  } else if ($record->finalstatus == 8) {
                        $record->finalstatus = 7;
                  } else if ($record->finalstatus == 9) {
                        $record->finalstatus = 8;
                  } else if ($record->finalstatus == 2) {
                        $record->finalstatus = 3;
                  }

                  if ($record->fg >= 3.5) {
                        $record->fgremarks = 'FAILED';
                  } else {
                        $record->fgremarks = 'PASSED';
                  }




                  DB::table('college_stud_term_grades')->insert([
                        'prospectusID' => $record->prospectusID,
                        'syid' => $record->syid,
                        'semid' => $record->semid,
                        'schedid' => $record->schedid,
                        'studid' => $record->studid,

                        // Grades
                        'prelim_grade' => $record->prelemgrade,
                        'midterm_grade' => $record->midtermgrade,
                        'prefinal_grade' => $record->prefigrade,
                        'final_grade' => $record->finalgrade,
                        'final_grade_average' => $record->fg,
                        'final_remarks' => $record->fgremarks,

                        // Transmuted grades — not available, so we set null
                        'prelim_transmuted' => $record->prelemgrade,
                        'midterm_transmuted' => $record->midtermgrade,
                        'prefinal_transmuted' => $record->prefigrade,
                        'final_transmuted' => $record->finalgrade,
                        'final_grade_transmuted' => $record->fg,

                        // Statuses
                        'prelim_status' => $record->prelemstatus,
                        'midterm_status' => $record->midtermstatus,
                        'prefinal_status' => $record->prefistatus,
                        'final_status' => $record->finalstatus,

                        // Updated by / datetime — mapping only final update field
                        'updatedby' => $record->updatedby,
                        'updateddatetime' => $record->updateddatetime,

                        // Created / Deleted
                        'createdby' => $record->createdby,
                        'createddatetime' => $record->createddatetime,
                        'deletedby' => $record->deletedby,
                        'deleteddatetime' => $record->deletedatetime,
                        'deleted' => $record->deleted,

                        // Mark final grading flag if finalgrade exists
                        'is_final_grading' => 1,
                  ]);
            }

            return response()->json(['message' => 'Student grades transferred successfully.']);
      }

      public static function transfer_teacher(Request $request)
      {


            $records = DB::table('college_classsched')
                  ->where('deleted', 0)
                  ->where('teacherid', '!=', null)
                  ->get();

            foreach ($records as $record) {
                  for ($i = 1; $i <= 4; $i++) {

                        DB::table('college_instructor')
                              ->insert([
                                    'term' => $i,
                                    'teacherid' => $record->teacherID,
                                    'classschedid' => $record->id,
                                    'updateddatetime' => \Carbon\Carbon::now('Asia/Manila')
                              ]);
                  }

            }

            return response()->json(['message' => 'Teachers transferred successfully.']);



      }

      public static function transfer_labfeees(Request $request)
      {


            $records = DB::table('labfees')
                  ->where('labfees.deleted', 0)
                  ->get();


            DB::table('labfees')->truncate();


            foreach ($records as $record) {
                  $prospectus = DB::table('college_prospectus')
                        ->where('subjectID', $record->subjid)
                        ->where('deleted', 0)
                        ->select('id')
                        ->get();

                  foreach ($prospectus as $p) {
                        DB::table('labfees')->insert([
                              'subjid' => $p->id,
                              'amount' => $record->amount,
                              'createdby' => auth()->user()->id,
                              'createddatetime' => \Carbon\Carbon::now('Asia/Manila')
                        ]);
                  }
            }

            return response()->json(['message' => 'Labfees transferred successfully.']);



      }

}
