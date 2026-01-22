<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class License extends Model
{
    protected $fillable = [
        'transaction_id',
        'client_id',
        'email',
        'primary_contact_info',
        'legal_name',
        'dba',
        'fein',
        'store_name',
        'store_address',
        'store_city',
        'store_state',
        'store_zip_code',
        'store_phone',
        'store_email',
        'country',
        'state',
        'city',
        'zip_code',
        'permit_type',
        'permit_subtype',
        'jurisdiction_country',
        'jurisdiction_state',
        'jurisdiction_city',
        'jurisdiction_federal',
        'agency_name',
        'expiration_date',
        'renewal_window_open_date',
        'renewal_status',
        'billing_status',
        'submission_confirmation_number',
        'renewal_evidence_file',
        'status',
        'workflow_status',
        'validated_at',
        'validated_by',
        'approved_at',
        'approved_by',
        'rejection_reason',
    ];

    protected $casts = [
        'expiration_date' => 'date',
        'renewal_window_open_date' => 'date',
        'validated_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    // Workflow status constants
    const WORKFLOW_PENDING_VALIDATION = 'pending_validation';
    const WORKFLOW_REQUIREMENTS_PENDING = 'requirements_pending';
    const WORKFLOW_REQUIREMENTS_SUBMITTED = 'requirements_submitted';
    const WORKFLOW_APPROVED = 'approved';
    const WORKFLOW_ACTIVE = 'active';  // License is active, not yet in renewal window
    const WORKFLOW_PAYMENT_PENDING = 'payment_pending';
    const WORKFLOW_PAYMENT_COMPLETED = 'payment_completed';
    const WORKFLOW_COMPLETED = 'completed';
    const WORKFLOW_REJECTED = 'rejected';
    const WORKFLOW_EXPIRED = 'expired';  // License has expired

    // Renewal status constants
    const RENEWAL_CLOSED = 'closed';      // Not in renewal window (> 2 months until expiry)
    const RENEWAL_OPEN = 'open';          // In renewal window (within 2 months of expiry)
    const RENEWAL_EXPIRED = 'expired';    // Past expiration, but can still renew

    // Billing status constants
    const BILLING_CLOSED = 'closed';          // No billing required
    const BILLING_PENDING = 'pending';        // Within renewal window, waiting for admin/agent to create payment
    const BILLING_OPEN = 'open';              // Payment created, client can pay
    const BILLING_INVOICED = 'invoiced';      // Invoice/payment created, awaiting payment
    const BILLING_PAID = 'paid';              // Payment completed
    const BILLING_OVERRIDDEN = 'overridden';  // Payment waived

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get the assigned agent from the latest payment.
     * Falls back to the payment creator if no assigned_agent_id is set.
     */
    public function getAssignedAgentAttribute(): ?User
    {
        $latestPayment = $this->latestPayment;
        if (!$latestPayment) {
            return null;
        }
        // Use assigned_agent_id if set, otherwise fall back to created_by
        return $latestPayment->assignedAgent ?? $latestPayment->creator;
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function requirements(): HasMany
    {
        return $this->hasMany(LicenseRequirement::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(LicensePayment::class);
    }

    public function latestPayment(): HasOne
    {
        return $this->hasOne(LicensePayment::class)->latestOfMany();
    }

    public function activePayment(): HasOne
    {
        return $this->hasOne(LicensePayment::class)->where('status', 'open');
    }

    // Workflow status helpers
    public function isPendingValidation(): bool
    {
        return $this->workflow_status === self::WORKFLOW_PENDING_VALIDATION;
    }

    public function hasRequirementsPending(): bool
    {
        return $this->workflow_status === self::WORKFLOW_REQUIREMENTS_PENDING;
    }

    public function hasRequirementsSubmitted(): bool
    {
        return $this->workflow_status === self::WORKFLOW_REQUIREMENTS_SUBMITTED;
    }

    public function isApproved(): bool
    {
        return $this->workflow_status === self::WORKFLOW_APPROVED;
    }

    public function isActive(): bool
    {
        return $this->workflow_status === self::WORKFLOW_ACTIVE;
    }

    public function isPaymentPending(): bool
    {
        return $this->workflow_status === self::WORKFLOW_PAYMENT_PENDING;
    }

    public function isPaymentCompleted(): bool
    {
        return $this->workflow_status === self::WORKFLOW_PAYMENT_COMPLETED;
    }

    public function isCompleted(): bool
    {
        return $this->workflow_status === self::WORKFLOW_COMPLETED;
    }

    public function isRejected(): bool
    {
        return $this->workflow_status === self::WORKFLOW_REJECTED;
    }

    public function isExpired(): bool
    {
        return $this->workflow_status === self::WORKFLOW_EXPIRED;
    }

    // Check if license has expired
    public function hasExpired(): bool
    {
        if (!$this->expiration_date) {
            return false;
        }
        return $this->expiration_date->isPast();
    }

    // Check if license is within 2 months of expiration
    public function isWithinRenewalWindow(): bool
    {
        if (!$this->expiration_date) {
            return false;
        }
        
        return $this->expiration_date->subMonths(2)->isPast() && !$this->expiration_date->isPast();
    }

    // Check if all requirements are approved
    public function allRequirementsApproved(): bool
    {
        if ($this->requirements()->count() === 0) {
            return true;
        }

        return $this->requirements()
            ->where('status', '!=', LicenseRequirement::STATUS_APPROVED)
            ->count() === 0;
    }

    // Check if any requirements are pending client submission
    public function hasPendingRequirements(): bool
    {
        return $this->requirements()
            ->whereIn('status', [LicenseRequirement::STATUS_PENDING, LicenseRequirement::STATUS_REJECTED])
            ->count() > 0;
    }

    // Workflow transitions
    public function markAsRequirementsPending(): void
    {
        $this->update(['workflow_status' => self::WORKFLOW_REQUIREMENTS_PENDING]);
    }

    public function markAsRequirementsSubmitted(): void
    {
        $this->update(['workflow_status' => self::WORKFLOW_REQUIREMENTS_SUBMITTED]);
    }

    public function approve(int $approverId): void
    {
        $this->update([
            'workflow_status' => self::WORKFLOW_APPROVED,
            'approved_at' => now(),
            'approved_by' => $approverId,
        ]);
    }

    public function markAsActive(): void
    {
        $this->update(['workflow_status' => self::WORKFLOW_ACTIVE]);
    }

    public function markAsPaymentPending(): void
    {
        $this->update(['workflow_status' => self::WORKFLOW_PAYMENT_PENDING]);
    }

    public function markAsPaymentCompleted(): void
    {
        $this->update(['workflow_status' => self::WORKFLOW_PAYMENT_COMPLETED]);
    }

    public function markAsCompleted(): void
    {
        $this->update(['workflow_status' => self::WORKFLOW_COMPLETED]);
    }

    public function markAsExpired(): void
    {
        $this->update(['workflow_status' => self::WORKFLOW_EXPIRED]);
    }

    /**
     * Determine the appropriate workflow status after approval based on expiration date
     * - If within 2 months of expiry: payment_pending
     * - If more than 2 months until expiry: active
     * - If already expired: expired
     */
    public function determinePostApprovalStatus(): string
    {
        if ($this->hasExpired()) {
            return self::WORKFLOW_EXPIRED;
        }
        
        if ($this->isWithinRenewalWindow()) {
            return self::WORKFLOW_PAYMENT_PENDING;
        }
        
        return self::WORKFLOW_ACTIVE;
    }

    /**
     * Apply the appropriate status after approval
     */
    public function applyPostApprovalStatus(int $approverId): void
    {
        $this->approve($approverId);
        
        $newStatus = $this->determinePostApprovalStatus();
        $this->update(['workflow_status' => $newStatus]);
    }

    public function reject(int $rejectorId, string $reason): void
    {
        $this->update([
            'workflow_status' => self::WORKFLOW_REJECTED,
            'rejection_reason' => $reason,
            'validated_by' => $rejectorId,
            'validated_at' => now(),
        ]);
    }

    // Get human-readable workflow status
    public function getWorkflowStatusLabelAttribute(): string
    {
        return match($this->workflow_status) {
            self::WORKFLOW_PENDING_VALIDATION => 'Pending Validation',
            self::WORKFLOW_REQUIREMENTS_PENDING => 'Requirements Pending',
            self::WORKFLOW_REQUIREMENTS_SUBMITTED => 'Requirements Submitted',
            self::WORKFLOW_APPROVED => 'Approved',
            self::WORKFLOW_ACTIVE => 'Active',
            self::WORKFLOW_PAYMENT_PENDING => 'Payment Pending',
            self::WORKFLOW_PAYMENT_COMPLETED => 'Payment Completed',
            self::WORKFLOW_COMPLETED => 'Completed',
            self::WORKFLOW_REJECTED => 'Rejected',
            self::WORKFLOW_EXPIRED => 'Expired',
            default => 'Unknown',
        };
    }

    // Get badge type for workflow status
    public function getWorkflowStatusBadgeAttribute(): string
    {
        return match($this->workflow_status) {
            self::WORKFLOW_PENDING_VALIDATION => 'warning',
            self::WORKFLOW_REQUIREMENTS_PENDING => 'info',
            self::WORKFLOW_REQUIREMENTS_SUBMITTED => 'primary',
            self::WORKFLOW_APPROVED => 'success',
            self::WORKFLOW_ACTIVE => 'success',
            self::WORKFLOW_PAYMENT_PENDING => 'warning',
            self::WORKFLOW_PAYMENT_COMPLETED => 'success',
            self::WORKFLOW_COMPLETED => 'success',
            self::WORKFLOW_REJECTED => 'danger',
            self::WORKFLOW_EXPIRED => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get days until expiration
     */
    public function getDaysUntilExpirationAttribute(): ?int
    {
        if (!$this->expiration_date) {
            return null;
        }
        return now()->diffInDays($this->expiration_date, false);
    }

    /**
     * Check if license should enter renewal window (called by scheduler)
     */
    public function shouldEnterRenewalWindow(): bool
    {
        return $this->isActive() && $this->isWithinRenewalWindow();
    }

    // ========================================
    // Renewal & Billing Status Methods
    // ========================================

    /**
     * Check if renewal is open
     */
    public function isRenewalOpen(): bool
    {
        return $this->renewal_status === self::RENEWAL_OPEN;
    }

    /**
     * Check if renewal is closed
     */
    public function isRenewalClosed(): bool
    {
        return $this->renewal_status === self::RENEWAL_CLOSED;
    }

    /**
     * Check if renewal is expired (past expiry but can still renew)
     */
    public function isRenewalExpired(): bool
    {
        return $this->renewal_status === self::RENEWAL_EXPIRED;
    }

    /**
     * Check if billing is open (payment can be created)
     */
    public function isBillingOpen(): bool
    {
        return $this->billing_status === self::BILLING_OPEN;
    }

    /**
     * Check if already invoiced
     */
    public function isBillingInvoiced(): bool
    {
        return $this->billing_status === self::BILLING_INVOICED;
    }

    /**
     * Check if billing is paid
     */
    public function isBillingPaid(): bool
    {
        return $this->billing_status === self::BILLING_PAID;
    }

    /**
     * Check if agent/admin can create payment
     * Payment can be created when renewal is open/expired AND billing is pending
     */
    public function canCreatePayment(): bool
    {
        return in_array($this->renewal_status, [self::RENEWAL_OPEN, self::RENEWAL_EXPIRED]) 
            && $this->billing_status === self::BILLING_PENDING;
    }

    /**
     * Mark billing as open (payment created by admin/agent)
     */
    public function markBillingOpen(): void
    {
        $this->update(['billing_status' => self::BILLING_OPEN]);
    }

    /**
     * Update renewal and billing status based on expiration date
     * Called by scheduler or manually
     */
    public function updateRenewalBillingStatus(): void
    {
        $updates = [];

        if (!$this->expiration_date) {
            // No expiration date set
            $updates['renewal_status'] = self::RENEWAL_CLOSED;
            // Preserve paid/overridden status
            if (!in_array($this->billing_status, [self::BILLING_PAID, self::BILLING_OVERRIDDEN])) {
                $updates['billing_status'] = self::BILLING_CLOSED;
            }
        } elseif ($this->hasExpired()) {
            // License has expired - can still renew
            $updates['renewal_status'] = self::RENEWAL_EXPIRED;
            // If not already open/invoiced/paid/overridden, set to pending (waiting for admin to create payment)
            if (!in_array($this->billing_status, [self::BILLING_OPEN, self::BILLING_INVOICED, self::BILLING_PAID, self::BILLING_OVERRIDDEN])) {
                $updates['billing_status'] = self::BILLING_PENDING;
            }
        } elseif ($this->isWithinRenewalWindow()) {
            // Within 2 months of expiry - renewal window open
            $updates['renewal_status'] = self::RENEWAL_OPEN;
            // If not already open/invoiced/paid/overridden, set to pending (waiting for admin to create payment)
            if (!in_array($this->billing_status, [self::BILLING_OPEN, self::BILLING_INVOICED, self::BILLING_PAID, self::BILLING_OVERRIDDEN])) {
                $updates['billing_status'] = self::BILLING_PENDING;
            }
        } else {
            // More than 2 months until expiry - closed
            $updates['renewal_status'] = self::RENEWAL_CLOSED;
            // Preserve paid/overridden status, otherwise set to closed
            if (!in_array($this->billing_status, [self::BILLING_PAID, self::BILLING_OVERRIDDEN])) {
                $updates['billing_status'] = self::BILLING_CLOSED;
            }
        }

        if (!empty($updates)) {
            $this->update($updates);
        }
    }

    /**
     * Mark billing as invoiced (payment created)
     */
    public function markBillingInvoiced(): void
    {
        $this->update(['billing_status' => self::BILLING_INVOICED]);
    }

    /**
     * Mark billing as paid
     */
    public function markBillingPaid(): void
    {
        $this->update([
            'billing_status' => self::BILLING_PAID,
            'workflow_status' => self::WORKFLOW_PAYMENT_COMPLETED,
        ]);
    }

    /**
     * Mark billing as overridden (waived)
     */
    public function markBillingOverridden(): void
    {
        $this->update([
            'billing_status' => self::BILLING_OVERRIDDEN,
            'workflow_status' => self::WORKFLOW_PAYMENT_COMPLETED,
        ]);
    }

    /**
     * Get renewal status label
     */
    public function getRenewalStatusLabelAttribute(): string
    {
        return match($this->renewal_status) {
            self::RENEWAL_CLOSED => 'Closed',
            self::RENEWAL_OPEN => 'Open',
            self::RENEWAL_EXPIRED => 'Expired (Renewable)',
            default => 'Unknown',
        };
    }

    /**
     * Get renewal status badge type
     */
    public function getRenewalStatusBadgeAttribute(): string
    {
        return match($this->renewal_status) {
            self::RENEWAL_CLOSED => 'secondary',
            self::RENEWAL_OPEN => 'warning',
            self::RENEWAL_EXPIRED => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get billing status label
     */
    public function getBillingStatusLabelAttribute(): string
    {
        return match($this->billing_status) {
            self::BILLING_CLOSED => 'Closed',
            self::BILLING_PENDING => 'Pending',
            self::BILLING_OPEN => 'Open',
            self::BILLING_INVOICED => 'Invoiced',
            self::BILLING_PAID => 'Paid',
            self::BILLING_OVERRIDDEN => 'Overridden',
            default => 'Unknown',
        };
    }

    /**
     * Get billing status badge type
     */
    public function getBillingStatusBadgeAttribute(): string
    {
        return match($this->billing_status) {
            self::BILLING_CLOSED => 'secondary',
            self::BILLING_PENDING => 'warning',
            self::BILLING_OPEN => 'info',
            self::BILLING_INVOICED => 'primary',
            self::BILLING_PAID => 'success',
            self::BILLING_OVERRIDDEN => 'dark',
            default => 'secondary',
        };
    }
}
