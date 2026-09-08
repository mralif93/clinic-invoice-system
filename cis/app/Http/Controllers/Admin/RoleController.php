<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RoleController extends Controller
{
    /**
     * Display a listing of access roles and assigned permission matrix.
     */
    public function index(Request $request): View
    {
        $query = Role::with(['permissions', 'users']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('display_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $roles = $query->latest()->paginate(10)->withQueryString();
        $permissions = Permission::all()->groupBy('module');

        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:roles,name'],
            'display_name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
            'permission_ids' => ['nullable', 'array'],
            'permission_ids.*' => ['exists:permissions,id'],
        ]);

        $role = Role::create([
            'name' => strtolower(str_replace(' ', '_', trim($validated['name']))),
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
            'is_system' => false,
        ]);

        if (!empty($validated['permission_ids'])) {
            $role->permissions()->sync($validated['permission_ids']);
        }

        AuditLog::log(
            'ROLE_CREATED',
            'Governance',
            "ROLE-{$role->id}",
            "Created access role '{$role->display_name}' ({$role->name})",
            ['permissions_count' => count($validated['permission_ids'] ?? [])]
        );

        return redirect()->route('admin.roles.index')
            ->with('success', "Role '{$role->display_name}' created successfully.");
    }

    /**
     * Update the specified role and its permission matrix.
     */
    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $request->validate([
            'display_name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:255'],
            'permission_ids' => ['nullable', 'array'],
            'permission_ids.*' => ['exists:permissions,id'],
        ]);

        $role->display_name = $validated['display_name'];
        $role->description = $validated['description'] ?? $role->description;
        $role->save();

        if (isset($validated['permission_ids'])) {
            $role->permissions()->sync($validated['permission_ids']);
        }

        AuditLog::log(
            'ROLE_UPDATED',
            'Governance',
            "ROLE-{$role->id}",
            "Updated permission matrix for role '{$role->display_name}'",
            ['permissions_count' => count($validated['permission_ids'] ?? [])]
        );

        return redirect()->route('admin.roles.index')
            ->with('success', "Role '{$role->display_name}' updated successfully.");
    }

    /**
     * Remove the specified role with system role safeguard.
     */
    public function destroy(Request $request, Role $role): RedirectResponse
    {
        if ($role->is_system) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Protected system roles cannot be deleted.');
        }

        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')
                ->with('error', "Cannot delete role '{$role->display_name}' because it currently has assigned active staff.");
        }

        $oldName = $role->display_name;
        $role->permissions()->detach();
        $role->delete();

        AuditLog::log(
            'ROLE_DELETED',
            'Governance',
            "ROLE-{$role->id}",
            "Deleted access role '{$oldName}'",
            ['role' => $oldName]
        );

        return redirect()->route('admin.roles.index')
            ->with('success', "Access role '{$oldName}' deleted successfully.");
    }
}
