<?php

namespace App\Models;

use Database\Factories\TherapistFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $employee_id
 * @property string|null $display_name
 * @property string|null $profile_pic
 * @property string|null $bio
 * @property array|null $education
 * @property bool $is_online
 * @property float $commission_rate
 * @property float $rating
 * @property int $review_count
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property-read Employee $employee
 * @property-read User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection|Service[] $services
 * @property-read \Illuminate\Database\Eloquent\Collection|Skill[] $skills
 * @property-read \Illuminate\Database\Eloquent\Collection|Language[] $languages
 * @property-read \Illuminate\Database\Eloquent\Collection|Speciality[] $specialities
 * @property-read \Illuminate\Database\Eloquent\Collection|TherapistAvailability[] $availabilities
 * @property-read \Illuminate\Database\Eloquent\Collection|TherapistServiceLog[] $serviceLogs
 * @property-read \Illuminate\Database\Eloquent\Collection|ClientPayment[] $clientPayments
 */
#[Fillable([
    'employee_id',
    'display_name',
    'profile_pic',
    'bio',
    'education',
    'is_online',
    'commission_rate',
    'rating',
    'review_count',
])]
class Therapist extends Model
{
    /** @use HasFactory<TherapistFactory> */
    use HasFactory;

    /**
     * The accessors to append to the model's array and JSON form (for Spa Service pages & APIs).
     *
     * @var array<int, string>
     */
    protected $appends = [
        'name',
        'email',
        'phone_number',
        'gender',
        'dob',
        'profile_pic',
        'is_active',
        'age',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'education' => 'array',
            'is_online' => 'boolean',
            'commission_rate' => 'float',
            'rating' => 'float',
            'review_count' => 'integer',
        ];
    }

    /**
     * Get the therapist's name (display_name or from parent employee/user).
     */
    public function getNameAttribute(): ?string
    {
        return $this->display_name ?: $this->employee?->name;
    }

    /**
     * Get the therapist's email (from parent employee/user).
     */
    public function getEmailAttribute(): ?string
    {
        return $this->employee?->email;
    }

    /**
     * Get the therapist's phone number (from parent employee).
     */
    public function getPhoneNumberAttribute(): ?string
    {
        return $this->employee?->phone_number;
    }

    /**
     * Get the therapist's gender (from parent employee).
     */
    public function getGenderAttribute(): ?string
    {
        return $this->employee?->gender;
    }

    /**
     * Get the therapist's date of birth (from parent employee).
     */
    public function getDobAttribute(): ?Carbon
    {
        return $this->employee?->dob;
    }

    /**
     * Get the therapist's profile pic (custom showcase photo or from employee).
     */
    public function getProfilePicAttribute(): ?string
    {
        return $this->attributes['profile_pic'] ?? $this->employee?->profile_pic;
    }

    /**
     * Get the therapist's active status (from parent employee).
     */
    public function getIsActiveAttribute(): bool
    {
        return (bool) $this->employee?->is_active;
    }

    /**
     * Calculate and get the therapist's age dynamically.
     */
    public function getAgeAttribute(): ?int
    {
        return $this->employee?->age;
    }

    /**
     * Scope a query to only include online therapists.
     */
    public function scopeOnline($query)
    {
        return $query->where('is_online', true);
    }

    /**
     * Scope a query to only include active therapists (via employee relation).
     */
    public function scopeActive($query)
    {
        return $query->whereHas('employee', function ($q) {
            $q->where('is_active', true);
        });
    }

    /**
     * Get the parent employee record.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the user account associated with the therapist through employee.
     */
    public function getUserAttribute(): ?User
    {
        return $this->employee?->user;
    }

    /**
     * Get standard services this therapist is linked to.
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'therapist_service')->withTimestamps();
    }

    /**
     * Get special skills/techniques this therapist can perform.
     */
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'therapist_skill')->withTimestamps();
    }

    /**
     * Get languages spoken by the therapist.
     */
    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'therapist_language')->withTimestamps();
    }

    /**
     * Get specialties associated with the therapist.
     */
    public function specialities(): BelongsToMany
    {
        return $this->belongsToMany(Speciality::class, 'therapist_speciality')
            ->withPivot('extra_charge')
            ->withTimestamps();
    }

    /**
     * Get availability schedule rules for the therapist.
     */
    public function availabilities(): HasMany
    {
        return $this->hasMany(TherapistAvailability::class);
    }

    /**
     * Get daily service treatment logs where this therapist is the primary therapist.
     */
    public function serviceLogs(): HasMany
    {
        return $this->hasMany(TherapistServiceLog::class, 'therapist_id');
    }

    /**
     * Get daily service treatment logs where this therapist performed as the 2nd therapist in a Dual / Couple massage.
     */
    public function secondaryServiceLogs(): HasMany
    {
        return $this->hasMany(TherapistServiceLog::class, 'secondary_therapist_id');
    }

    /**
     * Query all service logs (both single primary and dual secondary sessions).
     */
    public function allServiceLogs()
    {
        return TherapistServiceLog::where(function ($q) {
            $q->where('therapist_id', $this->id)
                ->orWhere('secondary_therapist_id', $this->id);
        });
    }

    /**
     * Get client payment records linked to this therapist.
     */
    public function clientPayments(): HasMany
    {
        return $this->hasMany(ClientPayment::class);
    }

    /**
     * Get all master employee daily attendance records for this therapist.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(EmployeeAttendance::class);
    }

    /**
     * Get all dedicated therapist duty shift allocation attendance records.
     */
    public function therapistAttendances(): HasMany
    {
        return $this->hasMany(TherapistAttendance::class);
    }

    /**
     * Check if therapist is marked present (full day or half day) today.
     */
    public function isPresentToday(?string $date = null): bool
    {
        $dateStr = $date ?? now()->toDateString();
        return $this->attendances()
            ->where('date', $dateStr)
            ->whereIn('status', ['present', 'half_day'])
            ->exists();
    }

    /**
     * Get live availability status payload using TherapistScheduleService.
     */
    public function getLiveStatus(?Carbon $currentTime = null): array
    {
        return app(\App\Services\TherapistScheduleService::class)->getLiveStatus($this, $currentTime);
    }
}
