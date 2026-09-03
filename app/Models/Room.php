<?php

namespace App\Models;

use Database\Factories\RoomFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $room_type_id
 * @property string $room_number
 * @property string $name
 * @property int $bed_count
 * @property float|null $height_feet
 * @property float|null $length_feet
 * @property float|null $width_feet
 * @property float|null $area_sqft
 * @property string|null $featured_image
 * @property array|null $gallery_images
 * @property string|null $floor_plan_image
 * @property bool $is_highlighted
 * @property string|null $highlight_tag
 * @property bool $has_jacuzzi
 * @property bool $has_sauna
 * @property bool $has_steam_bath
 * @property bool $has_shower
 * @property bool $has_toilet
 * @property bool $has_ac
 * @property bool $has_candle_light
 * @property bool $has_two_massage_beds
 * @property array|null $extra_amenities
 * @property string $status
 * @property bool $is_active
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read RoomType $roomType
 * @property-read RoomHighlight[] $highlights
 * @property-read TherapistAttendance[] $therapistAttendances
 * @property-read TherapistServiceLog[] $serviceLogs
 */
#[Fillable([
    'room_type_id',
    'room_number',
    'name',
    'bed_count',
    'height_feet',
    'length_feet',
    'width_feet',
    'area_sqft',
    'featured_image',
    'gallery_images',
    'floor_plan_image',
    'is_highlighted',
    'highlight_tag',
    'has_jacuzzi',
    'has_sauna',
    'has_steam_bath',
    'has_shower',
    'has_toilet',
    'has_ac',
    'has_candle_light',
    'has_two_massage_beds',
    'extra_amenities',
    'status',
    'is_active',
    'description',
])]
class Room extends Model
{
    /** @use HasFactory<RoomFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'bed_count' => 'integer',
            'height_feet' => 'decimal:2',
            'length_feet' => 'decimal:2',
            'width_feet' => 'decimal:2',
            'area_sqft' => 'decimal:2',
            'gallery_images' => 'array',
            'is_highlighted' => 'boolean',
            'has_jacuzzi' => 'boolean',
            'has_sauna' => 'boolean',
            'has_steam_bath' => 'boolean',
            'has_shower' => 'boolean',
            'has_toilet' => 'boolean',
            'has_ac' => 'boolean',
            'has_candle_light' => 'boolean',
            'has_two_massage_beds' => 'boolean',
            'extra_amenities' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the master room type.
     */
    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    /**
     * Get highlights assigned to this room (access highlight name, icon, image, description directly).
     */
    public function highlights(): BelongsToMany
    {
        return $this->belongsToMany(RoomHighlight::class, 'room_room_highlight')->withTimestamps();
    }

    /**
     * Get all duty shift allocations assigned to this room.
     */
    public function therapistAttendances(): HasMany
    {
        return $this->hasMany(TherapistAttendance::class);
    }

    /**
     * Get all service sessions conducted in this room.
     */
    public function serviceLogs(): HasMany
    {
        return $this->hasMany(TherapistServiceLog::class);
    }

    /**
     * Automatically populate default highlights from its parent room type into this room.
     */
    public function syncDefaultHighlightsFromRoomType(): void
    {
        if ($this->roomType) {
            $typeHighlightIds = $this->roomType->highlights()->pluck('room_highlights.id')->toArray();
            $this->highlights()->syncWithoutDetaching($typeHighlightIds);
        }
    }

    /**
     * List of features badges for UI rendering.
     *
     * @return string[]
     */
    public function getFeatureBadgesAttribute(): array
    {
        $badges = [];
        if ($this->has_two_massage_beds || $this->bed_count >= 2) $badges[] = 'Couple / Dual Massage Beds';
        if ($this->has_jacuzzi) $badges[] = 'Jacuzzi';
        if ($this->has_sauna) $badges[] = 'Sauna';
        if ($this->has_steam_bath) $badges[] = 'Steam Bath';
        if ($this->has_shower) $badges[] = 'Rain Shower';
        if ($this->has_toilet) $badges[] = 'Private Toilet';
        if ($this->has_ac) $badges[] = 'Climate AC';
        if ($this->has_candle_light) $badges[] = 'Candle Light Ambience';

        return $badges;
    }

    /**
     * Scope query to highlighted/featured rooms.
     */
    public function scopeHighlighted(Builder $query): void
    {
        $query->where('is_highlighted', true);
    }

    /**
     * Scope query to only couple/dual massage capable rooms (with 2 massage beds).
     */
    public function scopeCoupleCapable(Builder $query): void
    {
        $query->where('has_two_massage_beds', true)->orWhere('bed_count', '>=', 2);
    }

    /**
     * Scope query to available rooms.
     */
    public function scopeAvailable(Builder $query): void
    {
        $query->where('is_active', true)->where('status', 'available');
    }
}
