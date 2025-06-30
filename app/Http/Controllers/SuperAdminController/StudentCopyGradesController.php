<?php

namespace App\Http\Controllers\SuperAdminController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;


class StudentCopyGradesController extends Controller
{

    public static function copyofGrades(Request $request)
    {
        $syid = $request->get('syid');
        $strand = null;
        $studid = $request->get('studentid');
        $schoolinfo = DB::table('schoolinfo')->first();

        $schoolyear = DB::table('sy')->where('id', $syid)->first();
        $isireg = false;

        $student = DB::table('enrolledstud')
            ->where('enrolledstud.studid', $studid)
            ->where('enrolledstud.deleted', 0)
            ->where('enrolledstud.syid', $syid)
            ->join('studinfo', function ($join) {
                $join->on('studinfo.id', '=', 'enrolledstud.studid');
                $join->where('studinfo.deleted', 0);
            })
            ->join('sections', function ($join) {
                $join->on('enrolledstud.sectionid', '=', 'sections.id');
                $join->where('sections.deleted', 0);
            })
            ->join('gradelevel', function ($join) {
                $join->on('enrolledstud.levelid', '=', 'gradelevel.id');
                $join->where('gradelevel.deleted', 0);
            })
            ->select(
                'street',
                'barangay',
                'city',
                'province',
                'lastname',
                'firstname',
                'middlename',
                'suffix',
                'studinfo.acadprogid',
                'enrolledstud.levelid',
                'enrolledstud.sectionid',
                'dob',
                'gender',
                'levelname',
                'sections.sectionname',
                'lrn',
                'enrolledstud.studstatdate',
                'enrolledstud.studstatus'
            )
            ->first();

        if (!isset($student->levelid)) {

            $student = DB::table('sh_enrolledstud')
                ->where('sh_enrolledstud.studid', $studid)
                ->where('sh_enrolledstud.deleted', 0)
                ->where('sh_enrolledstud.syid', $syid)
                ->join('studinfo', function ($join) {
                    $join->on('sh_enrolledstud.studid', '=', 'studinfo.id');
                    $join->where('studinfo.deleted', 0);
                })
                ->join('sections', function ($join) {
                    $join->on('sh_enrolledstud.sectionid', '=', 'sections.id');
                    $join->where('sections.deleted', 0);
                })
                ->join('gradelevel', function ($join) {
                    $join->on('sh_enrolledstud.levelid', '=', 'gradelevel.id');
                    $join->where('gradelevel.deleted', 0);
                })
                ->select(
                    'street',
                    'barangay',
                    'city',
                    'province',
                    'lastname',
                    'firstname',
                    'middlename',
                    'suffix',
                    'studinfo.acadprogid',
                    'sh_enrolledstud.strandid',
                    'sh_enrolledstud.levelid',
                    'sh_enrolledstud.sectionid',
                    'dob',
                    'gender',
                    'levelname',
                    'sections.sectionname',
                    'sh_enrolledstud.studstatdate',
                    'sh_enrolledstud.studstatus',
                    'lrn'
                )
                ->first();

            $check_if_regular = DB::table('sh_enrolledstud')
                ->where('sh_enrolledstud.studid', $studid)
                ->where('sh_enrolledstud.deleted', 0)
                ->where('sh_enrolledstud.syid', $syid)
                ->select('strandid', 'sectionid')
                ->groupBy('strandid')
                ->get();

            if (count($check_if_regular) == 1) {
                $check_if_regular = DB::table('sh_enrolledstud')
                    ->where('sh_enrolledstud.studid', $studid)
                    ->where('sh_enrolledstud.deleted', 0)
                    ->where('sh_enrolledstud.syid', $syid)
                    ->select('strandid', 'sectionid')
                    ->groupBy('sectionid')
                    ->get();
            }

            if (count($check_if_regular) > 1) {

                $isireg = true;
                $student = DB::table('sh_enrolledstud')
                    ->where('sh_enrolledstud.studid', $studid)
                    ->where('sh_enrolledstud.deleted', 0)
                    ->where('sh_enrolledstud.syid', $syid)
                    ->where('sh_enrolledstud.semid', 2)
                    ->join('studinfo', function ($join) {
                        $join->on('sh_enrolledstud.studid', '=', 'studinfo.id');
                        $join->where('studinfo.deleted', 0);
                    })
                    ->join('sections', function ($join) {
                        $join->on('sh_enrolledstud.sectionid', '=', 'sections.id');
                        $join->where('sections.deleted', 0);
                    })
                    ->join('gradelevel', function ($join) {
                        $join->on('sh_enrolledstud.levelid', '=', 'gradelevel.id');
                        $join->where('gradelevel.deleted', 0);
                    })
                    ->select(
                        'street',
                        'barangay',
                        'city',
                        'province',
                        'lastname',
                        'firstname',
                        'middlename',
                        'suffix',
                        'studinfo.acadprogid',
                        'sh_enrolledstud.strandid',
                        'sh_enrolledstud.levelid',
                        'sh_enrolledstud.sectionid',
                        'dob',
                        'gender',
                        'levelname',
                        'sections.sectionname',
                        'sh_enrolledstud.studstatdate',
                        'sh_enrolledstud.studstatus',
                        'lrn'
                    )
                    ->first();
            }

            $strand = $student->strandid;
        }


        if (!isset($student->levelid)) {
            return "Student not Found!";
        }

        if ($schoolinfo->schoolid == 404978) { //do not remove
            if ($syid == 3) {
                if ($student->sectionid == 25) {
                    $student->sectionname .= ' (HUMSS 12)';
                } else if ($student->sectionid == 27) {
                    $student->sectionname .= ' (GAS 12)';
                }
            } else if ($syid == 4) {
                if ($student->sectionid == 25) {
                    $student->sectionname .= ' (GAS 12)';
                } else if ($student->sectionid == 27) {
                    $student->sectionname .= ' (HUMSS 12)';
                }
            }
        }

        $acad = $student->acadprogid;
        $gradelevel = $student->levelid;
        $sectionid = $student->sectionid;


        $birthDate = $student->dob; // Your birthdate
        $currentYear = explode("-", $schoolyear->sydesc)[0]; // Current Year
        $birthYear = date('Y', strtotime($birthDate)); // Extracted Birth Year using strtotime and date() function
        $age = $currentYear - $birthYear; // Current year minus birthyear
        $student->age = $age;

        $middlename = explode(" ", $student->middlename);
        $temp_middle = '';
        if ($middlename != null) {
            foreach ($middlename as $middlename_item) {
                if (strlen($middlename_item) > 0) {
                    $temp_middle .= $middlename_item[0] . '.';
                }
            }
        }

        $student->student = $student->lastname . ', ' . $student->firstname . ' ' . $student->suffix . ' ' . $temp_middle;

        $sectioninfo = DB::table('sectiondetail')
            ->where('sectionid', $sectionid)
            ->join('teacher', function ($join) {
                $join->on('sectiondetail.teacherid', '=', 'teacher.id');
                $join->where('teacher.deleted', 0);
            })
            ->select(
                'lastname',
                'firstname',
                'middlename',
                'suffix',
                'teacherid',
                'acadtitle',
                'title'
            )
            ->get();

        $adviser = '';
        $teacherid = null;

        foreach ($sectioninfo as $item) {
            $temp_middle = '';
            $temp_suffix = '';
            $temp_title = '';
            if (isset($item->middlename)) {
                $temp_middle = $item->middlename[0] . '.';
            }
            if (isset($item->title)) {
                $temp_title = $item->title . '. ';
            }
            if (isset($item->suffix)) {
                $temp_suffix = ', ' . $item->suffix;
            }
            $adviser = $temp_title . $item->firstname . ' ' . $temp_middle . ' ' . $item->lastname . $temp_suffix;
            if (isset($item->acadtitle)) {
                $adviser .= ', ' . $item->acadtitle;
            }
            $teacherid = $item->teacherid;
            $item->checked = 0;

        }

        //Attendance

        $semid = $request->get('semid');
        if (!$semid) {
            $semid = DB::table('semester')->where('isactive', 1)->first()->id;
        }
        $attendance_setup = \App\Models\AttendanceSetup\AttendanceSetupData::attendance_setup_list($syid, $gradelevel, $semid, true);
        // return $attendance_setup;
        $lastdate = null;


        if ($student->studstatus == 3 || $student->studstatus == 5 || $student->studstatus == 6) {
            $lastdate = $student->studstatdate;
        }

        foreach ($attendance_setup as $item) {

            $sf2_setup = DB::table('sf2_setup')
                ->where('month', $item->month)
                ->where('year', $item->year)
                ->where('sectionid', $sectionid)
                ->where('sf2_setup.deleted', 0)
                ->join('sf2_setupdates', function ($join) {
                    $join->on('sf2_setup.id', '=', 'sf2_setupdates.setupid');
                    $join->where('sf2_setupdates.deleted', 0);
                })
                ->select('dates')
                ->get();

            if (count($sf2_setup) == 0) {

                $sf2_setup = DB::table('sf2_setup')
                    ->where('month', $item->month)
                    ->where('year', $item->year)
                    ->where('sectionid', $sectionid)
                    ->where('sf2_setup.deleted', 0)
                    ->join('sf2_setupdates', function ($join) {
                        $join->on('sf2_setup.id', '=', 'sf2_setupdates.setupid');
                        $join->where('sf2_setupdates.deleted', 0);
                    })
                    ->select('dates')
                    ->get();

            }


            $temp_days = array();

            foreach ($sf2_setup as $sf2_setup_item) {
                array_push($temp_days, $sf2_setup_item->dates);
            }

            $student_attendance = DB::table('studattendance')
                ->where('studid', $studid)
                ->where('deleted', 0)
                ->whereIn('tdate', $temp_days)
                ->where('syid', $syid);
            if ($lastdate != null) {
                $student_attendance = $student_attendance->where('tdate', '<', $lastdate);
            }

            $student_attendance = $student_attendance->distinct('tdate')
                ->distinct()
                ->select([
                    'present',
                    'absent',
                    'tardy',
                    'cc',
                    'tdate',
                    'syid',
                    'presentam',
                    'presentpm',
                    'absentam',
                    'absentpm',
                    'lateam',
                    'latepm',
                    'ccam',
                    'ccpm'
                ])
                ->get();

            $student_attendance = collect($student_attendance)->unique('tdate')->values();

            $halfday = (collect($student_attendance)->where('presentam', 1)->count() +
                collect($student_attendance)->where('presentpm', 1)->count() +
                collect($student_attendance)->where('absentam', 1)->count() +
                collect($student_attendance)->where('absentpm', 1)->count()) * .5;

            $item->present = collect($student_attendance)->where('lateam', 1)->count() +
                collect($student_attendance)->where('latepm', 1)->count() +
                collect($student_attendance)->where('present', 1)->count() +
                collect($student_attendance)->where('tardy', 1)->count() +
                collect($student_attendance)->where('cc', 1)->count() + $halfday;

            $item->absent = collect($student_attendance)->where('absent', 1)->count() + $halfday;

            $item->late = collect($student_attendance)->where('lateam', 1)->count() +
                collect($student_attendance)->where('latepm', 1)->count() +
                collect($student_attendance)->where('tardy', 1)->count();

        }
        // return $attendance_setup;
        $schoolinfo = DB::table('schoolinfo')
            ->leftJoin('refregion', 'schoolinfo.region', '=', 'refregion.regCode')
            ->leftJoin('refcitymun', 'schoolinfo.division', '=', 'refcitymun.citymunCode')
            ->select('schoolinfo.*', 'refregion.regDesc', 'refcitymun.citymunDesc')
            ->get();


        //core value
        $checkGrades = [];
        $rv = [];

        $ob = \App\Http\Controllers\SuperAdminController\ObservedValuesController::observedvalues_list(null, null, null, $syid, $gradelevel);

        if (count($ob) > 0) {

            $checkGrades = DB::table('grading_system_grades_cv')
                ->leftJoin('grading_system_detail', function ($join) {
                    $join->on('grading_system_grades_cv.gsdid', '=', 'grading_system_detail.id');
                    $join->where('grading_system_detail.deleted', 0);
                })
                ->leftJoin('grading_system', function ($join) use ($ob) {
                    $join->on('grading_system_detail.headerid', '=', 'grading_system.id');
                    $join->where('grading_system.deleted', 0);
                    $join->where('grading_system.id', $ob[0]->headerid);
                })
                ->where('grading_system_grades_cv.deleted', 0)
                ->where('grading_system_grades_cv.studid', $studid)
                ->where('grading_system_grades_cv.syid', $syid)
                ->select(
                    'grading_system_grades_cv.id',
                    'grading_system_grades_cv.q1eval',
                    'grading_system_grades_cv.q2eval',
                    'grading_system_grades_cv.q3eval',
                    'grading_system_grades_cv.q4eval',
                    'grading_system_detail.description',
                    'value',
                    'sort',
                    'type',
                    'group'
                )
                ->orderBy('sort')
                ->get();

            if (count($checkGrades) == 0) {
                $checkGrades = DB::table('grading_system')
                    ->leftJoin('grading_system_detail', function ($join) {
                        $join->on('grading_system.id', '=', 'grading_system_detail.headerid');
                        $join->where('grading_system_detail.deleted', 0);
                    })
                    ->where('grading_system.deleted', 0)
                    ->where('grading_system.id', $ob[0]->headerid)
                    ->select(
                        'grading_system_detail.description',
                        'value',
                        'sort',
                        'type',
                        'group'
                    )
                    ->orderBy('sort')
                    ->get();

                foreach ($checkGrades as $item) {
                    $item->q1eval = null;
                    $item->q2eval = null;
                    $item->q3eval = null;
                    $item->q4eval = null;
                }
            }
            $rv = DB::table('grading_system_ratingvalue')
                ->where('deleted', 0)
                ->where('gsid', $ob[0]->headerid)
                ->orderBy('sort')
                ->get();
        }
        //core value

        $grading_version = DB::table('zversion_control')->where('module', 1)->where('isactive', 1)->first();

        $subjects = [];

        if ($student->levelid == 14 || $student->levelid == 15) {

            //return collect($check_if_regular);
            if ($isireg) {
                $tempgrades = array();
                foreach ($check_if_regular as $item) {
                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades($gradelevel, $studid, $syid, $strand, null, $sectionid, true);
                }

                $temp_grades = array();
                $finalgrade = array();

                $temp_sect = DB::table('sh_enrolledstud')->where('studid', $studid)->where('semid', 1)->where('syid', $syid)->where('deleted', 0)->first();

                if (isset($temp_sect)) {

                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades($gradelevel, $studid, $syid, $temp_sect->strandid, 1, $temp_sect->sectionid);

                    foreach ($studgrades as $item) {
                        if ($item->id == 'G1') {
                            if ($item->semid == 1) {
                                array_push($finalgrade, $item);
                            }
                        } else {
                            if ($item->strandid == $temp_sect->strandid) {
                                array_push($temp_grades, $item);
                            }
                            if ($item->strandid == null) {
                                array_push($temp_grades, $item);
                            }
                        }
                    }

                }

                $temp_sect = DB::table('sh_enrolledstud')->where('studid', $studid)->where('semid', 2)->where('syid', $syid)->where('deleted', 0)->first();

                if (!isset($temp_sect)) {
                    $temp_sect = DB::table('sh_enrolledstud')->where('studid', $studid)->where('semid', 1)->where('syid', $syid)->where('deleted', 0)->first();
                }

                if (isset($temp_sect)) {
                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades($gradelevel, $studid, $syid, $temp_sect->strandid, 2, $temp_sect->sectionid);

                    foreach ($studgrades as $item) {
                        if ($item->id == 'G1') {
                            if ($item->semid == 2) {
                                array_push($finalgrade, $item);
                            }
                        } else {
                            if ($item->strandid == $temp_sect->strandid) {
                                array_push($temp_grades, $item);
                            }
                            if ($item->strandid == null) {
                                array_push($temp_grades, $item);
                            }
                        }
                    }
                    $studgrades = $temp_grades;
                }

            } else {
                if ($grading_version->version == 'v2') {
                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades_gv2($gradelevel, $studid, $syid, $strand, null, $sectionid);
                } else {
                    $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades($gradelevel, $studid, $syid, $strand, null, $sectionid, true);

                }

                $temp_grades = array();
                $finalgrade = array();
                foreach ($studgrades as $item) {
                    if ($item->id == 'G1') {
                        array_push($finalgrade, $item);
                    } else {
                        if (isset($item->strandid)) {
                            if ($item->strandid == $strand) {
                                array_push($temp_grades, $item);
                            }
                            if ($item->strandid == null) {
                                array_push($temp_grades, $item);
                            }
                        }

                    }
                }

                $studgrades = $temp_grades;
            }

            //return $studgrades;




            $studgrades = collect($studgrades)->sortBy('sortid')->values();

        } else {
            if ($grading_version->version == 'v2') {
                $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades_gv2($gradelevel, $studid, $syid, null, null, $sectionid);
            } else {
                $studgrades = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_grades($gradelevel, $studid, $syid, null, null, $sectionid, true);

            }



            $subjects = \App\Http\Controllers\SuperAdminController\StudentGradeEvaluation::sf9_subjects($gradelevel);
            $grades = $studgrades;
            $grades = collect($grades)->sortBy('sortid')->values();
            $finalgrade = collect($grades)->where('id', 'G1')->values();
            unset($grades[count($grades) - 1]);
            $studgrades = collect($grades)->where('isVisible', '1')->values();
        }

        $principal_info = array(
            (object) [
                'name' => null,
                'title' => null
            ]
        );

        // Report Card Style
        $activetemplate = DB::table('reportcard_layouts')
            ->select('templatepath')
            ->where('isactive', 1)
            ->where('deleted', 0)
            ->where('acadprogid', $acad)
            ->first();



        //if($activetemplate->templatepath == '' || $activetemplate->templatepath == null){
        //$activetemplate->templatepath = principalsportal.forms.sf9layout.ica.shs;
        //}

        // $conduct = \App\Http\Controllers\PrincipalControllers\HomeroomConductController::getStudentrgades($syid, $studid, $gradelevel);

        $signatory = DB::table('signatory')
            ->where('form', 'report_card')
            ->where('syid', $syid)
            ->where('acadprogid', $acad)
            ->where('deleted', 0)
            ->select(
                'name',
                'title'
            )
            ->first();

        if (isset($signatory->name)) {
            $principal_info[0]->name = $signatory->name;
            $principal_info[0]->title = $signatory->title;
        }

        $address = '';

        if (strlen($student->street) > 0) {
            $address .= $student->street;
        }
        if (strlen($student->barangay) > 0) {
            if ($address != '') {
                $address .= ', ' . $student->barangay;
            } else {
                $address .= $student->barangay;
            }
        }
        if (strlen($student->city) > 0) {
            if ($address != '') {
                $address .= ', ' . $student->city;
            } else {
                $address .= $student->city;
            }
        }
        if (strlen($student->province) > 0) {
            if ($address != '') {
                $address .= ', ' . $student->province;
            } else {
                $address .= $student->province;
            }
        }

        $student->address = $address;




        $gradelevel = DB::table('gradelevel')
            ->where('id', $student->levelid)
            ->first();


        $eligible = DB::table('gradelevel')
            ->where('sortid', '>', $gradelevel->sortid)
            ->where('deleted', 0)
            ->orderBy('sortid')
            ->first()
            ->levelname;


        if ($acad == 5) {

            $strandInfo = DB::table('sh_strand')
                ->join('sh_track', function ($join) {
                    $join->on('sh_strand.trackid', '=', 'sh_track.id');
                    $join->where('sh_strand.deleted', 0);
                })
                ->where('sh_strand.id', $strand)
                ->select('trackname', 'strandcode', 'strandname')
                ->first();

            $semid = $request->get('semid');
            if ($request->get('semid') == "") {
                $semid = DB::table('semester')
                    ->where('isactive', 1)
                    ->first()
                    ->id;
            }

            $pdf = PDF::loadView('superadmin.pages.reports.copyofgradesshs', compact('semid', 'strandInfo', 'principal_info', 'student', 'subjects', 'studgrades', 'finalgrade', 'attendance_setup', 'schoolyear', 'schoolinfo', 'checkGrades', 'adviser'));
            $pdf->getDomPDF()->set_option("enable_php", true)->set_option("DOMPDF_ENABLE_CSS_FLOAT", true);
            return $pdf->stream();





        } else {


            $pdf = PDF::loadView('superadmin.pages.reports.copyofgradesjhs', compact('principal_info', 'student', 'subjects', 'studgrades', 'finalgrade', 'attendance_setup', 'schoolyear', 'schoolinfo', 'checkGrades', 'adviser'));
            return $pdf->stream();


        }

    }

}
