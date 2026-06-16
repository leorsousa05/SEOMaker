<?php
/** @var App\Models\Page|null $page */

if (!isset($page) || !$page instanceof App\Models\Page || $page->slug === '') {
    return;
}

$items = App\Seo\SeoManager::breadcrumbItems($page);
if (count($items) <= 1) {
    return;
}

// Visual breadcrumb uses "Início" for home; schema still uses site_title
$items[0]['name'] = 'Início';
?>

<nav class="breadcrumb" aria-label="Navegação">
    <ol class="breadcrumb__list">
        <?php foreach ($items as $index => $item): ?>
            <?php $isLast = $index === count($items) - 1; ?>
            <li class="breadcrumb__item<?= $isLast ? ' breadcrumb__item--current' : '' ?>" <?= $isLast ? 'aria-current="page"' : '' ?>>
                <?php if ($isLast): ?>
                    <?= htmlspecialchars($item['name']) ?>
                <?php else: ?>
                    <a href="<?= htmlspecialchars($item['url']) ?>"><?= htmlspecialchars($item['name']) ?></a>
                <?php endif; ?>
            </li>
            <?php if (!$isLast): ?>
                <li class="breadcrumb__separator" aria-hidden="true">/</li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ol>
</nav>
