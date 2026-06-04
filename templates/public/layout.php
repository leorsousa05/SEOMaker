<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    use App\Core\Config;
    use App\Models\Page;
    use App\Seo\SeoManager;
    
    /** @var Page|null $page */
    if (isset($page) && $page instanceof Page) {
        echo SeoManager::metaTags($page);
        echo "\n";
        echo SeoManager::schemaJsonLd($page);
        echo "\n";
        echo SeoManager::organizationSchema();
    }
    ?>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="/" class="brand"><?= htmlspecialchars(Config::get('site_title', 'SEO Template')) ?></a>
            <div class="nav-links">
                <a href="/">Início</a>
                <a href="/page/sobre">Sobre</a>
                <a href="/admin">Admin</a>
            </div>
        </div>
    </nav>
    
    <main>
        <?= $content ?>
    </main>
    
    <footer class="footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> <?= htmlspecialchars(Config::get('site_title', '')) ?></p>
        </div>
    </footer>
</body>
</html>
