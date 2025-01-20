<?php

namespace Webkul\TimeOff\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('time_off_leave_types')->delete();

        $timeOffLeaves = [
            [
                'sort' => 1,
                'color' => null,
                'company_id' => 1719,
                'max_allowed_negative' => 1,
                'creator_id' => null,
                'leave_validation_type' => 'both',
                'requires_allocation' => false,
                'employee_requests' => false,
                'allocation_validation_type' => 'hr',
                'time_type' => 'leave',
                'request_unit' => 'hour',
                'name' => 'Unpaid',
                'create_calendar_meeting' => true,
                'is_active' => true,
                'show_on_dashboard' => true,
                'unpaid' => true,
                'include_public_holidays_in_duration' => false,
                'support_document' => null,
                'allows_negative' => null,
                'created_at' => '2025-01-17 06:50:57.161221',
                'updated_at' => '2025-01-17 06:50:57.161221'
            ],
            [
                'sort' => 2,
                'color' => null,
                'company_id' => 4471,
                'max_allowed_negative' => 1,
                'creator_id' => 3192,
                'leave_validation_type' => 'both',
                'requires_allocation' => true,
                'employee_requests' => false,
                'allocation_validation_type' => 'hr',
                'time_type' => 'leave',
                'request_unit' => 'day',
                'name' => 'Training Time Off',
                'create_calendar_meeting' => true,
                'is_active' => true,
                'show_on_dashboard' => true,
                'unpaid' => false,
                'include_public_holidays_in_duration' => false,
                'support_document' => null,
                'allows_negative' => true,
                'created_at' => '2025-01-17 06:50:57.161221',
                'updated_at' => '2025-01-17 06:50:57.161221'
            ],
            [
                'sort' => 3,
                'color' => null,
                'company_id' => 1319,
                'max_allowed_negative' => 2,
                'creator_id' => null,
                'leave_validation_type' => 'both',
                'requires_allocation' => true,
                'employee_requests' => false,
                'allocation_validation_type' => 'hr',
                'time_type' => 'leave',
                'request_unit' => 'day',
                'name' => 'Paid Time Off',
                'create_calendar_meeting' => true,
                'is_active' => true,
                'show_on_dashboard' => true,
                'unpaid' => false,
                'include_public_holidays_in_duration' => false,
                'support_document' => null,
                'allows_negative' => null,
                'created_at' => '2025-01-17 06:50:57.161221',
                'updated_at' => '2025-01-20 07:13:43.336236'
            ],
            [
                'sort' => 4,
                'color' => null,
                'company_id' => 1519,
                'max_allowed_negative' => 2,
                'creator_id' => null,
                'leave_validation_type' => 'both',
                'requires_allocation' => false,
                'employee_requests' => false,
                'allocation_validation_type' => 'hr',
                'time_type' => 'leave',
                'request_unit' => 'day',
                'name' => 'Sick Time Off',
                'create_calendar_meeting' => true,
                'is_active' => true,
                'show_on_dashboard' => true,
                'unpaid' => false,
                'include_public_holidays_in_duration' => true,
                'support_document' => true,
                'allows_negative' => null,
                'created_at' => '2025-01-17 06:50:57.161221',
                'updated_at' => '2025-01-20 07:48:33.376986'
            ],
            [
                'sort' => 5,
                'color' => null,
                'company_id' => 4321,
                'max_allowed_negative' => 2,
                'creator_id' => null,
                'leave_validation_type' => 'manager',
                'requires_allocation' => true,
                'employee_requests' => false,
                'allocation_validation_type' => 'hr',
                'time_type' => 'leave',
                'request_unit' => 'day',
                'name' => 'Parental Leaves',
                'create_calendar_meeting' => true,
                'is_active' => true,
                'show_on_dashboard' => true,
                'unpaid' => false,
                'include_public_holidays_in_duration' => false,
                'support_document' => null,
                'allows_negative' => true,
                'created_at' => '2025-01-17 06:50:57.161221',
                'updated_at' => '2025-01-20 08:12:08.600025'
            ],
            [
                'sort' => 6,
                'color' => null,
                'company_id' => 1319,
                'max_allowed_negative' => 2,
                'creator_id' => null,
                'leave_validation_type' => 'manager',
                'requires_allocation' => true,
                'employee_requests' => true,
                'allocation_validation_type' => 'hr',
                'time_type' => 'leave',
                'request_unit' => 'day',
                'name' => 'Compensatory Days test',
                'create_calendar_meeting' => true,
                'is_active' => true,
                'show_on_dashboard' => true,
                'unpaid' => false,
                'include_public_holidays_in_duration' => true,
                'support_document' => true,
                'allows_negative' => true,
                'created_at' => '2025-01-17 06:50:57.161221',
                'updated_at' => '2025-01-20 08:12:56.381555'
            ],
        ];

        DB::table('time_off_leave_types')->insert($timeOffLeaves);
    }
}
