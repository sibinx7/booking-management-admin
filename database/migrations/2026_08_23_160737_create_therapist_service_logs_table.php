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
        Schema::create('therapist_service_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('therapist_attendance_id')->nullable()->constrained('therapist_attendances')->nullOnDelete();
            $table->foreignId('employee_attendance_id')->nullable()->constrained('employee_attendances')->nullOnDelete();
            $table->foreignId('room_id')->nullable()->constrained('rooms')->nullOnDelete(); // Normalized Foreign Key to Room
            $table->foreignId('therapist_id')->constrained('therapists')->cascadeOnDelete(); // Primary Therapist
            
            // Dual / Couple Massage Support
            $table->boolean('is_dual_massage')->default(false); // True for Couple / Four-Hand Dual Massage
            $table->foreignId('secondary_therapist_id')->nullable()->constrained('therapists')->nullOnDelete(); // 2nd Therapist
            
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->foreignId('service_duration_id')->nullable()->constrained('service_durations')->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->string('client_name')->default('Walk-in Guest');
            $table->string('client_phone')->nullable();
            $table->date('service_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            
            // Financials & Commissions for Primary & Secondary Therapists
            $table->decimal('service_price', 10, 2);
            $table->decimal('commission_rate', 5, 2)->default(0.00); // percentage
            $table->decimal('commission_amount', 10, 2)->default(0.00); // Primary therapist commission
            $table->decimal('secondary_commission_amount', 10, 2)->default(0.00); // Secondary therapist commission
            $table->decimal('tip_amount', 10, 2)->default(0.00); // Primary therapist tip
            $table->decimal('secondary_tip_amount', 10, 2)->default(0.00); // Secondary therapist tip
            
            $table->string('status')->default('completed'); // scheduled, in_progress, completed, cancelled, no_show
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
        Schema::dropIfExists('therapist_service_logs');
    }
};
