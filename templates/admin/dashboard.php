<?php /** @var array<string, mixed> $stats */ ?>

<div class="page-header">
    <h1>Dashboard</h1>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value"><?= (int) $stats['total_pages'] ?></div>
        <div class="stat-label">Total de Páginas</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= (int) $stats['active_pages'] ?></div>
        <div class="stat-label">Páginas Ativas</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= htmlspecialchars((string) $stats['last_update']) ?></div>
        <div class="stat-label">Última Atualização</div>
    </div>
</div>
