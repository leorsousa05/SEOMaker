<?php

declare(strict_types=1);

require __DIR__ . '/../../src/autoload.php';

use App\Core\View;
use App\Models\Page;

function assertContains(string $needle, string $haystack, string $message): void
{
    if (strpos($haystack, $needle) === false) {
        echo "FAIL: {$message}\n";
        echo "Expected to contain: {$needle}\n";
        exit(1);
    }
    echo "PASS: {$message}\n";
}

$features = [
    ['icon' => '<svg></svg>', 'title' => 'Feature 1', 'desc' => 'Description 1'],
    ['icon' => '<svg></svg>', 'title' => 'Feature 2', 'desc' => 'Description 2'],
];

$html = View::render('public/home', [
    'page' => new Page(),
    'features' => $features,
    'isHome' => true,
]);

assertContains('id="editor"', $html, 'homepage has editor showcase section');
assertContains('id="seo"', $html, 'homepage has seo showcase section');
assertContains('showcase-section"', $html, 'homepage has showcase section class');
assertContains('showcase-section--reverse', $html, 'homepage has reverse showcase section');
assertContains('showcase-section--dark', $html, 'homepage has dark showcase section');
assertContains('Editor de Página', $html, 'editor mockup header is rendered');
assertContains('SEO da Página', $html, 'seo mockup header is rendered');
assertContains('Blocos JSON', $html, 'editor floating card Blocos JSON is rendered');
assertContains('Schema.org', $html, 'seo floating card Schema.org is rendered');
assertContains('Crie páginas com blocos', $html, 'editor section heading is rendered');
assertContains('Schema.org, sitemap e meta tags', $html, 'seo section heading is rendered');

echo "\nAll HomeTemplate tests passed.\n";
