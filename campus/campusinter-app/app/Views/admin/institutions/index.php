<!-- Établissements -->
<div class="ci-admin__search">
    <form method="get" class="ci-admin__search-input" style="display: flex; gap: var(--ci-space-md); flex: 1;">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Rechercher un établissement...">
        <button type="submit" class="ci-btn ci-btn--primary ci-btn--sm">Rechercher</button>
    </form>
    <a href="/admin/institutions/create" class="ci-btn ci-btn--accent">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Ajouter
    </a>
</div>

<div class="ci-admin__table-wrapper">
    <?php if (empty($institutions)): ?>
        <div class="ci-empty">
            <p class="ci-empty__text">Aucun établissement trouvé.</p>
        </div>
    <?php else: ?>
        <table class="ci-admin__table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Site web</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($institutions as $institution): ?>
                    <tr>
                        <td style="font-weight: 600;"><?= htmlspecialchars($institution['name']) ?></td>
                        <td>
                            <?php if ($institution['website']): ?>
                                <a href="<?= htmlspecialchars($institution['website']) ?>" target="_blank" rel="noopener" style="color: var(--ci-secondary); font-size: 0.8125rem;">
                                    <?= htmlspecialchars($institution['website']) ?>
                                </a>
                            <?php else: ?>
                                <span style="color: var(--ci-gray-400);">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="ci-badge <?= $institution['status'] === 'active' ? 'ci-badge--accepted' : 'ci-badge--archived' ?>">
                                <?= $institution['status'] === 'active' ? 'Actif' : 'Inactif' ?>
                            </span>
                        </td>
                        <td class="ci-admin__table-actions">
                            <a href="/admin/institutions/edit?id=<?= $institution['id'] ?>" class="ci-btn ci-btn--ghost ci-btn--sm">Modifier</a>
                            <button type="button" class="ci-btn ci-btn--ghost ci-btn--sm" style="color: var(--ci-error);" data-ci-delete="/admin/institutions/delete?id=<?= $institution['id'] ?>" data-ci-name="<?= htmlspecialchars($institution['name']) ?>">Supprimer</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
