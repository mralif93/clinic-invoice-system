<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_name',
        'registration_number',
        'phone',
        'email',
        'address',
        'logo_path',
        'currency_symbol',
        'default_tax_rate',
        'invoice_terms',
        'receipt_footer',
    ];

    public static function getActiveProfile(): self
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'clinic_name' => 'Poliklinik & Surgeri Prima',
                'registration_number' => '202601004921 (142019-K)',
                'phone' => '+603-8899 1234',
                'email' => 'admin@clinic.my',
                'address' => 'No 12, Jalan Boulevard 3, Solaris Dutamas, 50480 Kuala Lumpur',
                'currency_symbol' => 'RM',
                'default_tax_rate' => 0.00,
                'invoice_terms' => 'Payment is due upon invoice issuance. Thank you for entrusting your medical care to Poliklinik Prima.',
                'receipt_footer' => 'Thank you for choosing Poliklinik Prima. Please keep this receipt for medical claims & tax relief purposes.',
            ]
        );
    }
}
