<!-- Domaines -->
<div class="ci-admin__search">
    <form method="get" class="ci-admin__search-input" style="display: flex; gap: var(--ci-space-md); flex: 1;">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Rechercher un domaine...">
        <button type="submit" class="ci-btn ci-btn--primary ci-btn--sm">Rechercher</button>
    </form>
    <a href="/admin/domains/create" class="ci-btn ci-btn--accent">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Ajouter
    </a>
</div>

<div class="ci-admin__table-wrapper">
    <?php if (empty($domains)): ?>
        <div class="ci-empty">
            <p class="ci-empty__text">Aucun domaine trouvé.</p>
        </div>
    <?php else: ?>
        <table class="ci-admin__table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Slug</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($domains as $domain): ?>
                    <tr>
                        <td style="font-weight: 600;"><?= htmlspecialchars($domain['name']) ?></td>
                        <td><code style="background: var(--ci-gray-100); padding: 2px 6px; border-radius: 4px; font-size: 0.8125rem;"><?= htmlspecialchars($domain['slug']) ?></code></td>
                        <td>
                            <span class="ci-badge <?= $domain['status'] === 'active' ? 'ci-badge--accepted' : 'ci-badge--archived' ?>">
                                <?= $domain['status'] === 'active' ? 'Actif' : 'Inactif' ?>
                            </span>
                        </td>
                        <td class="ci-admin__table-actions">
                            <a href="/admin/domains/edit?id=<?= $domain['id'] ?>" class="ci-btn ci-btn--ghost ci-btn--sm">Modifier</a>
                            <button type="button" class="ci-btn ci-btn--ghost ci-btn--sm" style="color: var(--ci-error);" data-ci-delete="/admin/domains/delete?id=<?= $domain['id'] ?>" data-ci-name="<?= htmlspecialchars($domain['name']) ?>">Supprimer</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
