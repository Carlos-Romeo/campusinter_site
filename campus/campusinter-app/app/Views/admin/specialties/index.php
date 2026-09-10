<!-- Spécialités -->
<div class="ci-admin__search">
    <form method="get" style="display: flex; gap: var(--ci-space-md); flex: 1; flex-wrap: wrap;">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Rechercher une spécialité..." class="ci-admin__search-input">
        <select name="domain_id" class="ci-admin__search-input" style="max-width: 250px;">
            <option value="">Tous les domaines</option>
            <?php foreach ($domains as $domain): ?>
                <option value="<?= $domain['id'] ?>" <?= $domainId == $domain['id'] ? 'selected' : '' ?>><?= htmlspecialchars($domain['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="ci-btn ci-btn--primary ci-btn--sm">Rechercher</button>
    </form>
    <a href="/admin/specialties/create" class="ci-btn ci-btn--accent">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Ajouter
    </a>
</div>

<div class="ci-admin__table-wrapper">
    <?php if (empty($specialties)): ?>
        <div class="ci-empty">
            <p class="ci-empty__text">Aucune spécialité trouvée.</p>
        </div>
    <?php else: ?>
        <table class="ci-admin__table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Domaine</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($specialties as $specialty): ?>
                    <tr>
                        <td style="font-weight: 600;"><?= htmlspecialchars($specialty['name']) ?></td>
                        <td><?= htmlspecialchars($specialty['domain_name']) ?></td>
                        <td>
                            <span class="ci-badge <?= $specialty['status'] === 'active' ? 'ci-badge--accepted' : 'ci-badge--archived' ?>">
                                <?= $specialty['status'] === 'active' ? 'Actif' : 'Inactif' ?>
                            </span>
                        </td>
                        <td class="ci-admin__table-actions">
                            <a href="/admin/specialties/edit?id=<?= $specialty['id'] ?>" class="ci-btn ci-btn--ghost ci-btn--sm">Modifier</a>
                            <button type="button" class="ci-btn ci-btn--ghost ci-btn--sm" style="color: var(--ci-error);" data-ci-delete="/admin/specialties/delete?id=<?= $specialty['id'] ?>" data-ci-name="<?= htmlspecialchars($specialty['name']) ?>">Supprimer</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
