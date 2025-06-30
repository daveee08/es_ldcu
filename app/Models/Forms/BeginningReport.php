<?php

namespace App\Models\Forms;

use Illuminate\Database\Eloquent\Model;
use DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class BeginningReport extends Model
{
    
    public static function beginning_report(
        $syid = null,
        $semid = null,
        $courseid = null,
        $sectionid = null,
        $levelid = null,
        $students = null,
        $schedules = null,  
        $grades = null      
    ) {
        $promotional_report = [];
    
        // ✅ Filter students by `yearLevel` to prevent duplication
        if ($levelid !== null) {
            $students = collect($students)->where('yearLevel', $levelid)->values();
        } else {
            $students = collect($students);
        }

        
        // ✅ Group schedules & grades by `studid` for quick access
        $grouped_schedules = collect($schedules)->groupBy('studid');  
        $grouped_grades = collect($grades)
            ->groupBy(function ($item) { 
                return trim((string) $item->studid); // Use ->studid instead of ['studid']
            })
            ->map(function ($items) {
                return $items->keyBy('subjectID');
            });

           
        foreach ($students as $enrolled_stud_item) {
            $student_sched = $grouped_schedules->get($enrolled_stud_item->studid, collect([]));
            $student_grades = $grouped_grades->get((string) $enrolled_stud_item->studid, collect([]));

            // if ($enrolled_stud_item->studid == 4129) {
            //     dd([
            //         'student' => $enrolled_stud_item->studid,
            //         'student_sched' => $student_sched->toArray(),
            //         'student_grades' => $student_grades->toArray()
            //     ]);
                
            // }
            $sched_array = [];
            foreach ($student_sched as $sched) {
                // ✅ Ensure correct grade lookup using `subjectID`
                $final_grade = optional($student_grades->get($sched->subjectID))->final_grade_transmuted;
                $final_grade = $final_grade == 0.00 ? null : $final_grade;
                $sched_array[] = (object)['data' => $sched->subjCode];  
                $sched_array[] = (object)['data' => $sched->credunits]; 
            }
    
            // ✅ Ensure at least 20 slots (10 subjects + 10 grades)
            while (count($sched_array) < 20) {
                $sched_array[] = (object)['data' => ""];
            }
    
            $promotional_report[] = (object)[
                'student' => $enrolled_stud_item->lastname . ', ' . $enrolled_stud_item->firstname,
                'gender' => $enrolled_stud_item->gender,
                'levelid' => $enrolled_stud_item->yearLevel,
                'sectionid' => $enrolled_stud_item->sectionid,
                'courseid' => $enrolled_stud_item->courseid,
                'subjects' => $sched_array
            ];
        }
    
        return $promotional_report;
    }

    public static function beginning_report_excel(
        $syid = null, 
        $semid = null, 
        $courseid = null, 
        $sectionid = null,
        $generate = null
    ){
        ini_set('memory_limit', '1024M'); // Increase memory limit to 1GB
    
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        $courses = DB::table('college_courses')
                    ->where('deleted', 0);
    
        if ($courseid != null) {
            $courses->where('id', $courseid);
        }
    
        $courses = $courses->select('id', 'courseDesc', 'courseabrv')->get();
    
        $styleArray = [
            'font' => ['size' => 11, 'name' => 'Agency FB']
        ];
    
        $styleArray_course = [
            'font' => ['bold' => true, 'size' => 14]
        ];
    
        $schoolInfo = DB::table('schoolinfo')->select('schoolname', 'address')->first();
        $semester = DB::table('semester')->where('id', $semid)->first()->semester;
        $sy = DB::table('sy')->where('id', $syid)->first()->sydesc;
    
        $x = 1;
        
        // Headers
        $headerData = [
            ['A'.$x.':AB'.$x, 'PROMOTIONAL REPORT (HEMIS)'],
            ['A'.(++$x).':AB'.$x, $semester.': S.Y. '.$sy],
            ['A'.(++$x).':L'.$x, 'SCHOOL: '.$schoolInfo->schoolname],
            ['M'.$x.':Z'.$x, 'REGION: 10', 'right'],
            ['A'.(++$x).':L'.$x, 'ADDRESS: '.$schoolInfo->address],
        ];
    
        foreach ($headerData as $header) {
            [$mergeCells, $text, $align] = array_pad($header, 3, 'center');
            $sheet->mergeCells($mergeCells);
            $sheet->setCellValue(explode(':', $mergeCells)[0], $text);
            $sheet->getStyle($mergeCells)->applyFromArray($styleArray_course);
            $sheet->getStyle($mergeCells)->getAlignment()->setHorizontal($align);
        }

        $x++;

        // Static column headers
        $tableHeaders = [
            ['A'.$x, '#'],
            ['B'.$x, 'STUDENT NAME'],
            ['C'.$x, 'GENDER']
        ];

        // Dynamic headers from D to Y: alternating CODE and UNITS
        $column = 'D';
        $endCol = 'Y';
        $toggle = true;

        while (ord($column) <= ord($endCol)) {
            $tableHeaders[] = [$column.$x, $toggle ? 'CODE' : 'UNITS'];
            $toggle = !$toggle;
            $column = chr(ord($column) + 1);
        }

        foreach ($tableHeaders as [$cell, $text]) {
            $sheet->setCellValue($cell, $text);
            $sheet->getStyle($cell)->applyFromArray($styleArray);
            $sheet->getStyle($cell)->getAlignment()->setHorizontal('center');
        }
    
        $students = DB::table('college_enrolledstud')
            ->join('studinfo', function ($join) {
                $join->on('college_enrolledstud.studid', '=', 'studinfo.id')
                    ->where('studinfo.deleted', 0);
            })
            ->where('college_enrolledstud.deleted', 0)
            ->where('college_enrolledstud.syid', $syid)
            ->where('college_enrolledstud.semid', $semid)
            ->whereIn('college_enrolledstud.studstatus', [1, 2, 4])
            ->when($courseid, function ($query, $courseid) {
                return $query->where('college_enrolledstud.courseid', $courseid);
            })
            // ->when($sectionid, function ($query, $sectionid) {
            //     return $query->where('college_enrolledstud.sectionid', $sectionid);
            // })
            ->select(
                'college_enrolledstud.studid', 
                'studinfo.gender', 
                'studinfo.lastname', 
                'studinfo.firstname', 
                'college_enrolledstud.sectionid', 
                'college_enrolledstud.yearLevel', 
                'college_enrolledstud.courseid'
            )
            ->orderBy('gender', 'desc')
            ->orderBy('lastname', 'asc')
            ->get();
        $studentIDs = $students->pluck('studid'); // 🔹 Optimize `pluck()` usage
        // 🟢 Fetch Schedule
        $sched = DB::table('college_loadsubject')
            ->join('college_classsched', function ($join) {
                $join->on('college_loadsubject.schedid', '=', 'college_classsched.id')
                    ->where('college_classsched.deleted', 0);
            })
            ->join('college_prospectus', function ($join) {
                $join->on('college_classsched.subjectID', '=', 'college_prospectus.id')
                    ->where('college_prospectus.deleted', 0);
            })
            ->where('college_loadsubject.deleted', 0)
            ->whereIn('college_loadsubject.studid', $studentIDs)
            ->where('college_classsched.syID', $syid)
            ->where('college_classsched.semesterID', $semid)
            ->select(
                'college_loadsubject.studid',
                'college_prospectus.id as subjectID', // 🔹 Ensure alias
                'college_prospectus.subjCode',
                'college_prospectus.credunits'
            )
            ->orderBy('college_prospectus.subjCode')
            ->get();
        $subjectIDs = $sched->pluck('subjectID'); // 🔹 Optimize `pluck()` usage
        
        // $student_grades = DB::table('college_stud_term_grades')
        //     ->whereIn('college_stud_term_grades.studid', $studentIDs)
        //     ->whereIn('college_stud_term_grades.prospectusID', $subjectIDs)
        //     ->where('college_stud_term_grades.syid', $syid)
        //     ->where('college_stud_term_grades.semid', $semid)
        //     ->where('college_stud_term_grades.final_status', 5)
        //     ->where('college_stud_term_grades.deleted', 0)
        //     ->select(
        //         'college_stud_term_grades.final_grade_transmuted', 
        //         'college_stud_term_grades.studid as studid', 
        //         'college_stud_term_grades.prospectusID as subjectID' // 🔹 Ensure alias consistency
        //     )
        //     ->get();

        // Check missing grades

            // Check if grades are being correctly grouped
        $allstudents = collect([]);
        
        for ($y = 17; $y <= 20; $y++) {
            $grades = self::beginning_report($syid, $semid, $courseid, $sectionid, $y, $students, $sched);
            $allstudents = $allstudents->merge($grades);
        }
        if($generate == 1){
            return $allstudents->sortBy('student')->values();
        }
        // dd($allstudents);
        $x++;
        $counter = 1;
        $allstudents = collect($allstudents)->sortBy('student')->values();
    
        // Set Column Sizes Before Loop
        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    
        foreach ($allstudents as $grade_item) {
            $sheet->getStyle("A$x:AB$x")->applyFromArray($styleArray);
            $sheet->setCellValue("A$x", "$counter.");
            $sheet->setCellValue("B$x", $grade_item->student);
            // $sheet->getStyle("B$x")->getFont()->setName('Arial')->setBold(false);
            $sheet->setCellValue("C$x", $grade_item->gender);
            $sheet->getStyle("C$x")->getAlignment()->setHorizontal('center');
        
            // Optimize Grade Processing using Array Chunk
            $grades_chunk = array_chunk($grade_item->subjects ?? [], 23); // Chunk to fit within 'A'-'Z'
            $subject_index = 68; // ASCII for 'D'
        
            foreach ($grades_chunk as $grade_set) {
                foreach ($grade_set as $grade) {
                    $letter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($subject_index - 64);
                    // Ensure we're applying styles only to actual grades (assuming grades are numeric)
                    if (is_numeric($grade->data)) {
                        $sheet->setCellValue("$letter$x", $grade->data);
                        $sheet->getStyle("$letter$x")->getFont()->setBold(true);
                        $sheet->getStyle("$letter$x")->getAlignment()->setHorizontal('center');
                    } else {
                        // If it's a subject name, just set the value without any styling
                        $sheet->setCellValue("$letter$x", $grade->data);
                    }
        
                    $subject_index++;
                }
            }
        
            $x++;
            $counter++;
        }
    
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="Beginning Report '.$sy.'.xlsx"');
        $writer->save("php://output");
        exit();
    }



}
