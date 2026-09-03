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
        // Parent table: users
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_role_id')->default(1)->constrained('user_roles')->restrictOnDelete();
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('password')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // Child table: admins (one-to-one with users)
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('role')->default('spa_admin'); // super_admin, spa_admin
            $table->timestamps();
        });

        // Child table: clients (one-to-one with users)
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('country_code')->nullable();
            $table->string('phone_number')->nullable()->unique();
            $table->string('registration_mode')->default('both'); // both, email_only, phone_only
            $table->string('google_id')->nullable()->unique();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('email_otp')->nullable();
            $table->timestamp('email_otp_expires_at')->nullable();
            $table->string('phone_otp')->nullable();
            $table->timestamp('phone_otp_expires_at')->nullable();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('clients');
        Schema::dropIfExists('admins');
        Schema::dropIfExists('users');
    }
};
