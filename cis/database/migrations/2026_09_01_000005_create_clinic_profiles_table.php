<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * SRS Module 6: Clinic Profile & Invoicing Defaults
     */
    public function up(): void
    {
        Schema::create('clinic_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('clinic_name')->default('Poliklinik & Surgeri Prima');
            $table->string('registration_number')->default('202601004921 (142019-K)')->comment('SSM or MOH license no.');
            $table->string('phone')->default('+603-8899 1234');
            $table->string('email')->default('admin@clinic.my');
            $table->text('address')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('currency_symbol')->default('RM');
            $table->decimal('default_tax_rate', 5, 2)->default(0.00);
            $table->text('invoice_terms')->nullable();
            $table->text('receipt_footer')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_profiles');
    }
};
