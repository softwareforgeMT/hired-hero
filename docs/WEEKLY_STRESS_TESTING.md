# Weekly Portal Stress Testing

This project now includes an automated weekly stress test command:

```bash
php artisan portal:stress-test
```

The scheduler runs it weekly through `app/Console/Kernel.php`.

## Environment Variables

Add these to your `.env`:

```dotenv
STRESS_TEST_ENABLED=true
STRESS_TEST_BASE_URL=${APP_URL}
STRESS_TEST_TARGETS=/,/login,/register
STRESS_TEST_DISCOVER_ROUTES=false
STRESS_TEST_INCLUDE_API_ROUTES=false
STRESS_TEST_MAX_DISCOVERED_TARGETS=250
STRESS_TEST_EXCLUDE_ROUTE_PATTERNS=_debugbar/*,_ignition/*,sanctum/*,broadcasting/*,admin/*,user/*,api/*,oauth/*,auth/*,stripe/*,webhooks/*
STRESS_TEST_REQUESTS_PER_TARGET=200
STRESS_TEST_CONCURRENCY=25
STRESS_TEST_TIMEOUT_SECONDS=10
STRESS_TEST_VERIFY_TLS=true
STRESS_TEST_MAX_ERROR_RATE_PERCENT=3
STRESS_TEST_MAX_AVG_RESPONSE_MS=2000
STRESS_TEST_FAIL_ON_THRESHOLD_BREACH=true
STRESS_TEST_SCHEDULE_DAY=0
STRESS_TEST_SCHEDULE_TIME=03:00
STRESS_TEST_SCHEDULE_TIMEZONE=UTC
STRESS_TEST_REPORT_DISK=local
STRESS_TEST_REPORT_DIRECTORY=stress-tests
STRESS_TEST_ALERT_EMAILS=
```

After updating `.env`, run:

```bash
php artisan optimize:clear
```

## Manual Run Examples

```bash
php artisan portal:stress-test --requests=100 --concurrency=10
php artisan portal:stress-test --targets=/,/dashboard --base-url=https://your-domain.com
php artisan portal:stress-test --discover-routes --list-routes-only
php artisan portal:stress-test --discover-routes --requests=20 --concurrency=5
```

## All Routes Mode (Safe GET/HEAD)

To run stress testing against all auto-discovered safe GET/HEAD routes:

1. Enable discovery in `.env`:

```dotenv
STRESS_TEST_DISCOVER_ROUTES=true
```

2. Preview discovered routes first:

```bash
php artisan portal:stress-test --discover-routes --list-routes-only
```

3. Run a low-impact test:

```bash
php artisan portal:stress-test --discover-routes --requests=10 --concurrency=3
```

Notes:
- Dynamic routes with required path params (for example `/users/{id}`) are skipped automatically.
- Exclusion patterns from `STRESS_TEST_EXCLUDE_ROUTE_PATTERNS` are applied.
- Discovered target list is capped by `STRESS_TEST_MAX_DISCOVERED_TARGETS`.
- If specific routes are expected to fail in stress mode (example: callback-only or query-dependent routes), add them to exclusions.

## Reports

Each run writes a JSON report to:

- `storage/app/stress-tests/*.json` (by default)

The command returns a non-zero exit code when thresholds are breached (unless `STRESS_TEST_FAIL_ON_THRESHOLD_BREACH=false`).
