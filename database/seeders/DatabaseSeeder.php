<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Client;
use App\Models\ClientPayment;
use App\Models\Duration;
use App\Models\Employee;
use App\Models\EmployeeAttendance;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\InventoryCategory;
use App\Models\InventoryItem;
use App\Models\InventoryStockLog;
use App\Models\Language;
use App\Models\PaymentType;
use App\Models\Receptionist;
use App\Models\Room;
use App\Models\RoomHighlight;
use App\Models\RoomType;
use App\Models\SalaryGrade;
use App\Models\SalaryIncrement;
use App\Models\SalaryPayment;
use App\Models\Service;
use App\Models\ServiceDuration;
use App\Models\ServiceHighlight;
use App\Models\ServiceReview;
use App\Models\ServiceSpecialOffer;
use App\Models\Skill;
use App\Models\Speciality;
use App\Models\Therapist;
use App\Models\TherapistAttendance;
use App\Models\TherapistAvailability;
use App\Models\TherapistServiceLog;
use App\Models\User;
use App\Models\UserRole;
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
        // 1. Master User Roles (prepopulated via migration or retrieved here)
        $roles = [
            'client' => UserRole::firstOrCreate(
                ['code' => 'client'],
                ['name' => 'Client', 'description' => 'Default client and customer role', 'is_active' => true]
            ),
            'employee' => UserRole::firstOrCreate(
                ['code' => 'employee'],
                ['name' => 'Employee', 'description' => 'Spa staff member (therapist, receptionist, cleaner, laundry)', 'is_active' => true]
            ),
            'admin' => UserRole::firstOrCreate(
                ['code' => 'admin'],
                ['name' => 'Admin', 'description' => 'System administrator or spa administrator', 'is_active' => true]
            ),
        ];

        // 2. Seed Super Admin
        $superAdminUser = User::factory()->create([
            'user_role_id' => $roles['admin']->id,
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
        ]);
        Admin::factory()->superAdmin()->create([
            'user_id' => $superAdminUser->id,
        ]);

        // 3. Seed Spa Admin
        $spaAdminUser = User::factory()->create([
            'user_role_id' => $roles['admin']->id,
            'name' => 'Spa Admin',
            'email' => 'spaadmin@example.com',
        ]);
        Admin::factory()->spaAdmin()->create([
            'user_id' => $spaAdminUser->id,
        ]);

        // 4. Seed Client (User)
        $clientUser = User::factory()->create([
            'user_role_id' => $roles['client']->id,
            'name' => 'Test Client',
            'email' => 'client@example.com',
        ]);
        Client::factory()->create([
            'user_id' => $clientUser->id,
        ]);

        // 5. Seed Salary Grades
        $grades = [
            'A' => SalaryGrade::create([
                'name' => 'Grade A - Senior Specialist',
                'code' => 'GRADE-A',
                'min_salary' => 35000.00,
                'max_salary' => 60000.00,
                'description' => 'Senior certified therapists and master bodywork artisans',
                'is_active' => true,
            ]),
            'B' => SalaryGrade::create([
                'name' => 'Grade B - Standard Professional',
                'code' => 'GRADE-B',
                'min_salary' => 22000.00,
                'max_salary' => 35000.00,
                'description' => 'Certified therapists, senior front-desk receptionists, and supervisors',
                'is_active' => true,
            ]),
            'C' => SalaryGrade::create([
                'name' => 'Grade C - Support Staff',
                'code' => 'GRADE-C',
                'min_salary' => 12000.00,
                'max_salary' => 22000.00,
                'description' => 'Sanitation cleaners, laundry handlers, and junior associates',
                'is_active' => true,
            ]),
        ];

        // 6. Seed Payment Types
        $paymentTypes = [
            'bank' => PaymentType::create([
                'name' => 'Bank Transfer',
                'code' => 'bank',
                'description' => 'Direct NEFT/RTGS/IMPS account transfer',
                'is_active' => true,
            ]),
            'upi' => PaymentType::create([
                'name' => 'UPI',
                'code' => 'upi',
                'description' => 'Unified Payments Interface (GPay, PhonePe, Paytm)',
                'is_active' => true,
            ]),
            'cash' => PaymentType::create([
                'name' => 'Cash',
                'code' => 'cash',
                'description' => 'Physical cash disbursement',
                'is_active' => true,
            ]),
        ];

        // 7. Seed Master Durations
        $durations = [];
        foreach ([30, 60, 90, 120, 150] as $mins) {
            $durations[$mins] = Duration::create([
                'minutes' => $mins,
                'display_text' => "{$mins} mins",
            ]);
        }

        // 8. Seed Volcanic Stone Ritual Service
        $volcanicStone = Service::create([
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
                'Organic herbal tea relaxation',
            ],
        ]);

        // 9. Bind durations & pricing to Volcanic Stone Ritual
        ServiceDuration::create([
            'service_id' => $volcanicStone->id,
            'duration_id' => $durations[60]->id,
            'price' => 130.00,
            'label' => 'Essential Reset',
            'title' => 'Essential Hot Stone Ritual',
            'popular' => false,
            'description' => '60-minute hot basalt stone massage focusing on key tension areas.',
        ]);

        ServiceDuration::create([
            'service_id' => $volcanicStone->id,
            'duration_id' => $durations[90]->id,
            'price' => 180.00,
            'label' => 'Signature Journey',
            'title' => 'Signature Volcanic Alignment',
            'popular' => true,
            'description' => '90-minute full body basalt stones session with spinal alignments.',
        ]);

        ServiceDuration::create([
            'service_id' => $volcanicStone->id,
            'duration_id' => $durations[120]->id,
            'price' => 240.00,
            'label' => 'Royal Sanctuary',
            'title' => 'Royal Stone & Herb Indulgence',
            'popular' => false,
            'description' => '120-minute therapeutic session including custom aromatherapy oils.',
        ]);

        // 10. Add Special Offers
        ServiceSpecialOffer::create([
            'service_id' => $volcanicStone->id,
            'duration_id' => null,
            'badge' => 'Couples Privilege',
            'title' => 'Romantic Couples Volcanic Escape',
            'discount' => '15% OFF',
            'description' => 'Enjoy a complimentary couples steam bath session when booking together.',
            'promo_code' => 'ROMANCE15',
            'is_active' => true,
        ]);

        ServiceSpecialOffer::create([
            'service_id' => $volcanicStone->id,
            'duration_id' => $durations[90]->id,
            'badge' => 'Extra Indulgence',
            'title' => 'Complementary Sauna Session',
            'discount' => 'FREE Access',
            'description' => 'Get free sauna access when you book our 90-minute Signature Journey.',
            'promo_code' => 'SAUNAFREE',
            'is_active' => true,
        ]);

        // 11. Add Highlights
        ServiceHighlight::create([
            'service_id' => $volcanicStone->id,
            'icon' => 'bi-flower1',
            'title' => 'Warm Basalt Stones',
            'description' => 'Smooth, volcanic basalt rocks rich in iron that retain thermal heat.',
        ]);

        ServiceHighlight::create([
            'service_id' => $volcanicStone->id,
            'icon' => 'bi-droplet',
            'title' => 'Organic Essential Oils',
            'description' => 'Specially infused lavender and eucalyptus botanical extracts.',
        ]);

        ServiceHighlight::create([
            'service_id' => $volcanicStone->id,
            'icon' => 'bi-wind',
            'title' => 'Aroma Sensory Ritual',
            'description' => 'Calm breathing exercises using custom therapeutic steam blends.',
        ]);

        // 12. Add Sample Reviews
        ServiceReview::create([
            'service_id' => $volcanicStone->id,
            'author_name' => 'Sarah Mitchell',
            'rating' => 5,
            'date' => '2026-08-15',
            'comment' => 'The heat from the volcanic stones went straight to my back pain. Utterly amazing therapist and sanctuary atmosphere!',
            'treatment_duration' => '90 mins',
            'verified_guest' => true,
            'is_published' => true,
            'published_at' => '2026-08-15 10:30:00',
        ]);

        ServiceReview::create([
            'service_id' => $volcanicStone->id,
            'author_name' => 'James Harrison',
            'rating' => 4,
            'date' => '2026-08-10',
            'comment' => 'Excellent deep muscle relaxation. The welcome foot bath was a really nice premium touch.',
            'treatment_duration' => '60 mins',
            'verified_guest' => true,
            'is_published' => true,
            'published_at' => '2026-08-10 14:15:00',
        ]);

        // 13. Seed Languages
        $languages = [
            'en' => Language::create(['name' => 'English', 'code' => 'en']),
            'ml' => Language::create(['name' => 'Malayalam', 'code' => 'ml']),
            'hi' => Language::create(['name' => 'Hindi', 'code' => 'hi']),
        ];

        // 14. Seed Skills
        $skills = [
            'hard' => Skill::create(['name' => 'Hard massage']),
            'buttock' => Skill::create(['name' => 'Buttock massage']),
            'soft' => Skill::create(['name' => 'Soft massage']),
            'foot' => Skill::create(['name' => 'Foot reflexology']),
        ];

        // 15. Seed Specialities
        $specialities = [
            'cute' => Speciality::create(['name' => 'Cute']),
            'friendly' => Speciality::create(['name' => 'Friendly']),
            'romantic' => Speciality::create(['name' => 'Romantic']),
            'extra_support' => Speciality::create(['name' => 'Extra support']),
        ];

        // 16. Seed Other Staff (Receptionist, Cleaner, Laundry)
        // Receptionist (Regular - Grade B)
        $receptionistUser = User::factory()->create([
            'user_role_id' => $roles['employee']->id,
            'name' => 'Pooja Verma',
            'email' => 'receptionist@example.com',
        ]);
        $receptionist = Employee::create([
            'user_id' => $receptionistUser->id,
            'salary_grade_id' => $grades['B']->id,
            'employee_code' => 'EMP-REC-01',
            'gender' => 'female',
            'dob' => '1998-04-12',
            'phone_number' => '+91 98765 43210',
            'profile_pic' => 'images/employees/pooja.jpg',
            'role' => 'receptionist',
            'employment_type' => 'regular',
            'status' => 'active',
            'is_active' => true,
            'joining_date' => '2025-01-10',
            'base_salary' => 24000.00,
            'bank_name' => 'HDFC Bank',
            'bank_account_number' => '50100234567890',
            'bank_ifsc' => 'HDFC0001234',
            'upi_id' => 'pooja@okhdfcbank',
            'notes' => 'Senior front desk receptionist and guest coordinator.',
        ]);

        // Cleaner (Temporary / Contract - Grade C)
        $cleanerUser = User::factory()->create([
            'user_role_id' => $roles['employee']->id,
            'name' => 'Ramesh Kumar',
            'email' => 'cleaner@example.com',
        ]);
        $cleaner = Employee::create([
            'user_id' => $cleanerUser->id,
            'salary_grade_id' => $grades['C']->id,
            'employee_code' => 'EMP-CLN-01',
            'gender' => 'male',
            'dob' => '1992-11-20',
            'phone_number' => '+91 98111 22334',
            'profile_pic' => 'images/employees/ramesh.jpg',
            'role' => 'cleaner',
            'employment_type' => 'temporary',
            'status' => 'active',
            'is_active' => true,
            'joining_date' => '2025-06-01',
            'base_salary' => 14000.00,
            'bank_name' => 'State Bank of India',
            'bank_account_number' => '203040506070',
            'bank_ifsc' => 'SBIN0004321',
            'upi_id' => 'ramesh@oksbi',
            'notes' => 'Daily spa area sanitation and hygiene maintenance.',
        ]);

        // Laundry Staff (Temporary - Grade C)
        $laundryUser = User::factory()->create([
            'user_role_id' => $roles['employee']->id,
            'name' => 'Sunita Devi',
            'email' => 'laundry@example.com',
        ]);
        $laundry = Employee::create([
            'user_id' => $laundryUser->id,
            'salary_grade_id' => $grades['C']->id,
            'employee_code' => 'EMP-LND-01',
            'gender' => 'female',
            'dob' => '1989-08-15',
            'phone_number' => '+91 98222 33445',
            'profile_pic' => 'images/employees/sunita.jpg',
            'role' => 'laundry',
            'employment_type' => 'temporary',
            'status' => 'active',
            'is_active' => true,
            'joining_date' => '2025-07-15',
            'base_salary' => 15000.00,
            'bank_name' => 'ICICI Bank',
            'bank_account_number' => '102030405060',
            'bank_ifsc' => 'ICIC0001020',
            'upi_id' => 'sunita@okicici',
            'notes' => 'Responsible for organic linen, robes, and towel steaming.',
        ]);

        // Former Employee who resigned / went to another job
        $formerUser = User::factory()->create([
            'user_role_id' => $roles['employee']->id,
            'name' => 'Vikram Singh',
            'email' => 'vikram@example.com',
        ]);
        Employee::create([
            'user_id' => $formerUser->id,
            'salary_grade_id' => $grades['B']->id,
            'employee_code' => 'EMP-REC-02',
            'gender' => 'male',
            'dob' => '1994-03-11',
            'phone_number' => '+91 98999 11223',
            'profile_pic' => 'images/employees/vikram.jpg',
            'role' => 'receptionist',
            'employment_type' => 'regular',
            'status' => 'resigned',
            'is_active' => false,
            'joining_date' => '2024-01-15',
            'exit_date' => '2025-12-31',
            'exit_reason' => 'Resigned to take a management role at a luxury resort.',
            'base_salary' => 26000.00,
            'bank_name' => 'HDFC Bank',
            'bank_account_number' => '50100998877665',
            'bank_ifsc' => 'HDFC0001234',
            'upi_id' => 'vikram@okhdfcbank',
            'notes' => 'Relieved with full clearance.',
        ]);

        // 17. Seed Therapists as Employees
        // Therapist 1: Anjali Nair (Regular - Grade A)
        $anjaliUser = User::factory()->create([
            'user_role_id' => $roles['employee']->id,
            'name' => 'Anjali Nair',
            'email' => 'anjali@example.com',
        ]);
        $anjaliEmp = Employee::create([
            'user_id' => $anjaliUser->id,
            'salary_grade_id' => $grades['A']->id,
            'employee_code' => 'EMP-THR-01',
            'gender' => 'female',
            'dob' => '1995-06-25',
            'phone_number' => '+91 98456 12345',
            'profile_pic' => 'images/therapists/anjali.jpg',
            'role' => 'therapist',
            'employment_type' => 'regular',
            'status' => 'active',
            'is_active' => true,
            'joining_date' => '2024-03-01',
            'base_salary' => 35000.00,
            'bank_name' => 'Axis Bank',
            'bank_account_number' => '912010012345678',
            'bank_ifsc' => 'UTIB0000123',
            'upi_id' => 'anjali@axisbank',
            'notes' => 'Specializes in Ayurvedic and deep thermal bodywork.',
        ]);
        $therapist1 = Therapist::create([
            'employee_id' => $anjaliEmp->id,
            'display_name' => 'Anjali Nair',
            'profile_pic' => 'images/therapists/anjali.jpg',
            'bio' => 'Certified Ayurvedic therapist with 6 years of holistic bodywork experience.',
            'education' => ['Diploma in Ayurvedic Spa Therapy', 'Basalt Stone Certification'],
            'is_online' => true,
            'commission_rate' => 50.00,
            'rating' => 4.90,
            'review_count' => 38,
        ]);

        // Therapist 2: Rahul Sharma (Regular - Grade B)
        $rahulUser = User::factory()->create([
            'user_role_id' => $roles['employee']->id,
            'name' => 'Rahul Sharma',
            'email' => 'rahul@example.com',
        ]);
        $rahulEmp = Employee::create([
            'user_id' => $rahulUser->id,
            'salary_grade_id' => $grades['B']->id,
            'employee_code' => 'EMP-THR-02',
            'gender' => 'male',
            'dob' => '1993-02-14',
            'phone_number' => '+91 98789 54321',
            'profile_pic' => 'images/therapists/rahul.jpg',
            'role' => 'therapist',
            'employment_type' => 'regular',
            'status' => 'active',
            'is_active' => true,
            'joining_date' => '2024-05-15',
            'base_salary' => 32000.00,
            'bank_name' => 'HDFC Bank',
            'bank_account_number' => '50100987654321',
            'bank_ifsc' => 'HDFC0001234',
            'upi_id' => 'rahul@okhdfcbank',
            'notes' => 'Experienced in Swedish and trigger-point reflexology.',
        ]);
        $therapist2 = Therapist::create([
            'employee_id' => $rahulEmp->id,
            'display_name' => 'Rahul Sharma',
            'profile_pic' => 'images/therapists/rahul.jpg',
            'bio' => 'Passionate about athletic recovery and stress decompression techniques.',
            'education' => ['Certified Swedish Massage Practitioner', 'Reflexology Mastery'],
            'is_online' => true,
            'commission_rate' => 45.00,
            'rating' => 4.75,
            'review_count' => 19,
        ]);

        // Therapist 3: Maya Krishnan (Guest Specialist - Grade A)
        $mayaUser = User::factory()->create([
            'user_role_id' => $roles['employee']->id,
            'name' => 'Maya Krishnan',
            'email' => 'maya@example.com',
        ]);
        $mayaEmp = Employee::create([
            'user_id' => $mayaUser->id,
            'salary_grade_id' => $grades['A']->id,
            'employee_code' => 'EMP-THR-03',
            'gender' => 'female',
            'dob' => '1996-09-18',
            'phone_number' => '+91 98333 77889',
            'profile_pic' => 'images/therapists/maya.jpg',
            'role' => 'therapist',
            'employment_type' => 'guest',
            'status' => 'active',
            'is_active' => true,
            'joining_date' => '2025-02-01',
            'base_salary' => 0.00,
            'bank_name' => 'Kotak Mahindra Bank',
            'bank_account_number' => '801234567890',
            'bank_ifsc' => 'KKBK0000888',
            'upi_id' => 'maya@kotak',
            'notes' => 'Visiting master therapist for VIP sessions and aromatherapy.',
        ]);
        $therapist3 = Therapist::create([
            'employee_id' => $mayaEmp->id,
            'display_name' => 'Maya Krishnan',
            'profile_pic' => 'images/therapists/maya.jpg',
            'bio' => 'Guest master artisan specializing in bespoke aromatics and soothing energy balance.',
            'education' => ['International Aromatherapy & Spa Master Diploma'],
            'is_online' => false,
            'commission_rate' => 60.00,
            'rating' => 5.00,
            'review_count' => 24,
        ]);

        // 18. Seed Salary Increments (History)
        SalaryIncrement::create([
            'employee_id' => $anjaliEmp->id,
            'salary_grade_id' => $grades['A']->id,
            'previous_salary' => 30000.00,
            'increment_amount' => 5000.00,
            'new_salary' => 35000.00,
            'increment_percentage' => 16.67,
            'effective_date' => '2025-04-01',
            'reason' => 'Annual Performance Appraisal & Promotion to Grade A Senior Specialist',
            'approved_by' => $spaAdminUser->id,
            'remarks' => 'Consistently highest customer rating and repeat bookings.',
        ]);

        SalaryIncrement::create([
            'employee_id' => $receptionist->id,
            'salary_grade_id' => $grades['B']->id,
            'previous_salary' => 20000.00,
            'increment_amount' => 4000.00,
            'new_salary' => 24000.00,
            'increment_percentage' => 20.00,
            'effective_date' => '2025-07-01',
            'commission_rate' => 15.00,
            'rating' => 4.95,
            'review_count' => 156,
        ]);

        // 18. Link Therapists to Skills, Languages, Specialities & Services
        $therapist1->languages()->sync([$languages['en']->id, $languages['ml']->id]);
        $therapist1->skills()->sync([$skills['hard']->id, $skills['foot']->id]);
        $therapist1->specialities()->sync([
            $specialities['friendly']->id => ['extra_charge' => 0.00],
            $specialities['romantic']->id => ['extra_charge' => 50.00],
        ]);
        $therapist1->services()->sync([$volcanicStone->id]);

        $therapist2->languages()->sync([$languages['en']->id, $languages['hi']->id]);
        $therapist2->skills()->sync([$skills['hard']->id, $skills['soft']->id]);
        $therapist2->specialities()->sync([
            $specialities['friendly']->id => ['extra_charge' => 0.00],
        ]);
        $therapist2->services()->sync([$volcanicStone->id]);

        $therapist3->languages()->sync([$languages['en']->id, $languages['ml']->id, $languages['hi']->id]);
        $therapist3->skills()->sync([$skills['buttock']->id, $skills['soft']->id]);
        $therapist3->specialities()->sync([
            $specialities['cute']->id => ['extra_charge' => 0.00],
            $specialities['romantic']->id => ['extra_charge' => 60.00],
            $specialities['extra_support']->id => ['extra_charge' => 100.00],
        ]);
        $therapist3->services()->sync([$volcanicStone->id]);

        // 19. Seed availabilities (Monday to Friday, 9:00 AM to 5:00 PM)
        foreach ([$therapist1, $therapist2, $therapist3] as $therapist) {
            for ($day = 1; $day <= 5; $day++) {
                TherapistAvailability::create([
                    'therapist_id' => $therapist->id,
                    'day_of_week' => $day,
                    'start_time' => '09:00:00',
                    'end_time' => '17:00:00',
                    'is_available' => true,
                ]);
            }
        }

        // 20. Seed Daily Attendance for Employees & Therapists (August 2026)
        $allActiveStaff = [$anjaliEmp, $rahulEmp, $mayaEmp, $receptionistEmp, $cleaner, $laundry];
        foreach ($allActiveStaff as $emp) {
            for ($day = 1; $day <= 26; $day++) {
                $dateStr = sprintf('2026-08-%02d', $day);
                $isSunday = (date('N', strtotime($dateStr)) == 7);
                EmployeeAttendance::create([
                    'employee_id' => $emp->id,
                    'therapist_id' => $emp->therapist?->id,
                    'date' => $dateStr,
                    'status' => $isSunday ? 'weekly_off' : 'present',
                    'check_in_time' => $isSunday ? null : '08:30:00',
                    'check_out_time' => $isSunday ? null : '17:30:00',
                    'work_hours' => $isSunday ? 0.00 : 8.50,
                    'notes' => $isSunday ? 'Sunday Weekly Off' : 'Regular Shift Completed',
                    'recorded_by' => $spaAdminUser->id,
                ]);
            }
        }

        // 21. Seed Master Reusable Room Highlights
        $hlRedDim = RoomHighlight::create([
            'name' => 'Red Dim Candlelight & Mood Lighting',
            'code' => 'red_dim_lighting',
            'icon' => 'flame',
            'category' => 'ambience',
            'description' => 'Sensual red and warm amber mood lighting tailored for romantic relaxation',
        ]);
        $hlCloseBeds = RoomHighlight::create([
            'name' => 'Close Adjacent Intimate Beds',
            'code' => 'close_intimate_beds',
            'icon' => 'bed',
            'category' => 'sensual_rituals',
            'description' => 'Beds positioned side-by-side with zero gap for touch and intimacy during couples massage',
        ]);
        $hlRomanticMoments = RoomHighlight::create([
            'name' => 'Supports Romantic Moments & Sensual Intimacy',
            'code' => 'romantic_moments',
            'icon' => 'heart',
            'category' => 'sensual_rituals',
            'description' => 'Designed exclusively for couples seeking passionate holistic harmony',
        ]);
        $hlSandwichMassage = RoomHighlight::create([
            'name' => 'Sandwich / Tandem Four-Hand Massage Support',
            'code' => 'sandwich_massage',
            'icon' => 'sparkles',
            'category' => 'sensual_rituals',
            'description' => 'Supports synchronized dual and multi-therapist sandwich massage bodywork',
        ]);
        $hl69Massage = RoomHighlight::create([
            'name' => '69 Sensual Synchrony Massage Ritual Support',
            'code' => 'sensual_69_massage',
            'icon' => 'repeat',
            'category' => 'sensual_rituals',
            'description' => 'Specialized synchronized ergonomic head-to-toe couple massage choreography',
        ]);
        $hlCoupleJacuzzi = RoomHighlight::create([
            'name' => 'Private Couple Rose Petal Jacuzzi',
            'code' => 'couple_jacuzzi',
            'icon' => 'bath',
            'category' => 'wellness',
            'description' => 'Warm hydrotherapy whirlpool bath infused with organic rose petals and essential oils',
        ]);
        $hlSynchronizedBeds = RoomHighlight::create([
            'name' => 'Two Side-by-Side Synchronized Beds',
            'code' => 'synchronized_twin_beds',
            'icon' => 'bed',
            'category' => 'sensual_rituals',
            'description' => 'Ergonomic dual massage beds designed for synchronized couple treatments',
        ]);
        $hlCedarSauna = RoomHighlight::create([
            'name' => 'Private Cedarwood Sauna Circuit',
            'code' => 'cedarwood_sauna',
            'icon' => 'thermometer-sun',
            'category' => 'wellness',
            'description' => 'Dry therapeutic cedarwood sauna cabinet for detox and muscle relaxation',
        ]);
        $hlHerbalSteam = RoomHighlight::create([
            'name' => 'Herbal Steam Chamber',
            'code' => 'herbal_steam',
            'icon' => 'cloud',
            'category' => 'wellness',
            'description' => 'Therapeutic eucalyptus and lemongrass infused aromatic steam chamber',
        ]);
        $hlHydroJetTub = RoomHighlight::create([
            'name' => 'Hydro Aromatherapy Jet Tub',
            'code' => 'hydro_jet_tub',
            'icon' => 'waves',
            'category' => 'wellness',
            'description' => 'Hydrotherapy jet bath for tension relief and skin rejuvenation',
        ]);
        $hlEnsuiteShower = RoomHighlight::create([
            'name' => 'Private Rainfall Shower & Restroom',
            'code' => 'ensuite_shower',
            'icon' => 'shower-head',
            'category' => 'amenities',
            'description' => 'En-suite private rain shower with luxury organic botanical toiletries',
        ]);
        $hlClimateAc = RoomHighlight::create([
            'name' => 'Individual Climate Control AC',
            'code' => 'climate_ac',
            'icon' => 'wind',
            'category' => 'amenities',
            'description' => 'Custom digital thermostat to control suite temperature and humidity',
        ]);
        $hlLounge = RoomHighlight::create([
            'name' => 'Private Relaxation Lounge',
            'code' => 'relaxation_lounge',
            'icon' => 'sofa',
            'category' => 'amenities',
            'description' => 'Plush seating area with herbal tea service and serene ambient acoustics',
        ]);

        // 22. Seed Master Room Types & Link Default Highlights
        $typeSingle = RoomType::create([
            'name' => 'Single Suite',
            'code' => 'single',
            'description' => 'Tranquil private suite with single massage bed for individual therapies',
            'featured_image' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=1200&q=80',
        ]);
        $typeSingle->highlights()->attach([$hlEnsuiteShower->id, $hlClimateAc->id, $hlHerbalSteam->id]);

        $typeCouple = RoomType::create([
            'name' => 'Couple Suite',
            'code' => 'couple',
            'description' => 'Spacious sanctuary with two side-by-side massage beds for couple & dual massages',
            'featured_image' => 'https://images.unsplash.com/photo-1600334089648-b0d9d3028eb2?auto=format&fit=crop&w=1200&q=80',
        ]);
        $typeCouple->highlights()->attach([$hlSynchronizedBeds->id, $hlCoupleJacuzzi->id, $hlSandwichMassage->id, $hlEnsuiteShower->id, $hlClimateAc->id]);

        $typeVip = RoomType::create([
            'name' => 'VIP Suite',
            'code' => 'vip',
            'description' => 'Exclusive luxury suite with private sauna, steam room, and lounge',
            'featured_image' => 'https://images.unsplash.com/photo-1596178065887-1198b6148b2b?auto=format&fit=crop&w=1200&q=80',
        ]);
        $typeVip->highlights()->attach([$hlCedarSauna->id, $hlHerbalSteam->id, $hlLounge->id, $hlEnsuiteShower->id, $hlClimateAc->id]);

        $typeHydro = RoomType::create([
            'name' => 'Hydrotherapy Suite',
            'code' => 'hydrotherapy_suite',
            'description' => 'Specialized suite featuring hydro bath, rain vichy shower, and floral tub',
            'featured_image' => 'https://images.unsplash.com/photo-1584132967334-10e028bd69f7?auto=format&fit=crop&w=1200&q=80',
        ]);
        $typeHydro->highlights()->attach([$hlHydroJetTub->id, $hlEnsuiteShower->id, $hlClimateAc->id]);

        $typeHoneymoon = RoomType::create([
            'name' => 'Honeymoon Suite',
            'code' => 'honeymoon',
            'description' => 'Sensual intimate couples suite with Jacuzzi, rose petals, dim red lighting, and specialized couple rituals',
            'featured_image' => 'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?auto=format&fit=crop&w=1200&q=80',
        ]);
        $typeHoneymoon->highlights()->attach([
            $hlRedDim->id,
            $hlCloseBeds->id,
            $hlRomanticMoments->id,
            $hlSandwichMassage->id,
            $hl69Massage->id,
            $hlCoupleJacuzzi->id,
            $hlEnsuiteShower->id,
            $hlClimateAc->id,
        ]);

        // 23. Seed Spa Treatment Rooms with Dimensions, Photos & Synced Highlights
        $room101 = Room::create([
            'room_type_id' => $typeSingle->id,
            'room_number' => 'Suite 101',
            'name' => 'Lotus Sanctuary',
            'bed_count' => 1,
            'height_feet' => 11.50,
            'length_feet' => 18.00,
            'width_feet' => 14.00,
            'area_sqft' => 252.00,
            'featured_image' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=1200&q=80',
            'gallery_images' => [
                'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1515377905703-c4788e51af15?auto=format&fit=crop&w=1200&q=80',
            ],
            'is_highlighted' => false,
            'highlight_tag' => 'Serene Single',
            'has_jacuzzi' => true,
            'has_sauna' => false,
            'has_steam_bath' => true,
            'has_shower' => true,
            'has_toilet' => true,
            'has_ac' => true,
            'has_candle_light' => false,
            'has_two_massage_beds' => false,
            'extra_amenities' => ['Hydro Aromatherapy Tub', 'Jasmine Ambient Scent', 'Bose Relaxation Audio'],
            'status' => 'available',
            'description' => 'A tranquil single therapy suite with private steam and hydro bath.',
        ]);
        $room101->syncDefaultHighlightsFromRoomType();

        $room102 = Room::create([
            'room_type_id' => $typeCouple->id,
            'room_number' => 'Suite 102',
            'name' => 'Mahogany Couples Suite',
            'bed_count' => 2,
            'height_feet' => 12.00,
            'length_feet' => 22.00,
            'width_feet' => 16.00,
            'area_sqft' => 352.00,
            'featured_image' => 'https://images.unsplash.com/photo-1600334089648-b0d9d3028eb2?auto=format&fit=crop&w=1200&q=80',
            'gallery_images' => [
                'https://images.unsplash.com/photo-1600334089648-b0d9d3028eb2?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&w=1200&q=80',
            ],
            'is_highlighted' => true,
            'highlight_tag' => 'Most Popular for Couples',
            'has_jacuzzi' => true,
            'has_sauna' => true,
            'has_steam_bath' => true,
            'has_shower' => true,
            'has_toilet' => true,
            'has_ac' => true,
            'has_candle_light' => true,
            'has_two_massage_beds' => true, // Two massage beds for couple/dual massage
            'extra_amenities' => ['Two Side-by-Side Massage Beds', 'Couple Rose Petal Jacuzzi', 'Private Cedar Sauna', 'Mood Candle Wall Sconces'],
            'status' => 'available',
            'description' => 'Luxury master couple suite for synchronized dual massage treatments.',
        ]);
        $room102->syncDefaultHighlightsFromRoomType();
        $room102->highlights()->attach($hlCedarSauna->id);

        $room103 = Room::create([
            'room_type_id' => $typeHoneymoon->id,
            'room_number' => 'Suite 103',
            'name' => 'Velvet Rose Honeymoon Sanctuary',
            'bed_count' => 2,
            'height_feet' => 12.50,
            'length_feet' => 24.00,
            'width_feet' => 18.00,
            'area_sqft' => 432.00,
            'featured_image' => 'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?auto=format&fit=crop&w=1200&q=80',
            'gallery_images' => [
                'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1596178065887-1198b6148b2b?auto=format&fit=crop&w=1200&q=80',
            ],
            'is_highlighted' => true,
            'highlight_tag' => 'Signature Honeymoon Sanctuary',
            'has_jacuzzi' => true,
            'has_sauna' => true,
            'has_steam_bath' => true,
            'has_shower' => true,
            'has_toilet' => true,
            'has_ac' => true,
            'has_candle_light' => true,
            'has_two_massage_beds' => true,
            'extra_amenities' => ['Rose Petal Hydro Tub', 'Chilled Champagne Bucket', 'Red Velvet Dimming Ambience'],
            'status' => 'available',
            'description' => 'Intimate and sensual couples sanctuary designed for romantic and four-hand rituals.',
        ]);
        $room103->syncDefaultHighlightsFromRoomType();

        // 22. Seed Dedicated Therapist Attendance Shifts & Suite Allocations
        $att1 = EmployeeAttendance::where('therapist_id', $therapist1->id)->where('date', '2026-08-15')->first();
        $therapistShift1 = TherapistAttendance::create([
            'therapist_id' => $therapist1->id,
            'employee_id' => $anjaliEmp->id,
            'employee_attendance_id' => $att1?->id,
            'room_id' => $room101->id,
            'date' => '2026-08-15',
            'shift_type' => 'full_day',
            'duty_start_time' => '09:00:00',
            'duty_end_time' => '18:00:00',
            'status' => 'on_duty',
            'max_sessions_allowed' => 6,
            'remarks' => 'Assigned to Suite 101 for full-day treatments',
            'allocated_by' => $spaAdminUser->id,
        ]);

        $att2 = EmployeeAttendance::where('therapist_id', $therapist2->id)->where('date', '2026-08-15')->first();
        $therapistShift2 = TherapistAttendance::create([
            'therapist_id' => $therapist2->id,
            'employee_id' => $rahulEmp->id,
            'employee_attendance_id' => $att2?->id,
            'room_id' => $room102->id,
            'date' => '2026-08-15',
            'shift_type' => 'morning_shift',
            'duty_start_time' => '08:30:00',
            'duty_end_time' => '14:30:00',
            'status' => 'on_duty',
            'max_sessions_allowed' => 4,
            'remarks' => 'Assigned to Suite 102 for morning leg',
            'allocated_by' => $spaAdminUser->id,
        ]);

        $att3 = EmployeeAttendance::where('therapist_id', $therapist3->id)->where('date', '2026-08-16')->first();
        $therapistShift3 = TherapistAttendance::create([
            'therapist_id' => $therapist3->id,
            'employee_id' => $mayaEmp->id,
            'employee_attendance_id' => $att3?->id,
            'room_id' => $room103->id,
            'date' => '2026-08-16',
            'shift_type' => 'evening_shift',
            'duty_start_time' => '14:00:00',
            'duty_end_time' => '20:30:00',
            'status' => 'on_duty',
            'max_sessions_allowed' => 4,
            'remarks' => 'Assigned to Suite 103 for evening leg',
            'allocated_by' => $spaAdminUser->id,
        ]);

        // 23. Seed Daily Therapist Service Logs (with Room & Couple Dual Massage)
        // Session 1: Single Session in Suite 101
        $serviceLog1 = TherapistServiceLog::create([
            'therapist_attendance_id' => $therapistShift1->id,
            'employee_attendance_id' => $att1?->id,
            'room_id' => $room101->id,
            'therapist_id' => $therapist1->id,
            'is_dual_massage' => false,
            'service_id' => $volcanicStone->id,
            'client_name' => 'Kavita Roy',
            'client_phone' => '+91 98765 11223',
            'service_date' => '2026-08-15',
            'start_time' => '10:00:00',
            'end_time' => '11:30:00',
            'service_price' => 3500.00,
            'commission_rate' => 15.00,
            'commission_amount' => 525.00,
            'tip_amount' => 200.00,
            'status' => 'completed',
            'notes' => 'Single session in Lotus Sanctuary.',
            'created_by' => $spaAdminUser->id,
        ]);

        // Session 2: Single Session in Suite 102
        $serviceLog2 = TherapistServiceLog::create([
            'therapist_attendance_id' => $therapistShift2->id,
            'employee_attendance_id' => $att2?->id,
            'room_id' => $room102->id,
            'therapist_id' => $therapist2->id,
            'is_dual_massage' => false,
            'service_id' => $volcanicStone->id,
            'client_name' => 'Arun Mehra',
            'client_phone' => '+91 98765 22334',
            'service_date' => '2026-08-15',
            'start_time' => '14:00:00',
            'end_time' => '15:00:00',
            'service_price' => 2500.00,
            'commission_rate' => 12.00,
            'commission_amount' => 300.00,
            'tip_amount' => 100.00,
            'status' => 'completed',
            'notes' => 'Deep tissue pressure applied.',
            'created_by' => $spaAdminUser->id,
        ]);

        // Session 3: Couple / Dual Massage in Suite 102 (Two Therapists: Dr. Anjali & Maya)
        $serviceLog3 = TherapistServiceLog::create([
            'therapist_attendance_id' => $therapistShift1->id,
            'employee_attendance_id' => $att1?->id,
            'room_id' => $room102->id, // Couple Room with 2 massage beds
            'therapist_id' => $therapist1->id, // Primary Therapist (Dr. Anjali)
            'is_dual_massage' => true,
            'secondary_therapist_id' => $therapist3->id, // Secondary Therapist (Maya)
            'service_id' => $volcanicStone->id,
            'client_name' => 'Mr. & Mrs. Kapoor (Couple Session)',
            'client_phone' => '+91 98765 33445',
            'service_date' => '2026-08-16',
            'start_time' => '16:00:00',
            'end_time' => '18:00:00', // 120 Mins
            'service_price' => 7000.00, // 2 x 3500
            'commission_rate' => 15.00,
            'commission_amount' => 525.00, // Primary therapist commission
            'secondary_commission_amount' => 525.00, // Secondary therapist commission
            'tip_amount' => 300.00,
            'secondary_tip_amount' => 300.00,
            'status' => 'completed',
            'notes' => 'Synchronized couple massage with Jacuzzi relaxation afterwards.',
            'created_by' => $spaAdminUser->id,
        ]);

        // 22. Seed Client Payments with Proofs (Handled by Receptionist Pooja)
        // Payment 1: UPI with Transaction ID Proof
        ClientPayment::create([
            'invoice_number' => 'INV-20260815-001',
            'therapist_service_log_id' => $serviceLog1->id,
            'therapist_id' => $therapist1->id,
            'receptionist_id' => $receptionist->id,
            'service_id' => $volcanicStone->id,
            'client_name' => 'Kavita Roy',
            'client_phone' => '+91 98765 11223',
            'subtotal' => 3500.00,
            'discount_amount' => 0.00,
            'tax_amount' => 175.00,
            'total_amount' => 3675.00,
            'payment_method' => 'upi',
            'upi_transaction_id' => 'UPI-REF-992834710293',
            'upi_app' => 'Google Pay',
            'receipt_image_path' => 'receipts/upi-proof-001.jpg',
            'payment_date' => '2026-08-15 11:35:00',
            'payment_status' => 'completed',
            'notes' => 'Payment received by Receptionist Pooja at front counter.',
            'received_by' => $receptionistUser->id,
        ]);

        // Payment 2: Cash with Denomination Proof Details
        ClientPayment::create([
            'invoice_number' => 'INV-20260815-002',
            'therapist_service_log_id' => $serviceLog2->id,
            'therapist_id' => $therapist2->id,
            'receptionist_id' => $receptionist->id,
            'service_id' => $volcanicStone->id,
            'client_name' => 'Arun Mehra',
            'client_phone' => '+91 98765 22334',
            'subtotal' => 2500.00,
            'discount_amount' => 0.00,
            'tax_amount' => 125.00,
            'total_amount' => 2625.00,
            'payment_method' => 'cash',
            'cash_receipt_number' => 'CASH-REC-2026-0881',
            'cash_denomination_details' => [
                '500' => 5,
                '100' => 1,
                '20' => 1,
                '5' => 1,
            ],
            'receipt_image_path' => 'receipts/cash-slip-002.jpg',
            'payment_date' => '2026-08-15 15:05:00',
            'payment_status' => 'completed',
            'notes' => 'Cash verified and placed in reception cash drawer.',
            'received_by' => $receptionistUser->id,
        ]);

        // Payment 3: Card with POS Terminal Transaction Proof
        ClientPayment::create([
            'invoice_number' => 'INV-20260816-003',
            'therapist_service_log_id' => $serviceLog3->id,
            'therapist_id' => $therapist3->id,
            'receptionist_id' => $receptionist->id,
            'service_id' => $volcanicStone->id,
            'client_name' => 'Neha Gupta',
            'client_phone' => '+91 98765 33445',
            'subtotal' => 3500.00,
            'discount_amount' => 0.00,
            'tax_amount' => 175.00,
            'total_amount' => 3675.00,
            'payment_method' => 'card',
            'card_transaction_id' => 'HDFC-POS-778899001',
            'card_last_four' => '4242',
            'card_network' => 'Visa Platinum',
            'receipt_image_path' => 'receipts/pos-charge-slip-003.jpg',
            'payment_date' => '2026-08-16 12:35:00',
            'payment_status' => 'completed',
            'notes' => 'Swiped on HDFC POS terminal.',
            'received_by' => $receptionistUser->id,
        ]);

        // 23. Seed Inventory Categories & Items
        $invCatOils = InventoryCategory::create([
            'name' => 'Massage Oils & Aromatic Elixirs',
            'code' => 'oils',
            'description' => 'Therapeutic massage and essential oils',
        ]);
        $invCatLinens = InventoryCategory::create([
            'name' => 'Linens & Towels',
            'code' => 'linens',
            'description' => 'Cotton towels, sheets, and bathrobes',
        ]);
        $invCatToiletries = InventoryCategory::create([
            'name' => 'Bathroom & Guest Amenities',
            'code' => 'toiletries',
            'description' => 'Herbal soaps, shampoos, scrubs, and steam essences',
        ]);

        $itemOil1 = InventoryItem::create([
            'category_id' => $invCatOils->id,
            'name' => 'Ayurvedic Dhanwantharam Massage Oil (5L)',
            'sku' => 'OIL-DHAN-5L',
            'unit' => 'liters',
            'current_stock' => 18.50,
            'reorder_threshold' => 5.00,
            'unit_cost' => 1850.00,
            'supplier_name' => 'Kottakkal Arya Vaidya Sala',
        ]);

        $itemOil2 = InventoryItem::create([
            'category_id' => $invCatOils->id,
            'name' => 'Pure Jasmine Essential Oil (500ml)',
            'sku' => 'OIL-JASM-500',
            'unit' => 'bottles',
            'current_stock' => 8.00,
            'reorder_threshold' => 2.00,
            'unit_cost' => 1200.00,
            'supplier_name' => 'Aroma Botanics India',
        ]);

        $itemTowels = InventoryItem::create([
            'category_id' => $invCatLinens->id,
            'name' => 'Luxury 600 GSM Cotton Bath Towels',
            'sku' => 'LIN-TOWL-600',
            'unit' => 'pieces',
            'current_stock' => 45.00,
            'reorder_threshold' => 15.00,
            'unit_cost' => 380.00,
            'supplier_name' => 'Bombay Dyeing Commercial',
        ]);

        // Stock log purchase
        InventoryStockLog::create([
            'item_id' => $itemOil1->id,
            'transaction_type' => 'purchase_in',
            'quantity' => 20.00,
            'unit_cost' => 1850.00,
            'total_cost' => 37000.00,
            'transaction_date' => '2026-08-01',
            'reference_invoice' => 'BILL-KOT-9988',
            'remarks' => 'Monthly bulk herbal oil delivery',
            'recorded_by' => $spaAdminUser->id,
        ]);

        // 24. Seed Utility Bills & Expenses
        $expCatPower = ExpenseCategory::create([
            'name' => 'Electricity & Power Utilities',
            'code' => 'electricity',
            'description' => 'Grid electricity, generator diesel, and power maintenance',
        ]);
        $expCatWater = ExpenseCategory::create([
            'name' => 'Water & Hydro Supply',
            'code' => 'water',
            'description' => 'Commercial RO and municipal water supply',
        ]);
        $expCatAC = ExpenseCategory::create([
            'name' => 'AC & Suite Climate Maintenance',
            'code' => 'ac_maintenance',
            'description' => 'Air conditioning filter cleaning, gas refill, and HVAC servicing',
        ]);
        $expCatLaundry = ExpenseCategory::create([
            'name' => 'Commercial Laundry Services',
            'code' => 'laundry',
            'description' => 'External steam wash and dry-cleaning for heavy quilts',
        ]);

        Expense::create([
            'expense_category_id' => $expCatPower->id,
            'title' => 'August 2026 Commercial Electricity Bill',
            'amount' => 18450.00,
            'expense_date' => '2026-08-20',
            'due_date' => '2026-08-28',
            'paid_date' => '2026-08-22',
            'payment_method' => 'bank_transfer',
            'payment_reference_no' => 'EB-TXN-9988112233',
            'vendor_name' => 'State Electricity Board',
            'bill_invoice_number' => 'EB-AUG-2026-88',
            'status' => 'paid',
            'notes' => 'Paid online via NetBanking.',
            'created_by' => $spaAdminUser->id,
        ]);

        Expense::create([
            'expense_category_id' => $expCatAC->id,
            'title' => 'Quarterly AC Filter Cleaning & Gas Refill (6 Suites)',
            'amount' => 7500.00,
            'expense_date' => '2026-08-10',
            'paid_date' => '2026-08-10',
            'payment_method' => 'upi',
            'payment_reference_no' => 'UPI-COOLAIR-8822',
            'vendor_name' => 'CoolAir HVAC Solutions',
            'status' => 'paid',
            'notes' => 'All 6 treatment suite split AC units serviced.',
            'created_by' => $spaAdminUser->id,
        ]);

        Expense::create([
            'expense_category_id' => $expCatWater->id,
            'title' => 'Commercial Purified Water Tanker & Hydro Bath Refill',
            'amount' => 4200.00,
            'expense_date' => '2026-08-12',
            'paid_date' => '2026-08-12',
            'payment_method' => 'cash',
            'payment_reference_no' => 'REC-WTR-0041',
            'vendor_name' => 'AquaPure Water Services',
            'status' => 'paid',
            'notes' => 'Paid in cash at reception counter.',
            'created_by' => $spaAdminUser->id,
        ]);

        // 25. Seed Payslips & Salary Payments (Bank, UPI, Cash)
        // Bank transfer for Anjali (July 2026 Payslip: Base + Attendance + Service Commissions)
        SalaryPayment::create([
            'employee_id' => $anjaliEmp->id,
            'payment_type_id' => $paymentTypes['bank']->id,
            'payslip_number' => 'PAY-2026-07-001',
            'month' => 7,
            'year' => 2026,
            'period_start_date' => '2026-07-01',
            'period_end_date' => '2026-07-31',
            'total_working_days' => 31,
            'present_days' => 27,
            'absent_days' => 4,
            'leave_days' => 0,
            'base_salary_amount' => 35000.00,
            'attendance_adjusted_base' => 30483.87,
            'services_completed_count' => 32,
            'service_commission_amount' => 12500.00,
            'bonus_amount' => 1000.00,
            'deduction_amount' => 0.00,
            'amount' => 43983.87,
            'payment_date' => '2026-08-01',
            'deposited_date' => '2026-08-01',
            'status' => 'deposited',
            'reference_number' => 'NEFT-AXIS-982143',
            'remarks' => 'July 2026 Salary payout via Bank Transfer (Base + 32 Services Commission)',
            'created_by' => $spaAdminUser->id,
        ]);

        // UPI transfer for Receptionist (Pooja)
        SalaryPayment::create([
            'employee_id' => $receptionistEmp->id,
            'payment_type_id' => $paymentTypes['upi']->id,
            'payslip_number' => 'PAY-2026-07-002',
            'month' => 7,
            'year' => 2026,
            'period_start_date' => '2026-07-01',
            'period_end_date' => '2026-07-31',
            'total_working_days' => 31,
            'present_days' => 31,
            'absent_days' => 0,
            'leave_days' => 0,
            'base_salary_amount' => 24000.00,
            'attendance_adjusted_base' => 24000.00,
            'services_completed_count' => 0,
            'service_commission_amount' => 0.00,
            'bonus_amount' => 500.00,
            'deduction_amount' => 0.00,
            'amount' => 24500.00,
            'payment_date' => '2026-08-01',
            'deposited_date' => '2026-08-01',
            'status' => 'deposited',
            'reference_number' => 'UPI-9988223344',
            'remarks' => 'July 2026 Salary payout via UPI GPay',
            'created_by' => $spaAdminUser->id,
        ]);

        // Cash payment for Cleaner (Ramesh)
        SalaryPayment::create([
            'employee_id' => $cleaner->id,
            'payment_type_id' => $paymentTypes['cash']->id,
            'payslip_number' => 'PAY-2026-07-003',
            'month' => 7,
            'year' => 2026,
            'period_start_date' => '2026-07-01',
            'period_end_date' => '2026-07-31',
            'total_working_days' => 31,
            'present_days' => 31,
            'absent_days' => 0,
            'leave_days' => 0,
            'base_salary_amount' => 14000.00,
            'attendance_adjusted_base' => 14000.00,
            'services_completed_count' => 0,
            'service_commission_amount' => 0.00,
            'bonus_amount' => 0.00,
            'deduction_amount' => 0.00,
            'amount' => 14000.00,
            'payment_date' => '2026-08-01',
            'deposited_date' => '2026-08-01',
            'status' => 'deposited',
            'reference_number' => 'CASH-REC-0081',
            'remarks' => 'July 2026 Monthly Cash Wage at Counter',
            'created_by' => $spaAdminUser->id,
        ]);

        // Pending Payment for Laundry staff (Sunita) (August 2026 Payslip)
        SalaryPayment::create([
            'employee_id' => $laundry->id,
            'payment_type_id' => $paymentTypes['bank']->id,
            'payslip_number' => 'PAY-2026-08-001',
            'month' => 8,
            'year' => 2026,
            'period_start_date' => '2026-08-01',
            'period_end_date' => '2026-08-31',
            'total_working_days' => 31,
            'present_days' => 31,
            'absent_days' => 0,
            'leave_days' => 0,
            'base_salary_amount' => 15000.00,
            'attendance_adjusted_base' => 15000.00,
            'services_completed_count' => 0,
            'service_commission_amount' => 0.00,
            'bonus_amount' => 0.00,
            'deduction_amount' => 0.00,
            'amount' => 15000.00,
            'payment_date' => '2026-09-01',
            'deposited_date' => null,
            'status' => 'pending',
            'reference_number' => null,
            'remarks' => 'August 2026 Salary pending processing',
            'created_by' => $spaAdminUser->id,
        ]);
    }
}
