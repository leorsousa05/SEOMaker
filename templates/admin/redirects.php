<?php
/** @var array<int, array<string, mixed>> $redirects */
/** @var string|null $flash */
?>

<?php if ($flash): ?>
    <div class="alert alert-success"><?= htmlspecialchars($flash) ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <div>
            <h2>Todos os Redirects</h2>
            <p><?= count($redirects) ?> redirect(s) cadastrado(s)</p>
        </div>
        <a href="/admin/redirects/edit" class="btn btn-primary">+ Novo Redirect</a>
    </div>

    <?php if (empty($redirects)): ?>
        <div class="empty-state" style="border: none; border-radius: 0;">
            <p>Nenhum redirect cadastrado.</p>
            <a href="/admin/redirects/edit" class="btn btn-primary" style="margin-top: 0.75rem;">Criar primeiro redirect</a>
        </div>
    <?php else: ?>
        <div class="table-wrap" style="border: none; border-radius: 0; box-shadow: none;">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 35%;">Origem</th>
                        <th style="width: 35%;">Destino</th>
                        <th>Tipo</th>
                        <th>Status</th>
                        <th style="width: 120px; text-align: right;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($redirects as $r): ?>
                    <tr>
                        <td>
                            <code style="background: var(--bg-subtle); color: var(--text-secondary); padding: 0.125rem 0.5rem; border-radius: var(--radius); font-size: 0.8125rem; border: 1px solid var(--border-light);">
                                <?= htmlspecialchars($r['from_path']) ?>
                            </code>
                        </td>
                        <td>
                            <code style="background: var(--bg-subtle); color: var(--text-secondary); padding: 0.125rem 0.5rem; border-radius: var(--radius); font-size: 0.8125rem; border: 1px solid var(--border-light);">
                                <?= htmlspecialchars($r['to_path']) ?>
                            </code>
                        </td>
                        <td>
                            <span class="badge <?= $r['type'] === '301' ? 'badge-blue' : 'badge-amber' ?>">
                                <?= htmlspecialchars($r['type']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($r['is_active']): ?>
                                <span class="badge badge-green">Ativo</span>
                            <?php else: ?>
                                <span class="badge badge-gray">Inativo</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <div style="display: inline-flex; gap: 0.25rem; justify-content: flex-end;">
                                <a href="/admin/redirects/edit/<?= (int) $r['id'] ?>" class="btn btn-sm btn-ghost">Editar</a>
                                <a href="/admin/redirects/delete/<?= (int) $r['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Remover este redirect?')">Excluir</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
