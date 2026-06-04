<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Admin</title>
    <link rel="stylesheet" href="/assets/admin.css">
</head>
<body class="login-page">
    <div class="login-box">
        <div class="login-logo">S</div>
        <h1>SEO Template</h1>
        <p class="subtitle">Painel de administração</p>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form action="/admin/login" method="POST">
            <div class="form-group">
                <label for="username">Usuário</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Entrar</button>
        </form>
        <p class="hint">Usuário padrão: admin / admin123</p>
    </div>
</body>
</html>
