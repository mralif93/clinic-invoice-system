<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * SRS Module 2: Treatment & Medication Catalog (Item Master)
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('SKU / Item Code');
            $table->string('name');
            $table->string('category')->default('Medication'); // Consultation, Procedure, Lab, Medication
            $table->decimal('unit_price', 10, 2)->default(0.00);
            $table->decimal('tax_rate', 5, 2)->default(0.00)->comment('Tax percentage e.g. 0 or 6');
            $table->integer('stock_quantity')->default(100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
