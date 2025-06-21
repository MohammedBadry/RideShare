<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function hasPermission($permission)
    {
        // Super admin has all permissions
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Define permission matrix - more maintainable and clear
        $rolePermissions = [
            'admin' => [
                'dashboard' => ['view'],
                'trips' => ['view', 'create', 'edit', 'delete'],
                'drivers' => ['view', 'create', 'edit', 'delete'],
                'vehicles' => ['view', 'create', 'edit', 'delete'],
                'analytics' => ['view', 'export'],
            ],
            'manager' => [
                'dashboard' => ['view'],
                'trips' => ['view', 'create', 'edit', 'delete'],
                'drivers' => ['view', 'create', 'edit', 'delete'],
                'vehicles' => ['view', 'create', 'edit', 'delete'],
                'analytics' => ['view'],
            ],
            'viewer' => [
                'dashboard' => ['view'],
                'trips' => ['view', 'create'],
                'drivers' => ['view'],
            ],
        ];

        // Parse permission string (e.g., 'view_trips' -> ['view', 'trips'])
        $parts = explode('_', $permission, 2);
        if (count($parts) !== 2) {
            return false;
        }

        [$action, $module] = $parts;

        // Check if role has access to this module and action
        return isset($rolePermissions[$this->role][$module]) &&
               in_array($action, $rolePermissions[$this->role][$module]);
    }

    /**
     * Check if user has any permission for a specific module
     */
    public function hasModuleAccess($module)
    {
        $rolePermissions = [
            'admin' => ['dashboard', 'trips', 'drivers', 'vehicles', 'analytics'],
            'manager' => ['dashboard', 'trips', 'drivers', 'vehicles', 'analytics'],
            'viewer' => ['dashboard', 'trips', 'drivers'],
        ];

        return isset($rolePermissions[$this->role]) &&
               in_array($module, $rolePermissions[$this->role]);
    }

    /**
     * Get all permissions for a specific module
     */
    public function getModulePermissions($module)
    {
        $rolePermissions = [
            'admin' => [
                'dashboard' => ['view'],
                'trips' => ['view', 'create', 'edit', 'delete'],
                'drivers' => ['view', 'create', 'edit', 'delete'],
                'vehicles' => ['view', 'create', 'edit', 'delete'],
                'analytics' => ['view', 'export'],
            ],
            'manager' => [
                'dashboard' => ['view'],
                'trips' => ['view', 'create', 'edit', 'delete'],
                'drivers' => ['view', 'create', 'edit', 'delete'],
                'vehicles' => ['view', 'create', 'edit', 'delete'],
                'analytics' => ['view'],
            ],
            'viewer' => [
                'dashboard' => ['view'],
                'trips' => ['view'],
                'drivers' => ['view'],
            ],
        ];

        return $rolePermissions[$this->role][$module] ?? [];
    }

    /**
     * Check if user can perform a specific action on a module
     */
    public function canPerform($action, $module)
    {
        return $this->hasPermission($action . '_' . $module);
    }
}
