<!-- Campus -->
<div class="ci-admin__search">
    <form method="get" class="ci-admin__search-input" style="display: flex; gap: var(--ci-space-md); flex: 1;">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Rechercher un campus...">
        <button type="submit" class="ci-btn ci-btn--primary ci-btn--sm">Rechercher</button>
    </form>
    <a href="/admin/campuses/create" class="ci-btn ci-btn--accent">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Ajouter
    </a>
</div>

<div class="ci-admin__table-wrapper">
    <?php if (empty($campuses)): ?>
        <div class="ci-empty">
            <p class="ci-empty__text">Aucun campus trouvé.</p>
        </div>
    <?php else: ?>
        <table class="ci-admin__table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Institution</th>
                    <th>Ville</th>
                    <th>Adresse</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($campuses as $campus): ?>
                    <tr>
                        <td style="font-weight: 600;"><?= htmlspecialchars($campus['name']) ?></td>
                        <td><?= htmlspecialchars($campus['institution_name']) ?></td>
                        <td><?= htmlspecialchars($campus['city_name']) ?></td>
                        <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            <?= htmlspecialchars($campus['address'] ?? '—') ?>
                        </td>
                        <td>
                            <span class="ci-badge <?= $campus['status'] === 'active' ? 'ci-badge--accepted' : 'ci-badge--archived' ?>">
                                <?= $campus['status'] === 'active' ? 'Actif' : 'Inactif' ?>
                            </span>
                        </td>
                        <td class="ci-admin__table-actions">
                            <a href="/admin/campuses/edit?id=<?= $campus['id'] ?>" class="ci-btn ci-btn--ghost ci-btn--sm">Modifier</a>
                            <button type="button" class="ci-btn ci-btn--ghost ci-btn--sm" style="color: var(--ci-error);" data-ci-delete="/admin/campuses/delete?id=<?= $campus['id'] ?>" data-ci-name="<?= htmlspecialchars($campus['name']) ?>">Supprimer</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
