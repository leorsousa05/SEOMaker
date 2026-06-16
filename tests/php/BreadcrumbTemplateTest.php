<?php

declare(strict_types=1);

require_once __DIR__ . '/../../src/autoload.php';

use App\Core\Config;
use App\Core\Seeder;
use App\Core\View;
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

Seeder::run();

Config::set('site_title', 'Test Site');
Config::set('site_url', 'https://example.com');

$page = new Page();
$page->slug = 'sobre';
$page->title = 'Sobre Nós';

$rendered = View::partial('public/partials/_breadcrumbs', ['page' => $page]);
assertTrue(strpos($rendered, '<nav class="breadcrumb"') !== false, 'breadcrumb nav rendered');
assertTrue(strpos($rendered, 'Início') !== false, 'home item in breadcrumb');
assertTrue(strpos($rendered, 'Sobre Nós') !== false, 'page title in breadcrumb');
assertTrue(strpos($rendered, 'aria-current="page"') !== false, 'current page aria');

$home = new Page();
$home->slug = '';
$home->title = 'Home';
$rendered = View::partial('public/partials/_breadcrumbs', ['page' => $home]);
assertTrue($rendered === '', 'homepage has no breadcrumbs');

echo "\nAll BreadcrumbTemplate tests passed.\n";
