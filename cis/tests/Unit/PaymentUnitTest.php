<?php

namespace Tests\Unit;

use App\Models\Payment;
use Tests\TestCase;

class PaymentUnitTest extends TestCase
{
    public function test_payment_model_casts_and_attributes(): void
    {
        $payment = new Payment([
            'invoice_id' => 1,
            'user_id' => 1,
            'amount' => 150.75,
            'payment_method' => 'card',
            'bank_name' => 'Maybank EDC',
            'transaction_ref' => 'RRN-123456',
            'batch_number' => 'BATCH-001',
            'payment_date' => '2026-09-01',
            'is_reconciled' => true,
        ]);

        $this->assertEquals(150.75, $payment->amount);
        $this->assertEquals('card', $payment->payment_method);
        $this->assertEquals('Maybank EDC', $payment->bank_name);
        $this->assertEquals('RRN-123456', $payment->transaction_ref);
        $this->assertTrue($payment->is_reconciled);
    }
}
