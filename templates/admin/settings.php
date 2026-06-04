<?php
/** @var array<string, string> $settings */
/** @var string|null $flash */
/** @var array<string, array{label: string, keys: array<int, string>}> $groups */
/** @var string $activeTab */
/** @var array<string, string> $labels */

$helperTexts = [
    'site_title' => 'Nome que aparece na aba do navegador e nos resultados do Google.',
    'site_description' => 'Descrição curta do site (até 160 caracteres). Usada pelo Google.',
    'site_url' => 'Endereço completo do site, incluindo https://',
    'site_logo' => 'Caminho da imagem do logo. Ex: /assets/logo.png',
    'favicon' => 'Ícone que aparece na aba do navegador. Ex: /assets/favicon.ico',
    'meta_default_title' => 'Título padrão quando uma página não define o seu.',
    'meta_default_description' => 'Descrição padrão quando uma página não define a sua.',
    'og_image' => 'Imagem que aparece ao compartilhar o site no Facebook/WhatsApp. Recomendado: 1200x630px.',
    'robots_txt_custom' => 'Regras customizadas para robôs de busca. Apenas para usuários avançados.',
    'contact_email' => 'Email que recebe mensagens do formulário de contato.',
    'contact_phone' => 'Telefone de contato que pode aparecer no site.',
    'contact_address' => 'Endereço físico da empresa.',
    'contact_whatsapp' => 'Número do WhatsApp com código do país. Ex: 5511999999999',
    'mail_from' => 'Email que aparece como remetente das mensagens enviadas pelo site.',
    'mail_from_name' => 'Nome que aparece como remetente dos emails.',
    'smtp_host' => 'Servidor SMTP para envio de emails. Ex: smtp.gmail.com',
    'smtp_port' => 'Porta do servidor SMTP. Ex: 587',
    'smtp_user' => 'Usuário para autenticação no SMTP.',
    'smtp_pass' => 'Senha para autenticação no SMTP.',
    'facebook_url' => 'URL completa da página no Facebook.',
    'instagram_url' => 'URL completa do perfil no Instagram.',
    'twitter_url' => 'URL completa do perfil no Twitter/X.',
    'linkedin_url' => 'URL completa da página no LinkedIn.',
    'youtube_url' => 'URL completa do canal no YouTube.',
];
?>

<div class="page-header">
    <h1>Configurações do Site</h1>
</div>

<?php if ($flash): ?>
    <div class="alert alert-success"><?= htmlspecialchars($flash) ?></div>
<?php endif; ?>

<div class="tabs" data-tabs>
    <nav class="tabs-nav">
        <?php foreach ($groups as $key => $group): ?>
            <button type="button" class="tabs-nav-item <?= $key === $activeTab ? 'active' : '' ?>" data-tab="<?= $key ?>">
                <?= htmlspecialchars($group['label']) ?>
            </button>
        <?php endforeach; ?>
    </nav>
    
    <form action="/admin/settings" method="POST" class="form tabs-form">
        <input type="hidden" name="active_tab" value="<?= htmlspecialchars($activeTab) ?>">
        
        <?php foreach ($groups as $key => $group): ?>
            <div class="tabs-panel <?= $key === $activeTab ? 'active' : '' ?>" data-tab="<?= $key ?>">
                <?php foreach ($group['keys'] as $fieldKey): ?>
                    <div class="form-group">
                        <label for="<?= $fieldKey ?>"><?= htmlspecialchars($labels[$fieldKey] ?? $fieldKey) ?></label>
                        <?php if (str_contains($fieldKey, '_description') || str_contains($fieldKey, '_custom') || str_contains($fieldKey, '_address')): ?>
                            <textarea id="<?= $fieldKey ?>" name="<?= $fieldKey ?>" rows="3"><?= htmlspecialchars($settings[$fieldKey] ?? '') ?></textarea>
                        <?php elseif (str_contains($fieldKey, '_pass')): ?>
                            <input type="password" id="<?= $fieldKey ?>" name="<?= $fieldKey ?>" value="<?= htmlspecialchars($settings[$fieldKey] ?? '') ?>">
                        <?php elseif (str_contains($fieldKey, 'site_url')): ?>
                            <input type="url" id="<?= $fieldKey ?>" name="<?= $fieldKey ?>" value="<?= htmlspecialchars($settings[$fieldKey] ?? '') ?>">
                        <?php elseif (str_contains($fieldKey, 'email')): ?>
                            <input type="email" id="<?= $fieldKey ?>" name="<?= $fieldKey ?>" value="<?= htmlspecialchars($settings[$fieldKey] ?? '') ?>">
                        <?php elseif (str_contains($fieldKey, 'phone') || str_contains($fieldKey, 'port')): ?>
                            <input type="tel" id="<?= $fieldKey ?>" name="<?= $fieldKey ?>" value="<?= htmlspecialchars($settings[$fieldKey] ?? '') ?>">
                        <?php else: ?>
                            <input type="text" id="<?= $fieldKey ?>" name="<?= $fieldKey ?>" value="<?= htmlspecialchars($settings[$fieldKey] ?? '') ?>">
                        <?php endif; ?>
                        <?php if (isset($helperTexts[$fieldKey])): ?>
                            <span class="help-text"><?= htmlspecialchars($helperTexts[$fieldKey]) ?></span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
        
        <button type="submit" class="btn btn-primary">Salvar Configurações</button>
    </form>
</div>
