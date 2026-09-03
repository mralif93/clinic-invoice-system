<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\ClinicProfile;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Item;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with 50+ rich clinical data records per module.
     */
    public function run(): void
    {
        // 0. Ensure Clinic Profile Singleton exists
        ClinicProfile::getActiveProfile();

        // 1. Seed Staff & Doctor Users
        $admin = User::updateOrCreate(
            ['email' => 'admin@clinic.my'],
            [
                'staff_id' => 'ADM-001',
                'name' => 'Dr. Aiman Hakim',
                'phone' => '+60123456789',
                'role' => 'admin',
                'status' => 'active',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $cashier = User::updateOrCreate(
            ['email' => 'cashier@clinic.my'],
            [
                'staff_id' => 'STF-001',
                'name' => 'Nurul Ain',
                'phone' => '+60198765432',
                'role' => 'staff',
                'status' => 'active',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Seed Master Items Catalog (50 Clinical Catalog Records)
        $catalogTemplates = [
            // Consultations
            ['name' => 'Standard General Consultation', 'category' => 'Consultation', 'price' => 45.00, 'stock' => 999],
            ['name' => 'Extended Specialist Consultation', 'category' => 'Consultation', 'price' => 95.00, 'stock' => 999],
            ['name' => 'Pediatric Health Assessment', 'category' => 'Consultation', 'price' => 65.00, 'stock' => 999],
            ['name' => 'Geriatric Comprehensive Review', 'category' => 'Consultation', 'price' => 85.00, 'stock' => 999],
            ['name' => 'Follow-up Chronic Care Review', 'category' => 'Consultation', 'price' => 35.00, 'stock' => 999],
            ['name' => 'Pre-Employment Medical Checkup', 'category' => 'Consultation', 'price' => 120.00, 'stock' => 999],
            ['name' => 'Antenatal Routine Consultation', 'category' => 'Consultation', 'price' => 75.00, 'stock' => 999],
            ['name' => 'Travel Health & Vaccination Advice', 'category' => 'Consultation', 'price' => 50.00, 'stock' => 999],
            ['name' => 'Emergency Out-of-Hours Triage', 'category' => 'Consultation', 'price' => 110.00, 'stock' => 999],
            ['name' => 'Telehealth Virtual Consultation', 'category' => 'Consultation', 'price' => 40.00, 'stock' => 999],

            // Medications
            ['name' => 'Paracetamol 500mg (10 tabs)', 'category' => 'Medication', 'price' => 8.00, 'stock' => 350],
            ['name' => 'Amoxicillin 500mg (20 caps)', 'category' => 'Medication', 'price' => 25.00, 'stock' => 120],
            ['name' => 'Cetirizine 10mg (10 tabs)', 'category' => 'Medication', 'price' => 12.50, 'stock' => 180],
            ['name' => 'Ibuprofen 400mg (10 tabs)', 'category' => 'Medication', 'price' => 14.00, 'stock' => 45], // Low stock
            ['name' => 'Augmentin 625mg (14 tabs)', 'category' => 'Medication', 'price' => 48.00, 'stock' => 60],
            ['name' => 'Metformin 500mg (30 tabs)', 'category' => 'Medication', 'price' => 15.00, 'stock' => 210],
            ['name' => 'Amlodipine 5mg (30 tabs)', 'category' => 'Medication', 'price' => 18.00, 'stock' => 140],
            ['name' => 'Perindopril 4mg (30 tabs)', 'category' => 'Medication', 'price' => 32.00, 'stock' => 85],
            ['name' => 'Omeprazole 20mg (14 caps)', 'category' => 'Medication', 'price' => 22.00, 'stock' => 30], // Low stock
            ['name' => 'Loratadine 10mg (10 tabs)', 'category' => 'Medication', 'price' => 10.00, 'stock' => 190],
            ['name' => 'Salbutamol Inhaler 100mcg', 'category' => 'Medication', 'price' => 28.00, 'stock' => 25], // Low stock
            ['name' => 'Azithromycin 250mg (6 tabs)', 'category' => 'Medication', 'price' => 38.00, 'stock' => 40], // Low stock
            ['name' => 'Glibenclamide 5mg (30 tabs)', 'category' => 'Medication', 'price' => 12.00, 'stock' => 110],
            ['name' => 'Simvastatin 20mg (30 tabs)', 'category' => 'Medication', 'price' => 24.00, 'stock' => 95],
            ['name' => 'Atorvastatin 20mg (30 tabs)', 'category' => 'Medication', 'price' => 42.00, 'stock' => 70],
            ['name' => 'Mefenamic Acid 500mg (10 caps)', 'category' => 'Medication', 'price' => 11.00, 'stock' => 160],
            ['name' => 'Chlorpheniramine 4mg (10 tabs)', 'category' => 'Medication', 'price' => 5.00, 'stock' => 300],
            ['name' => 'Diphenhydramine Cough Syrup 100ml', 'category' => 'Medication', 'price' => 16.50, 'stock' => 80],
            ['name' => 'Hydrocortisone 1% Cream 15g', 'category' => 'Medication', 'price' => 14.50, 'stock' => 55],
            ['name' => 'Oral Rehydration Salts (5 sachets)', 'category' => 'Medication', 'price' => 7.50, 'stock' => 220],

            // Procedures
            ['name' => 'Wound Dressing & Suturing', 'category' => 'Procedure', 'price' => 65.00, 'stock' => 999],
            ['name' => 'Nebulizer Therapy Session', 'category' => 'Procedure', 'price' => 35.00, 'stock' => 999],
            ['name' => 'Ear Syringing (Bilateral)', 'category' => 'Procedure', 'price' => 55.00, 'stock' => 999],
            ['name' => 'Foreign Body Removal (Skin/Ear)', 'category' => 'Procedure', 'price' => 80.00, 'stock' => 999],
            ['name' => 'Intramuscular (IM) Injection Fee', 'category' => 'Procedure', 'price' => 20.00, 'stock' => 999],
            ['name' => 'Intravenous (IV) Cannulation & Drip', 'category' => 'Procedure', 'price' => 75.00, 'stock' => 999],
            ['name' => 'Incision and Drainage (Abscess)', 'category' => 'Procedure', 'price' => 150.00, 'stock' => 999],
            ['name' => 'Cryotherapy Lesion Removal', 'category' => 'Procedure', 'price' => 90.00, 'stock' => 999],
            ['name' => 'Electrocardiogram (ECG) 12-Lead', 'category' => 'Procedure', 'price' => 60.00, 'stock' => 999],
            ['name' => 'Splinting & Fracture Immobilization', 'category' => 'Procedure', 'price' => 110.00, 'stock' => 999],

            // Lab Tests & Diagnostics
            ['name' => 'Full Blood Count (FBC) Profile', 'category' => 'Lab', 'price' => 55.00, 'stock' => 999],
            ['name' => 'Rapid Blood Glucose Screening', 'category' => 'Lab', 'price' => 15.00, 'stock' => 999],
            ['name' => 'Lipid Profile Assessment', 'category' => 'Lab', 'price' => 45.00, 'stock' => 999],
            ['name' => 'Renal Function Test (RFT)', 'category' => 'Lab', 'price' => 50.00, 'stock' => 999],
            ['name' => 'Liver Function Test (LFT)', 'category' => 'Lab', 'price' => 50.00, 'stock' => 999],
            ['name' => 'HbA1c Glycated Hemoglobin Test', 'category' => 'Lab', 'price' => 65.00, 'stock' => 999],
            ['name' => 'Urine Microalbumin & FEME', 'category' => 'Lab', 'price' => 30.00, 'stock' => 999],
            ['name' => 'Dengue NS1 Antigen Rapid Test', 'category' => 'Lab', 'price' => 60.00, 'stock' => 999],
            ['name' => 'Covid-19 RTK Antigen Nasal Swab', 'category' => 'Lab', 'price' => 35.00, 'stock' => 999],
            ['name' => 'Thyroid Stimulating Hormone (TSH)', 'category' => 'Lab', 'price' => 70.00, 'stock' => 999],
        ];

        $createdItems = [];
        foreach ($catalogTemplates as $index => $tmpl) {
            $prefix = match ($tmpl['category']) {
                'Consultation' => 'CON',
                'Medication' => 'MED',
                'Procedure' => 'PRC',
                'Lab' => 'LAB',
                default => 'GEN',
            };
            $code = $prefix . '-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);

            $createdItems[] = Item::updateOrCreate(
                ['code' => $code],
                [
                    'name' => $tmpl['name'],
                    'category' => $tmpl['category'],
                    'unit_price' => $tmpl['price'],
                    'tax_rate' => 0.00,
                    'stock_quantity' => $tmpl['stock'],
                ]
            );
        }

        // 3. Seed Patients Master (50 Patient Records)
        $malaysianFirstNames = [
            'Muhammad', 'Nurul', 'Ahmad', 'Siti', 'Chong', 'Tan', 'Lim', 'Lee', 'Kavitha', 'Suresh',
            'Farah', 'Daniel', 'Aina', 'Bryan', 'Mei Ling', 'Hafiz', 'Priya', 'Karthik', 'Zulhilmi', 'Aisyah',
            'Jason', 'Rachel', 'Devi', 'Azman', 'Jonathan', 'Michelle', 'Fauzi', 'Wong', 'Syed', 'Wan',
            'Nadia', 'Hakim', 'Stephanie', 'Ravi', 'Anand', 'Noraini', 'Kenneth', 'Hannah', 'Amirul', 'Zulaikha',
            'Rajesh', 'Kelly', 'Iskandar', 'Jessica', 'Muthu', 'Hidayah', 'Brandon', 'Yuki', 'Firdaus', 'Shalini',
        ];

        $malaysianLastNames = [
            'Ismail', 'Abdullah', 'Tan', 'Wei Lun', 'Subramaniam', 'Hashim', 'Kaur', 'Pillay', 'Othman', 'Rahman',
            'Khoo', 'Teoh', 'Bakar', 'Mansor', 'Gopal', 'Nadarajan', 'Chua', 'Goh', 'Mustafa', 'Nasir',
            'Cheah', 'Ng', 'Arumugam', 'Shanmugam', 'Yusof', 'Zakaria', 'Lim', 'Yap', 'Kamal', 'Baharuddin',
        ];

        $allergiesPool = [
            'None', 'None', 'None', 'Penicillin', 'None', 'Aspirin', 'Sulfa drugs', 'None',
            'NSAIDs', 'None', 'Amoxicillin, Cephalexin', 'None', 'Ciprofloxacin', 'None', 'Erythromycin',
        ];

        $createdPatients = [];
        for ($i = 1; $i <= 50; $i++) {
            $firstName = $malaysianFirstNames[($i - 1) % count($malaysianFirstNames)];
            $lastName = $malaysianLastNames[($i * 3) % count($malaysianLastNames)];
            $fullName = "{$firstName} {$lastName}";

            $year = rand(60, 99);
            $month = str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT);
            $day = str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT);
            $stateCode = str_pad(rand(1, 14), 2, '0', STR_PAD_LEFT);
            $lastFour = str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
            $icNumber = "{$year}{$month}{$day}-{$stateCode}-{$lastFour}";

            $gender = ($i % 2 === 0) ? 'Female' : 'Male';
            $allergy = $allergiesPool[$i % count($allergiesPool)];

            $createdPatients[] = Patient::updateOrCreate(
                ['id_number' => $icNumber],
                [
                    'name' => $fullName,
                    'phone' => '+601' . rand(1, 9) . '-' . rand(100, 999) . ' ' . rand(1000, 9999),
                    'date_of_birth' => (1900 + $year) . "-{$month}-{$day}",
                    'gender' => $gender,
                    'allergies' => $allergy,
                    'address' => "No. " . rand(1, 120) . ", Jalan Indah " . rand(1, 15) . ", Petaling Jaya, Selangor",
                ]
            );
        }

        // 4. Seed Invoices & Line Items (50 Invoices)
        $statuses = ['paid', 'paid', 'paid', 'paid', 'partial', 'unpaid', 'void'];
        $doctors = ['Dr. Aiman Hakim', 'Dr. Siti Nurhaliza', 'Dr. Jason Lee', 'Dr. Kavitha Raman'];
        $paymentMethods = ['cash', 'card', 'qr', 'transfer', 'panel'];
        $banks = ['Maybank DuitNow', 'CIMB Bank', 'Public Bank', 'Hong Leong EDC', 'RHB Terminal'];

        $createdInvoices = [];
        $createdPayments = [];

        for ($invIdx = 1; $invIdx <= 50; $invIdx++) {
            $patient = $createdPatients[($invIdx - 1) % count($createdPatients)];
            $status = $statuses[($invIdx - 1) % count($statuses)];
            $doctor = $doctors[($invIdx - 1) % count($doctors)];

            // Create 1 to 3 items per invoice
            $numItems = rand(1, 3);
            $subtotal = 0;
            $itemsForInvoice = [];

            for ($k = 0; $k < $numItems; $k++) {
                $itemModel = $createdItems[rand(0, count($createdItems) - 1)];
                $qty = rand(1, 2);
                $lineSubtotal = $itemModel->unit_price * $qty;
                $subtotal += $lineSubtotal;

                $itemsForInvoice[] = [
                    'item_id' => $itemModel->id,
                    'item_code' => $itemModel->code,
                    'item_name' => $itemModel->name,
                    'quantity' => $qty,
                    'unit_price' => $itemModel->unit_price,
                    'discount' => 0.00,
                    'tax' => 0.00,
                    'subtotal' => $lineSubtotal,
                ];
            }

            $discountAmount = ($invIdx % 5 === 0) ? round($subtotal * 0.10, 2) : 0.00;
            $totalAmount = max(0, $subtotal - $discountAmount);

            $paidAmount = match ($status) {
                'paid' => $totalAmount,
                'partial' => round($totalAmount * 0.50, 2),
                'unpaid', 'void' => 0.00,
            };

            // Days offset within the last 30 days
            $createdDate = now()->subDays(rand(0, 28))->subHours(rand(1, 10));

            $invoiceNumber = 'INV-' . $createdDate->format('Ymd') . '-' . str_pad($invIdx + 100, 4, '0', STR_PAD_LEFT);

            $invoice = Invoice::updateOrCreate(
                ['invoice_number' => $invoiceNumber],
                [
                    'patient_id' => $patient->id,
                    'user_id' => ($invIdx % 2 === 0) ? $admin->id : $cashier->id,
                    'doctor_name' => $doctor,
                    'subtotal' => $subtotal,
                    'discount_amount' => $discountAmount,
                    'tax_amount' => 0.00,
                    'total_amount' => $totalAmount,
                    'paid_amount' => $paidAmount,
                    'status' => $status,
                    'notes' => $status === 'void' ? 'Patient cancelled registration and left without consultation.' : null,
                    'void_reason' => $status === 'void' ? 'Patient cancelled registration and left without consultation.' : null,
                    'created_at' => $createdDate,
                    'updated_at' => $createdDate,
                ]
            );

            $createdInvoices[] = $invoice;

            // Seed invoice line items
            foreach ($itemsForInvoice as $lineItem) {
                InvoiceItem::updateOrCreate(
                    ['invoice_id' => $invoice->id, 'item_name' => $lineItem['item_name']],
                    $lineItem
                );
            }

            // If invoice has paid amount, create Payment record
            if ($paidAmount > 0) {
                $method = $paymentMethods[rand(0, count($paymentMethods) - 1)];
                $bank = ($method !== 'cash') ? $banks[rand(0, count($banks) - 1)] : null;
                $isReconciled = ($method === 'cash') ? true : ($invIdx % 2 === 0);

                $payment = Payment::create([
                    'invoice_id' => $invoice->id,
                    'user_id' => $cashier->id,
                    'amount' => $paidAmount,
                    'payment_method' => $method,
                    'bank_name' => $bank,
                    'transaction_ref' => ($method !== 'cash') ? 'TXN-' . strtoupper(bin2hex(random_bytes(4))) : null,
                    'batch_number' => ($method === 'card') ? 'BATCH-' . str_pad(rand(1, 20), 3, '0', STR_PAD_LEFT) : null,
                    'payment_date' => $createdDate,
                    'is_reconciled' => $isReconciled,
                    'remarks' => 'Settled at front-desk cashier counter.',
                    'created_at' => $createdDate,
                    'updated_at' => $createdDate,
                ]);

                $createdPayments[] = $payment;
            }
        }

        // 5. Ensure at least 50 Electronic Payments for Bank Reconciliation
        $electronicCount = Payment::where('payment_method', '!=', 'cash')->count();
        if ($electronicCount < 50) {
            $needed = 50 - $electronicCount;
            for ($ep = 1; $ep <= $needed; $ep++) {
                $inv = $createdInvoices[$ep % count($createdInvoices)];
                $method = ['card', 'qr', 'transfer', 'panel'][rand(0, 3)];
                $pDate = now()->subDays(rand(1, 25));

                Payment::create([
                    'invoice_id' => $inv->id,
                    'user_id' => $cashier->id,
                    'amount' => rand(30, 180) + 0.50,
                    'payment_method' => $method,
                    'bank_name' => $banks[rand(0, count($banks) - 1)],
                    'transaction_ref' => 'RRN-' . rand(10000000, 99999999),
                    'batch_number' => ($method === 'card') ? 'BATCH-' . str_pad(rand(1, 15), 3, '0', STR_PAD_LEFT) : null,
                    'payment_date' => $pDate,
                    'is_reconciled' => ($ep % 2 === 0),
                    'remarks' => 'Supplementary electronic terminal settlement record.',
                    'created_at' => $pDate,
                    'updated_at' => $pDate,
                ]);
            }
        }

        // 6. Seed Immutable Audit Logs (50 Records)
        $auditActions = [
            ['action' => 'INVOICE_CREATED', 'module' => 'Billing', 'desc' => 'Generated new medical POS invoice with consultation charges'],
            ['action' => 'PAYMENT_RECEIVED', 'module' => 'Billing', 'desc' => 'Tendered and settled payment transaction via cashier terminal'],
            ['action' => 'INVOICE_VOIDED', 'module' => 'Billing', 'desc' => 'Voided patient invoice due to duplicate queue registration'],
            ['action' => 'SETTINGS_UPDATED', 'module' => 'Governance', 'desc' => 'Updated clinic tax identification number and receipt template'],
            ['action' => 'PATIENT_REGISTERED', 'module' => 'MasterData', 'desc' => 'Registered new patient profile with penicillin allergy alerts'],
            ['action' => 'ITEM_UPDATED', 'module' => 'MasterData', 'desc' => 'Adjusted clinical consultation rate and restocked pharmaceutical inventory'],
            ['action' => 'BANK_RECONCILED', 'module' => 'Billing', 'desc' => 'Matched EDC credit card batch report against Maybank merchant account'],
        ];

        $currentAuditCount = AuditLog::count();
        if ($currentAuditCount < 50) {
            $auditsToCreate = 50 - $currentAuditCount;
            for ($a = 1; $a <= $auditsToCreate; $a++) {
                $template = $auditActions[$a % count($auditActions)];
                $logDate = now()->subDays(rand(0, 20))->subMinutes(rand(10, 500));

                AuditLog::create([
                    'user_id' => ($a % 3 === 0) ? $admin->id : $cashier->id,
                    'user_name' => ($a % 3 === 0) ? $admin->name : $cashier->name,
                    'action' => $template['action'],
                    'module' => $template['module'],
                    'target_reference' => 'REF-' . (1000 + $a),
                    'description' => $template['desc'] . ' [Record #' . $a . ']',
                    'ip_address' => '192.168.1.' . rand(10, 150),
                    'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
                    'payload' => ['record_id' => $a, 'status' => 'verified'],
                    'created_at' => $logDate,
                    'updated_at' => $logDate,
                ]);
            }
        }
    }
}
