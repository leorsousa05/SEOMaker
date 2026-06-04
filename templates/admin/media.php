<?php
/** @var array<int, array<string, mixed>> $items */
/** @var int $page */
/** @var int $perPage */
/** @var int $total */
/** @var int $totalPages */
/** @var string|null $flash */
?>

<div class="page-header">
    <h1>Galeria de Mídia</h1>
</div>

<?php if ($flash): ?>
    <div class="alert alert-success"><?= htmlspecialchars($flash) ?></div>
<?php endif; ?>

<!-- Upload Zone -->
<div class="upload-zone" id="upload-zone">
    <div class="upload-zone-icon">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
    </div>
    <div class="upload-zone-text">
        <strong>Arraste imagens aqui</strong> ou <label class="upload-zone-link"><input type="file" id="upload-input" multiple accept="image/jpeg,image/png,image/gif,image/webp">clique para selecionar</label>
    </div>
    <div class="upload-zone-hint">JPG, PNG, GIF, WEBP até 5MB</div>
    <div class="upload-progress" id="upload-progress" style="display:none;">
        <div class="upload-progress-bar" id="upload-progress-bar"></div>
    </div>
</div>

<!-- Media Grid -->
<?php if (empty($items)): ?>
    <div class="empty-state">
        <p>Nenhuma imagem ainda.</p>
        <p class="empty-state-hint">Arraste imagens acima para começar.</p>
    </div>
<?php else: ?>
    <div class="media-grid" id="media-grid">
        <?php foreach ($items as $item): ?>
            <div class="media-item" data-id="<?= (int) $item['id'] ?>">
                <div class="media-thumb">
                    <img src="<?= htmlspecialchars($item['path']) ?>" alt="<?= htmlspecialchars($item['original_name']) ?>" loading="lazy">
                </div>
                <div class="media-info">
                    <div class="media-name" title="<?= htmlspecialchars($item['original_name']) ?>"><?= htmlspecialchars($item['original_name']) ?></div>
                    <div class="media-meta"><?= (int) $item['width'] ?>×<?= (int) $item['height'] ?> · <?= round((int) $item['size_bytes'] / 1024, 1) ?> KB</div>
                </div>
                <div class="media-actions">
                    <button type="button" class="btn-copy-url" data-url="<?= htmlspecialchars($item['path']) ?>" title="Copiar URL">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                    </button>
                    <a href="/admin/media/delete/<?= (int) $item['id'] ?>" class="btn-delete" onclick="return confirm('Remover esta imagem?')" title="Remover">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>" class="btn">← Anterior</a>
            <?php endif; ?>
            <span class="pagination-info">Página <?= $page ?> de <?= $totalPages ?> (<?= $total ?> imagens)</span>
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>" class="btn">Próxima →</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<script src="/assets/media.js" defer></script>
