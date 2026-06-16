<?php

declare(strict_types=1);

require_once __DIR__ . '/../../src/autoload.php';

use App\Core\Database;
use App\Core\FileCache;
use App\Seo\RobotsBuilder;
use App\Seo\SitemapGenerator;

function assertCache(bool $condition, string $message): void
{
    if (!$condition) {
        throw new \RuntimeException("FAIL: {$message}");
    }
    echo "PASS: {$message}\n";
}

// Start with a clean cache state
FileCache::delete(SitemapGenerator::CACHE_KEY);
FileCache::delete(RobotsBuilder::CACHE_KEY);

// Ensure we have at least the homepage
$home = Database::fetchOne("SELECT * FROM pages WHERE slug = ''");
if (!$home) {
    Database::insert('pages', [
        'slug' => '',
        'title' => 'Home Cache Test',
        'meta_title' => 'Home Cache Test',
        'meta_description' => 'Test',
        'content_html' => '<p>Test</p>',
        'schema_type' => 'WebSite',
        'schema_data' => '{}',
        'is_active' => 1,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ]);
}

// Sitemap cache generation
$sitemap = SitemapGenerator::cachedGenerate();
assertCache(str_starts_with($sitemap, '<?xml version="1.0" encoding="UTF-8"?>'), 'sitemap cached generate returns xml');
assertCache(FileCache::get(SitemapGenerator::CACHE_KEY) === $sitemap, 'sitemap cache file matches generated content');

$secondSitemap = SitemapGenerator::cachedGenerate();
assertCache($secondSitemap === $sitemap, 'subsequent sitemap request returns cached content');

// Robots cache generation
$robots = RobotsBuilder::cachedGenerate();
assertCache(str_contains($robots, 'User-agent: *'), 'robots cached generate returns robots content');
assertCache(FileCache::get(RobotsBuilder::CACHE_KEY) === $robots, 'robots cache file matches generated content');

$secondRobots = RobotsBuilder::cachedGenerate();
assertCache($secondRobots === $robots, 'subsequent robots request returns cached content');

// Simulate page save invalidation
FileCache::delete(SitemapGenerator::CACHE_KEY);
FileCache::delete(RobotsBuilder::CACHE_KEY);

assertCache(FileCache::get(SitemapGenerator::CACHE_KEY) === null, 'sitemap cache removed after invalidation');
assertCache(FileCache::get(RobotsBuilder::CACHE_KEY) === null, 'robots cache removed after invalidation');

// Regenerate after invalidation
$regenerated = SitemapGenerator::cachedGenerate();
assertCache(str_starts_with($regenerated, '<?xml version="1.0" encoding="UTF-8"?>'), 'sitemap regenerates after invalidation');
assertCache(FileCache::get(SitemapGenerator::CACHE_KEY) === $regenerated, 'regenerated sitemap cache file matches content');

// Cleanup
FileCache::delete(SitemapGenerator::CACHE_KEY);
FileCache::delete(RobotsBuilder::CACHE_KEY);
