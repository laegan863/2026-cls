<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermitType extends Model
{
    use HasFactory;

    protected $fillable = [
        'permit_type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
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
