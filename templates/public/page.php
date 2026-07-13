<?php
/** @var App\Models\Page $page */
?>

<article class="max-w-7xl mx-auto px-6 py-16 md:py-24">
    <header class="text-center mb-16">
        <h1 class="text-4xl md:text-5xl font-black font-title tracking-tight text-zinc-900 dark:text-white leading-none mb-4">
            <?= htmlspecialchars($page->title) ?>
        </h1>
        <div class="h-1.5 w-16 bg-violet-600 rounded-full mx-auto"></div>
    </header>
    
    <div class="max-w-4xl mx-auto">
        <?php if (!empty($page->content_blocks)): ?>
            <?php
            $blocks = json_decode($page->content_blocks, true);
            if (is_array($blocks)) {
                echo \App\Content\BlockEditor::render($blocks);
            }
            ?>
        <?php else: ?>
            <div class="prose dark:prose-invert max-w-none text-zinc-700 dark:text-zinc-300 leading-relaxed">
                <?= $page->content_html ?>
            </div>
        <?php endif; ?>
    </div>
</article>
