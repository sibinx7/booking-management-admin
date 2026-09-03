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
        Schema::create('salary_increments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('salary_grade_id')->nullable()->constrained('salary_grades')->nullOnDelete();
            $table->decimal('previous_salary', 10, 2);
            $table->decimal('increment_amount', 10, 2);
            $table->decimal('new_salary', 10, 2);
            $table->decimal('increment_percentage', 5, 2)->nullable();
            $table->date('effective_date');
            $table->string('reason')->nullable(); // e.g. Annual Appraisal, Promotion to Senior Therapist, Market Adjustment
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_increments');
    }
};
