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
        // 1. Master Employee Daily Attendance (General Staff & Therapists)
        Schema::create('employee_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('therapist_id')->nullable()->constrained('therapists')->cascadeOnDelete();
            $table->date('date');
            $table->string('status')->default('present'); // present, absent, half_day, on_leave, weekly_off, holiday
            $table->string('shift_type')->default('general_full_day'); // general_full_day, morning_shift, evening_shift, half_day_morning, half_day_evening, custom
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->decimal('work_hours', 4, 2)->default(0.00);
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['employee_id', 'date']);
        });

        // 2. Dedicated Therapist Duty Shift & Allocation Attendance (Room & Time Slot Allocation)
        Schema::create('therapist_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('therapist_id')->constrained('therapists')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('employee_attendance_id')->nullable()->constrained('employee_attendances')->cascadeOnDelete();
            $table->foreignId('room_id')->nullable()->constrained('rooms')->nullOnDelete(); // Normalized Foreign Key to Room
            $table->date('date');
            $table->string('shift_type')->default('full_day'); // morning_shift, evening_shift, full_day, custom
            $table->time('duty_start_time')->default('09:00:00');
            $table->time('duty_end_time')->default('18:00:00');
            $table->string('status')->default('on_duty'); // scheduled, on_duty, completed, on_break, cancelled
            $table->unsignedSmallInteger('max_sessions_allowed')->default(6);
            $table->text('remarks')->nullable();
            $table->foreignId('allocated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['therapist_id', 'date', 'shift_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('therapist_attendances');
        Schema::dropIfExists('employee_attendances');
    }
};
