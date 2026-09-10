<!-- Program Form -->
<div class="ci-admin__form">
    <h2 class="ci-admin__form-title">
        <?= $program ? 'Modifier la formation' : 'Ajouter une formation' ?>
    </h2>

    <form method="post" action="<?= $program ? '/admin/programs/edit?id=' . $program['id'] : '/admin/programs/create' ?>">
        <?= \CampusInter\Helpers\Csrf::field() ?>

        <div class="ci-admin__form-row ci-admin__form-row--3">
            <div class="ci-form-group">
                <label for="academic_year_id">Année académique <span style="color: var(--ci-error);">*</span></label>
                <select id="academic_year_id" name="academic_year_id" required>
                    <option value="">Sélectionnez</option>
                    <?php foreach ($academicYears as $year): ?>
                        <option value="<?= $year['id'] ?>" <?= ($program['academic_year_id'] ?? '') == $year['id'] ? 'selected' : '' ?>><?= htmlspecialchars($year['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="ci-form-group">
                <label for="domain_id">Domaine <span style="color: var(--ci-error);">*</span></label>
                <select id="domain_id" name="domain_id" required>
                    <option value="">Sélectionnez</option>
                    <?php foreach ($domains as $domain): ?>
                        <option value="<?= $domain['id'] ?>" <?= ($program['domain_id'] ?? '') == $domain['id'] ? 'selected' : '' ?>><?= htmlspecialchars($domain['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="ci-form-group">
                <label for="specialty_id">Spécialité</label>
                <select id="specialty_id" name="specialty_id">
                    <option value="">Aucune</option>
                </select>
            </div>
        </div>

        <div class="ci-admin__form-row ci-admin__form-row--2">
            <div class="ci-form-group">
                <label for="name">Nom <span style="color: var(--ci-error);">*</span></label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($program['name'] ?? '') ?>" required placeholder="Ex: Master Génie Logiciel">
            </div>
            <div class="ci-form-group">
                <label for="level">Niveau <span style="color: var(--ci-error);">*</span></label>
                <select id="level" name="level" required>
                    <option value="">Sélectionnez</option>
                    <option value="Bac" <?= ($program['level'] ?? '') === 'Bac' ? 'selected' : '' ?>>Bac</option>
                    <option value="Bac+1" <?= ($program['level'] ?? '') === 'Bac+1' ? 'selected' : '' ?>>Bac+1</option>
                    <option value="Bac+2" <?= ($program['level'] ?? '') === 'Bac+2' ? 'selected' : '' ?>>Bac+2</option>
                    <option value="Bac+3" <?= ($program['level'] ?? '') === 'Bac+3' ? 'selected' : '' ?>>Bac+3</option>
                    <option value="Bac+4" <?= ($program['level'] ?? '') === 'Bac+4' ? 'selected' : '' ?>>Bac+4</option>
                    <option value="Bac+5" <?= ($program['level'] ?? '') === 'Bac+5' ? 'selected' : '' ?>>Bac+5</option>
                    <option value="Doctorat" <?= ($program['level'] ?? '') === 'Doctorat' ? 'selected' : '' ?>>Doctorat</option>
                    <option value="Autre" <?= ($program['level'] ?? '') === 'Autre' ? 'selected' : '' ?>>Autre</option>
                </select>
            </div>
        </div>

        <div class="ci-admin__form-row ci-admin__form-row--2">
            <div class="ci-form-group">
                <label for="duration">Durée</label>
                <input type="text" id="duration" name="duration" value="<?= htmlspecialchars($program['duration'] ?? '') ?>" placeholder="Ex: 2 ans">
            </div>
            <div class="ci-form-group">
                <label for="status">Statut</label>
                <select id="status" name="status">
                    <option value="active" <?= ($program['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Actif</option>
                    <option value="inactive" <?= ($program['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactif</option>
                </select>
            </div>
        </div>

        <div class="ci-form-group" style="margin-bottom: var(--ci-space-md);">
            <label for="description">Description</label>
            <textarea id="description" name="description" placeholder="Description de la formation..."><?= htmlspecialchars($program['description'] ?? '') ?></textarea>
        </div>

        <div class="ci-form-group" style="margin-bottom: var(--ci-space-md);">
            <label>Campus disponibles</label>
            <div class="ci-admin__checkbox-group">
                <?php foreach ($campuses as $campus): ?>
                    <label class="ci-admin__checkbox-label">
                        <input type="checkbox" name="campus_ids[]" value="<?= $campus['id'] ?>" <?= in_array($campus['id'], $programCampuses ?? []) ? 'checked' : '' ?>>
                        <?= htmlspecialchars($campus['institution_name'] . ' — ' . $campus['name'] . ' (' . $campus['city_name'] . ')') ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="ci-admin__form-actions">
            <a href="/admin/programs" class="ci-btn ci-btn--outline">Annuler</a>
            <button type="submit" class="ci-btn ci-btn--primary">
                <?= $program ? 'Enregistrer' : 'Créer' ?>
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const domainSelect = document.getElementById('domain_id');
    const specialtySelect = document.getElementById('specialty_id');
    const currentSpecialtyId = '<?= $program['specialty_id'] ?? '' ?>';

    domainSelect.addEventListener('change', async function() {
        const domainId = this.value;
        specialtySelect.innerHTML = '<option value="">Chargement...</option>';
        specialtySelect.disabled = true;

        if (!domainId) {
            specialtySelect.innerHTML = '<option value="">Aucune</option>';
            return;
        }

        try {
            const response = await fetch(`/api/specialties?domain_id=${domainId}`);
            const data = await response.json();
            
            let html = '<option value="">Aucune</option>';
            if (data.success && data.data) {
                data.data.forEach(s => {
                    const selected = s.id == currentSpecialtyId ? 'selected' : '';
                    html += `<option value="${s.id}" ${selected}>${s.name}</option>`;
                });
            }
            specialtySelect.innerHTML = html;
            specialtySelect.disabled = false;
        } catch (error) {
            specialtySelect.innerHTML = '<option value="">Erreur de chargement</option>';
        }
    });

    // Charger au démarrage si un domaine est sélectionné
    if (domainSelect.value) {
        domainSelect.dispatchEvent(new Event('change'));
    }
});
</script>
