<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class DemoDataService
{
    /**
     * Get fake users data
     */
    public static function getFakeUsers(): array
    {
        return [
            [
                'DT_RowIndex' => 1,
                'id' => 101,
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@demo.com',
                'phone' => '+1 (555) 123-4567',
                'status' => '<span class="badge bg-success">Active</span>',
                'created_at' => Carbon::now()->subDays(45)->format('F d, Y'),
                'referralDetails' => 'Code: SARAH2024<br>Unique Users: 3<br>Total Sales: $450.00'
            ],
            [
                'DT_RowIndex' => 2,
                'id' => 102,
                'name' => 'Michael Chen',
                'email' => 'michael.chen@demo.com',
                'phone' => '+1 (555) 234-5678',
                'status' => '<span class="badge bg-success">Active</span>',
                'created_at' => Carbon::now()->subDays(30)->format('F d, Y'),
                'referralDetails' => 'Code: MICHAEL24<br>Unique Users: 5<br>Total Sales: $750.00'
            ],
            [
                'DT_RowIndex' => 3,
                'id' => 103,
                'name' => 'Emma Rodriguez',
                'email' => 'emma.rodriguez@demo.com',
                'phone' => '+1 (555) 345-6789',
                'status' => '<span class="badge bg-warning">Pending</span>',
                'created_at' => Carbon::now()->subDays(15)->format('F d, Y'),
                'referralDetails' => 'Code: EMMA2024<br>Unique Users: 1<br>Total Sales: $150.00'
            ],
            [
                'DT_RowIndex' => 4,
                'id' => 104,
                'name' => 'James Wilson',
                'email' => 'james.wilson@demo.com',
                'phone' => '+1 (555) 456-7890',
                'status' => '<span class="badge bg-success">Active</span>',
                'created_at' => Carbon::now()->subDays(60)->format('F d, Y'),
                'referralDetails' => 'Code: JAMES24<br>Unique Users: 8<br>Total Sales: $1,200.00'
            ],
            [
                'DT_RowIndex' => 5,
                'id' => 105,
                'name' => 'Lisa Anderson',
                'email' => 'lisa.anderson@demo.com',
                'phone' => '+1 (555) 567-8901',
                'status' => '<span class="badge bg-danger">Inactive</span>',
                'created_at' => Carbon::now()->subDays(90)->format('F d, Y'),
                'referralDetails' => 'Code: LISA2024<br>Unique Users: 2<br>Total Sales: $300.00'
            ],
        ];
    }

    /**
     * Get fake orders data
     */
    public static function getFakeOrders(): array
    {
        return [
            [
                'id' => 1001,
                'plan_name' => '<div class="d-flex flex-column"><span class="fw-bold">Professional Monthly</span><small class="text-muted">Payment ID: PAY-2024-001</small></div>',
                'user_name' => 'Sarah Johnson',
                'amount' => '$99.00<br><small class="text-muted">Referrer: Direct</small>',
                'expires_at' => Carbon::now()->addMonths(1)->format('Y-m-d H:i:s'),
                'activities' => '<ul><li>Mock Interviews: 10</li><li>Presentations: 5</li></ul>'
            ],
            [
                'id' => 1002,
                'plan_name' => '<div class="d-flex flex-column"><span class="fw-bold">Enterprise Annual</span><small class="text-muted">Payment ID: PAY-2024-002</small></div>',
                'user_name' => 'Michael Chen',
                'amount' => '$999.00<br><small class="text-muted">Referrer: Google Ads</small>',
                'expires_at' => Carbon::now()->addYears(1)->format('Y-m-d H:i:s'),
                'activities' => '<ul><li>Mock Interviews: Unlimited</li><li>Presentations: Unlimited</li></ul>'
            ],
            [
                'id' => 1003,
                'plan_name' => '<div class="d-flex flex-column"><span class="fw-bold">Basic Monthly</span><small class="text-muted">Payment ID: PAY-2024-003</small></div>',
                'user_name' => 'Emma Rodriguez',
                'amount' => '$49.00<br><small class="text-muted">Referrer: Referral Code</small>',
                'expires_at' => Carbon::now()->addMonths(1)->format('Y-m-d H:i:s'),
                'activities' => '<ul><li>Mock Interviews: 5</li><li>Presentations: 2</li></ul>'
            ],
            [
                'id' => 1004,
                'plan_name' => '<div class="d-flex flex-column"><span class="fw-bold">Professional Monthly</span><small class="text-muted">Payment ID: PAY-2024-004</small></div>',
                'user_name' => 'James Wilson',
                'amount' => '$99.00<br><small class="text-muted">Referrer: Affiliate Link</small>',
                'expires_at' => Carbon::now()->addDays(25)->format('Y-m-d H:i:s'),
                'activities' => '<ul><li>Mock Interviews: 10</li><li>Presentations: 5</li></ul>'
            ],
            [
                'id' => 1005,
                'plan_name' => '<div class="d-flex flex-column"><span class="fw-bold">Professional Quarterly</span><small class="text-muted">Payment ID: PAY-2024-005</small></div>',
                'user_name' => 'Lisa Anderson',
                'amount' => '$249.00<br><small class="text-muted">Referrer: Partner</small>',
                'expires_at' => Carbon::now()->addMonths(3)->format('Y-m-d H:i:s'),
                'activities' => '<ul><li>Mock Interviews: 20</li><li>Presentations: 10</li></ul>'
            ],
        ];
    }

    /**
     * Create a fake JSON data structure from order data
     */
    public static function getDemoOrdersJson(int $draw = 1, int $recordsTotal = 5): array
    {
        $orders = self::getFakeOrders();

        return [
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $orders,
        ];
    }

    /**
     * Create a fake JSON data structure from user data
     */
    public static function getDemoUsersJson(int $draw = 1, int $recordsTotal = 5): array
    {
        $users = self::getFakeUsers();

        return [
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $users,
        ];
    }
}
