<!-- Specialty Form -->
<div class="ci-admin__form">
    <h2 class="ci-admin__form-title">
        <?= $specialty ? 'Modifier la spécialité' : 'Ajouter une spécialité' ?>
    </h2>

    <form method="post" action="<?= $specialty ? '/admin/specialties/edit?id=' . $specialty['id'] : '/admin/specialties/create' ?>">
        <?= \CampusInter\Helpers\Csrf::field() ?>

        <div class="ci-form-group" style="margin-bottom: var(--ci-space-md);">
            <label for="domain_id">Domaine <span style="color: var(--ci-error);">*</span></label>
            <select id="domain_id" name="domain_id" required>
                <option value="">Sélectionnez un domaine</option>
                <?php foreach ($domains as $domain): ?>
                    <option value="<?= $domain['id'] ?>" <?= ($specialty['domain_id'] ?? '') == $domain['id'] ? 'selected' : '' ?>><?= htmlspecialchars($domain['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="ci-form-group" style="margin-bottom: var(--ci-space-md);">
            <label for="name">Nom <span style="color: var(--ci-error);">*</span></label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($specialty['name'] ?? '') ?>" required placeholder="Ex: Génie Logiciel">
        </div>

        <?php if ($specialty): ?>
            <div class="ci-form-group" style="margin-bottom: var(--ci-space-md);">
                <label for="status">Statut</label>
                <select id="status" name="status">
                    <option value="active" <?= ($specialty['status'] ?? '') === 'active' ? 'selected' : '' ?>>Actif</option>
                    <option value="inactive" <?= ($specialty['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactif</option>
                </select>
            </div>
        <?php endif; ?>

        <div class="ci-admin__form-actions">
            <a href="/admin/specialties" class="ci-btn ci-btn--outline">Annuler</a>
            <button type="submit" class="ci-btn ci-btn--primary">
                <?= $specialty ? 'Enregistrer' : 'Créer' ?>
            </button>
        </div>
    </form>
</div>
