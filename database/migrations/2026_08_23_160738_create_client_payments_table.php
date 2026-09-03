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
        Schema::create('client_payments', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // e.g. INV-2026-09-001
            $table->foreignId('therapist_service_log_id')->nullable()->constrained('therapist_service_logs')->nullOnDelete();
            $table->foreignId('therapist_id')->nullable()->constrained('therapists')->nullOnDelete();
            $table->foreignId('receptionist_id')->nullable()->constrained('receptionists')->nullOnDelete(); // Receptionist who handled payment
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->string('client_name')->default('Walk-in Guest');
            $table->string('client_phone')->nullable();
            
            // Amounts
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->decimal('tax_amount', 10, 2)->default(0.00);
            $table->decimal('total_amount', 10, 2);
            
            // Payment Mode
            $table->string('payment_method')->default('cash'); // cash, upi, card, bank_transfer, split
            
            // Payment Proof Details
            $table->string('upi_transaction_id')->nullable(); // For UPI proof
            $table->string('upi_app')->nullable(); // GPay, PhonePe, Paytm, etc.
            
            $table->string('card_transaction_id')->nullable(); // POS terminal reference
            $table->string('card_last_four', 4)->nullable();
            $table->string('card_network')->nullable(); // Visa, Mastercard, RuPay
            
            $table->string('cash_receipt_number')->nullable(); // For Cash receipt
            $table->json('cash_denomination_details')->nullable(); // e.g. {"500": 2, "200": 1, "100": 1}
            
            $table->string('receipt_image_path')->nullable(); // Uploaded slip / screenshot proof
            
            $table->timestamp('payment_date')->useCurrent();
            $table->string('payment_status')->default('completed'); // completed, pending, refunded
            $table->text('notes')->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete(); // Auth user logged in
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_payments');
    }
};
