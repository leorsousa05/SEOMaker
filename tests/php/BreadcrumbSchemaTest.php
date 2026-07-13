<?php

declare(strict_types=1);

require_once __DIR__ . '/../../src/autoload.php';

use App\Core\Config;
use App\Core\Seeder;
use App\Models\Page;
use App\Seo\SeoManager;

if (!function_exists('assertTrue')) {
    function assertTrue(bool $condition, string $message): void
    {
        if (!$condition) {
            throw new \RuntimeException("FAIL: {$message}");
        }
        echo "PASS: {$message}\n";
    }
}

Seeder::run();
Config::set('site_title', 'SEOMaker');
Config::set('site_url', 'https://example.com');

$page = new Page();
$page->slug = 'sobre';
$page->title = 'Sobre Nós';

$schema = SeoManager::breadcrumbSchema($page);
assertTrue(strpos($schema, '"@type":"BreadcrumbList"') !== false, 'breadcrumb is BreadcrumbList');
assertTrue(strpos($schema, '"position":1') !== false, 'first item position 1');
assertTrue(strpos($schema, '"position":2') !== false, 'second item position 2');
assertTrue(strpos($schema, 'https://example.com/') !== false, 'homepage url in breadcrumb');
assertTrue(strpos($schema, 'https://example.com/sobre') !== false, 'page url in breadcrumb');

// Homepage has only 1 item
$home = new Page();
$home->slug = '';
$home->title = 'Home';
$homeSchema = SeoManager::breadcrumbSchema($home);
assertTrue(strpos($homeSchema, '"position":1') !== false, 'home breadcrumb has position 1');
assertTrue(strpos($homeSchema, '"position":2') === false, 'home breadcrumb has no position 2');

echo "\nAll BreadcrumbSchema tests passed.\n";
