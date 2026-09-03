<?php

namespace App\Models;

use Database\Factories\ServiceReviewFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $service_id
 * @property string $author_name
 * @property int $rating
 * @property Carbon $date
 * @property string $comment
 * @property string|null $treatment_duration
 * @property bool $verified_guest
 * @property bool $is_published
 * @property Carbon|null $published_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Service|null $service
 */
#[Fillable([
    'service_id',
    'author_name',
    'rating',
    'date',
    'comment',
    'treatment_duration',
    'verified_guest',
    'is_published',
    'published_at',
])]
class ServiceReview extends Model
{
    /** @use HasFactory<ServiceReviewFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'date' => 'date',
            'verified_guest' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    /**
     * Scope a query to only include published reviews.
     */
    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true);
    }

    /**
     * Scope a query to only include draft reviews.
     */
    public function scopeDraft(Builder $query): void
    {
        $query->where('is_published', false);
    }

    /**
     * Get the service that owns the review.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
