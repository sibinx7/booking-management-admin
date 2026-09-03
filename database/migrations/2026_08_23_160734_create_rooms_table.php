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
        // 1. Master Room Types Table
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Single Suite, Couple Suite, VIP Suite, Hydrotherapy Suite, Honeymoon Suite
            $table->string('code')->unique(); // single, couple, vip, hydrotherapy_suite, honeymoon
            $table->text('description')->nullable();
            $table->string('featured_image')->nullable(); // Master category preview image
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Master Reusable Room Highlights Table (Holds name, icon, image & description)
        Schema::create('room_highlights', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "Red Dim Mood Lighting", "Two Side-by-Side Beds", "69 Sensual Synchrony Ritual Support"
            $table->string('code')->unique(); // red_dim_lighting, two_side_by_side_beds, sensual_69_massage_support, etc.
            $table->string('icon')->nullable(); // e.g. "flame", "sparkles", "heart", "bath"
            $table->string('image')->nullable(); // Photo of this feature
            $table->string('category')->default('ambience'); // ambience, sensual_rituals, amenities, wellness, privacy
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Room Type <-> Room Highlights Pivot (Pure template mapping)
        Schema::create('room_type_room_highlight', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_type_id')->constrained('room_types')->cascadeOnDelete();
            $table->foreignId('room_highlight_id')->constrained('room_highlights')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['room_type_id', 'room_highlight_id']);
        });

        // 4. Spa Treatment Rooms Table (with Dimensions, Ceiling Height, and Individual Room Photo Gallery)
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_type_id')->constrained('room_types')->restrictOnDelete();
            $table->string('room_number')->unique(); // e.g. "Suite 101", "Suite 102", "VIP-01"
            $table->string('name'); // e.g. "Lotus Royal Sanctuary", "Mahogany Couples Suite"
            $table->unsignedTinyInteger('bed_count')->default(1); // 1 for single, 2 for couple/dual/honeymoon

            // Room Dimensions & Height
            $table->decimal('height_feet', 4, 2)->nullable(); // Ceiling height in feet (e.g. 12.00 ft)
            $table->decimal('length_feet', 4, 2)->nullable(); // Room length in feet
            $table->decimal('width_feet', 4, 2)->nullable(); // Room width in feet
            $table->decimal('area_sqft', 6, 2)->nullable(); // Total square footage (e.g. 350.00 sqft)

            // Individual Room Photos (Allows 2-3 rooms of the same type to showcase distinct ambiences)
            $table->string('featured_image')->nullable(); // Main showcase photo of this specific room
            $table->json('gallery_images')->nullable(); // Array of uploaded room interior photos
            $table->string('floor_plan_image')->nullable(); // Optional floor plan

            // Room Spotlight
            $table->boolean('is_highlighted')->default(false);
            $table->string('highlight_tag')->nullable();

            // Premium Spa Features & Amenities
            $table->boolean('has_jacuzzi')->default(false);
            $table->boolean('has_sauna')->default(false);
            $table->boolean('has_steam_bath')->default(false);
            $table->boolean('has_shower')->default(true);
            $table->boolean('has_toilet')->default(true);
            $table->boolean('has_ac')->default(true);
            $table->boolean('has_candle_light')->default(false);
            $table->boolean('has_two_massage_beds')->default(false);
            $table->json('extra_amenities')->nullable(); // e.g. ["Aromatherapy Diffuser", "Rose Petal Bath", "Bose Ambient Sound"]

            $table->string('status')->default('available'); // available, occupied, cleaning, maintenance
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 5. Room <-> Room Highlights Pivot (Pure room-to-highlight mapping)
        Schema::create('room_room_highlight', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
            $table->foreignId('room_highlight_id')->constrained('room_highlights')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['room_id', 'room_highlight_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_room_highlight');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('room_type_room_highlight');
        Schema::dropIfExists('room_highlights');
        Schema::dropIfExists('room_types');
    }
};
