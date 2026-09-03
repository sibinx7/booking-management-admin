<?php

namespace App\Models;

use Database\Factories\TherapistAttendanceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $therapist_id
 * @property int $employee_id
 * @property int|null $employee_attendance_id
 * @property int|null $room_id
 * @property Carbon $date
 * @property string $shift_type
 * @property string $duty_start_time
 * @property string $duty_end_time
 * @property string $status
 * @property int $max_sessions_allowed
 * @property string|null $remarks
 * @property int|null $allocated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Therapist $therapist
 * @property-read Employee $employee
 * @property-read EmployeeAttendance|null $employeeAttendance
 * @property-read Room|null $room
 * @property-read TherapistServiceLog[] $serviceLogs
 * @property-read User|null $allocator
 */
#[Fillable([
    'therapist_id',
    'employee_id',
    'employee_attendance_id',
    'room_id',
    'date',
    'shift_type',
    'duty_start_time',
    'duty_end_time',
    'status',
    'max_sessions_allowed',
    'remarks',
    'allocated_by',
])]
class TherapistAttendance extends Model
{
    /** @use HasFactory<TherapistAttendanceFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'max_sessions_allowed' => 'integer',
        ];
    }

    public function therapist(): BelongsTo
    {
        return $this->belongsTo(Therapist::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function employeeAttendance(): BelongsTo
    {
        return $this->belongsTo(EmployeeAttendance::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function serviceLogs(): HasMany
    {
        return $this->hasMany(TherapistServiceLog::class, 'therapist_attendance_id');
    }

    public function allocator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'allocated_by');
    }

    /**
     * Get allocated room number & name.
     */
    public function getAllocatedSuiteAttribute(): string
    {
        return $this->room ? "{$this->room->room_number} - {$this->room->name}" : 'Unassigned Suite';
    }

    /**
     * Completed service sessions during this therapist shift.
     */
    public function getCompletedSessionsCountAttribute(): int
    {
        return $this->serviceLogs()->where('status', 'completed')->count();
    }

    /**
     * Total commission earned during this therapist shift.
     */
    public function getTotalShiftCommissionAttribute(): float
    {
        return (float) $this->serviceLogs()->where('status', 'completed')->sum('commission_amount');
    }

    public function scopeOnDuty(Builder $query): void
    {
        $query->where('status', 'on_duty');
    }

    public function scopeForDate(Builder $query, string $date): void
    {
        $query->where('date', $date);
    }
}
