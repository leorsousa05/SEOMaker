<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - <?= htmlspecialchars(App\Core\Config::get('site_title', 'SEO Template')) ?></title>
    <link rel="stylesheet" href="/assets/admin.css">
</head>
<body class="admin">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <a href="/admin">Admin</a>
        </div>
        <nav class="sidebar-nav">
            <a href="/admin">Dashboard</a>
            <a href="/admin/pages">Páginas</a>
            <a href="/admin/settings">Configurações</a>
            <a href="/admin/logout" class="logout">Sair</a>
        </nav>
    </aside>
    <main class="admin-main">
        <?= $content ?>
    </main>
</body>
</html>
