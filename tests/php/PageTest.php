<?php

declare(strict_types=1);

require_once __DIR__ . '/../../src/autoload.php';

use App\Core\Database;
use App\Core\Seeder;
use App\Models\Page;

if (!function_exists('assertTrue')) {
    function assertTrue(bool $condition, string $message): void
    {
        if (!$condition) {
            throw new \RuntimeException("FAIL: {$message}");
        }
        echo "PASS: {$message}\n";
    }
}

if (!function_exists('assertEquals')) {
    function assertEquals($expected, $actual, string $message): void
    {
        if ($expected !== $actual) {
            throw new \RuntimeException("FAIL: {$message} — expected " . var_export($expected, true) . ', got ' . var_export($actual, true));
        }
        echo "PASS: {$message}\n";
    }
}

// Test generateSlug
assertEquals('sobre-nos', Page::generateSlug('Sobre Nós'), 'generateSlug accents and spaces');
assertEquals('ola-mundo', Page::generateSlug('Olá, Mundo!!!'), 'generateSlug punctuation');
assertEquals('test-123', Page::generateSlug('Test 123'), 'generateSlug numbers');
assertEquals('', Page::generateSlug(''), 'generateSlug empty title');
assertEquals('cafe-com-leite', Page::generateSlug('Café com Leite'), 'generateSlug lowercase with accents');

// Test validation
$errors = Page::validate(['title' => '', 'slug' => '']);
assertTrue(isset($errors['title']), 'validation requires title');
assertTrue(!isset($errors['slug']), 'empty slug is allowed');

$errors = Page::validate(['title' => 'Ok', 'slug' => 'slug-invalido_aqui']);
assertTrue(isset($errors['slug']), 'invalid chars rejected');

$errors = Page::validate(['title' => 'Ok', 'slug' => 'valido-aqui-123']);
assertTrue(empty($errors), 'valid slug passes');

// Duplicate check needs DB
Seeder::run();
$existing = Database::fetchOne("SELECT slug, id FROM pages WHERE slug != '' LIMIT 1");
if ($existing) {
    $errors = Page::validate(['title' => 'X', 'slug' => $existing['slug']]);
    assertTrue(isset($errors['slug']), 'duplicate slug rejected');
    
    $errors = Page::validate(['title' => 'X', 'slug' => $existing['slug']], (int) $existing['id']);
    assertTrue(empty($errors), 'same id excluded from duplicate check');
}

echo "\nAll Page tests passed.\n";
