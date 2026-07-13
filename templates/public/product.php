<?php
/**
 * @var App\Models\Product $product
 * @var string|null $imagePath
 * @var array<int, array<string, mixed>> $galleryImages
 * @var array<int, array<string, mixed>> $related
 */

$hasPromo   = $product->promo_price !== null && $product->promo_price > 0;
$finalPrice = $hasPromo ? $product->promo_price : $product->price;
$tags       = $product->tags ? array_map('trim', explode(',', $product->tags)) : [];
$allImages  = [];
if ($imagePath) {
    $allImages[] = $imagePath;
}
foreach ($galleryImages as $gi) {
    if ($gi['path'] !== $imagePath) {
        $allImages[] = $gi['path'];
    }
}
?>

<article>
    <!-- ── Breadcrumb ── -->
    <nav class="max-w-7xl mx-auto px-6 pt-8 pb-0">
        <ol class="flex items-center gap-2 text-sm text-zinc-500 dark:text-zinc-400">
            <li><a href="/" class="hover:text-violet-600 transition-colors">Início</a></li>
            <li><span class="text-zinc-300 dark:text-zinc-700">/</span></li>
            <li><a href="/#produtos" class="hover:text-violet-600 transition-colors">Produtos</a></li>
            <li><span class="text-zinc-300 dark:text-zinc-700">/</span></li>
            <li class="text-zinc-900 dark:text-white font-medium truncate max-w-[200px]"><?= htmlspecialchars($product->name) ?></li>
        </ol>
    </nav>

    <!-- ── Main product grid ── -->
    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-start">

            <!-- LEFT: Image gallery -->
            <div class="space-y-4">
                <!-- Main image -->
                <div class="relative aspect-square bg-zinc-100 dark:bg-zinc-800/60 rounded-3xl overflow-hidden border border-zinc-200/60 dark:border-zinc-700/60" id="main-image-wrap">
                    <?php if (!empty($allImages)): ?>
                        <img
                            id="main-product-image"
                            src="<?= htmlspecialchars($allImages[0]) ?>"
                            alt="<?= htmlspecialchars($product->name) ?>"
                            class="w-full h-full object-cover transition-opacity duration-300"
                        >
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-zinc-300 dark:text-zinc-700">
                            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>
                            </svg>
                        </div>
                    <?php endif; ?>
                    <?php if ($hasPromo): ?>
                        <span class="absolute top-4 left-4 bg-violet-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow">Em Promoção</span>
                    <?php endif; ?>
                </div>

                <!-- Thumbnails -->
                <?php if (count($allImages) > 1): ?>
                <div class="flex gap-3 flex-wrap">
                    <?php foreach ($allImages as $i => $img): ?>
                    <button
                        type="button"
                        onclick="switchImage('<?= htmlspecialchars($img) ?>', this)"
                        class="thumb-btn w-20 h-20 rounded-xl overflow-hidden border-2 transition-all duration-200 <?= $i === 0 ? 'border-violet-500 ring-2 ring-violet-500/30' : 'border-zinc-200 dark:border-zinc-700 hover:border-violet-400' ?>"
                    >
                        <img src="<?= htmlspecialchars($img) ?>" alt="" class="w-full h-full object-cover">
                    </button>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- RIGHT: Product info -->
            <div class="py-2">
                <?php if (!empty($product->category)): ?>
                    <span class="inline-block text-xs font-semibold text-violet-600 dark:text-violet-400 uppercase tracking-widest mb-3"><?= htmlspecialchars($product->category) ?></span>
                <?php endif; ?>

                <h1 class="text-3xl md:text-4xl font-bold text-zinc-900 dark:text-white tracking-tight leading-tight mb-4">
                    <?= htmlspecialchars($product->name) ?>
                </h1>

                <?php if (!empty($product->short_description)): ?>
                    <p class="text-zinc-500 dark:text-zinc-400 text-base leading-relaxed mb-6">
                        <?= htmlspecialchars($product->short_description) ?>
                    </p>
                <?php endif; ?>

                <!-- Price -->
                <div class="flex items-baseline gap-3 mb-6">
                    <?php if ($hasPromo): ?>
                        <span class="text-xl text-zinc-400 line-through">R$ <?= number_format((float)$product->price, 2, ',', '.') ?></span>
                    <?php endif; ?>
                    <span class="text-4xl font-black <?= $hasPromo ? 'text-violet-600 dark:text-violet-400' : 'text-zinc-900 dark:text-white' ?>">
                        R$ <?= number_format((float)$finalPrice, 2, ',', '.') ?>
                    </span>
                    <?php if ($hasPromo): ?>
                        <span class="bg-violet-100 dark:bg-violet-900/40 text-violet-700 dark:text-violet-300 text-xs font-bold px-2 py-1 rounded-full">
                            <?php
                                $pct = round((1 - (float)$product->promo_price / (float)$product->price) * 100);
                                echo "-{$pct}%";
                            ?>
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Stock / SKU info -->
                <div class="flex flex-wrap gap-4 mb-8 text-sm text-zinc-500 dark:text-zinc-400">
                    <?php if (!empty($product->sku)): ?>
                        <span>SKU: <strong class="text-zinc-700 dark:text-zinc-300"><?= htmlspecialchars($product->sku) ?></strong></span>
                    <?php endif; ?>
                    <?php if ($product->stock > 0): ?>
                        <span class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>
                            Em estoque (<?= $product->stock ?> un.)
                        </span>
                    <?php elseif ($product->stock === 0 && empty($product->external_link)): ?>
                        <span class="flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-red-500 inline-block"></span>
                            Fora de estoque
                        </span>
                    <?php endif; ?>
                </div>

                <!-- CTA -->
                <div class="flex flex-col sm:flex-row gap-3 mb-8">
                    <?php if (!empty($product->external_link)): ?>
                        <a
                            href="<?= htmlspecialchars($product->external_link) ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="flex-1 flex items-center justify-center gap-2 px-6 py-4 rounded-2xl bg-violet-600 hover:bg-violet-700 text-white font-bold text-base transition-all duration-200 shadow-lg shadow-violet-500/30 hover:shadow-violet-500/50 hover:-translate-y-0.5"
                        >
                            Comprar agora
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>
                    <?php else: ?>
                        <a
                            href="/#contato"
                            class="flex-1 flex items-center justify-center gap-2 px-6 py-4 rounded-2xl bg-violet-600 hover:bg-violet-700 text-white font-bold text-base transition-all duration-200 shadow-lg shadow-violet-500/30 hover:shadow-violet-500/50 hover:-translate-y-0.5"
                        >
                            Tenho interesse
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>
                    <?php endif; ?>
                    <a
                        href="/#contato"
                        class="flex items-center justify-center gap-2 px-6 py-4 rounded-2xl border border-zinc-200 dark:border-zinc-700 text-zinc-700 dark:text-zinc-300 hover:border-violet-400 hover:text-violet-600 dark:hover:text-violet-400 font-semibold text-base transition-all duration-200"
                    >
                        Falar com vendedor
                    </a>
                </div>

                <!-- Tags -->
                <?php if (!empty($tags)): ?>
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($tags as $tag): ?>
                        <span class="text-xs bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 px-3 py-1 rounded-full border border-zinc-200/60 dark:border-zinc-700/60">
                            #<?= htmlspecialchars($tag) ?>
                        </span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ── Full description ── -->
        <?php if (!empty($product->description)): ?>
        <div class="mt-20 border-t border-zinc-200/60 dark:border-zinc-800/60 pt-16">
            <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-6">Descrição do produto</h2>
            <div class="prose prose-zinc dark:prose-invert max-w-none text-zinc-600 dark:text-zinc-400 leading-relaxed text-base">
                <?= nl2br(htmlspecialchars($product->description)) ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- ── Related products ── -->
        <?php if (!empty($related)): ?>
        <div class="mt-20 border-t border-zinc-200/60 dark:border-zinc-800/60 pt-16">
            <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-8">Produtos relacionados</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($related as $rel): ?>
                <?php
                    $relHasPromo  = !empty($rel['promo_price']) && (float)$rel['promo_price'] > 0;
                    $relPrice     = $relHasPromo ? (float)$rel['promo_price'] : (float)$rel['price'];
                    $relUrl       = !empty($rel['external_link']) ? htmlspecialchars($rel['external_link']) : '/produtos/' . htmlspecialchars($rel['slug']);
                    $relExternal  = !empty($rel['external_link']);
                ?>
                <a href="<?= $relUrl ?>" <?= $relExternal ? 'target="_blank" rel="noopener noreferrer"' : '' ?> class="group bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200/60 dark:border-zinc-800/60 overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300 block">
                    <div class="aspect-square bg-zinc-100 dark:bg-zinc-800 overflow-hidden">
                        <?php if (!empty($rel['image_path'])): ?>
                            <img src="<?= htmlspecialchars($rel['image_path']) ?>" alt="<?= htmlspecialchars($rel['name']) ?>" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-zinc-300 dark:text-zinc-700">
                                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-zinc-900 dark:text-white text-sm leading-snug mb-2 line-clamp-2"><?= htmlspecialchars($rel['name']) ?></h3>
                        <div class="flex items-baseline gap-2">
                            <?php if ($relHasPromo): ?>
                                <span class="text-xs text-zinc-400 line-through">R$ <?= number_format((float)$rel['price'], 2, ',', '.') ?></span>
                            <?php endif; ?>
                            <span class="font-bold <?= $relHasPromo ? 'text-violet-600 dark:text-violet-400' : 'text-zinc-900 dark:text-white' ?>">R$ <?= number_format($relPrice, 2, ',', '.') ?></span>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</article>

<script>
function switchImage(src, btn) {
    var mainImg = document.getElementById('main-product-image');
    if (mainImg) {
        mainImg.style.opacity = '0';
        setTimeout(function() {
            mainImg.src = src;
            mainImg.style.opacity = '1';
        }, 150);
    }
    document.querySelectorAll('.thumb-btn').forEach(function(b) {
        b.classList.remove('border-violet-500', 'ring-2', 'ring-violet-500/30');
        b.classList.add('border-zinc-200', 'dark:border-zinc-700');
    });
    btn.classList.add('border-violet-500', 'ring-2', 'ring-violet-500/30');
    btn.classList.remove('border-zinc-200', 'dark:border-zinc-700');
}
</script>
