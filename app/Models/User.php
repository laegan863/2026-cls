<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'contact_no',
        'password',
        'role_id',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the role that the user belongs to.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        return $this->role?->hasPermission($permission) ?? false;
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->role?->slug === $role;
    }

    /**
     * Check if user has access to a specific module.
     */
    public function hasModuleAccess(string $moduleSlug, string $permission = 'can_view'): bool
    {
        // Admin has full access
        if ($this->role && $this->role->slug === 'admin') {
            return true;
        }

        return Module::userHasAccess($this, $moduleSlug, $permission);
    }

    /**
     * Check if user can view a module.
     */
    public function canViewModule(string $moduleSlug): bool
    {
        return $this->hasModuleAccess($moduleSlug, 'can_view');
    }

    /**
     * Check if user can create in a module.
     */
    public function canCreateInModule(string $moduleSlug): bool
    {
        return $this->hasModuleAccess($moduleSlug, 'can_create');
    }

    /**
     * Check if user can edit in a module.
     */
    public function canEditInModule(string $moduleSlug): bool
    {
        return $this->hasModuleAccess($moduleSlug, 'can_edit');
    }

    /**
     * Check if user can delete in a module.
     */
    public function canDeleteInModule(string $moduleSlug): bool
    {
        return $this->hasModuleAccess($moduleSlug, 'can_delete');
    }
}
