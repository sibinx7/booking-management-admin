<?php

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
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('all individual model factories work correctly', function () {
    expect(UserRole::factory()->client()->create())->toBeInstanceOf(UserRole::class);
    expect(UserRole::factory()->employee()->create())->toBeInstanceOf(UserRole::class);
    expect(UserRole::factory()->admin()->create())->toBeInstanceOf(UserRole::class);

    $defaultUser = User::factory()->create();
    expect($defaultUser)->toBeInstanceOf(User::class);
    expect($defaultUser->user_role_id)->toBe(1);
    expect($defaultUser->role->code)->toBe('client');
    expect($defaultUser->isClient())->toBeTrue();

    expect(User::factory()->client()->create())->toBeInstanceOf(User::class);
    expect(User::factory()->employee()->create())->toBeInstanceOf(User::class);
    expect(User::factory()->admin()->create())->toBeInstanceOf(User::class);

    expect(Admin::factory()->superAdmin()->create())->toBeInstanceOf(Admin::class);
    expect(Admin::factory()->spaAdmin()->create())->toBeInstanceOf(Admin::class);
    expect(Client::factory()->create())->toBeInstanceOf(Client::class);
    
    expect(SalaryGrade::factory()->create())->toBeInstanceOf(SalaryGrade::class);

    expect(PaymentType::factory()->bank()->create())->toBeInstanceOf(PaymentType::class);
    expect(PaymentType::factory()->upi()->create())->toBeInstanceOf(PaymentType::class);
    expect(PaymentType::factory()->cash()->create())->toBeInstanceOf(PaymentType::class);

    expect(Employee::factory()->therapist()->regular()->active()->create())->toBeInstanceOf(Employee::class);
    expect(Employee::factory()->receptionist()->temporary()->resigned()->create())->toBeInstanceOf(Employee::class);
    expect(Employee::factory()->cleaner()->terminated()->create())->toBeInstanceOf(Employee::class);
    expect(Employee::factory()->laundry()->guest()->onLeave()->create())->toBeInstanceOf(Employee::class);

    expect(SalaryPayment::factory()->deposited()->create())->toBeInstanceOf(SalaryPayment::class);
    expect(SalaryPayment::factory()->pending()->create())->toBeInstanceOf(SalaryPayment::class);

    expect(SalaryIncrement::factory()->create())->toBeInstanceOf(SalaryIncrement::class);

    expect(Duration::factory()->create())->toBeInstanceOf(Duration::class);
    expect(Language::factory()->create())->toBeInstanceOf(Language::class);
    expect(Skill::factory()->create())->toBeInstanceOf(Skill::class);
    expect(Speciality::factory()->create())->toBeInstanceOf(Speciality::class);

    $service = Service::factory()->create();
    expect($service)->toBeInstanceOf(Service::class);
    expect($service->images)->toBeArray();
    expect($service->ritual_steps)->toBeArray();

    expect(ServiceDuration::factory()->create())->toBeInstanceOf(ServiceDuration::class);
    expect(ServiceHighlight::factory()->create())->toBeInstanceOf(ServiceHighlight::class);
    expect(ServiceReview::factory()->create())->toBeInstanceOf(ServiceReview::class);
    expect(ServiceSpecialOffer::factory()->create())->toBeInstanceOf(ServiceSpecialOffer::class);
    expect(Therapist::factory()->create())->toBeInstanceOf(Therapist::class);
    expect(TherapistAvailability::factory()->create())->toBeInstanceOf(TherapistAvailability::class);
});

test('model relationships work properly', function () {
    // 1. User, UserRole & Admin
    $adminRole = UserRole::factory()->admin()->create();
    $adminUser = User::factory()->create(['user_role_id' => $adminRole->id]);
    $admin = Admin::factory()->superAdmin()->create(['user_id' => $adminUser->id]);
    expect($adminUser->admin->id)->toBe($admin->id);
    expect($adminUser->role->code)->toBe('admin');
    expect($adminUser->isSuperAdmin())->toBeTrue();
    expect($adminUser->isAdmin())->toBeTrue();

    // 2. User & Client
    $clientRole = UserRole::factory()->client()->create();
    $clientUser = User::factory()->create(['user_role_id' => $clientRole->id]);
    $client = Client::factory()->create(['user_id' => $clientUser->id]);
    expect($clientUser->client->id)->toBe($client->id);
    expect($clientUser->role->code)->toBe('client');
    expect($clientUser->isClient())->toBeTrue();

    // 3. User, Employee, Salary Grade & Therapist
    $gradeA = SalaryGrade::factory()->create(['name' => 'Grade A', 'code' => 'GRADE-A']);
    $empRole = UserRole::factory()->employee()->create();
    $empUser = User::factory()->create([
        'user_role_id' => $empRole->id,
        'name' => 'Maya Sharma',
        'email' => 'maya.test@example.com',
    ]);
    $employee = Employee::factory()->therapist()->regular()->active()->create([
        'user_id' => $empUser->id,
        'salary_grade_id' => $gradeA->id,
        'gender' => 'female',
        'dob' => '1995-05-10',
        'phone_number' => '+91 99999 88888',
        'bank_name' => 'HDFC Bank',
        'bank_account_number' => '50100123456789',
        'bank_ifsc' => 'HDFC0001234',
        'upi_id' => 'maya@okhdfc',
        'base_salary' => 35000.00,
    ]);
    expect($empUser->employee->id)->toBe($employee->id);
    expect($empUser->isEmployee())->toBeTrue();
    expect($employee->salaryGrade->id)->toBe($gradeA->id);
    expect($employee->name)->toBe('Maya Sharma');
    expect($employee->email)->toBe('maya.test@example.com');
    expect($employee->age)->toBeGreaterThan(18);

    $therapist = Therapist::factory()->create([
        'employee_id' => $employee->id,
        'display_name' => 'Master Maya',
        'profile_pic' => 'images/therapists/maya-showcase.jpg',
    ]);
    expect($employee->therapist->id)->toBe($therapist->id);
    expect($therapist->employee->id)->toBe($employee->id);
    expect($therapist->name)->toBe('Master Maya');
    expect($therapist->profile_pic)->toBe('images/therapists/maya-showcase.jpg');

    // 4. Employee & Salary Increments
    $increment = SalaryIncrement::create([
        'employee_id' => $employee->id,
        'salary_grade_id' => $gradeA->id,
        'previous_salary' => 30000.00,
        'increment_amount' => 5000.00,
        'new_salary' => 35000.00,
        'increment_percentage' => 16.67,
        'effective_date' => '2025-04-01',
        'reason' => 'Annual Performance Appraisal',
        'approved_by' => $adminUser->id,
    ]);
    expect($employee->salaryIncrements)->toHaveCount(1);
    expect($increment->employee->id)->toBe($employee->id);
    expect($increment->salaryGrade->id)->toBe($gradeA->id);
    expect($increment->approver->id)->toBe($adminUser->id);

    // 5. Employee & Payslips / Salary Payments
    $paymentType = PaymentType::factory()->bank()->create();
    $salaryPayment = SalaryPayment::factory()->deposited()->create([
        'employee_id' => $employee->id,
        'payment_type_id' => $paymentType->id,
        'payslip_number' => 'PAY-2026-07-001',
        'month' => 7,
        'year' => 2026,
        'period_start_date' => '2026-07-01',
        'period_end_date' => '2026-07-31',
        'amount' => 35000.00,
        'payment_date' => '2026-08-01',
        'deposited_date' => '2026-08-01',
        'created_by' => $adminUser->id,
    ]);
    expect($employee->salaryPayments)->toHaveCount(1);
    expect($salaryPayment->employee->id)->toBe($employee->id);
    expect($salaryPayment->paymentType->id)->toBe($paymentType->id);
    expect($salaryPayment->creator->id)->toBe($adminUser->id);
    expect($salaryPayment->month_name)->toBe('July');

    // 6. Service, Durations, Skills, Languages, Specialities
    $service = Service::factory()->create();
    $duration = Duration::factory()->create();
    $service->durations()->attach($duration->id, [
        'price' => 150.00,
        'label' => 'Standard',
        'title' => 'Standard Package',
    ]);
    expect($service->durations)->toHaveCount(1);
    expect($service->durations->first()->pivot->price)->toEqual(150.00);

    $skill = Skill::factory()->create();
    $lang = Language::factory()->create();
    $spec = Speciality::factory()->create();

    $therapist->skills()->attach($skill->id);
    $therapist->languages()->attach($lang->id);
    $therapist->specialities()->attach($spec->id, ['extra_charge' => 25.00]);
    $therapist->services()->attach($service->id);

    expect($therapist->skills)->toHaveCount(1);
    expect($therapist->languages)->toHaveCount(1);
    expect($therapist->specialities)->toHaveCount(1);
    expect($therapist->services)->toHaveCount(1);
});

test('employee status and salary scopes work as expected', function () {
    Employee::factory()->therapist()->regular()->active()->create();
    Employee::factory()->receptionist()->temporary()->resigned()->create(['exit_reason' => 'Left for higher studies']);
    Employee::factory()->cleaner()->guest()->terminated()->create(['exit_reason' => 'Repeated absence']);
    Employee::factory()->laundry()->regular()->onLeave()->create();

    expect(Employee::active()->count())->toBe(1);
    expect(Employee::resigned()->count())->toBe(1);
    expect(Employee::terminated()->count())->toBe(1);
    expect(Employee::onLeave()->count())->toBe(1);

    expect(Employee::resigned()->first()->exit_reason)->toBe('Left for higher studies');
    expect(Employee::terminated()->first()->exit_reason)->toBe('Repeated absence');
});

test('DatabaseSeeder executes successfully without errors', function () {
    $this->seed(DatabaseSeeder::class);

    expect(UserRole::count())->toBe(3);
    expect(SalaryGrade::count())->toBe(3);
    expect(PaymentType::count())->toBe(3);
    expect(Employee::count())->toBe(7); // 6 active + 1 resigned
    expect(SalaryIncrement::count())->toBe(2);
    expect(SalaryPayment::count())->toBe(4);
    expect(Therapist::count())->toBe(3);
    expect(Service::where('slug', 'volcanic-stone-ritual')->exists())->toBeTrue();
});
