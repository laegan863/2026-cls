<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class License extends Model
{
    protected $fillable = [
        'client_id',
        'email',
        'primary_contact_info',
        'legal_name',
        'dba',
        'fein',
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
        'assigned_agent_id',
        'renewal_status',
        'billing_status',
        'submission_confirmation_number',
    ];

    protected $casts = [
        'expiration_date' => 'date',
        'renewal_window_open_date' => 'date',
    ];

    /**
     * Get the client (user) that owns the license
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get the assigned agent for this license
     */
    public function assignedAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_agent_id');
    }
}
