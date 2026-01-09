<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'route',
        'description',
        'parent_id',
        'order',
        'is_active',
        'is_coming_soon',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_coming_soon' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get the parent module.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'parent_id');
    }

    /**
     * Get the child modules.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Module::class, 'parent_id')->orderBy('order');
    }

    /**
     * Get the roles that have access to this module.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_module')
            ->withPivot(['can_view', 'can_create', 'can_edit', 'can_delete'])
            ->withTimestamps();
    }

    /**
     * Get only parent modules (no parent_id).
     */
    public function scopeParentModules($query)
    {
        return $query->whereNull('parent_id')->orderBy('order');
    }

    /**
     * Get only active modules.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if user has access to this module.
     */
    public static function userHasAccess(User $user, string $moduleSlug, string $permission = 'can_view'): bool
    {
        if (!$user->role) {
            return false;
        }

        $module = self::where('slug', $moduleSlug)->first();
        if (!$module) {
            return false;
        }

        $roleModule = $user->role->modules()
            ->where('modules.id', $module->id)
            ->first();

        if (!$roleModule) {
            return false;
        }

        return (bool) $roleModule->pivot->{$permission};
    }

    /**
     * Get accessible modules for a user.
     */
    public static function getAccessibleModules(User $user): \Illuminate\Database\Eloquent\Collection
    {
        if (!$user->role) {
            return collect([]);
        }

        return $user->role->modules()
            ->where('is_active', true)
            ->wherePivot('can_view', true)
            ->orderBy('order')
            ->get();
    }

    /**
     * Get sidebar menu for a user (with hierarchy).
     */
    public static function getSidebarMenu(User $user): \Illuminate\Support\Collection
    {
        if (!$user->role) {
            return collect([]);
        }

        // Admin has access to all modules
        if ($user->role->slug === 'admin') {
            return self::whereNull('parent_id')
                ->where('is_active', true)
                ->with(['children' => function ($query) {
                    $query->where('is_active', true)
                        ->orderBy('order');
                }])
                ->orderBy('order')
                ->get();
        }

        // For Agent and Client roles, show only assigned modules
        $accessibleModuleIds = $user->role->modules()
            ->where('is_active', true)
            ->wherePivot('can_view', true)
            ->pluck('modules.id')
            ->toArray();

        return self::whereNull('parent_id')
            ->where('is_active', true)
            ->whereIn('id', $accessibleModuleIds)
            ->with(['children' => function ($query) use ($accessibleModuleIds) {
                $query->whereIn('id', $accessibleModuleIds)
                    ->where('is_active', true)
                    ->orderBy('order');
            }])
            ->orderBy('order')
            ->get();
    }
}
