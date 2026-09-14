<?php
/**
 * Campus Inter Admin - Détail d'un paiement
 */
?>

<div class="ci-admin-header">
    <div>
        <a href="/admin/payments" class="ci-admin-back">← Retour à la liste</a>
        <h1>Détail du paiement</h1>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: var(--ci-space-lg);">
    <!-- Informations de paiement -->
    <div>
        <div class="ci-admin-card" style="margin-bottom: var(--ci-space-lg);">
            <h2 style="font-size: 1.125rem; font-weight: 600; margin-bottom: var(--ci-space-md);">Informations de paiement</h2>
            
            <div class="ci-detail-grid">
                <div class="ci-detail-item">
                    <span class="ci-detail-label">Référence transaction</span>
                    <span class="ci-detail-value" style="font-family: monospace;">
                        <?php echo htmlspecialchars($payment['transaction_reference'] ?? 'N/A'); ?>
                    </span>
                </div>
                <div class="ci-detail-item">
                    <span class="ci-detail-label">Montant</span>
                    <span class="ci-detail-value ci-detail-value--large">
                        <?php echo number_format($payment['amount'], 0, ',', ' '); ?> <?php echo $payment['currency']; ?>
                    </span>
                </div>
                <div class="ci-detail-item">
                    <span class="ci-detail-label">Méthode de paiement</span>
                    <span class="ci-detail-value">
                        <?php if ($payment['provider']): ?>
                            <span class="ci-badge ci-badge--<?php echo $payment['provider'] === 'orange_money' ? 'orange' : 'yellow'; ?>">
                                <?php echo $payment['provider'] === 'orange_money' ? 'Orange Money' : 'MTN Mobile Money'; ?>
                            </span>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </span>
                </div>
                <div class="ci-detail-item">
                    <span class="ci-detail-label">Statut</span>
                    <span class="ci-detail-value">
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
                    </span>
                </div>
                <div class="ci-detail-item">
                    <span class="ci-detail-label">Date de création</span>
                    <span class="ci-detail-value"><?php echo date('d/m/Y à H:i', strtotime($payment['created_at'])); ?></span>
                </div>
                <?php if ($payment['paid_at']): ?>
                <div class="ci-detail-item">
                    <span class="ci-detail-label">Date de paiement</span>
                    <span class="ci-detail-value"><?php echo date('d/m/Y à H:i', strtotime($payment['paid_at'])); ?></span>
                </div>
                <?php endif; ?>
            </div>

            <!-- Actions -->
            <div style="margin-top: var(--ci-space-lg); padding-top: var(--ci-space-lg); border-top: 1px solid var(--ci-gray-200);">
                <h3 style="font-size: 0.875rem; font-weight: 600; margin-bottom: var(--ci-space-sm);">Changer le statut</h3>
                <div style="display: flex; gap: var(--ci-space-sm); flex-wrap: wrap;">
                    <?php if ($payment['status'] !== 'paid'): ?>
                        <button onclick="updatePaymentStatus(<?php echo $payment['id']; ?>, 'paid')" class="ci-btn ci-btn--success ci-btn--sm">
                            Marquer comme payé
                        </button>
                    <?php endif; ?>
                    <?php if ($payment['status'] !== 'failed'): ?>
                        <button onclick="updatePaymentStatus(<?php echo $payment['id']; ?>, 'failed')" class="ci-btn ci-btn--danger ci-btn--sm">
                            Marquer comme échoué
                        </button>
                    <?php endif; ?>
                    <?php if ($payment['status'] !== 'cancelled'): ?>
                        <button onclick="updatePaymentStatus(<?php echo $payment['id']; ?>, 'cancelled')" class="ci-btn ci-btn--outline ci-btn--sm">
                            Annuler
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Documents -->
        <?php if (!empty($documents)): ?>
        <div class="ci-admin-card">
            <h2 style="font-size: 1.125rem; font-weight: 600; margin-bottom: var(--ci-space-md);">Documents du candidat</h2>
            
            <div class="ci-documents-list">
                <?php 
                $docLabels = [
                    'diploma' => 'Relevé de notes',
                    'bac' => 'Attestation BAC',
                    'cv' => 'CV',
                ];
                ?>
                <?php foreach ($documents as $doc): ?>
                    <div class="ci-document-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                        </svg>
                        <div class="ci-document-info">
                            <span class="ci-document-name"><?php echo $docLabels[$doc['document_type']] ?? $doc['document_type']; ?></span>
                            <span class="ci-document-file"><?php echo htmlspecialchars($doc['file_name']); ?></span>
                        </div>
                        <a href="/storage/uploads/<?php echo htmlspecialchars($doc['file_path']); ?>" target="_blank" class="ci-btn ci-btn--sm ci-btn--outline">
                            Voir
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar: Candidature -->
    <div>
        <div class="ci-admin-card" style="margin-bottom: var(--ci-space-lg);">
            <h2 style="font-size: 1.125rem; font-weight: 600; margin-bottom: var(--ci-space-md);">Candidature</h2>
            
            <div class="ci-detail-grid">
                <div class="ci-detail-item">
                    <span class="ci-detail-label">Référence</span>
                    <span class="ci-detail-value">
                        <a href="/admin/applications/show?id=<?php echo $payment['application_id']; ?>" class="ci-link">
                            <?php echo htmlspecialchars($payment['reference']); ?>
                        </a>
                    </span>
                </div>
                <div class="ci-detail-item">
                    <span class="ci-detail-label">Formation</span>
                    <span class="ci-detail-value"><?php echo htmlspecialchars($payment['program_name']); ?></span>
                </div>
                <div class="ci-detail-item">
                    <span class="ci-detail-label">Niveau</span>
                    <span class="ci-detail-value"><?php echo htmlspecialchars($payment['level']); ?></span>
                </div>
                <?php if (!empty($payment['campus_name'])): ?>
                <div class="ci-detail-item">
                    <span class="ci-detail-label">Campus</span>
                    <span class="ci-detail-value"><?php echo htmlspecialchars($payment['campus_name']); ?></span>
                </div>
                <?php endif; ?>
                <?php if (!empty($payment['institution_name'])): ?>
                <div class="ci-detail-item">
                    <span class="ci-detail-label">Établissement</span>
                    <span class="ci-detail-value"><?php echo htmlspecialchars($payment['institution_name']); ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="ci-admin-card">
            <h2 style="font-size: 1.125rem; font-weight: 600; margin-bottom: var(--ci-space-md);">Candidat</h2>
            
            <div class="ci-detail-grid">
                <div class="ci-detail-item">
                    <span class="ci-detail-label">Nom complet</span>
                    <span class="ci-detail-value"><?php echo htmlspecialchars($payment['last_name'] . ' ' . $payment['first_name']); ?></span>
                </div>
                <div class="ci-detail-item">
                    <span class="ci-detail-label">Email</span>
                    <span class="ci-detail-value">
                        <a href="mailto:<?php echo htmlspecialchars($payment['email']); ?>" class="ci-link">
                            <?php echo htmlspecialchars($payment['email']); ?>
                        </a>
                    </span>
                </div>
                <?php if (!empty($payment['phone'])): ?>
                <div class="ci-detail-item">
                    <span class="ci-detail-label">Téléphone</span>
                    <span class="ci-detail-value">
                        <a href="tel:<?php echo htmlspecialchars($payment['phone']); ?>" class="ci-link">
                            <?php echo htmlspecialchars($payment['phone']); ?>
                        </a>
                    </span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
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

.ci-detail-grid {
    display: grid;
    gap: var(--ci-space-md);
}

.ci-detail-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.ci-detail-label {
    font-size: 0.75rem;
    color: var(--ci-gray-500);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.ci-detail-value {
    font-weight: 500;
}

.ci-detail-value--large {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--ci-primary);
}

.ci-documents-list {
    display: flex;
    flex-direction: column;
    gap: var(--ci-space-sm);
}

.ci-document-item {
    display: flex;
    align-items: center;
    gap: var(--ci-space-sm);
    padding: var(--ci-space-sm);
    background: var(--ci-gray-50);
    border-radius: 6px;
}

.ci-document-info {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.ci-document-name {
    font-weight: 500;
    font-size: 0.875rem;
}

.ci-document-file {
    font-size: 0.75rem;
    color: var(--ci-gray-500);
}

.ci-admin-back {
    display: inline-block;
    margin-bottom: var(--ci-space-sm);
    color: var(--ci-gray-500);
    text-decoration: none;
    font-size: 0.875rem;
}

.ci-admin-back:hover {
    color: var(--ci-primary);
}
</style>

<script>
async function updatePaymentStatus(id, status) {
    if (!confirm('Êtes-vous sûr de vouloir changer le statut de ce paiement ?')) {
        return;
    }

    try {
        const formData = new FormData();
        formData.append('id', id);
        formData.append('status', status);

        const response = await fetch('/admin/payments/status', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            window.location.reload();
        } else {
            alert(result.message || 'Erreur lors de la mise à jour');
        }
    } catch (error) {
        alert('Erreur de connexion');
    }
}
</script>
