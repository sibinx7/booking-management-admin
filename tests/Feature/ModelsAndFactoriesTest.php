<?php

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
use App\Services\SalaryCalculatorService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('all individual model factories work correctly', function () {
    expect(UserRole::firstOrCreate(['code' => 'client'], ['name' => 'Client', 'description' => 'Client profile', 'is_active' => true]))->toBeInstanceOf(UserRole::class);
    expect(UserRole::firstOrCreate(['code' => 'employee'], ['name' => 'Employee', 'description' => 'Employee profile', 'is_active' => true]))->toBeInstanceOf(UserRole::class);
    expect(UserRole::firstOrCreate(['code' => 'admin'], ['name' => 'Admin', 'description' => 'Admin profile', 'is_active' => true]))->toBeInstanceOf(UserRole::class);

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

    expect(PaymentType::firstOrCreate(['code' => 'bank'], ['name' => 'Bank Transfer', 'description' => 'Direct transfer', 'is_active' => true]))->toBeInstanceOf(PaymentType::class);
    expect(PaymentType::firstOrCreate(['code' => 'upi'], ['name' => 'UPI', 'description' => 'UPI transfer', 'is_active' => true]))->toBeInstanceOf(PaymentType::class);
    expect(PaymentType::firstOrCreate(['code' => 'cash'], ['name' => 'Cash', 'description' => 'Cash in hand', 'is_active' => true]))->toBeInstanceOf(PaymentType::class);

    expect(Employee::factory()->therapist()->regular()->active()->create())->toBeInstanceOf(Employee::class);
    expect(Employee::factory()->receptionist()->temporary()->resigned()->create())->toBeInstanceOf(Employee::class);
    expect(Employee::factory()->cleaner()->terminated()->create())->toBeInstanceOf(Employee::class);
    expect(Employee::factory()->laundry()->guest()->onLeave()->create())->toBeInstanceOf(Employee::class);

    expect(Receptionist::factory()->create())->toBeInstanceOf(Receptionist::class);
    expect(RoomHighlight::factory()->create())->toBeInstanceOf(RoomHighlight::class);
    expect(RoomType::factory()->create())->toBeInstanceOf(RoomType::class);
    expect(Room::factory()->create())->toBeInstanceOf(Room::class);
    expect(Room::factory()->coupleSuite()->create())->toBeInstanceOf(Room::class);
    expect(EmployeeAttendance::factory()->create())->toBeInstanceOf(EmployeeAttendance::class);
    expect(TherapistAttendance::factory()->create())->toBeInstanceOf(TherapistAttendance::class);
    expect(TherapistServiceLog::factory()->create())->toBeInstanceOf(TherapistServiceLog::class);
    expect(ClientPayment::factory()->create())->toBeInstanceOf(ClientPayment::class);
    expect(ClientPayment::factory()->cash()->create())->toBeInstanceOf(ClientPayment::class);
    expect(ClientPayment::factory()->card()->create())->toBeInstanceOf(ClientPayment::class);

    expect(InventoryCategory::factory()->create())->toBeInstanceOf(InventoryCategory::class);
    expect(InventoryItem::factory()->create())->toBeInstanceOf(InventoryItem::class);
    expect(InventoryStockLog::factory()->create())->toBeInstanceOf(InventoryStockLog::class);

    expect(ExpenseCategory::factory()->create())->toBeInstanceOf(ExpenseCategory::class);
    expect(Expense::factory()->create())->toBeInstanceOf(Expense::class);

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
    $adminRole = UserRole::firstOrCreate(['code' => 'admin'], ['name' => 'Admin', 'description' => 'Admin profile', 'is_active' => true]);
    $adminUser = User::factory()->create(['user_role_id' => $adminRole->id]);
    $admin = Admin::factory()->superAdmin()->create(['user_id' => $adminUser->id]);
    expect($adminUser->admin->id)->toBe($admin->id);
    expect($adminUser->role->code)->toBe('admin');
    expect($adminUser->isSuperAdmin())->toBeTrue();
    expect($adminUser->isAdmin())->toBeTrue();

    // 2. User & Client
    $clientRole = UserRole::firstOrCreate(['code' => 'client'], ['name' => 'Client', 'description' => 'Client profile', 'is_active' => true]);
    $clientUser = User::factory()->create(['user_role_id' => $clientRole->id]);
    $client = Client::factory()->create(['user_id' => $clientUser->id]);
    expect($clientUser->client->id)->toBe($client->id);
    expect($clientUser->role->code)->toBe('client');
    expect($clientUser->isClient())->toBeTrue();

    // 3. User, Employee, Salary Grade & Therapist
    $gradeA = SalaryGrade::factory()->create(['name' => 'Grade A', 'code' => 'GRADE-A']);
    $empRole = UserRole::firstOrCreate(['code' => 'employee'], ['name' => 'Employee', 'description' => 'Employee profile', 'is_active' => true]);
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

    // 4. Employee Attendance & Therapist Link
    $attendance = EmployeeAttendance::factory()->forTherapist($therapist)->create([
        'date' => '2026-08-01',
        'status' => 'present',
        'work_hours' => 8.50,
    ]);
    expect($therapist->attendances)->toHaveCount(1);
    expect($attendance->therapist->id)->toBe($therapist->id);
    expect($attendance->employee->id)->toBe($employee->id);

    // 5. Receptionist & Employee
    $recUser = User::factory()->create(['user_role_id' => $empRole->id, 'name' => 'Pooja']);
    $recEmp = Employee::factory()->receptionist()->create(['user_id' => $recUser->id, 'base_salary' => 24000.00]);
    $receptionist = Receptionist::factory()->create(['employee_id' => $recEmp->id, 'counter_number' => 'Counter 1']);
    expect($recEmp->receptionist->id)->toBe($receptionist->id);
    expect($receptionist->employee->id)->toBe($recEmp->id);
    expect($receptionist->name)->toBe('Pooja');

    // 6. Master Room Highlights, Room Type, Dimensions & Photos
    $hlBeds = RoomHighlight::create([
        'name' => 'Two Side-by-Side Intimate Massage Beds',
        'code' => 'two_intimate_beds',
        'icon' => 'bed',
        'image' => 'https://example.com/highlights/beds.jpg',
        'category' => 'sensual_rituals',
    ]);
    $hlJacuzzi = RoomHighlight::create([
        'name' => 'Private Couple Rose Petal Jacuzzi',
        'code' => 'couple_rose_jacuzzi',
        'icon' => 'bath',
        'image' => 'https://example.com/highlights/jacuzzi.jpg',
        'category' => 'wellness',
    ]);
    $hlSauna = RoomHighlight::create([
        'name' => 'Private Cedarwood Sauna Circuit',
        'code' => 'cedarwood_sauna_circuit',
        'icon' => 'thermometer-sun',
        'image' => 'https://example.com/highlights/sauna.jpg',
        'category' => 'wellness',
    ]);

    $coupleRoomType = RoomType::create([
        'name' => 'Couple Suite',
        'code' => 'couple',
        'description' => 'Dedicated couples sanctuary with twin beds',
    ]);
    $coupleRoomType->highlights()->attach([$hlBeds->id, $hlJacuzzi->id]);
    expect($coupleRoomType->highlights)->toHaveCount(2);

    $coupleRoom = Room::create([
        'room_type_id' => $coupleRoomType->id,
        'room_number' => 'Suite 102',
        'name' => 'Mahogany Couples Sanctuary',
        'bed_count' => 2,
        'height_feet' => 12.00,
        'length_feet' => 22.00,
        'width_feet' => 16.00,
        'area_sqft' => 352.00,
        'featured_image' => 'https://example.com/rooms/suite-102.jpg',
        'gallery_images' => ['https://example.com/rooms/suite-102-1.jpg', 'https://example.com/rooms/suite-102-2.jpg'],
        'is_highlighted' => true,
        'highlight_tag' => 'Signature Couple Suite',
        'has_two_massage_beds' => true,
        'has_jacuzzi' => true,
        'has_sauna' => true,
        'has_steam_bath' => true,
        'has_shower' => true,
        'has_toilet' => true,
        'has_ac' => true,
        'has_candle_light' => true,
    ]);
    $coupleRoom->syncDefaultHighlightsFromRoomType();
    // Add extra custom highlight to this room
    $coupleRoom->highlights()->attach($hlSauna->id);

    expect($coupleRoom->roomType->code)->toBe('couple');
    expect($coupleRoom->is_highlighted)->toBeTrue();
    expect($coupleRoom->highlight_tag)->toBe('Signature Couple Suite');
    expect($coupleRoom->height_feet)->toEqual(12.00);
    expect($coupleRoom->area_sqft)->toEqual(352.00);
    expect($coupleRoom->gallery_images)->toHaveCount(2);
    expect($coupleRoom->highlights)->toHaveCount(3); // 2 default + 1 custom extra
    expect($coupleRoom->highlights->where('code', 'two_intimate_beds')->first()->image)->toBe('https://example.com/highlights/beds.jpg');
    expect($coupleRoom->highlights->where('code', 'cedarwood_sauna_circuit')->first()->image)->toBe('https://example.com/highlights/sauna.jpg');
    expect($coupleRoom->has_two_massage_beds)->toBeTrue();
    expect($coupleRoom->has_jacuzzi)->toBeTrue();
    expect($coupleRoom->feature_badges)->toContain('Couple / Dual Massage Beds', 'Jacuzzi', 'Sauna');
    expect(Room::coupleCapable()->count())->toBe(1);
    expect(Room::highlighted()->count())->toBe(1);

    // 7. Therapist Duty Attendance, Room Allocation & Dual / Couple Massage
    $therapistShift = TherapistAttendance::create([
        'therapist_id' => $therapist->id,
        'employee_id' => $employee->id,
        'employee_attendance_id' => $attendance->id,
        'room_id' => $coupleRoom->id,
        'date' => '2026-08-01',
        'shift_type' => 'morning_shift',
        'status' => 'on_duty',
    ]);
    expect($therapist->therapistAttendances)->toHaveCount(1);
    expect($therapistShift->room->id)->toBe($coupleRoom->id);
    expect($therapistShift->allocated_suite)->toBe('Suite 102 - Mahogany Couples Sanctuary');

    // Create 2nd Therapist for Couple/Dual massage session
    $secEmpUser = User::factory()->create(['user_role_id' => $empRole->id]);
    $secEmp = Employee::factory()->therapist()->create(['user_id' => $secEmpUser->id]);
    $secTherapist = Therapist::factory()->create(['employee_id' => $secEmp->id]);

    $service = Service::factory()->create(['name' => 'Couples Volcanic Hot Stone Ritual']);
    
    // Couple / Dual Massage with 2 Therapists in Couple Room
    $serviceLog = TherapistServiceLog::create([
        'therapist_attendance_id' => $therapistShift->id,
        'employee_attendance_id' => $attendance->id,
        'room_id' => $coupleRoom->id,
        'therapist_id' => $therapist->id,
        'is_dual_massage' => true,
        'secondary_therapist_id' => $secTherapist->id,
        'service_id' => $service->id,
        'client_name' => 'Mr. & Mrs. Kapoor',
        'service_date' => '2026-08-01',
        'service_price' => 6000.00,
        'commission_rate' => 15.00,
        'commission_amount' => 450.00,
        'secondary_commission_amount' => 450.00,
        'tip_amount' => 200.00,
        'secondary_tip_amount' => 200.00,
        'status' => 'completed',
    ]);
    expect($serviceLog->is_dual_massage)->toBeTrue();
    expect($serviceLog->secondaryTherapist->id)->toBe($secTherapist->id);
    expect($serviceLog->room->id)->toBe($coupleRoom->id);
    expect($serviceLog->allocated_suite)->toBe('Suite 102 - Mahogany Couples Sanctuary');
    expect($serviceLog->room->id)->toBe($coupleRoom->id);
    expect($serviceLog->secondary_commission_amount)->toEqual(450.00);
    expect($therapist->serviceLogs)->toHaveCount(1);
    expect($coupleRoom->serviceLogs)->toHaveCount(1);
    expect(TherapistServiceLog::dualMassage()->count())->toBe(1);

    // Client Payment collected by Receptionist
    $payment = ClientPayment::create([
        'invoice_number' => 'INV-20260801-001',
        'therapist_service_log_id' => $serviceLog->id,
        'therapist_id' => $therapist->id,
        'receptionist_id' => $receptionist->id,
        'service_id' => $service->id,
        'client_name' => 'Rahul Khanna',
        'subtotal' => 3000.00,
        'total_amount' => 3150.00,
        'payment_method' => 'cash',
        'cash_receipt_number' => 'REC-001',
        'cash_denomination_details' => ['500' => 6, '100' => 1, '50' => 1],
        'payment_date' => now(),
        'payment_status' => 'completed',
    ]);
    expect($receptionist->clientPayments)->toHaveCount(1);
    expect($payment->receptionist->id)->toBe($receptionist->id);
    expect($payment->therapist->id)->toBe($therapist->id);
    expect($payment->cash_denomination_details)->toBeArray();

    // 7. Inventory & Stock Logs
    $oilCat = InventoryCategory::create(['name' => 'Oils', 'code' => 'oils-test']);
    $oilItem = InventoryItem::create([
        'category_id' => $oilCat->id,
        'name' => 'Jasmine Oil',
        'sku' => 'JAS-01',
        'unit' => 'liters',
        'current_stock' => 15.00,
        'reorder_threshold' => 5.00,
        'unit_cost' => 500.00,
    ]);
    $stockLog = InventoryStockLog::create([
        'item_id' => $oilItem->id,
        'transaction_type' => 'purchase_in',
        'quantity' => 10.00,
        'unit_cost' => 500.00,
        'total_cost' => 5000.00,
        'transaction_date' => '2026-08-01',
    ]);
    expect($oilItem->stockLogs)->toHaveCount(1);
    expect($oilCat->items)->toHaveCount(1);

    // 8. Expense & Utilities
    $powerCat = ExpenseCategory::create(['name' => 'Power', 'code' => 'power-test']);
    $bill = Expense::create([
        'expense_category_id' => $powerCat->id,
        'title' => 'Electricity Bill',
        'amount' => 12000.00,
        'expense_date' => '2026-08-01',
        'payment_method' => 'bank_transfer',
        'status' => 'paid',
    ]);
    expect($powerCat->expenses)->toHaveCount(1);
    expect($bill->category->id)->toBe($powerCat->id);
});

test('SalaryCalculatorService computes attendance and therapist commission accurately', function () {
    $empRole = UserRole::firstOrCreate(['code' => 'employee'], ['name' => 'Employee', 'description' => 'Employee profile', 'is_active' => true]);
    
    // Regular receptionist calculation
    $recUser = User::factory()->create(['user_role_id' => $empRole->id]);
    $recEmp = Employee::factory()->receptionist()->create([
        'user_id' => $recUser->id,
        'base_salary' => 30000.00,
    ]);
    
    // 20 days present out of 30
    for ($i = 1; $i <= 20; $i++) {
        EmployeeAttendance::create([
            'employee_id' => $recEmp->id,
            'date' => sprintf('2026-08-%02d', $i),
            'status' => 'present',
        ]);
    }
    for ($i = 21; $i <= 30; $i++) {
        EmployeeAttendance::create([
            'employee_id' => $recEmp->id,
            'date' => sprintf('2026-08-%02d', $i),
            'status' => 'absent',
        ]);
    }

    $calculator = new SalaryCalculatorService();
    $recResult = $calculator->compute($recEmp, 2026, 8, 1000.00, 500.00, 30);
    // Base 30000 * (20/30) = 20000 + 1000 bonus - 500 deduction = 20500
    expect($recResult['attendance_adjusted_base'])->toEqual(20000.00);
    expect($recResult['services_completed_count'])->toBe(0);
    expect($recResult['service_commission_amount'])->toEqual(0.00);
    expect($recResult['net_salary'])->toEqual(20500.00);

    // Therapist calculation with completed services
    $therUser = User::factory()->create(['user_role_id' => $empRole->id]);
    $therEmp = Employee::factory()->therapist()->create([
        'user_id' => $therUser->id,
        'base_salary' => 20000.00,
    ]);
    $therapist = Therapist::factory()->create(['employee_id' => $therEmp->id]);
    $service = Service::factory()->create();

    // 25 days present out of 30
    for ($i = 1; $i <= 25; $i++) {
        EmployeeAttendance::create([
            'employee_id' => $therEmp->id,
            'therapist_id' => $therapist->id,
            'date' => sprintf('2026-08-%02d', $i),
            'status' => 'present',
        ]);
    }
    for ($i = 26; $i <= 30; $i++) {
        EmployeeAttendance::create([
            'employee_id' => $therEmp->id,
            'therapist_id' => $therapist->id,
            'date' => sprintf('2026-08-%02d', $i),
            'status' => 'absent',
        ]);
    }

    // 10 completed services with 500 commission each = 5000 commission
    for ($j = 1; $j <= 10; $j++) {
        TherapistServiceLog::create([
            'therapist_id' => $therapist->id,
            'service_id' => $service->id,
            'client_name' => 'Guest ' . $j,
            'service_date' => sprintf('2026-08-%02d', $j),
            'service_price' => 3000.00,
            'commission_rate' => 15.00,
            'commission_amount' => 500.00,
            'status' => 'completed',
        ]);
    }

    $therResult = $calculator->compute($therEmp, 2026, 8, 0.00, 0.00, 30);
    // Base 20000 * (25/30) = 16666.67 + 5000 commission = 21666.67
    expect($therResult['present_days'])->toBe(25);
    expect($therResult['services_completed_count'])->toBe(10);
    expect($therResult['service_commission_amount'])->toEqual(5000.00);
    expect($therResult['attendance_adjusted_base'])->toEqual(16666.67);
    expect($therResult['net_salary'])->toEqual(21666.67);
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

test('TherapistScheduleService calculates full/half day attendance and live massage availability with free countdown', function () {
    $empRole = UserRole::firstOrCreate(['code' => 'employee'], ['name' => 'Employee', 'description' => 'Employee profile', 'is_active' => true]);
    $scheduleService = app(\App\Services\TherapistScheduleService::class);

    // 1. Therapist marked present full day and currently free
    $emp1 = Employee::factory()->therapist()->create(['user_id' => User::factory()->create(['user_role_id' => $empRole->id])]);
    $therapist1 = Therapist::factory()->create(['employee_id' => $emp1->id, 'is_online' => true]);
    EmployeeAttendance::create([
        'employee_id' => $emp1->id,
        'therapist_id' => $therapist1->id,
        'date' => '2026-08-25',
        'status' => 'present',
    ]);

    $status1 = $scheduleService->getLiveStatus($therapist1, \Carbon\Carbon::parse('2026-08-25 10:00:00'));
    expect($status1['is_present_today'])->toBeTrue();
    expect($status1['attendance_type'])->toBe('present');
    expect($status1['availability_status'])->toBe('available');
    expect($status1['status_badge'])->toBe('Available Now');

    // 2. Therapist marked present half day and currently busy in a 120-minute massage
    $emp2 = Employee::factory()->therapist()->create(['user_id' => User::factory()->create(['user_role_id' => $empRole->id])]);
    $therapist2 = Therapist::factory()->create(['employee_id' => $emp2->id, 'is_online' => true]);
    $service = Service::factory()->create(['name' => 'Signature Deep Relaxation 120 Min']);
    EmployeeAttendance::create([
        'employee_id' => $emp2->id,
        'therapist_id' => $therapist2->id,
        'date' => '2026-08-25',
        'status' => 'half_day',
    ]);

    TherapistServiceLog::create([
        'therapist_id' => $therapist2->id,
        'service_id' => $service->id,
        'client_name' => 'Mr. Vikram',
        'service_date' => '2026-08-25',
        'start_time' => '14:00:00',
        'end_time' => '16:00:00', // 120 minute session
        'service_price' => 4500.00,
        'commission_amount' => 675.00,
        'status' => 'completed',
    ]);

    // Check status at 14:30 (middle of the 120-minute session)
    $status2 = $scheduleService->getLiveStatus($therapist2, \Carbon\Carbon::parse('2026-08-25 14:30:00'));
    expect($status2['is_present_today'])->toBeTrue();
    expect($status2['attendance_type'])->toBe('half_day');
    expect($status2['availability_status'])->toBe('in_service');
    expect($status2['next_available_at'])->toBe('16:10:00'); // 16:00 + 10 min turnaround
    expect($status2['remaining_minutes_in_service'])->toBe(100); // from 14:30 to 16:10 is 100 mins

    // 3. Therapist off duty / absent today
    $emp3 = Employee::factory()->therapist()->create(['user_id' => User::factory()->create(['user_role_id' => $empRole->id])]);
    $therapist3 = Therapist::factory()->create(['employee_id' => $emp3->id, 'is_online' => true]);
    EmployeeAttendance::create([
        'employee_id' => $emp3->id,
        'therapist_id' => $therapist3->id,
        'date' => '2026-08-25',
        'status' => 'on_leave',
    ]);

    $status3 = $scheduleService->getLiveStatus($therapist3, \Carbon\Carbon::parse('2026-08-25 10:00:00'));
    expect($status3['is_present_today'])->toBeFalse();
    expect($status3['availability_status'])->toBe('off_duty');

    // 4. Daily Calendar Overview
    $overview = $scheduleService->getDailyCalendarOverview('2026-08-25', \Carbon\Carbon::parse('2026-08-25 14:30:00'));
    expect($overview['present_count'])->toBeGreaterThanOrEqual(2);
    expect($overview['in_service_count'])->toBeGreaterThanOrEqual(1);
});

test('DatabaseSeeder executes successfully without errors', function () {
    $this->seed(DatabaseSeeder::class);

    expect(UserRole::count())->toBe(3);
    expect(SalaryGrade::count())->toBe(3);
    expect(PaymentType::count())->toBe(3);
    expect(Employee::count())->toBe(7); // 6 active + 1 resigned
    expect(Therapist::count())->toBe(3);
    expect(Receptionist::count())->toBe(1);
    expect(RoomHighlight::count())->toBeGreaterThanOrEqual(10);
    expect(RoomType::count())->toBe(5);
    expect(Room::count())->toBe(3);
    expect(EmployeeAttendance::count())->toBeGreaterThan(100);
    expect(TherapistAttendance::count())->toBe(3);
    expect(TherapistServiceLog::count())->toBe(3);
    expect(ClientPayment::count())->toBe(3);
    expect(InventoryCategory::count())->toBe(3);
    expect(InventoryItem::count())->toBe(3);
    expect(ExpenseCategory::count())->toBe(4);
    expect(Expense::count())->toBe(3);
    expect(SalaryPayment::count())->toBe(4);
    expect(Service::where('slug', 'volcanic-stone-ritual')->exists())->toBeTrue();
});

