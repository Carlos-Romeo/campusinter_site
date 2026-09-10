<!-- Academic Year Form -->
<div class="ci-admin__form">
    <h2 class="ci-admin__form-title">
        <?= $year ? 'Modifier l\'année académique' : 'Ajouter une année académique' ?>
    </h2>

    <form method="post" action="<?= $year ? '/admin/academic-years/edit?id=' . $year['id'] : '/admin/academic-years/create' ?>">
        <?= \CampusInter\Helpers\Csrf::field() ?>

        <div class="ci-form-group" style="margin-bottom: var(--ci-space-md);">
            <label for="name">Nom <span style="color: var(--ci-error);">*</span></label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($year['name'] ?? '') ?>" required placeholder="Ex: 2026-2027">
        </div>

        <div class="ci-form-group" style="margin-bottom: var(--ci-space-md);">
            <label style="display: flex; align-items: center; gap: var(--ci-space-sm); cursor: pointer;">
                <input type="checkbox" name="is_active" value="1" <?= ($year['is_active'] ?? 0) ? 'checked' : '' ?>>
                <span>Année active (les formations de cette année sont proposées au public)</span>
            </label>
        </div>

        <div class="ci-admin__form-actions">
            <a href="/admin/academic-years" class="ci-btn ci-btn--outline">Annuler</a>
            <button type="submit" class="ci-btn ci-btn--primary">
                <?= $year ? 'Enregistrer' : 'Créer' ?>
            </button>
        </div>
    </form>
</div>
