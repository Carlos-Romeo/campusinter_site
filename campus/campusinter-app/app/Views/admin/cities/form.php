<!-- City Form -->
<div class="ci-admin__form">
    <h2 class="ci-admin__form-title">
        <?= $city ? 'Modifier la ville' : 'Ajouter une ville' ?>
    </h2>

    <form method="post" action="<?= $city ? '/admin/cities/edit?id=' . $city['id'] : '/admin/cities/create' ?>">
        <?= \CampusInter\Helpers\Csrf::field() ?>

        <div class="ci-form-group" style="margin-bottom: var(--ci-space-md);">
            <label for="name">Nom <span style="color: var(--ci-error);">*</span></label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($city['name'] ?? '') ?>" required placeholder="Ex: Lomé">
        </div>

        <div class="ci-form-group" style="margin-bottom: var(--ci-space-md);">
            <label for="country">Pays</label>
            <input type="text" id="country" name="country" value="<?= htmlspecialchars($city['country'] ?? 'Togo') ?>" placeholder="Ex: Togo">
        </div>

        <?php if ($city): ?>
            <div class="ci-form-group" style="margin-bottom: var(--ci-space-md);">
                <label for="status">Statut</label>
                <select id="status" name="status">
                    <option value="active" <?= ($city['status'] ?? '') === 'active' ? 'selected' : '' ?>>Actif</option>
                    <option value="inactive" <?= ($city['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactif</option>
                </select>
            </div>
        <?php endif; ?>

        <div class="ci-admin__form-actions">
            <a href="/admin/cities" class="ci-btn ci-btn--outline">Annuler</a>
            <button type="submit" class="ci-btn ci-btn--primary">
                <?= $city ? 'Enregistrer' : 'Créer' ?>
            </button>
        </div>
    </form>
</div>
