<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_role_id
 * @property string $name
 * @property string|null $email
 * @property string|null $password
 * @property Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property-read UserRole $role
 * @property-read Admin|null $admin
 * @property-read Client|null $client
 * @property-read Employee|null $employee
 */
#[Fillable(['user_role_id', 'name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Default model attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'user_role_id' => 1, // Default to Client role (id: 1)
    ];

    /**
     * Bootstrap the model and its traits.
     */
    protected static function booted(): void
    {
        static::creating(function (User $user) {
            if (empty($user->user_role_id)) {
                $user->user_role_id = 1;
            }
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'user_role_id' => 'integer',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the role associated with the user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(UserRole::class, 'user_role_id');
    }

    /**
     * Get the admin profile associated with the user.
     */
    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class);
    }

    /**
     * Get the client profile associated with the user.
     */
    public function client(): HasOne
    {
        return $this->hasOne(Client::class);
    }

    /**
     * Get the employee profile associated with the user.
     */
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    /**
     * Check if the user has an admin role.
     */
    public function isAdmin(): bool
    {
        return $this->role?->code === 'admin' || $this->admin !== null;
    }

    /**
     * Check if the user is a super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->admin && $this->admin->role === 'super_admin';
    }

    /**
     * Check if the user is a spa admin.
     */
    public function isSpaAdmin(): bool
    {
        return $this->admin && $this->admin->role === 'spa_admin';
    }

    /**
     * Check if the user has an employee role.
     */
    public function isEmployee(): bool
    {
        return $this->role?->code === 'employee' || $this->employee !== null;
    }

    /**
     * Check if the user has a client role.
     */
    public function isClient(): bool
    {
        return ($this->role?->code === 'client' || $this->role === null) && $this->admin === null && $this->employee === null;
    }
}
