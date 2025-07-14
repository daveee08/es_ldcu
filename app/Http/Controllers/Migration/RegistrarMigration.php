<?php

namespace App\Http\Controllers\Migration;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PrincipalControllers\DynamicPDFController;
use Illuminate\Http\Request;

class RegistrarMigration extends Controller
{
    public function generateAllMasterlists(Request $request)
    {
        $schoolyears = \DB::table('sy')->orderByDesc('sydesc')->get();
        $sections = \DB::table('sections')->where('deleted', 0)->get();
        $allMasterlists = [];

        foreach ($sections as $section) {
            $students = \DB::table('studinfo')
                ->select(
                    'studinfo.id as studentid',
                    'studinfo.lrn as lrn',
                    'studinfo.sid as sid',
                    'studinfo.firstname as firstname',
                    'studinfo.middlename as middlename',
                    'studinfo.lastname as lastname',
                    'studinfo.suffix as suffix',
                    \DB::raw('UPPER(studinfo.gender) as gender'),
                    'studinfo.dob',
                    'studinfo.semail',
                    'studinfo.mothername',
                    'studinfo.mcontactno',
                    'studinfo.moccupation',
                    'studinfo.fathername',
                    'studinfo.fcontactno',
                    'studinfo.foccupation',
                    'studinfo.guardianname',
                    'studinfo.contactno',
                    'studinfo.street',
                    'studinfo.barangay',
                    'studinfo.city',
                    'studinfo.province',
                    'studinfo.gcontactno',
                    'enrolledstud.sectionid',
                    'enrolledstud.levelid',
                    'sections.sectionname as sectionname',
                    'gradelevel.levelname as gradelevelname',
                    'gradelevel.acadprogid',
                    \DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname"),
                    'enrolledstud.studstatus',
                    'enrolledstud.dateenrolled',
                    'studinfo.mtname',
                    'studinfo.egname',
                    'studinfo.religionname',
                    'studinfo.guardianrelation'
                )
                ->join('enrolledstud', 'studinfo.id', '=', 'enrolledstud.studid')
                ->join('sections', 'enrolledstud.sectionid', '=', 'sections.id')
                ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
                ->where('enrolledstud.studstatus', '!=', '0')
                ->where('enrolledstud.studstatus', '<=', '5')
                ->where('enrolledstud.deleted', '0')
                ->where('studinfo.deleted', '0')
                ->where('enrolledstud.sectionid', $section->id)
                ->distinct('studentid')
                ->orderBy('studinfo.lastname')
                ->get();

            $allMasterlists[$section->sectionname] = $students;
        }
        return view('registrar.pdf.pdf_schoolform1', [
            'allMasterlists' => $allMasterlists,
            'schoolyears' => $schoolyears,
        ]);
    }

    public function downloadStudentMasterlistBatch(Request $request)
    {
        $sy = \DB::table('sy')->orderByDesc('sydesc')->first();
        $syid = $sy->id;
        $sections = \DB::table('sections')->where('deleted', 0)->get();
        $tempDir = storage_path('app/masterlist_batch_' . uniqid());
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }
        foreach ($sections as $section) {
            // Get enrolled students for this section (JHS/Elementary)
            $enrolledstud = \DB::table('studinfo')
                ->select(
                    'studinfo.id as studentid',
                    'studinfo.lrn as student_lrn',
                    'studinfo.sid as student_idnumber',
                    'studinfo.firstname as student_firstname',
                    'studinfo.middlename as student_middlename',
                    'studinfo.lastname as student_lastname',
                    'studinfo.suffix as student_suffix',
                    \DB::raw('UPPER(`gender`) as student_gender'),
                    \DB::raw('LOWER(`gender`) as gender'),
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
                    \DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname"),
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
                ->where('enrolledstud.sectionid', $section->id)
                ->where('enrolledstud.syid', $syid)
                ->distinct('studentid')
                ->get();
            // Get SHS students for this section
            $sh_enrolledstud = \DB::table('studinfo')
                ->select(
                    'studinfo.id as studentid',
                    'studinfo.lrn as student_lrn',
                    'studinfo.sid as student_idnumber',
                    'studinfo.firstname as student_firstname',
                    'studinfo.middlename as student_middlename',
                    'studinfo.lastname as student_lastname',
                    'studinfo.suffix as student_suffix',
                    \DB::raw('UPPER(`gender`) as student_gender'),
                    \DB::raw('LOWER(`gender`) as gender'),
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
                    \DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname"),
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
                ->where('sh_enrolledstud.sectionid', $section->id)
                ->where('sh_enrolledstud.syid', $syid)
                ->distinct('studentid')
                ->get();
            $students = collect($enrolledstud)->merge($sh_enrolledstud)->unique('studentid')->sortBy('studentname')->values();
            if (count($students) == 0) continue;
            $schoolinfo = \DB::table('schoolinfo')
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
            $defaultSchoolinfo = [
                'schoolid' => '',
                'schoolname' => '',
                'authorized' => '',
                'division' => '',
                'district' => '',
                'address' => '',
                'picurl' => '',
                'region' => ''
            ];
            if ($schoolinfo->count() == 0) {
                $schoolinfo = [(object)$defaultSchoolinfo];
            } else {
                // Convert to array, then cast first element to object and ensure all fields are strings
                $row = (array)$schoolinfo[0];
                foreach ($defaultSchoolinfo as $key => $val) {
                    if (!array_key_exists($key, $row) || is_null($row[$key])) {
                        $row[$key] = $val;
                    } else {
                        $row[$key] = (string)$row[$key];
                    }
                }
                $schoolinfo = [(object)$row];
            }
            $genderCount = [
                'maleCount' => $students->where('student_gender', 'MALE')->count(),
                'femaleCount' => $students->where('student_gender', 'FEMALE')->count(),
            ];
            $teacher = \DB::table('sectiondetail')
                ->join('teacher', 'sectiondetail.teacherid', '=', 'teacher.id')
                ->where('sectionid', $section->id)
                ->where('syid', $syid)
                ->where('sectiondetail.deleted', '0')
                ->first();
            $roomname = '';
            try {
                $roomname = \DB::table('sectiondetail')
                    ->where('sectionid', $section->id)
                    ->where('syid', $syid)
                    ->where('deleted', '0')
                    ->first()->roomname ?? '';
            } catch (\Exception $e) {}
            $acadprogid = $section->acadprogid ?? 0;
            $levelid = $section->levelid ?? 0;
            $format = 'lastname_first';
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('registrar.pdf.pdf_studentmasterlist', [
                'sectionid' => $section->id,
                'roomname' => $roomname,
                'data' => $students,
                'schoolinfo' => $schoolinfo,
                'genderCount' => $genderCount,
                'schoolyear' => \DB::table('sy')->where('id', $syid)->get(),
                'esc' => 0,
                'teacher' => $teacher,
                'academicprogram' => '',
                'acadprogid' => $acadprogid,
                'levelid' => $levelid,
                'syid' => $syid,
                'format' => $format
            ])->setPaper('legal', 'landscape');
            $gradeFolder = $students->first()->gradelevelname ?? 'Unknown_Grade';
            $gradeFolderSafe = preg_replace('/[^A-Za-z0-9_\-]/', '_', $gradeFolder);
            $folderPath = $tempDir . '/' . $gradeFolderSafe;
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }
            $filename = $folderPath . '/' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $section->sectionname) . '_masterlist.pdf';
            file_put_contents($filename, $pdf->output());
        }
        $schoolinfo = \DB::table('schoolinfo')->first();
        $schoolname = (is_object($schoolinfo) && isset($schoolinfo->schoolname)) ? preg_replace('/[^A-Za-z0-9_\-]/', '_', $schoolinfo->schoolname) : 'School';
        $zipname = storage_path('app/' . $schoolname . '_student_master_list.zip');
        $zip = new \ZipArchive();
        if ($zip->open($zipname, \ZipArchive::CREATE) === TRUE) {
            $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($tempDir));
            foreach ($rii as $file) {
                if ($file->isDir()) continue;
                $filePath = $file->getPathname();
                $localName = substr($filePath, strlen($tempDir) + 1);
                $zip->addFile($filePath, $localName);
            }
            $zip->close();
        }
        $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($tempDir, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($rii as $file) {
            if ($file->isDir()) {
                rmdir($file->getPathname());
            } else {
                unlink($file->getPathname());
            }
        }
        rmdir($tempDir);
        return response()->download($zipname)->deleteFileAfterSend(true);
    }

    public function downloadTorMasterlistBatch(Request $request)
        {

            set_time_limit(0);

            $sy = \DB::table('sy')->orderByDesc('sydesc')->first();
            $syid = $sy->id;
            $sections = \DB::table('sections')->where('deleted', 0)->get();
            $tempDir = storage_path('app/zip/' . uniqid());
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0777, true);
            }

            foreach ($sections as $section) {
                $students = \DB::table('studinfo')
                    ->select(
                        'studinfo.id as studentid',
                        'sid',
                        'lrn',
                        'studinfo.firstname',
                        'studinfo.middlename',
                        'studinfo.lastname',
                        'studinfo.suffix',
                        'studinfo.gender',
                        'gradelevel.levelname',
                        'gradelevel.id as levelid',
                        'college_courses.courseabrv'
                    )
                    ->join('college_enrolledstud', 'studinfo.id', '=', 'college_enrolledstud.studid')
                    ->leftJoin('college_courses', 'college_enrolledstud.courseid', '=', 'college_courses.id')
                    ->join('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
                    ->where('studinfo.deleted', '0')
                    ->where('college_enrolledstud.deleted', '0')
                    ->where('college_enrolledstud.studstatus', '!=', '0')
                    ->where('college_enrolledstud.studstatus', '<=', '5')
                    ->where('college_enrolledstud.sectionID', $section->id)
                    ->where('college_enrolledstud.syid', $syid)
                    ->orderBy('studinfo.lastname', 'asc')
                    ->orderBy('studinfo.firstname', 'asc')
                    ->groupBy('studinfo.id')
                    ->get();

                if (count($students) == 0) {
                    \Log::warning("No college students found for section: " . $section->sectionname);
                    continue;
                }

                foreach ($students as $student) {
                    $studentinfo = \DB::table('studinfo')
                        ->select('studinfo.*', 'nationality.nationality', 'religion.religionname')
                        ->leftJoin('nationality', 'studinfo.nationality', '=', 'nationality.id')
                        ->leftJoin('religion', 'studinfo.religionid', '=', 'religion.id')
                        ->where('studinfo.id', $student->studentid)
                        ->first();

                    $schoolyears = \DB::table('sy')
                        ->select('id as syid', 'sydesc', 'isactive')
                        ->orderByDesc('sydesc')
                        ->get();

                    $records = \App\Models\College\TOR::getrecords($student->studentid, $schoolyears);

                    $details = \DB::table('college_tordetail')
                        ->select('college_tordetail.*', 'dob', 'gender', 'mothername', 'fathername', 'college_tordetail.acrno', 'parentaddress', 'guardianaddress', 'studinfo_more.glits')
                        ->where('college_tordetail.studid', $student->studentid)
                        ->join('studinfo', 'college_tordetail.studid', 'studinfo.id')
                        ->leftJoin('studinfo_more', 'studinfo.id', 'studinfo_more.studid')
                        ->where('college_tordetail.deleted', '0')
                        ->first();

                    if (!$details) {
                        $details = (object) [
                            'studid' => null,
                            'parentguardian' => null,
                            'address' => null,
                            'elemcourse' => null,
                            'elemdatecomp' => null,
                            'secondcourse' => null,
                            'seconddatecomp' => null,
                            'admissiondate' => null,
                            'degree' => null,
                            'basisofadmission' => null,
                            'major' => null,
                            'specialorder' => null,
                            'elemsy' => null,
                            'secondsy' => null,
                            'thirdsy' => null,
                            'remarks' => null,
                            'graduationdate' => null,
                            'admissiondatestr' => null,
                            'collegeof' => null,
                            'entrancedata' => null,
                            'intermediategrades' => null,
                            'secondarygrades' => null,
                            'dob' => null,
                            'gender' => null,
                            'mothername' => null,
                            'fathername' => null,
                            'pob' => null,
                            'acrno' => null,
                            'citizenship' => null,
                            'civilstatus' => null,
                            'parentaddress' => null,
                            'guardianaddress' => null,
                            'glits' => null
                        ];
                    }

                    $signatories = \DB::table('signatory')
                        ->where('createdby', auth()->user()->id)
                        ->where('deleted', '0')
                        ->where('form', 'tor')
                        ->get();

                    $getphoto = \DB::table('studdisplayphoto')
                        ->where('studid', $student->studentid)
                        ->where('deleted', '0')
                        ->first();

                    $records = collect($records)->sortBy(function($record) {
                        return $record->sydesc . ' ' . $record->semid;
                    })->values();

                    $schoolinfo = \DB::table('schoolinfo')
                        ->select(
                            'schoolinfo.schoolid',
                            'schoolinfo.schoolname',
                            'schoolinfo.authorized',
                            'schoolinfo.picurl',
                            'refcitymun.citymunDesc as division',
                            'schoolinfo.district',
                            'schoolinfo.address',
                            'refregion.regDesc as region'
                        )
                        ->leftJoin('refregion', 'schoolinfo.region', '=', 'refregion.regCode')
                        ->leftJoin('refcitymun', 'schoolinfo.division', '=', 'refcitymun.citymunCode')
                        ->first();

                    $registrar = $signatories->where('title', 'Registrar')->first()->name ?? '';
                    $assistantreg = $signatories->where('title', 'Assistant Registrar')->first()->name ?? '';

                    $or = $details->or ?? '';
                    $dateissued = $details->dateissued ?? '';

                    $pdf = \PDF::loadview(
                        'registrar/forms/tor/pdf/pdf_tor_gbbc_dompdf',
                        compact(
                            'getphoto',
                            'schoolinfo',
                            'studentinfo',
                            'records',
                            'details',
                            'signatories',
                            'registrar',
                            'assistantreg',
                            'or',
                            'dateissued'
                        )
                    )->setPaper('legal', 'portrait');

                    // --- SORTING BY YEAR AND COURSE ---
                    $yearFolder = preg_replace('/[^A-Za-z0-9_\-]/', '_', $student->levelname ?? 'Unknown_Year');
                    $courseFolder = preg_replace('/[^A-Za-z0-9_\-]/', '_', $student->courseabrv ?? 'Unknown_Course');
                    $folderPath = $tempDir . '/' . $yearFolder . '/' . $courseFolder;
                    if (!file_exists($folderPath)) {
                        mkdir($folderPath, 0777, true);
                    }
                    $studentNameSafe = preg_replace('/[^A-Za-z0-9_\-]/', '_', $student->lastname . '_' . $student->firstname . ($student->middlename ? '_' . $student->middlename : ''));
                    $filename = $folderPath . '/' . $studentNameSafe . '_TOR.pdf';
                    file_put_contents($filename, $pdf->output());
                    if (!file_exists($filename)) {
                        \Log::error("PDF not created for student: " . $studentNameSafe);
                    }
                }
            }

            $schoolinfo = \DB::table('schoolinfo')->first();
            $schoolname = (is_object($schoolinfo) && isset($schoolinfo->schoolname)) ? preg_replace('/[^A-Za-z0-9_\-]/', '_', $schoolinfo->schoolname) : 'School';
            $zipname = storage_path('app/zip/' . $schoolname . '_tor_master_list.zip');
            $files = [];
            $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($tempDir));
            foreach ($rii as $file) {
                if ($file->isDir()) continue;
                $files[] = $file->getPathname();
            }
            if (count($files) === 0) {
                throw new \Exception("No PDF files generated for ZIP. Check student data and PDF generation.");
            }
            $zip = new \ZipArchive();
            if ($zip->open($zipname, \ZipArchive::CREATE) === TRUE) {
                foreach ($files as $filePath) {
                    $localName = substr($filePath, strlen($tempDir) + 1);
                    $zip->addFile($filePath, $localName);
                }
                $zip->close();
            } else {
                throw new \Exception("Failed to create ZIP file: $zipname");
            }
            $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($tempDir, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST);
            foreach ($rii as $file) {
                if ($file->isDir()) {
                    rmdir($file->getPathname());
                } else {
                    unlink($file->getPathname());
                }
            }
            rmdir($tempDir);
            return response()->download($zipname)->deleteFileAfterSend(true);
        }

    public function downloadSf9MasterlistBatch(Request $request)
    {
        $sy = \DB::table('sy')->orderByDesc('sydesc')->first();
        $syid = $sy->id;
        $sections = \DB::table('sections')->where('deleted', 0)->get();

        // Create temporary directory
        $tempDir = storage_path('app/zip/sf9_batch_' . uniqid());
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        foreach ($sections as $section) {
            // Get enrolled students for this section (JHS/Elementary)
            $enrolledstud = \DB::table('studinfo')
                ->select('studinfo.id', 'studinfo.lastname', 'studinfo.firstname', 'studinfo.middlename', 'studinfo.suffix')
                ->join('enrolledstud', 'studinfo.id', '=', 'enrolledstud.studid')
                ->where('enrolledstud.deleted', 0)
                ->where('studinfo.deleted', 0)
                ->where('enrolledstud.sectionid', $section->id)
                ->where('enrolledstud.syid', $syid)
                ->where('enrolledstud.studstatus', '<=', 5) // Exclude withdrawn students
                ->get();

            // Get SHS students for this section
            $sh_enrolledstud = \DB::table('studinfo')
                ->select('studinfo.id', 'studinfo.lastname', 'studinfo.firstname', 'studinfo.middlename', 'studinfo.suffix')
                ->join('sh_enrolledstud', 'studinfo.id', '=', 'sh_enrolledstud.studid')
                ->where('sh_enrolledstud.deleted', 0)
                ->where('studinfo.deleted', 0)
                ->where('sh_enrolledstud.sectionid', $section->id)
                ->where('sh_enrolledstud.syid', $syid)
                ->where('sh_enrolledstud.studstatus', '<=', 5) // Exclude withdrawn students
                ->get();

            $students = collect($enrolledstud)->merge($sh_enrolledstud)->unique('id');

            if (count($students) == 0) continue;

            // Get grade level name for folder structure
            $gradeLevel = \DB::table('gradelevel')
                ->join('enrolledstud', 'gradelevel.id', '=', 'enrolledstud.levelid')
                ->where('enrolledstud.sectionid', $section->id)
                ->where('enrolledstud.syid', $syid)
                ->value('levelname');

            if (!$gradeLevel) {
                $gradeLevel = \DB::table('gradelevel')
                    ->join('sh_enrolledstud', 'gradelevel.id', '=', 'sh_enrolledstud.levelid')
                    ->where('sh_enrolledstud.sectionid', $section->id)
                    ->where('sh_enrolledstud.syid', $syid)
                    ->value('levelname') ?? 'Unknown_Grade';
            }

            // Create safe folder names
            $gradeFolderSafe = preg_replace('/[^A-Za-z0-9_\-]/', '_', $gradeLevel);
            $sectionFolderSafe = preg_replace('/[^A-Za-z0-9_\-]/', '_', $section->sectionname);

            $folderPath = $tempDir . '/' . $gradeFolderSafe . '/' . $sectionFolderSafe;
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            foreach ($students as $student) {
                try {
                    // Generate PDF for each student using the existing sf9pdf method
                    $pdf = $this->sf9pdf($student->id, new Request(['syid' => $syid]));

                    // Generate filename
                    $filename = $student->lastname . '_' . $student->firstname;
                    if ($student->middlename) {
                        $filename .= '_' . substr($student->middlename, 0, 1);
                    }
                    if ($student->suffix) {
                        $filename .= '_' . $student->suffix;
                    }
                    $filename = preg_replace('/[^A-Za-z0-9_\-]/', '_', $filename) . '_SF9.pdf';

                    // Save PDF to file
                    file_put_contents($folderPath . '/' . $filename, $pdf->output());
                } catch (\Exception $e) {
                    // Log error and continue with next student
                    \Log::error("Failed to generate SF9 for student {$student->id}: " . $e->getMessage());
                    continue;
                }
            }
        }

        // Create zip file
        $schoolinfo = \DB::table('schoolinfo')->first();
        $schoolname = $schoolinfo->schoolname ?? 'School';
        $zipname = storage_path('app/zip/' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $schoolname) . '_SF9_Reports.zip');

        $zip = new \ZipArchive();
        if ($zip->open($zipname, \ZipArchive::CREATE) === TRUE) {
            $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($tempDir));
            foreach ($rii as $file) {
                if ($file->isDir()) continue;
                $filePath = $file->getPathname();
                $localName = substr($filePath, strlen($tempDir) + 1);
                $zip->addFile($filePath, $localName);
            }
            $zip->close();
        }

        // Clean up temporary files
        $rii = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($tempDir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($rii as $file) {
            if ($file->isDir()) {
                rmdir($file->getPathname());
            } else {
                unlink($file->getPathname());
            }
        }
        rmdir($tempDir);

        return response()->download($zipname)->deleteFileAfterSend(true);
    }

}
