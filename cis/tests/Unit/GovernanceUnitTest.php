<?php

namespace Tests\Unit;

use App\Models\AuditLog;
use App\Models\ClinicProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GovernanceUnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_clinic_profile_singleton_default_instantiation(): void
    {
        $profile = ClinicProfile::getActiveProfile();

        $this->assertEquals('Poliklinik & Surgeri Prima', $profile->clinic_name);
        $this->assertEquals('RM', $profile->currency_symbol);
        $this->assertNotEmpty($profile->phone);
        $this->assertNotEmpty($profile->receipt_footer);
    }

    public function test_audit_log_helper_records_actor_and_context(): void
    {
        $user = User::factory()->create([
            'name' => 'Dr. Aiman Hakim',
            'staff_id' => 'ADM-001',
        ]);
        $this->actingAs($user);

        $log = AuditLog::log(
            'INVOICE_VOIDED',
            'Billing',
            'INV-20260901-0001',
            'Invoice voided due to invalid prescription.',
            ['reason' => 'Doctor cancelled']
        );

        $this->assertDatabaseHas('audit_logs', [
            'id' => $log->id,
            'user_id' => $user->id,
            'user_name' => 'Dr. Aiman Hakim',
            'action' => 'INVOICE_VOIDED',
            'module' => 'Billing',
            'target_reference' => 'INV-20260901-0001',
        ]);
    }
}
