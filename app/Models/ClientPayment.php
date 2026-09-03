<?php

namespace App\Models;

use Database\Factories\ClientPaymentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $invoice_number
 * @property int|null $therapist_service_log_id
 * @property int|null $therapist_id
 * @property int|null $receptionist_id
 * @property int|null $service_id
 * @property int|null $client_id
 * @property string $client_name
 * @property string|null $client_phone
 * @property float $subtotal
 * @property float $discount_amount
 * @property float $tax_amount
 * @property float $total_amount
 * @property string $payment_method
 * @property string|null $upi_transaction_id
 * @property string|null $upi_app
 * @property string|null $card_transaction_id
 * @property string|null $card_last_four
 * @property string|null $card_network
 * @property string|null $cash_receipt_number
 * @property array|null $cash_denomination_details
 * @property string|null $receipt_image_path
 * @property Carbon $payment_date
 * @property string $payment_status
 * @property string|null $notes
 * @property int|null $received_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read TherapistServiceLog|null $therapistServiceLog
 * @property-read Therapist|null $therapist
 * @property-read Receptionist|null $receptionist
 * @property-read Service|null $service
 * @property-read Client|null $client
 * @property-read User|null $receiver
 */
#[Fillable([
    'invoice_number',
    'therapist_service_log_id',
    'therapist_id',
    'receptionist_id',
    'service_id',
    'client_id',
    'client_name',
    'client_phone',
    'subtotal',
    'discount_amount',
    'tax_amount',
    'total_amount',
    'payment_method',
    'upi_transaction_id',
    'upi_app',
    'card_transaction_id',
    'card_last_four',
    'card_network',
    'cash_receipt_number',
    'cash_denomination_details',
    'receipt_image_path',
    'payment_date',
    'payment_status',
    'notes',
    'received_by',
])]
class ClientPayment extends Model
{
    /** @use HasFactory<ClientPaymentFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'cash_denomination_details' => 'array',
            'payment_date' => 'datetime',
        ];
    }

    public function therapistServiceLog(): BelongsTo
    {
        return $this->belongsTo(TherapistServiceLog::class);
    }

    public function therapist(): BelongsTo
    {
        return $this->belongsTo(Therapist::class);
    }

    public function receptionist(): BelongsTo
    {
        return $this->belongsTo(Receptionist::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function scopeCompleted(Builder $query): void
    {
        $query->where('payment_status', 'completed');
    }

    public function scopeForMonth(Builder $query, int $year, int $month): void
    {
        $query->whereYear('payment_date', $year)->whereMonth('payment_date', $month);
    }
}
