<?php

namespace App\Spiders;

use RoachPHP\Http\Response;
use RoachPHP\Spider\BasicSpider;
use Generator;

class ExtractWebPage extends BasicSpider
{
    public array $startUrls = [];

    public function parse(Response $response): Generator
    {
        // 1. Identify which platform we are on based on the URL
        $url = $response->getUri();
        $html = '';

        if (str_contains($url, 'linkedin.com')) {
            // LinkedIn selector
            $html = $response->filter('ul.jobs-search__results-list')->count() > 0
                ? $response->filter('ul.jobs-search__results-list')->html()
                : '';
        } elseif (str_contains($url, 'indeed.com')) {
            // Indeed selector (from your provided HTML)
            $html = $response->filter('ul.css-pygyny')->count() > 0
                ? $response->filter('ul.css-pygyny')->html()
                : '';
        }

        // 2. If the main list wasn't found, fallback to the body or a default message
        if (empty($html)) {
            $html = $response->filter('body')->html();
        }

        yield $this->item([
            'html' => $html,
        ]);
    }
}
