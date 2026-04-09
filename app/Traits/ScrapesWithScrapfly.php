<?php

namespace App\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

trait ScrapesWithScrapfly
{
    /**
     * Fetch HTML content from Wellfound using Scrapfly.io API
     * Returns JSON-decoded tag array or empty string
     */
    public function fetchWithScrapeflyWelfound(string $url): array|string
    {
        try {
            Log::info('Fetching Wellfound with Scrapfly - Target: ' . $url);

            $client = new Client([
                'base_uri'         => 'https://api.scrapfly.io',
                'timeout'          => 160.0,         // 160 seconds for JS rendering
                'connect_timeout'  => 10.0,          // 10 seconds to connect
                'http_errors'      => false,         // Don't throw on 4xx/5xx
            ]);

            $params = [
                'key'       => env('SCRAPEFLY_API_KEY'),
                'url'       => $url,
                'format'    => 'json',
                'asp'       => 'true',
                'render_js' => 'true',
                'tags'      => 'job-scraper',
            ];

            $response = $client->request('GET', '/scrape', [
                'query'           => $params,
                'decode_content'  => true,  // Auto-decode gzip/deflate
            ]);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);

            Log::info('Scrapfly Status: ' . $statusCode);
    
            if ($statusCode >= 400) {
                Log::warning('Error (' . $statusCode . '): ' . json_decode($data['result']['content'], true));
                $errorMsg = $data['message'] ?? $data['description'] ?? 'Request failed';
                Log::warning('Scrapfly API Error (' . $statusCode . '): ' . $errorMsg);
                
                return '';
            }

            if ($statusCode >= 200 && $statusCode < 300) {
                // Scrapfly returns HTML in 'result' -> 'content'
                if (isset($data['result']['content'])) {
                    Log::info('Scrapfly: Successfully fetched content (' . strlen($data['result']['content']) . ' bytes)');
                    $content = json_decode($data['result']['content'], true);
                    $aValues = $content['content']['a'] ?? [];
                    return $aValues;
                }

                Log::warning('Scrapfly: No content in response');
                return '';
            }

            return '';
        } catch (RequestException $e) {
            Log::warning('Scrapfly RequestException: ' . $e->getMessage());
            if ($e->hasResponse()) {
                Log::warning('Response: ' . $e->getResponse()->getBody());
            }
            return '';
        } catch (\Exception $e) {
            Log::warning('Scrapfly error: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * Fetch HTML content from WorkDay using Scrapfly.io API
     * Returns HTML string directly
     */
    

    /**
     * Build Wellfound URL with role
     */
    private function buildWellfoundUrl(string $role): string
    {
        $role = strtolower(trim($role));
        // Convert role to URL-friendly format (e.g., "Software Engineer" -> "software-engineer")
        $roleSlug = str_replace([' ', '_'], '-', $role);
        return 'https://wellfound.com/role/' . $roleSlug;
    }

}
