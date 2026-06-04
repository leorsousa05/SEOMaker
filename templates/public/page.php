<?php
/** @var App\Models\Page $page */
?>

<section class="page-content">
    <div class="container">
        <h1><?= htmlspecialchars($page->title) ?></h1>
        
        <?php if (!empty($page->content_blocks)): ?>
            <?php
            $blocks = json_decode($page->content_blocks, true);
            if (is_array($blocks)) {
                echo \App\Content\BlockEditor::render($blocks);
            }
            ?>
        <?php else: ?>
            <div class="content">
                <?= $page->content_html ?>
            </div>
        <?php endif; ?>
    </div>
</section>
