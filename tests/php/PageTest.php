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

// Test in_menu property and mapping
$p = new Page();
assertTrue($p->in_menu === false, 'in_menu defaults to false');

$p2 = Page::fromArray(['title' => 'Menu Page', 'in_menu' => true]);
assertTrue($p2->in_menu === true, 'fromArray maps in_menu true');

$p3 = Page::fromArray(['title' => 'Non Menu Page', 'in_menu' => 0]);
assertTrue($p3->in_menu === false, 'fromArray maps in_menu false/0');

$arr = $p2->toArray();
assertEquals(1, $arr['in_menu'], 'toArray maps in_menu to 1');

$arr2 = $p3->toArray();
assertEquals(0, $arr2['in_menu'], 'toArray maps in_menu to 0');

// Test saving to DB and fetching
$testSlug = 'in-menu-test-' . uniqid();
Database::insert('pages', [
    'slug' => $testSlug,
    'title' => 'Menu DB Test',
    'in_menu' => 1,
    'is_active' => 1,
    'created_at' => date('Y-m-d H:i:s'),
]);

$fetched = Database::fetchOne("SELECT * FROM pages WHERE slug = ?", [$testSlug]);
assertTrue($fetched !== false, 'fetched test page');
$pageObj = Page::fromArray($fetched);
assertTrue($pageObj->in_menu === true, 'fetched page has in_menu true');

// Clean up
Database::delete('pages', "slug = ?", [$testSlug]);

echo "\nAll Page tests passed.\n";
