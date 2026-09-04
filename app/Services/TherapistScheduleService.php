<?php

namespace App\Services;

use App\Models\EmployeeAttendance;
use App\Models\Therapist;
use App\Models\TherapistServiceLog;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class TherapistScheduleService
{
    /**
     * Buffer turnaround time between massage sessions (in minutes).
     */
    public const TURNAROUND_BUFFER_MINUTES = 10;

    /**
     * Get real-time live availability status for a therapist at a specific moment in time.
     *
     * @return array{
     *     therapist_id: int,
     *     therapist_name: string,
     *     is_online: bool,
     *     is_present_today: bool,
     *     attendance_type: string,
     *     availability_status: string, // 'available' | 'in_service' | 'off_duty'
     *     status_badge: string,
     *     active_service: array|null,
     *     next_available_at: string|null,
     *     remaining_minutes_in_service: int|null
     * }
     */
    public function getLiveStatus(Therapist $therapist, ?Carbon $currentTime = null): array
    {
        $now = $currentTime ?? Carbon::now();
        $todayDate = $now->format('Y-m-d');
        $timeStr = $now->format('H:i:s');

        // 1. Check Attendance for Today
        $attendance = EmployeeAttendance::where(function ($q) use ($therapist) {
            $q->where('therapist_id', $therapist->id)
                ->orWhere('employee_id', $therapist->employee_id);
        })
            ->whereDate('date', $todayDate)
            ->first();

        $isPresentToday = false;
        $attendanceType = 'not_checked_in';

        if ($attendance) {
            $attendanceType = $attendance->status; // present, half_day, absent, on_leave, weekly_off
            $isPresentToday = in_array($attendance->status, ['present', 'half_day']);
        }

        // 2. If not present or therapist profile is offline
        if (! $isPresentToday || ! $therapist->is_online) {
            $statusLabel = ! $isPresentToday ? ($attendance ? ucfirst(str_replace('_', ' ', $attendance->status)) : 'Off Duty / Absent') : 'Offline';

            return [
                'therapist_id' => $therapist->id,
                'therapist_name' => $therapist->name,
                'is_online' => (bool) $therapist->is_online,
                'is_present_today' => $isPresentToday,
                'attendance_type' => $attendanceType,
                'availability_status' => 'off_duty',
                'status_badge' => $statusLabel,
                'active_service' => null,
                'next_available_at' => null,
                'remaining_minutes_in_service' => null,
            ];
        }

        // 3. Check for Active Service Session In Progress right now
        $activeServiceLog = TherapistServiceLog::with(['service', 'serviceDuration'])
            ->where('therapist_id', $therapist->id)
            ->whereDate('service_date', $todayDate)
            ->where(function ($query) use ($timeStr) {
                // Status explicitly in_progress, or time currently within start_time and end_time
                $query->where('status', 'in_progress')
                    ->orWhere(function ($q) use ($timeStr) {
                        $q->where('status', 'completed')
                            ->where('start_time', '<=', $timeStr)
                            ->where('end_time', '>=', $timeStr);
                    });
            })
            ->latest('start_time')
            ->first();

        if ($activeServiceLog) {
            $endTime = $activeServiceLog->end_time ? Carbon::parse($todayDate . ' ' . $activeServiceLog->end_time) : $now->copy()->addMinutes(30);
            $freeAt = $endTime->copy()->addMinutes(self::TURNAROUND_BUFFER_MINUTES);
            $remainingMinutes = (int) max(0, $now->diffInMinutes($freeAt, false));

            return [
                'therapist_id' => $therapist->id,
                'therapist_name' => $therapist->name,
                'is_online' => true,
                'is_present_today' => true,
                'attendance_type' => $attendanceType,
                'availability_status' => 'in_service',
                'status_badge' => 'In Service (Free at ' . $freeAt->format('h:i A') . ')',
                'active_service' => [
                    'id' => $activeServiceLog->id,
                    'service_name' => $activeServiceLog->service?->name ?? 'Custom Therapy',
                    'start_time' => $activeServiceLog->start_time,
                    'end_time' => $activeServiceLog->end_time,
                    'client_name' => $activeServiceLog->client_name,
                ],
                'next_available_at' => $freeAt->format('H:i:s'),
                'remaining_minutes_in_service' => $remainingMinutes,
            ];
        }

        // 4. Available Now
        return [
            'therapist_id' => $therapist->id,
            'therapist_name' => $therapist->name,
            'is_online' => true,
            'is_present_today' => true,
            'attendance_type' => $attendanceType,
            'availability_status' => 'available',
            'status_badge' => 'Available Now',
            'active_service' => null,
            'next_available_at' => $now->format('H:i:s'),
            'remaining_minutes_in_service' => 0,
        ];
    }

    /**
     * Get the real-time calendar and attendance summary for all therapists for a specific day.
     *
     * @return array{
     *     date: string,
     *     total_therapists: int,
     *     present_count: int,
     *     available_now_count: int,
     *     in_service_count: int,
     *     off_duty_count: int,
     *     therapists: array
     * }
     */
    public function getDailyCalendarOverview(string $date, ?Carbon $currentTime = null): array
    {
        $therapists = Therapist::with(['employee', 'services'])->whereHas('employee', function ($q) {
            $q->where('is_active', true);
        })->get();

        $referenceTime = $currentTime ?? Carbon::parse($date . ' ' . Carbon::now()->format('H:i:s'));
        $list = [];
        $presentCount = 0;
        $availableNowCount = 0;
        $inServiceCount = 0;
        $offDutyCount = 0;

        foreach ($therapists as $therapist) {
            $status = $this->getLiveStatus($therapist, $referenceTime);
            $list[] = $status;

            if ($status['is_present_today']) {
                $presentCount++;
            }
            if ($status['availability_status'] === 'available') {
                $availableNowCount++;
            } elseif ($status['availability_status'] === 'in_service') {
                $inServiceCount++;
            } else {
                $offDutyCount++;
            }
        }

        return [
            'date' => $date,
            'total_therapists' => $therapists->count(),
            'present_count' => $presentCount,
            'available_now_count' => $availableNowCount,
            'in_service_count' => $inServiceCount,
            'off_duty_count' => $offDutyCount,
            'therapists' => $list,
        ];
    }
}
