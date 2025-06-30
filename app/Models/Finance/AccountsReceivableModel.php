<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Carbon;

class AccountsReceivableModel extends Model
{
    public static function allstudents($selectedschoolyear, $selecteddaterange, $selecteddepartment, 
        $selectedgradelevel, $selectedsemester, $selectedsection, $selectedgrantee, $selectedmode)
    {
        // Parse date range if provided
        $dateFilter = null;
        if ($selecteddaterange) {
            $dates = explode(' - ', $selecteddaterange);
            $dateFilter = [
                'from' => Carbon::createFromFormat('m/d/Y', $dates[0])->startOfDay(),
                'to' => Carbon::createFromFormat('m/d/Y', $dates[1])->endOfDay()
            ];
        }

        // Get basic student info from all levels in one query using UNION
        $studentQuery = DB::table('studinfo')
            ->select(
                'studinfo.id',
                'studinfo.sid',
                'studinfo.firstname',
                'studinfo.middlename',
                'studinfo.lastname',
                'studinfo.suffix',
                'studinfo.gender',
                'studinfo.mol',
                'studinfo.grantee as granteeid',
                DB::raw('NULL as semid'),
                'sections.id as sectionid',
                'sections.sectionname',
                'gradelevel.id as levelid',
                'gradelevel.levelname',
                'academicprogram.id as acadprogid',
                'academicprogram.acadprogcode',
                'modeoflearning.description as mol',
                'grantee.description as grantee',
                DB::raw("'basic_education' as education_level"),
                DB::raw('NULL as courseid'),
                DB::raw('NULL as units')
            )
            ->join('enrolledstud', 'studinfo.id', '=', 'enrolledstud.studid')
            ->join('sections', 'enrolledstud.sectionid', '=', 'sections.id')
            ->join('gradelevel', 'enrolledstud.levelid', '=', 'gradelevel.id')
            ->join('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
            ->leftJoin('modeoflearning', 'studinfo.mol', '=', 'modeoflearning.id')
            ->leftJoin('grantee', 'studinfo.grantee', '=', 'grantee.id')
            ->where('sections.deleted', '0')
            ->where('studinfo.deleted', '0')
            ->where('enrolledstud.deleted', '0')
            ->where('enrolledstud.studstatus', '!=', '0')
            ->where('enrolledstud.syid', $selectedschoolyear)
            // ->where('studinfo.id', 3581)
            
            ->unionAll(
                DB::table('studinfo')
                    ->select(
                        'studinfo.id',
                        'studinfo.sid',
                        'studinfo.firstname',
                        'studinfo.middlename',
                        'studinfo.lastname',
                        'studinfo.suffix',
                        'studinfo.gender',
                        'studinfo.mol',
                        'studinfo.grantee as granteeid',
                        'sh_enrolledstud.semid',
                        'sections.id as sectionid',
                        'sections.sectionname',
                        'gradelevel.id as levelid',
                        'gradelevel.levelname',
                        'academicprogram.id as acadprogid',
                        'academicprogram.acadprogcode',
                        'modeoflearning.description as mol',
                        'grantee.description as grantee',
                        DB::raw("'senior_high' as education_level"),
                        DB::raw('NULL as courseid'),
                        DB::raw('NULL as units')
                    )
                    ->join('sh_enrolledstud', 'studinfo.id', '=', 'sh_enrolledstud.studid')
                    ->join('sections', 'sh_enrolledstud.sectionid', '=', 'sections.id')
                    ->join('gradelevel', 'sh_enrolledstud.levelid', '=', 'gradelevel.id')
                    ->join('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
                    ->leftJoin('modeoflearning', 'studinfo.mol', '=', 'modeoflearning.id')
                    ->leftJoin('grantee', 'studinfo.grantee', '=', 'grantee.id')
                    ->where('sections.deleted', '0')
                    ->where('studinfo.deleted', '0')
                    ->where('sh_enrolledstud.deleted', '0')
                    ->where('sh_enrolledstud.studstatus', '!=', '0')
                    ->where('sh_enrolledstud.syid', $selectedschoolyear)
            )
            
            ->unionAll(
                DB::table('studinfo')
                    ->select(
                        'studinfo.id',
                        'studinfo.sid',
                        'studinfo.firstname',
                        'studinfo.middlename',
                        'studinfo.lastname',
                        'studinfo.suffix',
                        'studinfo.gender',
                        'studinfo.mol',
                        'studinfo.grantee as granteeid',
                        'college_enrolledstud.semid',
                        'college_sections.id as sectionid',
                        'college_sections.sectionDesc as sectionname',
                        'gradelevel.id as levelid',
                        'gradelevel.levelname',
                        'academicprogram.id as acadprogid',
                        'academicprogram.acadprogcode',
                        'modeoflearning.description as mol',
                        'grantee.description as grantee',
                        DB::raw("'college' as education_level"),
                        'college_enrolledstud.courseid',
                        DB::raw('NULL as units')
                    )
                    ->join('college_enrolledstud', 'studinfo.id', '=', 'college_enrolledstud.studid')
                    ->join('college_sections', 'college_enrolledstud.sectionID', '=', 'college_sections.id')
                    ->join('gradelevel', 'college_enrolledstud.yearLevel', '=', 'gradelevel.id')
                    ->join('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
                    ->leftJoin('modeoflearning', 'studinfo.mol', '=', 'modeoflearning.id')
                    ->leftJoin('grantee', 'studinfo.grantee', '=', 'grantee.id')
                    ->where('college_sections.deleted', '0')
                    ->where('studinfo.deleted', '0')
                    ->where('college_enrolledstud.deleted', '0')
                    ->where('college_enrolledstud.studstatus', '!=', '0')
                    ->where('college_enrolledstud.syid', $selectedschoolyear)
            );

        // Apply semester filter if needed
        if ($selectedsemester) {
            $studentQuery->where(function($query) use ($selectedsemester) {
                $query->where('semid', $selectedsemester)
                      ->orWhereNull('semid');
            });
        }
    
        $students = $studentQuery->orderBy('acadprogid')
            ->orderBy('levelid')
            ->orderBy('lastname')
            ->get()
            ->unique('id');

        // Apply additional filters
        if ($selecteddepartment) {
            $students = $students->where('acadprogid', $selecteddepartment);
        }
        if ($selectedgradelevel) {
            $students = $students->where('levelid', $selectedgradelevel);
        }
        if ($selectedsection) {
            $students = $students->where('sectionid', $selectedsection);
        }
        if ($selectedgrantee) {
            $students = $students->where('granteeid', $selectedgrantee);
        }
        if ($selectedmode) {
            $students = $students->where('mol', $selectedmode);
        }

        // Preload ledger data for all students at once
        $studentIds = $students->pluck('id')->toArray();
        // $studentIds = [3658];
        // return $studentIds;
        $ledgerData = DB::table('studledger')
            ->select(
                'studid',
                DB::raw('SUM(amount) as total_assessment'),
                DB::raw('SUM(payment) as total_payment'),
                'semid'
            )
            ->whereIn('studid', $studentIds)
            ->where('deleted', '0')
            ->where('syid', $selectedschoolyear)
            ->groupBy('studid', 'semid')
            ->get()
            ->groupBy('studid');

        $getCashItems = DB::table('chrngcashtrans')
            ->select(
                'chrngcashtrans.id',
                'chrngcashtrans.amount',
                'chrngcashtrans.studid',
                'chrngcashtrans.semid',
                'chrngcashtrans.syid',
                'chrngcashtrans.kind'
            )
            ->join('chrngtrans', 'chrngcashtrans.transno', '=', 'chrngtrans.transno')
            ->where('chrngtrans.cancelled', 0)
            ->whereIn('chrngcashtrans.studid', $studentIds)
            ->where('chrngcashtrans.deleted', '0')
            ->where('chrngcashtrans.syid', $selectedschoolyear)
            ->where('chrngcashtrans.kind', 'item')
            ->get();

            
        // Calculate college units in batch
        $collegeStudents = $students->where('education_level', 'college');
        if ($collegeStudents->isNotEmpty()) {
            $collegeStudentIds = $collegeStudents->pluck('id');
            $courseIds = $collegeStudents->pluck('courseid');
            $levelIds = $collegeStudents->pluck('levelid');
            
            // Get all schedules for college students
            $schedules = DB::table('college_studsched')
                ->select('studid', 'schedid')
                ->whereIn('studid', $collegeStudentIds)
                ->where('deleted', '0')
                ->get()
                ->groupBy('studid');
                
            // Get all subjects for these schedules
            $schedIds = $schedules->flatMap->pluck('schedid')->unique()->toArray();
            $subjects = DB::table('college_classsched')
                ->select('id', 'subjectID')
                ->whereIn('id', $schedIds)
                ->where('deleted', '0')
                ->get()
                ->keyBy('id');
                
            // Get all prospectus data needed
            $prospectusData = DB::table('college_prospectus')
                ->select('subjectID', 'courseID', 'yearID', DB::raw('SUM(lecunits + labunits) as total_units'))
                ->whereIn('courseID', $courseIds)
                ->whereIn('yearID', $levelIds)
                ->where('deleted', '0')
                ->groupBy('subjectID', 'courseID', 'yearID')
                ->get()
                ->groupBy(['courseID', 'yearID', 'subjectID']);

            // Calculate units for each college student
            foreach ($collegeStudents as $student) {
                $units = 0;
                if (isset($schedules[$student->id])) {
                    foreach ($schedules[$student->id] as $sched) {
                        if (isset($subjects[$sched->schedid])) {
                            $subjectId = $subjects[$sched->schedid]->subjectID;
                            $units += $prospectusData[$student->courseid][$student->levelid][$subjectId][0]->total_units ?? 0;
                        }
                    }
                }
                $student->units = $units;
            }
        }
        
        // $students = collect($students)->where('id', 719)->values();
       
        // Process each student
        foreach ($students as $student) {
            $cashItemAmount = 0;
            
            $cashItemAmount = collect($getCashItems)
                ->filter(function ($item) use ($student) {
                    return $item->studid == $student->id &&
                        ($student->semid == null || $item->semid == $student->semid);
                })->sum('amount');
             
            $assessment = 0;
            $payment = 0;
            
            if (isset($ledgerData[$student->id])) {
                foreach ($ledgerData[$student->id] as $ledger) {
                    // Apply semester filter if needed
                    if (!$selectedsemester || $ledger->semid == $selectedsemester) {
                        $assessment += $ledger->total_assessment;
                        $payment += $ledger->total_payment;
                    }
                }
            }
            
            // Apply date range filter if needed
            if ($dateFilter) {
                // Note: This would require a more complex query to filter by date at the database level
                // Currently we're calculating totals first then applying date filter as a second step
                // For true optimization, you'd need to modify the ledger query to include date filtering
                $assessment = self::getFilteredAmount($student->id, $selectedsemester, $selectedschoolyear, $dateFilter, 'amount');
                $payment = self::getFilteredAmount($student->id, $selectedsemester, $selectedschoolyear, $dateFilter, 'payment');
            }

            if($payment > $cashItemAmount){
                $payment -= $cashItemAmount;
            }

            $student->totalassessment = $assessment;
            $student->discount = 0; // Assuming this would come from another table
            $student->netassessed = $assessment - $student->discount;
            $student->totalpayment = $payment;
            $student->balance = $student->netassessed - $payment;
        }

        return $students;
    }
    
    protected static function getFilteredAmount($studentId, $semesterId, $schoolYearId, $dateFilter, $column)
    {
        $query = DB::table('studledger')
            ->where('studid', $studentId)
            ->where('deleted', '0')
            ->where('syid', $schoolYearId);
            
        if ($semesterId) {
            $query->where('semid', $semesterId);
        }
        
        if ($dateFilter) {
            $query->whereBetween('createddatetime', [$dateFilter['from'], $dateFilter['to']]);
        }
        
        return $query->sum($column);
    }
}