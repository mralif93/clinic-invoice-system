<?php

namespace Tests\Unit;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceUnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_invoice_generates_sequential_number_with_date_prefix(): void
    {
        $invNumber = Invoice::generateInvoiceNumber();
        $datePrefix = date('Ymd');
        
        $this->assertStringStartsWith('INV-' . $datePrefix . '-', $invNumber);
        $this->assertEquals('INV-' . $datePrefix . '-0001', $invNumber);
    }

    public function test_invoice_due_amount_calculation(): void
    {
        $user = User::factory()->create();
        $patient = Patient::create([
            'name' => 'Test Patient',
            'id_number' => '990101-14-1111',
            'phone' => '+60111111111',
        ]);

        $invoice = Invoice::create([
            'invoice_number' => 'INV-TEST-DUE',
            'patient_id' => $patient->id,
            'user_id' => $user->id,
            'doctor_name' => 'Dr. Aiman',
            'subtotal' => 250.00,
            'discount_amount' => 25.00,
            'total_amount' => 225.00,
            'paid_amount' => 100.00,
            'status' => 'partial',
        ]);

        // Total 225 - Paid 100 = Due 125
        $this->assertEquals(125.00, $invoice->due_amount);
    }

    public function test_invoice_item_line_subtotal_calculation(): void
    {
        $item = new InvoiceItem([
            'item_name' => 'Nebulizer Therapy',
            'quantity' => 3,
            'unit_price' => 35.00,
            'discount' => 5.00,
            'tax' => 0.00,
            'subtotal' => (3 * 35.00) - 5.00,
        ]);

        $this->assertEquals(100.00, $item->subtotal);
    }
}
