<?php

declare(strict_types=1);

namespace App\Seo;

use App\Core\Config;
use App\Models\Page;

class SchemaGenerator
{
    /**
     * @return array<string, mixed>
     */
    public static function generate(Page $page): array
    {
        $siteUrl = Config::get('site_url', 'https://example.com');
        $siteName = Config::get('site_title', 'Site');
        
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => $page->schema_type,
            'url' => $siteUrl . ($page->slug === '' ? '/' : '/' . $page->slug),
            'name' => $page->meta_title ?: $page->title,
        ];
        
        if ($page->meta_description) {
            $schema['description'] = $page->meta_description;
        }
        
        // Merge custom schema data
        $custom = json_decode($page->schema_data, true);
        if (is_array($custom)) {
            $schema = array_merge($schema, $custom);
        }
        
        // Add WebSite schema for homepage
        if ($page->slug === '') {
            $schema['potentialAction'] = [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => $siteUrl . '/search?q={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ];
        }
        
        return $schema;
    }
    
    /**
     * @return array<string, mixed>
     */
    public static function organization(): array
    {
        $org = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => Config::get('site_title', ''),
            'url' => Config::get('site_url', ''),
        ];
        
        if ($logo = Config::get('site_logo')) {
            $org['logo'] = Config::get('site_url', '') . $logo;
        }
        
        return $org;
    }
}
