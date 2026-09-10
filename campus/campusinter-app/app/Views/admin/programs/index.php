<!-- Formations -->
<div class="ci-admin__search">
    <form method="get" style="display: flex; gap: var(--ci-space-md); flex: 1; flex-wrap: wrap;">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Rechercher une formation..." class="ci-admin__search-input">
        <select name="domain_id" class="ci-admin__search-input" style="max-width: 200px;">
            <option value="">Tous les domaines</option>
            <?php foreach ($domains as $domain): ?>
                <option value="<?= $domain['id'] ?>" <?= $domainId == $domain['id'] ? 'selected' : '' ?>><?= htmlspecialchars($domain['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <select name="level" class="ci-admin__search-input" style="max-width: 150px;">
            <option value="">Tous les niveaux</option>
            <option value="Bac" <?= $level === 'Bac' ? 'selected' : '' ?>>Bac</option>
            <option value="Bac+1" <?= $level === 'Bac+1' ? 'selected' : '' ?>>Bac+1</option>
            <option value="Bac+2" <?= $level === 'Bac+2' ? 'selected' : '' ?>>Bac+2</option>
            <option value="Bac+3" <?= $level === 'Bac+3' ? 'selected' : '' ?>>Bac+3</option>
            <option value="Bac+4" <?= $level === 'Bac+4' ? 'selected' : '' ?>>Bac+4</option>
            <option value="Bac+5" <?= $level === 'Bac+5' ? 'selected' : '' ?>>Bac+5</option>
            <option value="Doctorat" <?= $level === 'Doctorat' ? 'selected' : '' ?>>Doctorat</option>
        </select>
        <button type="submit" class="ci-btn ci-btn--primary ci-btn--sm">Rechercher</button>
    </form>
    <a href="/admin/programs/create" class="ci-btn ci-btn--accent">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Ajouter
    </a>
</div>

<div class="ci-admin__table-wrapper">
    <?php if (empty($programs)): ?>
        <div class="ci-empty">
            <p class="ci-empty__text">Aucune formation trouvée.</p>
        </div>
    <?php else: ?>
        <table class="ci-admin__table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Niveau</th>
                    <th>Domaine</th>
                    <th>Spécialité</th>
                    <th>Année</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($programs as $program): ?>
                    <tr>
                        <td style="font-weight: 600;"><?= htmlspecialchars($program['name']) ?></td>
                        <td><span class="ci-badge ci-badge--level"><?= htmlspecialchars($program['level']) ?></span></td>
                        <td><?= htmlspecialchars($program['domain_name']) ?></td>
                        <td><?= htmlspecialchars($program['specialty_name'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($program['academic_year_name']) ?></td>
                        <td>
                            <span class="ci-badge <?= $program['status'] === 'active' ? 'ci-badge--accepted' : 'ci-badge--archived' ?>">
                                <?= $program['status'] === 'active' ? 'Actif' : 'Inactif' ?>
                            </span>
                        </td>
                        <td class="ci-admin__table-actions">
                            <a href="/admin/programs/edit?id=<?= $program['id'] ?>" class="ci-btn ci-btn--ghost ci-btn--sm">Modifier</a>
                            <button type="button" class="ci-btn ci-btn--ghost ci-btn--sm" style="color: var(--ci-error);" data-ci-delete="/admin/programs/delete?id=<?= $program['id'] ?>" data-ci-name="<?= htmlspecialchars($program['name']) ?>">Supprimer</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
