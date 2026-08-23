<?php

namespace App\Models;

use Database\Factories\ServiceSpecialOfferFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $service_id
 * @property int|null $duration_id
 * @property string $badge
 * @property string $title
 * @property string $discount
 * @property string $description
 * @property string|null $promo_code
 * @property bool $is_active
 * @property Carbon|null $start_at
 * @property Carbon|null $end_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Service|null $service
 * @property-read Duration|null $duration
 */
#[Fillable([
    'service_id',
    'duration_id',
    'badge',
    'title',
    'discount',
    'description',
    'promo_code',
    'is_active',
    'start_at',
    'end_at',
])]
class ServiceSpecialOffer extends Model
{
    /** @use HasFactory<ServiceSpecialOfferFactory> */
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
            'start_at' => 'datetime',
            'end_at' => 'datetime',
        ];
    }

    /**
     * Get the service that owns the special offer.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the duration that this offer targets, if any.
     */
    public function duration(): BelongsTo
    {
        return $this->belongsTo(Duration::class);
    }
}
