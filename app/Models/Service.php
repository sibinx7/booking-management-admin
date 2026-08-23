<?php

namespace App\Models;

use Database\Factories\ServiceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string $tagline
 * @property string $category
 * @property string $hero_image
 * @property array|null $images
 * @property bool $is_new
 * @property bool $is_unlimited
 * @property Carbon|null $start
 * @property Carbon|null $end
 * @property bool $is_discount_active
 * @property string $discount_type
 * @property float $discount_value
 * @property Carbon|null $discount_start_at
 * @property Carbon|null $discount_end_at
 * @property float $rating
 * @property int $review_count
 * @property string $overview
 * @property string $full_description
 * @property array $ritual_steps
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property-read \Illuminate\Database\Eloquent\Collection|Therapist[] $therapists
 */
#[Fillable([
    'slug',
    'title',
    'tagline',
    'category',
    'hero_image',
    'images',
    'is_new',
    'is_unlimited',
    'start',
    'end',
    'is_discount_active',
    'discount_type',
    'discount_value',
    'discount_start_at',
    'discount_end_at',
    'rating',
    'review_count',
    'overview',
    'full_description',
    'ritual_steps',
])]
class Service extends Model
{
    /** @use HasFactory<ServiceFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'images' => 'array',
            'is_new' => 'boolean',
            'is_unlimited' => 'boolean',
            'start' => 'datetime',
            'end' => 'datetime',
            'is_discount_active' => 'boolean',
            'discount_value' => 'float',
            'discount_start_at' => 'datetime',
            'discount_end_at' => 'datetime',
            'rating' => 'float',
            'review_count' => 'integer',
            'ritual_steps' => 'array',
        ];
    }

    /**
     * Get all timing durations pivot records.
     */
    public function serviceDurations(): HasMany
    {
        return $this->hasMany(ServiceDuration::class);
    }

    /**
     * Get the durations for the service.
     */
    public function durations(): BelongsToMany
    {
        return $this->belongsToMany(Duration::class, 'service_durations')
            ->withPivot(['price', 'label', 'title', 'popular', 'description'])
            ->withTimestamps();
    }

    /**
     * Get the special offers for the service.
     */
    public function specialOffers(): HasMany
    {
        return $this->hasMany(ServiceSpecialOffer::class);
    }

    /**
     * Get the highlights for the service.
     */
    public function highlights(): HasMany
    {
        return $this->hasMany(ServiceHighlight::class);
    }

    /**
     * Get the reviews for the service.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(ServiceReview::class);
    }

    /**
     * Get the therapists skilled in performing this service.
     */
    public function therapists(): BelongsToMany
    {
        return $this->belongsToMany(Therapist::class, 'therapist_service')->withTimestamps();
    }
}
