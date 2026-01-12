<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LicensePaymentItem extends Model
{
    protected $fillable = [
        'license_payment_id',
        'label',
        'description',
        'amount',
        'sort_order',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        // Recalculate parent payment total when item is saved or deleted
        static::saved(function ($item) {
            $item->payment->recalculateTotal();
        });

        static::deleted(function ($item) {
            $item->payment->recalculateTotal();
        });
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(LicensePayment::class, 'license_payment_id');
    }
}
