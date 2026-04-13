<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Weekly Stress Test Toggle
    |--------------------------------------------------------------------------
    |
    | Set this to false to temporarily pause scheduled stress tests without
    | removing the scheduler entry.
    |
    */
    'enabled' => env('STRESS_TEST_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Target Resolution
    |--------------------------------------------------------------------------
    |
    | Targets may be absolute URLs or relative paths. Relative paths are
    | resolved against "base_url".
    |
    */
    'base_url' => env('STRESS_TEST_BASE_URL', env('APP_URL', 'http://localhost')),
    'targets' => array_values(array_filter(array_map('trim', explode(',', (string) env(
        'STRESS_TEST_TARGETS',
        '/,/login,/register'
    ))))),

    /*
    |--------------------------------------------------------------------------
    | Load Profile
    |--------------------------------------------------------------------------
    */
    'requests_per_target' => (int) env('STRESS_TEST_REQUESTS_PER_TARGET', 200),
    'concurrency' => (int) env('STRESS_TEST_CONCURRENCY', 25),
    'timeout_seconds' => (float) env('STRESS_TEST_TIMEOUT_SECONDS', 10),
    'verify_tls' => env('STRESS_TEST_VERIFY_TLS', true),

    /*
    |--------------------------------------------------------------------------
    | Pass/Fail Thresholds
    |--------------------------------------------------------------------------
    */
    'max_error_rate_percent' => (float) env('STRESS_TEST_MAX_ERROR_RATE_PERCENT', 3),
    'max_avg_response_ms' => (float) env('STRESS_TEST_MAX_AVG_RESPONSE_MS', 2000),
    'fail_on_threshold_breach' => env('STRESS_TEST_FAIL_ON_THRESHOLD_BREACH', true),

    /*
    |--------------------------------------------------------------------------
    | Scheduling
    |--------------------------------------------------------------------------
    |
    | Day format: 0 (Sunday) to 6 (Saturday)
    |
    */
    'schedule_day' => (int) env('STRESS_TEST_SCHEDULE_DAY', 0),
    'schedule_time' => env('STRESS_TEST_SCHEDULE_TIME', '03:00'),
    'schedule_timezone' => env('STRESS_TEST_SCHEDULE_TIMEZONE', 'UTC'),

    /*
    |--------------------------------------------------------------------------
    | Reporting and Alerts
    |--------------------------------------------------------------------------
    */
    'report_disk' => env('STRESS_TEST_REPORT_DISK', 'local'),
    'report_directory' => env('STRESS_TEST_REPORT_DIRECTORY', 'stress-tests'),
    'alert_emails' => array_values(array_filter(array_map('trim', explode(',', (string) env(
        'STRESS_TEST_ALERT_EMAILS',
        ''
    ))))),
];
