<?php
/** @var array<int, array<string, mixed>> $pages */
/** @var string|null $flash */
?>

<?php if ($flash): ?>
    <div class="alert alert-success"><?= htmlspecialchars($flash) ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <div>
            <h2>Todas as Páginas</h2>
            <p><?= count($pages) ?> página(s) cadastrada(s)</p>
        </div>
        <a href="/admin/pages/edit" class="btn btn-primary">+ Nova Página</a>
    </div>

    <?php if (empty($pages)): ?>
        <div class="empty-state" style="border: none; border-radius: 0;">
            <p>Nenhuma página cadastrada ainda.</p>
            <a href="/admin/pages/edit" class="btn btn-primary" style="margin-top: 0.75rem;">Criar primeira página</a>
        </div>
    <?php else: ?>
        <div class="table-wrap" style="border: none; border-radius: 0; box-shadow: none;">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 50%;">Página</th>
                        <th>Slug / URL</th>
                        <th>Status</th>
                        <th>Atualizada</th>
                        <th style="width: 180px; text-align: right;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pages as $page): ?>
                    <tr>
                        <td>
                            <div style="font-weight: 500; color: var(--text-primary);"><?= htmlspecialchars($page['title']) ?></div>
                            <?php if (!empty($page['meta_description'])): ?>
                                <div style="max-width: 380px; font-size: 0.8125rem; color: var(--text-secondary); line-height: 1.5; margin-top: 0.125rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    <?= htmlspecialchars((string)$page['meta_description']) ?>
                                </div>
                            <?php else: ?>
                                <div style="font-size: 0.8125rem; color: var(--text-muted); margin-top: 0.125rem;">Sem descrição SEO</div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <code style="background: var(--bg-subtle); color: var(--text-secondary); padding: 0.125rem 0.5rem; border-radius: var(--radius); font-size: 0.8125rem; border: 1px solid var(--border-light);">
                                /<?= htmlspecialchars($page['slug']) ?>
                            </code>
                        </td>
                        <td>
                            <?php if ($page['is_active']): ?>
                                <span class="badge badge-green">Ativa</span>
                            <?php else: ?>
                                <span class="badge badge-gray">Inativa</span>
                            <?php endif; ?>
                        </td>
                        <td style="font-size: 0.8125rem; color: var(--text-secondary); white-space: nowrap;">
                            <?= htmlspecialchars($page['updated_at'] ?? '-') ?>
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <div style="display: inline-flex; gap: 0.25rem; justify-content: flex-end;">
                                <a href="/page/<?= htmlspecialchars($page['slug']) ?>" target="_blank" class="btn btn-sm btn-ghost" title="Ver no site">Ver</a>
                                <a href="/admin/pages/edit/<?= (int) $page['id'] ?>" class="btn btn-sm btn-ghost" title="Editar">Editar</a>
                                <a href="/admin/pages/delete/<?= (int) $page['id'] ?>" class="btn btn-sm btn-danger" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir esta página?')">Excluir</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
