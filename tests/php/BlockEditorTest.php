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

echo "\nAll BlockEditor tests passed.\n";
