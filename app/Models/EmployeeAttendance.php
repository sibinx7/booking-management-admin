<?php

namespace App\Models;

use Database\Factories\EmployeeAttendanceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $employee_id
 * @property int|null $therapist_id
 * @property Carbon $date
 * @property string $status
 * @property string $shift_type
 * @property string|null $check_in_time
 * @property string|null $check_out_time
 * @property float $work_hours
 * @property string|null $notes
 * @property int|null $recorded_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Employee $employee
 * @property-read Therapist|null $therapist
 * @property-read User|null $recorder
 * @property-read TherapistServiceLog[] $serviceLogs
 */
#[Fillable([
    'employee_id',
    'therapist_id',
    'date',
    'status',
    'shift_type',
    'check_in_time',
    'check_out_time',
    'work_hours',
    'notes',
    'recorded_by',
])]
class EmployeeAttendance extends Model
{
    /** @use HasFactory<EmployeeAttendanceFactory> */
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
            'work_hours' => 'decimal:2',
        ];
    }

    /**
     * Get the base employee record.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the direct therapist profile if this attendance is for a therapist.
     */
    public function therapist(): BelongsTo
    {
        return $this->belongsTo(Therapist::class);
    }

    /**
     * Get therapy service sessions performed during this attendance shift.
     */
    public function serviceLogs(): HasMany
    {
        return $this->hasMany(TherapistServiceLog::class, 'employee_attendance_id');
    }

    /**
     * Total number of therapy sessions completed during this shift.
     */
    public function getCompletedServicesCountAttribute(): int
    {
        return $this->serviceLogs()->where('status', 'completed')->count();
    }

    /**
     * Total commission earned by therapist during this attendance shift.
     */
    public function getTotalShiftCommissionAttribute(): float
    {
        return (float) $this->serviceLogs()->where('status', 'completed')->sum('commission_amount');
    }

    /**
     * Human-readable shift name (e.g. "Morning Shift (08:00 - 14:00)").
     */
    public function getShiftLabelAttribute(): string
    {
        return match ($this->shift_type) {
            'morning_shift' => 'Morning Leg / Shift',
            'evening_shift' => 'Evening Leg / Shift',
            'half_day_morning' => 'Half Day (Morning Leg)',
            'half_day_evening' => 'Half Day (Evening Leg)',
            'custom' => 'Custom Shift',
            default => 'Full Day (General Shift)',
        };
    }

    /**
     * Get the recorder user.
     */
    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * Scope for present attendance.
     */
    public function scopePresent(Builder $query): void
    {
        $query->whereIn('status', ['present', 'half_day']);
    }

    /**
     * Scope for a specific shift type.
     */
    public function scopeShift(Builder $query, string $shiftType): void
    {
        $query->where('shift_type', $shiftType);
    }

    /**
     * Scope for a specific month and year.
     */
    public function scopeForMonth(Builder $query, int $year, int $month): void
    {
        $query->whereYear('date', $year)->whereMonth('date', $month);
    }
}
