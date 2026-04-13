# Stress Testing Plan (All Routes + Weekly Automation)

## Introduction

As part of the weekly performance assurance requirement, HiredHero now includes automated stress testing to validate that core portal routes remain stable and responsive under load.

The goal is to:

1. Detect crash-risk and endpoint degradation early.
2. Measure response-time behavior as load increases.
3. Produce auditable reports each run.
4. Run this validation automatically every week.

## Tool Used for Stress Testing

Stress testing is executed through:

```bash
php artisan portal:stress-test
```

The command uses concurrent async HTTP requests and records:

1. Total/completed/failed requests
2. Error-rate percentage
3. Avg/P95/min/max response times
4. Per-target pass/fail against thresholds

Reports are saved in JSON format:

- `storage/app/stress-tests/stress-test-YYYYMMDD_HHMMSS.json`

## Route Coverage Model

### A) Explicit target mode

Use specific business-critical routes:

```dotenv
STRESS_TEST_TARGETS=/,/login,/register,/placement/start,/pricing
```

### B) All routes mode (safe GET/HEAD discovery)

Enable automatic route discovery:

```dotenv
STRESS_TEST_DISCOVER_ROUTES=true
STRESS_TEST_MAX_DISCOVERED_TARGETS=250
STRESS_TEST_EXCLUDE_ROUTE_PATTERNS=_debugbar/*,_ignition/*,sanctum/*,broadcasting/*,admin/*,user/*,api/*,oauth/*,auth/*,stripe/*,webhooks/*
```

Behavior:

1. Scans Laravel route table.
2. Includes GET/HEAD routes only.
3. Skips dynamic path-parameter routes (example: `/jobs/{id}`).
4. Applies exclusion patterns for sensitive/non-representative paths.
5. Allows exclusion tuning for routes that are callback-only or query-dependent.

Preview covered routes before load:

```bash
php artisan portal:stress-test --discover-routes --list-routes-only
```

## Execution Phases (Recommended)

### Phase 1: Baseline (low impact)

```bash
php artisan portal:stress-test --discover-routes --requests=10 --concurrency=3 --timeout=10
```

Expected outcome:

1. Validate route discovery and reporting.
2. Confirm no immediate failures.

### Phase 2: Moderate load

```bash
php artisan portal:stress-test --discover-routes --requests=50 --concurrency=10 --timeout=10
```

Expected outcome:

1. Observe latency scaling behavior.
2. Identify first bottleneck endpoints.

### Phase 3: High load

```bash
php artisan portal:stress-test --discover-routes --requests=100 --concurrency=20 --timeout=10
```

Expected outcome:

1. Validate stability under aggressive load.
2. Determine if thresholds are breached by latency/error.

### Phase 4: Peak simulation (off-peak window only)

```bash
php artisan portal:stress-test --discover-routes --requests=200 --concurrency=25 --timeout=10
```

Expected outcome:

1. Establish practical system ceiling.
2. Produce optimization backlog from failing targets.

## Weekly Automation (Requirement Fulfillment)

Scheduled in:

- `app/Console/Kernel.php`

Configured by env:

```dotenv
STRESS_TEST_ENABLED=true
STRESS_TEST_SCHEDULE_DAY=0
STRESS_TEST_SCHEDULE_TIME=03:00
STRESS_TEST_SCHEDULE_TIMEZONE=UTC
```

Production cron requirement:

```cron
* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
```

## Pass/Fail Policy

Configured thresholds:

```dotenv
STRESS_TEST_MAX_ERROR_RATE_PERCENT=3
STRESS_TEST_MAX_AVG_RESPONSE_MS=2000
STRESS_TEST_FAIL_ON_THRESHOLD_BREACH=true
```

A run fails when either:

1. Error rate exceeds max error threshold.
2. Average response exceeds max latency threshold.

## Alerting

Enable failure emails:

```dotenv
STRESS_TEST_ALERT_EMAILS=ops@yourcompany.com,engineering@yourcompany.com
```

On failed runs:

1. Warning is logged.
2. Report is saved.
3. Alert email is sent to configured recipients.

## Client-Facing Summary Template

Weekly automated stress testing is now implemented across configured portal routes using a phased load strategy.
The system currently demonstrates strong reliability (request completion stability), while high-load phases are used to identify and optimize latency bottlenecks before they become production incidents.
