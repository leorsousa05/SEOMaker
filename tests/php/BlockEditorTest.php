<?php

declare(strict_types=1);

require_once __DIR__ . '/../../src/autoload.php';

use App\Content\BlockEditor;

function assertBlock(bool $condition, string $message): void
{
    if (!$condition) {
        throw new \RuntimeException("FAIL: {$message}");
    }
    echo "PASS: {$message}\n";
}

// Test: render text block
$blocks = [['type' => 'text', 'content' => '<p>Hello World</p>']];
$html = BlockEditor::render($blocks);
assertBlock(str_contains($html, '<p>Hello World</p>'), 'text block renders content');
assertBlock(str_contains($html, 'block-text'), 'text block has correct class');

// Test: render CTA block
$blocks = [['type' => 'cta', 'text' => 'Click Me', 'url' => '/test', 'style' => 'primary']];
$html = BlockEditor::render($blocks);
assertBlock(str_contains($html, 'Click Me'), 'cta block renders text');
assertBlock(str_contains($html, 'href="/test"'), 'cta block renders url');
assertBlock(str_contains($html, 'btn-primary'), 'cta block has primary style');

// Test: render FAQ block
$blocks = [['type' => 'faq', 'items' => [['question' => 'Q1?', 'answer' => 'A1.']]]];
$html = BlockEditor::render($blocks);
assertBlock(str_contains($html, '<details'), 'faq block uses details tag');
assertBlock(str_contains($html, 'Q1?'), 'faq block renders question');
assertBlock(str_contains($html, 'A1.'), 'faq block renders answer');

// Test: render spacer block
$blocks = [['type' => 'spacer', 'height' => 60]];
$html = BlockEditor::render($blocks);
assertBlock(str_contains($html, 'height:60px'), 'spacer block renders height');

// Test: render video block (YouTube)
$blocks = [['type' => 'video', 'url' => 'https://youtube.com/watch?v=abc123']];
$html = BlockEditor::render($blocks);
assertBlock(str_contains($html, 'youtube.com/embed/abc123'), 'video block extracts YouTube ID');

// Test: render video block (Vimeo)
$blocks = [['type' => 'video', 'url' => 'https://vimeo.com/123456']];
$html = BlockEditor::render($blocks);
assertBlock(str_contains($html, 'player.vimeo.com/video/123456'), 'video block extracts Vimeo ID');

// Test: getVideoEmbedUrl
assertBlock(BlockEditor::getVideoEmbedUrl('https://youtu.be/xyz') === 'https://www.youtube.com/embed/xyz', 'YouTube short URL');
assertBlock(BlockEditor::getVideoEmbedUrl('https://vimeo.com/999') === 'https://player.vimeo.com/video/999', 'Vimeo URL');
assertBlock(BlockEditor::getVideoEmbedUrl('https://example.com') === null, 'Invalid video URL returns null');

// Test: sanitizeHtml removes script
$dirty = '<p>Safe</p><script>alert("xss")</script><iframe src="bad"></iframe><a href="javascript:bad()">click</a>';
$clean = BlockEditor::sanitizeHtml($dirty);
assertBlock(!str_contains($clean, '<script>'), 'sanitize removes script tags');
assertBlock(!str_contains($clean, '<iframe>'), 'sanitize removes iframe tags');
assertBlock(!str_contains($clean, 'javascript:'), 'sanitize removes javascript: URLs');
assertBlock(str_contains($clean, '<p>Safe</p>'), 'sanitize keeps safe content');

// Test: empty blocks returns empty
assertBlock(BlockEditor::render([]) === '', 'empty blocks returns empty string');

// Test: unknown block type returns empty
$blocks = [['type' => 'unknown']];
$html = BlockEditor::render($blocks);
assertBlock($html === '', 'unknown block type returns empty');

// Test: defaultBlocks
$default = BlockEditor::defaultBlocks();
assertBlock(count($default) === 1, 'defaultBlocks returns 1 block');
assertBlock($default[0]['type'] === 'text', 'defaultBlocks first is text');

// Test: lazy loading default (true/not set) for image block
$blocks = [['type' => 'image', 'src' => '/test.jpg']];
$html = BlockEditor::render($blocks);
assertBlock(str_contains($html, 'loading="lazy"'), 'image block has loading="lazy" by default');

// Test: lazy loading disabled (lazy = false) for image block
$blocks = [['type' => 'image', 'src' => '/test.jpg', 'lazy' => false]];
$html = BlockEditor::render($blocks);
assertBlock(!str_contains($html, 'loading="lazy"'), 'image block does not have loading="lazy" when lazy is false');

// Test: lazy loading default (true/not set) for video block
$blocks = [['type' => 'video', 'url' => 'https://youtube.com/watch?v=abc123']];
$html = BlockEditor::render($blocks);
assertBlock(str_contains($html, 'loading="lazy"'), 'video block has loading="lazy" by default');

// Test: lazy loading disabled (lazy = false) for video block
$blocks = [['type' => 'video', 'url' => 'https://youtube.com/watch?v=abc123', 'lazy' => false]];
$html = BlockEditor::render($blocks);
assertBlock(!str_contains($html, 'loading="lazy"'), 'video block does not have loading="lazy" when lazy is false');

// Test: map block fallback address rendering when address_id is 0 / address not found
$blocks = [['type' => 'map', 'address_id' => 0, 'zoom' => 12]];
$html = BlockEditor::render($blocks, 'Rua Teste, 123, São Paulo');
assertBlock(str_contains($html, 'maps.google.com/maps?q=Rua+Teste%2C+123%2C+S%C3%A3o+Paulo'), 'map fallback address is rendered in maps URL');
assertBlock(str_contains($html, 'loading="lazy"'), 'map fallback block has loading="lazy" by default');

// Test: map block fallback address lazy loading disabled
$blocks = [['type' => 'map', 'address_id' => 0, 'zoom' => 12, 'lazy' => false]];
$html = BlockEditor::render($blocks, 'Rua Teste, 123, São Paulo');
assertBlock(!str_contains($html, 'loading="lazy"'), 'map fallback block does not have loading="lazy" when lazy is false');

// Test: map block empty fallback returns empty string
$blocks = [['type' => 'map', 'address_id' => 0, 'zoom' => 12]];
$html = BlockEditor::render($blocks, '');
assertBlock($html === '', 'map block with no address and empty fallback returns empty string');

// Test: gallery block rendering and lazy loading
use App\Core\Database;
use App\Core\Seeder;
Seeder::run();
$mediaId = Database::insert('media', [
    'filename' => 'test_gallery.jpg',
    'original_name' => 'test_gallery.jpg',
    'mime_type' => 'image/jpeg',
    'size_bytes' => 100,
    'path' => '/uploads/test_gallery.jpg',
    'created_at' => date('Y-m-d H:i:s'),
]);

try {
    // Gallery block lazy default
    $blocks = [['type' => 'gallery', 'media_ids' => [$mediaId], 'columns' => 3]];
    $html = BlockEditor::render($blocks);
    assertBlock(str_contains($html, 'loading="lazy"'), 'gallery block has loading="lazy" by default');
    assertBlock(str_contains($html, 'src="/uploads/test_gallery.jpg"'), 'gallery block renders media path');

    // Gallery block lazy false
    $blocks = [['type' => 'gallery', 'media_ids' => [$mediaId], 'columns' => 3, 'lazy' => false]];
    $html = BlockEditor::render($blocks);
    assertBlock(!str_contains($html, 'loading="lazy"'), 'gallery block does not have loading="lazy" when lazy is false');
} finally {
    // Clean up
    Database::delete('media', 'id = ?', [$mediaId]);
}

echo "\nAll BlockEditor tests passed.\n";
