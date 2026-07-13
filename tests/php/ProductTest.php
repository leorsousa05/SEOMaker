<?php

declare(strict_types=1);

require_once __DIR__ . '/../../src/autoload.php';

use App\Core\Seeder;
use App\Models\Product;

if (!function_exists('assertTrue')) {
    function assertTrue(bool $condition, string $message): void
    {
        if (!$condition) {
            throw new \RuntimeException("FAIL: {$message}");
        }
        echo "PASS: {$message}\n";
    }
}

Seeder::run();

// ── generateSlug ──────────────────────────────────────────────────────────────
$slug = Product::generateSlug('Camiseta Azul 100% Algodão');
assertTrue($slug === 'camiseta-azul-100-algodao', 'generateSlug: removes accents and special chars');

$slug2 = Product::generateSlug('  iPhone 15 Pro Max  ');
assertTrue($slug2 === 'iphone-15-pro-max', 'generateSlug: trims spaces and handles numbers');

$slug3 = Product::generateSlug('---Produto---');
assertTrue($slug3 === 'produto', 'generateSlug: strips leading and trailing hyphens');

// ── fromArray / toArray round-trip ────────────────────────────────────────────
$data = [
    'id'                => 5,
    'name'              => 'Tênis Esportivo',
    'slug'              => 'tenis-esportivo',
    'short_description' => 'Leve e confortável',
    'description'       => 'Descrição longa aqui',
    'price'             => '199.90',
    'promo_price'       => '149.90',
    'image_id'          => 3,
    'gallery_ids'       => '[1,2,3]',
    'category'          => 'Calçados',
    'tags'              => 'esporte,corrida',
    'sku'               => 'TEN-001',
    'stock'             => '10',
    'external_link'     => 'https://loja.com/tenis',
    'featured'          => '1',
    'is_active'         => '1',
    'created_at'        => '2024-01-01 00:00:00',
    'updated_at'        => '2024-06-01 00:00:00',
];

$product = Product::fromArray($data);
assertTrue($product->id === 5, 'fromArray: casts id to int');
assertTrue($product->name === 'Tênis Esportivo', 'fromArray: preserves name');
assertTrue($product->price === 199.90, 'fromArray: casts price to float');
assertTrue($product->promo_price === 149.90, 'fromArray: casts promo_price to float');
assertTrue($product->image_id === 3, 'fromArray: casts image_id to int');
assertTrue($product->stock === 10, 'fromArray: casts stock to int');
assertTrue($product->featured === true, 'fromArray: casts featured to bool');
assertTrue($product->is_active === true, 'fromArray: casts is_active to bool');
assertTrue($product->gallery_ids === '[1,2,3]', 'fromArray: preserves gallery_ids JSON string');

$arr = $product->toArray();
assertTrue($arr['featured'] === 1, 'toArray: bool featured becomes 1');
assertTrue($arr['is_active'] === 1, 'toArray: bool is_active becomes 1');
assertTrue($arr['price'] === 199.90, 'toArray: price preserved as float');

// ── validate: valid data ──────────────────────────────────────────────────────
$errors = Product::validate(['name' => 'Produto X', 'price' => '50.00', 'promo_price' => '', 'slug' => '']);
assertTrue(empty($errors), 'validate: no errors for valid data');

// ── validate: missing name ────────────────────────────────────────────────────
$errors = Product::validate(['name' => '', 'price' => '10', 'promo_price' => '', 'slug' => '']);
assertTrue(isset($errors['name']), 'validate: error when name is empty');

// ── validate: invalid price ───────────────────────────────────────────────────
$errors = Product::validate(['name' => 'X', 'price' => 'abc', 'promo_price' => '', 'slug' => '']);
assertTrue(isset($errors['price']), 'validate: error when price is not numeric');

$errors = Product::validate(['name' => 'X', 'price' => '-5', 'promo_price' => '', 'slug' => '']);
assertTrue(isset($errors['price']), 'validate: error when price is negative');

// ── validate: promo_price >= price ────────────────────────────────────────────
$errors = Product::validate(['name' => 'X', 'price' => '100', 'promo_price' => '150', 'slug' => '']);
assertTrue(isset($errors['promo_price']), 'validate: error when promo_price >= price');

// ── validate: invalid slug format ────────────────────────────────────────────
$errors = Product::validate(['name' => 'X', 'price' => '10', 'promo_price' => '', 'slug' => 'Invalid Slug!']);
assertTrue(isset($errors['slug']), 'validate: error for invalid slug characters');

// ── validate: null promo_price ────────────────────────────────────────────────
$errors = Product::validate(['name' => 'Y', 'price' => '99', 'promo_price' => null, 'slug' => 'produto-y']);
assertTrue(empty($errors), 'validate: null promo_price is allowed');

echo "\nAll ProductTest tests passed.\n";
