import http from "k6/http";
import { check, sleep } from "k6";

const baseUrl = (__ENV.BASE_URL || "http://127.0.0.1:8000").replace(/\/$/, "");
const targetCsv = __ENV.TARGETS || "/,/login,/register";
const targets = targetCsv
  .split(",")
  .map((item) => item.trim())
  .filter((item) => item.length > 0);

const requestsPerTarget = Number(__ENV.REQUESTS_PER_TARGET || 20);
const concurrency = Number(__ENV.CONCURRENCY || 5);
const sleepMs = Number(__ENV.SLEEP_MS || 0);

const maxErrorRatePercent = Number(__ENV.MAX_ERROR_RATE_PERCENT || 3);
const maxAvgMs = Number(__ENV.MAX_AVG_RESPONSE_MS || 2000);
const maxP95Ms = Number(__ENV.MAX_P95_RESPONSE_MS || 4000);

const summaryPath =
  __ENV.SUMMARY_JSON ||
  `./storage/app/stress-tests/k6-summary-${new Date().toISOString().replace(/[:.]/g, "-")}.json`;

const skipTlsVerify = (__ENV.INSECURE_SKIP_TLS_VERIFY || "false").toLowerCase() === "true";

export const options = {
  vus: concurrency,
  iterations: requestsPerTarget,
  insecureSkipTLSVerify: skipTlsVerify,
  thresholds: {
    checks: ["rate>0.97"],
    http_req_failed: [`rate<${maxErrorRatePercent / 100}`],
    http_req_duration: [`avg<${maxAvgMs}`, `p(95)<${maxP95Ms}`],
  },
  summaryTrendStats: ["avg", "min", "med", "p(95)", "max"],
};

export default function () {
  for (const target of targets) {
    const normalizedPath = target.startsWith("http://") || target.startsWith("https://")
      ? null
      : `/${target.replace(/^\/+/, "")}`;
    const url = normalizedPath ? `${baseUrl}${normalizedPath === "/" ? "/" : normalizedPath}` : target;
    const endpointTag = normalizedPath || target;

    const response = http.get(url, {
      tags: { endpoint: endpointTag },
      headers: {
        "User-Agent": "HiredHero-k6-StressTest/1.0",
      },
    });

    check(response, {
      "status is < 400": (r) => r.status < 400,
    });

    if (sleepMs > 0) {
      sleep(sleepMs / 1000);
    }
  }
}

export function handleSummary(data) {
  return {
    [summaryPath]: JSON.stringify(data, null, 2),
  };
}
