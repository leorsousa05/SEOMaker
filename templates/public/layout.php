<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
    <link rel="stylesheet" href="/assets/style.css">
    <link rel="sitemap" type="application/xml" href="/sitemap.xml">
</head>
<body class="bg-white dark:bg-zinc-950 text-zinc-900 dark:text-zinc-50 font-body transition-colors antialiased">
    <nav class="sticky top-0 z-50 bg-white/85 dark:bg-zinc-950/85 backdrop-blur-md border-b border-zinc-200/50 dark:border-zinc-800/50 py-4 transition-colors">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
            <a href="/" class="text-2xl font-black font-title tracking-tight bg-gradient-to-r from-violet-600 to-blue-500 bg-clip-text text-transparent">
                <?= htmlspecialchars(Config::get('site_title', 'SEOMaker')) ?>
            </a>
            <div class="flex items-center gap-6">
                <?php
                $menuPages = \App\Core\Database::fetchAll("SELECT slug, title FROM pages WHERE is_active = 1 AND in_menu = 1 ORDER BY id");
                foreach ($menuPages as $mp) {
                    $url = $mp['slug'] === '' ? '/' : '/' . $mp['slug'];
                    echo '<a href="' . htmlspecialchars($url) . '" class="text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors">' . htmlspecialchars($mp['title']) . '</a>';
                }
                ?>
            </div>
        </div>
    </nav>
    
    <main class="min-h-[60vh]">
        <?= $content ?>
    </main>
    
    <footer class="bg-zinc-50 dark:bg-zinc-950 border-t border-zinc-200/50 dark:border-zinc-800/50 py-16 text-sm text-zinc-500 dark:text-zinc-400">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
                <div class="flex flex-col gap-3">
                    <h3 class="text-lg font-bold font-title text-zinc-900 dark:text-white">
                        <?= htmlspecialchars(Config::get('site_title', 'SEOMaker')) ?>
                    </h3>
                    <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed max-w-sm">
                        <?= htmlspecialchars(Config::get('site_description', 'Template completo para SEO com painel administrativo.')) ?></p>
                </div>
                <div class="flex flex-col gap-3">
                    <h4 class="text-xs font-bold text-zinc-800 dark:text-zinc-200 uppercase tracking-wider">Navegação</h4>
                    <ul class="flex flex-col gap-2">
                        <li><a href="/" class="hover:text-zinc-900 dark:hover:text-white transition-colors">Início</a></li>
                        <?php foreach ($menuPages as $mp): ?>
                            <?php $url = $mp['slug'] === '' ? '/' : '/' . $mp['slug']; ?>
                            <li><a href="<?= htmlspecialchars($url) ?>" class="hover:text-zinc-900 dark:hover:text-white transition-colors"><?= htmlspecialchars($mp['title']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="flex flex-col gap-3">
                    <h4 class="text-xs font-bold text-zinc-800 dark:text-zinc-200 uppercase tracking-wider">Contato</h4>
                    <ul class="flex flex-col gap-2">
                        <?php if ($email = Config::get('contact_email')): ?>
                            <li>E-mail: <a href="mailto:<?= htmlspecialchars($email) ?>" class="hover:text-zinc-900 dark:hover:text-white transition-colors font-medium"><?= htmlspecialchars($email) ?></a></li>
                        <?php endif; ?>
                        <?php if ($phone = Config::get('contact_phone')): ?>
                            <li>Tel: <a href="tel:<?= htmlspecialchars($phone) ?>" class="hover:text-zinc-900 dark:hover:text-white transition-colors font-medium"><?= htmlspecialchars($phone) ?></a></li>
                        <?php endif; ?>
                        <?php if ($address = Config::get('contact_address')): ?>
                            <li class="leading-relaxed"><?= htmlspecialchars($address) ?></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <div class="border-t border-zinc-200/50 dark:border-zinc-800/50 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs">
                <p>&copy; <?= date('Y') ?> <?= htmlspecialchars(Config::get('site_title', '')) ?>. Todos os direitos reservados.</p>
                <p class="text-zinc-400 dark:text-zinc-500">Otimizado para motores de busca.</p>
            </div>
        </div>
    </footer>
</body>
</html>
