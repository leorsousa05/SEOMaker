<?php
/** @var App\Models\Page $page */
?>

<section class="section page-content">
    <div class="container">
        <header class="page-header" data-reveal>
            <h1 class="title-1"><?= htmlspecialchars($page->title) ?></h1>
            <?php if (!empty($page->meta_description)): ?>
                <p class="lead"><?= htmlspecialchars($page->meta_description) ?></p>
            <?php endif; ?>
        </header>

        <?php if (!empty($page->content_blocks)): ?>
            <?php
            $blocks = json_decode($page->content_blocks, true);
            if (is_array($blocks)) {
                echo \App\Content\BlockEditor::render($blocks);
            }
            ?>
        <?php else: ?>
            <div class="content" data-reveal>
                <?= $page->content_html ?>
            </div>
        <?php endif; ?>
    </div>
</section>
