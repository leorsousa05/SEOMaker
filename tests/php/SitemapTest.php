<?php

declare(strict_types=1);

require_once __DIR__ . '/../../src/autoload.php';

use App\Core\Config;
use App\Core\Database;
use App\Seo\SitemapGenerator;

function assertSitemap(bool $condition, string $message): void
{
    if (!$condition) {
        throw new \RuntimeException("FAIL: {$message}");
    }
    echo "PASS: {$message}\n";
}

// Ensure we have at least the homepage
$home = Database::fetchOne("SELECT * FROM pages WHERE slug = ''");
if (!$home) {
    Database::insert('pages', [
        'slug' => '',
        'title' => 'Home Test',
        'meta_title' => 'Home Test',
        'meta_description' => 'Test',
        'content_html' => '<p>Test</p>',
        'schema_type' => 'WebSite',
        'schema_data' => '{}',
        'is_active' => 1,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ]);
}

$inactive = Database::fetchOne("SELECT * FROM pages WHERE slug = 'inactive-test-page'");
if (!$inactive) {
    Database::insert('pages', [
        'slug' => 'inactive-test-page',
        'title' => 'Inactive Test',
        'meta_title' => 'Inactive',
        'meta_description' => 'Test',
        'content_html' => '<p>Test</p>',
        'schema_type' => 'WebPage',
        'schema_data' => '{}',
        'is_active' => 0,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ]);
}

$xml = SitemapGenerator::generate();

assertSitemap(str_starts_with($xml, '<?xml version="1.0" encoding="UTF-8"?>'), 'starts with xml declaration');
assertSitemap(str_contains($xml, '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'), 'contains urlset tag');
assertSitemap(str_contains($xml, '<priority>1.0</priority>'), 'homepage has priority 1.0');
assertSitemap(!str_contains($xml, 'inactive-test-page'), 'inactive page is excluded');
assertSitemap(str_contains($xml, '<lastmod>'), 'contains lastmod');
assertSitemap(str_contains($xml, '<changefreq>weekly</changefreq>'), 'contains changefreq weekly');

// Cleanup
Database::delete('pages', "slug = 'inactive-test-page'");
