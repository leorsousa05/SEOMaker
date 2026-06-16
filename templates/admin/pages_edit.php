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

$formErrors = $_SESSION['form_errors'] ?? [];
$formData = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_errors'], $_SESSION['form_data']);

$page = array_merge($page ?? [], $formData);

$fieldDefs = [];
foreach ($schemaTypes as $type) {
    $fieldDefs[$type] = SchemaFormBuilder::fieldsForType($type);
}
?>

<?php if (!empty($formErrors)): ?>
<div class="alert alert-danger">
    <strong>Corrija os erros abaixo:</strong>
    <ul style="margin-top: 0.5rem; margin-bottom: 0; padding-left: 1.25rem;">
        <?php foreach ($formErrors as $field => $message): ?>
            <li><?= htmlspecialchars($message) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

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
            <div class="card" style="margin-bottom: 1.5rem;">
                <div class="card-body">
                    <div class="form-group <?= isset($formErrors['title']) ? 'has-error' : '' ?>">
                        <label for="title">Título da Página</label>
                        <input type="text" id="title" name="title" value="<?= htmlspecialchars($page['title'] ?? '') ?>" required>
                        <?php if (isset($formErrors['title'])): ?>
                            <span class="error-text"><?= htmlspecialchars($formErrors['title']) ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group <?= isset($formErrors['slug']) ? 'has-error' : '' ?>">
                        <label for="slug">URL Amigável (Slug)</label>
                        <input type="text" id="slug" name="slug" value="<?= htmlspecialchars($page['slug'] ?? '') ?>" placeholder="ex: sobre-nos (deixe vazio para homepage)">
                        <?php if (isset($formErrors['slug'])): ?>
                            <span class="error-text"><?= htmlspecialchars($formErrors['slug']) ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="form-group form-check">
                        <input type="checkbox" id="auto_slug" name="auto_slug" value="1" <?= ($page['auto_slug'] ?? 0) ? 'checked' : '' ?>>
                        <label for="auto_slug">Gerar slug automaticamente a partir do título</label>
                    </div>
                    
                    <div class="form-group <?= isset($formErrors['content_blocks']) ? 'has-error' : '' ?>">
                        <label>Conteúdo da Página</label>
                        <div id="block-editor" data-blocks="<?= htmlspecialchars(json_encode($contentBlocks)) ?>"></div>
                        <?php if (isset($formErrors['content_blocks'])): ?>
                            <span class="error-text"><?= htmlspecialchars($formErrors['content_blocks']) ?></span>
                        <?php endif; ?>
                        <p class="help-text">Adicione blocos de texto, imagens, vídeos e mais. Não precisa saber HTML!</p>
                    </div>
                    
                    <div class="form-group form-check">
                        <input type="checkbox" id="is_active" name="is_active" value="1" <?= ($page['is_active'] ?? 1) ? 'checked' : '' ?>>
                        <label for="is_active">Página visível no site</label>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- TAB: SEO -->
        <div class="tabs-panel <?= $activeTab === 'seo' ? 'active' : '' ?>" data-tab="seo">
            <?= \App\Core\View::partial('admin/_seo_preview', ['page' => $page ?? []]) ?>
            
            <div class="card">
                <div class="card-body">
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
                        <label>Indexação do Google</label>
                        <div class="form-check-group">
                            <label class="form-check">
                                <input type="radio" name="meta_robots[]" value="index" <?= (strpos($page['meta_robots'] ?? 'index, follow', 'noindex') === false) ? 'checked' : '' ?>>
                                index
                            </label>
                            <label class="form-check">
                                <input type="radio" name="meta_robots[]" value="noindex" <?= (strpos($page['meta_robots'] ?? 'index, follow', 'noindex') !== false) ? 'checked' : '' ?>>
                                noindex
                            </label>
                        </div>
                        <div class="form-check-group">
                            <label class="form-check">
                                <input type="radio" name="meta_robots[]" value="follow" <?= (strpos($page['meta_robots'] ?? 'index, follow', 'nofollow') === false) ? 'checked' : '' ?>>
                                follow
                            </label>
                            <label class="form-check">
                                <input type="radio" name="meta_robots[]" value="nofollow" <?= (strpos($page['meta_robots'] ?? 'index, follow', 'nofollow') !== false) ? 'checked' : '' ?>>
                                nofollow
                            </label>
                        </div>
                        <span class="help-text">Escolha se os motores de busca devem indexar esta página e seguir os links.</span>
                    </div>
                    
                    <div class="form-group">
                        <label for="canonical_url">URL Canônica</label>
                        <input type="url" id="canonical_url" name="canonical_url" value="<?= htmlspecialchars($page['canonical_url'] ?? '') ?>" placeholder="https://exemplo.com/pagina">
                        <span class="help-text">Deixe em branco para usar a URL automática desta página.</span>
                    </div>
                    
                    <div class="form-group">
                        <label>Imagem de compartilhamento social</label>
                        <div class="media-picker" id="og-image-picker">
                            <?php if (!empty($page['og_image_id'])): ?>
                                <img src="<?= htmlspecialchars($page['og_image_path'] ?? '') ?>" alt="" class="media-picker-thumb">
                            <?php else: ?>
                                <div class="media-picker-placeholder">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                    <span>Nenhuma imagem selecionada</span>
                                </div>
                            <?php endif; ?>
                            <div class="media-picker-actions">
                                <button type="button" class="btn btn-ghost" id="og-image-select">Selecionar imagem</button>
                                <button type="button" class="btn btn-danger" id="og-image-remove" style="<?= empty($page['og_image_id']) ? 'display:none' : '' ?>">Remover</button>
                            </div>
                        </div>
                        <input type="hidden" id="og_image_id" name="og_image_id" value="<?= htmlspecialchars((string) ($page['og_image_id'] ?? '')) ?>">
                        <span class="help-text">Recomendado: 1200×630px. Usada no Facebook, WhatsApp e Twitter.</span>
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
            </div>
        </div>
        
        <!-- TAB: ENDEREÇO -->
        <div class="tabs-panel <?= $activeTab === 'address' ? 'active' : '' ?>" data-tab="address">
            <div class="card">
                <div class="card-body">
                    <?= \App\Core\View::partial('admin/_address_form', ['address' => $address]) ?>
                </div>
            </div>
        </div>
        
        <div style="margin-top: 1.5rem;">
            <button type="submit" class="btn btn-primary btn-lg">Salvar Página</button>
            <a href="/admin/pages" class="btn btn-ghost">Cancelar</a>
        </div>
    </form>
</div>

<!-- Media Selection Modal -->
<div class="modal-overlay" id="media-modal-overlay" style="display:none;">
    <div class="modal media-modal">
        <div class="modal-header">
            <h3>Escolher Imagem da Galeria</h3>
            <button type="button" class="modal-close" id="media-modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <div class="media-modal-grid" id="media-modal-grid"></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-ghost" id="media-modal-cancel">Cancelar</button>
            <button type="button" class="btn btn-primary" id="media-modal-confirm">Usar Imagem Selecionada</button>
        </div>
    </div>
</div>

<script>
window.schemaFieldDefs = <?= json_encode($fieldDefs) ?>;
</script>
<script src="/assets/schema-editor.js" defer></script>
<script src="/assets/block-editor.js" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var selectBtn = document.getElementById('og-image-select');
    var removeBtn = document.getElementById('og-image-remove');
    var hiddenInput = document.getElementById('og_image_id');
    var picker = document.getElementById('og-image-picker');
    if (!selectBtn || !hiddenInput || !picker) return;

    selectBtn.addEventListener('click', function () {
        if (typeof window.openMediaModal !== 'function') return;
        window.openMediaModal(function (url) {
            if (!url) return;
            fetch('/admin/media/json')
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (!data.success || !data.items) return;
                    var item = data.items.find(function (i) { return i.path === url; });
                    if (!item) return;
                    hiddenInput.value = item.id;
                    var thumb = picker.querySelector('.media-picker-thumb');
                    if (thumb) {
                        thumb.src = item.path;
                    } else {
                        var placeholder = picker.querySelector('.media-picker-placeholder');
                        if (placeholder) {
                            thumb = document.createElement('img');
                            thumb.src = item.path;
                            thumb.alt = '';
                            thumb.className = 'media-picker-thumb';
                            placeholder.parentNode.replaceChild(thumb, placeholder);
                        }
                    }
                    if (removeBtn) removeBtn.style.display = '';
                });
        });
    });

    if (removeBtn) {
        removeBtn.addEventListener('click', function () {
            hiddenInput.value = '';
            var thumb = picker.querySelector('.media-picker-thumb');
            if (thumb) {
                var placeholder = document.createElement('div');
                placeholder.className = 'media-picker-placeholder';
                placeholder.innerHTML = '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg><span>Nenhuma imagem selecionada</span>';
                thumb.parentNode.replaceChild(placeholder, thumb);
            }
            removeBtn.style.display = 'none';
        });
    }
});
</script>
