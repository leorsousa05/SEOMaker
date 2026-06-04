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
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Slug</th>
                    <th>Título</th>
                    <th>Status</th>
                    <th>Atualizada</th>
                    <th style="width: 140px;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pages as $page): ?>
                <tr>
                    <td><?= (int) $page['id'] ?></td>
                    <td><code style="background: rgba(255,255,255,0.05); padding: 0.125rem 0.375rem; border-radius: var(--radius); font-size: 0.8125rem;"><?= htmlspecialchars($page['slug']) ?></code></td>
                    <td><?= htmlspecialchars($page['title']) ?></td>
                    <td>
                        <?php if ($page['is_active']): ?>
                            <span class="badge badge-green">Ativa</span>
                        <?php else: ?>
                            <span class="badge badge-gray">Inativa</span>
                        <?php endif; ?>
                    </td>
                    <td style="color: var(--text-secondary); font-size: 0.8125rem;"><?= htmlspecialchars($page['updated_at'] ?? '-') ?></td>
                    <td>
                        <a href="/admin/pages/edit/<?= (int) $page['id'] ?>" class="btn btn-sm btn-ghost">Editar</a>
                        <a href="/admin/pages/delete/<?= (int) $page['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta página?')">Excluir</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
