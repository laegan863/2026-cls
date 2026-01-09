<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermitType extends Model
{
    use HasFactory;

    protected $fillable = [
        'permit_type',
        'sub_type',
        'is_active',
    ];

    protected $casts = [
        'sub_type' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the sub types as an array.
     */
    public function getSubTypesArray(): array
    {
        return $this->sub_type ?? [];
    }

    /**
     * Add a sub type to the existing array.
     */
    public function addSubType(string $subType): void
    {
        $subTypes = $this->sub_type ?? [];
        if (!in_array($subType, $subTypes)) {
            $subTypes[] = $subType;
            $this->sub_type = $subTypes;
            $this->save();
        }
    }

    /**
     * Remove a sub type from the array.
     */
    public function removeSubType(string $subType): void
    {
        $subTypes = $this->sub_type ?? [];
        $subTypes = array_filter($subTypes, fn($type) => $type !== $subType);
        $this->sub_type = array_values($subTypes);
        $this->save();
    }
}
