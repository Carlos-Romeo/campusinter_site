<!-- Villes -->
<div class="ci-admin__search">
    <form method="get" class="ci-admin__search-input" style="display: flex; gap: var(--ci-space-md); flex: 1;">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Rechercher une ville...">
        <button type="submit" class="ci-btn ci-btn--primary ci-btn--sm">Rechercher</button>
    </form>
    <a href="/admin/cities/create" class="ci-btn ci-btn--accent">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Ajouter
    </a>
</div>

<div class="ci-admin__table-wrapper">
    <?php if (empty($cities)): ?>
        <div class="ci-empty">
            <p class="ci-empty__text">Aucune ville trouvée.</p>
        </div>
    <?php else: ?>
        <table class="ci-admin__table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Pays</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cities as $city): ?>
                    <tr>
                        <td style="font-weight: 600;"><?= htmlspecialchars($city['name']) ?></td>
                        <td><?= htmlspecialchars($city['country']) ?></td>
                        <td>
                            <span class="ci-badge <?= $city['status'] === 'active' ? 'ci-badge--accepted' : 'ci-badge--archived' ?>">
                                <?= $city['status'] === 'active' ? 'Actif' : 'Inactif' ?>
                            </span>
                        </td>
                        <td class="ci-admin__table-actions">
                            <a href="/admin/cities/edit?id=<?= $city['id'] ?>" class="ci-btn ci-btn--ghost ci-btn--sm">Modifier</a>
                            <button type="button" class="ci-btn ci-btn--ghost ci-btn--sm" style="color: var(--ci-error);" data-ci-delete="/admin/cities/delete?id=<?= $city['id'] ?>" data-ci-name="<?= htmlspecialchars($city['name']) ?>">Supprimer</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
