<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\Item;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Patient $patient;
    protected Item $item;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
        $this->patient = Patient::create([
            'name' => 'Sarah Jenkins',
            'id_number' => '920412-14-5542',
            'phone' => '+60123456789',
        ]);
        $this->item = Item::create([
            'code' => 'CON-01',
            'name' => 'Standard Consultation',
            'category' => 'Consultation',
            'unit_price' => 50.00,
            'tax_rate' => 0.00,
            'stock_quantity' => 100,
        ]);
    }

    public function test_invoices_index_page_accessible(): void
    {
        $this->actingAs($this->user);
        $response = $this->get(route('admin.invoices.index'));
        $response->assertStatus(200);
        $response->assertSee('Billing &amp; Invoices Directory', false);
    }

    public function test_invoice_create_pos_page_accessible(): void
    {
        $this->actingAs($this->user);
        $response = $this->get(route('admin.invoices.create'));
        $response->assertStatus(200);
        $response->assertSee('Front-Desk POS Invoice Generator');
    }

    public function test_can_create_invoice_with_line_items_and_payment(): void
    {
        $this->actingAs($this->user);

        $payload = [
            'patient_id' => $this->patient->id,
            'doctor_name' => 'Dr. Aiman Hakim',
            'discount_amount' => 5.00,
            'notes' => 'Patient standard checkup',
            'items' => [
                [
                    'item_id' => $this->item->id,
                    'item_name' => $this->item->name,
                    'item_code' => $this->item->code,
                    'quantity' => 2,
                    'unit_price' => 50.00,
                    'discount' => 0.00,
                    'tax' => 0.00,
                ],
            ],
            'payment_received' => 95.00,
            'payment_method' => 'cash',
        ];

        $response = $this->post(route('admin.invoices.store'), $payload);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('invoices', [
            'patient_id' => $this->patient->id,
            'total_amount' => 95.00,
            'paid_amount' => 95.00,
            'status' => 'paid',
        ]);

        $this->assertDatabaseHas('invoice_items', [
            'item_name' => 'Standard Consultation',
            'quantity' => 2,
            'subtotal' => 100.00,
        ]);

        $this->assertDatabaseHas('payments', [
            'amount' => 95.00,
            'payment_method' => 'cash',
        ]);
    }

    public function test_can_show_invoice_details_and_receipt_previews(): void
    {
        $this->actingAs($this->user);

        $invoice = Invoice::create([
            'invoice_number' => 'INV-20260901-0001',
            'patient_id' => $this->patient->id,
            'user_id' => $this->user->id,
            'doctor_name' => 'Dr. Aiman Hakim',
            'subtotal' => 100.00,
            'total_amount' => 100.00,
            'paid_amount' => 50.00,
            'status' => 'partial',
        ]);

        $response = $this->get(route('admin.invoices.show', $invoice->id));
        $response->assertStatus(200);
        $response->assertSee('INV-20260901-0001');
        $response->assertSee('80mm Thermal Receipt');
        $response->assertSee('A4 Formal Tax Invoice');
    }

    public function test_can_settle_remaining_payment_on_partial_invoice(): void
    {
        $this->actingAs($this->user);

        $invoice = Invoice::create([
            'invoice_number' => 'INV-20260901-0002',
            'patient_id' => $this->patient->id,
            'user_id' => $this->user->id,
            'doctor_name' => 'Dr. Aiman Hakim',
            'subtotal' => 100.00,
            'total_amount' => 100.00,
            'paid_amount' => 40.00,
            'status' => 'partial',
        ]);

        Payment::create([
            'invoice_id' => $invoice->id,
            'user_id' => $this->user->id,
            'amount' => 40.00,
            'payment_method' => 'cash',
            'payment_date' => now(),
            'is_reconciled' => true,
        ]);

        $response = $this->post(route('admin.invoices.settle', $invoice->id), [
            'amount' => 60.00,
            'payment_method' => 'qr',
            'bank_name' => 'Maybank DuitNow',
            'transaction_ref' => 'RRN-998877',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'paid_amount' => 100.00,
            'status' => 'paid',
        ]);
    }

    public function test_can_void_invoice_with_reason(): void
    {
        $this->actingAs($this->user);

        $invoice = Invoice::create([
            'invoice_number' => 'INV-20260901-0003',
            'patient_id' => $this->patient->id,
            'user_id' => $this->user->id,
            'doctor_name' => 'Dr. Aiman Hakim',
            'subtotal' => 50.00,
            'total_amount' => 50.00,
            'paid_amount' => 0.00,
            'status' => 'unpaid',
        ]);

        $response = $this->post(route('admin.invoices.void', $invoice->id), [
            'void_reason' => 'Patient consultation cancelled by doctor.',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('invoices', [
            'id' => $invoice->id,
            'status' => 'void',
            'void_reason' => 'Patient consultation cancelled by doctor.',
        ]);
    }
}
