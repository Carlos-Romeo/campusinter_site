<?php
/**
 * Campus Inter Admin - Liste des paiements
 */
?>

<div class="ci-admin-header">
    <h1><?php echo $title; ?></h1>
    <p class="ci-admin-subtitle">Gestion des frais de dossier</p>
</div>

<!-- Statistiques -->
<div class="ci-admin-stats">
    <div class="ci-stat-card">
        <span class="ci-stat-value"><?php echo number_format($total); ?></span>
        <span class="ci-stat-label">Total paiements</span>
    </div>
</div>

<!-- Filtres -->
<div class="ci-admin-card" style="margin-bottom: var(--ci-space-lg);">
    <form method="get" class="ci-admin-filters">
        <div class="ci-filter-group">
            <input type="text" name="search" placeholder="Rechercher (référence, nom, transaction...)" value="<?php echo htmlspecialchars($search); ?>" class="ci-input">
        </div>
        <div class="ci-filter-group">
            <select name="status" class="ci-select">
                <option value="">Tous les statuts</option>
                <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>En attente</option>
                <option value="paid" <?php echo $status === 'paid' ? 'selected' : ''; ?>>Payé</option>
                <option value="failed" <?php echo $status === 'failed' ? 'selected' : ''; ?>>Échoué</option>
                <option value="cancelled" <?php echo $status === 'cancelled' ? 'selected' : ''; ?>>Annulé</option>
            </select>
        </div>
        <button type="submit" class="ci-btn ci-btn--primary">Filtrer</button>
        <?php if ($search || $status): ?>
            <a href="/admin/payments" class="ci-btn ci-btn--outline">Réinitialiser</a>
        <?php endif; ?>
    </form>
</div>

<!-- Liste -->
<div class="ci-admin-card">
    <?php if (empty($payments)): ?>
        <div class="ci-admin-empty">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                <line x1="1" y1="10" x2="23" y2="10"></line>
            </svg>
            <p>Aucun paiement trouvé</p>
        </div>
    <?php else: ?>
        <div class="ci-admin-table-wrapper">
            <table class="ci-admin-table">
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Candidat</th>
                        <th>Formation</th>
                        <th>Montant</th>
                        <th>Méthode</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $payment): ?>
                        <tr>
                            <td>
                                <a href="/admin/applications/show?id=<?php echo $payment['application_id']; ?>" class="ci-link">
                                    <?php echo htmlspecialchars($payment['reference']); ?>
                                </a>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($payment['last_name'] . ' ' . $payment['first_name']); ?>
                                <br><small style="color: var(--ci-gray-500);"><?php echo htmlspecialchars($payment['email']); ?></small>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($payment['program_name']); ?>
                                <br><small style="color: var(--ci-gray-500);"><?php echo htmlspecialchars($payment['level']); ?></small>
                            </td>
                            <td>
                                <strong><?php echo number_format($payment['amount'], 0, ',', ' '); ?> <?php echo $payment['currency']; ?></strong>
                            </td>
                            <td>
                                <?php if ($payment['provider']): ?>
                                    <span class="ci-badge ci-badge--<?php echo $payment['provider'] === 'orange_money' ? 'orange' : 'yellow'; ?>">
                                        <?php echo $payment['provider'] === 'orange_money' ? 'Orange Money' : 'MTN Mobile'; ?>
                                    </span>
                                <?php else: ?>
                                    <span style="color: var(--ci-gray-400);">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $statusClasses = [
                                    'pending' => 'warning',
                                    'paid' => 'success',
                                    'failed' => 'error',
                                    'cancelled' => 'gray',
                                ];
                                $statusLabels = [
                                    'pending' => 'En attente',
                                    'paid' => 'Payé',
                                    'failed' => 'Échoué',
                                    'cancelled' => 'Annulé',
                                ];
                                ?>
                                <span class="ci-badge ci-badge--<?php echo $statusClasses[$payment['status']] ?? 'gray'; ?>">
                                    <?php echo $statusLabels[$payment['status']] ?? $payment['status']; ?>
                                </span>
                            </td>
                            <td>
                                <?php echo date('d/m/Y H:i', strtotime($payment['created_at'])); ?>
                            </td>
                            <td>
                                <a href="/admin/payments/show?id=<?php echo $payment['id']; ?>" class="ci-btn ci-btn--sm ci-btn--outline" title="Voir">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="ci-admin-pagination">
                <span class="ci-pagination-info">
                    Page <?php echo $page; ?> sur <?php echo $totalPages; ?> (<?php echo $total; ?> résultats)
                </span>
                <div class="ci-pagination-links">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>" class="ci-btn ci-btn--sm ci-btn--outline">← Précédent</a>
                    <?php endif; ?>
                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>" class="ci-btn ci-btn--sm ci-btn--outline">Suivant →</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<style>
.ci-badge--orange {
    background: #FFF3E0;
    color: #E65100;
}

.ci-badge--yellow {
    background: #FFFDE7;
    color: #F57F17;
}

.ci-badge--gray {
    background: #F5F5F5;
    color: #616161;
}
</style>
