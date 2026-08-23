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
 * @property int|null $user_id
 * @property string $name
 * @property string|null $profile_pic
 * @property Carbon|null $dob
 * @property string $gender
 * @property string|null $bio
 * @property string|null $phone_number
 * @property string|null $email
 * @property array|null $education
 * @property bool $is_active
 * @property bool $is_online
 * @property float $commission_rate
 * @property string|null $payment_info
 * @property float $rating
 * @property int $review_count
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property-read User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection|Service[] $services
 * @property-read \Illuminate\Database\Eloquent\Collection|Skill[] $skills
 * @property-read \Illuminate\Database\Eloquent\Collection|Language[] $languages
 * @property-read \Illuminate\Database\Eloquent\Collection|Speciality[] $specialities
 * @property-read \Illuminate\Database\Eloquent\Collection|TherapistAvailability[] $availabilities
 */
#[Fillable([
    'user_id',
    'name',
    'profile_pic',
    'dob',
    'gender',
    'bio',
    'phone_number',
    'email',
    'education',
    'is_active',
    'is_online',
    'commission_rate',
    'payment_info',
    'rating',
    'review_count',
])]
class Therapist extends Model
{
    /** @use HasFactory<TherapistFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'dob' => 'date',
            'education' => 'array',
            'is_active' => 'boolean',
            'is_online' => 'boolean',
            'commission_rate' => 'float',
            'rating' => 'float',
            'review_count' => 'integer',
        ];
    }

    /**
     * Calculate and get the therapist's age dynamically.
     */
    public function getAgeAttribute(): ?int
    {
        if (!$this->dob) {
            return null;
        }

        return $this->dob->age;
    }

    /**
     * Scope a query to only include online therapists.
     */
    public function scopeOnline($query)
    {
        return $query->where('is_online', true);
    }

    /**
     * Scope a query to only include active therapists.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the user account associated with the therapist.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
}
