<!-- Campus Form -->
<div class="ci-admin__form">
    <h2 class="ci-admin__form-title">
        <?= $campus ? 'Modifier le campus' : 'Ajouter un campus' ?>
    </h2>

    <form method="post" action="<?= $campus ? '/admin/campuses/edit?id=' . $campus['id'] : '/admin/campuses/create' ?>">
        <?= \CampusInter\Helpers\Csrf::field() ?>

        <div class="ci-admin__form-row ci-admin__form-row--2">
            <div class="ci-form-group">
                <label for="institution_id">Institution <span style="color: var(--ci-error);">*</span></label>
                <select id="institution_id" name="institution_id" required>
                    <option value="">Sélectionnez une institution</option>
                    <?php foreach ($institutions as $institution): ?>
                        <option value="<?= $institution['id'] ?>" <?= ($campus['institution_id'] ?? '') == $institution['id'] ? 'selected' : '' ?>><?= htmlspecialchars($institution['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="ci-form-group">
                <label for="city_id">Ville <span style="color: var(--ci-error);">*</span></label>
                <select id="city_id" name="city_id" required>
                    <option value="">Sélectionnez une ville</option>
                    <?php foreach ($cities as $city): ?>
                        <option value="<?= $city['id'] ?>" <?= ($campus['city_id'] ?? '') == $city['id'] ? 'selected' : '' ?>><?= htmlspecialchars($city['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="ci-form-group" style="margin-bottom: var(--ci-space-md);">
            <label for="name">Nom <span style="color: var(--ci-error);">*</span></label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($campus['name'] ?? '') ?>" required placeholder="Ex: Campus Centre">
        </div>

        <div class="ci-form-group" style="margin-bottom: var(--ci-space-md);">
            <label for="address">Adresse</label>
            <input type="text" id="address" name="address" value="<?= htmlspecialchars($campus['address'] ?? '') ?>" placeholder="Ex: Boulevard du 13 Januar, Lomé">
        </div>

        <?php if ($campus): ?>
            <div class="ci-form-group" style="margin-bottom: var(--ci-space-md);">
                <label for="status">Statut</label>
                <select id="status" name="status">
                    <option value="active" <?= ($campus['status'] ?? '') === 'active' ? 'selected' : '' ?>>Actif</option>
                    <option value="inactive" <?= ($campus['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactif</option>
                </select>
            </div>
        <?php endif; ?>

        <div class="ci-admin__form-actions">
            <a href="/admin/campuses" class="ci-btn ci-btn--outline">Annuler</a>
            <button type="submit" class="ci-btn ci-btn--primary">
                <?= $campus ? 'Enregistrer' : 'Créer' ?>
            </button>
        </div>
    </form>
</div>
