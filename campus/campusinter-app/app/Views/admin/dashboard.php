<!-- Dashboard -->
<div class="ci-admin__stats ci-stagger">
    <div class="ci-admin__stat">
        <div class="ci-admin__stat-value"><?= $stats['today'] ?></div>
        <div class="ci-admin__stat-label">Aujourd'hui</div>
    </div>
    <div class="ci-admin__stat">
        <div class="ci-admin__stat-value"><?= $stats['week'] ?></div>
        <div class="ci-admin__stat-label">Cette semaine</div>
    </div>
    <div class="ci-admin__stat">
        <div class="ci-admin__stat-value"><?= $stats['total'] ?></div>
        <div class="ci-admin__stat-label">Total candidatures</div>
    </div>
    <div class="ci-admin__stat">
        <div class="ci-admin__stat-value"><?= $stats['programs'] ?></div>
        <div class="ci-admin__stat-label">Formations actives</div>
    </div>
    <div class="ci-admin__stat">
        <div class="ci-admin__stat-value"><?= $stats['institutions'] ?></div>
        <div class="ci-admin__stat-label">Établissements</div>
    </div>
    <div class="ci-admin__stat">
        <div class="ci-admin__stat-value"><?= $stats['campuses'] ?></div>
        <div class="ci-admin__stat-label">Campus</div>
    </div>
</div>

<!-- Dernières candidatures -->
<div class="ci-admin__table-wrapper">
    <div style="padding: var(--ci-space-lg); border-bottom: 1px solid var(--ci-gray-200); display: flex; align-items: center; justify-content: space-between;">
        <h2 style="font-size: 1rem; font-weight: 700; color: var(--ci-gray-900);">Dernières candidatures</h2>
        <a href="/admin/applications" class="ci-btn ci-btn--ghost ci-btn--sm">Voir tout</a>
    </div>
    
    <?php if (empty($recentApplications)): ?>
        <div class="ci-empty" style="padding: var(--ci-space-xl);">
            <p class="ci-empty__text">Aucune candidature pour le moment.</p>
        </div>
    <?php else: ?>
        <table class="ci-admin__table">
            <thead>
                <tr>
                    <th>Référence</th>
                    <th>Nom</th>
                    <th>Formation</th>
                    <th>Campus</th>
                    <th>Date</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentApplications as $app): ?>
                    <tr>
                        <td>
                            <a href="/admin/applications/show?id=<?= $app['id'] ?>" style="font-weight: 600; color: var(--ci-secondary);">
                                <?= htmlspecialchars($app['reference']) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($app['first_name'] . ' ' . $app['last_name']) ?></td>
                        <td><?= htmlspecialchars($app['program_name']) ?></td>
                        <td><?= htmlspecialchars($app['campus_name']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($app['created_at'])) ?></td>
                        <td>
                            <select class="ci-admin__status-select" data-ci-status="<?= $app['id'] ?>">
                                <option value="new" <?= $app['status'] === 'new' ? 'selected' : '' ?>>Nouveau</option>
                                <option value="processing" <?= $app['status'] === 'processing' ? 'selected' : '' ?>>En cours</option>
                                <option value="accepted" <?= $app['status'] === 'accepted' ? 'selected' : '' ?>>Accepté</option>
                                <option value="rejected" <?= $app['status'] === 'rejected' ? 'selected' : '' ?>>Refusé</option>
                                <option value="archived" <?= $app['status'] === 'archived' ? 'selected' : '' ?>>Archivé</option>
                            </select>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
