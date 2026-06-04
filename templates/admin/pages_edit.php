<?php
/** @var array<string, mixed>|null $page */
/** @var array<int, string> $schemaTypes */

use App\Seo\SchemaFormBuilder;

$isEdit = $page !== null;
$title = $isEdit ? 'Editar Página' : 'Nova Página';
$schemaType = $page['schema_type'] ?? 'WebPage';
$schemaValues = $isEdit ? SchemaFormBuilder::parseJson($page['schema_data'] ?? '{}', $schemaType) : [];

// Build field definitions JS
$fieldDefs = [];
foreach ($schemaTypes as $type) {
    $fieldDefs[$type] = SchemaFormBuilder::fieldsForType($type);
}
?>

<div class="page-header">
    <h1><?= $title ?></h1>
    <a href="/admin/pages" class="btn">Voltar</a>
</div>

<?= \App\Core\View::partial('admin/_seo_preview', ['page' => $page ?? []]) ?>

<div data-schema-editor>
    <form action="/admin/pages/save" method="POST" class="form">
        <?php if ($isEdit): ?>
            <input type="hidden" name="id" value="<?= (int) $page['id'] ?>">
        <?php endif; ?>
        
        <div class="form-group">
            <label for="title">Título da Página</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($page['title'] ?? '') ?>" required>
        </div>
        
        <div class="form-group">
            <label for="slug">URL Amigável (Slug)</label>
            <input type="text" id="slug" name="slug" value="<?= htmlspecialchars($page['slug'] ?? '') ?>" placeholder="ex: sobre-nos (deixe vazio para homepage)">
            <span class="help-text">Usado na URL. Deixe vazio para a página inicial.</span>
        </div>
        
        <div class="form-group">
            <label for="meta_title">Título para o Google (SEO)</label>
            <input type="text" id="meta_title" name="meta_title" value="<?= htmlspecialchars($page['meta_title'] ?? '') ?>">
            <span class="help-text">Título que aparece nos resultados de busca. Se vazio, usa o título da página.</span>
        </div>
        
        <div class="form-group">
            <label for="meta_description">Descrição para o Google (SEO)</label>
            <textarea id="meta_description" name="meta_description" rows="2"><?= htmlspecialchars($page['meta_description'] ?? '') ?></textarea>
            <span class="help-text">Resumo de até 160 caracteres que aparece no Google.</span>
        </div>
        
        <div class="form-group">
            <label for="content_html">Conteúdo da Página</label>
            <textarea id="content_html" name="content_html" rows="10"><?= htmlspecialchars($page['content_html'] ?? '') ?></textarea>
            <span class="help-text">Você pode usar HTML básico para formatar o conteúdo.</span>
        </div>
        
        <div class="form-group">
            <label for="schema_type">Tipo de Informação (Schema)</label>
            <select id="schema_type" name="schema_type">
                <?php foreach ($schemaTypes as $type): ?>
                    <option value="<?= $type ?>" <?= $schemaType === $type ? 'selected' : '' ?>><?= $type ?></option>
                <?php endforeach; ?>
            </select>
            <span class="help-text">Ajuda o Google a entender melhor o conteúdo desta página.</span>
        </div>
        
        <div class="form-group">
            <label>Dados do Schema</label>
            <div id="schema-fields-container" data-initial="<?= htmlspecialchars(json_encode($schemaValues)) ?>"></div>
            <input type="hidden" id="schema_data" name="schema_data" value="<?= htmlspecialchars($page['schema_data'] ?? '{}') ?>">
        </div>
        
        <div class="form-group form-check">
            <input type="checkbox" id="is_active" name="is_active" value="1" <?= ($page['is_active'] ?? 1) ? 'checked' : '' ?>>
            <label for="is_active">Página visível no site</label>
        </div>
        
        <button type="submit" class="btn btn-primary">Salvar Página</button>
    </form>
</div>

<script>
window.schemaFieldDefs = <?= json_encode($fieldDefs) ?>;
</script>
<script src="/assets/schema-editor.js" defer></script>
