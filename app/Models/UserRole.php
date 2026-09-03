<?php

namespace App\Models;

use Database\Factories\UserRoleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $description
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property-read \Illuminate\Database\Eloquent\Collection|User[] $users
 */
#[Fillable([
    'name',
    'code',
    'description',
    'is_active',
])]
class UserRole extends Model
{
    /** @use HasFactory<UserRoleFactory> */
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
     * Scope a query to only include active user roles.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get all users associated with this role.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
