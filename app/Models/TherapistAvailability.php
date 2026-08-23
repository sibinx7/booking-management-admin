<?php

namespace App\Models;

use Database\Factories\TherapistAvailabilityFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $therapist_id
 * @property int $day_of_week
 * @property string $start_time
 * @property string $end_time
 * @property bool $is_available
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property-read Therapist $therapist
 */
#[Fillable([
    'therapist_id',
    'day_of_week',
    'start_time',
    'end_time',
    'is_available',
])]
class TherapistAvailability extends Model
{
    /** @use HasFactory<TherapistAvailabilityFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'day_of_week' => 'integer',
            'is_available' => 'boolean',
        ];
    }

    /**
     * Get the therapist that owns this availability slot.
     */
    public function therapist(): BelongsTo
    {
        return $this->belongsTo(Therapist::class);
    }
}
