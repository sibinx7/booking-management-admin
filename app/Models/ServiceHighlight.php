<?php

namespace App\Models;

use Database\Factories\ServiceHighlightFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $service_id
 * @property string|null $icon
 * @property string $title
 * @property string $description
 * @property string|null $image
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Service|null $service
 */
#[Fillable([
    'service_id',
    'icon',
    'title',
    'description',
    'image',
])]
class ServiceHighlight extends Model
{
    /** @use HasFactory<ServiceHighlightFactory> */
    use HasFactory;

    /**
     * Get the service that owns the highlight.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
