<!-- Confirmation -->
<section class="ci-section">
    <div class="ci-container">
        <div class="ci-confirmation">
            <!-- Icon -->
            <div class="ci-confirmation__icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <!-- Title -->
            <h1 class="ci-confirmation__title">Votre préinscription a bien été enregistrée</h1>

            <!-- Reference -->
            <div class="ci-confirmation__ref"><?= $reference ?></div>

            <!-- Details -->
            <div class="ci-confirmation__details">
                <div class="ci-confirmation__detail">
                    <span class="ci-confirmation__detail-label">Formation</span>
                    <span class="ci-confirmation__detail-value"><?= $programName ?></span>
                </div>
                <div class="ci-confirmation__detail">
                    <span class="ci-confirmation__detail-label">Campus</span>
                    <span class="ci-confirmation__detail-value"><?= $campusName ?></span>
                </div>
            </div>

            <!-- Message -->
            <p class="ci-confirmation__message">
                Un email de confirmation vous a été envoyé.<br>
                Conservez votre référence pour suivre votre candidature.
            </p>

            <!-- CTA -->
            <a href="/" class="ci-btn ci-btn--primary ci-btn--lg">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Retour aux formations
            </a>
        </div>
    </div>
</section>
