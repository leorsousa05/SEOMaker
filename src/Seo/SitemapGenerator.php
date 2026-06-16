<?php

declare(strict_types=1);

namespace App\Seo;

use App\Core\Config;
use App\Core\Database;
use App\Core\FileCache;

class SitemapGenerator
{
    public const CACHE_KEY = 'sitemap.xml';

    public static function generate(): string
    {
        $siteUrl = rtrim(Config::get('site_url', 'https://example.com'), '/');
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        $pages = Database::fetchAll('SELECT slug, updated_at FROM pages WHERE is_active = 1 ORDER BY id');
        
        foreach ($pages as $page) {
            $loc = $page['slug'] === '' ? $siteUrl . '/' : $siteUrl . '/page/' . $page['slug'];
            $lastmod = !empty($page['updated_at']) ? date('Y-m-d', strtotime($page['updated_at'])) : date('Y-m-d');
            
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($loc) . "</loc>\n";
            $xml .= "    <lastmod>{$lastmod}</lastmod>\n";
            $xml .= "    <changefreq>weekly</changefreq>\n";
            $xml .= "    <priority>" . ($page['slug'] === '' ? '1.0' : '0.8') . "</priority>\n";
            $xml .= "  </url>\n";
        }
        
        $xml .= '</urlset>';
        
        return $xml;
    }

    public static function cachedGenerate(): string
    {
        $cached = FileCache::get(self::CACHE_KEY);
        if ($cached !== null) {
            return $cached;
        }

        $content = self::generate();
        FileCache::set(self::CACHE_KEY, $content);

        return $content;
    }
}
