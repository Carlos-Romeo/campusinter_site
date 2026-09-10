<!-- Application Detail -->
<div class="ci-admin__detail">
    <div class="ci-admin__detail-header">
        <div>
            <h2 class="ci-admin__detail-title"><?= htmlspecialchars($application['reference']) ?></h2>
            <p style="font-size: 0.875rem; color: var(--ci-gray-500); margin-top: 4px;">
                Candidature du <?= date('d/m/Y à H:i', strtotime($application['created_at'])) ?>
            </p>
        </div>
        <div style="display: flex; gap: var(--ci-space-md); align-items: center;">
            <select class="ci-admin__status-select" data-ci-status="<?= $application['id'] ?>" style="padding: 8px 12px; font-size: 0.875rem;">
                <option value="new" <?= $application['status'] === 'new' ? 'selected' : '' ?>>Nouveau</option>
                <option value="processing" <?= $application['status'] === 'processing' ? 'selected' : '' ?>>En cours</option>
                <option value="accepted" <?= $application['status'] === 'accepted' ? 'selected' : '' ?>>Accepté</option>
                <option value="rejected" <?= $application['status'] === 'rejected' ? 'selected' : '' ?>>Refusé</option>
                <option value="archived" <?= $application['status'] === 'archived' ? 'selected' : '' ?>>Archivé</option>
            </select>
            <a href="/admin/applications" class="ci-btn ci-btn--outline ci-btn--sm">Retour</a>
        </div>
    </div>

    <div class="ci-admin__detail-body">
        <!-- Formation -->
        <div class="ci-admin__detail-section">
            <h3 class="ci-admin__detail-section-title">Formation</h3>
            <div class="ci-admin__detail-grid">
                <div class="ci-admin__detail-item">
                    <span class="ci-admin__detail-label">Formation</span>
                    <span class="ci-admin__detail-value" style="font-weight: 600;"><?= htmlspecialchars($application['program_name']) ?></span>
                </div>
                <div class="ci-admin__detail-item">
                    <span class="ci-admin__detail-label">Niveau</span>
                    <span class="ci-admin__detail-value"><?= htmlspecialchars($application['program_level']) ?></span>
                </div>
                <div class="ci-admin__detail-item">
                    <span class="ci-admin__detail-label">Année académique</span>
                    <span class="ci-admin__detail-value"><?= htmlspecialchars($application['academic_year_name']) ?></span>
                </div>
                <div class="ci-admin__detail-item">
                    <span class="ci-admin__detail-label">Durée</span>
                    <span class="ci-admin__detail-value"><?= htmlspecialchars($application['program_description'] ?? '—') ?></span>
                </div>
            </div>
        </div>

        <!-- Établissement -->
        <div class="ci-admin__detail-section">
            <h3 class="ci-admin__detail-section-title">Établissement & Campus</h3>
            <div class="ci-admin__detail-grid">
                <div class="ci-admin__detail-item">
                    <span class="ci-admin__detail-label">Institution</span>
                    <span class="ci-admin__detail-value"><?= htmlspecialchars($application['institution_name']) ?></span>
                </div>
                <div class="ci-admin__detail-item">
                    <span class="ci-admin__detail-label">Campus</span>
                    <span class="ci-admin__detail-value"><?= htmlspecialchars($application['campus_name']) ?></span>
                </div>
                <div class="ci-admin__detail-item">
                    <span class="ci-admin__detail-label">Ville</span>
                    <span class="ci-admin__detail-value"><?= htmlspecialchars($application['city_name']) ?></span>
                </div>
                <div class="ci-admin__detail-item">
                    <span class="ci-admin__detail-label">Adresse</span>
                    <span class="ci-admin__detail-value"><?= htmlspecialchars($application['campus_address'] ?? '—') ?></span>
                </div>
            </div>
        </div>

        <!-- Candidat -->
        <div class="ci-admin__detail-section">
            <h3 class="ci-admin__detail-section-title">Candidat</h3>
            <div class="ci-admin__detail-grid">
                <div class="ci-admin__detail-item">
                    <span class="ci-admin__detail-label">Nom</span>
                    <span class="ci-admin__detail-value"><?= htmlspecialchars($application['last_name']) ?></span>
                </div>
                <div class="ci-admin__detail-item">
                    <span class="ci-admin__detail-label">Prénom</span>
                    <span class="ci-admin__detail-value"><?= htmlspecialchars($application['first_name']) ?></span>
                </div>
                <div class="ci-admin__detail-item">
                    <span class="ci-admin__detail-label">Date de naissance</span>
                    <span class="ci-admin__detail-value"><?= $application['birth_date'] ? date('d/m/Y', strtotime($application['birth_date'])) : '—' ?></span>
                </div>
                <div class="ci-admin__detail-item">
                    <span class="ci-admin__detail-label">Email</span>
                    <span class="ci-admin__detail-value">
                        <a href="mailto:<?= htmlspecialchars($application['email']) ?>" style="color: var(--ci-secondary);">
                            <?= htmlspecialchars($application['email']) ?>
                        </a>
                    </span>
                </div>
                <div class="ci-admin__detail-item">
                    <span class="ci-admin__detail-label">Téléphone</span>
                    <span class="ci-admin__detail-value">
                        <?= $application['phone'] ? '<a href="tel:' . htmlspecialchars($application['phone']) . '" style="color: var(--ci-secondary);">' . htmlspecialchars($application['phone']) . '</a>' : '—' ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Parcours -->
        <div class="ci-admin__detail-section">
            <h3 class="ci-admin__detail-section-title">Parcours scolaire</h3>
            <div class="ci-admin__detail-grid">
                <div class="ci-admin__detail-item">
                    <span class="ci-admin__detail-label">Dernier diplôme</span>
                    <span class="ci-admin__detail-value"><?= htmlspecialchars($application['last_diploma'] ?? '—') ?></span>
                </div>
                <div class="ci-admin__detail-item">
                    <span class="ci-admin__detail-label">Établissement</span>
                    <span class="ci-admin__detail-value"><?= htmlspecialchars($application['last_diploma_institution'] ?? '—') ?></span>
                </div>
                <div class="ci-admin__detail-item">
                    <span class="ci-admin__detail-label">Année du diplôme</span>
                    <span class="ci-admin__detail-value"><?= htmlspecialchars($application['last_diploma_year'] ?? '—') ?></span>
                </div>
                <div class="ci-admin__detail-item">
                    <span class="ci-admin__detail-label">Moyenne du diplôme</span>
                    <span class="ci-admin__detail-value"><?= $application['last_diploma_average'] ? $application['last_diploma_average'] . '/20' : '—' ?></span>
                </div>
            </div>
        </div>

        <!-- Baccalauréat -->
        <div class="ci-admin__detail-section">
            <h3 class="ci-admin__detail-section-title">Baccalauréat</h3>
            <div class="ci-admin__detail-grid">
                <div class="ci-admin__detail-item">
                    <span class="ci-admin__detail-label">Année</span>
                    <span class="ci-admin__detail-value"><?= htmlspecialchars($application['bac_year'] ?? '—') ?></span>
                </div>
                <div class="ci-admin__detail-item">
                    <span class="ci-admin__detail-label">Série</span>
                    <span class="ci-admin__detail-value"><?= htmlspecialchars($application['bac_series'] ?? '—') ?></span>
                </div>
                <div class="ci-admin__detail-item">
                    <span class="ci-admin__detail-label">Moyenne</span>
                    <span class="ci-admin__detail-value"><?= $application['bac_average'] ? $application['bac_average'] . '/20' : '—' ?></span>
                </div>
            </div>
        </div>

        <?php if (!empty($application['message'])): ?>
            <!-- Message -->
            <div class="ci-admin__detail-section">
                <h3 class="ci-admin__detail-section-title">Message du candidat</h3>
                <p style="background: var(--ci-gray-50); padding: var(--ci-space-md); border-radius: var(--ci-radius-md); color: var(--ci-gray-700); line-height: 1.6;">
                    <?= nl2br(htmlspecialchars($application['message'])) ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>
