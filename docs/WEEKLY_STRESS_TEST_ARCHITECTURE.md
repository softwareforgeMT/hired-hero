# Weekly Stress Test Architecture and Responsibilities

## Purpose
This document defines how automated weekly stress testing works in HiredHero, who owns each part, and how to operate it safely in production.

Status date: April 10, 2026.

## Business Requirement
Need automated stress testing every week to verify that core portal endpoints continue to operate under load without crashing.

## Architecture Overview
The implementation follows a command-driven scheduled workflow:

1. Scheduler triggers the stress test weekly.
2. Stress test command runs concurrent HTTP requests against configured targets.
3. Command computes performance and reliability metrics.
4. Command evaluates thresholds (pass/fail).
5. Command stores a JSON report for audit/history.
6. Command sends alert emails on failure (if configured).

## Components (What Is What)

1. Scheduler orchestration
- File: `app/Console/Kernel.php`
- Responsibility: Runs `portal:stress-test` on configured weekly day/time/timezone.
- Protection: `withoutOverlapping()` prevents duplicate concurrent runs.

2. Stress test execution command
- File: `app/Console/Commands/RunPortalStressTestCommand.php`
- Responsibility: Executes the complete load test lifecycle.
- Key functions:
  - `resolveSettings()`: Reads runtime config/env/options.
  - `discoverRouteTargets()`: Auto-discovers safe GET/HEAD routes for all-routes mode.
  - `runTargetStressTest()`: Sends concurrent requests and captures metrics.
  - `buildSummary()`: Aggregates global metrics and final pass/fail.
  - `storeReport()`: Persists JSON report.
  - `sendFailureAlertEmails()`: Sends failure notifications.

3. Configuration source of truth
- File: `config/stress-test.php`
- Responsibility: Centralized tuning for targets, load profile, thresholds, schedule, report location, and alert recipients.
- Design choice: No hardcoded production values inside command logic.
- Includes route discovery controls:
  - `STRESS_TEST_DISCOVER_ROUTES`
  - `STRESS_TEST_EXCLUDE_ROUTE_PATTERNS`
  - `STRESS_TEST_MAX_DISCOVERED_TARGETS`

4. Operations runbook
- File: `docs/WEEKLY_STRESS_TESTING.md`
- Responsibility: Manual execution commands, environment variables, and report location.

5. Detailed execution plan
- File: `docs/STRESS_TEST_EXECUTION_PLAN_ALL_ROUTES.md`
- Responsibility: PDF-style phase plan for all-routes stress testing and client reporting.

6. Advanced k6 suite
- File: `scripts/k6/stress-test.js`
- Usage guide: `docs/K6_STRESS_TESTING.md`
- Responsibility: High-fidelity external load testing with threshold-enforced exit status and JSON summary export.

## Ownership Model (Who Is Who)

1. Product/Client owner
- Defines critical endpoints and acceptable reliability/performance targets.

2. Backend engineering owner
- Maintains command logic, thresholds, and report schema.
- Ensures code-level correctness and backward compatibility.

3. DevOps/SRE owner
- Ensures server cron runs `php artisan schedule:run` every minute.
- Ensures queue/runtime services are healthy.
- Maintains alert routing and incident channel.

4. QA owner
- Validates functional behavior in staging/local.
- Verifies report generation and pass/fail behavior under controlled load.

5. Support/Operations owner
- Monitors alerts and triages failures.
- Escalates recurring degradations to engineering.

## Data Contract (Report Output)
Each run generates a report at:

- `storage/app/stress-tests/stress-test-YYYYMMDD_HHMMSS.json`

Report contains:

1. `meta`
- generation timestamp
- environment
- effective runtime settings

2. `summary`
- runtime
- total completed/failed requests
- overall error rate
- overall average response time
- failed targets
- pass/fail

3. `targets[]`
- per-target completed/failed counts
- status code distribution
- avg and p95 latency
- error rate
- pass/fail

## Pass/Fail Policy
A run fails when one or more configured thresholds are breached:

1. Error rate exceeds `STRESS_TEST_MAX_ERROR_RATE_PERCENT`
2. Average response time exceeds `STRESS_TEST_MAX_AVG_RESPONSE_MS`

Behavior on failure:

1. Run is marked failed.
2. Warning is logged.
3. Alert email is sent to `STRESS_TEST_ALERT_EMAILS` (if set).
4. Non-zero exit code is returned unless `STRESS_TEST_FAIL_ON_THRESHOLD_BREACH=false`.

## Operational Checklist

1. Configure `.env` values for targets, load profile, thresholds, schedule.
2. Verify scheduler visibility with `php artisan schedule:list`.
3. Validate command manually with `php artisan portal:stress-test`.
4. Confirm JSON report is written after each run.
5. Confirm alert email delivery by intentionally running with strict thresholds in non-production.

## Non-Goals / Boundaries

1. This is application-level HTTP stress testing, not infrastructure-level load generation at cloud edge scale.
2. This does not replace APM, uptime monitors, or synthetic browser tests.
3. This does not auto-remediate; it provides detection, evidence, and alerting.

## Recommended Next Evolution

1. Add trend dashboards from report history (error-rate and latency over time).
2. Add endpoint categories (critical vs non-critical) with separate thresholds.
3. Add Slack/Teams integration alongside email for faster incident response.
4. Add staging pre-release stress gate in CI/CD before production deploy windows.
