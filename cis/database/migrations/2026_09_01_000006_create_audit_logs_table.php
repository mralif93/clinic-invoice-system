<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * SRS Module 7: Audit Trail & Activity Logging
     */
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('user_name')->nullable();
            $table->string('action'); // INVOICE_CREATED, INVOICE_VOIDED, PAYMENT_RECEIVED, ITEM_PRICE_UPDATED, PATIENT_CREATED, SETTINGS_UPDATED
            $table->string('module')->default('Billing'); // Billing, MasterData, Governance, Auth
            $table->string('target_reference')->nullable()->comment('e.g. INV-20260901-0042');
            $table->text('description');
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
