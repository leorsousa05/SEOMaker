<?php
/**
 * @var array<int, array<string, mixed>> $products
 * @var string|null $flash
 */
?>

<?php if ($flash): ?>
    <div class="alert alert-success"><?= htmlspecialchars($flash) ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <div>
            <h2>Produtos</h2>
            <p>Gerencie o catálogo de produtos do seu site.</p>
        </div>
        <a href="/admin/products/edit" class="btn btn-primary">+ Novo Produto</a>
    </div>

    <?php if (empty($products)): ?>
        <div class="empty-state">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>
            </svg>
            <p>Nenhum produto cadastrado ainda.</p>
            <a href="/admin/products/edit" class="btn btn-primary">Criar primeiro produto</a>
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Categoria</th>
                        <th>Preço</th>
                        <th>Estoque</th>
                        <th>Status</th>
                        <th>Destaque</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:0.75rem;">
                                    <?php if (!empty($product['image_path'])): ?>
                                        <img src="<?= htmlspecialchars($product['image_path']) ?>" alt="" style="width:40px;height:40px;object-fit:cover;border-radius:var(--radius);border:1px solid var(--border);">
                                    <?php else: ?>
                                        <div style="width:40px;height:40px;background:var(--bg-muted);border-radius:var(--radius);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;color:var(--text-muted);">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <div style="font-weight:600;color:var(--text-primary);"><?= htmlspecialchars($product['name']) ?></div>
                                        <?php if (!empty($product['sku'])): ?>
                                            <div style="font-size:0.75rem;color:var(--text-muted);">SKU: <?= htmlspecialchars($product['sku']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($product['category'] ?: '—') ?></td>
                            <td>
                                <?php if (!empty($product['promo_price'])): ?>
                                    <span style="text-decoration:line-through;color:var(--text-muted);font-size:0.8rem;">R$ <?= number_format((float)$product['price'], 2, ',', '.') ?></span><br>
                                    <strong style="color:var(--accent);">R$ <?= number_format((float)$product['promo_price'], 2, ',', '.') ?></strong>
                                <?php else: ?>
                                    R$ <?= number_format((float)$product['price'], 2, ',', '.') ?>
                                <?php endif; ?>
                            </td>
                            <td><?= (int)$product['stock'] ?></td>
                            <td>
                                <?php if ($product['is_active']): ?>
                                    <span class="badge badge-green">Ativo</span>
                                <?php else: ?>
                                    <span class="badge badge-gray">Inativo</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($product['featured']): ?>
                                    <span class="badge badge-blue">★ Destaque</span>
                                <?php else: ?>
                                    <span style="color:var(--text-muted);font-size:0.8rem;">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="/admin/products/edit/<?= $product['id'] ?>" class="btn btn-ghost btn-sm">Editar</a>
                                    <a href="/admin/products/delete/<?= $product['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Remover este produto?')">Excluir</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
