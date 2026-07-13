<?php
/**
 * @var App\Models\Product $product
 * @var bool|null $compact
 */

$compact = !empty($compact);
$hasPromo = $product->hasPromoPrice();
$displayedPrice = $product->getDisplayedPrice();
$originalPrice = $product->getOriginalPrice();
$discountPercent = $product->getDiscountPercent();
$productUrl = $product->getPublicUrl();
$ctaLabel = $product->getCardCtaLabel();
$imagePath = $product->image_path ?? null;
?>

<article class="group relative bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200/60 dark:border-zinc-800/60 overflow-hidden shadow-sm transition-all duration-300 <?= $compact ? 'hover:-translate-y-0.5 hover:shadow-lg' : 'hover:-translate-y-1 hover:shadow-xl' ?>">
    <div class="relative aspect-square bg-zinc-100 dark:bg-zinc-800 overflow-hidden">
        <?php if (!empty($imagePath)): ?>
            <img
                src="<?= htmlspecialchars($imagePath) ?>"
                alt="<?= htmlspecialchars($product->name) ?>"
                loading="lazy"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
            >
        <?php else: ?>
            <div class="w-full h-full flex items-center justify-center text-zinc-300 dark:text-zinc-700">
                <svg width="<?= $compact ? 36 : 48 ?>" height="<?= $compact ? 36 : 48 ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                </svg>
            </div>
        <?php endif; ?>

        <?php if ($hasPromo): ?>
            <span class="absolute top-3 left-3 bg-violet-600 text-white text-[11px] font-bold px-2.5 py-1 rounded-full shadow">
                <?= $discountPercent !== null ? '-' . $discountPercent . '%' : 'Em Promoção' ?>
            </span>
        <?php endif; ?>

        <?php if (!empty($product->category)): ?>
            <span class="absolute top-3 right-3 bg-white/90 dark:bg-zinc-900/90 backdrop-blur-sm text-zinc-600 dark:text-zinc-300 text-xs font-medium px-2.5 py-1 rounded-full border border-zinc-200/60 dark:border-zinc-700/60">
                <?= htmlspecialchars($product->category) ?>
            </span>
        <?php endif; ?>
    </div>

    <div class="<?= $compact ? 'p-4' : 'p-5' ?>">
        <h3 class="font-semibold text-zinc-900 dark:text-white leading-snug line-clamp-2 <?= $compact ? 'text-sm mb-1.5' : 'text-sm mb-1' ?>">
            <?= htmlspecialchars($product->name) ?>
        </h3>

        <?php if (!$compact && !empty($product->short_description)): ?>
            <p class="text-zinc-500 dark:text-zinc-400 text-xs leading-relaxed mb-3 line-clamp-2">
                <?= htmlspecialchars($product->short_description) ?>
            </p>
        <?php endif; ?>

        <div class="flex items-baseline gap-2 mb-4">
            <?php if ($hasPromo): ?>
                <span class="text-xs text-zinc-400 line-through">R$ <?= number_format($originalPrice, 2, ',', '.') ?></span>
            <?php endif; ?>
            <span class="<?= $compact ? 'text-base' : 'text-lg' ?> font-bold <?= $hasPromo ? 'text-violet-600 dark:text-violet-400' : 'text-zinc-900 dark:text-white' ?>">
                R$ <?= number_format($displayedPrice, 2, ',', '.') ?>
            </span>
        </div>

        <a
            href="<?= htmlspecialchars($productUrl) ?>"
            <?= $product->hasExternalLink() ? 'target="_blank" rel="noopener noreferrer"' : '' ?>
            class="flex items-center justify-center gap-2 w-full px-4 py-2.5 rounded-xl bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 text-sm font-semibold hover:bg-violet-600 dark:hover:bg-violet-500 dark:hover:text-white transition-colors duration-200"
        >
            <?= htmlspecialchars($ctaLabel) ?>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
    </div>
</article>
