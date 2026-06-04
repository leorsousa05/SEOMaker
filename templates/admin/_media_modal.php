<?php
/** @var array<int, array<string, mixed>> $items */
?>

<div class="modal-overlay" id="media-modal-overlay" style="display:none;">
    <div class="modal media-modal">
        <div class="modal-header">
            <h3>Selecionar Imagem</h3>
            <button type="button" class="modal-close" id="media-modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <div class="media-modal-grid" id="media-modal-grid">
                <?php foreach ($items as $item): ?>
                    <div class="media-modal-item" data-url="<?= htmlspecialchars($item['path']) ?>" data-id="<?= (int) $item['id'] ?>">
                        <img src="<?= htmlspecialchars($item['path']) ?>" alt="" loading="lazy">
                        <div class="media-modal-select">✓</div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn" id="media-modal-cancel">Cancelar</button>
            <button type="button" class="btn btn-primary" id="media-modal-confirm">Selecionar</button>
        </div>
    </div>
</div>
