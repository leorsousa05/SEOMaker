<?php
/** @var array<string, mixed>|null $redirect */
/** @var array<string, string> $errors */

$isEdit = $redirect !== null && isset($redirect['id']);
?>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger">
    <strong>Corrija os erros abaixo:</strong>
    <ul style="margin-top: 0.5rem; margin-bottom: 0; padding-left: 1.25rem;">
        <?php foreach ($errors as $field => $message): ?>
            <li><?= htmlspecialchars($message) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form action="/admin/redirects/save" method="POST" class="form">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= (int) $redirect['id'] ?>">
            <?php endif; ?>
            
            <div class="form-row form-row--2">
                <div class="form-group <?= isset($errors['from_path']) ? 'has-error' : '' ?>">
                    <label for="from_path">Caminho de Origem</label>
                    <input type="text" id="from_path" name="from_path" value="<?= htmlspecialchars($redirect['from_path'] ?? '') ?>" placeholder="/pagina-antiga" required>
                    <?php if (isset($errors['from_path'])): ?>
                        <span class="error-text"><?= htmlspecialchars($errors['from_path']) ?></span>
                    <?php endif; ?>
                    <span class="help-text">URL relativa que será redirecionada. Ex: /antiga</span>
                </div>
                
                <div class="form-group <?= isset($errors['to_path']) ? 'has-error' : '' ?>">
                    <label for="to_path">Caminho de Destino</label>
                    <input type="text" id="to_path" name="to_path" value="<?= htmlspecialchars($redirect['to_path'] ?? '') ?>" placeholder="/pagina-nova" required>
                    <?php if (isset($errors['to_path'])): ?>
                        <span class="error-text"><?= htmlspecialchars($errors['to_path']) ?></span>
                    <?php endif; ?>
                    <span class="help-text">URL relativa de destino. Ex: /nova ou https://externo.com</span>
                </div>
            </div>
            
            <div class="form-row form-row--2">
                <div class="form-group">
                    <label for="type">Tipo de Redirect</label>
                    <select id="type" name="type">
                        <option value="301" <?= ($redirect['type'] ?? '301') === '301' ? 'selected' : '' ?>>301 — Permanente (SEO)</option>
                        <option value="302" <?= ($redirect['type'] ?? '') === '302' ? 'selected' : '' ?>>302 — Temporário</option>
                    </select>
                </div>
                
                <div class="form-group form-check" style="align-self: flex-end; padding-bottom: 0.5rem;">
                    <input type="checkbox" id="is_active" name="is_active" value="1" <?= ($redirect['is_active'] ?? 1) ? 'checked' : '' ?>>
                    <label for="is_active">Redirect ativo</label>
                </div>
            </div>
            
            <div style="margin-top: 1rem;">
                <button type="submit" class="btn btn-primary">Salvar Redirect</button>
                <a href="/admin/redirects" class="btn btn-ghost">Cancelar</a>
            </div>
        </form>
    </div>
</div>
