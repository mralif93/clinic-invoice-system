<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'staff_id',
        'name',
        'email',
        'phone',
        'role',
        'department',
        'designation',
        'employee_code',
        'avatar',
        'status',
        'password',
        'last_login_at',
        'last_login_ip',
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
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * User Roles Relationship (Many-to-Many via user_roles)
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    /**
     * Dynamic role attribute for backward compatibility with existing Blade layouts and views.
     * Returns the assigned role display_name or fallback to stored/default role.
     */
    public function getRoleAttribute(): string
    {
        if ($this->relationLoaded('roles')) {
            $firstRole = $this->roles->first();
            if ($firstRole) {
                return $firstRole->display_name;
            }
        } elseif ($this->exists) {
            $firstRole = $this->roles()->first();
            if ($firstRole) {
                return $firstRole->display_name;
            }
        }

        return $this->attributes['role'] ?? 'staff';
    }

    /**
     * Check if user has a specific role by name or display_name.
     */
    public function hasRole(string|array $roles): bool
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }

        if ($this->exists) {
            $slugs = array_map(function ($r) {
                return strtolower(str_replace(' ', '_', trim($r)));
            }, $roles);

            $hasDbRole = $this->roles()
                ->where(function ($query) use ($roles, $slugs) {
                    $query->whereIn('name', $roles)
                        ->orWhereIn('name', $slugs)
                        ->orWhereIn('display_name', $roles);
                })
                ->exists();

            if ($hasDbRole) {
                return true;
            }
        }

        $attrRole = $this->attributes['role'] ?? null;
        if ($attrRole) {
            $attrSlug = strtolower(str_replace(' ', '_', trim($attrRole)));
            foreach ($roles as $r) {
                $rSlug = strtolower(str_replace(' ', '_', trim($r)));
                if ($attrRole === $r || $attrSlug === $rSlug) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check if user has a specific permission via assigned roles.
     */
    public function hasPermission(string $permission): bool
    {
        if (!$this->exists) {
            return false;
        }

        return $this->roles()
            ->whereHas('permissions', fn ($query) => $query->where('name', $permission))
            ->exists();
    }

    /**
     * Check if user is admin / doctor.
     */
    public function isAdmin(): bool
    {
        $rawRole = strtolower($this->attributes['role'] ?? '');
        return in_array($rawRole, ['admin', 'super_admin', 'doctor'])
            || $this->hasRole(['admin', 'super_admin', 'doctor', 'Super Administrator', 'Clinic Administrator', 'Doctor / Clinical Lead']);
    }

    /**
     * Check if user is cashier / receptionist / staff.
     */
    public function isStaff(): bool
    {
        $rawRole = strtolower($this->attributes['role'] ?? '');
        return in_array($rawRole, ['staff', 'cashier', 'receptionist'])
            || $this->hasRole(['staff', 'cashier', 'receptionist']);
    }

    /**
     * Check if user is active.
     */
    public function isActive(): bool
    {
        return ($this->attributes['status'] ?? 'active') === 'active';
    }

    /**
     * Virtual is_active attribute for backward-compatibility.
     */
    public function getIsActiveAttribute(): bool
    {
        return $this->isActive();
    }
}
