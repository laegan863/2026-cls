<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class LicensePayment extends Model
{
    protected $fillable = [
        'license_id',
        'created_by',
        'invoice_number',
        'total_amount',
        'status',
        'payment_method',
        'stripe_payment_intent_id',
        'stripe_checkout_session_id',
        'notes',
        'paid_at',
        'paid_by',
        'overridden_by',
        'override_reason',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_OPEN = 'open';
    const STATUS_PAID = 'paid';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_OVERRIDDEN = 'overridden';

    // Payment method constants
    const METHOD_ONLINE = 'online';
    const METHOD_OFFLINE = 'offline';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->invoice_number)) {
                $payment->invoice_number = 'INV-' . strtoupper(Str::random(8)) . '-' . date('Ymd');
            }
        });
    }

    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function payer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    public function overrider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'overridden_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(LicensePaymentItem::class)->orderBy('sort_order');
    }

    // Helper methods
    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isOpen(): bool
    {
        return $this->status === self::STATUS_OPEN;
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isOverridden(): bool
    {
        return $this->status === self::STATUS_OVERRIDDEN;
    }

    public function calculateTotal(): float
    {
        return $this->items()->sum('amount');
    }

    public function recalculateTotal(): void
    {
        $this->update(['total_amount' => $this->calculateTotal()]);
    }

    public function openForPayment(): void
    {
        $this->recalculateTotal();
        $this->update(['status' => self::STATUS_OPEN]);
    }

    public function markAsPaid(string $method, int $paidBy = null, string $stripePaymentIntentId = null): void
    {
        $this->update([
            'status' => self::STATUS_PAID,
            'payment_method' => $method,
            'paid_at' => now(),
            'paid_by' => $paidBy,
            'stripe_payment_intent_id' => $stripePaymentIntentId,
        ]);
    }

    public function override(int $overriddenBy, string $reason): void
    {
        $this->update([
            'status' => self::STATUS_OVERRIDDEN,
            'payment_method' => self::METHOD_OFFLINE,
            'overridden_by' => $overriddenBy,
            'override_reason' => $reason,
            'paid_at' => now(),
        ]);
    }

    public function cancel(): void
    {
        $this->update(['status' => self::STATUS_CANCELLED]);
    }
}
