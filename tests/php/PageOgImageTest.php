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

Seeder::run();

Config::set('site_title', 'Test Site');
Config::set('site_description', 'Test description');
Config::set('site_url', 'https://example.com');
Config::set('og_image', '/uploads/global-og.png');

$page = new Page();
$page->slug = 'sobre';
$page->title = 'Sobre Nós';

$meta = SeoManager::metaTags($page);
assertTrue(strpos($meta, 'content="https://example.com/uploads/global-og.png"') !== false, 'fallback to global og_image');

// Insert fake media
Database::insert('media', [
    'filename' => 'page-og.png',
    'original_name' => 'page-og.png',
    'mime_type' => 'image/png',
    'size_bytes' => 1000,
    'width' => 1200,
    'height' => 630,
    'path' => '/uploads/page-og.png',
    'created_at' => date('Y-m-d H:i:s'),
]);
$mediaId = (int) Database::getInstance()->lastInsertId();

$page->og_image_id = $mediaId;
$meta = SeoManager::metaTags($page);
assertTrue(strpos($meta, 'content="https://example.com/uploads/page-og.png"') !== false, 'page-level og image overrides global');

$page->og_image_id = 99999;
$meta = SeoManager::metaTags($page);
assertTrue(strpos($meta, 'content="https://example.com/uploads/global-og.png"') !== false, 'invalid media id falls back to global');

echo "\nAll PageOgImage tests passed.\n";
