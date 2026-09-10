<!-- Domain Form -->
<div class="ci-admin__form">
    <h2 class="ci-admin__form-title">
        <?= $domain ? 'Modifier le domaine' : 'Ajouter un domaine' ?>
    </h2>

    <form method="post" action="<?= $domain ? '/admin/domains/edit?id=' . $domain['id'] : '/admin/domains/create' ?>">
        <?= \CampusInter\Helpers\Csrf::field() ?>

        <div class="ci-form-group" style="margin-bottom: var(--ci-space-md);">
            <label for="name">Nom <span style="color: var(--ci-error);">*</span></label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($domain['name'] ?? '') ?>" required placeholder="Ex: Informatique">
        </div>

        <?php if ($domain): ?>
            <div class="ci-form-group" style="margin-bottom: var(--ci-space-md);">
                <label for="status">Statut</label>
                <select id="status" name="status">
                    <option value="active" <?= ($domain['status'] ?? '') === 'active' ? 'selected' : '' ?>>Actif</option>
                    <option value="inactive" <?= ($domain['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactif</option>
                </select>
            </div>
        <?php endif; ?>

        <div class="ci-admin__form-actions">
            <a href="/admin/domains" class="ci-btn ci-btn--outline">Annuler</a>
            <button type="submit" class="ci-btn ci-btn--primary">
                <?= $domain ? 'Enregistrer' : 'Créer' ?>
            </button>
        </div>
    </form>
</div>
