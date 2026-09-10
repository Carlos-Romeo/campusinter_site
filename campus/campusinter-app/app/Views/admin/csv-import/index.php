<!-- Import CSV -->
<div class="ci-admin__form" style="max-width: 900px;">
    <h2 class="ci-admin__form-title">Import des formations via CSV</h2>

    <!-- Instructions -->
    <div style="background: var(--ci-info-bg); border-left: 4px solid var(--ci-info); padding: var(--ci-space-md); border-radius: var(--ci-radius-md); margin-bottom: var(--ci-space-xl);">
        <h3 style="font-size: 0.9375rem; font-weight: 600; color: var(--ci-info); margin-bottom: var(--ci-space-sm);">Format du fichier CSV</h3>
        <p style="font-size: 0.875rem; color: var(--ci-gray-700); margin-bottom: var(--ci-space-sm);">
            Le fichier doit être au format CSV avec sépoint-virgule (<code>;</code>).
        </p>
        <p style="font-size: 0.875rem; color: var(--ci-gray-700); margin-bottom: var(--ci-space-sm);">
            <strong>Colonnes obligatoires :</strong> nom, domaine, niveau
        </p>
        <p style="font-size: 0.875rem; color: var(--ci-gray-700);">
            <strong>Colonnes optionnelles :</strong> specialite, description, duree, campus, etablissement, ville
        </p>
    </div>

    <!-- Formulaire d'upload -->
    <form id="ci-csv-form" enctype="multipart/form-data">
        <?= \CampusInter\Helpers\Csrf::field() ?>

        <div class="ci-form-group" style="margin-bottom: var(--ci-space-md);">
            <label for="academic_year_id">Année académique <span style="color: var(--ci-error);">*</span></label>
            <select id="academic_year_id" name="academic_year_id" required>
                <option value="">Sélectionnez une année</option>
                <?php foreach ($academicYears as $year): ?>
                    <option value="<?= $year['id'] ?>"><?= htmlspecialchars($year['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="ci-form-group" style="margin-bottom: var(--ci-space-lg);">
            <label for="csv_file">Fichier CSV <span style="color: var(--ci-error);">*</span></label>
            <input type="file" id="csv_file" name="csv_file" accept=".csv" required>
        </div>

        <button type="submit" id="ci-analyze-btn" class="ci-btn ci-btn--secondary">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Analyser le fichier
        </button>
    </form>

    <!-- Résultats de l'analyse -->
    <div id="ci-csv-results" style="display: none; margin-top: var(--ci-space-xl);">
        <h3 style="font-size: 1rem; font-weight: 700; margin-bottom: var(--ci-space-lg);">Résultat de l'analyse</h3>

        <!-- Statistiques -->
        <div class="ci-admin__stats" style="margin-bottom: var(--ci-space-lg);">
            <div class="ci-admin__stat">
                <div class="ci-admin__stat-value" id="ci-total-rows">0</div>
                <div class="ci-admin__stat-label">Lignes totales</div>
            </div>
            <div class="ci-admin__stat">
                <div class="ci-admin__stat-value" id="ci-valid-rows" style="color: var(--ci-success);">0</div>
                <div class="ci-admin__stat-label">Lignes valides</div>
            </div>
            <div class="ci-admin__stat">
                <div class="ci-admin__stat-value" id="ci-duplicate-rows" style="color: var(--ci-warning);">0</div>
                <div class="ci-admin__stat-label">Doublons</div>
            </div>
            <div class="ci-admin__stat">
                <div class="ci-admin__stat-value" id="ci-error-rows" style="color: var(--ci-error);">0</div>
                <div class="ci-admin__stat-label">Erreurs</div>
            </div>
        </div>

        <!-- Erreurs -->
        <div id="ci-csv-errors" style="display: none; margin-bottom: var(--ci-space-lg);">
            <h4 style="font-size: 0.9375rem; font-weight: 600; color: var(--ci-error); margin-bottom: var(--ci-space-sm);">Erreurs détectées</h4>
            <div id="ci-errors-list" style="background: var(--ci-error-bg); border-radius: var(--ci-radius-md); padding: var(--ci-space-md); max-height: 300px; overflow-y: auto;"></div>
        </div>

        <!-- Aperçu -->
        <div id="ci-csv-preview" style="display: none; margin-bottom: var(--ci-space-lg);">
            <h4 style="font-size: 0.9375rem; font-weight: 600; margin-bottom: var(--ci-space-sm);">Aperçu des données</h4>
            <div class="ci-admin__table-wrapper" style="max-height: 300px; overflow-y: auto;">
                <table class="ci-admin__table" id="ci-preview-table">
                    <thead></thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <!-- Bouton d'import -->
        <div id="ci-import-section" style="display: none;">
            <button type="button" id="ci-import-btn" class="ci-btn ci-btn--accent ci-btn--lg">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                Importer les formations valides
            </button>
        </div>
    </div>

    <!-- Résultat import -->
    <div id="ci-import-result" style="display: none; margin-top: var(--ci-space-xl);"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('ci-csv-form');
    const analyzeBtn = document.getElementById('ci-analyze-btn');
    const importBtn = document.getElementById('ci-import-btn');
    const csrfToken = document.querySelector('input[name="_csrf_token"]')?.value || '';

    // Analyse du fichier
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(form);
        
        analyzeBtn.disabled = true;
        analyzeBtn.innerHTML = '<span class="ci-btn--loading"></span> Analyse en cours...';

        try {
            const response = await fetch('/admin/csv-import/analyze', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: formData,
            });

            const data = await response.json();

            if (data.success) {
                displayResults(data.data);
            } else {
                alert(data.message);
            }
        } catch (error) {
            alert('Erreur lors de l\'analyse du fichier.');
        } finally {
            analyzeBtn.disabled = false;
            analyzeBtn.innerHTML = '<svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg> Analyser le fichier';
        }
    });

    // Import
    if (importBtn) {
        importBtn.addEventListener('click', async () => {
            const academicYearId = document.getElementById('academic_year_id').value;
            if (!academicYearId) {
                alert('Veuillez sélectionner une année académique.');
                return;
            }

            if (!confirm('Voulez-vous vraiment importer les formations valides ?')) {
                return;
            }

            importBtn.disabled = true;
            importBtn.innerHTML = '<span class="ci-btn--loading"></span> Import en cours...';

            try {
                const formData = new FormData();
                formData.append('academic_year_id', academicYearId);
                formData.append('_csrf_token', csrfToken);

                const response = await fetch('/admin/csv-import/import', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: formData,
                });

                const data = await response.json();

                const resultDiv = document.getElementById('ci-import-result');
                resultDiv.style.display = 'block';

                if (data.success) {
                    resultDiv.innerHTML = `
                        <div style="background: var(--ci-success-bg); border-left: 4px solid var(--ci-success); padding: var(--ci-space-md); border-radius: var(--ci-radius-md);">
                            <p style="font-weight: 600; color: var(--ci-success);">${data.message}</p>
                            <p style="font-size: 0.875rem; color: var(--ci-gray-600); margin-top: 8px;">
                                <a href="/admin/programs" style="color: var(--ci-secondary);">Voir les formations →</a>
                            </p>
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div style="background: var(--ci-error-bg); border-left: 4px solid var(--ci-error); padding: var(--ci-space-md); border-radius: var(--ci-radius-md);">
                            <p style="font-weight: 600; color: var(--ci-error);">${data.message}</p>
                        </div>
                    `;
                }
            } catch (error) {
                alert('Erreur lors de l\'import.');
            } finally {
                importBtn.disabled = false;
                importBtn.innerHTML = '<svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg> Importer les formations valides';
            }
        });
    }

    function displayResults(data) {
        document.getElementById('ci-csv-results').style.display = 'block';
        document.getElementById('ci-total-rows').textContent = data.total_rows;
        document.getElementById('ci-valid-rows').textContent = data.valid_rows;
        document.getElementById('ci-duplicate-rows').textContent = data.duplicates;
        document.getElementById('ci-error-rows').textContent = data.errors;

        // Erreurs
        const errorsDiv = document.getElementById('ci-csv-errors');
        const errorsList = document.getElementById('ci-errors-list');
        if (data.errors_details && Object.keys(data.errors_details).length > 0) {
            errorsDiv.style.display = 'block';
            let html = '';
            for (const [line, errors] of Object.entries(data.errors_details)) {
                html += `<div style="margin-bottom: 8px;"><strong>Ligne ${line}:</strong> ${errors.join(', ')}</div>`;
            }
            errorsList.innerHTML = html;
        } else {
            errorsDiv.style.display = 'none';
        }

        // Aperçu
        const previewDiv = document.getElementById('ci-csv-preview');
        if (data.preview && data.preview.length > 0) {
            previewDiv.style.display = 'block';
            const table = document.getElementById('ci-preview-table');
            const headers = Object.keys(data.preview[0]).filter(h => !h.startsWith('_'));
            
            table.querySelector('thead').innerHTML = '<tr>' + headers.map(h => `<th>${h}</th>`).join('') + '</tr>';
            table.querySelector('tbody').innerHTML = data.preview.map(row => 
                '<tr>' + headers.map(h => `<td>${row[h] || ''}</td>`).join('') + '</tr>'
            ).join('');
        } else {
            previewDiv.style.display = 'none';
        }

        // Bouton import
        if (data.valid_rows > 0) {
            document.getElementById('ci-import-section').style.display = 'block';
        }
    }
});
</script>
