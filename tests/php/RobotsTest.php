<?php

declare(strict_types=1);

require_once __DIR__ . '/../../src/autoload.php';

use App\Core\Config;
use App\Seo\RobotsBuilder;

function assertRobots(bool $condition, string $message): void
{
    if (!$condition) {
        throw new \RuntimeException("FAIL: {$message}");
    }
    echo "PASS: {$message}\n";
}

// Save original
$originalCustom = Config::get('robots_txt_custom', '');

// Test default generation
Config::set('robots_txt_custom', '');
$output = RobotsBuilder::generate();

assertRobots(str_contains($output, 'User-agent: *'), 'default contains user agent');
assertRobots(str_contains($output, 'Allow: /'), 'default contains allow');
assertRobots(str_contains($output, 'Sitemap:'), 'default contains sitemap reference');

// Test custom config
Config::set('robots_txt_custom', "User-agent: *\nDisallow: /admin/");
$output = RobotsBuilder::generate();

assertRobots(str_contains($output, 'Disallow: /admin/'), 'returns custom disallow');
assertRobots(!str_contains($output, 'Allow: /'), 'custom overrides default allow');

// Restore
Config::set('robots_txt_custom', $originalCustom);
