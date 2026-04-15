# k6 Stress Testing (Advanced)

This project includes an advanced k6 script for professional load/stress testing:

- `scripts/k6/stress-test.js`

Use this alongside Laravel's weekly command (`php artisan portal:stress-test`) for stronger testing coverage.

## Prerequisites

Install k6 locally:

- Windows (choco): `choco install k6`
- macOS (brew): `brew install k6`
- Linux: see official docs

Official docs:

- https://grafana.com/docs/k6/latest/

## Quick Start

```bash
k6 run scripts/k6/stress-test.js
```

Default behavior:

1. Base URL: `http://127.0.0.1:8000`
2. Targets: `/,/login,/register`
3. Requests per target: `20`
4. Concurrency: `5`
5. Summary JSON written to `storage/app/stress-tests/k6-summary-*.json`

## Environment Variables

```bash
BASE_URL=https://hiredheroai.com
TARGETS=/,/login,/register,/placement/start,/pricing
REQUESTS_PER_TARGET=50
CONCURRENCY=10
MAX_ERROR_RATE_PERCENT=3
MAX_AVG_RESPONSE_MS=2000
MAX_P95_RESPONSE_MS=4000
SLEEP_MS=0
INSECURE_SKIP_TLS_VERIFY=false
SUMMARY_JSON=./storage/app/stress-tests/k6-live-summary.json
```

## Example Runs

### 1) Local moderate load

```bash
k6 run ^
  -e BASE_URL=http://127.0.0.1:8000 ^
  -e TARGETS=/,/login,/register ^
  -e REQUESTS_PER_TARGET=50 ^
  -e CONCURRENCY=10 ^
  scripts/k6/stress-test.js
```

### 2) Live canary (safe)

```bash
k6 run ^
  -e BASE_URL=https://hiredheroai.com ^
  -e TARGETS=/,/login,/register ^
  -e REQUESTS_PER_TARGET=10 ^
  -e CONCURRENCY=2 ^
  scripts/k6/stress-test.js
```

### 3) Live ramp

```bash
k6 run -e BASE_URL=https://hiredheroai.com -e REQUESTS_PER_TARGET=50 -e CONCURRENCY=10 scripts/k6/stress-test.js
k6 run -e BASE_URL=https://hiredheroai.com -e REQUESTS_PER_TARGET=100 -e CONCURRENCY=20 scripts/k6/stress-test.js
```

## Notes

1. k6 exits non-zero if thresholds are breached.
2. For WAF/CDN protected environments, allowlist runner IP/user-agent (`HiredHero-k6-StressTest/1.0`) as needed.
3. Do not include destructive POST/DELETE routes in `TARGETS`.
