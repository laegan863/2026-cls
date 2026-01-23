<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LicenseRenewal extends Model
{
    protected $fillable = [
        'license_id',
        'payment_id',
        'renewal_number',
        'previous_expiration_date',
        'new_expiration_date',
        'renewal_evidence_file',
        'file_uploaded_at',
        'file_uploaded_by',
        'status',
        'notes',
    ];

    protected $casts = [
        'previous_expiration_date' => 'date',
        'new_expiration_date' => 'date',
        'file_uploaded_at' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING_PAYMENT = 'pending_payment';
    const STATUS_PENDING_FILE = 'pending_file';
    const STATUS_COMPLETED = 'completed';

    /**
     * Get the license that owns this renewal
     */
    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }

    /**
     * Get the payment associated with this renewal
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(LicensePayment::class, 'payment_id');
    }

    /**
     * Get the user who uploaded the file
     */
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'file_uploaded_by');
    }

    /**
     * Check if renewal is pending file upload
     */
    public function isPendingFile(): bool
    {
        return $this->status === self::STATUS_PENDING_FILE;
    }

    /**
     * Check if renewal is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Mark as pending file
     */
    public function markAsPendingFile(): void
    {
        $this->update(['status' => self::STATUS_PENDING_FILE]);
    }

    /**
     * Mark as completed
     */
    public function markAsCompleted(): void
    {
        $this->update(['status' => self::STATUS_COMPLETED]);
    }
}
