<?php

namespace App\Models;

use Database\Factories\ServiceDurationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $service_id
 * @property int $duration_id
 * @property float $price
 * @property string $label
 * @property string $title
 * @property bool $popular
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Service|null $service
 * @property-read Duration|null $duration
 */
#[Fillable([
    'service_id',
    'duration_id',
    'price',
    'label',
    'title',
    'popular',
    'description',
])]
class ServiceDuration extends Model
{
    /** @use HasFactory<ServiceDurationFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'float',
            'popular' => 'boolean',
        ];
    }

    /**
     * Get the service that owns the duration pricing tier.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the duration model for this pricing tier.
     */
    public function duration(): BelongsTo
    {
        return $this->belongsTo(Duration::class);
    }
}
