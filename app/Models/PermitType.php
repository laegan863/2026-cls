<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermitType extends Model
{
    use HasFactory;

    protected $fillable = [
        'permit_type',
        'short_name',
        'description',
        'jurisdiction_level',
        'agency_name',
        'expiration_date',
        'has_renewal',
        'renewal_cycle_months',
        'reminder_days',
        'government_fee',
        'cls_service_fee',
        'city_county_fee',
        'additional_fee',
        'additional_fee_description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'has_renewal' => 'boolean',
        'renewal_cycle_months' => 'integer',
        'reminder_days' => 'array',
        'government_fee' => 'decimal:2',
        'cls_service_fee' => 'decimal:2',
        'city_county_fee' => 'decimal:2',
        'additional_fee' => 'decimal:2',
        'expiration_date' => 'date',
    ];

    /**
     * Get the sub-permits for this permit type.
     */
    public function subPermits()
    {
        return $this->hasMany(PermitSubType::class);
    }

    /**
     * Get only active sub-permits for this permit type.
     */
    public function activeSubPermits()
    {
        return $this->hasMany(PermitSubType::class)->where('is_active', true);
    }
}
