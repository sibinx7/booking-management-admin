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
        Schema::create('inventory_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. Massage Oils & Aromas, Linens & Towels, Bathroom & Toiletries, Hot Stones & Equipment, Refreshments
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('inventory_categories')->cascadeOnDelete();
            $table->string('name'); // e.g. Cold-Pressed Sesame Herbal Oil, Jasmine Essential Extract, Luxury Bath Towels, Herbal Scrub Powder
            $table->string('sku')->nullable()->unique();
            $table->string('unit')->default('bottles'); // ml, liters, pieces, bottles, packs, boxes
            $table->decimal('current_stock', 10, 2)->default(0.00);
            $table->decimal('reorder_threshold', 10, 2)->default(5.00);
            $table->decimal('unit_cost', 10, 2)->default(0.00);
            $table->string('supplier_name')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('inventory_stock_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('inventory_items')->cascadeOnDelete();
            $table->string('transaction_type'); // purchase_in, treatment_usage_out, wastage_out, laundry_out, laundry_return_in, adjustment
            $table->decimal('quantity', 10, 2);
            $table->decimal('unit_cost', 10, 2)->default(0.00);
            $table->decimal('total_cost', 10, 2)->default(0.00);
            $table->date('transaction_date');
            $table->string('reference_invoice')->nullable(); // Supplier bill/receipt no
            $table->text('remarks')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_stock_logs');
        Schema::dropIfExists('inventory_items');
        Schema::dropIfExists('inventory_categories');
    }
};
