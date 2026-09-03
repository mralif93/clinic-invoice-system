<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * SRS Module 4 & 5: Payment Processing, Drawer & Bank Reconciliation
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->comment('Cashier who received the settlement');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method')->default('cash'); // cash, card, qr, transfer, panel
            $table->string('bank_name')->nullable();
            $table->string('transaction_ref')->nullable()->comment('RRN or DuitNow Ref');
            $table->string('batch_number')->nullable()->comment('EDC Terminal Batch No');
            $table->date('payment_date');
            $table->boolean('is_reconciled')->default(false);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
