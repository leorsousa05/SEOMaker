<?php

declare(strict_types=1);

require_once __DIR__ . '/../../src/autoload.php';

use App\Core\Config;
use App\Core\Database;
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

// Setup
Seeder::run();

// Ensure test settings
Config::set('site_title', 'SEOMaker');
Config::set('site_description', 'Test description');
Config::set('site_url', 'https://example.com');
Config::set('og_image', '/uploads/og.png');

$page = new Page();
$page->slug = 'sobre';
$page->title = 'Sobre Nós';
$page->meta_title = 'Sobre';
$page->meta_description = 'Descrição sobre';

$meta = SeoManager::metaTags($page);

assertTrue(strpos($meta, '<link rel="canonical" href="https://example.com/sobre">') !== false, 'canonical tag contains absolute url');
assertTrue(strpos($meta, '<meta name="twitter:card" content="summary_large_image">') !== false, 'twitter card is present');
assertTrue(strpos($meta, '<meta name="twitter:title" content="Sobre">') !== false, 'twitter title is present');
assertTrue(strpos($meta, '<meta name="twitter:image" content="https://example.com/uploads/og.png">') !== false, 'twitter image uses og_image');

// Homepage canonical
$home = new Page();
$home->slug = '';
$home->title = 'Home';
$homeMeta = SeoManager::metaTags($home);
assertTrue(strpos($homeMeta, 'href="https://example.com/"') !== false, 'homepage canonical is root');

// Without og_image
Config::set('og_image', '');
$noOg = SeoManager::metaTags($page);
assertTrue(strpos($noOg, 'twitter:image') === false, 'twitter image omitted when no og_image');

echo "\nAll SeoManager tests passed.\n";
