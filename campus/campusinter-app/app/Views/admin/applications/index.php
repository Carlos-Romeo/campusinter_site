<!-- Candidatures -->
<div class="ci-admin__search">
    <form method="get" style="display: flex; gap: var(--ci-space-md); flex: 1; flex-wrap: wrap;">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Rechercher par référence, nom, email..." class="ci-admin__search-input" style="min-width: 300px;">
        <select name="status" class="ci-admin__search-input" style="max-width: 180px;">
            <option value="">Tous les statuts</option>
            <option value="new" <?= $status === 'new' ? 'selected' : '' ?>>Nouveau</option>
            <option value="processing" <?= $status === 'processing' ? 'selected' : '' ?>>En cours</option>
            <option value="accepted" <?= $status === 'accepted' ? 'selected' : '' ?>>Accepté</option>
            <option value="rejected" <?= $status === 'rejected' ? 'selected' : '' ?>>Refusé</option>
            <option value="archived" <?= $status === 'archived' ? 'selected' : '' ?>>Archivé</option>
        </select>
        <button type="submit" class="ci-btn ci-btn--primary ci-btn--sm">Rechercher</button>
    </form>
</div>

<p style="font-size: 0.875rem; color: var(--ci-gray-500); margin-bottom: var(--ci-space-md);">
    <?= $total ?> candidature<?= $total > 1 ? 's' : '' ?> trouvée<?= $total > 1 ? 's' : '' ?>
</p>

<div class="ci-admin__table-wrapper">
    <?php if (empty($applications)): ?>
        <div class="ci-empty">
            <p class="ci-empty__text">Aucune candidature trouvée.</p>
        </div>
    <?php else: ?>
        <table class="ci-admin__table">
            <thead>
                <tr>
                    <th>Référence</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Formation</th>
                    <th>Campus</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applications as $app): ?>
                    <tr>
                        <td>
                            <a href="/admin/applications/show?id=<?= $app['id'] ?>" style="font-weight: 600; color: var(--ci-secondary); font-family: var(--ci-font-mono); font-size: 0.8125rem;">
                                <?= htmlspecialchars($app['reference']) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($app['last_name'] . ' ' . $app['first_name']) ?></td>
                        <td style="font-size: 0.8125rem;"><?= htmlspecialchars($app['email']) ?></td>
                        <td><?= htmlspecialchars($app['program_name']) ?></td>
                        <td><?= htmlspecialchars($app['campus_name']) ?></td>
                        <td style="font-size: 0.8125rem; white-space: nowrap;"><?= date('d/m/Y H:i', strtotime($app['created_at'])) ?></td>
                        <td>
                            <select class="ci-admin__status-select" data-ci-status="<?= $app['id'] ?>">
                                <option value="new" <?= $app['status'] === 'new' ? 'selected' : '' ?>>Nouveau</option>
                                <option value="processing" <?= $app['status'] === 'processing' ? 'selected' : '' ?>>En cours</option>
                                <option value="accepted" <?= $app['status'] === 'accepted' ? 'selected' : '' ?>>Accepté</option>
                                <option value="rejected" <?= $app['status'] === 'rejected' ? 'selected' : '' ?>>Refusé</option>
                                <option value="archived" <?= $app['status'] === 'archived' ? 'selected' : '' ?>>Archivé</option>
                            </select>
                        </td>
                        <td>
                            <a href="/admin/applications/show?id=<?= $app['id'] ?>" class="ci-btn ci-btn--ghost ci-btn--sm">Voir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- Pagination -->
<?php if ($totalPages > 1): ?>
    <div class="ci-pagination" style="margin-top: var(--ci-space-lg);">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>" 
               class="ci-pagination__btn <?= $i === $page ? 'ci-pagination__btn--active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
<?php endif; ?>
