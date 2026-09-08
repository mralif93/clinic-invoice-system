<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of clinic staff, doctors, roles, and account statuses.
     */
    public function index(Request $request): View
    {
        $query = User::with('roles');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('staff_id', 'like', "%{$search}%")
                    ->orWhere('employee_code', 'like', "%{$search}%")
                    ->orWhere('designation', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($roleId = $request->input('role_id')) {
            $query->whereHas('roles', function ($q) use ($roleId) {
                $q->where('roles.id', $roleId);
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();
        $roles = Role::orderBy('name')->get();

        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'admins' => User::where('role', 'admin')->orWhere('role', 'super_admin')->orWhere('role', 'doctor')->count(),
            'suspended' => User::where('status', 'suspended')->count(),
        ];

        return view('admin.users.index', compact('users', 'roles', 'stats'));
    }

    /**
     * Store a newly created clinic staff or doctor account.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'staff_id' => ['nullable', 'string', 'max:50', 'unique:users,staff_id'],
            'employee_code' => ['nullable', 'string', 'max:50', 'unique:users,employee_code'],
            'phone' => ['nullable', 'string', 'max:30'],
            'department' => ['nullable', 'string', 'max:100'],
            'designation' => ['nullable', 'string', 'max:100'],
            'password' => ['required', 'string', 'min:6'],
            'status' => ['nullable', 'in:active,inactive,suspended'],
            'role_ids' => ['nullable', 'array'],
            'role_ids.*' => ['exists:roles,id'],
        ]);

        $firstRoleId = !empty($validated['role_ids']) ? $validated['role_ids'][0] : null;
        $primaryRole = $firstRoleId ? Role::find($firstRoleId)?->name : 'staff';

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'staff_id' => $validated['staff_id'] ?? null,
            'employee_code' => $validated['employee_code'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'department' => $validated['department'] ?? 'General Outpatient',
            'designation' => $validated['designation'] ?? 'Clinical Staff',
            'role' => $primaryRole ?? 'staff',
            'password' => Hash::make($validated['password']),
            'status' => $validated['status'] ?? 'active',
            'email_verified_at' => now(),
        ]);

        if (!empty($validated['role_ids'])) {
            $user->roles()->sync($validated['role_ids']);
        }

        AuditLog::log(
            'USER_CREATED',
            'Users',
            $user->staff_id ?? "USER-{$user->id}",
            "Created staff account for '{$user->name}' ({$user->email})",
            [
                'name' => $user->name,
                'email' => $user->email,
                'staff_id' => $user->staff_id,
                'status' => $user->status,
                'roles' => $validated['role_ids'] ?? [],
            ]
        );

        return redirect()->route('admin.users.index')
            ->with('success', "Staff account for {$user->name} created successfully.");
    }

    /**
     * Update user details, role assignments, or account status.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'staff_id' => ['nullable', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'employee_code' => ['nullable', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'department' => ['nullable', 'string', 'max:100'],
            'designation' => ['nullable', 'string', 'max:100'],
            'password' => ['nullable', 'string', 'min:6'],
            'status' => ['nullable', 'in:active,inactive,suspended'],
            'role_ids' => ['nullable', 'array'],
            'role_ids.*' => ['exists:roles,id'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->staff_id = $validated['staff_id'] ?? null;
        $user->employee_code = $validated['employee_code'] ?? null;
        $user->phone = $validated['phone'] ?? null;
        $user->department = $validated['department'] ?? $user->department;
        $user->designation = $validated['designation'] ?? $user->designation;
        $user->status = $validated['status'] ?? 'active';

        if (!empty($validated['role_ids'])) {
            $firstRoleId = $validated['role_ids'][0];
            $user->role = Role::find($firstRoleId)?->name ?? $user->role;
        }

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        if (isset($validated['role_ids'])) {
            $user->roles()->sync($validated['role_ids']);
        }

        AuditLog::log(
            'USER_UPDATED',
            'Users',
            $user->staff_id ?? "USER-{$user->id}",
            "Updated staff profile and permissions for '{$user->name}'",
            [
                'name' => $user->name,
                'email' => $user->email,
                'staff_id' => $user->staff_id,
                'status' => $user->status,
                'roles' => $validated['role_ids'] ?? [],
            ]
        );

        return redirect()->route('admin.users.index')
            ->with('success', "Staff account for {$user->name} updated successfully.");
    }

    /**
     * Delete user account with safety checks.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own logged-in station account.');
        }

        $oldName = $user->name;
        $user->delete();

        AuditLog::log(
            'USER_DELETED',
            'Users',
            $user->staff_id ?? "USER-{$user->id}",
            "Deleted staff account '{$oldName}'",
            ['name' => $oldName, 'email' => $user->email]
        );

        return redirect()->route('admin.users.index')
            ->with('success', "Staff account for {$oldName} deleted successfully.");
    }

    /**
     * Direct password reset for user account by Administrator.
     */
    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user->password = Hash::make($validated['password']);
        $user->save();

        AuditLog::log(
            'USER_PASSWORD_RESET',
            'Users',
            $user->staff_id ?? "USER-{$user->id}",
            "Reset credentials for user account '{$user->name}'",
            ['user_id' => $user->id, 'email' => $user->email]
        );

        return redirect()->route('admin.users.index')
            ->with('success', "Password for {$user->name} has been successfully updated.");
    }

    /**
     * Block, suspend, or activate staff account.
     */
    public function toggleStatus(Request $request, User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot suspend your own active administrator station.');
        }

        $oldStatus = $user->status;
        $newStatus = $oldStatus === 'active' ? 'suspended' : 'active';

        $user->status = $newStatus;
        $user->save();

        $actionText = $newStatus === 'suspended' ? 'suspended' : 'activated';

        AuditLog::log(
            $newStatus === 'suspended' ? 'USER_SUSPENDED' : 'USER_ACTIVATED',
            'Users',
            $user->staff_id ?? "USER-{$user->id}",
            "Account status {$actionText} for '{$user->name}'",
            ['status' => $newStatus]
        );

        return redirect()->route('admin.users.index')
            ->with('success', "Staff account {$user->name} has been {$actionText}.");
    }
}
