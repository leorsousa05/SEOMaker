<?php

declare(strict_types=1);

namespace App\Seo;

use App\Core\Config;

class RobotsBuilder
{
    public static function generate(): string
    {
        $custom = Config::get('robots_txt_custom', '');
        
        if (trim($custom) !== '') {
            return trim($custom) . PHP_EOL;
        }
        
        $siteUrl = rtrim(Config::get('site_url', ''), '/');
        
        $output = 'User-agent: *' . PHP_EOL;
        $output .= 'Allow: /' . PHP_EOL;
        
        if ($siteUrl !== '') {
            $output .= PHP_EOL;
            $output .= 'Sitemap: ' . $siteUrl . '/sitemap.xml' . PHP_EOL;
        }
        
        return $output;
    }
}
