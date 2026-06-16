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

Config::set('site_title', 'Test Site');
Config::set('site_description', 'Test description');
Config::set('site_url', 'https://example.com');

$page = new Page();
$page->slug = 'sobre';
$page->title = 'Sobre Nós';
$page->meta_title = 'Sobre';
$page->meta_description = 'Descrição sobre';

$meta = SeoManager::metaTags($page);
assertTrue(strpos($meta, '<meta name="robots" content="index, follow">') !== false, 'default robots meta is index, follow');

$page->meta_robots = 'noindex, nofollow';
$meta = SeoManager::metaTags($page);
assertTrue(strpos($meta, '<meta name="robots" content="noindex, nofollow">') !== false, 'custom robots meta rendered');

assertTrue(Page::defaultMetaRobots() === 'index, follow', 'defaultMetaRobots returns index, follow');

echo "\nAll PageRobots tests passed.\n";
