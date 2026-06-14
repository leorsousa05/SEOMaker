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
        echo SeoManager::breadcrumbSchema($page);
        echo "\n";
        echo SeoManager::organizationSchema();
    }
    ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Syne:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/style.css">
    <link rel="sitemap" type="application/xml" href="/sitemap.xml">
</head>
<body>
    <nav class="navbar <?= (!isset($isHome) || !$isHome) ? 'has-no-hero' : '' ?>">
        <div class="container">
            <a href="/" class="brand">
                <span class="brand-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                        <path d="M2 17l10 5 10-5"/>
                        <path d="M2 12l10 5 10-5"/>
                    </svg>
                </span>
                <?= htmlspecialchars(Config::get('site_title', 'SEO Core')) ?>
            </a>
            <button type="button" class="navbar-toggle" aria-label="Abrir menu" aria-expanded="false">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="3" y1="12" x2="21" y2="12"/>
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>
            <div class="nav-links">
                <a href="/">Início</a>
                <a href="/page/sobre">Sobre</a>
                <a href="/#contato">Contato</a>
                <a href="/admin" class="btn btn-primary">Admin</a>
            </div>
        </div>
    </nav>

    <main>
        <?= $content ?>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="/" class="brand">
                        <span class="brand-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                                <path d="M2 17l10 5 10-5"/>
                                <path d="M2 12l10 5 10-5"/>
                            </svg>
                        </span>
                        <?= htmlspecialchars(Config::get('site_title', 'SEO Core')) ?>
                    </a>
                    <p>Template SEO completo para empresas que querem crescer no Google com um site profissional e fácil de gerenciar.</p>
                </div>
                <div class="footer-column">
                    <h4>Produto</h4>
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li><a href="/page/sobre">Sobre</a></li>
                        <li><a href="/#contato">Contato</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>Recursos</h4>
                    <ul>
                        <li><a href="/sitemap.xml">Sitemap</a></li>
                        <li><a href="/admin">Painel Admin</a></li>
                        <li><a href="/robots.txt">Robots.txt</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>Contato</h4>
                    <ul>
                        <li><a href="/#contato">Fale conosco</a></li>
                        <li><a href="mailto:<?= htmlspecialchars(Config::get('contact_email', 'contato@example.com')) ?>"><?= htmlspecialchars(Config::get('contact_email', 'contato@example.com')) ?></a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> <?= htmlspecialchars(Config::get('site_title', 'SEO Core')) ?>. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script src="/assets/animations.js" defer></script>
</body>
</html>
