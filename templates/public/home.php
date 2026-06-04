<?php
/** @var App\Models\Page $page */
/** @var array<int, array<string, string>> $features */
?>

<section class="hero">
    <div class="container">
        <h1><?= htmlspecialchars($page->title ?: 'SEO Template PHP') ?></h1>
        <p class="lead"><?= htmlspecialchars(App\Core\Config::get('site_description', 'Template completo para SEO com painel administrativo.')) ?></p>
        <a href="#contato" class="btn btn-primary">Fale Conosco</a>
    </div>
</section>

<section class="features">
    <div class="container">
        <div class="grid">
            <?php foreach ($features as $feature): ?>
            <div class="card">
                <div class="card-icon"><?= $feature['icon'] ?></div>
                <h3><?= htmlspecialchars($feature['title']) ?></h3>
                <p><?= htmlspecialchars($feature['desc']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="contato" class="contact">
    <div class="container">
        <h2>Entre em Contato</h2>
        
        <?php if (!empty($_SESSION['contact_success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['contact_success']) ?></div>
            <?php unset($_SESSION['contact_success']); ?>
        <?php endif; ?>
        
        <?php if (!empty($_SESSION['contact_errors'])): ?>
            <div class="alert alert-danger">
                <?php foreach ($_SESSION['contact_errors'] as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
            <?php unset($_SESSION['contact_errors']); ?>
        <?php endif; ?>
        
        <form action="/contact" method="POST" class="form">
            <div class="form-group">
                <label for="name">Nome</label>
                <input type="text" id="name" name="name" required value="<?= htmlspecialchars($_SESSION['contact_data']['name'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_SESSION['contact_data']['email'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="message">Mensagem</label>
                <textarea id="message" name="message" rows="5" required><?= htmlspecialchars($_SESSION['contact_data']['message'] ?? '') ?></textarea>
            </div>
            <?php unset($_SESSION['contact_data']); ?>
            <button type="submit" class="btn btn-primary">Enviar Mensagem</button>
        </form>
    </div>
</section>
