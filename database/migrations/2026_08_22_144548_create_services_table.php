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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('tagline');
            $table->string('category');
            $table->string('hero_image');
            $table->json('images')->nullable();
            $table->boolean('is_new')->default(false);
            $table->boolean('is_unlimited')->default(true);
            $table->timestamp('start')->nullable();
            $table->timestamp('end')->nullable();
            $table->boolean('is_discount_active')->default(false);
            $table->string('discount_type')->default('percentage'); // percentage, fixed
            $table->decimal('discount_value', 10, 2)->default(0.00);
            $table->timestamp('discount_start_at')->nullable();
            $table->timestamp('discount_end_at')->nullable();
            $table->decimal('rating', 3, 2)->default(5.00);
            $table->integer('review_count')->default(0);
            $table->text('overview');
            $table->text('full_description');
            $table->json('ritual_steps');
            $table->timestamps();
        });

        Schema::create('durations', function (Blueprint $table) {
            $table->id();
            $table->integer('minutes')->unique();
            $table->string('display_text');
            $table->timestamps();
        });

        Schema::create('service_durations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->foreignId('duration_id')->constrained('durations')->cascadeOnDelete();
            $table->decimal('price', 10, 2);
            $table->string('label');
            $table->string('title'); // non-nullable title for the tier
            $table->boolean('popular')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['service_id', 'duration_id']);
        });

        Schema::create('service_special_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->foreignId('duration_id')->nullable()->constrained('durations')->cascadeOnDelete();
            $table->string('badge');
            $table->string('title');
            $table->string('discount');
            $table->text('description');
            $table->string('promo_code')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->timestamps();
        });

        Schema::create('service_highlights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->string('icon')->nullable();
            $table->string('title');
            $table->text('description');
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('service_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->string('author_name');
            $table->unsignedInteger('rating');
            $table->date('date');
            $table->text('comment');
            $table->string('treatment_duration')->nullable();
            $table->boolean('verified_guest')->default(true);
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_reviews');
        Schema::dropIfExists('service_highlights');
        Schema::dropIfExists('service_special_offers');
        Schema::dropIfExists('service_durations');
        Schema::dropIfExists('durations');
        Schema::dropIfExists('services');
    }
};
