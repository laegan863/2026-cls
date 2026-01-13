<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the licenses associated with this agency.
     */
    public function licenses()
    {
        return $this->hasMany(License::class, 'agency_name', 'name');
    }

    /**
     * Scope to get only active agencies.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
