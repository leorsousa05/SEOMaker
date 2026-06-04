<?php
/** @var array<string, string> $settings */
/** @var string|null $flash */
/** @var array<string, array{label: string, keys: array<int, string>}> $groups */
/** @var string $activeTab */
/** @var array<string, string> $labels */
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
                        <?php else: ?>
                            <input type="text" id="<?= $fieldKey ?>" name="<?= $fieldKey ?>" value="<?= htmlspecialchars($settings[$fieldKey] ?? '') ?>">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
        
        <button type="submit" class="btn btn-primary">Salvar Configurações</button>
    </form>
</div>
