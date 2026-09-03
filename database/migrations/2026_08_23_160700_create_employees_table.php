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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->foreignId('salary_grade_id')->nullable()->constrained('salary_grades')->nullOnDelete();
            $table->string('employee_code')->nullable()->unique();
            $table->string('gender')->default('female');
            $table->date('dob')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('profile_pic')->nullable();
            $table->string('role')->default('therapist'); // therapist, receptionist, cleaner, laundry, manager, other
            $table->string('employment_type')->default('regular'); // regular, temporary, guest
            $table->string('status')->default('active'); // active, inactive, terminated, resigned, on_leave
            $table->boolean('is_active')->default(true);
            $table->date('joining_date')->nullable();
            $table->date('exit_date')->nullable();
            $table->text('exit_reason')->nullable(); // e.g. Left for another job, terminated, personal reasons
            $table->decimal('base_salary', 10, 2)->default(0.00);
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_ifsc')->nullable();
            $table->string('upi_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
