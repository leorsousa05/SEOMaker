<?php

declare(strict_types=1);

require_once __DIR__ . '/../../src/autoload.php';

use App\Admin\SettingsController;

function assertTrue(bool $condition, string $message): void
{
    if (!$condition) {
        throw new \RuntimeException("FAIL: {$message}");
    }
    echo "PASS: {$message}\n";
}

// Test: groups are defined
$controller = new SettingsController();
$reflection = new \ReflectionClass($controller);

$groupsProp = $reflection->getProperty('groups');
$groupsProp->setAccessible(true);
$groups = $groupsProp->getValue($controller);

assertTrue(isset($groups['geral']), 'groups contain geral');
assertTrue(isset($groups['seo']), 'groups contain seo');
assertTrue(isset($groups['contato']), 'groups contain contato');
assertTrue(isset($groups['email']), 'groups contain email');
assertTrue(isset($groups['social']), 'groups contain social');

// Test: each group has label and keys
foreach ($groups as $key => $group) {
    assertTrue(isset($group['label']) && is_string($group['label']), "group {$key} has label");
    assertTrue(isset($group['keys']) && is_array($group['keys']), "group {$key} has keys array");
}

// Test: labels map all keys
$labelsProp = $reflection->getProperty('labels');
$labelsProp->setAccessible(true);
$labels = $labelsProp->getValue($controller);

foreach ($groups as $group) {
    foreach ($group['keys'] as $key) {
        assertTrue(isset($labels[$key]), "label exists for key: {$key}");
    }
}

// Test: no duplicate keys across groups
$allKeys = [];
foreach ($groups as $group) {
    foreach ($group['keys'] as $key) {
        assertTrue(!in_array($key, $allKeys, true), "key {$key} is not duplicated");
        $allKeys[] = $key;
    }
}

echo "\nAll SettingsController tests passed.\n";
