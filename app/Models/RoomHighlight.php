<?php

namespace App\Models;

use Database\Factories\RoomHighlightFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $icon
 * @property string|null $image
 * @property string $category
 * @property string|null $description
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read RoomType[] $roomTypes
 * @property-read Room[] $rooms
 */
#[Fillable([
    'name',
    'code',
    'icon',
    'image',
    'category',
    'description',
    'is_active',
])]
class RoomHighlight extends Model
{
    /** @use HasFactory<RoomHighlightFactory> */
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
        ];
    }

    /**
     * Get all room types that include this highlight.
     */
    public function roomTypes(): BelongsToMany
    {
        return $this->belongsToMany(RoomType::class, 'room_type_room_highlight')->withTimestamps();
    }

    /**
     * Get all specific rooms that feature this highlight.
     */
    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'room_room_highlight')->withTimestamps();
    }
}
