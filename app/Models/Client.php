<?php

namespace App\Models;

use Database\Factories\ClientFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property string|null $country_code
 * @property string|null $phone_number
 * @property string $registration_mode
 * @property string|null $google_id
 * @property Carbon|null $phone_verified_at
 * @property string|null $email_otp
 * @property Carbon|null $email_otp_expires_at
 * @property string|null $phone_otp
 * @property Carbon|null $phone_otp_expires_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User|null $user
 */
#[Fillable([
    'user_id',
    'country_code',
    'phone_number',
    'registration_mode',
    'google_id',
    'phone_verified_at',
    'email_otp',
    'email_otp_expires_at',
    'phone_otp',
    'phone_otp_expires_at',
])]
class Client extends Model
{
    /** @use HasFactory<ClientFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'phone_verified_at' => 'datetime',
            'email_otp_expires_at' => 'datetime',
            'phone_otp_expires_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the client profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
