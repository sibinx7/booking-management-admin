<?php

namespace App\Models;

use Database\Factories\DurationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $minutes
 * @property string $display_text
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['minutes', 'display_text'])]
class Duration extends Model
{
    /** @use HasFactory<DurationFactory> */
    use HasFactory;

    /**
     * Get the services that have this duration.
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'service_durations')
            ->withPivot(['price', 'label', 'title', 'popular', 'description'])
            ->withTimestamps();
    }
}
