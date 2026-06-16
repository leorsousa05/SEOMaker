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

$meta = SeoManager::metaTags($page);
assertTrue(strpos($meta, 'href="https://example.com/page/sobre"') !== false, 'default canonical generated from slug');

$page->canonical_url = 'https://example.com/campanha';
$meta = SeoManager::metaTags($page);
assertTrue(strpos($meta, 'href="https://example.com/campanha"') !== false, 'manual canonical overrides slug');

$page->canonical_url = '/campanha-relativa';
$meta = SeoManager::metaTags($page);
assertTrue(strpos($meta, 'href="https://example.com/campanha-relativa"') !== false, 'relative canonical prefixed with site_url');

echo "\nAll PageCanonical tests passed.\n";
