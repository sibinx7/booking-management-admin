<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Super Admin
        $superAdminUser = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
        ]);
        Admin::factory()->superAdmin()->create([
            'user_id' => $superAdminUser->id,
        ]);

        // 2. Seed Spa Admin
        $spaAdminUser = User::factory()->create([
            'name' => 'Spa Admin',
            'email' => 'spaadmin@example.com',
        ]);
        Admin::factory()->spaAdmin()->create([
            'user_id' => $spaAdminUser->id,
        ]);

        // 3. Seed Client (User)
        $clientUser = User::factory()->create([
            'name' => 'Test Client',
            'email' => 'client@example.com',
        ]);
        Client::factory()->create([
            'user_id' => $clientUser->id,
        ]);

        // 4. Seed Master Durations
        $durations = [];
        foreach ([30, 60, 90, 120, 150] as $mins) {
            $durations[$mins] = \App\Models\Duration::create([
                'minutes' => $mins,
                'display_text' => "{$mins} mins",
            ]);
        }

        // 5. Seed Volcanic Stone Ritual Service
        $volcanicStone = \App\Models\Service::create([
            'slug' => 'volcanic-stone-ritual',
            'title' => 'Volcanic Stone Ritual',
            'tagline' => 'Deep thermal muscle melting & hot basalt alignments',
            'category' => 'Exotic Massage',
            'hero_image' => 'images/treatments/volcanic-stone.jpg',
            'images' => ['images/treatments/gallery1.jpg', 'images/treatments/gallery2.jpg'],
            'is_new' => true,
            'is_unlimited' => true,
            'start' => now(),
            'end' => null,
            'is_discount_active' => false,
            'discount_type' => 'percentage',
            'discount_value' => 0.00,
            'discount_start_at' => null,
            'discount_end_at' => null,
            'rating' => 4.85,
            'review_count' => 62,
            'overview' => 'Experience ultimate restoration with our volcanic stone therapy. Heat conducts deep into muscle tissue to relieve tension and melt stresses away.',
            'full_description' => 'Combining the deep conductive heat of volcanic basalt stones with organic aromatics and rhythmic Swedish strokes. Warm stones are placed on energy alignment points along your back, hands, and feet to ground your body and relieve core tension.',
            'ritual_steps' => [
                'Welcome foot ritual with sea minerals',
                'Aromatic inhalation sensory check',
                'Warm basalt stone placement & Swedish massage techniques',
                'Cool stone face massage & alignment',
                'Organic herbal tea relaxation'
            ],
        ]);

        // 6. Bind durations & pricing to Volcanic Stone Ritual
        \App\Models\ServiceDuration::create([
            'service_id' => $volcanicStone->id,
            'duration_id' => $durations[60]->id,
            'price' => 130.00,
            'label' => 'Essential Reset',
            'title' => 'Essential Hot Stone Ritual',
            'popular' => false,
            'description' => '60-minute hot basalt stone massage focusing on key tension areas.',
        ]);

        \App\Models\ServiceDuration::create([
            'service_id' => $volcanicStone->id,
            'duration_id' => $durations[90]->id,
            'price' => 180.00,
            'label' => 'Signature Journey',
            'title' => 'Signature Volcanic Alignment',
            'popular' => true,
            'description' => '90-minute full body basalt stones session with spinal alignments.',
        ]);

        \App\Models\ServiceDuration::create([
            'service_id' => $volcanicStone->id,
            'duration_id' => $durations[120]->id,
            'price' => 240.00,
            'label' => 'Royal Sanctuary',
            'title' => 'Royal Stone & Herb Indulgence',
            'popular' => false,
            'description' => '120-minute therapeutic session including custom aromatherapy oils.',
        ]);

        // 7. Add Special Offers
        \App\Models\ServiceSpecialOffer::create([
            'service_id' => $volcanicStone->id,
            'duration_id' => null,
            'badge' => 'Couples Privilege',
            'title' => 'Romantic Couples Volcanic Escape',
            'discount' => '15% OFF',
            'description' => 'Enjoy a complimentary couples steam bath session when booking together.',
            'promo_code' => 'ROMANCE15',
            'is_active' => true,
        ]);

        \App\Models\ServiceSpecialOffer::create([
            'service_id' => $volcanicStone->id,
            'duration_id' => $durations[90]->id,
            'badge' => 'Extra Indulgence',
            'title' => 'Complementary Sauna Session',
            'discount' => 'FREE Access',
            'description' => 'Get free sauna access when you book our 90-minute Signature Journey.',
            'promo_code' => 'SAUNAFREE',
            'is_active' => true,
        ]);

        // 8. Add Highlights
        \App\Models\ServiceHighlight::create([
            'service_id' => $volcanicStone->id,
            'icon' => 'bi-flower1',
            'title' => 'Warm Basalt Stones',
            'description' => 'Smooth, volcanic basalt rocks rich in iron that retain thermal heat.',
        ]);

        \App\Models\ServiceHighlight::create([
            'service_id' => $volcanicStone->id,
            'icon' => 'bi-droplet',
            'title' => 'Organic Essential Oils',
            'description' => 'Specially infused lavender and eucalyptus botanical extracts.',
        ]);

        \App\Models\ServiceHighlight::create([
            'service_id' => $volcanicStone->id,
            'icon' => 'bi-wind',
            'title' => 'Aroma Sensory Ritual',
            'description' => 'Calm breathing exercises using custom therapeutic steam blends.',
        ]);

        // 9. Add Sample Reviews
        \App\Models\ServiceReview::create([
            'service_id' => $volcanicStone->id,
            'author_name' => 'Sarah Mitchell',
            'rating' => 5,
            'date' => '2026-08-15',
            'comment' => 'The heat from the volcanic stones went straight to my back pain. Utterly amazing therapist and sanctuary atmosphere!',
            'treatment_duration' => '90 mins',
            'verified_guest' => true,
        ]);

        \App\Models\ServiceReview::create([
            'service_id' => $volcanicStone->id,
            'author_name' => 'James Harrison',
            'rating' => 4,
            'date' => '2026-08-10',
            'comment' => 'Excellent deep muscle relaxation. The welcome foot bath was a really nice premium touch.',
            'treatment_duration' => '60 mins',
            'verified_guest' => true,
        ]);

        // 10. Seed Languages
        $languages = [
            'en' => \App\Models\Language::create(['name' => 'English', 'code' => 'en']),
            'ml' => \App\Models\Language::create(['name' => 'Malayalam', 'code' => 'ml']),
            'hi' => \App\Models\Language::create(['name' => 'Hindi', 'code' => 'hi']),
        ];

        // 11. Seed Skills
        $skills = [
            'hard' => \App\Models\Skill::create(['name' => 'Hard massage']),
            'buttock' => \App\Models\Skill::create(['name' => 'Buttock massage']),
            'soft' => \App\Models\Skill::create(['name' => 'Soft massage']),
            'foot' => \App\Models\Skill::create(['name' => 'Foot reflexology']),
        ];

        // 12. Seed Specialities
        $specialities = [
            'cute' => \App\Models\Speciality::create(['name' => 'Cute']),
            'friendly' => \App\Models\Speciality::create(['name' => 'Friendly']),
            'romantic' => \App\Models\Speciality::create(['name' => 'Romantic']),
            'extra_support' => \App\Models\Speciality::create(['name' => 'Extra support']),
        ];

        // 13. Seed Therapists
        $therapist1 = \App\Models\Therapist::factory()->create([
            'name' => 'Anjali Nair',
            'gender' => 'female',
            'email' => 'anjali@example.com',
            'rating' => 4.90,
            'review_count' => 38,
        ]);

        $therapist2 = \App\Models\Therapist::factory()->create([
            'name' => 'Rahul Sharma',
            'gender' => 'male',
            'email' => 'rahul@example.com',
            'rating' => 4.75,
            'review_count' => 19,
        ]);

        $therapist3 = \App\Models\Therapist::factory()->create([
            'name' => 'Maya Krishnan',
            'gender' => 'female',
            'email' => 'maya@example.com',
            'rating' => 5.00,
            'review_count' => 24,
        ]);

        // Bind languages, skills, specialties and standard services to therapists
        
        // Anjali: Speaks English & Malayalam; does Hard massage & Foot reflexology; speciality Friendly (no extra) and Romantic ($50 extra)
        $therapist1->languages()->sync([$languages['en']->id, $languages['ml']->id]);
        $therapist1->skills()->sync([$skills['hard']->id, $skills['foot']->id]);
        $therapist1->specialities()->sync([
            $specialities['friendly']->id => ['extra_charge' => 0.00],
            $specialities['romantic']->id => ['extra_charge' => 50.00],
        ]);
        $therapist1->services()->sync([$volcanicStone->id]);

        // Rahul: Speaks English & Hindi; does Hard massage & Soft massage; speciality Friendly (no extra)
        $therapist2->languages()->sync([$languages['en']->id, $languages['hi']->id]);
        $therapist2->skills()->sync([$skills['hard']->id, $skills['soft']->id]);
        $therapist2->specialities()->sync([
            $specialities['friendly']->id => ['extra_charge' => 0.00],
        ]);
        $therapist2->services()->sync([$volcanicStone->id]);

        // Maya: Speaks English, Malayalam & Hindi; does Buttock massage & Soft massage; speciality Cute (no extra), Romantic ($60 extra) and Extra support ($100 extra)
        $therapist3->languages()->sync([$languages['en']->id, $languages['ml']->id, $languages['hi']->id]);
        $therapist3->skills()->sync([$skills['buttock']->id, $skills['soft']->id]);
        $therapist3->specialities()->sync([
            $specialities['cute']->id => ['extra_charge' => 0.00],
            $specialities['romantic']->id => ['extra_charge' => 60.00],
            $specialities['extra_support']->id => ['extra_charge' => 100.00],
        ]);
        $therapist3->services()->sync([$volcanicStone->id]);

        // 14. Seed availabilities (Monday to Friday, 9:00 AM to 5:00 PM)
        foreach ([$therapist1, $therapist2, $therapist3] as $therapist) {
            for ($day = 1; $day <= 5; $day++) {
                \App\Models\TherapistAvailability::create([
                    'therapist_id' => $therapist->id,
                    'day_of_week' => $day,
                    'start_time' => '09:00:00',
                    'end_time' => '17:00:00',
                    'is_available' => true,
                ]);
            }
        }
    }
}
