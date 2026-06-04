<?php
/** @var array<int, array<string, mixed>> $pages */
/** @var string|null $flash */
?>

<div class="page-header">
    <h1>Páginas</h1>
    <a href="/admin/pages/edit" class="btn btn-primary">Nova Página</a>
</div>

<?php if ($flash): ?>
    <div class="alert alert-success"><?= htmlspecialchars($flash) ?></div>
<?php endif; ?>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Slug</th>
            <th>Título</th>
            <th>Ativa</th>
            <th>Atualizada</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pages as $page): ?>
        <tr>
            <td><?= (int) $page['id'] ?></td>
            <td><?= htmlspecialchars($page['slug']) ?></td>
            <td><?= htmlspecialchars($page['title']) ?></td>
            <td><?= $page['is_active'] ? 'Sim' : 'Não' ?></td>
            <td><?= htmlspecialchars($page['updated_at'] ?? '-') ?></td>
            <td>
                <a href="/admin/pages/edit/<?= (int) $page['id'] ?>" class="btn btn-sm">Editar</a>
                <a href="/admin/pages/delete/<?= (int) $page['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Excluir</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
