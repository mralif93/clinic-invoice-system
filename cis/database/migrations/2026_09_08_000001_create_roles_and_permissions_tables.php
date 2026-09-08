<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Standardize RBAC & User Management identical to HRMS & Payroll.
     */
    public function up(): void
    {
        // 1. Expand users table with standard identity & security columns
        Schema::table('users', function (Blueprint $table) {
            $table->string('department')->nullable()->after('role');
            $table->string('designation')->nullable()->after('department');
            $table->string('employee_code')->nullable()->unique()->after('designation');
            $table->string('avatar')->nullable()->after('employee_code');
            $table->dateTime('last_login_at')->nullable()->after('remember_token');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
        });

        // 2. Roles Table
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique(); // e.g. super_admin, doctor, admin, cashier, receptionist
            $table->string('display_name', 100);
            $table->string('description')->nullable();
            $table->boolean('is_system')->default(false);
            $table->timestamps();
        });

        // 3. Permissions Table
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80)->unique(); // e.g. invoices.manage, reports.view
            $table->string('display_name', 120);
            $table->string('module', 50)->index(); // invoices, patients, items, reports, settings, users
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // 4. User Roles Pivot Table
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'role_id']);
        });

        // 5. Role Permissions Pivot Table
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['role_id', 'permission_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'department',
                'designation',
                'employee_code',
                'avatar',
                'last_login_at',
                'last_login_ip',
            ]);
        });
    }
};
