<?php

namespace App\Http\Controllers\Financev2Controller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PaymentScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('fn_payment_schedules as ps')
            ->select(
                'ps.id',
                'ps.description',
                'ps.is_active',
                'sy.sydesc',
                'sem.semester'
            )
            ->join('sy', 'ps.school_year_id', '=', 'sy.id')
            ->join('semester as sem', 'ps.semester_id', '=', 'sem.id')
            ->where('ps.deleted', 0);

        if ($request->has('school_year') && $request->school_year) {
            $query->where('ps.school_year_id', $request->school_year);
        }

        if ($request->has('semester') && $request->semester) {
            $query->where('ps.semester_id', $request->semester);
        }

        $schedules = $query->get();

        // Load months for each schedule
        foreach ($schedules as $schedule) {
            $schedule->months = DB::table('fn_payment_schedule_months')
                ->where('schedule_id', $schedule->id)
                ->get();
        }

        return response()->json($schedules);
    }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);

        try {
            DB::beginTransaction();

            // If setting as active, deactivate other schedules for the same SY/semester
            if ($request->is_active) {
                DB::table('fn_payment_schedules')
                    ->where('school_year_id', $validated['school_year_id'])
                    ->where('semester_id', $validated['semester_id'])
                    ->update(['is_active' => 0]);
            }

            // Create the schedule
            $scheduleId = DB::table('fn_payment_schedules')->insertGetId([
                'description' => $validated['description'],
                'school_year_id' => $validated['school_year_id'],
                'semester_id' => $validated['semester_id'],
                'is_active' => $validated['is_active'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Add the months
            $this->saveScheduleMonths($scheduleId, $validated['months'], $validated['percentage_enabled']);

            DB::commit();

            $schedule = $this->getScheduleWithMonths($scheduleId);
            return response()->json($schedule, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create schedule: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $schedule = $this->getScheduleWithMonths($id);

        if (!$schedule) {
            return response()->json(['message' => 'Payment schedule not found'], 404);
        }

        return response()->json($schedule);
    }

    public function update(Request $request, $id)
    {
        $validated = $this->validateRequest($request);

        $schedule = DB::table('fn_payment_schedules')->where('id', $id)->first();

        if (!$schedule) {
            return response()->json(['message' => 'Payment schedule not found'], 404);
        }

        try {
            DB::beginTransaction();

            // If setting as active, deactivate other schedules for the same SY/semester
            if ($request->is_active) {
                DB::table('fn_payment_schedules')
                    ->where('school_year_id', $validated['school_year_id'])
                    ->where('semester_id', $validated['semester_id'])
                    ->where('id', '!=', $id)
                    ->update(['is_active' => 0]);
            }

            // Update the schedule
            DB::table('fn_payment_schedules')
                ->where('id', $id)
                ->update([
                    'description' => $validated['description'],
                    'school_year_id' => $validated['school_year_id'],
                    'semester_id' => $validated['semester_id'],
                    'is_active' => $validated['is_active'],
                    'updated_at' => now()
                ]);

            // Delete existing months and add new ones
            DB::table('fn_payment_schedule_months')->where('schedule_id', $id)->delete();
            $this->saveScheduleMonths($id, $validated['months'], $validated['percentage_enabled']);

            DB::commit();

            $schedule = $this->getScheduleWithMonths($id);
            return response()->json($schedule);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update schedule: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $affected = DB::table('fn_payment_schedules')
            ->where('id', $id)
            ->update([
                'deleted' => 1,
                'deleted_at' => now()
            ]);

        if ($affected === 0) {
            return response()->json(['message' => 'Payment schedule not found'], 404);
        }

        return response()->json(null, 204);
    }

    private function getScheduleWithMonths($id)
    {
        $schedule = DB::table('fn_payment_schedules')
            ->select(
                'fn_payment_schedules.*',
                'sy.sydesc',
                'sem.semester'
            )
            ->join('sy', 'fn_payment_schedules.school_year_id', '=', 'sy.id')
            ->join('semester as sem', 'fn_payment_schedules.semester_id', '=', 'sem.id')
            ->where('fn_payment_schedules.id', $id)
            ->first();

        if ($schedule) {
            $schedule->months = DB::table('fn_payment_schedule_months')
                ->where('schedule_id', $id)
                ->get();
        }

        return $schedule;
    }

    private function validateRequest(Request $request)
    {
        $request->merge([
            'months' => array_map(function($month) {
                // Ensure is_included is properly cast to boolean
                $month['is_included'] = filter_var($month['is_included'], FILTER_VALIDATE_BOOLEAN);
                return $month;
            }, $request->months)
        ]);
    
        return $request->validate([
            'description' => 'required|string|max:255',
            'school_year_id' => 'required|exists:sy,id',
            'semester_id' => 'required|exists:semester,id',
            'is_active' => 'boolean',
            'percentage_enabled' => 'boolean',
            'months' => 'required|array',
            'months.*.month_name' => 'required|string',
            'months.*.display_name' => 'nullable|string',
            'months.*.percentage' => 'nullable|numeric|between:0,100',
            'months.*.is_included' => 'boolean' // Now this will accept proper boolean values
        ]);
    }

    private function saveScheduleMonths($scheduleId, $months, $percentageEnabled)
    {
        $monthData = [];
        $year = DB::table('sy')
            ->where('id', request('school_year_id'))
            ->value('sydesc');
        $year = explode('-', $year)[0];

        foreach ($months as $month) {
            if ($month['is_included']) {
                $monthData[] = [
                    'schedule_id' => $scheduleId,
                    'month_name' => $month['month_name'] . ' ' . $year,
                    'display_name' => $month['display_name'],
                    'percentage' => $percentageEnabled ? $month['percentage'] : null,
                    'is_included' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        if (!empty($monthData)) {
            DB::table('fn_payment_schedule_months')->insert($monthData);
        }
    }
}