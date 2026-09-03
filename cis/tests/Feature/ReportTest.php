<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
    }

    public function test_daily_cash_drawer_page_renders(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('admin.reports.cash-drawer'));
        $response->assertStatus(200);
        $response->assertSee('Daily Cash Drawer &amp; Shift Reconciliation', false);
    }

    public function test_bank_reconciliation_page_renders_and_toggles_match(): void
    {
        $this->actingAs($this->user);

        $patient = Patient::create([
            'name' => 'Demo Patient',
            'id_number' => '900101-10-1010',
            'phone' => '+60120001111',
        ]);

        $invoice = Invoice::create([
            'invoice_number' => 'INV-20260901-9999',
            'patient_id' => $patient->id,
            'user_id' => $this->user->id,
            'doctor_name' => 'Dr. Aiman',
            'subtotal' => 80.00,
            'total_amount' => 80.00,
            'paid_amount' => 80.00,
            'status' => 'paid',
        ]);

        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'user_id' => $this->user->id,
            'amount' => 80.00,
            'payment_method' => 'card',
            'bank_name' => 'Maybank EDC',
            'transaction_ref' => 'CARD-123456',
            'payment_date' => now(),
            'is_reconciled' => false,
        ]);

        $response = $this->get(route('admin.reports.bank-recon'));
        $response->assertStatus(200);
        $response->assertSee('Bank Reconciliation &amp; Terminal Settlements', false);
        $response->assertSee('CARD-123456');

        // Toggle reconcile state
        $toggleResponse = $this->post(route('admin.reports.reconcile-toggle', $payment->id));
        $toggleResponse->assertSessionHas('success');

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'is_reconciled' => true,
        ]);
    }

    public function test_revenue_analytics_page_renders(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('admin.reports.analytics'));
        $response->assertStatus(200);
        $response->assertSee('Revenue Analytics &amp; Aging Debt', false);
        $response->assertSee('Patient Accounts Receivable Aging');
    }
}
