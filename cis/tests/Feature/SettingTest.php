<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\ClinicProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
    }

    public function test_clinic_profile_page_renders(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('admin.settings.profile'));
        $response->assertStatus(200);
        $response->assertSee('Clinic Profile &amp; Governance Settings', false);
    }

    public function test_can_update_clinic_profile_and_logs_audit(): void
    {
        $this->actingAs($this->user);

        $payload = [
            'clinic_name' => 'Klinik Pakar Kesihatan Prima',
            'registration_number' => '202699887711 (MOH-998)',
            'phone' => '+603-9988 7766',
            'email' => 'contact@klinikpakar.my',
            'address' => 'No 88, Jalan Medika 1, KL',
            'currency_symbol' => 'RM',
            'default_tax_rate' => 0.00,
            'invoice_terms' => 'Updated terms and policies.',
            'receipt_footer' => 'Updated receipt footer.',
        ];

        $response = $this->put(route('admin.settings.profile.update'), $payload);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('clinic_profiles', [
            'clinic_name' => 'Klinik Pakar Kesihatan Prima',
            'registration_number' => '202699887711 (MOH-998)',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'SETTINGS_UPDATED',
            'module' => 'Governance',
        ]);
    }

    public function test_invoice_templates_page_renders(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('admin.settings.templates'));
        $response->assertStatus(200);
        $response->assertSee('Invoice &amp; Thermal Receipt Layout Templates', false);
    }

    public function test_audit_logs_page_renders(): void
    {
        $this->actingAs($this->user);

        AuditLog::log('TEST_ACTION', 'Billing', 'INV-001', 'Test event log');

        $response = $this->get(route('admin.settings.audit-logs'));
        $response->assertStatus(200);
        $response->assertSee('Immutable Audit Trail &amp; Activity Log', false);
        $response->assertSee('TEST_ACTION');
    }
}
