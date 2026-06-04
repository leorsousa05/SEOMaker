<?php

declare(strict_types=1);

require_once __DIR__ . '/../../src/autoload.php';

use App\Seo\SchemaFormBuilder;

function assertSchema(bool $condition, string $message): void
{
    if (!$condition) {
        throw new \RuntimeException("FAIL: {$message}");
    }
    echo "PASS: {$message}\n";
}

// Test: types() returns array
$types = SchemaFormBuilder::types();
assertSchema(count($types) > 0, 'types returns non-empty array');
assertSchema(in_array('Organization', $types, true), 'Organization is in types');
assertSchema(in_array('Product', $types, true), 'Product is in types');

// Test: fieldsForType returns fields for known types
$orgFields = SchemaFormBuilder::fieldsForType('Organization');
assertSchema(count($orgFields) > 0, 'Organization has fields');
assertSchema($orgFields[0]['name'] === 'schema_name', 'Organization first field is schema_name');
assertSchema($orgFields[3]['key'] === 'contactPoint.telephone', 'Organization has dot notation field');

// Test: fieldsForType returns empty for unknown type
$unknown = SchemaFormBuilder::fieldsForType('UnknownType');
assertSchema(count($unknown) === 0, 'Unknown type returns empty fields');

// Test: buildJson creates correct structure
$postData = [
    'schema_name' => 'Minha Empresa',
    'schema_url' => 'https://example.com',
    'schema_phone' => '+55 11 99999-9999',
    'schema_email' => 'contato@example.com',
];
$json = SchemaFormBuilder::buildJson($postData, 'Organization');
$decoded = json_decode($json, true);
assertSchema($decoded['@type'] === 'Organization', 'buildJson has correct @type');
assertSchema($decoded['name'] === 'Minha Empresa', 'buildJson has correct name');
assertSchema(isset($decoded['contactPoint']['telephone']), 'buildJson creates nested contactPoint.telephone');
assertSchema($decoded['contactPoint']['telephone'] === '+55 11 99999-9999', 'buildJson nested value correct');
assertSchema($decoded['contactPoint']['email'] === 'contato@example.com', 'buildJson nested email correct');

// Test: buildJson skips empty values
$postData2 = [
    'schema_name' => 'Teste',
    'schema_email' => '',
];
$json2 = SchemaFormBuilder::buildJson($postData2, 'Organization');
$decoded2 = json_decode($json2, true);
assertSchema(!isset($decoded2['contactPoint']['email']), 'buildJson skips empty values');

// Test: parseJson extracts values
$testJson = json_encode([
    '@type' => 'Organization',
    'name' => 'Empresa Teste',
    'contactPoint' => [
        'telephone' => '1234',
        'email' => 'test@example.com',
    ],
]);
$values = SchemaFormBuilder::parseJson($testJson, 'Organization');
assertSchema($values['schema_name'] === 'Empresa Teste', 'parseJson extracts name');
assertSchema($values['schema_phone'] === '1234', 'parseJson extracts nested phone');
assertSchema($values['schema_email'] === 'test@example.com', 'parseJson extracts nested email');

// Test: parseJson with invalid JSON
$valuesInvalid = SchemaFormBuilder::parseJson('invalid', 'Organization');
assertSchema($valuesInvalid['schema_name'] === '', 'parseJson handles invalid JSON gracefully');

// Test: buildJson for Product with offers
$postProduct = [
    'schema_name' => 'Produto X',
    'schema_price' => '99.90',
    'schema_currency' => 'BRL',
];
$jsonProduct = SchemaFormBuilder::buildJson($postProduct, 'Product');
$decodedProduct = json_decode($jsonProduct, true);
assertSchema($decodedProduct['offers']['price'] === '99.90', 'buildJson handles Product offers.price');
assertSchema($decodedProduct['offers']['priceCurrency'] === 'BRL', 'buildJson handles Product offers.priceCurrency');

// Test: parseJson for Product
$valuesProduct = SchemaFormBuilder::parseJson($jsonProduct, 'Product');
assertSchema($valuesProduct['schema_price'] === '99.90', 'parseJson extracts Product price');
assertSchema($valuesProduct['schema_currency'] === 'BRL', 'parseJson extracts Product currency');

echo "\nAll SchemaFormBuilder tests passed.\n";
