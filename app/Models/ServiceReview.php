<?php

namespace App\Models;

use Database\Factories\ServiceReviewFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $service_id
 * @property string $author_name
 * @property int $rating
 * @property string $date
 * @property string $comment
 * @property string|null $treatment_duration
 * @property bool $verified_guest
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
            'verified_guest' => 'boolean',
        ];
    }

    /**
     * Get the service that owns the review.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
