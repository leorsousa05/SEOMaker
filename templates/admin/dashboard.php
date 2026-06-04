<?php /** @var array<string, mixed> $stats */ ?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total de Páginas</div>
        <div class="stat-value"><?= (int) $stats['total_pages'] ?></div>
    </div>
    <div class="stat-card blue">
        <div class="stat-label">Páginas Ativas</div>
        <div class="stat-value"><?= (int) $stats['active_pages'] ?></div>
    </div>
    <div class="stat-card purple">
        <div class="stat-label">Última Atualização</div>
        <div class="stat-value" style="font-size: 1.25rem;"><?= htmlspecialchars((string) $stats['last_update']) ?></div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Bem-vindo ao painel</h2>
    </div>
    <div class="card-body">
        <p style="color: var(--text-secondary); line-height: 1.7;">
            Aqui você gerencia todas as páginas do site, configurações de SEO, 
            galeria de imagens e dados estruturados (Schema.org).
        </p>
        <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem; flex-wrap: wrap;">
            <a href="/admin/pages/edit" class="btn btn-primary">+ Nova Página</a>
            <a href="/admin/media" class="btn btn-secondary">Galeria</a>
            <a href="/admin/settings" class="btn btn-ghost">Configurações</a>
        </div>
    </div>
</div>
