<!-- Années académiques -->
<div style="margin-bottom: var(--ci-space-lg); display: flex; justify-content: flex-end;">
    <a href="/admin/academic-years/create" class="ci-btn ci-btn--accent">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Ajouter
    </a>
</div>

<div class="ci-admin__table-wrapper">
    <?php if (empty($years)): ?>
        <div class="ci-empty">
            <p class="ci-empty__text">Aucune année académique.</p>
        </div>
    <?php else: ?>
        <table class="ci-admin__table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Année active</th>
                    <th>Créé le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($years as $year): ?>
                    <tr>
                        <td style="font-weight: 600;"><?= htmlspecialchars($year['name']) ?></td>
                        <td>
                            <?php if ($year['is_active']): ?>
                                <span class="ci-badge ci-badge--accepted">Active</span>
                            <?php else: ?>
                                <span class="ci-badge ci-badge--archived">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td><?= date('d/m/Y', strtotime($year['created_at'])) ?></td>
                        <td class="ci-admin__table-actions">
                            <a href="/admin/academic-years/edit?id=<?= $year['id'] ?>" class="ci-btn ci-btn--ghost ci-btn--sm">Modifier</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
