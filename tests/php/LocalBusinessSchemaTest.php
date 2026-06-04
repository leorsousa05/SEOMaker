<?php

declare(strict_types=1);

require_once __DIR__ . '/../../src/autoload.php';

use App\Models\Address;
use App\Seo\LocalBusinessSchema;

function assertLocalBusiness(bool $condition, string $message): void
{
    if (!$condition) {
        throw new \RuntimeException("FAIL: {$message}");
    }
    echo "PASS: {$message}\n";
}

$addr = new Address();
$addr->street = 'Av. Paulista';
$addr->number = '1000';
$addr->complement = 'Sala 101';
$addr->neighborhood = 'Bela Vista';
$addr->city = 'São Paulo';
$addr->state = 'SP';
$addr->zip = '01310-100';
$addr->country = 'Brasil';
$addr->lat = '-23.5505';
$addr->lng = '-46.6333';

// Test: basic generation
$schema = LocalBusinessSchema::generate($addr);
assertLocalBusiness($schema['@type'] === 'LocalBusiness', 'schema type is LocalBusiness');
assertLocalBusiness(isset($schema['address']), 'schema has address');
assertLocalBusiness($schema['address']['@type'] === 'PostalAddress', 'address is PostalAddress');
assertLocalBusiness(str_contains($schema['address']['streetAddress'], 'Av. Paulista'), 'streetAddress contains street');
assertLocalBusiness(str_contains($schema['address']['streetAddress'], '1000'), 'streetAddress contains number');
assertLocalBusiness($schema['address']['addressLocality'] === 'São Paulo', 'addressLocality is city');
assertLocalBusiness($schema['address']['addressRegion'] === 'SP', 'addressRegion is state');
assertLocalBusiness($schema['address']['addressCountry'] === 'Brasil', 'addressCountry is correct');

// Test: geo coordinates
assertLocalBusiness(isset($schema['geo']), 'schema has geo');
assertLocalBusiness($schema['geo']['@type'] === 'GeoCoordinates', 'geo is GeoCoordinates');
assertLocalBusiness($schema['geo']['latitude'] === '-23.5505', 'latitude correct');
assertLocalBusiness($schema['geo']['longitude'] === '-46.6333', 'longitude correct');

// Test: extra fields
$schema = LocalBusinessSchema::generate($addr, [
    'telephone' => '+55 11 99999-9999',
    'openingHours' => 'Mo-Fr 09:00-18:00',
    'priceRange' => '$$',
]);
assertLocalBusiness($schema['telephone'] === '+55 11 99999-9999', 'extra telephone included');
assertLocalBusiness($schema['openingHours'] === 'Mo-Fr 09:00-18:00', 'extra openingHours included');
assertLocalBusiness($schema['priceRange'] === '$$', 'extra priceRange included');

// Test: custom type
$schema = LocalBusinessSchema::generate($addr, ['@type' => 'Restaurant']);
assertLocalBusiness($schema['@type'] === 'Restaurant', 'custom @type overrides default');

// Test: fullAddress
assertLocalBusiness(str_contains($addr->fullAddress(), 'Av. Paulista'), 'fullAddress contains street');
assertLocalBusiness(str_contains($addr->fullAddress(), 'São Paulo'), 'fullAddress contains city');

echo "\nAll LocalBusinessSchema tests passed.\n";
