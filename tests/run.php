<?php

declare(strict_types=1);

echo "=== PHP Tests ===\n";
require __DIR__ . '/php/SettingsControllerTest.php';

echo "\n=== JS Tests ===\n";
if (shell_exec('which node')) {
    passthru('node ' . __DIR__ . '/js/tabs.test.js');
} else {
    echo "Node not available. Skipping JS tests.\n";
}

echo "\nDone.\n";
