<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('expense_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Electricity & Power, Water Utility, AC & Equipment Maintenance, Laundry Services, Rent & Premises, Cleaning Supplies, Misc
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_category_id')->constrained('expense_categories')->cascadeOnDelete();
            $table->string('title'); // e.g. Monthly Electricity Bill (August 2026), Commercial Water Supply, AC Service & Gas Refill
            $table->decimal('amount', 10, 2);
            $table->date('expense_date');
            $table->date('due_date')->nullable();
            $table->date('paid_date')->nullable();
            $table->string('payment_method')->default('bank_transfer'); // cash, upi, bank_transfer, card
            $table->string('payment_reference_no')->nullable(); // Transaction ID or Cheque No
            $table->string('vendor_name')->nullable(); // Electricity Board, Water Agency, CoolAir Technicians, etc.
            $table->string('bill_invoice_number')->nullable();
            $table->string('receipt_image_path')->nullable(); // Uploaded bill/receipt scan
            $table->string('status')->default('paid'); // paid, pending, cancelled
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('expense_categories');
    }
};
