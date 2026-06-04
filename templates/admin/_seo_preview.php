<?php
/** @var array<string, mixed>|null $page */

$previewTitle = htmlspecialchars($page['meta_title'] ?? $page['title'] ?? 'Título da Página');
$previewDesc = htmlspecialchars($page['meta_description'] ?? '');
$siteUrl = \App\Core\Config::get('site_url', 'https://example.com');
$previewUrl = rtrim($siteUrl, '/') . '/' . ($page['slug'] ?? '');
?>

<div class="seo-preview-card">
    <div class="seo-preview-label">Pré-visualização no Google</div>
    <div class="seo-preview-title"><?= $previewTitle ?></div>
    <div class="seo-preview-url"><?= htmlspecialchars($previewUrl) ?></div>
    <div class="seo-preview-desc"><?= $previewDesc ?></div>
</div>
