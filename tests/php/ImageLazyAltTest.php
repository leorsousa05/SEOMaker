<?php

declare(strict_types=1);

require_once __DIR__ . '/../../src/autoload.php';

use App\Content\BlockEditor;

if (!function_exists('assertTrue')) {
    function assertTrue(bool $condition, string $message): void
    {
        if (!$condition) {
            throw new \RuntimeException("FAIL: {$message}");
        }
        echo "PASS: {$message}\n";
    }
}

// First image eager
$html = BlockEditor::render([
    ['type' => 'image', 'src' => '/img1.jpg', 'alt' => 'Primeira'],
    ['type' => 'image', 'src' => '/img2.jpg', 'alt' => 'Segunda'],
]);
assertTrue(strpos($html, 'loading="eager"') !== false, 'first image is eager');
assertTrue(strpos($html, 'loading="lazy"') !== false, 'subsequent images are lazy');

// Gallery images
$html = BlockEditor::render([
    ['type' => 'gallery', 'media_ids' => [], 'alt' => 'Galeria'],
]);
assertTrue(strpos($html, 'loading=') === false, 'empty gallery has no images');

// Validation: missing alt
$errors = BlockEditor::validateBlocks([
    ['type' => 'image', 'src' => '/img.jpg', 'alt' => ''],
]);
assertTrue(!empty($errors), 'missing alt triggers validation error');

// Validation: valid alt
$errors = BlockEditor::validateBlocks([
    ['type' => 'image', 'src' => '/img.jpg', 'alt' => 'Descrição'],
]);
assertTrue(empty($errors), 'valid alt passes validation');

// requiresAltText
assertTrue(BlockEditor::requiresAltText('image'), 'image requires alt');
assertTrue(BlockEditor::requiresAltText('gallery'), 'gallery requires alt');
assertTrue(!BlockEditor::requiresAltText('text'), 'text does not require alt');

echo "\nAll ImageLazyAlt tests passed.\n";
