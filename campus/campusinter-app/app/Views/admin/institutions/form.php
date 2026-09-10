<!-- Institution Form -->
<div class="ci-admin__form">
    <h2 class="ci-admin__form-title">
        <?= $institution ? 'Modifier l\'établissement' : 'Ajouter un établissement' ?>
    </h2>

    <form method="post" action="<?= $institution ? '/admin/institutions/edit?id=' . $institution['id'] : '/admin/institutions/create' ?>">
        <?= \CampusInter\Helpers\Csrf::field() ?>

        <div class="ci-form-group" style="margin-bottom: var(--ci-space-md);">
            <label for="name">Nom <span style="color: var(--ci-error);">*</span></label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($institution['name'] ?? '') ?>" required placeholder="Ex: Université de Lomé">
        </div>

        <div class="ci-form-group" style="margin-bottom: var(--ci-space-md);">
            <label for="description">Description</label>
            <textarea id="description" name="description" placeholder="Description de l'établissement..."><?= htmlspecialchars($institution['description'] ?? '') ?></textarea>
        </div>

        <div class="ci-form-group" style="margin-bottom: var(--ci-space-md);">
            <label for="website">Site web</label>
            <input type="url" id="website" name="website" value="<?= htmlspecialchars($institution['website'] ?? '') ?>" placeholder="https://www.exemple.com">
        </div>

        <?php if ($institution): ?>
            <div class="ci-form-group" style="margin-bottom: var(--ci-space-md);">
                <label for="status">Statut</label>
                <select id="status" name="status">
                    <option value="active" <?= ($institution['status'] ?? '') === 'active' ? 'selected' : '' ?>>Actif</option>
                    <option value="inactive" <?= ($institution['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactif</option>
                </select>
            </div>
        <?php endif; ?>

        <div class="ci-admin__form-actions">
            <a href="/admin/institutions" class="ci-btn ci-btn--outline">Annuler</a>
            <button type="submit" class="ci-btn ci-btn--primary">
                <?= $institution ? 'Enregistrer' : 'Créer' ?>
            </button>
        </div>
    </form>
</div>
