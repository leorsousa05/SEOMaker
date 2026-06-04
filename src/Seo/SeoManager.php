<?php

declare(strict_types=1);

namespace App\Seo;

use App\Core\Config;
use App\Models\Page;

class SeoManager
{
    public static function metaTags(Page $page): string
    {
        $siteTitle = Config::get('site_title', 'Site');
        $siteDesc = Config::get('site_description', '');
        $siteUrl = rtrim(Config::get('site_url', 'https://example.com'), '/');
        $canonical = $siteUrl . ($page->slug === '' ? '/' : '/page/' . $page->slug);
        
        $title = $page->meta_title ?: $page->title;
        $fullTitle = $title ? $title . ' | ' . $siteTitle : $siteTitle;
        $description = $page->meta_description ?: $siteDesc;
        
        $tags = [];
        $tags[] = '<title>' . self::e($fullTitle) . '</title>';
        $tags[] = '<meta name="description" content="' . self::e($description) . '">';
        $tags[] = '<link rel="canonical" href="' . self::e($canonical) . '">';
        
        // Open Graph
        $tags[] = '<meta property="og:title" content="' . self::e($title) . '">';
        $tags[] = '<meta property="og:description" content="' . self::e($description) . '">';
        $tags[] = '<meta property="og:url" content="' . self::e($canonical) . '">';
        $tags[] = '<meta property="og:type" content="website">';
        if ($ogImage = Config::get('og_image')) {
            $tags[] = '<meta property="og:image" content="' . self::e($siteUrl . $ogImage) . '">';
        }
        
        // Twitter Card
        $tags[] = '<meta name="twitter:card" content="summary_large_image">';
        $tags[] = '<meta name="twitter:title" content="' . self::e($title) . '">';
        $tags[] = '<meta name="twitter:description" content="' . self::e($description) . '">';
        
        // Analytics
        if ($gaId = Config::get('analytics_id')) {
            $tags[] = "<!-- Google tag (gtag.js) -->";
            $tags[] = "<script async src=\"https://www.googletagmanager.com/gtag/js?id=" . self::e($gaId) . "\"></script>";
            $tags[] = "<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','" . self::e($gaId) . "');</script>";
        }
        
        return implode("\n", $tags);
    }
    
    public static function schemaJsonLd(Page $page): string
    {
        $schema = SchemaGenerator::generate($page);
        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
    }
    
    public static function organizationSchema(): string
    {
        $schema = SchemaGenerator::organization();
        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
    }
    
    private static function e(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
}
