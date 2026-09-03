<?php

namespace App\Models;

use Database\Factories\ReceptionistFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $employee_id
 * @property string|null $counter_number
 * @property string $shift_preference
 * @property string|null $desk_phone
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Employee $employee
 */
#[Fillable([
    'employee_id',
    'counter_number',
    'shift_preference',
    'desk_phone',
    'is_active',
])]
class Receptionist extends Model
{
    /** @use HasFactory<ReceptionistFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
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
     * Get client payments collected by this receptionist.
     */
    public function clientPayments(): HasMany
    {
        return $this->hasMany(ClientPayment::class);
    }

    /**
     * Helper accessor for receptionist's full name.
     */
    public function getNameAttribute(): string
    {
        return $this->employee?->name ?? 'Receptionist #' . $this->id;
    }
}
