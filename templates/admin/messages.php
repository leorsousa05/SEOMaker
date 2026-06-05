<?php
/** @var array<int, array<string, mixed>> $messages */
/** @var array<string, int> $counts */
/** @var string $status */
/** @var string|null $flash */

$statusLabels = [
    'all' => 'Todas',
    'new' => 'Novas',
    'replied' => 'Respondidas',
    'archived' => 'Arquivadas',
];

$statusBadges = [
    'new' => 'badge-blue',
    'replied' => 'badge-green',
    'archived' => 'badge-gray',
];
?>

<?php if ($flash): ?>
    <div class="alert alert-success"><?= htmlspecialchars($flash) ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <div>
            <h2>Mensagens recebidas</h2>
            <p><?= $counts['all'] ?> mensagem(ns) no total</p>
        </div>
    </div>
    
    <div style="padding: 0 1.5rem; border-bottom: 1px solid var(--border-light);">
        <div class="tabs-nav" style="margin-bottom: 0; border-bottom: none;">
            <?php foreach ($counts as $key => $count): ?>
                <a href="/admin/messages?status=<?= $key ?>" class="tabs-nav-item <?= $status === $key ? 'active' : '' ?>" style="text-decoration: none;">
                    <?= $statusLabels[$key] ?> (<?= $count ?>)
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    
    <?php if (empty($messages)): ?>
        <div class="empty-state" style="border: none; border-radius: 0;">
            <p>Nenhuma mensagem encontrada.</p>
        </div>
    <?php else: ?>
        <div class="table-wrap" style="border: none; border-radius: 0; box-shadow: none;">
            <table class="table">
                <thead>
                    <tr>
                        <th>De</th>
                        <th>Contato</th>
                        <th>Mensagem</th>
                        <th>Data</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $msg): ?>
                    <tr>
                        <td style="font-weight: 500;"><?= htmlspecialchars($msg['name']) ?></td>
                        <td>
                            <div style="font-size: 0.75rem; color: var(--text-secondary);"><?= htmlspecialchars($msg['email']) ?></div>
                            <?php if ($msg['phone']): ?>
                            <div style="font-size: 0.75rem; color: var(--text-muted);"><?= htmlspecialchars($msg['phone']) ?></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="max-width: 320px; font-size: 0.8125rem; color: var(--text-secondary); line-height: 1.5;">
                                <?= nl2br(htmlspecialchars(mb_strimwidth($msg['message'], 0, 140, '...'))) ?>
                            </div>
                        </td>
                        <td style="font-size: 0.75rem; color: var(--text-muted); white-space: nowrap;"><?= htmlspecialchars($msg['created_at']) ?></td>
                        <td>
                            <span class="badge <?= $statusBadges[$msg['status']] ?? 'badge-gray' ?>"><?= ucfirst($msg['status']) ?></span>
                        </td>
                        <td>
                            <?php if ($msg['status'] !== 'replied'): ?>
                                <a href="/admin/messages/reply/<?= (int) $msg['id'] ?>" class="btn btn-sm btn-ghost">Responder</a>
                            <?php endif; ?>
                            <?php if ($msg['status'] !== 'archived'): ?>
                                <a href="/admin/messages/archive/<?= (int) $msg['id'] ?>" class="btn btn-sm btn-ghost">Arquivar</a>
                            <?php endif; ?>
                            <a href="/admin/messages/delete/<?= (int) $msg['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Remover esta mensagem?')">Excluir</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
