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
        if ($page->canonical_url !== null && $page->canonical_url !== '') {
            $canonical = $page->canonical_url;
            if (str_starts_with($canonical, '/')) {
                $canonical = $siteUrl . $canonical;
            }
        } else {
            $canonical = $siteUrl . ($page->slug === '' ? '/' : '/page/' . $page->slug);
        }
        
        $title = $page->meta_title ?: $page->title;
        $fullTitle = $title ? $title . ' | ' . $siteTitle : $siteTitle;
        $description = $page->meta_description ?: $siteDesc;
        
        $tags = [];
        $tags[] = '<title>' . self::e($fullTitle) . '</title>';
        $tags[] = '<meta name="description" content="' . self::e($description) . '">';
        $tags[] = '<meta name="robots" content="' . self::e($page->meta_robots ?: Page::defaultMetaRobots()) . '">';
        $tags[] = '<link rel="canonical" href="' . self::e($canonical) . '">';
        
        $ogImage = $page->ogImageUrl() ?: Config::get('og_image');
        $ogImageAbsolute = $ogImage && !str_starts_with($ogImage, 'http') ? $siteUrl . $ogImage : $ogImage;
        
        // Open Graph
        $tags[] = '<meta property="og:title" content="' . self::e($title) . '">';
        $tags[] = '<meta property="og:description" content="' . self::e($description) . '">';
        $tags[] = '<meta property="og:url" content="' . self::e($canonical) . '">';
        $tags[] = '<meta property="og:type" content="website">';
        if ($ogImageAbsolute) {
            $tags[] = '<meta property="og:image" content="' . self::e($ogImageAbsolute) . '">';
        }
        
        // Twitter Card
        $tags[] = '<meta name="twitter:card" content="summary_large_image">';
        $tags[] = '<meta name="twitter:title" content="' . self::e($title) . '">';
        $tags[] = '<meta name="twitter:description" content="' . self::e($description) . '">';
        if ($ogImageAbsolute) {
            $tags[] = '<meta name="twitter:image" content="' . self::e($ogImageAbsolute) . '">';
        }
        
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
        $faqSchema = self::faqSchema($page);
        if ($faqSchema !== null) {
            $schema['@graph'] = [$schema, $faqSchema];
        }
        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
    }
    
    public static function faqSchema(Page $page): ?array
    {
        $items = self::extractFaqItems($page);
        if (empty($items)) {
            return null;
        }
        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $items,
        ];
    }
    
    /**
     * @return array<int, array<string, mixed>>
     */
    private static function extractFaqItems(Page $page): array
    {
        $contentBlocks = $page->content_blocks ?? null;
        if (!$contentBlocks || !is_string($contentBlocks)) {
            return [];
        }
        $blocks = json_decode($contentBlocks, true);
        if (!is_array($blocks)) {
            return [];
        }
        
        $entities = [];
        foreach ($blocks as $block) {
            if (!is_array($block) || ($block['type'] ?? '') !== 'faq') {
                continue;
            }
            foreach ($block['items'] ?? [] as $item) {
                if (!is_array($item)) {
                    continue;
                }
                $question = trim(strip_tags((string) ($item['question'] ?? '')));
                $answer = trim(strip_tags((string) ($item['answer'] ?? '')));
                if ($question === '' || $answer === '') {
                    continue;
                }
                $entities[] = [
                    '@type' => 'Question',
                    'name' => $question,
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $answer,
                    ],
                ];
            }
        }
        return $entities;
    }
    
    /**
     * @return array<int, array<string, string>>
     */
    public static function breadcrumbItems(Page $page): array
    {
        $siteTitle = Config::get('site_title', 'Site');
        $items = [
            ['name' => $siteTitle, 'url' => '/'],
        ];
        if ($page->slug !== '') {
            $items[] = [
                'name' => $page->title ?: $page->meta_title,
                'url' => '/page/' . $page->slug,
            ];
        }
        return $items;
    }
    
    public static function breadcrumbSchema(Page $page): string
    {
        $siteUrl = rtrim(Config::get('site_url', 'https://example.com'), '/');
        $items = [];
        foreach (self::breadcrumbItems($page) as $index => $item) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => $siteUrl . $item['url'],
            ];
        }
        
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
        
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
