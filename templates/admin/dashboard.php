<?php /** @var array<string, mixed> $stats */ ?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
        </div>
        <div>
            <div class="stat-label">Total de Páginas</div>
            <div class="stat-value"><?= (int) $stats['total_pages'] ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon--accent">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <div>
            <div class="stat-label">Páginas Ativas</div>
            <div class="stat-value"><?= (int) $stats['active_pages'] ?></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon--blue">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div>
            <div class="stat-label">Última Atualização</div>
            <div class="stat-value" style="font-size: 1.25rem;"><?= htmlspecialchars((string) $stats['last_update']) ?></div>
        </div>
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
