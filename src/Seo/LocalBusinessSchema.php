<?php

declare(strict_types=1);

namespace App\Seo;

use App\Core\Config;
use App\Models\Address;

class LocalBusinessSchema
{
    /**
     * @param array<string, mixed> $extra
     * @return array<string, mixed>
     */
    public static function generate(Address $addr, array $extra = []): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => $extra['@type'] ?? 'LocalBusiness',
            'name' => $extra['name'] ?? Config::get('site_title', ''),
            'url' => $extra['url'] ?? Config::get('site_url', ''),
        ];
        
        if ($addr->street || $addr->city) {
            $schema['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => trim($addr->street . ' ' . $addr->number . ($addr->complement ? ' - ' . $addr->complement : '')),
                'addressLocality' => $addr->city,
                'addressRegion' => $addr->state,
                'postalCode' => $addr->zip,
                'addressCountry' => $addr->country,
            ];
        }
        
        if ($addr->lat && $addr->lng) {
            $schema['geo'] = [
                '@type' => 'GeoCoordinates',
                'latitude' => $addr->lat,
                'longitude' => $addr->lng,
            ];
        }
        
        if (!empty($extra['telephone'])) {
            $schema['telephone'] = $extra['telephone'];
        }
        
        if (!empty($extra['openingHours'])) {
            $schema['openingHours'] = $extra['openingHours'];
        }
        
        if (!empty($extra['priceRange'])) {
            $schema['priceRange'] = $extra['priceRange'];
        }
        
        return array_merge($schema, $extra);
    }
}
