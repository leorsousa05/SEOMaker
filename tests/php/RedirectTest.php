<?php

declare(strict_types=1);

require_once __DIR__ . '/../../src/autoload.php';

use App\Core\Database;
use App\Core\Seeder;
use App\Models\Redirect;

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

Seeder::run();

// Validation tests
$errors = Redirect::validate(['from_path' => '', 'to_path' => '/destino']);
assertTrue(isset($errors['from_path']), 'validation requires from_path');

$errors = Redirect::validate(['from_path' => '/origem', 'to_path' => '']);
assertTrue(isset($errors['to_path']), 'validation requires to_path');

$errors = Redirect::validate(['from_path' => '/igual', 'to_path' => '/igual']);
assertTrue(isset($errors['from_path']), 'same origin and destination rejected');

$errors = Redirect::validate(['from_path' => '/nova', 'to_path' => '/destino']);
assertTrue(empty($errors), 'valid redirect passes');

// CRUD tests
$id = Redirect::create([
    'from_path' => '/teste-redirect',
    'to_path' => '/destino-redirect',
    'type' => '301',
    'is_active' => 1,
]);
assertTrue($id > 0, 'redirect created');

$found = Redirect::findByPath('/teste-redirect');
assertTrue($found !== null, 'findByPath returns redirect');
assertEquals('/destino-redirect', $found['to_path'], 'to_path matches');

$inactive = Redirect::findByPath('/nao-existe');
assertTrue($inactive === null, 'findByPath returns null for missing');

$all = Redirect::findAll();
assertTrue(count($all) >= 1, 'findAll returns redirects');

// Duplicate validation
$errors = Redirect::validate(['from_path' => '/teste-redirect', 'to_path' => '/outro']);
assertTrue(isset($errors['from_path']), 'duplicate from_path rejected');

// Exclude id allows same path
$errors = Redirect::validate(['from_path' => '/teste-redirect', 'to_path' => '/outro'], $id);
assertTrue(empty($errors), 'same id excluded from duplicate check');

// Update
Redirect::update($id, ['to_path' => '/novo-destino']);
$updated = Redirect::findByPath('/teste-redirect');
assertEquals('/novo-destino', $updated['to_path'], 'update changes to_path');

// Delete
Redirect::delete($id);
$deleted = Redirect::findByPath('/teste-redirect');
assertTrue($deleted === null, 'delete removes redirect');

echo "\nAll Redirect tests passed.\n";
