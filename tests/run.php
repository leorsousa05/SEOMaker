<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/autoload.php';
\App\Core\Seeder::run();

echo "=== PHP Tests ===\n";
require __DIR__ . '/php/SettingsControllerTest.php';
echo "\n";
require __DIR__ . '/php/SchemaFormBuilderTest.php';
echo "\n";
require __DIR__ . '/php/BlockEditorTest.php';
echo "\n";
require __DIR__ . '/php/LocalBusinessSchemaTest.php';
echo "\n";
require __DIR__ . '/php/SitemapTest.php';
echo "\n";
require __DIR__ . '/php/RobotsTest.php';
echo "\n";
require __DIR__ . '/php/ContactMessageTest.php';
echo "\n";
require __DIR__ . '/php/SeoManagerTest.php';
echo "\n";
require __DIR__ . '/php/PageTest.php';
echo "\n";
require __DIR__ . '/php/BreadcrumbSchemaTest.php';
echo "\n";
require __DIR__ . '/php/RedirectTest.php';
echo "\n";
require __DIR__ . '/php/MediaManagerTest.php';
echo "\n";
require __DIR__ . '/php/ProductTest.php';



echo "\n=== JS Tests ===\n";
if (shell_exec('which node')) {
    passthru('node ' . __DIR__ . '/js/tabs.test.js');
} else {
    echo "Node not available. Skipping JS tests.\n";
}

echo "\nDone.\n";
