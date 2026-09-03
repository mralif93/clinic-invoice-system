<?php

namespace Tests\Unit;

use App\Models\Invoice;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientUnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_model_has_fillable_attributes(): void
    {
        $patient = new Patient([
            'name' => 'John Doe',
            'id_number' => '800101-14-1234',
            'phone' => '+60123456789',
            'date_of_birth' => '1980-01-01',
            'gender' => 'Male',
            'allergies' => 'Penicillin',
            'address' => 'Kuala Lumpur',
        ]);

        $this->assertEquals('John Doe', $patient->name);
        $this->assertEquals('800101-14-1234', $patient->id_number);
        $this->assertEquals('+60123456789', $patient->phone);
        $this->assertEquals('Male', $patient->gender);
        $this->assertEquals('Penicillin', $patient->allergies);
    }

    public function test_patient_calculates_outstanding_balance_accurately(): void
    {
        $user = User::factory()->create();
        $patient = Patient::create([
            'name' => 'Sarah Doe',
            'id_number' => '900202-10-5555',
            'phone' => '+60199998888',
        ]);

        // Unpaid invoice of 150
        Invoice::create([
            'invoice_number' => 'INV-TEST-001',
            'patient_id' => $patient->id,
            'user_id' => $user->id,
            'doctor_name' => 'Dr. Aiman',
            'subtotal' => 150.00,
            'total_amount' => 150.00,
            'paid_amount' => 0.00,
            'status' => 'unpaid',
        ]);

        // Partially paid invoice: total 200, paid 50 (due: 150)
        Invoice::create([
            'invoice_number' => 'INV-TEST-002',
            'patient_id' => $patient->id,
            'user_id' => $user->id,
            'doctor_name' => 'Dr. Aiman',
            'subtotal' => 200.00,
            'total_amount' => 200.00,
            'paid_amount' => 50.00,
            'status' => 'partial',
        ]);

        // Fully paid invoice: total 100, paid 100 (due: 0)
        Invoice::create([
            'invoice_number' => 'INV-TEST-003',
            'patient_id' => $patient->id,
            'user_id' => $user->id,
            'doctor_name' => 'Dr. Aiman',
            'subtotal' => 100.00,
            'total_amount' => 100.00,
            'paid_amount' => 100.00,
            'status' => 'paid',
        ]);

        // Voided invoice: total 300 (should not count)
        Invoice::create([
            'invoice_number' => 'INV-TEST-004',
            'patient_id' => $patient->id,
            'user_id' => $user->id,
            'doctor_name' => 'Dr. Aiman',
            'subtotal' => 300.00,
            'total_amount' => 300.00,
            'paid_amount' => 0.00,
            'status' => 'void',
        ]);

        // Expected outstanding: 150 + 150 = 300.00
        $this->assertEquals(300.00, $patient->outstanding_balance);
    }
}
