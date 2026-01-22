<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermitSubType extends Model
{
    use HasFactory;

    protected $fillable = [
        'permit_type_id',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the parent permit type.
     */
    public function permitType()
    {
        return $this->belongsTo(PermitType::class);
    }
}
