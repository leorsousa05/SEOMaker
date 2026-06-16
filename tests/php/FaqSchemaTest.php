<?php

declare(strict_types=1);

require_once __DIR__ . '/../../src/autoload.php';

use App\Core\Config;
use App\Core\Seeder;
use App\Models\Page;
use App\Seo\SeoManager;

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
Config::set('site_description', 'Test description');
Config::set('site_url', 'https://example.com');

$page = new Page();
$page->slug = 'sobre';
$page->title = 'Sobre Nós';
$page->content_blocks = json_encode([]);

$schemaHtml = SeoManager::schemaJsonLd($page);
assertTrue(strpos($schemaHtml, 'FAQPage') === false, 'no FAQPage schema when no faq block');

$page->content_blocks = json_encode([
    ['type' => 'faq', 'items' => [
        ['question' => 'O que é SEO?', 'answer' => '<p>SEO é otimização.</p>'],
        ['question' => '', 'answer' => 'Resposta sem pergunta'],
    ]],
]);

$schemaHtml = SeoManager::schemaJsonLd($page);
assertTrue(strpos($schemaHtml, 'FAQPage') !== false, 'FAQPage schema present');
assertTrue(strpos($schemaHtml, 'O que é SEO?') !== false, 'faq question in schema');
assertTrue(strpos($schemaHtml, 'Resposta sem pergunta') === false, 'empty question filtered out');
assertTrue(strpos($schemaHtml, '<p>') === false, 'html tags stripped from answer');

echo "\nAll FaqSchema tests passed.\n";
