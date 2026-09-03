<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\EmployeeAttendance;
use App\Models\TherapistServiceLog;
use Carbon\Carbon;

class SalaryCalculatorService
{
    /**
     * Compute salary breakdown for a given employee for a month and year.
     *
     * @return array{
     *     total_working_days: int,
     *     present_days: int,
     *     absent_days: int,
     *     leave_days: int,
     *     base_salary_amount: float,
     *     attendance_adjusted_base: float,
     *     services_completed_count: int,
     *     service_commission_amount: float,
     *     bonus_amount: float,
     *     deduction_amount: float,
     *     net_salary: float
     * }
     */
    public function compute(
        Employee $employee,
        int $year,
        int $month,
        float $bonusAmount = 0.00,
        float $deductionAmount = 0.00,
        ?int $customTotalWorkingDays = null
    ): array {
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        $totalWorkingDays = $customTotalWorkingDays ?? $endDate->daysInMonth;

        // 1. Fetch attendance records
        $attendances = EmployeeAttendance::where('employee_id', $employee->id)
            ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get();

        $presentDays = 0;
        $absentDays = 0;
        $leaveDays = 0;

        foreach ($attendances as $att) {
            if ($att->status === 'present') {
                $presentDays += 1;
            } elseif ($att->status === 'half_day') {
                $presentDays += 0.5;
            } elseif ($att->status === 'on_leave') {
                $leaveDays += 1;
            } elseif ($att->status === 'absent') {
                $absentDays += 1;
            }
        }

        // If no attendance records logged yet, assume full attendance by default
        if ($attendances->isEmpty()) {
            $presentDays = $totalWorkingDays;
        }

        // 2. Base salary & attendance adjusted base
        $baseSalary = (float) $employee->base_salary;
        $attendanceRatio = $totalWorkingDays > 0 ? min(1.0, $presentDays / $totalWorkingDays) : 1.0;
        $attendanceAdjustedBase = round($baseSalary * $attendanceRatio, 2);

        // 3. Therapist Service Commissions (including single primary & dual/couple secondary sessions)
        $servicesCompletedCount = 0;
        $serviceCommissionAmount = 0.00;

        if ($employee->role === 'therapist' && $employee->therapist) {
            $therapistId = $employee->therapist->id;

            // Primary sessions
            $primaryLogs = TherapistServiceLog::where('therapist_id', $therapistId)
                ->where('status', 'completed')
                ->whereBetween('service_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->get();

            // Secondary sessions (Dual / Couple Massages)
            $secondaryLogs = TherapistServiceLog::where('secondary_therapist_id', $therapistId)
                ->where('status', 'completed')
                ->whereBetween('service_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->get();

            $servicesCompletedCount = $primaryLogs->count() + $secondaryLogs->count();
            $serviceCommissionAmount = (float) ($primaryLogs->sum('commission_amount') + $secondaryLogs->sum('secondary_commission_amount'));
        }

        // 4. Calculate Net Salary
        $netSalary = round($attendanceAdjustedBase + $serviceCommissionAmount + $bonusAmount - $deductionAmount, 2);

        return [
            'total_working_days' => $totalWorkingDays,
            'present_days' => (int) round($presentDays),
            'absent_days' => $absentDays,
            'leave_days' => $leaveDays,
            'base_salary_amount' => $baseSalary,
            'attendance_adjusted_base' => $attendanceAdjustedBase,
            'services_completed_count' => $servicesCompletedCount,
            'service_commission_amount' => $serviceCommissionAmount,
            'bonus_amount' => $bonusAmount,
            'deduction_amount' => $deductionAmount,
            'net_salary' => max(0.00, $netSalary),
        ];
    }
}
