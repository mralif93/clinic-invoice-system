<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $staff;
    protected Role $adminRole;
    protected Role $cashierRole;

    protected function setUp(): void
    {
        parent::setUp();

        // Create standard roles
        $this->adminRole = Role::create([
            'name' => 'admin',
            'display_name' => 'Clinic Administrator',
            'description' => 'Full administrative access',
            'is_system' => true,
        ]);

        $this->cashierRole = Role::create([
            'name' => 'cashier',
            'display_name' => 'Billing Cashier',
            'description' => 'Handles POS & payments',
            'is_system' => true,
        ]);

        $perm = Permission::create([
            'name' => 'manage_users',
            'display_name' => 'Manage Users',
            'module' => 'Administration',
        ]);
        $this->adminRole->permissions()->attach($perm);

        // Create admin user
        $this->admin = User::create([
            'name' => 'Dr. Admin User',
            'email' => 'admin@clinic.my',
            'staff_id' => 'ADM-001',
            'role' => 'admin',
            'is_active' => true,
            'password' => Hash::make('password'),
        ]);
        $this->admin->roles()->attach($this->adminRole);

        // Create staff user
        $this->staff = User::create([
            'name' => 'Siti Cashier',
            'email' => 'cashier@clinic.my',
            'staff_id' => 'CSH-001',
            'role' => 'cashier',
            'is_active' => true,
            'password' => Hash::make('password'),
        ]);
        $this->staff->roles()->attach($this->cashierRole);
    }

    public function test_admin_can_view_users_index()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users.index'));
        $response->assertStatus(200);
        $response->assertSee('Staff &amp; Identity Management', false);
        $response->assertSee('Dr. Admin User');
        $response->assertSee('Siti Cashier');
    }

    public function test_cashier_cannot_access_user_management()
    {
        $response = $this->actingAs($this->staff)->get(route('admin.users.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_create_new_staff_user_with_roles()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.users.store'), [
            'name' => 'Dr. Lisa Wong',
            'email' => 'lisa@clinic.my',
            'staff_id' => 'DOC-005',
            'department' => 'Pediatrics',
            'designation' => 'Pediatrician',
            'password' => 'SecurePass123!',
            'password_confirmation' => 'SecurePass123!',
            'role_ids' => [$this->adminRole->id],
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'lisa@clinic.my',
            'staff_id' => 'DOC-005',
            'designation' => 'Pediatrician',
            'status' => 'active',
        ]);

        $newUser = User::where('email', 'lisa@clinic.my')->first();
        $this->assertTrue($newUser->hasRole('admin'));
        $this->assertTrue($newUser->isAdmin());
    }

    public function test_admin_can_update_user_details()
    {
        $response = $this->actingAs($this->admin)->put(route('admin.users.update', $this->staff->id), [
            'name' => 'Siti Nurhaliza',
            'email' => 'siti.new@clinic.my',
            'staff_id' => 'CSH-002',
            'department' => 'Billing HQ',
            'designation' => 'Senior Cashier',
            'role_ids' => [$this->cashierRole->id],
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->staff->refresh();
        $this->assertEquals('Siti Nurhaliza', $this->staff->name);
        $this->assertEquals('siti.new@clinic.my', $this->staff->email);
        $this->assertEquals('CSH-002', $this->staff->staff_id);
    }

    public function test_admin_can_toggle_user_status()
    {
        $this->assertTrue($this->staff->is_active);

        $response = $this->actingAs($this->admin)->post(route('admin.users.toggle-status', $this->staff->id));
        $response->assertRedirect(route('admin.users.index'));

        $this->staff->refresh();
        $this->assertFalse($this->staff->is_active);
    }

    public function test_admin_cannot_deactivate_self()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.users.toggle-status', $this->admin->id));
        $response->assertSessionHas('error');

        $this->admin->refresh();
        $this->assertTrue($this->admin->is_active);
    }

    public function test_admin_cannot_delete_self()
    {
        $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $this->admin->id));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    public function test_admin_can_delete_staff_user()
    {
        $response = $this->actingAs($this->admin)->delete(route('admin.users.destroy', $this->staff->id));
        $response->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseMissing('users', ['id' => $this->staff->id]);
    }

    public function test_admin_can_reset_staff_password()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.users.reset-password', $this->staff->id), [
            'password' => 'NewPassword2026!',
            'password_confirmation' => 'NewPassword2026!',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->staff->refresh();
        $this->assertTrue(Hash::check('NewPassword2026!', $this->staff->password));
    }

    public function test_admin_can_view_and_manage_roles()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.roles.index'));
        $response->assertStatus(200);
        $response->assertSee('Role-Based Access Control');
        $response->assertSee('Clinic Administrator');

        // Create new role
        $createResponse = $this->actingAs($this->admin)->post(route('admin.roles.store'), [
            'name' => 'nurse_lead',
            'display_name' => 'Nurse Supervisor',
            'description' => 'Oversees clinical triage',
        ]);

        $createResponse->assertRedirect(route('admin.roles.index'));
        $this->assertDatabaseHas('roles', [
            'name' => 'nurse_lead',
            'display_name' => 'Nurse Supervisor',
            'is_system' => false,
        ]);
    }

    public function test_system_role_cannot_be_deleted()
    {
        $response = $this->actingAs($this->admin)->delete(route('admin.roles.destroy', $this->adminRole->id));
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('roles', ['id' => $this->adminRole->id]);
    }

    public function test_admin_can_access_user_create_page()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users.create'));
        $response->assertStatus(200);
        $response->assertSee('Register Staff Account');
        $response->assertSee('Full Legal / Practitioner Name');
        $response->assertSee('Official Work Email');
    }

    public function test_admin_can_access_user_edit_page()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users.edit', $this->staff->id));
        $response->assertStatus(200);
        $response->assertSee('Edit Staff Personnel');
        $response->assertSee($this->staff->email);
    }

    public function test_admin_can_access_user_show_profile_page()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.users.show', $this->staff->id));
        $response->assertStatus(200);
        $response->assertSee('Staff Profile');
        $response->assertSee($this->staff->name);
        $response->assertSee('Personnel Identity');
        $response->assertSee('Invoices Authored by Staff');
    }

    public function test_user_model_role_and_permission_unit_methods()
    {
        $this->assertTrue($this->admin->isAdmin());
        $this->assertFalse($this->admin->isStaff());
        $this->assertTrue($this->admin->hasPermission('manage_users'));
        $this->assertFalse($this->admin->hasPermission('non_existent_perm'));

        $this->assertFalse($this->staff->isAdmin());
        $this->assertTrue($this->staff->isStaff());
        $this->assertTrue($this->staff->isActive());
    }
}
