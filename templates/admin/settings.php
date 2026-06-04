<?php
/** @var array<string, string> $settings */
/** @var string|null $flash */
?>

<div class="page-header">
    <h1>Configurações do Site</h1>
</div>

<?php if ($flash): ?>
    <div class="alert alert-success"><?= htmlspecialchars($flash) ?></div>
<?php endif; ?>

<form action="/admin/settings" method="POST" class="form">
    <div class="form-section">
        <h3>Informações Básicas</h3>
        <div class="form-group">
            <label for="site_title">Título do Site</label>
            <input type="text" id="site_title" name="site_title" value="<?= htmlspecialchars($settings['site_title'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="site_description">Descrição (SEO)</label>
            <textarea id="site_description" name="site_description" rows="3"><?= htmlspecialchars($settings['site_description'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label for="site_url">URL do Site</label>
            <input type="url" id="site_url" name="site_url" value="<?= htmlspecialchars($settings['site_url'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="site_logo">Logo (caminho)</label>
            <input type="text" id="site_logo" name="site_logo" value="<?= htmlspecialchars($settings['site_logo'] ?? '') ?>">
        </div>
    </div>
    
    <div class="form-section">
        <h3>Contato</h3>
        <div class="form-group">
            <label for="contact_email">Email de Contato</label>
            <input type="email" id="contact_email" name="contact_email" value="<?= htmlspecialchars($settings['contact_email'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="contact_phone">Telefone</label>
            <input type="text" id="contact_phone" name="contact_phone" value="<?= htmlspecialchars($settings['contact_phone'] ?? '') ?>">
        </div>
    </div>
    
    <div class="form-section">
        <h3>SEO & Analytics</h3>
        <div class="form-group">
            <label for="analytics_id">Google Analytics ID</label>
            <input type="text" id="analytics_id" name="analytics_id" value="<?= htmlspecialchars($settings['analytics_id'] ?? '') ?>" placeholder="G-XXXXXXXXXX">
        </div>
        <div class="form-group">
            <label for="og_image">Imagem OG (caminho)</label>
            <input type="text" id="og_image" name="og_image" value="<?= htmlspecialchars($settings['og_image'] ?? '') ?>">
        </div>
    </div>
    
    <div class="form-section">
        <h3>Email</h3>
        <div class="form-group">
            <label for="mail_from">Email Remetente</label>
            <input type="email" id="mail_from" name="mail_from" value="<?= htmlspecialchars($settings['mail_from'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="mail_from_name">Nome Remetente</label>
            <input type="text" id="mail_from_name" name="mail_from_name" value="<?= htmlspecialchars($settings['mail_from_name'] ?? '') ?>">
        </div>
    </div>
    
    <button type="submit" class="btn btn-primary">Salvar Configurações</button>
</form>
