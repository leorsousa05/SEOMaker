<?php
/** @var array<string, mixed>|null $page */
/** @var array<int, string> $schemaTypes */

$isEdit = $page !== null;
$title = $isEdit ? 'Editar Página' : 'Nova Página';
?>

<div class="page-header">
    <h1><?= $title ?></h1>
    <a href="/admin/pages" class="btn">Voltar</a>
</div>

<form action="/admin/pages/save" method="POST" class="form">
    <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= (int) $page['id'] ?>">
    <?php endif; ?>
    
    <div class="form-group">
        <label for="title">Título</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($page['title'] ?? '') ?>" required>
    </div>
    
    <div class="form-group">
        <label for="slug">Slug</label>
        <input type="text" id="slug" name="slug" value="<?= htmlspecialchars($page['slug'] ?? '') ?>" required placeholder="ex: sobre-nos (deixe vazio para homepage)">
    </div>
    
    <div class="form-group">
        <label for="meta_title">Meta Título (SEO)</label>
        <input type="text" id="meta_title" name="meta_title" value="<?= htmlspecialchars($page['meta_title'] ?? '') ?>">
    </div>
    
    <div class="form-group">
        <label for="meta_description">Meta Descrição (SEO)</label>
        <textarea id="meta_description" name="meta_description" rows="2"><?= htmlspecialchars($page['meta_description'] ?? '') ?></textarea>
    </div>
    
    <div class="form-group">
        <label for="content_html">Conteúdo HTML</label>
        <textarea id="content_html" name="content_html" rows="10"><?= htmlspecialchars($page['content_html'] ?? '') ?></textarea>
    </div>
    
    <div class="form-group">
        <label for="schema_type">Tipo Schema.org</label>
        <select id="schema_type" name="schema_type">
            <?php foreach ($schemaTypes as $type): ?>
                <option value="<?= $type ?>" <?= ($page['schema_type'] ?? 'WebPage') === $type ? 'selected' : '' ?>><?= $type ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="form-group">
        <label for="schema_data">Dados Schema (JSON)</label>
        <textarea id="schema_data" name="schema_data" rows="5"><?= htmlspecialchars($page['schema_data'] ?? '{}') ?></textarea>
    </div>
    
    <div class="form-group form-check">
        <input type="checkbox" id="is_active" name="is_active" value="1" <?= ($page['is_active'] ?? 1) ? 'checked' : '' ?>>
        <label for="is_active">Página ativa</label>
    </div>
    
    <button type="submit" class="btn btn-primary">Salvar Página</button>
</form>
