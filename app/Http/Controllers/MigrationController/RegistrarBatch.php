<?php

namespace App\Http\Controllers\MigrationController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use ZipArchive;
use PDF;

class RegistrarBatch extends Controller
{
    public function downloadByGrade(Request $request)
    {
        $syid = $request->get('syid') ?? DB::table('sy')->where('isactive', 1)->value('id');
        $acadprogid = $request->get('acadprogid') ?? 2;

        $gradelevels = DB::table('gradelevel')
            ->where('acadprogid', $acadprogid)
            ->where('deleted', 0)
            ->orderBy('sortid')
            ->get();

        $tempDir = storage_path('app/public/masterlists_' . time());
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        foreach ($gradelevels as $grade) {
            $gradeFolder = $tempDir . '/' . preg_replace('/[^\w\-]/', '_', $grade->levelname);
            if (!file_exists($gradeFolder)) {
                mkdir($gradeFolder, 0777, true);
            }

            $sections = DB::table('sections')
                ->where('levelid', $grade->id)
                ->where('deleted', 0)
                ->get();

            if ($sections->isEmpty()) {
                continue;
            }

            foreach ($sections as $section) {
                // --- Fetch students using the same logic as ReportsController ---
                $students = DB::table('studinfo')
                    ->select(
                        'studinfo.id as studentid',
                        'studinfo.lrn as student_lrn',
                        'studinfo.sid as student_idnumber',
                        'studinfo.firstname as student_firstname',
                        'studinfo.middlename as student_middlename',
                        'studinfo.lastname as student_lastname',
                        'studinfo.suffix as student_suffix',
                        DB::raw('UPPER(`gender`) as student_gender'),
                        DB::raw('LOWER(`gender`) as gender'),
                        'studinfo.dob',
                        'studinfo.semail',
                        'studinfo.mothername',
                        'studinfo.mcontactno',
                        'studinfo.moccupation',
                        'studinfo.fathername',
                        'studinfo.fcontactno',
                        'studinfo.foccupation',
                        'studinfo.guardianname',
                        'studinfo.contactno as studentcontactno',
                        'studinfo.street',
                        'studinfo.barangay',
                        'studinfo.city',
                        'studinfo.province',
                        'studinfo.gcontactno',
                        'studinfo.ismothernum',
                        'studinfo.isfathernum',
                        'studinfo.isguardannum',
                        'enrolledstud.sectionid',
                        'enrolledstud.levelid',
                        'sections.sectionname as sectionname',
                        'gradelevel.acadprogid',
                        'gradelevel.levelname as gradelevelname',
                        'grantee.description as grantee',
                        DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname"),
                        'enrolledstud.studstatus',
                        'studentstatus.description'
                    )
                    ->join('enrolledstud', 'studinfo.id', '=', 'enrolledstud.studid')
                    ->leftJoin('studentstatus', 'enrolledstud.studstatus', '=', 'studentstatus.id')
                    ->join('sections', 'enrolledstud.sectionid', '=', 'sections.id')
                    ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
                    ->leftJoin('grantee', 'studinfo.grantee', '=', 'grantee.id')
                    ->where('enrolledstud.studstatus', '!=', '0')
                    ->where('enrolledstud.studstatus', '<=', '5')
                    ->where('enrolledstud.deleted', '0')
                    ->where('studinfo.deleted', '0')
                    ->where('enrolledstud.syid', $syid)
                    ->where('enrolledstud.sectionid', $section->id)
                    ->whereIn('enrolledstud.studstatus', [1, 2, 4])
                    ->distinct('studentid')
                    ->get();

                $sh_enrolledstud = DB::table('studinfo')
                    ->select(
                        'studinfo.id as studentid',
                        'studinfo.lrn as student_lrn',
                        'studinfo.sid as student_idnumber',
                        'studinfo.firstname as student_firstname',
                        'studinfo.middlename as student_middlename',
                        'studinfo.lastname as student_lastname',
                        'studinfo.suffix as student_suffix',
                        DB::raw('UPPER(`gender`) as student_gender'),
                        DB::raw('LOWER(`gender`) as gender'),
                        'studinfo.dob',
                        'studinfo.semail',
                        'studinfo.mothername',
                        'studinfo.mcontactno',
                        'studinfo.moccupation',
                        'studinfo.fathername',
                        'studinfo.fcontactno',
                        'studinfo.foccupation',
                        'studinfo.guardianname',
                        'studinfo.contactno as studentcontactno',
                        'studinfo.street',
                        'studinfo.barangay',
                        'studinfo.city',
                        'studinfo.province',
                        'studinfo.gcontactno',
                        'studinfo.ismothernum',
                        'studinfo.isfathernum',
                        'studinfo.isguardannum',
                        'sh_enrolledstud.sectionid',
                        'sh_enrolledstud.levelid',
                        'sections.sectionname as sectionname',
                        'gradelevel.levelname as gradelevelname',
                        'gradelevel.acadprogid',
                        'grantee.description as grantee',
                        'sh_strand.id as strandid',
                        'sh_strand.strandname',
                        'sh_strand.strandcode',
                        'sh_track.trackname',
                        DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname"),
                        'sh_enrolledstud.studstatus',
                        'studentstatus.description'
                    )
                    ->join('sh_enrolledstud', 'studinfo.id', '=', 'sh_enrolledstud.studid')
                    ->leftJoin('studentstatus', 'sh_enrolledstud.studstatus', '=', 'studentstatus.id')
                    ->join('sections', 'sh_enrolledstud.sectionid', '=', 'sections.id')
                    ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
                    ->leftJoin('grantee', 'studinfo.grantee', '=', 'grantee.id')
                    ->join('sh_strand', 'sh_enrolledstud.strandid', '=', 'sh_strand.id')
                    ->join('sh_track', 'sh_strand.trackid', '=', 'sh_track.id')
                    ->where('sh_enrolledstud.studstatus', '!=', '0')
                    ->where('sh_enrolledstud.studstatus', '<=', '5')
                    ->where('sh_enrolledstud.deleted', '0')
                    ->where('studinfo.deleted', '0')
                    ->where('sh_enrolledstud.syid', $syid)
                    ->where('sh_enrolledstud.sectionid', $section->id)
                    ->whereIn('sh_enrolledstud.studstatus', [1, 2, 4])
                    ->distinct('studentid')
                    ->get();

                $college_enrolledstud = DB::table('studinfo')
                    ->select(
                        'studinfo.id as studentid',
                        'studinfo.lrn as student_lrn',
                        'studinfo.sid as student_idnumber',
                        'studinfo.firstname as student_firstname',
                        'studinfo.middlename as student_middlename',
                        'studinfo.lastname as student_lastname',
                        'studinfo.suffix as student_suffix',
                        DB::raw('UPPER(`gender`) as student_gender'),
                        DB::raw('LOWER(`gender`) as gender'),
                        'studinfo.dob',
                        'studinfo.semail',
                        'studinfo.mothername',
                        'studinfo.mcontactno',
                        'studinfo.moccupation',
                        'studinfo.fathername',
                        'studinfo.fcontactno',
                        'studinfo.foccupation',
                        'studinfo.guardianname',
                        'studinfo.contactno as studentcontactno',
                        'studinfo.street',
                        'studinfo.barangay',
                        'studinfo.city',
                        'studinfo.province',
                        'studinfo.gcontactno',
                        'studinfo.ismothernum',
                        'studinfo.isfathernum',
                        'studinfo.isguardannum',
                        'college_enrolledstud.sectionid',
                        'college_enrolledstud.yearLevel as levelid',
                        'college_sections.sectionDesc as sectionname',
                        'gradelevel.levelname as gradelevelname',
                        'gradelevel.acadprogid',
                        'grantee.description as grantee',
                        'college_courses.id as strandid',
                        'college_courses.id as courseid',
                        'college_courses.collegeid',
                        'college_courses.courseDesc as strandname',
                        'college_courses.courseabrv as strandcode',
                        'college_colleges.collegeDesc as trackname',
                        DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname"),
                        'college_enrolledstud.studstatus',
                        'college_year.yearDesc',
                        'studentstatus.description'
                    )
                    ->join('college_enrolledstud', 'studinfo.id', '=', 'college_enrolledstud.studid')
                    ->leftJoin('college_year', 'college_enrolledstud.yearLevel', '=', 'college_year.levelid')
                    ->leftJoin('studentstatus', 'college_enrolledstud.studstatus', '=', 'studentstatus.id')
                    ->leftJoin('college_sections', 'college_enrolledstud.sectionid', '=', 'college_sections.id')
                    ->join('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
                    ->leftJoin('grantee', 'studinfo.grantee', '=', 'grantee.id')
                    ->join('college_courses', 'college_enrolledstud.courseid', '=', 'college_courses.id')
                    ->join('college_colleges', 'college_courses.collegeid', '=', 'college_colleges.id')
                    ->where('college_enrolledstud.studstatus', '!=', '0')
                    ->where('college_enrolledstud.studstatus', '<=', '5')
                    ->where('college_enrolledstud.deleted', '0')
                    ->where('studinfo.deleted', '0')
                    ->where('college_enrolledstud.syid', $syid)
                    ->where('college_enrolledstud.sectionid', $section->id)
                    ->whereIn('college_enrolledstud.studstatus', [1, 2, 4])
                    ->distinct('studentid')
                    ->get();

                $students = collect();
                $students = $students->merge($enrolledstud);
                $students = $students->merge($sh_enrolledstud);
                $students = $students->merge($college_enrolledstud);
                $students = $students->unique('studentid');
                $students = $students->sortBy('studentname')->values()->all();

                // Gender count
                $maleCount = 0;
                $femaleCount = 0;
                foreach ($students as $student) {
                    if (strtoupper($student->student_gender) == "MALE") $maleCount++;
                    if (strtoupper($student->student_gender) == "FEMALE") $femaleCount++;
                }
                $genderCount = ['maleCount' => $maleCount, 'femaleCount' => $femaleCount];

                // School info
                $schoolinfo = DB::table('schoolinfo')
                    ->select(
                        'schoolinfo.schoolid',
                        'schoolinfo.schoolname',
                        'schoolinfo.authorized',
                        'refcitymun.citymunDesc as division',
                        'schoolinfo.district',
                        'schoolinfo.address',
                        'schoolinfo.picurl',
                        'refregion.regDesc as region'
                    )
                    ->leftJoin('refregion', 'schoolinfo.region', '=', 'refregion.regCode')
                    ->leftJoin('refcitymun', 'schoolinfo.division', '=', 'refcitymun.citymunCode')
                    ->get();

                $schoolyear = DB::table('sy')->where('id', $syid)->get();

                // Adviser/teacher
                $teacher = DB::table('sectiondetail')
                    ->select('teacher.title', 'teacher.lastname', 'teacher.firstname', 'teacher.middlename', 'teacher.suffix')
                    ->join('teacher', 'sectiondetail.teacherid', '=', 'teacher.id')
                    ->where('sectionid', $section->id)
                    ->where('syid', $syid)
                    ->where('sectiondetail.deleted', '0')
                    ->first();

                if ($teacher) {
                    $middlename = $teacher->middlename ? $teacher->middlename[0] . '.' : '';
                    $teacher = $teacher->title . ' ' . $teacher->firstname . ' ' . $middlename . ' ' . $teacher->lastname . ' ' . $teacher->suffix;
                } else {
                    $teacher = null;
                }

                // --- Generate PDF using the same template ---
                try {
                    $pdf = PDF::loadView('registrar.pdf.pdf_studentmasterlist', [
                        'sectionid' => $section->id,
                        'roomname' => '',
                        'data' => $students,
                        'schoolinfo' => $schoolinfo,
                        'genderCount' => $genderCount,
                        'schoolyear' => $schoolyear,
                        'esc' => 0,
                        'teacher' => $teacher,
                        'academicprogram' => '',
                        'acadprogid' => $acadprogid,
                        'levelid' => $section->levelid ?? null,
                        'syid' => $syid,
                        'format' => 'lastname_first',
                    ]);
                    $filename = $gradeFolder . '/' . preg_replace('/[^\w\-]/', '_', $section->sectionname) . '.pdf';
                    $pdf->save($filename);

                    if (file_exists($filename)) {
                        \Log::info('PDF created: ' . $filename);
                    } else {
                        \Log::warning('PDF NOT created: ' . $filename);
                    }
                } catch (\Exception $e) {
                    \Log::error('PDF generation failed for section ' . $section->sectionname . ': ' . $e->getMessage());
                    continue;
                }
                \Log::info('Section: ' . $section->sectionname . ' | Students: ' . count($students));
            }
        }

        // After all PDFs are generated, before ZIP creation
        $createdFiles = [];
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($tempDir)) as $file) {
            if ($file->isFile()) {
                $createdFiles[] = $file->getRealPath();
            }
        }
        if (empty($createdFiles)) {
            return response('No PDF files were created. Check PDF generation, view errors, and permissions.', 500);
        }

        // Create ZIP
        $zipFile = storage_path('app/public/Student Masterlist.zip');
        $zip = new ZipArchive;
        if ($zip->open($zipFile, ZipArchive::CREATE) === TRUE) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($tempDir),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );
            foreach ($files as $name => $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($tempDir) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
        } else {
            return response('Failed to create ZIP file.', 500);
        }

        if (!file_exists($zipFile)) {
            return response('ZIP file was not created.', 500);
        }

        \File::deleteDirectory($tempDir);

        return response()->download($zipFile)->deleteFileAfterSend(true);
    }

    public function showStudentMasterlistModal(Request $request)
    {
        $sy = $request->get('sy') ?? DB::table('sy')->where('isactive', 1)->value('id');
        return view('superadmin.pages.migration.modals.studentmasterlist', [
            'sy' => $sy,
        ]);
    }
}
