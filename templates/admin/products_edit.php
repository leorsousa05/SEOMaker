<?php
/**
 * @var array<string, mixed>|null $product
 * @var string|null $imagePath
 * @var array<int, array<string, mixed>> $galleryItems
 */

$isEdit = $product !== null;

$formErrors = $_SESSION['form_errors'] ?? [];
$formData   = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_errors'], $_SESSION['form_data']);

$product = array_merge($product ?? [], $formData);

$activeTab = $_GET['tab'] ?? 'general';

$val = fn(string $key, mixed $default = '') => htmlspecialchars((string)($product[$key] ?? $default));
?>

<?php if (!empty($formErrors)): ?>
<div class="alert alert-danger">
    <strong>Corrija os erros abaixo:</strong>
    <ul style="margin-top:0.5rem;margin-bottom:0;padding-left:1.25rem;">
        <?php foreach ($formErrors as $message): ?>
            <li><?= htmlspecialchars($message) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<div class="tabs" data-tabs>
    <nav class="tabs-nav">
        <button type="button" class="tabs-nav-item <?= $activeTab === 'general'  ? 'active' : '' ?>" data-tab="general">Geral</button>
        <button type="button" class="tabs-nav-item <?= $activeTab === 'content'  ? 'active' : '' ?>" data-tab="content">Conteúdo</button>
        <button type="button" class="tabs-nav-item <?= $activeTab === 'media'    ? 'active' : '' ?>" data-tab="media">Mídia</button>
        <button type="button" class="tabs-nav-item <?= $activeTab === 'category' ? 'active' : '' ?>" data-tab="category">Categoria &amp; Tags</button>
    </nav>

    <form action="/admin/products/save" method="POST" class="form tabs-form" id="product-form">
        <?php if ($isEdit): ?>
            <input type="hidden" name="id" value="<?= (int)$product['id'] ?>">
        <?php endif; ?>
        <input type="hidden" name="image_id"    id="field_image_id"    value="<?= $val('image_id') ?>">
        <input type="hidden" name="gallery_ids" id="field_gallery_ids" value="<?= $val('gallery_ids', '[]') ?>">

        <!-- ======== GENERAL TAB ======== -->
        <div class="tabs-panel <?= $activeTab === 'general' ? 'active' : '' ?>" data-tab="general">
            <div class="card">
                <div class="card-body">
                    <div class="form-group <?= isset($formErrors['name']) ? 'has-error' : '' ?>">
                        <label for="name">Nome do produto <span style="color:var(--danger)">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" value="<?= $val('name') ?>" required>
                        <?php if (isset($formErrors['name'])): ?>
                            <span class="error-text"><?= htmlspecialchars($formErrors['name']) ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group <?= isset($formErrors['slug']) ? 'has-error' : '' ?>">
                        <label for="slug">Slug (URL amigável)</label>
                        <input type="text" id="slug" name="slug" class="form-control" value="<?= $val('slug') ?>" placeholder="gerado-automaticamente">
                        <?php if (isset($formErrors['slug'])): ?>
                            <span class="error-text"><?= htmlspecialchars($formErrors['slug']) ?></span>
                        <?php endif; ?>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div class="form-group <?= isset($formErrors['price']) ? 'has-error' : '' ?>">
                            <label for="price">Preço (R$) <span style="color:var(--danger)">*</span></label>
                            <input type="number" id="price" name="price" class="form-control" step="0.01" min="0" value="<?= $val('price', '0') ?>">
                            <?php if (isset($formErrors['price'])): ?>
                                <span class="error-text"><?= htmlspecialchars($formErrors['price']) ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="form-group <?= isset($formErrors['promo_price']) ? 'has-error' : '' ?>">
                            <label for="promo_price">Preço Promocional (R$)</label>
                            <input type="number" id="promo_price" name="promo_price" class="form-control" step="0.01" min="0" value="<?= $val('promo_price') ?>">
                            <?php if (isset($formErrors['promo_price'])): ?>
                                <span class="error-text"><?= htmlspecialchars($formErrors['promo_price']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                        <div class="form-group">
                            <label for="sku">SKU / Código</label>
                            <input type="text" id="sku" name="sku" class="form-control" value="<?= $val('sku') ?>">
                        </div>
                        <div class="form-group">
                            <label for="stock">Estoque</label>
                            <input type="number" id="stock" name="stock" class="form-control" min="0" value="<?= $val('stock', '0') ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="external_link">Link externo (Mercado Livre, Shopify, etc.)</label>
                        <input type="url" id="external_link" name="external_link" class="form-control" value="<?= $val('external_link') ?>" placeholder="https://...">
                    </div>

                    <div style="display:flex;gap:2rem;align-items:center;margin-top:0.5rem;">
                        <label class="checkbox-label" style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;">
                            <input type="checkbox" name="is_active" value="1" <?= !empty($product['is_active']) || !$isEdit ? 'checked' : '' ?>>
                            Produto ativo
                        </label>
                        <label class="checkbox-label" style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;">
                            <input type="checkbox" name="featured" value="1" <?= !empty($product['featured']) ? 'checked' : '' ?>>
                            ★ Destacar na home
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- ======== CONTENT TAB ======== -->
        <div class="tabs-panel <?= $activeTab === 'content' ? 'active' : '' ?>" data-tab="content">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="short_description">Descrição curta</label>
                        <textarea id="short_description" name="short_description" class="form-control" rows="3" placeholder="Resumo exibido no card do produto..."><?= $val('short_description') ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="description">Descrição completa</label>
                        <textarea id="description" name="description" class="form-control" rows="8" placeholder="Descrição detalhada do produto..."><?= $val('description') ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- ======== MEDIA TAB ======== -->
        <div class="tabs-panel <?= $activeTab === 'media' ? 'active' : '' ?>" data-tab="media">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label>Imagem principal</label>
                        <div id="image_preview_wrap" style="margin-bottom:0.75rem;">
                            <?php if ($imagePath): ?>
                                <img id="image_preview" src="<?= htmlspecialchars($imagePath) ?>" alt="" style="max-width:200px;max-height:200px;object-fit:cover;border-radius:var(--radius);border:1px solid var(--border);">
                            <?php else: ?>
                                <div id="image_preview_placeholder" style="width:200px;height:140px;background:var(--bg-muted);border:2px dashed var(--border);border-radius:var(--radius);display:flex;align-items:center;justify-content:center;color:var(--text-muted);font-size:0.85rem;">
                                    Nenhuma imagem
                                </div>
                            <?php endif; ?>
                        </div>
                        <button type="button" class="btn btn-ghost" id="btn_choose_image">📁 Escolher Imagem</button>
                        <?php if ($imagePath): ?>
                            <button type="button" class="btn btn-ghost btn-sm" id="btn_remove_image" style="margin-left:0.5rem;">✕ Remover</button>
                        <?php endif; ?>
                    </div>

                    <hr style="border:none;border-top:1px solid var(--border);margin:1.5rem 0;">

                    <div class="form-group">
                        <label>Galeria de imagens adicionais</label>
                        <div id="gallery_preview" style="display:flex;flex-wrap:wrap;gap:0.5rem;margin-bottom:0.75rem;">
                            <?php foreach ($galleryItems as $gi): ?>
                                <div class="gallery-thumb" data-id="<?= $gi['id'] ?>" style="position:relative;width:80px;height:80px;">
                                    <img src="<?= htmlspecialchars($gi['path']) ?>" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:var(--radius);border:1px solid var(--border);">
                                    <button type="button" onclick="removeGalleryItem(<?= $gi['id'] ?>)" style="position:absolute;top:-6px;right:-6px;background:var(--danger);color:#fff;border:none;border-radius:50%;width:20px;height:20px;cursor:pointer;font-size:0.7rem;display:flex;align-items:center;justify-content:center;padding:0;">✕</button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" class="btn btn-ghost" id="btn_add_gallery">📁 Adicionar Imagens</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ======== CATEGORY/TAGS TAB ======== -->
        <div class="tabs-panel <?= $activeTab === 'category' ? 'active' : '' ?>" data-tab="category">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label for="category">Categoria</label>
                        <input type="text" id="category" name="category" class="form-control" value="<?= $val('category') ?>" placeholder="Ex: Eletrônicos, Roupas, Serviços...">
                    </div>
                    <div class="form-group">
                        <label for="tags">Tags</label>
                        <input type="text" id="tags" name="tags" class="form-control" value="<?= $val('tags') ?>" placeholder="Separadas por vírgula: promoção, novo, destaque">
                        <small style="color:var(--text-muted);">Separe as tags com vírgula.</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- SAVE BAR -->
        <div style="display:flex;align-items:center;gap:1rem;margin-top:1.5rem;">
            <button type="submit" class="btn btn-primary btn-lg">
                <?= $isEdit ? 'Salvar alterações' : 'Criar produto' ?>
            </button>
            <a href="/admin/products" class="btn btn-ghost">Cancelar</a>
        </div>
    </form>
</div>

<?php
// Load media modal partial
echo \App\Core\View::partial('admin/_media_modal', []);
?>

<script src="/assets/block-editor.js"></script>
<script>
(function () {
    // ── Image picker ──────────────────────────────────────────────────────
    var imageIdField = document.getElementById('field_image_id');
    var imgWrap      = document.getElementById('image_preview_wrap');
    var btnChoose    = document.getElementById('btn_choose_image');
    var btnRemove    = document.getElementById('btn_remove_image');

    function setImagePreview(path) {
        imgWrap.innerHTML = '<img id="image_preview" src="' + path + '" alt="" style="max-width:200px;max-height:200px;object-fit:cover;border-radius:var(--radius);border:1px solid var(--border);">';
    }

    if (btnChoose) {
        btnChoose.addEventListener('click', function () {
            openMediaModal(function (selected) {
                if (!selected || !selected.length) return;
                var item = selected[0];
                imageIdField.value = item.id;
                setImagePreview(item.path);
                if (!document.getElementById('btn_remove_image')) {
                    var rb = document.createElement('button');
                    rb.type = 'button';
                    rb.className = 'btn btn-ghost btn-sm';
                    rb.id = 'btn_remove_image';
                    rb.style.marginLeft = '0.5rem';
                    rb.textContent = '✕ Remover';
                    btnChoose.insertAdjacentElement('afterend', rb);
                    rb.addEventListener('click', removeImage);
                }
            }, false);
        });
    }

    function removeImage() {
        imageIdField.value = '';
        imgWrap.innerHTML = '<div id="image_preview_placeholder" style="width:200px;height:140px;background:var(--bg-muted);border:2px dashed var(--border);border-radius:var(--radius);display:flex;align-items:center;justify-content:center;color:var(--text-muted);font-size:0.85rem;">Nenhuma imagem</div>';
        var rb = document.getElementById('btn_remove_image');
        if (rb) rb.remove();
    }

    if (btnRemove) {
        btnRemove.addEventListener('click', removeImage);
    }

    // ── Gallery picker ────────────────────────────────────────────────────
    var galleryIdsField = document.getElementById('field_gallery_ids');
    var galleryPreview  = document.getElementById('gallery_preview');
    var btnGallery      = document.getElementById('btn_add_gallery');

    function getGalleryIds() {
        try { return JSON.parse(galleryIdsField.value || '[]'); } catch(e) { return []; }
    }

    function setGalleryIds(ids) {
        galleryIdsField.value = JSON.stringify(ids);
    }

    window.removeGalleryItem = function(id) {
        var ids = getGalleryIds().filter(function(i) { return i !== id; });
        setGalleryIds(ids);
        var thumb = galleryPreview.querySelector('[data-id="' + id + '"]');
        if (thumb) thumb.remove();
    };

    if (btnGallery) {
        btnGallery.addEventListener('click', function () {
            openMediaModal(function (selected) {
                if (!selected || !selected.length) return;
                var ids = getGalleryIds();
                selected.forEach(function (item) {
                    if (ids.indexOf(item.id) !== -1) return;
                    ids.push(item.id);
                    var wrap = document.createElement('div');
                    wrap.className = 'gallery-thumb';
                    wrap.dataset.id = item.id;
                    wrap.style.cssText = 'position:relative;width:80px;height:80px;';
                    wrap.innerHTML =
                        '<img src="' + item.path + '" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:var(--radius);border:1px solid var(--border);">' +
                        '<button type="button" onclick="removeGalleryItem(' + item.id + ')" style="position:absolute;top:-6px;right:-6px;background:var(--danger);color:#fff;border:none;border-radius:50%;width:20px;height:20px;cursor:pointer;font-size:0.7rem;display:flex;align-items:center;justify-content:center;padding:0;">✕</button>';
                    galleryPreview.appendChild(wrap);
                });
                setGalleryIds(ids);
            }, true);
        });
    }
})();
</script>
