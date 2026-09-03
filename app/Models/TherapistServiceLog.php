<?php

namespace App\Models;

use Database\Factories\TherapistServiceLogFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $therapist_attendance_id
 * @property int|null $employee_attendance_id
 * @property int|null $room_id
 * @property int $therapist_id
 * @property bool $is_dual_massage
 * @property int|null $secondary_therapist_id
 * @property int $service_id
 * @property int|null $service_duration_id
 * @property int|null $client_id
 * @property string $client_name
 * @property string|null $client_phone
 * @property Carbon $service_date
 * @property string|null $start_time
 * @property string|null $end_time
 * @property float $service_price
 * @property float $commission_rate
 * @property float $commission_amount
 * @property float $secondary_commission_amount
 * @property float $tip_amount
 * @property float $secondary_tip_amount
 * @property string $status
 * @property string|null $notes
 * @property int|null $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read TherapistAttendance|null $therapistAttendance
 * @property-read EmployeeAttendance|null $employeeAttendance
 * @property-read Room|null $room
 * @property-read Therapist $therapist
 * @property-read Therapist|null $secondaryTherapist
 * @property-read Service $service
 * @property-read ServiceDuration|null $serviceDuration
 * @property-read Client|null $client
 * @property-read ClientPayment|null $clientPayment
 */
#[Fillable([
    'therapist_attendance_id',
    'employee_attendance_id',
    'room_id',
    'therapist_id',
    'is_dual_massage',
    'secondary_therapist_id',
    'service_id',
    'service_duration_id',
    'client_id',
    'client_name',
    'client_phone',
    'service_date',
    'start_time',
    'end_time',
    'service_price',
    'commission_rate',
    'commission_amount',
    'secondary_commission_amount',
    'tip_amount',
    'secondary_tip_amount',
    'status',
    'notes',
    'created_by',
])]
class TherapistServiceLog extends Model
{
    /** @use HasFactory<TherapistServiceLogFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'service_date' => 'date',
            'is_dual_massage' => 'boolean',
            'service_price' => 'decimal:2',
            'commission_rate' => 'decimal:2',
            'commission_amount' => 'decimal:2',
            'secondary_commission_amount' => 'decimal:2',
            'tip_amount' => 'decimal:2',
            'secondary_tip_amount' => 'decimal:2',
        ];
    }

    public function therapistAttendance(): BelongsTo
    {
        return $this->belongsTo(TherapistAttendance::class);
    }

    public function employeeAttendance(): BelongsTo
    {
        return $this->belongsTo(EmployeeAttendance::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function therapist(): BelongsTo
    {
        return $this->belongsTo(Therapist::class);
    }

    public function secondaryTherapist(): BelongsTo
    {
        return $this->belongsTo(Therapist::class, 'secondary_therapist_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function serviceDuration(): BelongsTo
    {
        return $this->belongsTo(ServiceDuration::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function clientPayment(): HasOne
    {
        return $this->hasOne(ClientPayment::class);
    }

    /**
     * Dynamically retrieve room / suite name from the normalized room relationship.
     */
    public function getAllocatedSuiteAttribute(): string
    {
        return $this->room ? "{$this->room->room_number} - {$this->room->name}" : 'Unassigned Suite';
    }

    public function scopeCompleted(Builder $query): void
    {
        $query->where('status', 'completed');
    }

    public function scopeInProgress(Builder $query): void
    {
        $query->where('status', 'in_progress');
    }

    public function scopeDualMassage(Builder $query): void
    {
        $query->where('is_dual_massage', true);
    }

    public function scopeForMonth(Builder $query, int $year, int $month): void
    {
        $query->whereYear('service_date', $year)->whereMonth('service_date', $month);
    }
}
