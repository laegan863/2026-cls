<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the permissions for the role.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permission')
            ->withTimestamps();
    }

    /**
     * Get the modules for the role.
     */
    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'role_module')
            ->withPivot(['can_view', 'can_create', 'can_edit', 'can_delete'])
            ->withTimestamps();
    }

    /**
     * Get the users for the role.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Check if the role has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        return $this->permissions()->where('slug', $permission)->exists();
    }

    /**
     * Check if the role has access to a specific module.
     */
    public function hasModuleAccess(string $moduleSlug, string $permission = 'can_view'): bool
    {
        $module = $this->modules()->where('slug', $moduleSlug)->first();
        
        if (!$module) {
            return false;
        }

        return (bool) $module->pivot->{$permission};
    }

    /**
     * Sync modules with permissions.
     */
    public function syncModulesWithPermissions(array $modulePermissions): void
    {
        $syncData = [];
        
        foreach ($modulePermissions as $moduleId => $permissions) {
            // Check if has_access is set (new simplified format)
            if (isset($permissions['has_access'])) {
                $hasAccess = (bool) $permissions['has_access'];
                // When has_access is enabled, grant all permissions for that module
                $syncData[$moduleId] = [
                    'can_view' => $hasAccess,
                    'can_create' => $hasAccess,
                    'can_edit' => $hasAccess,
                    'can_delete' => $hasAccess,
                ];
            } else {
                // Legacy format with individual permissions
                $syncData[$moduleId] = [
                    'can_view' => $permissions['can_view'] ?? false,
                    'can_create' => $permissions['can_create'] ?? false,
                    'can_edit' => $permissions['can_edit'] ?? false,
                    'can_delete' => $permissions['can_delete'] ?? false,
                ];
            }
        }

        $this->modules()->sync($syncData);
    }

    /**
     * Generate slug from name.
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($role) {
            if (empty($role->slug)) {
                $role->slug = \Str::slug($role->name);
            }
        });

        static::updating(function ($role) {
            if ($role->isDirty('name') && empty($role->slug)) {
                $role->slug = \Str::slug($role->name);
            }
        });
    }
}
