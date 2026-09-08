<?php

namespace Tests\Unit;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRbacUnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_role_assignment_and_has_role_check()
    {
        $roleDoctor = Role::create([
            'name' => 'doctor',
            'display_name' => 'Doctor / Clinical Lead',
        ]);

        $user = User::create([
            'name' => 'Dr. Aiman Hakim',
            'email' => 'aiman@clinic.my',
            'password' => bcrypt('secret123'),
            'status' => 'active',
            'role' => 'doctor',
        ]);

        $user->roles()->attach($roleDoctor);

        $this->assertTrue($user->hasRole('doctor'));
        $this->assertTrue($user->hasRole('Doctor / Clinical Lead'));
        $this->assertTrue($user->isAdmin());
        $this->assertFalse($user->isStaff());
        $this->assertTrue($user->isActive());
        $this->assertTrue($user->is_active);
    }

    public function test_user_granular_permission_check()
    {
        $roleCashier = Role::create([
            'name' => 'cashier',
            'display_name' => 'Billing Cashier',
        ]);

        $permInvoices = Permission::create([
            'name' => 'invoices.create',
            'display_name' => 'Create Invoices',
            'module' => 'invoices',
        ]);

        $roleCashier->permissions()->attach($permInvoices);

        $cashier = User::create([
            'name' => 'Nurul Cashier',
            'email' => 'nurul@clinic.my',
            'password' => bcrypt('secret123'),
            'status' => 'active',
            'role' => 'cashier',
        ]);

        $cashier->roles()->attach($roleCashier);

        $this->assertTrue($cashier->hasPermission('invoices.create'));
        $this->assertFalse($cashier->hasPermission('users.manage'));
        $this->assertTrue($cashier->isStaff());
        $this->assertFalse($cashier->isAdmin());
    }

    public function test_user_role_dynamic_accessor_backward_compatibility()
    {
        $roleAdmin = Role::create([
            'name' => 'admin',
            'display_name' => 'Clinic Administrator',
        ]);

        $user = User::create([
            'name' => 'Manager Admin',
            'email' => 'mgr@clinic.my',
            'password' => bcrypt('secret123'),
            'status' => 'active',
            'role' => 'admin',
        ]);

        // Prior to attaching roles, falls back to stored role
        $this->assertEquals('admin', $user->role);

        // After attaching role, returns display_name
        $user->roles()->attach($roleAdmin);
        $user->load('roles');
        $this->assertEquals('Clinic Administrator', $user->role);
    }
}
