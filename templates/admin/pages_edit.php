<?php
/** @var array<string, mixed>|null $page */
/** @var array<int, string> $schemaTypes */
/** @var App\Models\Address|null $address */
/** @var array<int, array<string, mixed>> $contentBlocks */

use App\Seo\SchemaFormBuilder;

$isEdit = $page !== null;
$title = $isEdit ? 'Editar Página' : 'Nova Página';
$schemaType = $page['schema_type'] ?? 'WebPage';
$schemaValues = $isEdit ? SchemaFormBuilder::parseJson($page['schema_data'] ?? '{}', $schemaType) : [];
$activeTab = $_GET['tab'] ?? 'content';

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

<div class="tabs" data-tabs>
    <nav class="tabs-nav">
        <button type="button" class="tabs-nav-item <?= $activeTab === 'content' ? 'active' : '' ?>" data-tab="content">Conteúdo</button>
        <button type="button" class="tabs-nav-item <?= $activeTab === 'seo' ? 'active' : '' ?>" data-tab="seo">SEO</button>
        <button type="button" class="tabs-nav-item <?= $activeTab === 'address' ? 'active' : '' ?>" data-tab="address">Endereço</button>
    </nav>
    
    <form action="/admin/pages/save" method="POST" class="form tabs-form" id="page-form">
        <?php if ($isEdit): ?>
            <input type="hidden" name="id" value="<?= (int) $page['id'] ?>">
        <?php endif; ?>
        <input type="hidden" id="content_blocks" name="content_blocks" value='<?= htmlspecialchars(json_encode($contentBlocks)) ?>'>
        <input type="hidden" id="schema_data" name="schema_data" value="<?= htmlspecialchars($page['schema_data'] ?? '{}') ?>">
        
        <!-- TAB: CONTEÚDO -->
        <div class="tabs-panel <?= $activeTab === 'content' ? 'active' : '' ?>" data-tab="content">
            <div class="form-group">
                <label for="title">Título da Página</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($page['title'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="slug">URL Amigável (Slug)</label>
                <input type="text" id="slug" name="slug" value="<?= htmlspecialchars($page['slug'] ?? '') ?>" placeholder="ex: sobre-nos (deixe vazio para homepage)">
            </div>
            
            <div class="form-group">
                <label>Conteúdo da Página</label>
                <div id="block-editor" data-blocks="<?= htmlspecialchars(json_encode($contentBlocks)) ?>"></div>
                <p class="help-text">Adicione blocos de texto, imagens, vídeos e mais. Não precisa saber HTML!</p>
            </div>
            
            <div class="form-group form-check">
                <input type="checkbox" id="is_active" name="is_active" value="1" <?= ($page['is_active'] ?? 1) ? 'checked' : '' ?>>
                <label for="is_active">Página visível no site</label>
            </div>
        </div>
        
        <!-- TAB: SEO -->
        <div class="tabs-panel <?= $activeTab === 'seo' ? 'active' : '' ?>" data-tab="seo">
            <div class="form-group">
                <label for="meta_title">Título para o Google</label>
                <input type="text" id="meta_title" name="meta_title" value="<?= htmlspecialchars($page['meta_title'] ?? '') ?>">
                <span class="help-text">Título que aparece nos resultados de busca.</span>
            </div>
            
            <div class="form-group">
                <label for="meta_description">Descrição para o Google</label>
                <textarea id="meta_description" name="meta_description" rows="2"><?= htmlspecialchars($page['meta_description'] ?? '') ?></textarea>
                <span class="help-text">Resumo de até 160 caracteres.</span>
            </div>
            
            <div class="form-group">
                <label for="schema_type">Tipo de Informação</label>
                <select id="schema_type" name="schema_type">
                    <?php foreach ($schemaTypes as $type): ?>
                        <option value="<?= $type ?>" <?= $schemaType === $type ? 'selected' : '' ?>><?= $type ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Dados do Schema</label>
                <div id="schema-fields-container" data-initial="<?= htmlspecialchars(json_encode($schemaValues)) ?>"></div>
            </div>
        </div>
        
        <!-- TAB: ENDEREÇO -->
        <div class="tabs-panel <?= $activeTab === 'address' ? 'active' : '' ?>" data-tab="address">
            <?= \App\Core\View::partial('admin/_address_form', ['address' => $address]) ?>
        </div>
        
        <button type="submit" class="btn btn-primary">Salvar Página</button>
    </form>
</div>

<script>
window.schemaFieldDefs = <?= json_encode($fieldDefs) ?>;
</script>
<script src="/assets/schema-editor.js" defer></script>
<script src="/assets/block-editor.js" defer></script>
