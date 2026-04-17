<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RunPortalStressTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'portal:stress-test
                            {--requests= : Total requests per target URL}
                            {--concurrency= : Number of concurrent requests}
                            {--timeout= : Request timeout in seconds}
                            {--targets=* : Absolute URL(s) or relative path(s) to test}
                            {--base-url= : Base URL used for relative targets}
                            {--discover-routes : Auto-discover safe GET/HEAD routes from Laravel routing table}
                            {--list-routes-only : Print resolved stress test targets and exit}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run weekly stress tests against portal endpoints';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!config('stress-test.enabled', true)) {
            $this->warn('Stress testing is disabled via STRESS_TEST_ENABLED.');

            return 0;
        }

        $settings = $this->resolveSettings();

        if (empty($settings['targets'])) {
            $this->error('No stress test targets configured. Set STRESS_TEST_TARGETS or use --discover-routes.');

            return 1;
        }

        if ((bool) $this->option('list-routes-only')) {
            $this->info('Resolved stress test targets: '.count($settings['targets']));
            foreach ($settings['targets'] as $target) {
                $this->line('- '.$target);
            }

            return 0;
        }

        $this->info('Starting weekly portal stress test...');
        $this->line('Targets: '.implode(', ', $settings['targets']));
        $this->line("Requests per target: {$settings['requests']}");
        $this->line("Concurrency: {$settings['concurrency']}");
        $this->line("Timeout: {$settings['timeout']}s");

        if (!empty($settings['route_discovery']) && !empty($settings['route_discovery']['enabled'])) {
            $this->line(
                'Route discovery: discovered '.$settings['route_discovery']['discovered_count']
                .', skipped dynamic '.$settings['route_discovery']['skipped_dynamic_count']
                .', skipped excluded '.$settings['route_discovery']['skipped_excluded_count']
                .($settings['route_discovery']['truncated'] ? ', list truncated by max target limit' : '')
            );
        }

        $startedAt = now();
        $startedAtTime = microtime(true);
        $targetResults = [];

        foreach ($settings['targets'] as $targetUrl) {
            $this->line("Testing {$targetUrl}...");

            $targetResults[] = $this->runTargetStressTest(
                $targetUrl,
                $settings['requests'],
                $settings['concurrency'],
                $settings['timeout'],
                $settings['verify_tls'],
                $settings['max_error_rate_percent'],
                $settings['max_avg_response_ms'],
                $settings['api_token']
            );
        }

        $summary = $this->buildSummary(
            $targetResults,
            (float) ((microtime(true) - $startedAtTime) * 1000),
            $settings['max_error_rate_percent'],
            $settings['max_avg_response_ms']
        );

        $report = [
            'meta' => [
                'generated_at' => now()->toIso8601String(),
                'started_at' => $startedAt->toIso8601String(),
                'environment' => config('app.env'),
                'settings' => $settings,
            ],
            'summary' => $summary,
            'targets' => $targetResults,
        ];

        $storedReportPath = $this->storeReport($report);

        $this->table(
            ['Target', 'Completed', 'Failed', 'Error %', 'Avg ms', 'P95 ms', 'Passed'],
            array_map(function ($target) {
                return [
                    $target['target'],
                    $target['completed_requests'],
                    $target['failed_requests'],
                    number_format($target['error_rate_percent'], 2),
                    number_format($target['avg_response_ms'], 2),
                    number_format($target['p95_response_ms'], 2),
                    $target['passed'] ? 'yes' : 'no',
                ];
            }, $targetResults)
        );

        $this->line('Report saved to: '.$storedReportPath);
        $this->line('Overall error rate: '.number_format($summary['overall_error_rate_percent'], 2).'%');
        $this->line('Overall average response: '.number_format($summary['overall_avg_response_ms'], 2).'ms');

        if (!$summary['passed']) {
            $this->error('Stress test thresholds were breached.');

            Log::warning('Weekly portal stress test failed', [
                'summary' => $summary,
                'report_path' => $storedReportPath,
            ]);

            $this->sendFailureAlertEmails($summary, $storedReportPath);

            return config('stress-test.fail_on_threshold_breach', true) ? 1 : 0;
        }

        $this->info('Weekly portal stress test passed.');

        Log::info('Weekly portal stress test passed', [
            'summary' => $summary,
            'report_path' => $storedReportPath,
        ]);

        return 0;
    }

    /**
     * @return array<string, mixed>
     */
    protected function resolveSettings()
    {
        $baseUrl = (string) ($this->option('base-url') ?: config('stress-test.base_url', config('app.url', '')));
        $requests = max(1, (int) ($this->option('requests') ?: config('stress-test.requests_per_target', 200)));
        $concurrency = max(1, (int) ($this->option('concurrency') ?: config('stress-test.concurrency', 25)));
        $timeout = (float) ($this->option('timeout') ?: config('stress-test.timeout_seconds', 10));
        $timeout = $timeout > 0 ? $timeout : 10;
        $apiToken = (string) config('stress-test.api_token', env('STRESS_TEST_API_TOKEN', ''));

        $providedTargets = $this->option('targets');
        $configuredTargets = config('stress-test.targets', []);
        $manualTargets = !empty($providedTargets) ? $providedTargets : $configuredTargets;

        $resolvedTargets = $this->normalizeTargets($manualTargets, $baseUrl);

        $routeDiscoveryEnabled = (bool) ($this->option('discover-routes') || config('stress-test.discover_routes', false));
        $routeDiscoveryMeta = [
            'enabled' => $routeDiscoveryEnabled,
            'discovered_count' => 0,
            'skipped_dynamic_count' => 0,
            'skipped_excluded_count' => 0,
            'truncated' => false,
        ];

        if ($routeDiscoveryEnabled) {
            $discovery = $this->discoverRouteTargets($baseUrl);
            $resolvedTargets = array_values(array_unique(array_merge($resolvedTargets, $discovery['targets'])));

            $routeDiscoveryMeta['discovered_count'] = $discovery['discovered_count'];
            $routeDiscoveryMeta['skipped_dynamic_count'] = $discovery['skipped_dynamic_count'];
            $routeDiscoveryMeta['skipped_excluded_count'] = $discovery['skipped_excluded_count'];
            $routeDiscoveryMeta['truncated'] = $discovery['truncated'];
        }

        return [
            'base_url' => $baseUrl,
            'targets' => $resolvedTargets,
            'requests' => $requests,
            'concurrency' => $concurrency,
            'timeout' => $timeout,
            'verify_tls' => (bool) config('stress-test.verify_tls', true),
            'api_token' => $apiToken,
            'max_error_rate_percent' => (float) config('stress-test.max_error_rate_percent', 3),
            'max_avg_response_ms' => (float) config('stress-test.max_avg_response_ms', 2000),
            'route_discovery' => $routeDiscoveryMeta,
        ];
    }

    /**
     * @param  array<int, string>  $targets
     * @return array<int, string>
     */
    protected function normalizeTargets(array $targets, $baseUrl)
    {
        $normalized = [];
        $trimmedBaseUrl = rtrim((string) $baseUrl, '/');

        foreach ($targets as $target) {
            $target = trim((string) $target);

            if ($target === '') {
                continue;
            }

            if (Str::startsWith($target, ['http://', 'https://'])) {
                $normalized[] = $target;
                continue;
            }

            if ($trimmedBaseUrl !== '') {
                $normalized[] = $trimmedBaseUrl.'/'.ltrim($target, '/');
            }
        }

        return array_values(array_unique($normalized));
    }

    /**
     * @return array<string, mixed>
     */
    protected function discoverRouteTargets($baseUrl)
    {
        $includeApiRoutes = (bool) config('stress-test.include_api_routes', false);
        $excludePatterns = config('stress-test.exclude_route_patterns', []);
        $maxTargets = max(1, (int) config('stress-test.max_discovered_targets', 250));

        $targets = [];
        $skippedDynamic = 0;
        $skippedExcluded = 0;
        $trimmedBaseUrl = rtrim((string) $baseUrl, '/');

        foreach (Route::getRoutes() as $route) {
            $methods = $route->methods();
            if (!in_array('GET', $methods, true) && !in_array('HEAD', $methods, true)) {
                continue;
            }

            $uri = '/'.ltrim((string) $route->uri(), '/');
            if ($uri === '//') {
                $uri = '/';
            }

            $uriWithoutLeadingSlash = ltrim($uri, '/');

            if (!$includeApiRoutes && Str::startsWith($uriWithoutLeadingSlash, 'api/')) {
                $skippedExcluded++;
                continue;
            }

            if (Str::contains($uri, ['{', '}'])) {
                $skippedDynamic++;
                continue;
            }

            if ($this->isRouteExcluded($uriWithoutLeadingSlash, $excludePatterns)) {
                $skippedExcluded++;
                continue;
            }

            if ($trimmedBaseUrl === '') {
                continue;
            }

            $targets[] = $uri === '/' ? $trimmedBaseUrl.'/' : $trimmedBaseUrl.'/'.ltrim($uri, '/');
        }

        $targets = array_values(array_unique($targets));
        $truncated = false;

        if (count($targets) > $maxTargets) {
            $targets = array_slice($targets, 0, $maxTargets);
            $truncated = true;
        }

        return [
            'targets' => $targets,
            'discovered_count' => count($targets),
            'skipped_dynamic_count' => $skippedDynamic,
            'skipped_excluded_count' => $skippedExcluded,
            'truncated' => $truncated,
        ];
    }

    /**
     * @param  array<int, string>  $patterns
     * @return bool
     */
    protected function isRouteExcluded($uriWithoutLeadingSlash, array $patterns)
    {
        foreach ($patterns as $pattern) {
            $pattern = trim((string) $pattern);
            if ($pattern === '') {
                continue;
            }

            if (Str::endsWith($pattern, '/*')) {
                $prefix = rtrim(substr($pattern, 0, -2), '/');
                if ($uriWithoutLeadingSlash === $prefix || Str::startsWith($uriWithoutLeadingSlash, $prefix.'/')) {
                    return true;
                }
            }

            if (Str::is($pattern, $uriWithoutLeadingSlash) || Str::is($pattern, '/'.$uriWithoutLeadingSlash)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array<string, mixed>
     */
    protected function runTargetStressTest(
        $targetUrl,
        $requests,
        $concurrency,
        $timeoutSeconds,
        $verifyTls,
        $maxErrorRatePercent,
        $maxAvgResponseMs,
        $apiToken = ''
    ) {
        $client = new Client([
            'http_errors' => false,
            'verify' => $verifyTls,
        ]);

        $durations = [];

        $result = [
            'target' => $targetUrl,
            'total_requests' => (int) $requests,
            'completed_requests' => 0,
            'successful_requests' => 0,
            'failed_requests' => 0,
            'timeout_requests' => 0,
            'status_codes' => [],
            'avg_response_ms' => 0.0,
            'p95_response_ms' => 0.0,
            'min_response_ms' => 0.0,
            'max_response_ms' => 0.0,
            'error_rate_percent' => 0.0,
            'passed' => false,
        ];

        $requestsGenerator = function () use ($client, $targetUrl, $requests, $timeoutSeconds, $apiToken) {
            for ($i = 0; $i < $requests; $i++) {
                yield function () use ($client, $targetUrl, $timeoutSeconds, $apiToken) {
                    $requestStart = microtime(true);

                    $headers = [
                        'User-Agent' => 'HiredHero-StressTest/1.0',
                    ];

                    // Add authorization header if token is provided
                    if (!empty($apiToken)) {
                        $headers['Authorization'] = "Bearer {$apiToken}";
                    }

                    return $client->requestAsync('GET', $targetUrl, [
                        'timeout' => $timeoutSeconds,
                        'connect_timeout' => $timeoutSeconds,
                        'headers' => $headers,
                    ])->then(function ($response) use ($requestStart) {
                        return [
                            'status_code' => $response->getStatusCode(),
                            'duration_ms' => (microtime(true) - $requestStart) * 1000,
                        ];
                    });
                };
            }
        };

        $pool = new Pool($client, $requestsGenerator(), [
            'concurrency' => $concurrency,
            'fulfilled' => function ($responseMeta) use (&$result, &$durations) {
                $statusCode = (int) ($responseMeta['status_code'] ?? 0);
                $durationMs = (float) ($responseMeta['duration_ms'] ?? 0);

                $result['completed_requests']++;
                $durations[] = $durationMs;
                $result['status_codes'][$statusCode] = ($result['status_codes'][$statusCode] ?? 0) + 1;

                if ($statusCode >= 200 && $statusCode < 400) {
                    $result['successful_requests']++;
                } else {
                    $result['failed_requests']++;
                }
            },
            'rejected' => function ($reason) use (&$result) {
                $result['completed_requests']++;
                $result['failed_requests']++;

                $statusKey = 'error';

                if ($reason instanceof RequestException && $reason->hasResponse()) {
                    $statusKey = (string) $reason->getResponse()->getStatusCode();
                } elseif (is_object($reason) && method_exists($reason, 'getMessage')) {
                    $message = strtolower((string) $reason->getMessage());
                    if (Str::contains($message, ['timed out', 'timeout'])) {
                        $result['timeout_requests']++;
                        $statusKey = 'timeout';
                    }
                }

                $result['status_codes'][$statusKey] = ($result['status_codes'][$statusKey] ?? 0) + 1;
            },
        ]);

        $pool->promise()->wait();

        sort($durations);
        $durationCount = count($durations);
        $durationSum = $durationCount > 0 ? array_sum($durations) : 0;

        $result['avg_response_ms'] = $durationCount > 0 ? round($durationSum / $durationCount, 2) : 0.0;
        $result['p95_response_ms'] = $durationCount > 0 ? round($durations[(int) floor(($durationCount - 1) * 0.95)], 2) : 0.0;
        $result['min_response_ms'] = $durationCount > 0 ? round($durations[0], 2) : 0.0;
        $result['max_response_ms'] = $durationCount > 0 ? round($durations[$durationCount - 1], 2) : 0.0;
        $result['error_rate_percent'] = $result['completed_requests'] > 0
            ? round(($result['failed_requests'] / $result['completed_requests']) * 100, 2)
            : 100.0;
        $result['passed'] = $result['error_rate_percent'] <= $maxErrorRatePercent
            && $result['avg_response_ms'] <= $maxAvgResponseMs;

        ksort($result['status_codes']);

        return $result;
    }

    /**
     * @param  array<int, array<string, mixed>>  $targetResults
     * @return array<string, mixed>
     */
    protected function buildSummary(array $targetResults, $runtimeMs, $maxErrorRatePercent, $maxAvgResponseMs)
    {
        $completed = 0;
        $failed = 0;
        $weightedAvgLatencySum = 0.0;
        $failedTargets = [];

        foreach ($targetResults as $targetResult) {
            $completed += (int) $targetResult['completed_requests'];
            $failed += (int) $targetResult['failed_requests'];
            $weightedAvgLatencySum += ((float) $targetResult['avg_response_ms']) * ((int) $targetResult['completed_requests']);

            if (empty($targetResult['passed'])) {
                $failedTargets[] = $targetResult['target'];
            }
        }

        $overallAvgResponseMs = $completed > 0 ? round($weightedAvgLatencySum / $completed, 2) : 0.0;
        $overallErrorRatePercent = $completed > 0 ? round(($failed / $completed) * 100, 2) : 100.0;

        return [
            'runtime_ms' => round($runtimeMs, 2),
            'total_completed_requests' => $completed,
            'total_failed_requests' => $failed,
            'overall_error_rate_percent' => $overallErrorRatePercent,
            'overall_avg_response_ms' => $overallAvgResponseMs,
            'failed_targets' => $failedTargets,
            'thresholds' => [
                'max_error_rate_percent' => $maxErrorRatePercent,
                'max_avg_response_ms' => $maxAvgResponseMs,
            ],
            'passed' => empty($failedTargets)
                && $overallErrorRatePercent <= $maxErrorRatePercent
                && $overallAvgResponseMs <= $maxAvgResponseMs,
        ];
    }

    /**
     * @param  array<string, mixed>  $report
     * @return string
     */
    protected function storeReport(array $report)
    {
        $disk = (string) config('stress-test.report_disk', 'local');
        $directory = trim((string) config('stress-test.report_directory', 'stress-tests'), '/');
        $fileName = 'stress-test-'.now()->format('Ymd_His').'.json';
        $relativePath = ($directory !== '' ? $directory.'/' : '').$fileName;

        try {
            Storage::disk($disk)->put($relativePath, json_encode($report, JSON_PRETTY_PRINT));

            return $disk.':'.$relativePath;
        } catch (\Throwable $exception) {
            Log::error('Unable to store stress test report', [
                'disk' => $disk,
                'path' => $relativePath,
                'error' => $exception->getMessage(),
            ]);

            Storage::disk('local')->put($relativePath, json_encode($report, JSON_PRETTY_PRINT));

            return 'local:'.$relativePath;
        }
    }

    /**
     * @param  array<string, mixed>  $summary
     * @return void
     */
    protected function sendFailureAlertEmails(array $summary, $reportPath)
    {
        $emails = config('stress-test.alert_emails', []);

        if (empty($emails)) {
            return;
        }

        $subject = '[HiredHero] Weekly stress test failed';
        $body = "The weekly stress test failed in environment '".config('app.env')."'.\n\n"
            ."Report: {$reportPath}\n"
            .'Overall error rate: '.number_format((float) $summary['overall_error_rate_percent'], 2)."%\n"
            .'Overall average response: '.number_format((float) $summary['overall_avg_response_ms'], 2)."ms\n"
            .'Failed targets: '.implode(', ', $summary['failed_targets'])."\n"
            .'Generated at: '.now()->toIso8601String();

        try {
            Mail::raw($body, function ($message) use ($emails, $subject) {
                $message->to($emails)->subject($subject);
            });
        } catch (\Throwable $exception) {
            Log::error('Unable to send stress test alert email', [
                'emails' => $emails,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
