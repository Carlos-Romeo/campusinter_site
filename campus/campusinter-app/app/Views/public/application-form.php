<!-- Application Form -->
<section class="ci-section">
    <div class="ci-container">
        <div style="max-width: 700px; margin: 0 auto;">
            <!-- Breadcrumb -->
            <nav style="margin-bottom: var(--ci-space-lg); font-size: 0.875rem; color: var(--ci-gray-500);">
                <a href="/" style="color: var(--ci-secondary);">Formations</a>
                <span style="margin: 0 var(--ci-space-sm);">/</span>
                <a href="/programme?id=<?= $program['id'] ?>" style="color: var(--ci-secondary);"><?= htmlspecialchars($program['name']) ?></a>
                <span style="margin: 0 var(--ci-space-sm);">/</span>
                <span>Préinscription</span>
            </nav>

            <!-- Header -->
            <div class="ci-card" style="margin-bottom: var(--ci-space-lg); text-align: center;">
                <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--ci-gray-900); margin-bottom: var(--ci-space-sm);">
                    Préinscription
                </h1>
                <p style="color: var(--ci-gray-600);">
                    <?= htmlspecialchars($program['name']) ?> — <?= htmlspecialchars($campus['name']) ?>
                </p>
            </div>

            <!-- Steps -->
            <div class="ci-steps">
                <div class="ci-step ci-step--active" data-ci-step-indicator="1">
                    <span class="ci-step__number">1</span>
                    <span>Identité</span>
                </div>
                <div class="ci-step__separator"></div>
                <div class="ci-step" data-ci-step-indicator="2">
                    <span class="ci-step__number">2</span>
                    <span>Parcours</span>
                </div>
                <div class="ci-step__separator"></div>
                <div class="ci-step" data-ci-step-indicator="3">
                    <span class="ci-step__number">3</span>
                    <span>Documents</span>
                </div>
                <div class="ci-step__separator"></div>
                <div class="ci-step" data-ci-step-indicator="4">
                    <span class="ci-step__number">4</span>
                    <span>Confirmation</span>
                </div>
            </div>

            <!-- Form -->
            <div class="ci-card">
                <form id="ci-application-form" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="ci-program-id" value="<?= $program['id'] ?>">
                    <input type="hidden" id="ci-campus-id" value="<?= $campus['id'] ?>">

                    <!-- Honeypot -->
                    <div style="position: absolute; left: -9999px;" aria-hidden="true">
                        <input type="text" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <!-- Étape 1: Identité -->
                    <div class="ci-form-step" data-ci-step-content="1">
                        <h2 style="font-size: 1.125rem; font-weight: 700; margin-bottom: var(--ci-space-lg); color: var(--ci-gray-900);">
                            Informations personnelles
                        </h2>

                        <div class="ci-form-group" style="margin-bottom: var(--ci-space-md);">
                            <label for="first_name">Prénom <span style="color: var(--ci-error);">*</span></label>
                            <input type="text" id="first_name" name="first_name" required autocomplete="given-name" placeholder="Votre prénom">
                        </div>

                        <div class="ci-form-group" style="margin-bottom: var(--ci-space-md);">
                            <label for="last_name">Nom <span style="color: var(--ci-error);">*</span></label>
                            <input type="text" id="last_name" name="last_name" required autocomplete="family-name" placeholder="Votre nom">
                        </div>

                        <div class="ci-form-group" style="margin-bottom: var(--ci-space-md);">
                            <label for="birth_date">Date de naissance</label>
                            <input type="date" id="birth_date" name="birth_date" autocomplete="bday">
                        </div>

                        <div class="ci-form-group" style="margin-bottom: var(--ci-space-md);">
                            <label for="email">Email <span style="color: var(--ci-error);">*</span></label>
                            <input type="email" id="email" name="email" required autocomplete="email" placeholder="votre.email@exemple.com">
                        </div>

                        <div class="ci-form-group" style="margin-bottom: var(--ci-space-lg);">
                            <label for="phone">Téléphone</label>
                            <input type="tel" id="phone" name="phone" autocomplete="tel" placeholder="+228 90 00 00 00">
                        </div>

                        <div class="ci-form-step-actions">
                            <span></span>
                            <button type="button" class="ci-btn ci-btn--secondary" data-ci-step="2">
                                Continuer
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Étape 2: Parcours -->
                    <div class="ci-form-step" data-ci-step-content="2" style="display: none;">
                        <h2 style="font-size: 1.125rem; font-weight: 700; margin-bottom: var(--ci-space-lg); color: var(--ci-gray-900);">
                            Parcours scolaire
                        </h2>

                        <div class="ci-form-group" style="margin-bottom: var(--ci-space-md);">
                            <label for="last_diploma">Dernier diplôme obtenu</label>
                            <input type="text" id="last_diploma" name="last_diploma" placeholder="Ex: Licence Informatique">
                        </div>

                        <div class="ci-form-group" style="margin-bottom: var(--ci-space-md);">
                            <label for="last_diploma_institution">Établissement du dernier diplôme</label>
                            <input type="text" id="last_diploma_institution" name="last_diploma_institution" placeholder="Ex: Université de Lomé">
                        </div>

                        <div class="ci-form-group" style="margin-bottom: var(--ci-space-md);">
                            <label for="last_diploma_year">Année du dernier diplôme</label>
                            <input type="number" id="last_diploma_year" name="last_diploma_year" min="1950" max="<?= date('Y') ?>" placeholder="Ex: 2024">
                        </div>

                        <div class="ci-form-group" style="margin-bottom: var(--ci-space-md);">
                            <label for="bac_year">Année du BAC</label>
                            <input type="number" id="bac_year" name="bac_year" min="1950" max="<?= date('Y') ?>" placeholder="Ex: 2021">
                        </div>

                        <div class="ci-form-group" style="margin-bottom: var(--ci-space-md);">
                            <label for="bac_series">Série du BAC</label>
                            <input type="text" id="bac_series" name="bac_series" placeholder="Ex: C, D, TI">
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--ci-space-md); margin-bottom: var(--ci-space-md);" class="ci-form-grid-2">
                            <div class="ci-form-group">
                                <label for="bac_average">Moyenne du BAC (/20)</label>
                                <input type="number" id="bac_average" name="bac_average" min="0" max="20" step="0.01" placeholder="Ex: 14.50">
                            </div>
                            <div class="ci-form-group">
                                <label for="last_diploma_average">Moyenne du diplôme (/20)</label>
                                <input type="number" id="last_diploma_average" name="last_diploma_average" min="0" max="20" step="0.01" placeholder="Ex: 13.75">
                            </div>
                        </div>

                        <div class="ci-form-group" style="margin-bottom: var(--ci-space-lg);">
                            <label for="message">Message (optionnel)</label>
                            <textarea id="message" name="message" placeholder="Vous pouvez laisser un message ou des informations complémentaires..."></textarea>
                        </div>

                        <div class="ci-form-step-actions">
                            <button type="button" class="ci-btn ci-btn--outline" data-ci-step="1">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                Retour
                            </button>
                            <button type="button" class="ci-btn ci-btn--secondary" data-ci-step="3">
                                Continuer
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Étape 3: Documents -->
                    <div class="ci-form-step" data-ci-step-content="3" style="display: none;">
                        <h2 style="font-size: 1.125rem; font-weight: 700; margin-bottom: var(--ci-space-lg); color: var(--ci-gray-900);">
                            Documents
                        </h2>
                        
                        <p style="color: var(--ci-gray-600); margin-bottom: var(--ci-space-lg); font-size: 0.875rem;">
                            Téléchargez vos documents pour compléter votre dossier. Formats acceptés : PDF, JPG, PNG (max 5 Mo chacun).
                        </p>

                        <div class="ci-form-group" style="margin-bottom: var(--ci-space-md);">
                            <label for="diploma_file">
                                Relevé de notes du dernier diplôme
                                <span style="color: var(--ci-gray-500); font-weight: normal;">(optionnel)</span>
                            </label>
                            <div class="ci-file-upload">
                                <input type="file" id="diploma_file" name="diploma_file" accept=".pdf,.jpg,.jpeg,.png" class="ci-file-input">
                                <label for="diploma_file" class="ci-file-label">
                                    <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <span class="ci-file-name">Choisir un fichier</span>
                                </label>
                            </div>
                        </div>

                        <div class="ci-form-group" style="margin-bottom: var(--ci-space-md);">
                            <label for="bac_file">
                                Attestation ou copie du BAC
                                <span style="color: var(--ci-gray-500); font-weight: normal;">(optionnel)</span>
                            </label>
                            <div class="ci-file-upload">
                                <input type="file" id="bac_file" name="bac_file" accept=".pdf,.jpg,.jpeg,.png" class="ci-file-input">
                                <label for="bac_file" class="ci-file-label">
                                    <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <span class="ci-file-name">Choisir un fichier</span>
                                </label>
                            </div>
                        </div>

                        <div class="ci-form-group" style="margin-bottom: var(--ci-space-lg);">
                            <label for="cv_file">
                                CV
                                <span style="color: var(--ci-gray-500); font-weight: normal;">(optionnel)</span>
                            </label>
                            <div class="ci-file-upload">
                                <input type="file" id="cv_file" name="cv_file" accept=".pdf,.jpg,.jpeg,.png" class="ci-file-input">
                                <label for="cv_file" class="ci-file-label">
                                    <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <span class="ci-file-name">Choisir un fichier</span>
                                </label>
                            </div>
                        </div>

                        <div class="ci-form-step-actions">
                            <button type="button" class="ci-btn ci-btn--outline" data-ci-step="2">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                Retour
                            </button>
                            <button type="button" class="ci-btn ci-btn--secondary" data-ci-step="4">
                                Continuer
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Étape 4: Confirmation -->
                    <div class="ci-form-step" data-ci-step-content="4" style="display: none;">
                        <h2 style="font-size: 1.125rem; font-weight: 700; margin-bottom: var(--ci-space-lg); color: var(--ci-gray-900);">
                            Vérifier vos informations
                        </h2>

                        <div id="ci-summary" style="margin-bottom: var(--ci-space-lg);"></div>

                        <div class="ci-payment-notice" style="background: var(--ci-primary-light, #E3F2FD); border-radius: 8px; padding: 1rem; margin-bottom: var(--ci-space-lg);">
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="var(--ci-primary, #1976D2)">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <strong style="display: block; margin-bottom: 0.25rem;">Frais de dossier : 25 000 FCFA</strong>
                                    <span style="font-size: 0.875rem; color: var(--ci-gray-600);">
                                        Après soumission, vous serez redirigé vers la page de paiement Mobile Money.
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="ci-form-step-actions">
                            <button type="button" class="ci-btn ci-btn--outline" data-ci-step="3">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                Retour
                            </button>
                            <button type="submit" id="ci-submit-btn" class="ci-btn ci-btn--accent ci-btn--lg">
                                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Confirmer et payer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<style>
/* Styles pour l'upload de fichiers */
.ci-file-upload {
    position: relative;
}

.ci-file-input {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
    z-index: 1;
}

.ci-file-label {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    border: 2px dashed var(--ci-gray-300, #ccc);
    border-radius: 8px;
    background: var(--ci-gray-50, #f9f9f9);
    color: var(--ci-gray-600, #666);
    cursor: pointer;
    transition: all 0.2s;
}

.ci-file-label:hover {
    border-color: var(--ci-primary, #1976D2);
    background: var(--ci-primary-light, #E3F2FD);
}

.ci-file-input:focus + .ci-file-label {
    outline: none;
    border-color: var(--ci-primary, #1976D2);
    box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.1);
}

.ci-file-input.has-file + .ci-file-label {
    border-color: var(--ci-success, #4CAF50);
    background: var(--ci-success-light, #E8F5E9);
}

.ci-file-name {
    flex: 1;
}

.ci-file-size {
    font-size: 0.75rem;
    color: var(--ci-gray-500, #999);
}
</style>

<script src="/assets/js/application-form.js"></script>
