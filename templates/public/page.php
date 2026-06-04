<?php /** @var App\Models\Page $page */ ?>

<section class="page-content">
    <div class="container">
        <h1><?= htmlspecialchars($page->title) ?></h1>
        <div class="content">
            <?= $page->content_html ?>
        </div>
    </div>
</section>
