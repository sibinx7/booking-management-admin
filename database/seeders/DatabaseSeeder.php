<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Client;
use App\Models\Duration;
use App\Models\Employee;
use App\Models\Language;
use App\Models\PaymentType;
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
use App\Models\TherapistAvailability;
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
        ]);

        ServiceReview::create([
            'service_id' => $volcanicStone->id,
            'author_name' => 'James Harrison',
            'rating' => 4,
            'date' => '2026-08-10',
            'comment' => 'Excellent deep muscle relaxation. The welcome foot bath was a really nice premium touch.',
            'treatment_duration' => '60 mins',
            'verified_guest' => true,
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
            'reason' => 'Completed probation and assumed Front Desk Lead duties',
            'approved_by' => $spaAdminUser->id,
            'remarks' => 'Superb coordination and customer greeting feedback.',
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

        // 20. Seed Payslips & Salary Payments (Bank, UPI, Cash)
        // Bank transfer for Anjali (July 2026 Payslip)
        SalaryPayment::create([
            'employee_id' => $anjaliEmp->id,
            'payment_type_id' => $paymentTypes['bank']->id,
            'payslip_number' => 'PAY-2026-07-001',
            'month' => 7,
            'year' => 2026,
            'period_start_date' => '2026-07-01',
            'period_end_date' => '2026-07-31',
            'amount' => 35000.00,
            'payment_date' => '2026-08-01',
            'deposited_date' => '2026-08-01',
            'status' => 'deposited',
            'reference_number' => 'NEFT-AXIS-982143',
            'remarks' => 'July 2026 Salary payout via Bank Transfer',
            'created_by' => $spaAdminUser->id,
        ]);

        // UPI transfer for Receptionist (Pooja)
        SalaryPayment::create([
            'employee_id' => $receptionist->id,
            'payment_type_id' => $paymentTypes['upi']->id,
            'payslip_number' => 'PAY-2026-07-002',
            'month' => 7,
            'year' => 2026,
            'period_start_date' => '2026-07-01',
            'period_end_date' => '2026-07-31',
            'amount' => 24000.00,
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
