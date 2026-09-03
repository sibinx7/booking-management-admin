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
        Schema::create('salary_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('payment_type_id')->constrained('payment_types')->restrictOnDelete();
            $table->string('payslip_number')->nullable()->unique(); // e.g. PAY-2026-08-001
            $table->unsignedTinyInteger('month'); // 1 to 12
            $table->unsignedSmallInteger('year'); // e.g. 2026
            $table->date('period_start_date'); // e.g. 2026-08-01
            $table->date('period_end_date'); // e.g. 2026-08-31
            
            // Attendance Metrics
            $table->unsignedSmallInteger('total_working_days')->default(30);
            $table->unsignedSmallInteger('present_days')->default(30);
            $table->unsignedSmallInteger('absent_days')->default(0);
            $table->unsignedSmallInteger('leave_days')->default(0);
            
            // Base Salary & Attendance Breakdown
            $table->decimal('base_salary_amount', 10, 2)->default(0.00);
            $table->decimal('attendance_adjusted_base', 10, 2)->default(0.00);
            
            // Therapist Performance Commission
            $table->unsignedInteger('services_completed_count')->default(0);
            $table->decimal('service_commission_amount', 10, 2)->default(0.00);
            
            // Adjustments
            $table->decimal('bonus_amount', 10, 2)->default(0.00);
            $table->decimal('deduction_amount', 10, 2)->default(0.00);
            
            // Total Net Salary
            $table->decimal('amount', 10, 2); // Net payable payout
            
            $table->date('payment_date');
            $table->date('deposited_date')->nullable();
            $table->string('status')->default('deposited'); // pending, deposited, failed, cancelled
            $table->string('reference_number')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_payments');
    }
};
